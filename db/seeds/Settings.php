<?php

require_once 'init_autoloader.php';
require_once 'module/System/src/System/Service/Settings.php';

use Phinx\Seed\AbstractSeed;
use System\Service\Settings as SettingsConstants;

class Settings extends AbstractSeed {

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
