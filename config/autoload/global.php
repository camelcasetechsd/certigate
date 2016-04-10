<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'view_manager' => array(
        // We want to show the user if the page is not found
        'display_not_found_reason' => true,
        // We want to display exceptions when the occur
        'display_exceptions' => true,
        // This defines the doctype we want to use in our
        // output
        'doctype' => 'HTML5',
        // Here we define the error templates
        'not_found_template' => 'error/index',
        'exception_template' => 'error/index',
        // Create out template mapping
        'template_map' => array(
            // This is where the global layout resides
            'layout/layout' => __DIR__ . APPLICATION_THEMES.CURRENT_THEME.'layout/layout.phtml',
            'layout/messages' => __DIR__ . APPLICATION_THEMES.CURRENT_THEME.'layout/messages.phtml',
            // This defines where we can find the templates
            // for the error messages
            'error/404' => __DIR__ . APPLICATION_THEMES.CURRENT_THEME. 'layout/error/error.phtml',
            'error/index' => __DIR__ . APPLICATION_THEMES.CURRENT_THEME.'layout/error/error.phtml',
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
//            'translator' => 'MvcTranslator',
            'entitymanager' => 'doctrine.entitymanager.orm_default',
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => 'testpass',
                    'dbname' => 'certigate',
                )
            )
        )
    ),
    'mustache' => array(
        'suffix' => 'phtml',
        'pragmas' => array(
            Mustache_Engine::PRAGMA_BLOCKS
        ),
        'partials_loader' => array(
            dirname(__FILE__) .APPLICATION_THEMES.CURRENT_THEME.'layout',
            "extension" => ".phtml"
        )
    ),
    'mail_settings' => array(
        'name' => '104.130.122.2',
        'host' => 'smtp.mailgun.org',
        'connection_class' => 'plain',
        'connection_config' => array(
            'ssl' => 'tls',
            'username' => 'postmaster@sandbox2d80f78437a048b9832f621a18ce51e0.mailgun.org',
            'password' => '74bbf394d82b6eb4c52a2cf97acf2fd3'
        ),
        'port' => 587,
    ),
    'dompdf_module' => array(
        'default_font' => 'sans-serif',
    ),
    'website' => array(
        'host' => 'local-certigate.com'
    ),
    'courseEvent' => array(
        'periodicNotification' => 5 //No. of days
    ),
);
