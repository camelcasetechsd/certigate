<?php

namespace Orgs;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'orgs' => 'Orgs\Controller\orgsController',
        ),
        'factories' => array(
            'Orgs\Model\Org' => 'Orgs\Model\OrgFactory'
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
            'Orgs\Controller\Orgs' => 'Orgs\Controller\OrgsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'org_type' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/orgs/type',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
                        'action' => 'type'
                    ),
                )
            ),
            'list_atp_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/orgs/atps',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
                        'action' => 'atps'
                    ),
                )
            ),
            'list_atc_orgs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/orgs/atcs',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
                        'action' => 'atcs'
                    ),
                )
            ),
            'more' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/orgs/more[/:id]',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
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
                    'route' => '/orgs/new',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
                        'action' => 'new'
                    )
                )
            ),
            'edit_org' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/orgs/edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Orgs\Controller\Orgs',
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
