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
            'Notifications',
            'DOMPDFModule',
            'EStore',
            'DefaultModule',
            'Translation',
            'Chat'
        ),
        'anonymous_routes' => array(
//            'contactUs' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'defaultSign' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'noaccess' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'noAgreement' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'resource_not_found' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'home' => array(
//                'resource' => 'DefaultModule',
//            ),
//            'noOrganizationUsers' => array(
//                'resource' => 'DefaultModule',
//            ),
            'cmsPageView' => array(
                'resource' => 'CMS',
            ),
            'cmsPressReleaseList' => array(
                'resource' => 'CMS',
            ),
            'cmsPressReleaseDetails' => array(
                'resource' => 'CMS',
            ),
            'cmsPressReleaseUnsubscribe' => array(
                'resource' => 'CMS',
            ),
//            'translationSetLocale' => array(
//                'resource' => 'Translation',
//            ),
            'registration' => array(
                'resource' => 'Users'
            ),
            'refreshcaptcha' => array(
                'resource' => 'Users'
            ),
            'rolesStatements' => array(
                'resource' => 'Users'
            ),
        )
    )
);
