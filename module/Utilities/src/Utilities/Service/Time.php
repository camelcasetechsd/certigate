<?php

namespace Utilities\Service;

/**
 * Time
 * 
 * Handles Date/Time-related operations
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Time {

    /**
     * UNIX date string
     */
    const UNIX_DATE_STRING = "Thu, 01 Jan 1970";

    /**
     * Saudi time zone id
     */
    const DEFAULT_TIME_ZONE_ID = "Asia/Riyadh";
    
    /**
     * date format
     */
    const DATE_FORMAT = 'd/m/Y';
    
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
    
    /**
     * Convert time to DateTime object
     * ,or update it if it's already an object without right date
     * 
     * @access public
     * @param \DateTime $time
     * @return \DateTime
     */
    static public function objectifyTime($time){
        if (!is_object($time)) {
            $time = new \DateTime(Time::UNIX_DATE_STRING." ".$time);
        }elseif($time->format("Y") != "1970"){
            $time->modify(Time::UNIX_DATE_STRING." ".$time->format("H:s"));
        }
        return $time;
    }

}
