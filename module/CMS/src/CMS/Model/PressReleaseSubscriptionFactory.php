<?php

namespace CMS\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Model\PressReleaseSubscription;

/**
 * PressReleaseSubscriptionFactory Factory
 * 
 * Prepare PressReleaseSubscription service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class PressReleaseSubscriptionFactory implements FactoryInterface {

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
        $query = $serviceLocator->get('wrapperQuery')->setEntity(/* $entityName = */ 'CMS\Entity\PressReleaseSubscription');
        $router = $serviceLocator->get('router');
        $mustacheViewRenderer = $serviceLocator->get('Mustache\View\Renderer');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        return new PressReleaseSubscription($query, $router, $mustacheViewRenderer, $systemCacheHandler, $notification);
    }

}
