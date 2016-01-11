<?php

namespace CMS;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * CMS Module
 * 
 * cms module configuration
 * 
 * 
 * @property array $addedRoutes
 * 
 * @package cms
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface {

    public $addedRoutes;
    
    /**
     * Attach event on bootstrap
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event) {
        // get the event manager.
        $eventManager = $event->getApplication()->getEventManager();

        $eventManager->attach(
                // the event to attach to 
                MvcEvent::EVENT_ROUTE,
                // any callable works here.
                array($this, 'addRoutes'),
                // The priority.  Must be a positive integer to make
                // sure that the handler is triggered *before* the application
                // tries to match a route.
                100
        );
    }

    /**
     * addRoutes Event Handler
     * set static pages routes
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function addRoutes(MvcEvent $event) {

        $serviceManager = $event->getTarget()->getServiceManager();
        $routeEvent = $serviceManager->get('CMS\Event\RouteEvent');

        $routeConfig = $routeEvent->addStaticPagesRoutes($event);
        $this->addedRoutes = $routeConfig;
    }

    /**
     * Get config array
     * 
     * 
     * @access public
     * @return array module configuration array
     */
    public function getConfig() {
        $configArray = include __DIR__ . '/config/module.config.php';
        if(is_array($this->addedRoutes) && count($this->addedRoutes) > 0){
            $configArray["router"]["routes"] = array_merge($configArray["router"]["routes"], $this->addedRoutes);
        }
        return $configArray;
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
