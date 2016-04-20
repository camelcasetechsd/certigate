<?php

if (array_key_exists("mainAppUserData", $_SESSION) && !isset($_SESSION[UID . 'USER']['id'])) {
    $userData = $_SESSION["mainAppUserData"];
    $username = str_replace('"', '&quot;', $userData['username']);
    // check if user exists
    $user = \CODOF\User\User::getByUsername($username);
    // not exist -> create user then login with that user
    // exists -> login that user
    if ($user === false) {
        $db = \DB::getPDO();
        $register = new \CODOF\User\Register($db);
        $register->username = $username;
        $register->name = $userData['name'];
        $register->password = uniqid();
        $register->mail = $userData['email'];
        $register->avatar = "../../../../../../.." . $userData['photo'];
        $register->user_status = 1;
        $register->rid = ROLE_USER;
        $errors = $register->register_user();
        $register->login();
    }
    else {
        $user->loginByMail($userData['email']);
    }
    \CODOF\Hook::call('before_login');
}

