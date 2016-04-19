<?php

namespace IssueTracker\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use IssueTracker\Model\Comments;

class CommentsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $query = $serviceLocator->get('wrapperQuery');
        return new Comments($query);
    }

}
