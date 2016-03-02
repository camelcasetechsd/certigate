<?php

namespace CMS\Model;

use Utilities\Service\Query\Query;
use Doctrine\Common\Collections\Criteria;
use Zend\Authentication\AuthenticationService;
use Utilities\Service\MessageTypes;
use Zend\View\Model\ViewModel;

/**
 * PressReleaseSubscription Model
 * 
 * Handles PressReleaseSubscription Entity related business
 * 
 * @property Query $query
 * @property Zend\Mvc\Router\RouteInterface $router
 * @property Mustache\View\Renderer $viewRenderer
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
     * Set needed properties
     * 
     * @access public
     * @param Query $query
     * @param Zend\Mvc\Router\RouteInterface $router
     * @param Mustache\View\Renderer $viewRenderer
     */
    public function __construct($query, $router, $viewRenderer)
    {
        $this->query = $query;
        $this->router = $router;
        $this->viewRenderer = $viewRenderer;
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
        $subscribtions = $this->query->filter(/* $entityName = */ "CMS\Entity\PressReleaseSubscription", $criteria);

        // subscribed part
        foreach ($subscribtions as $subscribtion) {
            $userId = $subscribtion->getUser()->getId();
            $parameters = array();
            if ($loggedInUserFlag === false) {
                $parameters = array('token' => $subscribtion->getToken());
            }
            $subscriptionsStatus[$userId] = array(
                "isSubscribed" => true,
                "action" => $this->router->assemble($parameters, array('name' => 'noaccess')),
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
     * @param bool $message ,default is empty string
     * 
     * @return string HTML content for subscription result
     */
    public function getSubscriptionResultHTML($status = false, $unsubscribeFlag = false, $message = '')
    {
        $unsubscribeText = "";
        if ($unsubscribeFlag === true) {
            $unsubscribeText = "un";
        }
        if ($status === false) {
            $messages = array(
                'type' => MessageTypes::DANGER,
                'message' => (empty($message)) ? "Failed to {$unsubscribeText}subscribe!" : $message
            );
        }
        else {
            $messages = array(
                'type' => MessageTypes::SUCCESS,
                'message' => (empty($message)) ? "You have been {$unsubscribeText}subscribed successfully." : $message
            );
        }
        $variables = array(
            'messages' => $messages
        );
        $view = new ViewModel($variables);
        $view->setTemplate('layout/messages');

        return $this->viewRenderer->render($view);
    }

    /**
     * Get subscription for logged in user or the passed token
     * 
     * @access public
     * @param string $token
     * @param array $subscriptionsStatus
     * 
     * @return array subscription if found and message if error occurred
     */
    public function getSubscription($token, $subscriptionsStatus)
    {
        $pressReleaseSubscription = null;
        $message = null;
        if (!empty($token)) {
            $criteria = array(
                'token' => $token
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
        if (isset($pressReleaseSubscription) && !is_object($pressReleaseSubscription)) {
            $message = "No subscription exists with the passed data";
        }
        
        return array(
            "message" => $message,
            "pressReleaseSubscription" => $pressReleaseSubscription,
        );
    }

}
