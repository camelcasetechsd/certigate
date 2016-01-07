<?php

namespace CMS\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Model\MenuItem;

/**
 * MenuItem Factory
 * 
 * Prepare MenuItem service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class MenuItemFactory implements FactoryInterface {

    /**
     * Prepare MenuItem service
     * 
     * @uses MenuItem
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return MenuItem
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new MenuItem();
    }

}
