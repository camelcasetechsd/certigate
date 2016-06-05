<?php

namespace CMS\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Model\Press;
/**
 * PressReleaseSubscriptionFactory Factory
 * 
 * Prepare PressReleaseSubscription service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class PressFactory implements FactoryInterface {

    /**
     * Prepare PressReleaseSubscription service
     * 
     * @uses PressReleaseSubscription
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return PressReleaseSubscription
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity(/* $entityName = */ 'CMS\Entity\Page');
        return new Press($query);
    }

}
