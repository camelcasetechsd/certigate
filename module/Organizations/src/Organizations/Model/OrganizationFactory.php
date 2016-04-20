<?php

namespace Organizations\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrganizationFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $version = $serviceLocator->get('Versioning\Model\Version');
        $organizationUser = $serviceLocator->get('Organizations\Model\OrganizationUser');
        return new Organization($query, $systemCacheHandler, $notification, $version, $organizationUser);
    }

}
