<?php

namespace System\Service\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use System\Service\Cache\CacheHandler;

/**
 * CacheHandler Factory
 * 
 * Prepare CacheHandler service factory
 * 
 * 
 * 
 * @package system
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
        $cacheHandler = new CacheHandler($cache, $query);
        return $cacheHandler;
    }

}
