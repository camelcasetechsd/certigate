<?php

namespace Utilities\Service\Cache;

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
 * @package utilities
 * @subpackage cache
 */
class CacheHandler {

    /**
     * menus key
     */
    const MENUS_KEY = "menus";

    /**
     * menus paths key
     */
    const MENUS_PATHS_KEY = "menus_paths";

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
    public function __construct(Cache $cache, Query $query) {
        $this->cache = $cache;
        $this->query = $query;
    }

    /**
     * Prepare cached data for CMS usage
     * 
     * 
     * @access public
     * @return array cached CMS data serialized
     */
    public function prepareCachedCMSData() {
        $menuItems = $this->query->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem')->entityRepository->getMenuItemsSorted(/* $hiddenMenuItemsIds = */ array(), /* $menuItemStatus = */ true, /* $menuStatus = */ true, /* $withPagesOnlyFlag = */ true, /* $select = */ null, /* $treeFlag = */ true);
        $menuItemsPaths = $this->query->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem')->entityRepository->getMenuItemsSorted(/* $hiddenMenuItemsIds = */ array(), /* $menuItemStatus = */ true, /* $menuStatus = */ true, /* $withPagesOnlyFlag = */ true, /* $select = */ "mt.path as path", /* $treeFlag = */ false);
        $menuItemsPathsFlat = array();
        array_walk_recursive($menuItemsPaths, function($value) use (&$menuItemsPathsFlat) {
            $menuItemsPathsFlat[] = $value;
        });
        $items = array(
            self::MENUS_KEY => serialize($menuItems),
            self::MENUS_PATHS_KEY => serialize($menuItemsPathsFlat)
        );
        $this->cache->setItems($items);

        return $items;
    }

    /**
     * Get cached data for CMS usage
     * 
     * 
     * @access public
     * @return array cached CMS data unserialized
     */
    public function getCachedCMSData() {
        $cachedCMSDataKeys = array(
            self::MENUS_KEY,
            self::MENUS_PATHS_KEY
        );

        $existingCachedCMSDataKeys = $this->cache->cacheAdapter->hasItems($cachedCMSDataKeys);
        if (count($existingCachedCMSDataKeys) < count($cachedCMSDataKeys)) {
            $items = $this->prepareCachedCMSData();
        } else {
            $items = array(
                self::MENUS_KEY => $this->cache->cacheAdapter->getItem(self::MENUS_KEY),
                self::MENUS_PATHS_KEY => $this->cache->cacheAdapter->getItem(self::MENUS_PATHS_KEY)
            );
        }

        $cachedCMSData = array(
            self::MENUS_KEY => unserialize($items[self::MENUS_KEY]),
            self::MENUS_PATHS_KEY => unserialize($items[self::MENUS_PATHS_KEY])
        );
        return $cachedCMSData;
    }

}
