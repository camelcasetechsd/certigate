<?php

namespace CMS\Event;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Event\RouteEvent;
use CMS\Service\Cache\CacheHandler;

/**
 * RouteEvent Factory
 * 
 * Prepare RouteEvent service factory
 * 
 * 
 * @package cms
 * @subpackage event
 */
class RouteEventFactory implements FactoryInterface {

    /**
     * Prepare RouteEvent service
     * 
     * @uses RouteEvent
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return RouteEvent
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $forceFlush = (APPLICATION_ENV == "production" )? false : true;
       
        $cmsCacheHandler = $serviceLocator->get('cmsCacheHandler');
        $menusArray = $cmsCacheHandler->getCachedCMSData($forceFlush);
        $menusArray[CacheHandler::MENUS_PATHS_KEY][] = "/2/2/";
        return new RouteEvent($menusArray[CacheHandler::MENUS_PATHS_KEY]);
    }

}
