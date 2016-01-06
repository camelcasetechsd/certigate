<?php

namespace CertigateAcl;

use Zend\Mvc\MvcEvent;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap( MvcEvent $e )
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach( 'route', array($this, 'loadConfiguration'), 2 );
        //you can attach other function need here...
    }

    public function loadConfiguration( MvcEvent $e )
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $sm->get( 'router' );
        $request = $sm->get( 'request' );

        $matchedRoute = $router->match( $request );
        if (null !== $matchedRoute) {
            $sharedManager->attach( 'Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) use ($sm) {
                $sm->get( 'ControllerPluginManager' )->get( 'CertigateAclPlugin' )
                    ->doAuthorization( $e ); //pass to the plugin...    
            }, 2
            );
        }
    }

}
