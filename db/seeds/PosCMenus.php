<?php

require_once __DIR__.'/../AbstractSeed.php';

use db\AbstractSeed;
use \CMS\Entity\MenuItem;
use \CMS\Service\PageTypes;

class PosCMenus extends AbstractSeed
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
            "titleAr" => "Primary Menu",
            "status" => true
        ];

        $this->insert('menu', $menu);
        $primaryMenuId = $this->getAdapter()->getConnection()->lastInsertId();


        $primaryMenuItems = [
            "about" => [
                "title" => "About",
                "titleAr" => "About",
                "path" => "#",
                "weight" => 1,
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
                    ],
                    [
                        "title" => "Alliances & Partnerships",
                        "titleAr" => "Alliances & Partnerships",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "Become a Partner",
                                "titleAr" => "Become a Partner",
                                "path" => "/organizations/type",
                            ],
                        ]
                    ],
                    [
                        "title" => "Media Center",
                        "titleAr" => "Media Center",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "Ask the Expert",
                                "titleAr" => "Ask the Expert",
                                "path" => "/q2a",
                            ],
                        ]
                    ],
                    [
                        "title" => "Career Center",
                        "titleAr" => "Career Center",
                        "path" => "http://thinktalentpro.com",
                    ],
                    [
                        "title" => "Contact Us",
                        "titleAr" => "Contact Us",
                        "type" => "page",
                        "path" => "/ContactUs",
                    ],
                ]
            ],
            //second menu
            "Training & Certification" => [
                "title" => "Training & Certification",
                "titleAr" => "Training & Certification",
                "weight" => 2,
                "path" => "#",
                "children" => [
                    [
                        "title" => "CGP Training",
                        "titleAr" => "CGP Training",
                        "path" => "#",
                        "weight" => 1,
                        "children" => [
                            [
                                "title" => "Classroom Training",
                                "titleAr" => "Classroom Training",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "Public Training",
                                        "titleAr" => "Public Training",
                                        "path" => "#",
                                        "weight" => 1,
                                        "children" => [
                                            [
                                                // need to be specific 
                                                // not implemented yet
                                                "title" => "Course Evaluation",
                                                "titleAr" => "Course Evaluation",
                                                "path" => "/courses/evaluation/1",
                                            ]
                                        ]
                                    ],
                                    [
                                        "title" => "Course Calendar",
                                        "titleAr" => "Course Calendar",
                                        "path" => "/courses/calendar",
                                        "weight" => 2,
                                        "children" => [
                                            [
                                                "title" => "Register",
                                                "titleAr" => "Register",
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
                        "titleAr" => "Training Partner",
                        "path" => "#",
                        "children" => [
                            [
                                "title" => "ATP Program",
                                "titleAr" => "ATP Program",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "Overview",
                                        "titleAr" => "Overview",
                                        "path" => "/overview",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP Benefits",
                                        "titleAr" => "ATP Benefits",
                                        "path" => "/atp_benefits",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP Selection Criteria",
                                        "titleAr" => "ATP Selection Criteria",
                                        "path" => "/atp_criteria",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "ATP SOP",
                                        "titleAr" => "ATP SOP",
                                        "path" => "/atp_sop",
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Apply to be an ATP",
                                        "titleAr" => "Apply to be an ATP",
                                        "path" => "/organizations/new?organization=1",
                                    ],
                                    [
                                        "title" => "ATP Login",
                                        "titleAr" => "ATP Login",
                                        "path" => "/sign/out",
                                        "children" => [
                                            [
                                                "title" => "Publish Training Calendar",
                                                "titleAr" => "Publish Training Calendar",
                                                "path" => "/courses/new",
                                            ]
                                        ]
                                    ],
                                    [
                                        "title" => "ATP Directory",
                                        "titleAr" => "ATP Directory",
                                        "path" => "/organizations/atps",
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        "title" => "Certified Instructors",
                        "titleAr" => "Certified Instructors",
                        "path" => "#",
                        "weight" => 3,
                        "children" => [



                            [
                                "title" => "Certified Instructors",
                                "titleAr" => "Certified Instructors",
                                "path" => "#",
                                "weight" => 3,
                                "children" => [
                                    [
                                        // not implemented yet
                                        "title" => "welcome page",
                                        "titleAr" => "welcome page",
                                        "path" => "/welcome_page",
                                        "weight" => 1,
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Faculty Login",
                                        "titleAr" => "Faculty Login",
                                        "path" => "/sign/out",
                                        "weight" => 2,
                                        "children" => [
                                            [
                                                "title" => "CertiGate Pro Instructor Resources",
                                                "titleAr" => "CertiGate Pro Instructor Resources",
                                                "path" => "#",
                                                "weight" => 2,
                                                "children" => [
                                                    [
                                                        "title" => "General Resources",
                                                        "titleAr" => "General Resources",
                                                        "path" => "/general_resources",
                                                        "weight" => 1,
                                                        "type" => "page"
                                                    ],
                                                    [
                                                        "title" => "Training Materials",
                                                        "titleAr" => "Training Materials",
                                                        "path" => "#",
                                                        "weight" => 2,
                                                        "children" => [

                                                            [
                                                                "title" => "Select Course",
                                                                "titleAr" => "Select Course",
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
                                        "titleAr" => "Certified Instructor",
                                        "path" => "/cerified_instructor/info",
                                        "weight" => 3,
                                        "type" => "page"
                                    ],
                                    [
                                        "title" => "Apply to be an Instructor",
                                        "titleAr" => "Apply to be an Instructor",
                                        "path" => "/users/new",
                                        "weight" => 4,
                                    ],
                                    [
                                        "title" => "Authorized Instructor Program (AIP)",
                                        "titleAr" => "Authorized Instructor Program (AIP)",
                                        "path" => "#",
                                        "weight" => 5,
                                        "children" => [
                                            [
                                                "title" => "Welcome",
                                                "titleAr" => "Welcome",
                                                "path" => "/aip_welcome",
                                                "weight" => 1,
                                                "type" => "page"
                                            ],
                                            [
                                                /**
                                                 * no specific course 
                                                 */
                                                "title" => "Course Outlines",
                                                "titleAr" => "Course Outlines",
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
                        "titleAr" => "Testing Center",
                        "path" => "#",
                        "weight" => 3,
                        "children" => [
                            [
                                "title" => "ATC Program",
                                "titleAr" => "ATC Program",
                                "path" => "#",
                                "weight" => 1,
                                "children" => [
                                    [
                                        "title" => "ATC",
                                        "titleAr" => "ATC",
                                        "path" => "#",
                                        "weight" => 1,
                                        "children" => [

                                            [
                                                "title" => "Overview",
                                                "titleAr" => "Overview",
                                                "path" => "/atc_verview",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC Benefits",
                                                "titleAr" => "ATC Benefits",
                                                "path" => "/atc_benefits",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC Selection Criteria",
                                                "titleAr" => "ATC Selection Criteria",
                                                "path" => "/atc_criteria",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC SOP",
                                                "titleAr" => "ATC SOP",
                                                "path" => "/atc_sop",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => "ATC FAQs",
                                                "titleAr" => "ATC FAQs",
                                                "path" => "/atc_faqs",
                                                "type" => "page",
                                            ],
                                            [
                                                "title" => " Apply to be an ATC",
                                                "titleAr" => " Apply to be an ATC",
                                                "path" => "/organizations/new?organization=1",
                                            ],
                                            [
                                                "title" => "Apply to be an ATC Administrator",
                                                "titleAr" => "Apply to be an ATC Administrator",
                                                "path" => "/users/new",
                                            ],
                                            [
                                                "title" => "Apply to be an ATC Proctor",
                                                "titleAr" => "Apply to be an ATC Proctor",
                                                "path" => "/users/new",
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                            [
                                "title" => "ATC Login",
                                "titleAr" => "ATC Login",
                                "path" => "#",
                                "weight" => 2,
                                "children" => [
                                    [
                                        "title" => "Testing Session Request",
                                        "titleAr" => "Testing Session Request",
                                        "path" => "/courses/exam/book",
                                        "weight" => 1,
                                    ],
                                    [
                                        /**
                                         * Ask ali ???
                                         */
                                        "title" => "Renew ATC Status ",
                                        "titleAr" => "Renew ATC Status ",
                                        "path" => "/",
                                        "weight" => 3,
                                    ],
                                    [
                                        "title" => "ATC Directory",
                                        "titleAr" => "ATC Directory",
                                        "path" => "/organizations/atcs",
                                        "weight" => 4,
                                    ],
                                ]
                            ]
                        ]
                    ],
                    [
                        "title" => "Reports",
                        "titleAr" => "Reports",
                        "path" => "#",
                        "weight" => 6,
                        "children" => [
                            [
                                // not implemented yet
                                "title" => "Reports",
                                "titleAr" => "Reports",
                                "path" => "/results",
                                "type" => "page",
                                "children" => [
                                    [
                                        // not implemented yet
                                        "title" => "Students passed specific exam",
                                        "titleAr" => "Students passed specific exam",
                                        "path" => "/passed",
                                        "children" => [
                                            [
                                                // not implemented yet
                                                "title" => "Request Certificate",
                                                "titleAr" => "Request Certificate",
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
        $menuItem['titleAr'] = $item['titleAr'];
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
            'titleAr' => $item['titleAr'],
            'path' => $item['path'],
            'status' => TRUE,
            'body' => base64_encode(bzcompress($faker->text)),
            'bodyAr' => base64_encode(bzcompress($faker->text)),
            'type' => PageTypes::PAGE_TYPE
        ]);

        $pageId = $this->getAdapter()->getConnection()->lastInsertId();
        return $pageId;
    }

}
