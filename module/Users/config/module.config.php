<?php

namespace Users;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'user' => 'Users\Controller\IndexController',
            'roles' => 'Users\Controller\RolesController'
        ),
        'factories' => array(
            'Users\Model\User' => 'Users\Model\UserFactory',
            'Users\Auth\Authentication' => 'Users\Auth\AuthenticationFactory',
            'Users\Event\AuthenticationEvent' => 'Users\Event\AuthenticationEventFactory'
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
            'Users\Controller\Index' => 'Users\Controller\IndexController',
            'Users\Controller\Roles' => 'Users\Controller\RolesController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'userEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/users/edit/:id',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Index',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'userDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/users/delete/:id',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Index',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'users' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /users the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/users[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the index controller.
                     */
                    'defaults' => array(
                        'controller' => 'Users\Controller\Index',
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
            'roles' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /cms the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/roles[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'Users\Controller\Roles',
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
            'rolesEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/roles/edit/:id',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Roles',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'rolesDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/roles/delete/:id',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Roles',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'rolesPrivileges' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/roles/privileges/:id',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Roles',
                        'action' => 'privileges',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
        )
    ),
);
