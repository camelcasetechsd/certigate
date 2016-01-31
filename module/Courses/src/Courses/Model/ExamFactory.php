<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Exam;

/**
 * Exam Factory
 * 
 * Prepare Exam service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class ExamFactory implements FactoryInterface {

    /**
     * Prepare Exam service
     * 
     * @uses Exam
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Exam
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\ExamBook');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        return new Exam($query, $systemCacheHandler);
    }

}
