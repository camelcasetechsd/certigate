<?php

namespace Chat\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Chat\Service\ChatServer;

class ChatServerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $chatHandler = $serviceLocator->get('Chat\Service\ChatHandler');
        return new ChatServer($chatHandler);
    }

}
