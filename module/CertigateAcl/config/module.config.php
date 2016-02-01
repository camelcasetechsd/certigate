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
            'DefaultModule',
            'Utilities',
            'Mustache',
            'CustomMustache',
            'CertigateAcl',
            'LosI18n',
            'SlmQueue',
            'SlmQueueDoctrine',
            'Versioning',
            'Notifications'
        ),
        'anonymous_routes' => array(
            'userCreate' => array(
                'resource' => 'Users',
                'privileges' => 'userCreate',
            ),
            'cmsPageView' => array(
                'resource' => 'CMS',
                'privileges' => 'cmsPageView',
            )
        )
    )
);
