<?php

return array(
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
            'userCreate' => array(
                'resource' => 'Users',
            ),
            'cmsPageView' => array(
                'resource' => 'CMS',
            )
        )
    )
);
