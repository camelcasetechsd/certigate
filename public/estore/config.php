<?php
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'vagrant'));
$env = APPLICATION_ENV;
require_once __DIR__ . '/config/' . $env . '.php';
