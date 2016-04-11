<?php

namespace Courses\Model;

use Doctrine\Common\Collections\Criteria;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Courses\Form\CourseEventSubscriptionForm;
use Courses\Entity\CourseEventSubscription as CourseEventSubscriptionEntity;
use Utilities\Form\FormButtons;

/**
 * CourseEventSubscription Model
 * 
 * Handles CourseEventSubscription Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property array $courseEventSubscriptionConfig
 * 
 * @package courses
 * @subpackage model
 */
class CourseEventSubscription
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
     *
     * @var array
     */
    protected $courseEventSubscriptionConfig;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param array $courseEventSubscriptionConfig
     */
    public function __construct($query, $systemCacheHandler, $notification, $courseEventSubscriptionConfig)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->courseEventSubscriptionConfig = $courseEventSubscriptionConfig;
    }

    /**
     * Save/ remove course event subscription based on selected option
     * 
     * @access public
     * @param Courses\Entity\CourseEventSubscription $courseEventSubscription
     * @param array $data
     */
    public function process($courseEventSubscription, $data)
    {
        if (array_key_exists(FormButtons::SUBSCRIBE_BUTTON, $data)) {
            $method = "save";
        }
        elseif (array_key_exists(FormButtons::UNSUBSCRIBE_BUTTON, $data)) {
            $method = "remove";
        }
        $this->query->setEntity('Courses\Entity\CourseEventSubscription')->$method($courseEventSubscription);
    }

    /**
     * Get course event subscription
     * 
     * @access public
     * @param int $courseEventId
     * @param int $currentUserId
     * @return CourseEventSubscription course event subscription entity
     */
    public function getCourseEventSubscription($courseEventId, $currentUserId)
    {
        $criteria = array(
            "courseEvent" => $courseEventId,
            "user" => $currentUserId,
        );
        $courseEventSubscription = $this->query->findOneBy("Courses\Entity\CourseEventSubscription", $criteria);
        if (is_null($courseEventSubscription)) {
            $courseEventSubscription = new CourseEventSubscriptionEntity();
            $courseEventSubscription->exchangeArray(/* $data = */ $criteria);
        }
        else {
            $courseEventSubscription->setCourseEvent($courseEventSubscription->getCourseEvent()->getId());
            $courseEventSubscription->setUser($courseEventSubscription->getUser()->getId());
        }
        return $courseEventSubscription;
    }

    /**
     * Get course event subscription form
     * 
     * @access public
     * @param mixed $courseEvent id or object
     * @param mixed $currentUser id or object
     * @param bool $bindFlag ,default is true
     * @return CourseEventSubscriptionForm
     */
    public function getCourseEventSubscriptionForm($courseEvent, $currentUser, $bindFlag = true)
    {
        if (!is_object($currentUser) && is_numeric($currentUser)) {
            $currentUser = $this->query->find("Users\Entity\User", $currentUser);
        }
        if (!is_object($courseEvent) && is_numeric($courseEvent)) {
            $courseEvent = $this->query->find("Courses\Entity\CourseEvent", $courseEvent);
        }
        $options["courseEventId"] = $courseEvent->getId();
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->eq("user", $currentUser));
        $subscriptionsForCurrentUser = $courseEvent->getCourseEventSubscriptions()->matching($criteria);
        $options["isSubscribed"] = (count($subscriptionsForCurrentUser) > 0) ? true : false;
        $form = new CourseEventSubscriptionForm(/* $name = */ "course_event_subscription_" . $courseEvent->getId(), /* $options = */ $options);
        if ($bindFlag === true) {
            $courseEventSubscription = $this->getCourseEventSubscription(/* $courseEventId = */ $courseEvent->getId(), /* $currentUserId = */ $currentUser->getId());
            $form->bind($courseEventSubscription);
        }
        return $form;
    }

    /**
     * Notify course event subscribers
     * 
     * @access public
     */
    public function notifySubscribers()
    {
        $courseEventSubscriptions = $this->query->setEntity("Courses\Entity\CourseEventSubscription")->entityRepository->getCourseEventSubscriptions($this->courseEventSubscriptionConfig["periodicNotificationDays"]);
        foreach ($courseEventSubscriptions as $courseEventSubscription) {
            $user = $courseEventSubscription->getUser();
            $userData = array(
                "name" => $user->getFullName(),
                "nameAr" => $user->getFullNameAr(),
                "email" => $user->getEmail(),
            );
            $this->sendCalendarAlert($userData, /* $courseEvent = */ $courseEventSubscription->getCourseEvent());
        }
    }

    /**
     * Send calendar alert periodically
     * 
     * @access private
     * @param array $userData
     * @param Courses\Entity\CourseEvent $courseEvent
     * 
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendCalendarAlert($userData, $courseEvent)
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
            "courseName" => $courseEvent->getCourse()->getName(),
        );
        $mailArray = array(
            'to' => $userData["email"],
            'from' => $from,
            'templateName' => MailTempates::CALENDAR_EVENT_NOTIFICATION_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::CALENDAR_EVENT_NOTIFICATION_SUBJECT,
        );
        $this->notification->notify($mailArray);
    }

}
