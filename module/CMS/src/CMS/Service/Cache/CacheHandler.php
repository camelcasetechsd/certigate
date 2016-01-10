<?php

namespace CMS\Service\Cache;

use Utilities\Service\Cache\Cache;
use Utilities\Service\Query\Query;

/**
 * CacheHandler
 * 
 * Handles caching usage related business
 * 
 * 
 * 
 * @property CMS\Model\MenuItem $menuItem
 * @property Cache $cache
 * @property Query $query
 * 
 * @package cms
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
     * @var CMS\Model\MenuItem
     */
    public $menuItem;

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
     * @param CMS\Model\MenuItem $menuItem
     */
    public function __construct(Cache $cache, Query $query, $menuItem) {
        $this->cache = $cache;
        $this->query = $query;
        $this->menuItem = $menuItem;
    }

    /**
     * Prepare cached data for CMS usage
     * 
     * 
     * @access public
     * 
     * @param bool $forceFlush ,default is bool false
     * @return array cached CMS data serialized
     */
    public function prepareCachedCMSData($forceFlush = false) {
        if ($forceFlush === false) {
            $cachedCMSDataKeys = array(
                self::MENUS_KEY,
                self::MENUS_PATHS_KEY
            );
            $existingCachedCMSDataKeys = $this->cache->cacheAdapter->hasItems($cachedCMSDataKeys);
        }
        if ($forceFlush === true || ($forceFlush === false && count($existingCachedCMSDataKeys) !== count($cachedCMSDataKeys))) {
            $menuItems = $this->menuItem->getMenuItems();
            
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
        } else {
            $items = $this->getCachedCMSData();
            $items = array(
                self::MENUS_KEY => serialize($items[self::MENUS_KEY]),
                self::MENUS_PATHS_KEY => serialize($items[self::MENUS_PATHS_KEY])
            );
        }
        return $items;
    }

    /**
     * Get cached data for CMS usage
     * 
     * 
     * @access public
     * 
     * @param bool $forceFlush ,default is bool false
     * @return array cached CMS data unserialized
     */
    public function getCachedCMSData($forceFlush = false) {
        $cachedCMSDataKeys = array(
            self::MENUS_KEY,
            self::MENUS_PATHS_KEY
        );

        if ($forceFlush === true) {
            $items = $this->prepareCachedCMSData($forceFlush);
        } else {
            $existingCachedCMSDataKeys = $this->cache->cacheAdapter->hasItems($cachedCMSDataKeys);
            if (count($existingCachedCMSDataKeys) < count($cachedCMSDataKeys)) {
                $items = $this->prepareCachedCMSData();
            } else {
                $items = array(
                    self::MENUS_KEY => $this->cache->cacheAdapter->getItem(self::MENUS_KEY),
                    self::MENUS_PATHS_KEY => $this->cache->cacheAdapter->getItem(self::MENUS_PATHS_KEY)
                );
            }
        }

        $cachedCMSData = array(
            self::MENUS_KEY => unserialize($items[self::MENUS_KEY]),
            self::MENUS_PATHS_KEY => unserialize($items[self::MENUS_PATHS_KEY])
        );
        return $cachedCMSData;
    }

}
