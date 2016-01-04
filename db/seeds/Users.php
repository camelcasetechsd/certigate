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
        $user = [
            "role_id" => NULL,
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
    }

}
