<?php

namespace Versioning\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Versioning\Model\Version;

/**
 * Version Factory
 * 
 * Prepare Version service factory
 * 
 * 
 * @package versioning
 * @subpackage model
 */
class VersionFactory implements FactoryInterface {

    /**
     * Prepare Version service
     * 
     * @uses Version
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Version
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Versioning\Entity\LogEntry');
        $objectUtilities = $serviceLocator->get('objectUtilities');
        return new Version($query, $objectUtilities);
    }

}
