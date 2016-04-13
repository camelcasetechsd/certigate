<?php

namespace Utilities\Service;

/**
 * Random
 * 
 * Generate random values
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Random {

    /**
     * Get random and unique value
     * 
     * 
     * @access public
     * @return string random and almost unique value
     */
    public static function getRandomUniqueName() {
        $uniqid = uniqid(mt_rand(), true);
        $cid = str_replace('.', '',$uniqid.md5($uniqid));
        return $cid;
    }

}
