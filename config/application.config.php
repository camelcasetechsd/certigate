<?php

use Zend\Console\Console;

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
$env = APPLICATION_ENV;
if (!ini_get('date.timezone')) {
    date_default_timezone_set("UTC");
}

// defining path for application themes directory
defined('APPLICATION_THEMES') || define('APPLICATION_THEMES', (getenv('APPLICATION_THEMES') ? getenv('APPLICATION_THEMES') : '/../../themes/'));

// defining Name of the current theme
defined('CURRENT_THEME') || define('CURRENT_THEME', (getenv('CURRENT_THEME') ? getenv('CURRENT_THEME') : 'certigate/'));

/**
 * Configuration file generated by ZFTool
 * The previous configuration file is stored in application.config.old
 *
 * @see https://github.com/zendframework/ZFTool
 */
$config = array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'CustomDoctrine',
        'SlmQueue',
        'SlmQueueDoctrine',
        'DefaultModule',
        'CMS',
        'Users',
        'Utilities',
        'LosI18n',
        'Mustache',
        'CustomMustache',
        'CertigateAcl',
        'Organizations',
        'Courses',
        'Versioning',
        'System',
        'Notifications',
        'DOMPDFModule'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor'
        ),
        'config_glob_paths' => array(
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $env)
        )
    )
);

if (Console::isConsole()) {
    $key = array_search('CustomMustache', $config['modules']);
    unset($config['modules'][$key]);
}

return $config;
