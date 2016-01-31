<?php

require_once 'init_autoloader.php';

use Phinx\Seed\AbstractSeed;

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
        $settings = array(array(
            'name' => 'Admin_Email',
            'value' => 'admin@armyspy.com'
                ), array(
            'name' => 'Operations',
            'value' => 'ops@armyspy.com'
                ), array(
            'name' => 'TVTC',
            'value' => 'tvtc@armyspy.com'
        ));
        $this->insert('setting', $settings);
    }

}
