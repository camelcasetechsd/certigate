<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;

class OrganizationRegions extends AbstractSeed
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
        $region1 = array(
            "title" => "Ha'il",
        );
        $region2 = array(
            "title" => "Qassim",
        );
        $region3 = array(
            "title" => "Riyadh",
        );
        $region4 = array(
            "title" => "Tabuk",
        );
        $region5 = array(
            "title" => "Madinah",
        );
        $region6 = array(
            "title" => "Makkah",
        );
        $region7 = array(
            "title" => "Bahah",
        );
        $region8 = array(
            "title" => "Northern Borders",
        );
        $region9 = array(
            "title" => "Jawf",
        );
        $region10 = array(
            "title" => "Jizan",
        );
        $region11 = array(
            "title" => "Asir",
        );
        $region12 = array(
            "title" => "Najran",
        );
        $region13 = array(
            "title" => "Eastern Province",
        );

        $this->insert('organization_region', $region1);
        $this->insert('organization_region', $region2);
        $this->insert('organization_region', $region3);
        $this->insert('organization_region', $region4);
        $this->insert('organization_region', $region5);
        $this->insert('organization_region', $region6);
        $this->insert('organization_region', $region7);
        $this->insert('organization_region', $region8);
        $this->insert('organization_region', $region9);
        $this->insert('organization_region', $region10);
        $this->insert('organization_region', $region11);
        $this->insert('organization_region', $region12);
        $this->insert('organization_region', $region13);
    }

}
