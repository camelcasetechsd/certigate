<?php

namespace Utilities;

// Our main imports that we want to use
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Utilities Module
 * 
 * utilities module configuration
 * 
 * 
 * 
 * @package utilities
 */
class Module implements ConfigProviderInterface,AutoloaderProviderInterface {

    /**
     * on Bootstrap application, Accept enum field type
     * 
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $entityManager = $event->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
    }
    
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
    
    /**
     * Get view config array
     * 
     * 
     * @access public
     * @return array module view configuration array
     */
    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'formelementerrors' => 'Utilities\Form\FormElementErrors'
            ),
        );
    }

}
