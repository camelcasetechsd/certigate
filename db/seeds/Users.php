<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User;

class Users extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $role = [
            'name' => 'Admin'
        ];
        $this->insert('role', $role);
        $adminRoleId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $user = [
            "name" => "Admin",
            "username" => "admin",
            "password" => User::hashPassword( "adminadmin" ),
            "mobile" => "01115991948",
            "dateOfBirth" => "'2015-11-01'",
            "photo" => '/upload/images/userdefault.png',
            "maritalStatus" => "single",
            "description" => "admin user",
            "status" => true
        ];
        $this->insert( 'user', $user );
        $adminUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $this->insert('user_role',
            [
                'user_id' => $adminUserId,
                'role_id' => $adminRoleId
            ]);
    }

}
