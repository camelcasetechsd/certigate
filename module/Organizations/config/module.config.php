<?php

namespace Organizations;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'service_manager' => array(
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
            'Organizations\Controller\Organizations' => 'Organizations\Controller\OrganizationsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'org_type' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/type',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'type'
                    ),
                )
            ),
            'list_atp_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atps',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'atps'
                    ),
                )
            ),
            'list_atc_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/atcs',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'atcs'
                    ),
                )
            ),
            'more' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
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
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'new'
                    )
                )
            ),
            'edit_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'edit'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    )
                )
            ),
            'delete_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/delete[/:id]',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'delete'
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*'
                    )
                )
            ),
            'saveState' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizations/savestate',
                    'defaults' => array(
                        'controller' => 'Organizations\Controller\Organizations',
                        'action' => 'saveState'
                    )
                )
            )
        )
    )
);
