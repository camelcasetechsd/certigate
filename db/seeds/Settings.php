<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/System/Service/Settings.php';

use Phinx\Seed\AbstractSeed;
use System\Service\Settings;

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
            'name' => Settings::ADMIN_EMAIL,
            'value' => $faker->freeEmail
                ), 
            array(
            'name' => Settings::OPERATIONS_EMAIL,
            'value' => $faker->freeEmail
                ), 
            array(
            'name' => Settings::TVTC_EMAIL,
            'value' => $faker->freeEmail
                ),
            array(
            'name' => Settings::SYSTEM_EMAIL,
            'value' => $faker->freeEmail
                ),
            );
        $this->insert('setting', $settings);
    }

}
