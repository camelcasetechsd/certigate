<?php

namespace Users;

// Our main imports that we want to use
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Users Module
 * 
 * users module configuration
 * 
 * 
 * 
 * @package users
 */
class Module implements ConfigProviderInterface,AutoloaderProviderInterface {

    /**
     * Get config array
     * 
     * 
     * @access public
     * @return array module configuration array
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get autoloader config array
     * 
     * 
     * @access public
     * @return array module autoloader configuration array
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
