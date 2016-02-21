<?php

namespace Versioning;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Versioning\Listener\LoggableListener;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace( '\\', '/', __NAMESPACE__ ),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Initiate loggable listener on bootstrap
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function onBootstrap( MvcEvent $event )
    {
        /* @var $eventManager \Doctrine\Common\EventManager */
        $eventManager = $event->getApplication()->getServiceManager()->get( 'entitymanager' )->getEventManager();
        $loggableListener = new LoggableListener;
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($auth->hasIdentity()) {
            $loggableListener->setUserId( $storage['id'] );
            $loggableListener->setUsername( $storage['username'] );
        }
        
        $eventManager->addEventSubscriber( $loggableListener );
    }

}
