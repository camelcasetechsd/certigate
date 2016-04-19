<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Resource;

/**
 * Resource Factory
 * 
 * Prepare Resource service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class ResourceFactory implements FactoryInterface {

    /**
     * Prepare Resource service
     * 
     * @uses Resource
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Resource
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\Resource');
        $logger = $serviceLocator->get('loggerUtilities');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $translationHandler = $serviceLocator->get('translatorHandler');
        return new Resource($query, $logger, $systemCacheHandler, $notification, $translationHandler);
    }

}
