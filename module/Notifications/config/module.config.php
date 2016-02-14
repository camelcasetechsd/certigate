<?php

namespace Notifications;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../../../template/default',
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
    'service_manager' => array(
        'factories' => array(
            'Notifications\Service\Notification' => 'Notifications\Service\NotificationFactory',
        ),
    ),
);
