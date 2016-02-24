<?php

namespace Translation;

return array(
    'router' => array(),
    'service_manager' => array(
        'factories' => array(
            'CMS\Service\TranslatorHandler' => 'Translation\Service\Translator\TranslatorHandlerFactory',
        ),
        'aliases' => array(
            'translatorHandler' => 'CMS\Service\TranslatorHandler',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ),
        ),
    ),
    'controllers' => array(),
    'view_manager' => array(),
);
