<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;

class OrganizationGoverns extends AbstractSeed
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
        $gov1 = array(
            "title" => "gov1",
        );
        $gov2 = array(
            "title" => "gov2",
        );
        $gov3 = array(
            "title" => "gov3",
        );
        $gov4 = array(
            "title" => "gov4",
        );
        $gov5 = array(
            "title" => "gov5",
        );
        $gov6 = array(
            "title" => "gov6",
        );

        $this->insert('organization_governorate', $gov1);
        $this->insert('organization_governorate', $gov2);
        $this->insert('organization_governorate', $gov3);
        $this->insert('organization_governorate', $gov4);
        $this->insert('organization_governorate', $gov5);
        $this->insert('organization_governorate', $gov6);
    }

}
