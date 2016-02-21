<?php

namespace CMS;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../../../themes/default/modules',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'cmsPage' => 'CMS\Controller\PageController',
            'cmsMenu' => 'CMS\Controller\MenuController',
            'cmsMenuItem' => 'CMS\Controller\MenuItemController',
            'cmsCacheHandler' => 'CMS\Service\CacheHandler',
            'cmsMenuView' => 'CMS\Service\View\MenuView',
        ),
        'factories' => array(
            'CMS\Model\Page' => 'CMS\Model\PageFactory',
            'CMS\Model\MenuItem' => 'CMS\Model\MenuItemFactory',
            'CMS\Service\CacheHandler' => 'CMS\Service\Cache\CacheHandlerFactory',
            'CMS\Event\RouteEvent' => 'CMS\Event\RouteEventFactory',
        ),
        'invokables' => array(
            'CMS\Service\View\MenuView' => 'CMS\Service\View\MenuView',
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
            'CMS\Controller\Page' => 'CMS\Controller\PageController',
            'CMS\Controller\Menu' => 'CMS\Controller\MenuController',
            'CMS\Controller\MenuItem' => 'CMS\Controller\MenuItemController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'cmsPage' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /cms the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/cms/page[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
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
            'cmsPageHistory' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/page/history/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'history',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsPageEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/page/edit/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsPageDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/page/delete/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsPageActivate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/page/activate/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'activate',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            /**
             * action to upload photos
             */
            'cmsPageUpload' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/cms/page/upload',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'imgUpload',
                    ),
                )
            ),
            /**
             * action to browse photos
             */
            'cmsPageBrowse' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/cms/page/browse',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Page',
                        'action' => 'browse',
                    ),
                )
            ),
            'cmsMenu' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /cms the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/cms/menu[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Menu',
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
            'cmsMenuEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/menu/edit/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Menu',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsMenuDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/menu/delete/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\Menu',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsMenuItem' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    /*
                      We want to make /cms the main end point, with
                      an optional controller and action.
                     */
                    'route' => '/cms/menuitem[/:action]',
                    /*
                      We want a default end point (if no controller
                      and action is given) to go to the index action
                      of the controller.
                     */
                    'defaults' => array(
                        'controller' => 'CMS\Controller\MenuItem',
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
            'cmsMenuItemEdit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/menuitem/edit/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\MenuItem',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsMenuItemDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/menuitem/delete/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\MenuItem',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
            'cmsMenuItemActivate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/cms/menuitem/activate/:id',
                    'defaults' => array(
                        'controller' => 'CMS\Controller\MenuItem',
                        'action' => 'activate',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                )
            ),
        )
    )
);
