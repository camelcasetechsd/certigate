<?php

namespace Chat\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Chat\Service\ChatHandler;

class ChatHandlerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $chatModel = $serviceLocator->get('Chat\Model\Chat');
        return new ChatHandler($chatModel);
    }

}
