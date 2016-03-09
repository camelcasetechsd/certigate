<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;

class OrganizationTypes extends AbstractSeed 
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
        $org1 = array(
            "title" => "ATP",
        );
        $org2 = array(
            "title" => "ATC",
        );
        $org3 = array(
            "title" => "Distributor",
        );
        $org4 = array(
            "title" => "Re-Seller",
        );

        $this->insert('organizationType', $org1);
        $this->insert('organizationType', $org2);
        $this->insert('organizationType', $org3);
        $this->insert('organizationType', $org4);
    }

}
