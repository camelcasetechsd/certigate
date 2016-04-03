<?php

namespace CMS\Model;

use Utilities\Service\Query\Query;
use Doctrine\Common\Collections\Criteria;
use Zend\Authentication\AuthenticationService;
use Utilities\Service\MessageTypes;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;

/**
 * PressReleaseSubscription Model
 * 
 * Handles PressReleaseSubscription Entity related business
 * 
 * @property Query $query
 * @property Zend\Mvc\Router\RouteInterface $router
 * @property Mustache\View\Renderer $viewRenderer
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package cms
 * @subpackage model
 */
class PressReleaseSubscription
{

    /**
     *
     * @var Query 
     */
    protected $query;

    /**
     *
     * @var Zend\Mvc\Router\RouteInterface
     */
    protected $router;

    /**
     *
     * @var Mustache\View\Renderer
     */
    protected $viewRenderer;

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
     * @param Query $query
     * @param Zend\Mvc\Router\RouteInterface $router
     * @param Mustache\View\Renderer $viewRenderer
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $router, $viewRenderer, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->router = $router;
        $this->viewRenderer = $viewRenderer;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    /**
     * Get subscription status
     * 
     * @access public
     * @param array $userIds ,default is null
     * @return array subscriptions status
     */
    public function getSubscriptionsStatus($userIds = array())
    {
        $loggedInUserFlag = false;
        if (empty($userIds)) {
            $loggedInUserFlag = true;
            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();
            if ($auth->hasIdentity()) {
                $userIds[] = $storage['id'];
            }
        }
        $subscriptionsStatus = array();
        if (empty($userIds)) {
            return $subscriptionsStatus;
        }
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->in("user", $userIds));
        $subscriptions = $this->query->filter(/* $entityName = */ "CMS\Entity\PressReleaseSubscription", $criteria);

        // subscribed part
        foreach ($subscriptions as $subscription) {
            $userId = $subscription->getUser()->getId();
            $parameters = array();
            if ($loggedInUserFlag === false) {
                $parameters = array(
                    'token' => $subscription->getToken(),
                    'userId' => $userId,
                );
            }
            $subscriptionsStatus[$userId] = array(
                "isSubscribed" => true,
                "action" => $this->router->assemble($parameters, array('name' => 'cmsPressReleaseUnsubscribe')),
            );
        }
        // non subscribed part
        foreach ($userIds as $userId) {
            if (!array_key_exists($userId, $subscriptionsStatus)) {
                $subscriptionsStatus[$userId] = array(
                    "isSubscribed" => false,
                    "action" => $this->router->assemble(array(), array('name' => 'cmsPressReleaseSubscribe')),
                );
            }
        }

        return $subscriptionsStatus;
    }

    /**
     * Get subscription submission result HTML result
     * 
     * @access public
     * @param bool $status ,default is false
     * @param bool $unsubscribeFlag ,default is false
     * @param string $failureMessage ,default is false
     * @param string $successMessage ,default is false
     * 
     * @return array messages according to process result
     */
    public function getSubscriptionResult($status = false, $unsubscribeFlag = false, $failureMessage = false, $successMessage = false)
    {
        $unsubscribeText = "";
        if ($unsubscribeFlag === true) {
            $unsubscribeText = "un";
        }
        if ($status === false) {
            $messages = array(
                'type' => MessageTypes::DANGER,
                'message' => (empty($failureMessage)) ? "Failed to {$unsubscribeText}subscribe!" : $failureMessage
            );
        }
        else {
            $messages = array(
                'type' => MessageTypes::SUCCESS,
                'message' => (empty($successMessage)) ? "You have been {$unsubscribeText}subscribed successfully." : $successMessage
            );
        }
        return $messages;
    }

    /**
     * Get subscription for logged in user or the passed token
     * 
     * @access public
     * @param string $token
     * @param int $userId
     * @param array $subscriptionsStatus
     * 
     * @return array subscription if found and message if error occurred
     */
    public function getSubscription($token, $userId, $subscriptionsStatus)
    {
        $pressReleaseSubscription = null;
        $message = null;
        if (!empty($token) && !empty($userId)) {
            $criteria = array(
                'token' => $token,
                'user' => $userId
            );
        }
        elseif (!empty($subscriptionsStatus)) {
            $userId = key($subscriptionsStatus);
            $criteria = array(
                'user' => $userId
            );
        }

        if (!isset($criteria)) {
            $message = "Either a user should be logged in or a token should be passed";
        }
        else {
            $pressReleaseSubscription = $this->query->findOneBy(/* $entityName = */ "CMS\Entity\PressReleaseSubscription", $criteria);
        }
        if (!is_object($pressReleaseSubscription)) {
            $message = "No subscription exists with the passed data";
        }

        return array(
            "message" => $message,
            "pressReleaseSubscription" => $pressReleaseSubscription,
        );
    }

    /**
     * Notify subscribers
     *  
     * @access public
     * 
     * @param CMS\Entity\Page $pressRelease
     * @param array $subscriptions
     * @throws \Exception From email is not set
     */
    public function notifySubscribers($pressRelease, $subscriptions = array())
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

        $mailArray = array(
            'from' => $from,
            'templateName' => MailTempates::NEW_PRESS_RELEASE_TEMPLATE,
            'subject' => MailSubjects::NEW_PRESS_RELEASE_SUBJECT,
        );
        if(empty($subscriptions)){
            $subscriptions = $this->query->findAll(/* $entityName = */ "CMS\Entity\PressReleaseSubscription");
        }
        foreach ($subscriptions as $subscription) {
            $subscriptionUser = $subscription->getUser();
            $mailArray["to"] = $subscriptionUser->getEmail();
            $unsubscribeUrlParameters = array(
                'token' => $subscription->getToken(),
                'userId' => $subscriptionUser->getId(),
            );
            $templateParameters = array(
                "user" => $subscriptionUser,
                "pressRelease" => $pressRelease,
                "pressReleaseUrl" => $this->router->assemble(array("id" => $pressRelease->getId()), array('name' => 'cmsPressReleaseDetails', 'force_canonical' => true)),
                "unsubscribeUrl" => $this->router->assemble($unsubscribeUrlParameters, array('name' => 'cmsPressReleaseUnsubscribe', 'force_canonical' => true)),
            );
            $mailArray["templateParameters"] = $templateParameters;
            $this->notification->notify($mailArray);
        }
    }

}
