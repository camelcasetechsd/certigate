<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User;
use \Users\Entity\Role;

class Users extends AbstractSeed {

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run() {
        $faker = Faker\Factory::create();
        
        $userRole = array('name' => 'User');
        $this->insert('role', $userRole);
        $normalUserRoleId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $adminRole = array('name' => Role::ADMIN_ROLE);
        $this->insert('role', $adminRole);
        $adminRoleId = $this->getAdapter()->getConnection()->lastInsertId();
                
        $adminUser = array(
                "firstName" => "Admin",
                "middleName" => "Foo",
                "lastName" => "Bar",
                "country" => $faker->countryCode,
                "language" => $faker->languageCode,
                "username" => "admin",
                "password" => User::hashPassword("adminadmin"),
                "mobile" => "01115991948",
                "dateOfBirth" => date('Y-m-d H:i:s'),
                "photo" => '/upload/images/userdefault.png',
                "status" => true
            );
        $this->insert('user', $adminUser);
        $adminUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $normalUser = array(
                "firstName" => "User",
                "middleName" => "Foo",
                "lastName" => "Bar",
                "country" => $faker->countryCode,
                "language" => $faker->languageCode,
                "username" => "user",
                "password" => User::hashPassword("useruser"),
                "mobile" => "01115991948",
                "dateOfBirth" => date('Y-m-d H:i:s'),
                "photo" => '/upload/images/userdefault.png',
                "status" => true
            );
        $this->insert('user', $normalUser);
        $normalUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $userRoles = array(array(
            'user_id' => $adminUserId,
            'role_id' => $adminRoleId
                ), array(
            'user_id' => $normalUserId,
            'role_id' => $normalUserRoleId
        ));
        $this->insert('user_role', $userRoles);
    }

}
