<?php

namespace Notifications\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Notifications\Service\Notification;

/**
 * Notification Factory
 * 
 * Prepare Notification service factory
 * 
 * 
 * 
 * @package notifications
 * @subpackage service
 */
class NotificationFactory implements FactoryInterface {

    /**
     * Prepare Notification service
     * 
     * 
     * @uses Notification
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Notification
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $queuePluginManager = $serviceLocator->get('SlmQueue\Queue\QueuePluginManager');
        $defaultQueue = $queuePluginManager->get('default');
        
        $jobPluginManager = $defaultQueue->getJobPluginManager();
        $sendEmailJob = $jobPluginManager->get('Notifications\Service\Job\SendEmailJob');
        
        $notification = new Notification($defaultQueue, $sendEmailJob);
        return $notification;
    }

}
