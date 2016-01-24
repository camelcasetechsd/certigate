<?php

namespace Utilities\Service\Logger;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Logger Factory
 * 
 * Prepare Logger service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage logger
 */
class LoggerFactory implements FactoryInterface
{

    /**
     * Error log file path
     */
    const ERROR_LOG_FILE = "./logs/error.log";

    /**
     * Prepare Logger service
     * 
     * 
     * @uses Logger
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Logger
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = new Logger();
        $writer = new Stream(self::ERROR_LOG_FILE);

        $logger->addWriter($writer);

        return $logger;
    }

}
