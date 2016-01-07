<?php

namespace Utilities\Service\Cache;

use Zend\Cache\Storage\StorageInterface;

/**
 * Cache
 * 
 * Handles caching related business
 * 
 * 
 * 
 * @property StorageInterface $cacheAdapter
 * 
 * @package utilities
 * @subpackage cache
 */
class Cache {

    /**
     *
     * @var StorageInterface 
     */
    public $cacheAdapter;


    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param StorageInterface $cacheAdapter
     */
    public function __construct(StorageInterface $cacheAdapter) {
        $this->cacheAdapter = $cacheAdapter;
    }
    
    /**
     * Set key with value
     * If item already exists, it is replaced
     * 
     * @access public
     * @param string $key
     * @param string $value
     */
    public function setItem($key, $value){
        if($this->cacheAdapter->hasItem($key)){
            $methodName = "replaceItem";
        }else{
            $methodName = "setItem";
        }
        $this->cacheAdapter->$methodName($key, $value);
    }
    
    /**
     * Set array of items
     * If items already exists, they are replaced
     * 
     * @access public
     * @param array $items
     */
    public function setItems($items){
        $existingItemsKeys = array_flip($this->cacheAdapter->hasItems(array_keys($items)));
        $existingItems = array_intersect_key($items, $existingItemsKeys);
        $nonExistingItems = array_diff_key($items, $existingItemsKeys);
        if(count($existingItems) > 0){
            $this->cacheAdapter->replaceItems($existingItems);
        }
        if(count($nonExistingItems) > 0){
            $this->cacheAdapter->setItems($nonExistingItems);
        }
    }

}
