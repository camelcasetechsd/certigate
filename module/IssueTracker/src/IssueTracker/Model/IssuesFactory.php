<?php

namespace IssueTracker\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use IssueTracker\Model\Issues;

class IssuesFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');

        return new Issues($query, $notification, $systemCacheHandler);
    }

}
