<?php

namespace CustomDoctrine\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CustomDoctrine\Service\DoctrineObject;

/**
 * DoctrineObjectHydrator Factory
 * 
 * Prepare DoctrineObject Hydrator factory
 * 
 * 
 * 
 * @package customDoctrine
 * @subpackage service
 */
class DoctrineObjectHydratorFactory implements FactoryInterface
{

    /**
     * Prepare DoctrineObject Hydrator
     * 
     * 
     * @uses DoctrineObject
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return DoctrineObject
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {

        $parentLocator = $serviceLocator->getServiceLocator();
        return new DoctrineObject($parentLocator->get('doctrine.entitymanager.orm_default'));
    }

}
