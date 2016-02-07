<?php

namespace CertigateAcl\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CertigateAcl\Service\AclValidator;

/**
 * AclValidator Factory
 * 
 * Prepare AclValidator Service factory
 * 
 * 
 * 
 * @package certigateAcl
 * @subpackage service
 */
class AclValidatorFactory implements FactoryInterface
{

    /**
     * Prepare AclValidator Service
     * 
     * 
     * @uses AclValidator
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return AclValidator
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        $query = $serviceLocator->get('wrapperQuery');
        $router = $serviceLocator->get( 'router' );
        return new AclValidator($query, $router);
    }

}
