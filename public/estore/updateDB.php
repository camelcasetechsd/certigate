<?php

// Configuration
if (is_file(__DIR__ . '/config.php')) {
    require_once(__DIR__ . '/config.php');
}
$passwordPart = " ";
if (!empty(DB_PASSWORD)) {
    $passwordPart = " -p'" . DB_PASSWORD . "'";
}
$databaseStatus = shell_exec('mysql -u ' . DB_USERNAME . $passwordPart . ' -D ' . DB_DATABASE . ' --execute="SELECT CASE COUNT(*) WHEN \'0\' THEN \'empty database\' ELSE \'has tables\' END AS contents FROM information_schema.tables WHERE table_type = \'BASE TABLE\' AND table_schema = \'' . DB_DATABASE . '\';"');
if (strpos($databaseStatus, "has tables") === false) {
    shell_exec("mysql -u " . DB_USERNAME . " $passwordPart -D " . DB_DATABASE . " < " . __DIR__ . "/certigate_estore.sql");
}