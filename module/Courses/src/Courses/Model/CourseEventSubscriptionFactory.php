<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\CourseEventSubscription;

/**
 * CourseEventSubscription Factory
 * 
 * Prepare CourseEventSubscription service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class CourseEventSubscriptionFactory implements FactoryInterface {

    /**
     * Prepare CourseEventSubscription service
     * 
     * @uses CourseEventSubscription
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return CourseEventSubscription
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\CourseEventSubscription');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $config = $serviceLocator->get('Config');
        return new CourseEventSubscription($query, $systemCacheHandler, $notification, $config["courseEventSubscription"]);
    }

}
