<?php

require_once 'init_autoloader.php';
require_once 'module/CMS/src/CMS/Entity/Menu.php';
require_once 'module/CMS/src/CMS/Entity/MenuItem.php';
require_once 'module/CMS/src/CMS/Service/PageTypes.php';

use Phinx\Seed\AbstractSeed;
use \CMS\Entity\Menu;
use \CMS\Entity\MenuItem;
use \CMS\Service\PageTypes;

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

        $this->insert('menu', $menu);
        $primaryMenuId = $this->getAdapter()->getConnection()->lastInsertId();


        $primaryMenuItems = [
            "about" => [
                "title" => "About",
                "path" => "#",
                "weight" => 1,
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
                        "title" => "Alliances & Partnerships",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "Become a Partner",
                                "path" => "/organizations/type",
                            ],
                        ]
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
                        "path" => "http://thinktalentpro.com",
                    ],
                    [
                        "title" => "Contact Us",
                        "type" => "page",
                        "path" => "/ContactUs",
                    ],
                ]
            ],
            //second menu
            "Training & Certification" => [
                "title" => "Training & Certification",
                "weight" => 2,
                "path" => "#",
                "children" => [
                    [
                        "title" => "CGP Training",
                        "path" => "#",
                        "weight" => 1,
                        "children" => [
                            [
                                "title" => "Classroom Training",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "Public Training",
                                        "path" => "#",
                                        "weight" => 1,
                                        "children" => [
                                            [
                                                // need to be specific 
                                                // not implemented yet
                                                "title" => "Course Evaluation",
                                                "path" => "/courses/evaluation/1",
                                            ]
                                        ]
                                    ],
                                    [
                                        "title" => "Course Calendar",
                                        "path" => "/courses/calendar",
                                        "weight" => 2,
                                        "children" => [
                                            [
                                                "title" => "Register",
                                                "path" => "/courses/new",
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        "title" => "Training Partner",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "ATP Program",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "Overview",
                                        "path" => "/overview",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP Benefits",
                                        "path" => "/atp_benefits",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP Selection Criteria",
                                        "path" => "/atp_criteria",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP SOP",
                                        "path" => "/atp_sop",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Apply to be an ATP",
                                        "path" => "/organizations/new?organization=1",
                                    ],
                                    [
                                        "title" => "ATP Login",
                                        "path" => "/sign/out",
                                        "children" => [
                                            [
                                                "title" => "Publish Training Calendar",
                                                "path" => "/courses/new",
                                            ]
                                        ]
                                    ],
                                    [
                                        "title" => "ATP Directory",
                                        "path" => "/organizations/atps",
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        "title" => "Certified Instructors",
                        "path" => "#",
                        "weight" => 3,
                        "children" => [



                            [
                                "title" => "Certified Instructors",
                                "path" => "#",
                                "weight" => 3,
                                "children" => [
                                    [
                                        // not implemented yet
                                        "title" => "welcome page",
                                        "path" => "/welcome_page",
                                        "weight" => 1,
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Faculty Login",
                                        "path" => "/sign/out",
                                        "weight" => 2,
                                        "children" => [
                                            [
                                                "title" => "CertiGate Pro Instructor Resources",
                                                "path" => "#",
                                                "weight" => 2,
                                                "children" => [
                                                    [
                                                        "title" => "General Resources",
                                                        "path" => "/general_resources",
                                                        "weight" => 1,
                                                        "type" => "page"
                                                    ],
                                                    [
                                                        "title" => "Training Materials",
                                                        "path" => "#",
                                                        "weight" => 2,
                                                        "children" => [

                                                            [
                                                                "title" => "Select Course",
                                                                "path" => "/courses/instructor-calendar",
                                                                "weight" => 1,
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                        ]
                                    ],
                                    [
                                        //not implemented yet
                                        "title" => "Certified Instructor",
                                        "path" => "/cerified_instructor/info",
                                        "weight" => 3,
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Apply to be an Instructor",
                                        "path" => "/users/new",
                                        "weight" => 4,
                                    ],
                                    [
                                        "title" => "Authorized Instructor Program (AIP)",
                                        "path" => "#",
                                        "weight" => 5,
                                        "children" => [
                                            [
                                                "title" => "Welcome",
                                                "path" => "/aip_welcome",
                                                "weight" => 1,
                                                "type" => "page"
                                            ],
                                            [
                                                /**
                                                 * no specific course 
                                                 */
                                                "title" => "Course Outlines",
                                                "path" => '/courses/instructor-training',
                                                "weight" => 2,
                                            ],
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ],
                    [
                        "title" => "Testing Center",
                        "path" => "#",
                        "weight" => 3,
                        "children" => [
                            [
                                "title" => "ATC Program",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "ATC",
                                        "path" => "#",
                                        "weight" => 1,
                                        "children" => [

                                            [
                                                "title" => "Overview",
                                                "path" => "/atc_verview",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC Benefits",
                                                "path" => "/atc_benefits",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC Selection Criteria",
                                                "path" => "/atc_criteria",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC SOP",
                                                "path" => "/atc_sop",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC FAQs",
                                                "path" => "/atc_faqs",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => " Apply to be an ATC",
                                                "path" => "/organizations/new?organization=1",
                                            ],
                                            [
                                                "title" => "Apply to be an ATC Administrator",
                                                "path" => "/users/new",
                                            ],
                                            [
                                                "title" => "Apply to be an ATC Proctor",
                                                "path" => "/users/new",
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                            [
                                "title" => "ATC Login",
                                "path" => "#",
                                "weight" => 2,
                                "children" => [
                                    [
                                        "title" => "Testing Session Request",
                                        "path" => "/courses/exam/book",
                                        "weight" => 1,
                                    ],
                                    [
                                        /**
                                         * Ask ali ???
                                         */
                                        "title" => "Renew ATC Status ",
                                        "path" => "/",
                                        "weight" => 3,
                                    ],
                                    [
                                        "title" => "ATC Directory",
                                        "path" => "/organizations/atcs",
                                        "weight" => 4,
                                    ],
                                ]
                            ]
                        ]
                    ],
                    [
                        "title" => "Reports",
                        "path" => "#",
                        "weight" => 6,
                        "children" => [
                            [
                                // not implemented yet
                                "title" => "Reports",
                                "path" => "/results",
                                "type" => "page",
                                "children" => [
                                    [
                                        // not implemented yet
                                        "title" => "Students passed specific exam",
                                        "path" => "/passed",
                                        "children" => [
                                            [
                                                // not implemented yet
                                                "title" => "Request Certificate",
                                                "path" => "/certificate/request",
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ],
                ]
            ]
        ];
        foreach ($primaryMenuItems as $item) {
            $this->insertMenuItem($item, $primaryMenuId);
        }


        // Admin Menu
        $menu = [
            "title" => "Admin Menu",
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

        $this->insert('page', [
            'title' => $item['title'],
            'path' => $item['path'],
            'status' => TRUE,
            'body' => base64_encode(bzcompress($faker->text)),
            'type' => PageTypes::PAGE_TYPE
        ]);

        $pageId = $this->getAdapter()->getConnection()->lastInsertId();
        return $pageId;
    }

}
