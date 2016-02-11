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

    public function onBootstrap( MvcEvent $e )
    {
        /* @var $evm \Doctrine\Common\EventManager */
        $evm = $e->getApplication()->getServiceManager()->get( 'entitymanager' )->getEventManager();
        $loggableListener = new LoggableListener;
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggableListener->setUsername( $auth->getIdentity()['username'] );
        }

        $evm->addEventSubscriber( $loggableListener );
    }

}
