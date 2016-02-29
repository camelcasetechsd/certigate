<?php

namespace Users\Entity\Fixture;

use Users\Entity\Role as RoleEntity;

/**
 * UserCredentials usernames and passwords for users used in testing
 * 
 * @package users
 * @subpackage entity
 */
class UserCredentials
{
    static public $userCredentials = array(
        RoleEntity::PROCTOR_ROLE => array(
            "password" => 'proctorP@$$w0rd'
        ),
        RoleEntity::STUDENT_ROLE => array(
            "password" => 'studentP@$$w0rd'
        ),
        RoleEntity::TEST_CENTER_ADMIN_ROLE => array(
            "password" => 'testcenteradminP@$$w0rd'
        ),
        RoleEntity::TRAINING_MANAGER_ROLE => array(
            "password" => 'trainingmanagerP@$$w0rd'
        ),
        RoleEntity::USER_ROLE => array(
            "password" => 'userP@$$w0rd'
        ),
        RoleEntity::ADMIN_ROLE  => array(
            "password" => 'adminP@$$w0rd'
        ),
    );
}