<?php

namespace Versioning;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Gedmo\Loggable\LoggableListener;

class Module
{

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
