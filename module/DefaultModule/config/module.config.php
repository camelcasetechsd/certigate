<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'DefaultModule\Service\ContactUs' => 'DefaultModule\Service\ContactUsFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'DefaultModule\Controller\Index' => 'DefaultModule\Controller\IndexController',
            'DefaultModule\Controller\Sign' => 'DefaultModule\Controller\SignController',
            'DefaultModule\Controller\Error' => 'DefaultModule\Controller\ErrorController',
            'DefaultModule\Controller\ContactUs' => 'DefaultModule\Controller\ContactUsController',
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
            'contactUs' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/contact-us',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\ContactUs',
                        'action' => 'index',
                    ),
                ),
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
            'noAgreement' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/noagreement/:id/:role',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'noAgreement',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                        'role' => '[a-zA-Z%20\-,]+'
                    ),
                ),
            ),
            'resource_not_found' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/resource_not_found[/:message]',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'resourceNotFound',
                    ),
                    'constraints' => array(
                        'message' => '[A-Z_]+'
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
            'noOrganizationUsers' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/no-organization-users',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'noOrganizationUsers',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'test' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/test',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Index',
                        'action' => 'test',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'somethingWentWrong' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/something_went_wrong',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'somethingWentWrong',
                    ),
                ),
            ),
            'invalidToken' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/invalid-token',
                    'defaults' => array(
                        'controller' => 'DefaultModule\Controller\Error',
                        'action' => 'invalidToken',
                    ),
                ),
            ),
        )
    )
);
