<?php

namespace Courses;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Courses\Listener\CourseManagementListener;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

class Module implements AutoloaderProviderInterface
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
     * Add course management listener
     * 
     * @access public
     * @param MvcEvent $event
     */
    public function onBootstrap( MvcEvent $event )
    {
        /* @var $eventManager \Doctrine\Common\EventManager */
        $eventManager = $event->getApplication()->getServiceManager()->get( 'entitymanager' )->getEventManager();
        $courseManagementListener = new CourseManagementListener;
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }
        $courseManagementListener->setIsAdminUser( $isAdminUser );
        $eventManager->addEventSubscriber( $courseManagementListener );
    }
}
