<?php

namespace CMS\Model;

use Utilities\Service\Query\Query;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use CMS\Service\PageTypes;
use Utilities\Service\Status;
use Zend\Authentication\AuthenticationService;

/**
 * PressRelease Model
 * 
 * Handles PressRelease Entity related business
 * 
 * @property Query $query
 * @property Zend\Mvc\Router\RouteInterface $router
 * 
 * @package cms
 * @subpackage model
 */
class PressRelease
{

    use \Utilities\Service\Paginator\PaginatorTrait;

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
     * Set needed properties
     * 
     * @access public
     * @param Query $query
     * @param Query $router
     */
    public function __construct($query, $router)
    {
        $this->query = $query;
        $this->router = $router;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "CMS\Entity\Page"));
    }

    /**
     * Filter press releases
     * 
     * @access public
     */
    public function filterPressReleases()
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->eq("type", PageTypes::PRESS_RELEASE_TYPE));
        $criteria->andWhere($expr->eq("status", Status::STATUS_ACTIVE));
        $this->setCriteria($criteria);
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
        if(empty($userIds)){
            return $subscriptionsStatus;
        }
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->in("user", $userIds));
        $subscribtions = $this->query->filter(/*$entityName =*/ "CMS\Entity\PressReleaseSubscription", $criteria);
        
        // subscribed part
        foreach($subscribtions as $subscribtion){
            $userId = $subscribtion->getUser()->getId();
            $parameters = array();
            if($loggedInUserFlag === false){
                $parameters = array('token' => $subscribtion->getToken());
            }
            $subscriptionsStatus[$userId] = array(
                "isSubscribed" => true,
                "action" => $this->router->assemble($parameters, array('name' => 'noaccess')),
            );
        }
        // non subscribed part
        foreach($userIds as $userId){
            if(!array_key_exists($userId, $subscriptionsStatus)){
                $subscriptionsStatus[$userId] = array(
                    "isSubscribed" => false,
                    "action" => $this->router->assemble(array(), array('name' => 'noaccess')),
                );
            }
        }
        
        return $subscriptionsStatus;
    }

}
