<?php

namespace Orgs\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Organizations\Model\Organization;

class OrgFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Orgs\Entity\Org');
        return new Org($query);
    }
    
}
