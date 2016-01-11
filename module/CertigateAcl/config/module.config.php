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
            'CertigateAcl'
        )
    )
);
