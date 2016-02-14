<?php

namespace Versioning;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Versioning\Listener\LoggableListener;
use Users\Entity\Role;

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
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $loggableListener->setUsername( $auth->getIdentity()['username'] );
        }
        $loggableListener->setIsAdminUser( $isAdminUser );
        
        $eventManager->addEventSubscriber( $loggableListener );
    }

}
