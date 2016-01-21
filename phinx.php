<?php
if (!ini_get('date.timezone')) {
    date_default_timezone_set("UTC");
}
$config = [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'vagrant',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => 3306,
            'charset' => 'utf8'
        ],
        'uat' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'development_db',
            'user' => 'root',
            'pass' => '',
            'port' => 3306,
            'charset' => 'utf8'
        ],
        'vagrant' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'certigate',
            'user' => 'root',
            'pass' => 'testpass',
            'port' => 3306,
            'charset' => 'utf8'
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'certigate',
            'user' => 'camelcasetech',
            'pass' => 'c@m31C@$3T3c4',
            'port' => 3306,
            'charset' => 'utf8'
        ],
    ]
];


return $config;