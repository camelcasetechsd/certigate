<?php

// Configuration
if (is_file(__DIR__ . '/config.php')) {
    require_once(__DIR__ . '/config.php');
}
$passwordPart = " ";
if (!empty(DB_PASSWORD)) {
    $passwordPart = " -p'" . DB_PASSWORD . "'";
}
$databaseStatus = shell_exec('mysql -u ' . DB_USERNAME . $passwordPart . ' -D ' . DB_DATABASE . ' --execute="SELECT CASE COUNT(*) WHEN \'0\' THEN \'empty database\' ELSE \'has tables\' END AS contents FROM information_schema.tables WHERE table_name like \'' . DB_PREFIX . '%\' AND table_type = \'BASE TABLE\' AND table_schema = \'' . DB_DATABASE . '\';"');
if (strpos($databaseStatus, "has tables") === false) {
    shell_exec("mysql -u " . DB_USERNAME . " $passwordPart -D " . DB_DATABASE . " < " . __DIR__ . "/certigate_forum.sql");
}

$singleSignData = array(
    "sso_get_user_path" => SSO_GET_USER_PATH,
    "sso_login_user_path" => SSO_LOGIN_USER_PATH,
    "sso_logout_user_path" => SSO_LOGOUT_USER_PATH,
    "sso_register_user_path" => SSO_REGISTER_USER_PATH,
    "sso_client_id" => SSO_CLIENT_ID,
    "sso_secret" => SSO_SECRET,
    "sso_name" => SSO_NAME,
);
foreach ($singleSignData as $fieldName => $fieldValue){
    shell_exec("mysql -u " . DB_USERNAME . " $passwordPart -D " . DB_DATABASE . " -e \"UPDATE " . DB_PREFIX . "config SET option_value = '" . $fieldValue . "' WHERE option_name='" . $fieldName . "'\"");
}