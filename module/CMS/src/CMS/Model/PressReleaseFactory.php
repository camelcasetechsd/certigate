<?php

namespace CMS\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Model\PressRelease;

/**
 * PressReleaseFactory Factory
 * 
 * Prepare PressReleaseFactory service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class PressReleaseFactory implements FactoryInterface {

    /**
     * Prepare PressRelease service
     * 
     * @uses PressRelease
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return PressRelease
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity(/* $entityName = */ 'CMS\Entity\PressReleaseSubscription');
        $router = $serviceLocator->get('router');
        return new PressRelease($query, $router);
    }

}
