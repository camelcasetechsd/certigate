<?php

namespace Users;

// Our main imports that we want to use
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

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
     * on Bootstrap application, Attach dispatch event listener
     * 
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event) {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach( MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
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
     * MVC preDispatch Event
     *
     * @param $event
     * @return mixed
     */
    public function mvcPreDispatch($event) {
        $serviceManager = $event->getTarget()->getServiceManager();
        $auth = $serviceManager->get('Users\Event\AuthenticationEvent');

        return $auth->preDispatch($event);
    }

}
