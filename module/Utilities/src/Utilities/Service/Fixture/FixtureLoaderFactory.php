<?php

namespace Utilities\Service\Fixture;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\Fixture\FixtureLoader;

/**
 * FixtureLoader Factory
 * 
 * Prepare FixtureLoader service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class FixtureLoaderFactory implements FactoryInterface {

    /**
     * Prepare FixtureLoader service
     * 
     * 
     * @uses FixtureLoader
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return FixtureLoader
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery');
        $fixtureLoader = new FixtureLoader($query);
        return $fixtureLoader;
    }

}
