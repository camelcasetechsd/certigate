<?php

namespace IssueTracker;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'IssueTracker\Model\Categories' => 'IssueTracker\Model\CategoriesFactory',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'IssueTracker\Controller\Issue' => 'IssueTracker\Controller\IssueController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'issues' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'index',
                    ),
                ),
            ),
            'newIssues' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues/new',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'new',
                    ),
                ),
            ),
            'editIssues' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/issues/edit[/:issueId]',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'issueId' => '[0-9]*'
                    ),
                ),
            ),
            'closeIssues' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues/close[/:issueId]',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'close',
                    ),
                    'constraints' => array(
                        'issueId' => '[0-9]*'
                    ),
                ),
            ),
            'reportIssues' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues/report/new',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'issueReport',
                    ),
                ),
            ),
            'listIssuesCategory' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues/categories',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'categories',
                    ),
                ),
            ),
            'newIssuesCategory' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/issues/categories/new',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'newCategory',
                    ),
                ),
            ),
            'editIssuesCategory' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/issues/categories/edit[/:issueId]',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'editCategory',
                    ),
                    'constraints' => array(
                        'issueId' => '[0-9]*'
                    ),
                ),
            ),
            'removeIssuesCategory' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/issues/categories/remove[/:issueId]',
                    'defaults' => array(
                        'controller' => 'IssueTracker\Controller\Issue',
                        'action' => 'removeCategory',
                    ),
                    'constraints' => array(
                        'issueId' => '[0-9]*'
                    ),
                ),
            ),
        )
    )
);
