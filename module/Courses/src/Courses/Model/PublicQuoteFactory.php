<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\PublicQuote;

/**
 * PublicQuote Factory
 * 
 * Prepare PublicQuote service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class PublicQuoteFactory implements FactoryInterface {

    /**
     * Prepare PublicQuote service
     * 
     * @uses PublicQuote
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return PublicQuote
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\PublicQuote');
        $objectUtilities = $serviceLocator->get('objectUtilities');
        return new PublicQuote($query, $objectUtilities);
    }

}
