<?php

namespace DefaultModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DefaultModule\Service\ContactUs;

/**
 * ContactUs Factory
 * 
 * Prepare ContactUs Service factory
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage service
 */
class ContactUsFactory implements FactoryInterface
{

    /**
     * Prepare ContactUs Service
     * 
     * 
     * @uses ContactUs
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return ContactUs
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        return new ContactUs($systemCacheHandler, $notification);
    }

}
