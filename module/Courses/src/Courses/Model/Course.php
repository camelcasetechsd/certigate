<?php

namespace Courses\Model;

use Utilities\Service\Status;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use EStore\Service\ApiCalls;
use Zend\Http\Request;
use EStore\Service\ProductTypes;
use Utilities\Service\Random;

/**
 * Course Model
 * 
 * Handles Course Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Courses\Model\Outline $outlineModel
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property Versioning\Model\Version $version
 * @property EStore\Service\Api $estoreApi
 * 
 * @package courses
 * @subpackage model
 */
class Course
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Courses\Model\Outline
     */
    protected $outlineModel;

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
     * @var Versioning\Model\Version
     */
    protected $version;

    /**
     *
     * @var EStore\Service\Api
     */
    protected $estoreApi;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Courses\Model\Outline $outlineModel
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Versioning\Model\Version $version
     * @param EStore\Service\Api $estoreApi
     */
    public function __construct($query, $outlineModel, $systemCacheHandler, $notification, $version, $estoreApi)
    {
        $this->query = $query;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "Courses\Entity\Course"));
        $this->outlineModel = $outlineModel;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->version = $version;
        $this->estoreApi = $estoreApi;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "Courses\Entity\Course"));
    }

    /**
     * Save course
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param array $data ,default is empty array
     * @param bool $editFlag ,default is bool false
     * @param bool $isAdminUser ,default is bool false
     * @param string $userEmail ,default is null
     */
    public function save($course, $data = array(), $editFlag = false, $isAdminUser = false, $userEmail = null)
    {
        $notifyAdminFlag = false;
        Status::setStatus($course, $data, $editFlag);
        if ($editFlag === true) {
            $data = array();
        }

        if ($isAdminUser === false) {
            $course->setStatus(Status::STATUS_NOT_APPROVED);
            $notifyAdminFlag = true;
        }
        unset($data["outlines"]);
        $this->saveCourseProduct($course, $data, $editFlag);
        $this->query->setEntity("Courses\Entity\Course")->save($course, $data, /* $flushAll = */ true);

        // remove not needed outlines        
        $this->outlineModel->cleanUpOutlines();

        if ($notifyAdminFlag === true) {
            $this->sendMail($userEmail, $editFlag);
        }
    }

    /**
     * Save course product
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param array $data ,default is empty array
     * @param bool $editFlag ,default is bool false
     */
    public function saveCourseProduct($course, $data = array(), $editFlag = false)
    {
        if ($editFlag === true) {
            $estoreApiEdge = ApiCalls::PRODUCT_EDIT;
            $name = $course->getName();
            $price = $course->getPrice();
            $brief = $course->getBrief();
        }
        else {
            $estoreApiEdge = ApiCalls::PRODUCT_ADD;
            $name = $data["name"];
            $price = $data["price"];
            $brief = $data["brief"];
        }
        $languages = $this->estoreApi->getLanguageData();
        $languageId = reset($languages)["language_id"];
        $random = new Random();
        $parameters = array(
            'model' => $name,
            'keyword' => $name . $random->getRandomUniqueName(),
            'price' => $price,
            'status' => ($course->getStatus() == Status::STATUS_ACTIVE) ? true : false,
            'shipping' => false,
            'quantity' => 9999999999,
            'product_store' => array(0),
            'product_description' => array(
                $languageId => array(
                    'name' => $name . " " . ProductTypes::COURSE,
                    'meta_title' => ProductTypes::COURSE,
                    'description' => $brief,
                    'tag' => "",
                    'meta_description' => "",
                    'meta_keyword' => "",
                )
            ),
            'sku' => "",
            'upc' => "",
            'ean' => "",
            'jan' => "",
            'isbn' => "",
            'mpn' => "",
            'location' => "",
            'minimum' => "",
            'subtract' => "",
            'stock_status_id' => "",
            'date_available' => "",
            'manufacturer_id' => "",
            'points' => "",
            'weight' => "",
            'weight_class_id' => "",
            'length' => "",
            'width' => "",
            'height' => "",
            'length_class_id' => "",
            'tax_class_id' => "",
            'sort_order' => "",
        );
        $queryParameters = array();
        if (!empty($course->getProductId())) {
            $queryParameters["product_id"] = $course->getProductId();
        }
        $responseContent = $this->estoreApi->callEdge(/* $edge = */ $estoreApiEdge, /* $method = */ Request::METHOD_POST, $queryParameters, $parameters);
        if (empty($course->getProductId())) {
            $course->setProductId($responseContent->productId);
        }
    }

    /**
     * Get course log entries
     * 
     * @access public
     * @param Courses\Entity\Course $course 
     * @return array log entries for the course
     */
    public function getLogEntries($course)
    {
        $courseArray = array($course);
        $courseLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $courseArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);

        $outlines = $course->getOutlines()->toArray();
        $outlinesLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $outlines, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);

        $resources = $course->getResources()->toArray();
        $resourcesLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $resources, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);

        $evaluation = $course->getEvaluation();
        $evaluationArray = array();
        $questions = array();
        if (is_object($evaluation) && count($evaluation->getQuestions()) > 0) {
            $evaluationArray[] = $evaluation;
            $questions = $evaluation->getQuestions()->toArray();
        }
        $questionsLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $questions, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);
        $evaluationLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $evaluationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);

        $hasPendingChanges = false;
        if (count($questionsLogs) > 0 || count($resourcesLogs) > 0 || count($outlinesLogs) > 0 || count($courseLogs) > 0) {
            $hasPendingChanges = true;
        }

        return array(
            "course" => $courseArray,
            "courseLogs" => $courseLogs,
            "outlines" => $outlines,
            "outlinesLogs" => $outlinesLogs,
            "resources" => $resources,
            "resourcesLogs" => $resourcesLogs,
            "evaluation" => $evaluationArray,
            "evaluationLogs" => $evaluationLogs,
            "questions" => $questions,
            "questionsLogs" => $questionsLogs,
            "hasPendingChanges" => $hasPendingChanges,
        );
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param bool $editFlag
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($userEmail, $editFlag)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );

        if ($editFlag === false) {
            $templateName = MailTempates::NEW_COURSE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_COURSE_NOTIFICATION_SUBJECT;
        }
        else {
            $templateName = MailTempates::UPDATED_COURSE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::UPDATED_COURSE_NOTIFICATION_SUBJECT;
        }

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
     * Filter courses
     * 
     * @access public
     * 
     * @param array $criteriaArray ,default is empty array
     */
    public function filterCourses($criteriaArray = array())
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        foreach ($criteriaArray as $fieldName => $fieldValue) {
            $criteria->andWhere($expr->eq($fieldName, $fieldValue));
        }
        $this->setCriteria($criteria);
    }

}
