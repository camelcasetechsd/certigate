<?php

namespace CMS\Service\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Service\Cache\CacheHandler;

/**
 * CacheHandler Factory
 * 
 * Prepare CacheHandler service factory
 * 
 * 
 * 
 * @package cms
 * @subpackage cache
 */
class CacheHandlerFactory implements FactoryInterface {

    /**
     * Prepare CacheHandler service
     * 
     * 
     * @uses CacheHandler
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        
        $cache = $serviceLocator->get('cacheUtilities');
        $query = $serviceLocator->get('wrapperQuery');
        $menuItem = $serviceLocator->get('CMS\Model\MenuItem');
        $cacheHandler = new CacheHandler($cache, $query, $menuItem);
        $cacheHandler->setServiceLocator($serviceLocator);
        return $cacheHandler;
    }

}
