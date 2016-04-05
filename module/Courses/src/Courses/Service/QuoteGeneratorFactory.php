<?php

namespace Courses\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Service\QuoteGenerator;

/**
 * QuoteGenerator Factory
 * 
 * Prepare QuoteGenerator service factory
 * 
 * 
 * @package courses
 * @subpackage service
 */
class QuoteGeneratorFactory implements FactoryInterface {

    /**
     * Prepare QuoteGenerator service
     * 
     * @uses QuoteGenerator
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return QuoteGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new QuoteGenerator($serviceLocator);
    }

}
