<?php

// HTTP
define('HTTP_SERVER', 'http://local-certigate.com/estore/admin/');
define('HTTP_CATALOG', 'http://local-certigate.com/estore/');

// HTTPS
define('HTTPS_SERVER', 'http://local-certigate.com/estore/admin/');
define('HTTPS_CATALOG', 'http://local-certigate.com/estore/');

// DIR
$dir = __DIR__;

define('DIR_APPLICATION', $dir . '/../../admin/');
define('DIR_SYSTEM', $dir . '/../../system/');
define('DIR_LANGUAGE', $dir . '/../../admin/language/');
define('DIR_TEMPLATE', $dir . '/../../admin/view/template/');
define('DIR_CONFIG', $dir . '/../../system/config/');
define('DIR_IMAGE', $dir . '/../../image/');
define('DIR_CACHE', $dir . '/../../system/storage/cache/');
define('DIR_DOWNLOAD', $dir . '/../../system/storage/download/');
define('DIR_LOGS', $dir . '/../../system/storage/logs/');
define('DIR_MODIFICATION', $dir . '/../../system/storage/modification/');
define('DIR_UPLOAD', $dir . '/../../system/storage/upload/');
define('DIR_CATALOG', $dir . '/../../catalog/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'testpass');
define('DB_DATABASE', 'certigate');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
