<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\QuoteGenerator;

/**
 * QuoteGenerator Factory
 * 
 * Prepare QuoteGenerator service factory
 * 
 * 
 * @package courses
 * @subpackage model
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
