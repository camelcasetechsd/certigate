<?php

namespace CMS\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Model\Page;

/**
 * Page Factory
 * 
 * Prepare Page service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class PageFactory implements FactoryInterface {

    /**
     * Prepare Page service
     * 
     * @uses Page
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Page
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('CMS\Entity\Page');
        $pressReleaseSubscriptionModel = $serviceLocator->get('CMS\Model\PressReleaseSubscription');
        return new Page($query, $pressReleaseSubscriptionModel);
    }

}
