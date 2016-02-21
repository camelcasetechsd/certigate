<?php

namespace Versioning;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                    ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'versioningVersion' => 'Versioning\Controller\VersionController',
        ),
        'factories' => array(
            'Versioning\Model\Version' => 'Versioning\Model\VersionFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Versioning\Controller\Version' => 'Versioning\Controller\VersionController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'versioningVersion' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /version the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/version[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'Versioning\Controller\Version',
                        'action' => 'index'
                    ),
                    /*
                      We only want to allow alphanumeric characters
                      with an exception to the dash and underscore.
                     */
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                )
            ),
            'versioningRestore' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/version/restore/:redirect/:id',
                    'defaults' => array(
                        'controller' => 'Versioning\Controller\Version',
                        'action' => 'restore',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'redirect' => '[a-zA-Z]+',
                    ),
                )
            ),
            'versioningDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/version/delete/:redirect/:id',
                    'defaults' => array(
                        'controller' => 'Versioning\Controller\Version',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'redirect' => '[a-zA-Z]+',
                    ),
                )
            ),
        )
    )
);
