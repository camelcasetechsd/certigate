<?php

namespace Translation;

return array(
    'service_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../' . APPLICATION_THEMES . CURRENT_THEME . 'modules',
        ),
        'factories' => array(
            'Translation\Service\TranslatorHandler' => 'Translation\Service\Translator\TranslatorHandlerFactory',
            'Translation\Helper\TranslatorHelper' => 'Translation\Helper\TranslatorHelperFactory',
            'Translation\Service\Locale' => 'Translation\Service\Locale\LocaleFactory',
        ),
        'aliases' => array(
            'translatorHandler' => 'Translation\Service\TranslatorHandler',
            'translatorHelper' => 'Translation\Helper\TranslatorHelper',
            'applicationLocale' => 'Translation\Service\Locale',
        ),
    ),
    'translator' => array(
        'locale' => 'ar_AR' ,
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
            'translationTest' => array(
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
            'translationSetLocale' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/trans/setlocale/:locale',
                    'defaults' => array(
                        'controller' => 'Translation\Controller\Index',
                        'action' => 'setLocale',
                    ),
                    'constraints' => array(
                        'locale' => '[a-z]{2}_[A-Z]{2}',
                    ),
                )
            ),
        )
    )
);
