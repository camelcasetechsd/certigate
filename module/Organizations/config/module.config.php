<?php

namespace Organizations;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'orgs' => 'Organizations\Controller\orgsController',
        ),
        'factories' => array(
            'Organizations\Model\Organization' => 'Organizations\Model\OrganizationFactory'
        ),
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
            'Organizations\Controller\Orgs' => 'Organizations\Controller\OrganizationsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'list_atps' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atps',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Orgs',
                        'action' => 'atps'
                    ),
                )
            ),
            'list_atcs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atcs',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Orgs',
                        'action' => 'atcs'
                    ),
                )
            ),
            'more' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Orgs',
                        'action' => 'more'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
            'new_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/new',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Orgs',
                        'action' => 'new'
                    )
                )
            ),
            'edit_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Orgs',
                        'action' => 'edit'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    )
                )
            )
        )
    )
);
