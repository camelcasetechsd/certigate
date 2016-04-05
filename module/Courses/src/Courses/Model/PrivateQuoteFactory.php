<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\PrivateQuote;

/**
 * PrivateQuote Factory
 * 
 * Prepare PrivateQuote service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class PrivateQuoteFactory implements FactoryInterface {

    /**
     * Prepare PrivateQuote service
     * 
     * @uses PrivateQuote
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return PrivateQuote
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\PrivateQuote');
        $translationHandler = $serviceLocator->get('translatorHandler');
        return new PrivateQuote($query, $translationHandler);
    }

}
