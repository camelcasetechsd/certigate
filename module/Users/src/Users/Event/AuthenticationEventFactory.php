<?php

namespace Users\Event;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Users\Event\AuthenticationEvent;

/**
 * AuthenticationEvent Factory
 * 
 * Prepare AuthenticationEvent service factory
 * 
 * 
 * 
 * @package users
 * @subpackage event
 */
class AuthenticationEventFactory implements FactoryInterface {

    /**
     * Prepare AuthenticationEvent service
     * 
     * 
     * @uses AuthenticationEvent
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationEvent
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        
        $authenticationEvent = new AuthenticationEvent();
        return $authenticationEvent;
    }
}
