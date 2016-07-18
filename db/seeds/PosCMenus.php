<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use \CMS\Entity\MenuItem;
use \CMS\Service\PageTypes;

class PosCMenus extends AbstractSeed
{

    /**
     *
     * @var array static pages content
     */
    private $staticPagesContent;
    
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
        $filename = __DIR__ . "/../staticContent/pages.json";
        $this->staticPagesContent = json_decode(file_get_contents($filename));
                
        // Primary Menu
        $menu = [
            "title" => "Primary Menu",
            "titleAr" => "Primary Menu",
            "status" => true
        ];

        $this->insert('menu', $menu);
        $primaryMenuId = $this->getAdapter()->getConnection()->lastInsertId();


        $primaryMenuItems = [

            //first menu
            "Training & Certification" => [
                "title" => "Training & Certification",
                "titleAr" => "Training & Certification",
                "weight" => 1,
                "path" => "#",
                "children" => [
                    [
                        "title" => "CGP Training",
                        "titleAr" => "CGP Training",
                        "path" => "#",
                        "weight" => 1,
                        "children" => [
                            [

                                "title" => "Public Training",
                                "titleAr" => "Public Training",
                                "path" => "/quote/training/public",
                                "weight" => 1,
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "Private Training",
                                "titleAr" => "Private Training",
                                "path" => "/quote/training/private",
                                "weight" => 2,
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "Training Quotations",
                                "titleAr" => "Training Quotations",
                                "path" => "/quote",
                                "weight" => 3,
                            ]
                        ],
                    ]
                    ,
                    [
                        "title" => "Training Partner",
                        "titleAr" => "Training Partner",
                        "path" => "#",
                        "weight" => 2,
                        "children" => [
                            [
                                "title" => "ATP Program",
                                "titleAr" => "ATP Program",
                                "path" => "/atp_program",
                                "type" => "page",
                                "weight" => 1,
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "ATP Directory",
                                "titleAr" => "ATP Directory",
                                "path" => "/organizations/atps",
                                "weight" => 2,
                                "children" => [
                                ]
                            ],
                        ]
                    ],
                    [
                        "title" => "Certified Instructors",
                        "titleAr" => "Certified Instructors",
                        "path" => "#",
                        "weight" => 4,
                        "children" =>
                        [
                            [
                                "title" => "welcome page",
                                "titleAr" => "welcome page",
                                "path" => "/welcome_page",
                                "weight" => 1,
                                "type" => "page"
                            ],
                            [
                                "title" => "Certified Instructor",
                                "titleAr" => "Certified Instructor",
                                "path" => "/certified_instructor",
                                "weight" => 4,
                                "type" => "page"
                            ],
                            [
                                "title" => "Instructors Directory",
                                "titleAr" => "Instructors Directory",
                                "path" => "/users/instructors",
                                "weight" => 5,
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "Authorized Instructor Program (AIP)",
                                "titleAr" => "Authorized Instructor Program (AIP)",
                                "path" => "/courses/instructor-training",
                                "weight" => 6,
                                "children" => [
                                ]
                            ],
                        ]
                    ],
                    [
                        "title" => "Testing Center",
                        "titleAr" => "Testing Center",
                        "path" => "#",
                        "weight" => 3,
                        "children" => [
                            [
                                "title" => "ATC Program",
                                "titleAr" => "ATC Program",
                                "path" => "/atc_program",
                                "type" => "page",
                                "weight" => 1,
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "ATC FAQs",
                                "titleAr" => "ATC FAQs",
                                "path" => "/atc_faqs",
                                "weight" => 2,
                                "type" => "page",
                                "children" => [
                                ]
                            ],
                            [
                                "title" => "ATC Directory",
                                "titleAr" => "ATC Directory",
                                "path" => "/organizations/atcs",
                                "weight" => 3,
                                "children" => [
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "about" => [
                "title" => "About",
                "titleAr" => "About",
                "path" => "#",
                "weight" => 2,
                "children" => [
                    [
                        "title" => "Corporate Profile",
                        "titleAr" => "Corporate Profile",
                        "path" => "/corportate_profile",
                        "type" => "page",
                    ],
                    [
                        "title" => "How can Certigate Pro help you ?",
                        "titleAr" => "How can Certigate Pro help you ?",
                        "path" => "/how_can_certigate_pro_help_you",
                        "type" => "page",
                    ]
                ]
            ],
            "Contact Us" => [
                "title" => "Contact Us",
                "titleAr" => "Contact Us",
                "weight" => 4,
                "path" => "/contact-us",
                "children" => []
            ]
        ];
        foreach ($primaryMenuItems as $item) {
            $this->insertMenuItem($item, $primaryMenuId);
        }


        // Admin Menu
        $menu = [
            "title" => "Admin Menu",
            "titleAr" => "Admin Menu",
            "titleAr" => "Admin Menu",
            "status" => true
        ];

        $this->insert('menu', $menu);
    }

    public function insertMenuItem($item, $primaryMenuId, $parentId = null)
    {
        $menuItem = [];
        if (isset($item['type']) && $item['type'] == "page") {
            $pageId = $this->insertPageForMenuItem($item);
            $menuItem['page_id'] = $pageId;
            $menuItem['type'] = MenuItem::TYPE_PAGE;
        }
        else {
            $menuItem['directUrl'] = $item['path'];
            $menuItem['type'] = MenuItem::TYPE_DIRECT_URL;
        }

        $menuItem['parent_id'] = $parentId;
        $menuItem['menu_id'] = $primaryMenuId;
        $menuItem['title'] = $item['title'];
        $menuItem['titleAr'] = $item['titleAr'] . ' ar';
        $menuItem['weight'] = (isset($item['weight'])) ? $item['weight'] : 1;
        $menuItem['status'] = true;

        $this->insert('menuItem', $menuItem);

        $menuItemParentId = $this->getAdapter()->getConnection()->lastInsertId();
        if (isset($item['children']) && count($item['children']) > 0) {
            foreach ($item['children'] as $childItem) {
                $this->insertMenuItem($childItem, $primaryMenuId, $menuItemParentId);
            }
        }
    }

    public function insertPageForMenuItem($item)
    {
        $faker = Faker\Factory::create();

        if(property_exists($this->staticPagesContent, $item['path'])){
            $body = $this->staticPagesContent->$item['path']->body;
            $bodyAr = $this->staticPagesContent->$item['path']->bodyAr;
        }else{
            $body = $bodyAr = $faker->text;
        }
        $this->insert('page', [
            'title' => $item['title'],
            'titleAr' => $item['titleAr'] . ' ar',
            'path' => $item['path'],
            'status' => TRUE,
            'body' => base64_encode(bzcompress($body)),
            'bodyAr' => base64_encode(bzcompress($bodyAr)),
            'type' => PageTypes::PAGE_TYPE
        ]);

        $pageId = $this->getAdapter()->getConnection()->lastInsertId();
        return $pageId;
    }

}
