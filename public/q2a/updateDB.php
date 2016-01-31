<?php

require_once __DIR__.'/qa-config.php';
$passwordPart = "";
if(!empty(QA_MYSQL_PASSWORD)){
    $passwordPart = "-p".QA_MYSQL_PASSWORD;
}
shell_exec("mysql -u ".QA_MYSQL_USERNAME." $passwordPart -D ".QA_MYSQL_DATABASE." < ".__DIR__."/qaDumpSql.sql");
