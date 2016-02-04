<?php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir( dirname( __DIR__ ) );
defined( 'APPLICATION_PATH' ) || define( 'APPLICATION_PATH', (getenv( 'APPLICATION_PATH' ) ? getenv( 'APPLICATION_PATH' ) : __DIR__ ) );

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file( __DIR__ . parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) )) {
    return false;
}


// Define application environment
defined( 'APPLICATION_ENV' ) || define( 'APPLICATION_ENV', (getenv( 'APPLICATION_ENV' ) ? getenv( 'APPLICATION_ENV' ) : 'production' ) );

switch (APPLICATION_ENV) {
    case 'vagrant':
    case 'development':
        error_reporting( E_ALL );
        ini_set( "display_errors", 'on' );
        break;
    case 'production':
    case 'acceptance':
    default:
        break;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init( require 'config/application.config.php' )->run();
