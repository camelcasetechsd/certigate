<?php

namespace Chat\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Chat\Model\Chat;

/**
 * Page Factory
 * 
 * Prepare Page service factory
 * 
 * 
 * @package cms
 * @subpackage model
 */
class ChatFactory implements FactoryInterface
{

    /**
     * Prepare message service
     * 
     * @uses Page
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Page
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery')->setEntity('Chat\Entity\Message');
        return new Chat($query);
    }

}
