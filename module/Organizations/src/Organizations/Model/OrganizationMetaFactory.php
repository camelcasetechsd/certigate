<?php

namespace Organizations\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Organizations\Model\OrganizationMeta;

/**
 * OrganizationMeta Factory
 * 
 * Prepare OrganizationMeta service factory
 * 
 * 
 * @package organizations
 * @subpackage model
 */
class OrganizationMetaFactory implements FactoryInterface {

    /**
     * Prepare OrganizationMeta service
     * 
     * @uses OrganizationMeta
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return OrganizationMeta
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Organizations\Entity\OrganizationMeta');
        return new OrganizationMeta($query);
    }

}
