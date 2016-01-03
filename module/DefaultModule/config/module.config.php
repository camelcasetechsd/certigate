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
        )
    )
);
