<?php
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'vagrant'));
$env = APPLICATION_ENV;

require_once 'config/' . $env . '.php';

define('SSO_GET_USER_PATH', HOME_PAGE_URL."users/details");
define('SSO_LOGIN_USER_PATH', HOME_PAGE_URL."sign/in");
define('SSO_LOGOUT_USER_PATH', HOME_PAGE_URL."sign/out");
define('SSO_REGISTER_USER_PATH', HOME_PAGE_URL."users/new");
define('SSO_CLIENT_ID', 'certigateforum');
define('SSO_SECRET', 'C371i9@13Xe24!rf');
define('SSO_NAME', 'certigate');
define('DB_PREFIX', 'codo_');