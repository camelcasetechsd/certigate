<?php

namespace Translation;

return array(
    'service_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
        'factories' => array(
            'CMS\Service\TranslatorHandler' => 'Translation\Service\Translator\TranslatorHandlerFactory',
        ),
        'aliases' => array(
            'translatorHandler' => 'CMS\Service\TranslatorHandler',
        ),
    ),
    'translator' => array(
        'local' => 'ar_Ar' ,
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Translation\Controller\Index' => 'Translation\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'organizationUsers' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/trans/index',
                    'defaults' => array(
                        'controller' => 'Translation\Controller\Index',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                )
            ),
        )
    )
);
