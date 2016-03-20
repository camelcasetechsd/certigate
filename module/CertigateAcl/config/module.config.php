<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'aclValidator' => 'CertigateAcl\Service\AclValidator',
        ),
        'factories' => array(
            'CertigateAcl\Service\AclValidator' => 'CertigateAcl\Service\AclValidatorFactory',
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'CertigateAclPlugin' => 'CertigateAcl\Controller\Plugin\CertigateAclPlugin',
        )
    ),
    'roles_management' => array(
        'excluded_modules' => array(
            'DoctrineModule',
            'DoctrineORMModule',
            'Utilities',
            'Mustache',
            'CustomDoctrine',
            'CustomMustache',
            'CertigateAcl',
            'LosI18n',
            'SlmQueue',
            'SlmQueueDoctrine',
            'Versioning',
            'Notifications'
        ),
        'anonymous_routes' => array(
            'contactUs' => array(
                'resource' => 'DefaultModule',
            ),
            'defaultSign' => array(
                'resource' => 'DefaultModule',
            ),
            'noaccess' => array(
                'resource' => 'DefaultModule',
            ),
            'noAgreement' => array(
                'resource' => 'DefaultModule',
            ),
            'resource_not_found' => array(
                'resource' => 'DefaultModule',
            ),
            'home' => array(
                'resource' => 'DefaultModule',
            ),
            'userCreate' => array(
                'resource' => 'Users',
            ),
            'cmsPageView' => array(
                'resource' => 'CMS',
            ),
            'translationSetLocale' => array(
                'resource' => 'Translation',
            )
        )
    )
);
