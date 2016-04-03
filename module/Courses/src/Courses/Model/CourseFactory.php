<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Course;

/**
 * Course Factory
 * 
 * Prepare Course service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class CourseFactory implements FactoryInterface {

    /**
     * Prepare Course service
     * 
     * @uses Course
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Course
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $outlineModel = $serviceLocator->get('Courses\Model\Outline');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $version = $serviceLocator->get('Versioning\Model\Version');
        $estoreApi = $serviceLocator->get('EStore\Service\Api');
        return new Course($query, $outlineModel, $systemCacheHandler, $notification, $version, $estoreApi);
    }

}
