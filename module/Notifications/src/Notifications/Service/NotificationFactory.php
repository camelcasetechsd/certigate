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
        $mailQueue = $queuePluginManager->get('mail');
        
        $jobPluginManager = $mailQueue->getJobPluginManager();
        $sendEmailJob = $jobPluginManager->get('Notifications\Service\Job\SendEmailJob');
        
        $viewRenderer = $serviceLocator->get('Mustache\View\Renderer');
        $notification = new Notification($mailQueue, $sendEmailJob, $viewRenderer);
        return $notification;
    }

}
