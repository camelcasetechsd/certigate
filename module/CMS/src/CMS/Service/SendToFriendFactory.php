<?php

namespace CMS\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CMS\Service\SendToFriend;

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
class SendToFriendFactory implements FactoryInterface
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
        return new SendToFriend($systemCacheHandler, $notification);
    }

}
