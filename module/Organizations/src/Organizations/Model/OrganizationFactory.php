<?php

namespace Organizations\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrganizationFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        return new Organization($query);
    }

}
