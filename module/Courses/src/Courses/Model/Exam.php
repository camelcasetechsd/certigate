<?php

namespace Courses\Model;

use System\Service\Cache\CacheHandler;
use Notifications\Service\Notification;
use Notifications\Service\MailTemplates;
use Zend\View\Helper\ServerUrl;
use Utilities\Service\Time;
use System\Service\Settings;
use Notifications\Service\MailSubjects;
use Courses\Entity\ExamBook;
use Organizations\Entity\OrganizationType;
use Utilities\Service\Status;
use Doctrine\Common\Collections\Criteria;

/**
 * Exam Model
 * 
 * Handles Exam Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package courses
 * @subpackage model
 */
class Exam
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

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
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $systemCacheHandler, Notification $notification)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    public function saveBookingRequest($data, $config)
    {
        $bookObj = new ExamBook();
// admin pending request
        $bookObj->setAdminStatus(ExamBook::ADMIN_PENDING);
        $this->query->setEntity('Courses\Entity\ExamBook')->save($bookObj, $data);

        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::TVTC_EMAIL, $settings)) {
            $tvtcMail = $settings[Settings::TVTC_EMAIL];
        }
        $toBeNotifiedArray = array();
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings) && array_key_exists(Settings::OPERATIONS_EMAIL, $settings)) {
            $toBeNotifiedArray[] = $settings[Settings::ADMIN_EMAIL];
            $toBeNotifiedArray[] = $settings[Settings::OPERATIONS_EMAIL];
        }

        if (isset($tvtcMail)) {
// send tvtc new mail
            $this->sendMail($bookObj, $tvtcMail);
        }
        if (count($toBeNotifiedArray) > 0) {
// send admin new mail
            $this->sendMail($bookObj, $toBeNotifiedArray, /* $notificationEmailFlag = */ true);
        }
    }

    /**
     * List exam requests
     * @access public
     * 
     * @param bool $isAdminUser
     * @param array $userData
     * @return array
     */
    public function listRequests($isAdminUser, $userData)
    {
        $criteria = Criteria::create();
        if ($isAdminUser === false) {
            $types = array(OrganizationType::TYPE_ATC_TITLE);
            $userIds = array($userData["id"]);
            $userOrganizations = $this->query->setEntity("Organizations\Entity\Organization")->entityRepository->getOrganizationsBy($userIds, $types, Status::STATUS_ACTIVE);
            $expr = Criteria::expr();
            $criteria->andWhere($expr->in("atc", $userOrganizations));
        }
        $requests = $this->query->filter('Courses\Entity\ExamBook', $criteria);
        foreach ($requests as $req) {
// showing tvtc status
            switch ($req->tvtcStatus) {
                case 1 :
                    $req->tvtcStatus = "Approved";
                    break;
                case 2 :
                    $req->tvtcStatus = "Declined";
                    break;
                case 3 :
                    $req->tvtcStatus = "Pending";
                    $req->isTvtcPending = 1;
                    break;
                default :
                    $req->tvtcStatus = "";
                    break;
            }
// showing admin status
            switch ($req->adminStatus) {
                case 1 :
                    $req->adminStatus = "Approved";
                    break;
                case 2 :
                    $req->adminStatus = "Declined";

                    break;
                case 3 :
                    $req->adminStatus = "Pending";
                    $req->isAdminPending = 1;
                    break;
            }
            $req->date = date_format($req->date, Time::DATE_FORMAT);
            $req->dateHj = date_format($req->dateHj, Time::DATE_FORMAT);
        }

        return $requests;
    }

    public function respondeToExamRequest($response, $requestId, $tvtcResponse = null, $token = null)
    {
        $request = $this->query->findOneBy('Courses\Entity\ExamBook', array(
            'id' => $requestId
        ));
// tvtc response 
        if ($tvtcResponse != null) {
            if ($this->validateTvtcToken($request, $token)) {
                $request->setTvtcStatus($response);
// set token equal null to prevent using it again
                $request->setToken(false);
                $this->query->save($request);
            }
            else {
                return false;
            }
        }
// admin response
        else {
            $request->setAdminStatus($response);
            $this->query->save($request);
        }

        return true;
    }

    /**
     * function meant to send 2 emails one to admin 
     * and the other to tvtc
     * @param int $request
     * @param string $to
     * @param bool $notificationEmailFlag ,default is false
     * @throws \Exception From email is not set
     */
    private function sendMail($request, $to, $notificationEmailFlag = false)
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
        $serverUrl = new ServerUrl();
        $serverUrlString = $serverUrl();
        $templateParameters = array(
            "request" => $request,
            "serverUrl" => $serverUrlString,
            "token" => $request->getToken()
        );

// if tctv mail
        if ($notificationEmailFlag === false) {
            $templateName = MailTemplates::EXAM_APPROVAL_REQUEST_TEMPLATE;
            $subject = MailSubjects::EXAM_APPROVAL_REQUEST_SUBJECT;
        }
// if admin mail
        else {
            $templateName = MailTemplates::NEW_EXAM_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_EXAM_NOTIFICATION_SUBJECT;
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
     * function to validate tvtc acceptance or rejection mail response token
     * @param Curses\Entity\ExamBook $requestObj 
     * @param string $token 
     * @return boolean
     */
    private function validateTvtcToken($requestObj, $token)
    {
        if ($requestObj->getToken() === $token) {
            return true;
        }
        return false;
    }

}
