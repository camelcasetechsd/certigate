<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Evaluation;

/**
 * Evaluation Factory
 * 
 * Prepare Evaluation service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class EvaluationFactory implements FactoryInterface {

    /**
     * Prepare Evaluation service
     * 
     * @uses Evaluation
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Evaluation
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\Evaluation');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        return new Evaluation($query, $systemCacheHandler, $notification);
    }

}
