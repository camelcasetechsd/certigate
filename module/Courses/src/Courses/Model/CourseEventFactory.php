<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\CourseEvent;

/**
 * CourseEvent Factory
 * 
 * Prepare CourseEvent service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class CourseEventFactory implements FactoryInterface {

    /**
     * Prepare CourseEvent service
     * 
     * @uses CourseEvent
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return CourseEvent
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\CourseEvent');
        $objectUtilities = $serviceLocator->get('objectUtilities');
        $estoreApi = $serviceLocator->get('EStore\Service\Api');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $translatorHandler = $serviceLocator->get('translatorHandler');
        $formView = $serviceLocator->get('Utilities\Service\View\FormView');
        $courseEventSubscription = $serviceLocator->get('Courses\Model\CourseEventSubscription');
        return new CourseEvent($query, $objectUtilities, $estoreApi, $systemCacheHandler, $notification, $translatorHandler, $formView, $courseEventSubscription);
    }

}
