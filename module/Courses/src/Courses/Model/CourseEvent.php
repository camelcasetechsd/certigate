<?php

namespace Courses\Model;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;
use Doctrine\Common\Collections\Criteria;
use Utilities\Service\Status;
use Courses\Entity\CourseEventUser;
use EStore\Service\ApiCalls;
use EStore\Service\OptionTypes;
use Zend\Http\Request;
use Utilities\Service\Random;
use Utilities\Service\Time;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;

/**
 * CourseEvent Model
 * 
 * Handles CourseEvent Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Object $objectUtilities
 * @property EStore\Service\Api $estoreApi
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property Translation\Service\Translator\TranslatorHandler $translatorHandler
 * 
 * @package courses
 * @subpackage model
 */
class CourseEvent
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Object 
     */
    protected $objectUtilities;

    /**
     *
     * @var EStore\Service\Api
     */
    protected $estoreApi;

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
     * @var Translation\Service\Translator\TranslatorHandler
     */
    protected $translatorHandler;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Utilities\Service\Object $objectUtilities
     * @param EStore\Service\Api $estoreApi
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Translation\Service\Translator\TranslatorHandler $translatorHandler
     */
    public function __construct($query, $objectUtilities, $estoreApi, $systemCacheHandler, $notification, $translatorHandler)
    {
        $this->query = $query;
        $this->objectUtilities = $objectUtilities;
        $this->estoreApi = $estoreApi;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->translatorHandler = $translatorHandler;
    }

    /**
     * Save course event
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param array $data ,default is empty array
     */
    public function save($courseEvent, $data = array())
    {
        $editFlag = false;
        if (empty($data)) {
            $editFlag = true;
        }
        $this->saveCourseEventOption($courseEvent, $data, $editFlag);
        $this->query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent->setStatus(Status::STATUS_ACTIVE), $data);
    }

    /**
     * Save course event option
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param array $data ,default is empty array
     * @param bool $editFlag ,default is bool false
     */
    public function saveCourseEventOption($courseEvent, $data = array(), $editFlag = false)
    {
        if ($editFlag === true) {
            $estoreApiEdge = ApiCalls::OPTION_VALUE_EDIT;
            $startDate = $courseEvent->getStartDate()->format("D, d M Y");
            $endDate = $courseEvent->getEndDate()->format("D, d M Y");
            $organizationId = $courseEvent->getAtp();
            $instructorId = $courseEvent->getAi();
            $courseId = $courseEvent->getCourse();
        }
        else {
            $estoreApiEdge = ApiCalls::OPTION_VALUE_ADD;
            $startDate = $data["startDate"];
            $endDate = $data["endDate"];
            $courseId = $data["course"];
            $organizationId = $data["atp"];
            $instructorId = $data["ai"];
        }
        $course = $this->query->find('Courses\Entity\Course', $courseId);
        $organization = $this->query->find('Organizations\Entity\Organization', $organizationId);
        $instructor = $this->query->find('Users\Entity\User', $instructorId);
        $languages = $this->estoreApi->getLanguageData();
        $languageId = reset($languages)["language_id"];
        $parameters = array(
            'option_name' => OptionTypes::COURSE_EVENT,
            'type' => 'select',
            'sort_order' => 1,
            'product_id' => $course->getProductId(),
            'required' => true,
            'quantity' => 9999999999,
            'subtract' => 1,
            'price' => $course->getPrice(),
            'price_prefix' => "",
            'points' => "",
            'points_prefix' => "",
            'weight' => "",
            'weight_prefix' => "",
            'option_description' => array(
                $languageId => array(
                    'name' => OptionTypes::COURSE_EVENT,
                )
            ),
            'option_value' => array(
                'image' => "",
                'sort_order' => 1,
                'option_value_description' => array(
                    $languageId => array(
                        'name' => "{$startDate} - {$endDate} By "
                        . "{$instructor->getFirstName()} {$instructor->getLastName()} At "
                        . "{$organization->getCommercialName()} {$organization->getCity()}",
                    )
                ),
            )
        );
        $queryParameters = array();
        if (!empty($courseEvent->getOptionValueId())) {
            $queryParameters["option_value_id"] = $courseEvent->getOptionValueId();
        }
        $responseContent = $this->estoreApi->callEdge(/* $edge = */ $estoreApiEdge, /* $method = */ Request::METHOD_POST, $queryParameters, $parameters);
        if (empty($courseEvent->getOptionValueId())) {
            $courseEvent->setOptionId($responseContent->optionId);
            $courseEvent->setOptionValueId($responseContent->optionValueId);
        }
    }

    /**
     * Set course Privileges
     * 
     * @access public
     * @param array $courses
     * @return array courseEvents with canRoll property added
     */
    public function setCourseEventsPrivileges($courses)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $currentUser = NULL;
        if ($auth->hasIdentity()) {
            $currentUser = $this->query->find('Users\Entity\User', $storage['id']);
        }
        foreach ($courses as $course) {
            $courseEvents = $course->getCourseEvents();
            $course->canDownload = false;
            $course->currentUserEnrolled = false;
            foreach ($courseEvents as $courseEvent) {
                $nonAuthorizedEnroll = false;
                $courseFull = false;
                $canEnroll = true;
                $canLeave = false;
                $enrolling = false;
                if ($auth->hasIdentity()) {
                    $courseEventAiId = $this->objectUtilities->getId($courseEvent->getAi());
                    if (in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) && $storage['id'] == $courseEventAiId) {
                        $nonAuthorizedEnroll = true;
                    }
                }
                if (!is_null($currentUser)) {
                    $courseEventUser = $this->query->findOneBy('Courses\Entity\CourseEventUser', array(
                        "user" => $currentUser,
                        "courseEvent" => $courseEvent,
                    ));
                    if (is_object($courseEventUser)) {
                        $canLeave = true;
                        if ($courseEventUser->getStatus() == Status::STATUS_INACTIVE) {
                            $enrolling = true;
                        }
                    }
                }
                if ($courseEvent->getStudentsNo() >= $courseEvent->getCapacity()) {
                    $courseFull = true;
                }
                if ($canLeave === true || $nonAuthorizedEnroll === true || $courseFull === true) {
                    $canEnroll = false;
                }
                $today = new \DateTime();
                $alreadyStarted = false;
                if ($courseEvent->getStartDate() <= $today) {
                    $alreadyStarted = true;
                }
                $courseEvent->alreadyStarted = $alreadyStarted;
                $courseEvent->enrolling = $enrolling;
                $courseEvent->canEnroll = $canEnroll;
                $courseEvent->isFull = $courseFull;
                $courseEvent->canLeave = $canLeave;
                if ($course->canDownload === false && $canLeave === true) {
                    $course->canDownload = $course->currentUserEnrolled = true;
                }
                $courseEvent->startDateIso = $courseEvent->getStartDate()->format(DATE_ISO8601);
                $courseEvent->endDateIso = $courseEvent->getEndDate()->format(DATE_ISO8601);
                $courseEvent->timeZone = Time::DEFAULT_TIME_ZONE_ID;
            }
            $canEvaluate = false;
            $criteria = Criteria::create();
            $expr = Criteria::expr();
            $criteria->andWhere($expr->eq("user", $currentUser));
            if (is_object($course->getEvaluation()) && $auth->hasIdentity() && $course->getEvaluation()->getStatus() == Status::STATUS_ACTIVE && $course->currentUserEnrolled === true && $course->getEvaluation()->getVotes()->matching($criteria)->isEmpty() && (in_array(Role::STUDENT_ROLE, $storage['roles']) || in_array(Role::ADMIN_ROLE, $storage['roles']))
            ) {
                $canEvaluate = true;
            }
            $course->canEvaluate = $canEvaluate;
            $courseEvents = $this->objectUtilities->prepareForDisplay($courseEvents);
        }
        return $courses;
    }

    /**
     * Leave course event
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param Users\Entity\User $user
     */
    public function leaveCourse($courseEvent, $user)
    {
        $studentsNo = $courseEvent->getStudentsNo();
        $studentsNo--;
        $courseEvent->setStudentsNo($studentsNo);
        $this->query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent);

        $courseEventUser = $this->query->findOneBy('Courses\Entity\CourseEventUser', /* $criteria = */ array(
            "user" => $user->getId(),
            "courseEvent" => $courseEvent->getId(),
        ));
        $this->query->remove($courseEventUser);
    }

    /**
     * Enroll course
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param Users\Entity\User $user
     * @param string $redirectBackUrl
     * @return string redirect url
     * @throws \Exception Capacity exceeded
     * @throws \Exception Adding course to cart failed
     */
    public function enrollCourse($courseEvent, $user, $redirectBackUrl)
    {
        $existingCourseEventUser = $this->query->findOneBy('Courses\Entity\CourseEventUser', array(
            "user" => $user,
            "courseEvent" => $courseEvent,
        ));
        if (is_null($existingCourseEventUser)) {
            $studentsNo = $courseEvent->getStudentsNo();
            $studentsNo++;

            $capacity = $courseEvent->getCapacity();
            if ($capacity < $studentsNo) {
                throw new \Exception("Capacity exceeded");
            }

            $courseEvent->setStudentsNo($studentsNo);
            $this->query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent);

            $random = new Random();
            $courseEventUser = new CourseEventUser();
            $token = $random->getRandomUniqueName();
            $courseEventUserData = array(
                "status" => Status::STATUS_INACTIVE,
                "user" => $user,
                "courseEvent" => $courseEvent,
                "token" => $token
            );
        }
        else {
            $token = $existingCourseEventUser->getToken();
        }

        $parameters = array(
            'product_id' => $courseEvent->getCourse()->getProductId(),
            'quantity' => 1,
            'option' => array(
                $courseEvent->getOptionId() => $courseEvent->getOptionValueId(),
                'redirectUrl' => $redirectBackUrl . "/" . $token
            ),
        );
        $responseContent = $this->estoreApi->callEdge(/* $edge = */ ApiCalls::CART_ADD, /* $method = */ Request::METHOD_POST, /* $queryParameters = */ array(), $parameters);
        if (property_exists($responseContent, "success")) {
            if (is_null($existingCourseEventUser)) {
                $this->query->setEntity('Courses\Entity\CourseEventUser')->save($courseEventUser, $courseEventUserData);
            }
            return $responseContent->redirectUrl;
        }
        throw new \Exception("Adding course to cart failed");
    }

    /**
     * Approve course enroll
     * 
     * @access public
     * @param string $token
     */
    public function approveEnroll($token)
    {
        $existingCourseEventUser = $this->query->findOneBy('Courses\Entity\CourseEventUser', array(
            "token" => $token,
        ));
        if (!is_null($existingCourseEventUser)) {
            $existingCourseEventUser->setToken("")->setStatus(Status::STATUS_ACTIVE);
            $this->query->setEntity('Courses\Entity\CourseEventUser')->save($existingCourseEventUser);
        }
    }

    /**
     * Validate courseEvent form
     * 
     * @access public
     * @param Courses\Form\CourseEventForm $form
     * @param array $data
     * @param Courses\Entity\CourseEvent $courseEvent ,default is null
     * @param bool $isEditForm ,default is true
     * @return bool custom validation result
     */
    public function validateForm($form, $data, $courseEvent = null, $isEditForm = true)
    {
        $isCustomValidationValid = true;
        if ((int) $data['capacity'] < (int) $data['studentsNo']) {
            $form->get('capacity')->setMessages(array("Capacity should be higher than enrolled students number"));
            $isCustomValidationValid = false;
        }
        $endDate = strtotime(str_replace('/', '-', $data['endDate']));
        $startDate = strtotime(str_replace('/', '-', $data['startDate']));
        if ($endDate < $startDate) {
            $form->get('endDate')->setMessages(array("End date should be after Start date"));
            $isCustomValidationValid = false;
        }
        // retrieve old data if custom validation failed to pass
        if ($isCustomValidationValid === false && !is_null($courseEvent)) {
            $courseEvent->exchangeArray($data);
            $form->bind($courseEvent, /* $flags = */ FormInterface::VALUES_NORMALIZED, $isEditForm);
        }
        return $isCustomValidationValid;
    }

    /**
     * Get course events listing criteria
     * 
     * @access public
     * @param int $trainingManagerId ,default is false
     * @param int $courseId ,default is false
     * @return Criteria listing criteria
     */
    public function getListingCriteria($trainingManagerId = false, $courseId = false)
    {
        if ($trainingManagerId === false) {
            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();
            if ($auth->hasIdentity()) {
                if (in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles'])) {
                    $trainingManagerId = $storage['id'];
                }
            }
        }
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        if (!empty($trainingManagerId)) {
            $atpsArray = $this->query->setEntity(/* $entityName = */'Organizations\Entity\Organization')->entityRepository->getOrganizationsBy(/* $userIds = */ array($trainingManagerId));
            $criteria->andWhere($expr->in("atp", $atpsArray));
        }
        if (!empty($courseId)) {
            $course = $this->query->find('Courses\Entity\Course', $courseId);
            $criteria->andWhere($expr->eq("course", $course));
        }
        return $criteria;
    }

    /**
     * Send calendar alert
     * 
     * @access public
     * @param string $url
     * @param array $userData
     * @return array mail result message
     * 
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    public function sendCalendarAlert($url, $userData)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        $templateParameters = array(
            "userData" => $userData,
            "url" => $url,
        );

        $mailArray = array(
            'to' => $userData["email"],
            'from' => $from,
            'templateName' => MailTempates::NEW_CALENDAR_EVENT_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::NEW_CALENDAR_EVENT_SUBJECT,
        );
        $this->notification->notify($mailArray);

        $data["message"] = $this->translatorHandler->translate("Mail will be sent shortly!");
        return $data;
    }

    public function prepareCourseOccurrences($courseEvents)
    {
        foreach ($courseEvents as $event) {
            $event->startDate = $event->startDate->format('d-m-Y');
            $event->endDate = $event->endDate->format('d-m-Y');
            $event->name = $event->getCourse()->getName();
            $event->course_id = $event->getCourse()->getId();
        }
        return $courseEvents;
    }

}
