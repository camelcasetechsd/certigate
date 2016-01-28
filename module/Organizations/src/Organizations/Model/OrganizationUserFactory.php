<?php

namespace Organizations\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Organizations\Model\OrganizationUser;

/**
 * OrganizationUser Factory
 * 
 * Prepare OrganizationUser service factory
 * 
 * 
 * @package organizations
 * @subpackage model
 */
class OrganizationUserFactory implements FactoryInterface {

    /**
     * Prepare OrganizationUser service
     * 
     * @uses OrganizationUser
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return OrganizationUser
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Organizations\Entity\OrganizationUser');
        return new OrganizationUser($query);
    }

}
