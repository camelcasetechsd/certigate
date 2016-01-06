<?php

namespace Utilities\Service\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Cache\Cache;
use Zend\Cache\Storage\Adapter\MemcachedOptions;
use Zend\Cache\Storage\Adapter\Memcached;

/**
 * Cache Factory
 * 
 * Prepare Cache service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage cache
 */
class CacheFactory implements FactoryInterface {

    /**
     * Prepare Cache service
     * 
     * 
     * @uses MemcachedOptions
     * @uses Memcached
     * @uses Cache
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Cache
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $memcachedOptions = new MemcachedOptions(array(
            'ttl' => 60 * 60 * 24 * 365, // 1 year
            'namespace' => 'certigate_cache',
            'key_pattern' => null,
            'readable' => true,
            'writable' => true,
            'servers' => array(
                array('localhost', 11211)
            )
        ));
        $cacheAdapter = new Memcached($memcachedOptions);
        $cache = new Cache($cacheAdapter);
        return $cache;
    }

}
