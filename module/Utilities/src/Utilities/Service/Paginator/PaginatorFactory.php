<?php

namespace Utilities\Service\Paginator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Paginator\PaginatorQuery ;

/**
 * Query Factory
 * 
 * Prepare Query service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage paginator
 */
class PaginatorFactory implements FactoryInterface {

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
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $query = new PaginatorQuery($entityManager);
        return $query;
    }

}
