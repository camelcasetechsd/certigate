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
        // Primary Menu
        $menu = [
            "title" => "Primary Menu",
            "status" => true
        ];

        $this->insert( 'menu', $menu );
        $primaryMenuId = $this->getAdapter()->getConnection()->lastInsertId();
        $primaryMenuItems = [
            "home" => [
                "title" => "Home",
                "path" => "/",
                "weight" => 1
            ],
            "about" => [
                "title" => "About",
                "path" => "/about",
                "type" => "page",
                "weight" => 2,
                "children" => [
                    [
                        "title" => "Corporate Profile",
                        "path" => "/corportate_profile",
                        "type" => "page",
                    ],
                    [
                        "title" => "How can Certigate Pro help you ?",
                        "path" => "/how_can_certigate_pro_help_you",
                        "type" => "page",
                    ],
                    [
                        "title" => "Media Center",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "Ask the Expert",
                                "path" => "/q2a",
                            ],
                        ]
                    ],
                    [
                        "title" => "Career Center",
                        "path" => "/career_center",
                        "type" => "page",
                    ],
                    [
                        "title" => "Contact us",
                        "path" => "/conctactus",
                        "type" => "page",
                    ],
                ]
            ],
            "testing_center" => [
                "title" => "Testing Center",
                "path" => "#",
                "weight" => 3,
                "children" => [
                    [
                        "title" => "ATC Program",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "Overview",
                                "path" => "/atc_overview",
                                "type" => "page",
                            ],
                            [
                                "title" => "ATC Benefits",
                                "path" => "/atc_benefits",
                                "type" => "page",
                            ],
                            [
                                "title" => "ATC Selection Criteria",
                                "path" => "/atc_selection_criteria",
                                "type" => "page",
                            ],
                            [
                                "title" => "ATC SOP",
                                "path" => "/atc_overview",
                                "type" => "page",
                            ],
                            [
                                "title" => "Apply to be an ATC",
                                "path" => "/organizations/new?organization=1",
                            ],
                            [
                                "title" => "Apply to be an ATC Administrator",
                                "path" => "/organizations/new?organization=1",
                            ],
                            [
                                "title" => "Apply to be an ATC Proctor",
                                "path" => "/organizations/new?organization=1",
                            ],
                        ]
                    ],
                    [
                        "title" => "ATC Login",
                        "path" => "/sign/in",
                    ],
                    [
                        "title" => "ATC Directory",
                        "path" => "/organizations/atcs",
                    ],
                ]
            ],
            "estore" => [
                "title" => "eStore",
                "path" => "http://estore.local-certigate.com/",
                "weight" => 4
            ],
            "instructorCalendar" => [
                "title" => "Instructor Training Calendar",
                "path" => "/courses/instructor-calendar",
                "weight" => 5
            ],
        ];

        foreach ($primaryMenuItems as $item) {
            $this->insertMenuItem( $item, $primaryMenuId );
        }


        // Admin Menu
        $menu = [
            "title" => "Admin Menu",
            "status" => true
        ];

        $this->insert( 'menu', $menu );
    }

    public function insertMenuItem( $item, $primaryMenuId, $parentId = null )
    {
        $this->insert( 'menuItem', [
            'parent_id' => $parentId,
            'menu_id' => $primaryMenuId,
            'title' => $item['title'],
            'path' => $item['path'],
            'weight' => (isset( $item['weight'] )) ? $item['weight'] : 1,
            'status' => true
        ] );
        $menuItemParentId = $this->getAdapter()->getConnection()->lastInsertId();
        if (isset( $item['type'] ) && $item['type'] == "page") {
            $this->insertPageForMenuItem( $item, $menuItemParentId );
        }
        if (isset( $item['children'] ) && count( $item['children'] ) > 0) {
            foreach ($item['children'] as $childItem) {
                $this->insertMenuItem( $childItem, $primaryMenuId, $menuItemParentId );
            }
        }
    }

    public function insertPageForMenuItem( $item, $itemId )
    {
        $faker = Faker\Factory::create();

        $this->insert( 'page', [
            'menuitem_id' => $itemId,
            'title' => $item['title'],
            'body' => base64_encode( bzcompress( $faker->text ) )
        ] );
    }

}
