<?php

namespace Courses\Model;

use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use Courses\Entity\PublicQuote;
use Courses\Entity\PrivateQuote;
use Courses\Form\PublicQuoteReservationForm;
use Courses\Form\PrivateQuoteReservationForm;
use Utilities\Service\Status;
use Utilities\Form\FormButtons;
use System\Service\Settings;
use Notifications\Service\MailTemplates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Courses\Form\PublicQuoteForm;
use Courses\Form\PrivateQuoteForm;
use Zend\Form\FormInterface;
use Utilities\Service\Object;

/**
 * Quote Model
 * 
 * Handles Quote common business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Service\Translator\TranslatorHandler $translationHandler
 * @property Utilities\Service\View\FormView $formView
 * @property Courses\Service\QuoteGenerator $quoteGenerator
 * @property array $quoteConfig
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property string $currentType
 * @property Courses\Model\CourseEvent $courseEventModel
 * @property Utilities\Service\Object $objectUtilities
 * @property Translation\Service\Locale\Locale $applicationLocale
 * 
 * @package courses
 * @subpackage model
 */
class Quote
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;

    /**
     *
     * @var Translation\Service\Translator\TranslatorHandler
     */
    protected $translationHandler;

    /**
     *
     * @var Utilities\Service\View\FormView
     */
    protected $formView;

    /**
     *
     * @var Courses\Service\QuoteGenerator
     */
    protected $quoteGenerator;

    /**
     *
     * @var array
     */
    protected $quoteConfig;

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     *
     * @var string
     */
    protected $currentType;

    /**
     *
     * @var Courses\Model\CourseEvent
     */
    protected $courseEventModel;

    /**
     *
     * @var Utilities\Service\Object
     */
    protected $objectUtilities;

    /**
     *
     * @var Translation\Service\Locale\Locale
     */
    protected $applicationLocale;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Translator\TranslatorHandler $translationHandler
     * @param Utilities\Service\View\FormView $formView
     * @param Courses\Service\QuoteGenerator $quoteGenerator
     * @param array $quoteConfig
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Courses\Model\CourseEvent $courseEventModel
     * @param Utilities\Service\Object $objectUtilities
     * @param Translation\Service\Locale\Locale $applicationLocale
     */
    public function __construct($query, $translationHandler, $formView, $quoteGenerator, $quoteConfig, $systemCacheHandler, $notification, $courseEventModel, $objectUtilities, $applicationLocale)
    {
        $this->query = $query;
        $this->translationHandler = $translationHandler;
        $this->formView = $formView;
        $this->quoteGenerator = $quoteGenerator;
        $this->quoteConfig = $quoteConfig;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->courseEventModel = $courseEventModel;
        $this->objectUtilities = $objectUtilities;
        $this->applicationLocale = $applicationLocale;
    }

    /**
     * Set current type
     * 
     * @access public
     * @param string $currentType
     * 
     * @return \Courses\Model\Quote
     */
    public function setCurrentType($currentType)
    {
        $this->currentType = $currentType;
        return $this;
    }

    /**
     * Get current type
     * 
     * @access public
     * @return string currentType
     */
    public function getCurrentType()
    {
        return $this->currentType;
    }

    /**
     * Save quote
     * 
     * @access public
     * @param mixed $quote
     * @param Utilities\Form\Form $form
     * @param array $data ,default is empty array
     * @param string $type ,default is public quote type
     * @param bool $editFlag ,default is bool false
     * @param bool $isAdminUser ,default is bool false
     * @param string $userEmail ,default is null
     */
    public function save($quote, $form, $data = array(), $type = PublicQuote::QUOTE_TYPE, $editFlag = false, $isAdminUser = false, $userEmail = null)
    {
        $isCancelled = $this->isQuoteCancelled($data, $isAdminUser);

        if ($isCancelled === false) {
            $formData = $form->getData(FormInterface::VALUES_AS_ARRAY);
            if (array_key_exists("wireTransfer", $formData)) {
                $quote->setWireTransfer($formData["wireTransfer"]);
            }
        }
        $this->setQuoteStatus($quote, $data, $isAdminUser);

        if ($isCancelled === false) {
            $quoteModel = $this->quoteGenerator->getModel($type);
            $quoteModel->preSave($quote, $data);
        }

        $quoteData = array();
        if ($editFlag === false) {
            $quoteData = $data;
        }

        $quoteArray = $this->objectUtilities->prepareForSave(array($quote));
        $quote = reset($quoteArray);
        $this->query->setEntity($this->getQuoteEntityClass($type))->save($quote, $quoteData);

        if ($isCancelled === false) {
            $quoteModel->postSave($quote, $data);
        }
        else {
            $this->releaseReservedSeats($quote, $type, /* $onlyReleaseSeats = */ true);
        }
        $this->sendMail($type, $userEmail, $isAdminUser, $quote);
    }

    /**
     * Set quote status
     * 
     * @access public
     * @param mixed $quote
     * @param array $data ,defaul is empty array
     * @param bool $isAdminUser ,defaul is false
     */
    public function setQuoteStatus($quote, $data = array(), $isAdminUser = false)
    {
        if (array_key_exists(FormButtons::RESERVE_BUTTON, $data)) {
            $quote->setStatus(Status::STATUS_PENDING_PRICING);
        }
        elseif (array_key_exists(FormButtons::CANCEL_BUTTON, $data)) {
            $quote->setStatus(Status::STATUS_CANCELLED);
        }
        elseif (array_key_exists(FormButtons::PROCESS_BUTTON, $data)) {
            $quote->setStatus(Status::STATUS_PENDING_PAYMENT);
        }
        elseif (array_key_exists(FormButtons::DECLINE_BUTTON, $data)) {
            if ($isAdminUser === true) {
                $quote->setStatus(Status::STATUS_PENDING_REPAYMENT);
            }
            else {
                $quote->setStatus(Status::STATUS_CANCELLED);
            }
        }
        elseif (array_key_exists(FormButtons::ACCEPT_BUTTON, $data)) {
            if ($isAdminUser === true) {
                $quote->setStatus(Status::STATUS_ACTIVE);
            }
            else {
                $quote->setStatus(Status::STATUS_PENDING_REVIEW);
            }
        }
    }

    /**
     * Prepare quote for display
     * 
     * @access public
     * @param mixed $quote
     * @param string $type
     */
    public function prepareQuoteForDisplay($quote, $type)
    {
        $quote->isPendingPricing = false;
        $quote->isPendingPayment = false;
        $quote->isPendingRepayment = false;
        $quote->isPendingReview = false;
        $quote->isActive = false;
        $quote->isPriceSet = true;
        $quote->isWireTransferSet = true;

        $quote->existingCourseEvent = true;

        $quote->isPublic = false;
        $quote->isPrivate = false;
        $status = $quote->getStatus();
        switch ($status) {
            case Status::STATUS_PENDING_PRICING:
                $quote->isPendingPricing = true;
                if ($type == PrivateQuote::QUOTE_TYPE) {
                    $quote->existingCourseEvent = false;
                }
                $quote->isPriceSet = false;
                $quote->isWireTransferSet = false;
                break;
            case Status::STATUS_PENDING_PAYMENT:
                $quote->isPendingPayment = true;
                $quote->isWireTransferSet = false;
                break;
            case Status::STATUS_PENDING_REVIEW:
                $quote->isPendingReview = true;
                break;
            case Status::STATUS_PENDING_REPAYMENT:
                $quote->isPendingRepayment = true;
                break;
            case Status::STATUS_ACTIVE:
                $quote->isActive = true;
                break;
            case Status::STATUS_CANCELLED:
                $quote->isPriceSet = false;
                $quote->isWireTransferSet = false;
                break;
        }
        switch ($type) {
            case PrivateQuote::QUOTE_TYPE:
                $quote->isPrivate = true;
                break;
            case PublicQuote::QUOTE_TYPE:
                $quote->isPublic = true;
                break;
        }

        $quote->total = $this->getQuoteTotalPrice($quote, $type);

        $quoteArray = $this->objectUtilities->prepareForDisplay(array($quote));
        $quote = reset($quoteArray);
    }

    /**
     * Get quote total price
     * 
     * @access public
     * @param mixed $quote
     * @param string $type
     * 
     * @return int quote total price
     */
    public function getQuoteTotalPrice($quote, $type)
    {
        $total = 0;
        if ($type == PublicQuote::QUOTE_TYPE) {
            $discount = $quote->getDiscount();
            $seatsNo = $quote->getSeatsNo();
            $unitPrice = $quote->getUnitPrice();
            if (is_numeric($unitPrice) && is_numeric($seatsNo)) {
                $total = ((float) $unitPrice * (int) $seatsNo) - (float) $discount;
            }
        }
        else {
            $discount = $quote->getDiscount();
            $price = $quote->getPrice();
            if (is_numeric($price)) {
                $total = (float) $price - (float) $discount;
            }
        }
        return number_format($total, 2);
    }

    /**
     * Prepare quote course data for display
     * 
     * @access public
     * @param mixed $quote
     * @param string $type
     * 
     * @return Courses\Entity\Course prepared course
     */
    public function prepareQuoteCourseForDisplay($quote, $type)
    {
        if ($type === PrivateQuote::QUOTE_TYPE) {
            $course = $quote->getCourse();
        }
        else {
            $course = $quote->getCourseEvent()->getCourse();
        }
        $courseArray = array($course);

        $preparedCourseArray = $this->courseEventModel->setCourseEventsPrivileges($this->objectUtilities->prepareForDisplay($courseArray));
        return reset($preparedCourseArray);
    }

    /**
     * Get courses with corresponding forms
     * 
     * @access public
     * @param array $courses
     * @param string $type
     * @param int $userId
     * @param string $actionUrl
     * @return array courses with forms
     */
    public function prepareReservationForms($courses, $type, $userId, $actionUrl)
    {
        $options = array("actionUrl" => $actionUrl, "user" => $userId);
        foreach ($courses as $course) {
            if ($type === PrivateQuote::QUOTE_TYPE) {
                $options["course"] = $course->getId();
                $options["query"] = $this->query;
                $form = new PrivateQuoteReservationForm(/* $name = */ "private_quote_reservation_" . $course->getId(), /* $options = */ $options);
                $course->formObject = $form;
                $course->formView = $this->formView->getFormView($course->formObject);
            }
            else {
                foreach ($course->getCourseEvents() as $courseEvent) {
                    if ($type === PublicQuote::QUOTE_TYPE) {
                        $options["courseEvent"] = $courseEvent->getId();
                        $form = new PublicQuoteReservationForm(/* $name = */ "public_quote_reservation_" . $courseEvent->getId(), /* $options = */ $options);
                        $courseEvent->formObject = $form;
                        $courseEvent->formView = $this->formView->getFormView($courseEvent->formObject);
                    }
                }
            }
        }
        return $courses;
    }

    /**
     * Get reservation form submitted
     * 
     * @access public
     * @param array $courses
     * @param string $type
     * @param array $data
     * @return Utilities\Form\Form submitted form
     */
    public function getReservationForm($courses, $type, $data)
    {
        $form = null;
        if ($type == PublicQuote::QUOTE_TYPE) {
            $courseEventId = $data["courseEvent"];
        }
        elseif ($type == PrivateQuote::QUOTE_TYPE) {
            $courseId = $data["course"];
        }
        foreach ($courses as $course) {
            if (isset($courseId)) {
                if ($course->getId() == $courseId) {
                    $form = $course->formObject;
                    break;
                }
            }
            elseif (isset($courseEventId)) {
                foreach ($course->getCourseEvents() as $courseEvent) {
                    if ($courseEvent->getId() == $courseEventId) {
                        $form = $courseEvent->formObject;
                        // break all loops
                        break 2;
                    }
                }
            }
        }
        return $form;
    }

    /**
     * Get quote form
     * 
     * @access public
     * @param string $type
     * @param int $id
     * @param int $userId
     * @return Utilities\Form\Form submitted form
     */
    public function getQuoteForm($type, $id, $userId)
    {
        $quoteEntityClass = $this->getQuoteEntityClass($type);
        $quote = $this->query->find($quoteEntityClass, $id);
        $options = array(
            'status' => $quote->getStatus()
        );
        if ($type == PublicQuote::QUOTE_TYPE) {
            $form = new PublicQuoteForm(/* $name = */ null, $options);
        }
        elseif ($type == PrivateQuote::QUOTE_TYPE) {
            $options["query"] = $this->query;
            $options["userId"] = $userId;
            $options["applicationLocale"] = $this->applicationLocale;
            $form = new PrivateQuoteForm(/* $name = */ null, $options);
        }
        $form->bind($quote);
        return $form;
    }

    /**
     * Is quote cancelled
     * 
     * @access public
     * @param array $data
     * @param bool $isAdminUser
     * @return bool is form valid
     */
    public function isQuoteCancelled($data, $isAdminUser)
    {
        $isCancelled = false;
        if (array_key_exists(FormButtons::CANCEL_BUTTON, $data) || ( $isAdminUser === false && array_key_exists(FormButtons::DECLINE_BUTTON, $data))) {
            $isCancelled = true;
        }
        return $isCancelled;
    }

    /**
     * Validate quote form
     * 
     * @access public
     * @param Utilities\Form\Form $form
     * @param array $data
     * @param string $type
     * @param bool $isAdminUser
     * @return bool is form valid
     */
    public function isQuoteFormValid($form, $quote, $data, $type, $isAdminUser)
    {
        $isValid = true;
        if ($this->isQuoteCancelled($data, $isAdminUser) === false) {
            $quoteModel = $this->quoteGenerator->getModel($type);
            $isValid = $form->isValid();
            $isValid &= $quoteModel->isQuoteFormValid($form, $quote, $data);
        }
        if ($isValid == false) {
            $quote->exchangeArray($data);
            $form->bind($quote);
        }
        return $isValid;
    }

    /**
     * Get quote form
     * 
     * @access public
     * @param string $type
     * @param int $id
     * @return Utilities\Form\Form submitted form
     */
    public function delete($type, $id)
    {
        $quoteEntityClass = $this->getQuoteEntityClass($type);
        $quote = $this->query->find($quoteEntityClass, $id);

        $this->query->setEntity($quoteEntityClass)->remove($quote);
        $this->releaseReservedSeats($quote, $type);
    }

    /**
     * Release reserved seats
     * 
     * @access public
     * @param mixed $quote
     * @param string $type
     * @param bool $onlyReleaseSeats no entity removal, just seats release ,default is false
     * @param bool $onlyRemove no seats release, just entity removal ,default is false
     */
    public function releaseReservedSeats($quote, $type, $onlyReleaseSeats = false, $onlyRemove = false)
    {
        $courseEvent = $quote->getCourseEvent();
        $this->query->setEntity("Courses\Entity\CourseEvent");
        if ($type == PublicQuote::QUOTE_TYPE && $onlyRemove === false) {
            $courseEvent->setStudentsNo((int) $courseEvent->getStudentsNo() - (int) $quote->getSeatsNo());
            $courseEventArray = $this->objectUtilities->prepareForSave(array($courseEvent));
            $courseEvent = reset($courseEventArray);
            $this->query->save($courseEvent);
        }
        elseif ($type == PrivateQuote::QUOTE_TYPE && $onlyReleaseSeats === false) {
            $this->query->remove($courseEvent);
        }
    }

    /**
     * Set form submitted
     * 
     * @access public
     * @param Utilities\Form\Form $form
     * @param array $courses
     * @param string $type
     * @param array $data
     */
    public function setForm($form, $courses, $type, $data)
    {
        if ($type == PublicQuote::QUOTE_TYPE) {
            $courseEventId = $data["courseEvent"];
        }
        elseif ($type == PrivateQuote::QUOTE_TYPE) {
            $courseId = $data["course"];
        }
        foreach ($courses as $course) {
            if (isset($courseId)) {
                if ($course->getId() == $courseId) {
                    $course->formObject = $form;
                    $course->formView = $this->formView->getFormView($course->formObject);
                    break;
                }
            }
            elseif (isset($courseEventId)) {
                foreach ($course->getCourseEvents() as $courseEvent) {
                    if ($courseEvent->getId() == $courseEventId) {
                        $courseEvent->formObject = $form;
                        $courseEvent->formView = $this->formView->getFormView($courseEvent->formObject);
                        // break all loops
                        break 2;
                    }
                }
            }
        }
        return $form;
    }

    /**
     * Cleanup cancelled quotes
     * Release reserved seats by public quotes
     * 
     * @access public
     */
    public function cleanup()
    {
        $publicQuotes = $this->getQuotes(/* $type = */ PublicQuote::QUOTE_TYPE, /* $status = */ Status::STATUS_CANCELLED, /* $lastModifiedDays = */ $this->quoteConfig["expireAfterDays"]);
        $privateQuotes = $this->getQuotes(/* $type = */ PrivateQuote::QUOTE_TYPE, /* $status = */ Status::STATUS_CANCELLED, /* $lastModifiedDays = */ $this->quoteConfig["expireAfterDays"]);
        $quotes = array_merge($publicQuotes, $privateQuotes);
        foreach ($quotes as $quote) {
            $this->query->remove($quote, /* $noFlush = */ true);
        }
        $this->query->entityManager->flush();

        foreach ($privateQuotes as $privateQuote) {
            $this->releaseReservedSeats($privateQuote, /* $type = */ PrivateQuote::QUOTE_TYPE);
        }
    }

    /**
     * Get quotes
     * 
     * @access public
     * @param string $type
     * @param int $status ,default is null
     * @param int $lastModifiedDays ,default is null
     * @return array
     */
    public function getQuotes($type, $status = null, $lastModifiedDays = null)
    {
        $entityName = $this->getQuoteEntityClass($type);
        $repository = $this->query->setEntity($entityName)->entityRepository;
        $queryBuilder = $repository->createQueryBuilder("pq");
        $parameters = array();

        $queryBuilder->select("pq");
        if (!is_null($status)) {
            $parameters['status'] = $status;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('pq.status', ":status"));
        }
        if (!is_null($lastModifiedDays)) {
            $lastModifiedDate = new \DateTime("- $lastModifiedDays days");
            $parameters['lastModifiedDays'] = $lastModifiedDate;
            $queryBuilder->andWhere($queryBuilder->expr()->lte('pq.modified', ":lastModifiedDays"));
        }

        if (count($parameters) > 0) {
            $queryBuilder->setParameters($parameters);
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Get quote entity class
     * 
     * @access public
     * @param string $type
     * @return string quote entity class
     */
    public function getQuoteEntityClass($type)
    {
        return "Courses\Entity\\{$type}Quote";
    }

    /**
     * Get translated quotes types
     * 
     * @access public
     * @return array translated quotes types
     */
    public function getTranslatedQuoteTypes()
    {
        $types = array(
            PublicQuote::QUOTE_TYPE,
            PrivateQuote::QUOTE_TYPE
        );
        return $this->translationHandler->getTranslatedArray($types);
    }

    /**
     * Filter quotes
     * 
     * @access public
     * 
     * @param bool $isAdminUser
     * @param int $userId
     * @param array $data
     */
    public function filterQuotes($isAdminUser, $userId, $data)
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        if ($isAdminUser === false) {
            $user = $this->query->find("Users\Entity\User", $userId);
            $criteria->andWhere($expr->eq("user", $user));
        }
        $entityName = $this->getQuoteEntityClass(PublicQuote::QUOTE_TYPE);
        $currentType = PublicQuote::QUOTE_TYPE;
        if (array_key_exists("type", $data) && !empty($data["type"])) {
            $entityName = $this->getQuoteEntityClass($data["type"]);
            $currentType = $data["type"];
        }
        $this->setCurrentType($currentType);
        $this->paginator = new Paginator(new PaginatorAdapter($this->query->setEntity($entityName), $entityName));
        $this->setCriteria($criteria);
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $type
     * @param string $userEmail
     * @param bool $isAdminUser
     * @param mixed $quote
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($type, $userEmail, $isAdminUser, $quote)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if ($isAdminUser === false && array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }
        elseif ($isAdminUser === true) {
            $to = $userEmail;
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }

        $mailData = $this->getMailData($type, $quote);
        $templateName = $mailData["templateName"];
        $templateParameters = $mailData["templateParameters"];
        $subject = $mailData["subject"];

        $mailArray = array(
            'to' => $to,
            'from' => $from,
            'templateName' => $templateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($mailArray);
    }

    /**
     * Get mail subjet, template and ..etc
     * 
     * @access private
     * @param string $type
     * @param mixed $quote
     * @return array mail data
     */
    private function getMailData($type, $quote)
    {
        $status = $quote->getStatus();
        switch ($status) {
            case Status::STATUS_PENDING_PRICING:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::ADMIN_PUBLIC_QUOTE_PENDING_PRICING_TEMPLATE;
                    $subject = MailSubjects::ADMIN_PUBLIC_QUOTE_PENDING_PRICING_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::ADMIN_PRIVATE_QUOTE_PENDING_PRICING_TEMPLATE;
                    $subject = MailSubjects::ADMIN_PRIVATE_QUOTE_PENDING_PRICING_SUBJECT;
                }
                break;
            case Status::STATUS_PENDING_PAYMENT:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::CLIENT_PUBLIC_QUOTE_PENDING_PAYMENT_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PUBLIC_QUOTE_PENDING_PAYMENT_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::CLIENT_PRIVATE_QUOTE_PENDING_PAYMENT_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PRIVATE_QUOTE_PENDING_PAYMENT_SUBJECT;
                }
                break;
            case Status::STATUS_PENDING_REVIEW:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::ADMIN_PUBLIC_QUOTE_PENDING_REVIEW_TEMPLATE;
                    $subject = MailSubjects::ADMIN_PUBLIC_QUOTE_PENDING_REVIEW_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::ADMIN_PRIVATE_QUOTE_PENDING_REVIEW_TEMPLATE;
                    $subject = MailSubjects::ADMIN_PRIVATE_QUOTE_PENDING_REVIEW_SUBJECT;
                }
                break;
            case Status::STATUS_PENDING_REPAYMENT:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::CLIENT_PUBLIC_QUOTE_PENDING_REPAYMENT_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PUBLIC_QUOTE_PENDING_REPAYMENT_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::CLIENT_PRIVATE_QUOTE_PENDING_REPAYMENT_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PRIVATE_QUOTE_PENDING_REPAYMENT_SUBJECT;
                }
                break;
            case Status::STATUS_ACTIVE:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::CLIENT_PUBLIC_QUOTE_READY_INVOICE_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PUBLIC_QUOTE_READY_INVOICE_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::CLIENT_PRIVATE_QUOTE_READY_INVOICE_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PRIVATE_QUOTE_READY_INVOICE_SUBJECT;
                }
                break;
            case Status::STATUS_CANCELLED:
                if ($type == PublicQuote::QUOTE_TYPE) {
                    $templateName = MailTemplates::CLIENT_PUBLIC_QUOTE_CANCELLED_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PUBLIC_QUOTE_CANCELLED_SUBJECT;
                }
                else {
                    $templateName = MailTemplates::CLIENT_PRIVATE_QUOTE_CANCELLED_TEMPLATE;
                    $subject = MailSubjects::CLIENT_PRIVATE_QUOTE_CANCELLED_SUBJECT;
                }
                break;
        }
        $user = $quote->getUser();
        $total = $this->getQuoteTotalPrice($quote, $type);
        $baseTemplateParameters = array(
            "userFullName" => $user->getFullName(),
            "userFullNameAr" => $user->getFullNameAr(),
            "courseName" => $quote->getCourseEvent()->getCourse()->getName(),
            "courseNameAr" => $quote->getCourseEvent()->getCourse()->getNameAr(),
            "discount" => $quote->getDiscount(),
            "total" => $total,
        );
        if ($type == PublicQuote::QUOTE_TYPE) {
            $templateParameters = array_merge($baseTemplateParameters, array(
                "seatsNo" => $quote->getSeatsNo(),
                "unitPrice" => $quote->getUnitPrice(),
            ));
        }
        else {
            $templateParameters = array_merge($baseTemplateParameters, array(
                "venue" => $quote->getVenue()->getName(),
                "date" => $quote->getPreferredDate()->format(Object::DATE_DISPLAY_FORMAT),
                "price" => $quote->getPrice(),
            ));
        }
        return array(
            "templateParameters" => $templateParameters,
            "templateName" => $templateName,
            "subject" => $subject
        );
    }

}
