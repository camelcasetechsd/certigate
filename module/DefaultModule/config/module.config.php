<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'DefaultModule\Controller\Index' => 'DefaultModule\Controller\IndexController',
            'DefaultModule\Controller\Sign' => 'DefaultModule\Controller\SignController',
            'DefaultModule\Controller\Error' => 'DefaultModule\Controller\ErrorController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'defaultSign' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /sign the main end point, with
                      an optional action.
                     */
                    'route' => '/sign[/:action]',
                    /*
                      We want a default end point (if no
                      action is given) to go to the index action
                      of the sign controller.
                     */
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Sign',
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
            'noaccess' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/noaccess',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'noaccess',
                    ),
                ),
            ),
            'resource_not_found' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/resource_not_found',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'resourceNotFound',
                    ),
                ),
            ),
            'generalResources' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/general-resources',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Index',
                        'action' => 'generalResources',
                    ),
                ),
            ),
            'download_resources' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/general-resources/download[/:filename]',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Index',
                        'action' => 'download',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        )
    )
);
