<?php

require_once __DIR__.'/qa-config.php';
$passwordPart = "";
if(!empty(QA_MYSQL_PASSWORD)){
    $passwordPart = "-p'".QA_MYSQL_PASSWORD."'";
}
shell_exec("mysql -u ".QA_MYSQL_USERNAME." $passwordPart -D ".QA_MYSQL_DATABASE." < ".__DIR__."/qaDumpSql.sql");

$specificDomainData = array(
    "from_email" => CONFIG_FROM_EMAIL,
    "site_title" => CONFIG_SITE_TITLE,
    "site_url" => CONFIG_SITE_URL,
);
foreach ($specificDomainData as $fieldName => $fieldValue){
    shell_exec("mysql -u ".QA_MYSQL_USERNAME." $passwordPart -D ".QA_MYSQL_DATABASE." -e \"UPDATE " . QA_MYSQL_TABLE_PREFIX . "options SET content = '" . $fieldValue . "' WHERE title='" . $fieldName . "'\"");
}