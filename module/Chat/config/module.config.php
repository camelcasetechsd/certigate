<?php

namespace Chat;

return array(
    'view_manager' => array(
        'template_path_stack' => array(),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Chat\Model\Chat' => 'Chat\Model\ChatFactory',
            'Chat\Service\ChatHandler' => 'Chat\Service\ChatHandlerFactory',
            'Chat\Service\ChatServer' => 'Chat\Service\ChatServerFactory',
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
            'Chat\Controller\Chat' => 'Chat\Controller\ChatController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'startChat' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/chat',
                    'defaults' => array(
                        'controller' => 'Chat\Controller\Chat',
                        'action' => 'startChat',
                    ),
                ),
            ),
            'minimizeChat' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/chat/minimize',
                    'defaults' => array(
                        'controller' => 'Chat\Controller\Chat',
                        'action' => 'minimizeChat',
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'startServer' => array(
                    'options' => array(
                        'route' => 'chat-server',
                        'defaults' => array(
                            'controller' => 'Chat\Controller\Chat',
                            'action' => 'runServer'
                        )
                    )
                )
            )
        )
    ),
);
