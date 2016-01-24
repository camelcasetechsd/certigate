<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Outline;

/**
 * Outline Factory
 * 
 * Prepare Outline service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class OutlineFactory implements FactoryInterface {

    /**
     * Prepare Outline service
     * 
     * @uses Outline
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Outline
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\Outline');
        return new Outline($query);
    }

}
