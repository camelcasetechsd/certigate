<?php

namespace Users\Auth;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Users\Auth\Authentication;

/**
 * Authentication Factory
 * 
 * Prepare Authentication service factory
 * 
 * 
 * 
 * @package users
 * @subpackage auth
 */
class AuthenticationFactory implements FactoryInterface {

    /**
     * Prepare Authentication service
     * 
     * 
     * @uses Authentication
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $wrapperQuery = $serviceLocator->get('wrapperQuery');
        $estoreApi = $serviceLocator->get('EStore\Service\Api');
        $authentication = new Authentication($wrapperQuery, $estoreApi);
        
        return $authentication;
    }
}
