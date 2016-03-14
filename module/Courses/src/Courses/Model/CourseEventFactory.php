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
        return new CourseEvent($query, $objectUtilities, $estoreApi);
    }

}
