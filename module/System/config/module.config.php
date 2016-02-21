<?php

namespace System;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'systemSettings' => 'System\Controller\SettingsController',
            'systemCacheHandler' => 'System\Service\CacheHandler',
        ),
        'factories' => array(
            'System\Service\CacheHandler' => 'System\Service\Cache\CacheHandlerFactory',
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
            'System\Controller\Settings' => 'System\Controller\SettingsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'systemSettings' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /system the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/system/settings[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'System\Controller\Settings',
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
            'systemSettingsEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/system/settings/edit/:id',
                    'defaults' => array(
                        'controller' => 'System\Controller\Settings',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'systemSettingsDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/system/settings/delete/:id',
                    'defaults' => array(
                        'controller' => 'System\Controller\Settings',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
)
    )
);
