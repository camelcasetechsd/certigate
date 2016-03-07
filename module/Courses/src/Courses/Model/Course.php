<?php

namespace Courses\Model;

use Utilities\Service\Status;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;

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
 * 
 * @package courses
 * @subpackage model
 */
class Course
{

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
     * @var Zend\Paginator\Paginator 
     */
    protected $paginator = NULL;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Courses\Model\Outline $outlineModel
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Versioning\Model\Version $version
     */
    public function __construct($query, $outlineModel, $systemCacheHandler, $notification, $version)
    {
        $this->query = $query;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "Courses\Entity\Course"));
        $this->outlineModel = $outlineModel;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->version = $version;
    }

    /**
     * Save course
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param array $data ,default is empty array
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
        $this->query->setEntity("Courses\Entity\Course")->save($course, $data, /* $flushAll = */ true);

        // remove not needed outlines        
        $this->outlineModel->cleanUpOutlines();

        if ($notifyAdminFlag === true) {
            $this->sendMail($userEmail, $editFlag);
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
     * Set current page
     * @param int $currentPage
     */
    public function setPage($currentPage)
    {
        $this->paginator->setCurrentPageNumber($currentPage);
    }

    /**
     * Set number of pages
     * @param int $numberPerPage
     */
    public function setNumberPerPage($numberPerPage)
    {
        $this->numberPerPage = $numberPerPage;
    }

    /**
     * Set number of items per page
     * @param int $itemsCountPerPage
     */
    public function setItemCountPerPage($itemsCountPerPage)
    {
        $this->paginator->setItemCountPerPage($itemsCountPerPage);
    }

    /**
     * Get number of pages
     * @return int number of pages
     */
    public function getNumberOfPages()
    {
        return (int) $this->paginator->count();
    }

    /**
     * Get pages range
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return array pages range
     */
    public function getPagesRange($currentPageNumber = null)
    {

        $numberOfPages = $this->getNumberOfPages();
        $pageNumbers = array();
        //create an array of page numbers
        if ($numberOfPages > 1) {
            $pageNumbers = range(1, $numberOfPages);


            foreach ($pageNumbers as $pageKey => &$pageNumber) {
                $pageNumber = array(
                    "pageNumber" => $pageKey + 1,
                    "isCurrent" => false
                );
            }

            if (empty($currentPageNumber)) {
                $currentPageNumber = 1;
            }
            $pageNumbers[$currentPageNumber - 1]["isCurrent"] = true;
        }
        return $pageNumbers;
    }

    /**
     * Get next page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int next page number
     */
    public function getNextPageNumber($currentPageNumber = null)
    {
        $nextPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1) {
            if (!empty($currentPageNumber)) {
                if ($currentPageNumber != $numberOfPages) {
                    $nextPageNumber += $currentPageNumber + 1;
                }
            }
            else {
                $nextPageNumber = 2;
            }
        }

        return $nextPageNumber;
    }

    /**
     * Get previous page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int previous page number
     */
    public function getPreviousPageNumber($currentPageNumber = null)
    {
        $previousPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1 && !empty($currentPageNumber)) {
            $previousPageNumber += $currentPageNumber - 1;
        }

        return $previousPageNumber;
    }

    /**
     * Get number of items per page
     * @return int number of items per page
     */
    public function getCurrentItems()
    {
        return $this->paginator;
    }

    /**
     * Set criteria
     * 
     * @access public
     * 
     * @param Doctrine\Common\Collections\Criteria $criteria
     */
    public function setCriteria($criteria)
    {
        $this->paginator->getAdapter()->setCriteria($criteria);
    }

}
