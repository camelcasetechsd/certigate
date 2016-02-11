<?php

namespace Utilities\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Object;

/**
 * Object Factory
 * 
 * Prepare Object service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class ObjectFactory implements FactoryInterface {

    /**
     * Prepare Object service
     * 
     * 
     * @uses Object
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Object
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $countriesService = $serviceLocator->get('losi18n-countries');
        $languagesService = $serviceLocator->get('losi18n-languages');
        $query = $serviceLocator->get('wrapperQuery');
        $object = new Object($countriesService, $languagesService, $query);
        return $object;
    }

}
