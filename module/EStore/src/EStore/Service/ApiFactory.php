<?php

namespace EStore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use EStore\Service\Api;

/**
 * Api Factory
 * 
 * Prepare Api service factory
 * 
 * 
 * @package estore
 * @subpackage service
 */
class ApiFactory implements FactoryInterface {

    /**
     * Prepare Api service
     * 
     * @uses Api
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Api
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config');
        $query = $serviceLocator->get('wrapperQuery');
        $serverUrl = $serviceLocator->get('ViewHelperManager')->get('ServerUrl');
        return new Api($query, $serverUrl, $config["website"]);
    }

}
