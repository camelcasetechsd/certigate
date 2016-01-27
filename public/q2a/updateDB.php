<?php

require_once __DIR__.'/qa-config.php';
shell_exec("mysql -u ".QA_MYSQL_USERNAME." -p".QA_MYSQL_PASSWORD." -D ".QA_MYSQL_DATABASE." < ".__DIR__."/qaDumpSql.sql");
