<?php

namespace Utilities\Service\Query;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Query\Query;

/**
 * Query Factory
 * 
 * Prepare Query service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage query
 */
class QueryFactory implements FactoryInterface {

    /**
     * Prepare Query service
     * 
     * 
     * @uses Query
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Query
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        // Get the entity manager through our service manager
        $entitymanager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $query = new Query($entitymanager);
        return $query;
    }

}
