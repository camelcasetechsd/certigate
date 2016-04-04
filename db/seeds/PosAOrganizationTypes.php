<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;

class PosAOrganizationTypes extends AbstractSeed
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
        /**
         * Declearing organization types
         */
        $org1 = array(
            "title" => "ATC",
        );
        $org2 = array(
            "title" => "ATP",
        );
        $org3 = array(
            "title" => "Distributor",
        );
        $org4 = array(
            "title" => "Re-Seller",
        );

        $this->insert('organization_type', $org1);

        $this->insert('organization_type', $org2);

        $this->insert('organization_type', $org3);

        $this->insert('organization_type', $org4);
    }

}
