<?php

namespace Utilities\Service\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Cache\CacheHandler;

/**
 * CacheHandler Factory
 * 
 * Prepare CacheHandler service factory
 * 
 * 
 * 
 * @package utilities
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
