<?php

require_once __DIR__.'/../AbstractSeed.php';

use db\AbstractSeed;
use System\Service\Settings as SettingsConstants;

class PosBSettings extends AbstractSeed {

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
        
        $settings = array(
            array(
            'name' => SettingsConstants::ADMIN_EMAIL,
            'value' => $faker->freeEmail
                ), 
            array(
            'name' => SettingsConstants::OPERATIONS_EMAIL,
            'value' => $faker->freeEmail
                ), 
            array(
            'name' => SettingsConstants::TVTC_EMAIL,
            'value' => $faker->freeEmail
                ),
            array(
            'name' => SettingsConstants::SYSTEM_EMAIL,
            'value' => $faker->freeEmail
                ),
            );
        $this->insert('setting', $settings);
    }

}
