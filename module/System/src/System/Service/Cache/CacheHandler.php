<?php

namespace System\Service\Cache;

use Utilities\Service\Cache\Cache;
use Utilities\Service\Query\Query;

/**
 * CacheHandler
 * 
 * Handles caching usage related business
 * 
 * 
 * 
 * @property Cache $cache
 * @property Query $query
 * 
 * @package system
 * @subpackage cache
 */
class CacheHandler
{

    /**
     * settings key
     */
    const SETTINGS_KEY = "settings";

    /**
     *
     * @var Cache 
     */
    public $cache;

    /**
     *
     * @var Query 
     */
    public $query;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param Cache $cache
     * @param Query $query
     */
    public function __construct(Cache $cache, Query $query)
    {
        $this->cache = $cache;
        $this->query = $query;
    }

    /**
     * Prepare cached data for System usage
     * 
     * 
     * @access public
     * 
     * @param bool $forceFlush ,default is bool false
     * @return array cached System data serialized
     */
    public function prepareCachedSystemData($forceFlush = false)
    {
        if ($forceFlush === false) {
            $cachedSystemDataKeys = array(
                self::SETTINGS_KEY,
            );
            $existingCachedSystemDataKeys = $this->cache->cacheAdapter->hasItems($cachedSystemDataKeys);
        }
        if ($forceFlush === true || ($forceFlush === false && count($existingCachedSystemDataKeys) !== count($cachedSystemDataKeys))) {
            $settings = $this->query->findAll(/* $entityName = */ 'System\Entity\Setting');
            $settingsArray = array();
            foreach ($settings as $setting) {
                $settingsArray[$setting->name] = $setting->value;
            }
            $items = array(
                self::SETTINGS_KEY => serialize($settingsArray),
            );
            $this->cache->setItems($items);
        }
        else {
            $items = $this->getCachedSystemData();
            $items = array(
                self::SETTINGS_KEY => serialize($items[self::SETTINGS_KEY]),
            );
        }
        return $items;
    }

    /**
     * Get cached data for System usage
     * 
     * 
     * @access public
     * 
     * @param bool $forceFlush ,default is bool false
     * @return array cached System data unserialized
     */
    public function getCachedSystemData($forceFlush = false)
    {
        $cachedSystemDataKeys = array(
            self::SETTINGS_KEY,
        );

        if ($forceFlush === true) {
            $items = $this->prepareCachedSystemData($forceFlush);
        }
        else {
            $existingCachedSystemDataKeys = $this->cache->cacheAdapter->hasItems($cachedSystemDataKeys);
            if (count($existingCachedSystemDataKeys) < count($cachedSystemDataKeys)) {
                $items = $this->prepareCachedSystemData();
            }
            else {
                $items = array(
                    self::SETTINGS_KEY => $this->cache->cacheAdapter->getItem(self::SETTINGS_KEY),
                );
            }
        }

        $cachedSystemData = array(
            self::SETTINGS_KEY => unserialize($items[self::SETTINGS_KEY]),
        );
        return $cachedSystemData;
    }
    
    /**
     * flush cache of settings
     * @param mixed $key
     */
    public function flushSettingsCache($key = null)
    {
        if(is_null($key)){
            $key = array(
                self::SETTINGS_KEY,
            );
        }
        $this->cache->flush($key);
    }
}
