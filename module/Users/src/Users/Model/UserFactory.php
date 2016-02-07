<?php

namespace Users\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Users\Model\User;

/**
 * User Factory
 * 
 * Prepare User service factory
 * 
 * 
 * 
 * @package users
 * @subpackage model
 */
class UserFactory implements FactoryInterface {

    /**
     * Prepare User service
     * 
     * 
     * @uses User
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Users\Entity\User');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $auth = $serviceLocator->get('Users\Auth\Authentication');
        return new User($query, $systemCacheHandler, $notification, $auth);
    }

}
