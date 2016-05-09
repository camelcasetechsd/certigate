<?php

namespace Utilities\Service;

/**
 * Process
 * 
 * Handles Command-related operations
 * 
 * @package utilities
 * @subpackage service
 */
class Process
{

    /**
     * Run process in background
     * 
     * 
     * @access public
     * @param string $cmd
     * @param bool $getProcessIdFlag ,default is bool false
     * @return int process id
     */
    public static function runBackgroundProcess($cmd, $getProcessIdFlag = false)
    {
        exec("$cmd > /dev/null 2>&1 &");
        if ($getProcessIdFlag === true) {
            $cmdParts = explode(/* $delimiter = */ " ", $cmd);
            exec("pidof " . reset($cmdParts), $processOutput);
            return reset($processOutput);
        }
    }

    /**
     * Kill running process by id
     * 
     * 
     * @access public
     * @param int $processId process id
     * @param bool $clearProcessIdFlag ,default is bool true
     */
    public static function killProcessById(&$processId, $clearProcessIdFlag = true)
    {
        self::killProcess($processId);
        if ($clearProcessIdFlag === true) {
            $processId = null;
        }
    }
    
    /**
     * Kill running process by name
     * 
     * 
     * @access public
     * @param int $processName process name
     */
    public static function killProcessByName($processName)
    {
        self::killProcess("\$(pidof $processName)");
    }
    
    /**
     * Kill running process
     * 
     * 
     * @access public
     * @param int $processIdentifier process id
     */
    private static function killProcess($processIdentifier)
    {
        exec("kill -9 $processIdentifier");
    }

}
