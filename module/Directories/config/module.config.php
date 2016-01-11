<?php

namespace Directories;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'atps' => 'Directories\Controller\AtpsController',
            'atcs' => 'Directories\Controller\AtcsController'
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
            'Directories\Controller\Atps' => 'Directories\Controller\AtpsController',
            'Directories\Controller\Atcs' => 'Directories\Controller\AtcsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'list_atps' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atps',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atps',
                        'action' => 'index'
                    ),
                )
            ),
            'more_atp' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atps/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atps',
                        'action' => 'more'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
            'new_atp' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atps/new',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atps',
                        'action' => 'new'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
            'list_atcs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atcs',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atcs',
                        'action' => 'index'
                    ),
                )
            ),
            'more_atc' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atcs/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atcs',
                        'action' => 'more'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
            'new_atc' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/directories/atcs/new',
                    'defaults' => array(
                        'controller' => 'Directories\Controller\Atcs',
                        'action' => 'new'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    ),
                )
            ),
        )
    )
);
