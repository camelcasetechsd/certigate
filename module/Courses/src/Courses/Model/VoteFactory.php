<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Vote;

/**
 * Vote Factory
 * 
 * Prepare Vote service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class VoteFactory implements FactoryInterface {

    /**
     * Prepare Vote service
     * 
     * @uses Vote
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Vote
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Courses\Entity\Vote');
        return new Vote($query);
    }

}
