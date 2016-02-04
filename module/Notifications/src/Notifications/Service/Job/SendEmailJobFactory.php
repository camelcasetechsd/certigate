<?php

namespace Notifications\Service\Job;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Notifications\Service\Job\SendEmailJob;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
/**
 * SendEmailJob Factory
 * 
 * Prepare SendEmail job factory
 * 
 * 
 * 
 * @package notifications
 * @subpackage job
 */
class SendEmailJobFactory implements FactoryInterface {

    /**
     * Prepare SendEmail job
     * 
     * 
     * @uses SendEmailJob
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return SendEmailJob
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        // Setup SMTP transport using LOGIN authentication
        $config = $serviceLocator->getServiceLocator()->get('Config');
        $options = new SmtpOptions($config['mail_settings']);
        $transport = new Smtp();
        $transport->setOptions($options);
        
        $job = new SendEmailJob($transport);
        return $job;
    }

}
