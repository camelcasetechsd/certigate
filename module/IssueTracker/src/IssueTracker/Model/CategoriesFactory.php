<?php

namespace IssueTracker\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use IssueTracker\Model\Categories;

class CategoriesFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        return new Categories($query, $notification);
    }

}
