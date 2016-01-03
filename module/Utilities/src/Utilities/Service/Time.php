<?php

namespace Utilities\Service;

/**
 * Time
 * 
 * Handles Time-related operations
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Time {

    /**
     * Get hour difference
     * 
     * 
     * @access public
     * @param string $firstTime
     * @param string $lastTime
     * @return float hour difference between first and last times
     */
    public function hourDifference($firstTime,$lastTime) {
        $firstTime=strtotime($firstTime);
        $lastTime=strtotime($lastTime);
        $timeDiff=$lastTime-$firstTime;//in seconds
        return $timeDiff/60/60;//in hours
    }

}
