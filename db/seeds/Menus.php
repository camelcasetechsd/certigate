<?php

require_once 'init_autoloader.php';
require_once 'module/CMS/src/CMS/Entity/Menu.php';

use Phinx\Seed\AbstractSeed;
use \CMS\Entity\Menu;

class Menus extends AbstractSeed
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
        $menu = [
            "title" => "Primary Menu",
            "status" => true
        ];

        $this->insert( 'menu', $menu );
    }

}
