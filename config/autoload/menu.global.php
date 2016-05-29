<?php

/**
 * Static menu items to be merged with the items created from the admin module 
 *
 */
return array(
    'static_menus' => array(
        'admin_menu' => array(
            "CMS" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 1,
                'title_underscored' => "cms",
                'children' => array(
                    array(
                        "Pages" => array(
                            'depth' => 1,
                            'path' => "/cms/page",
                            'weight' => 1,
                            'title_underscored' => "cms_page",
                            'children' => array(
                            )
                        ),
                        "Menus" => array(
                            'depth' => 1,
                            'path' => "/cms/menu",
                            'weight' => 1,
                            'title_underscored' => "cms_menu",
                            'children' => array(
                            )
                        ),
                        "Menu Items" => array(
                            'depth' => 1,
                            'path' => "/cms/menuitem",
                            'weight' => 1,
                            'title_underscored' => "cms_menuitem",
                            'children' => array(
                            )
                        ),
                    )
                )
            ),
            "Courses" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 3,
                'title_underscored' => "courses",
                'children' => array(
                    array(
                        "Courses" => array(
                            'depth' => 1,
                            'path' => "/courses",
                            'weight' => 0,
                            'title_underscored' => "courses",
                            'children' => array(
                            )
                        ),
                        "Course Events" => array(
                            'depth' => 1,
                            'path' => "/course-events",
                            'weight' => 1,
                            'title_underscored' => "course_events",
                            'children' => array(
                            )
                        )
                    )
                )
            ),
            "Users" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 2,
                'title_underscored' => "users",
                'children' => array(
                    array(
                        "Users" => array(
                            'depth' => 1,
                            'path' => "/users",
                            'weight' => 0,
                            'title_underscored' => "users",
                            'children' => array(
                            )
                        ),
                        "Roles" => array(
                            'depth' => 1,
                            'path' => "/roles",
                            'weight' => 1,
                            'title_underscored' => "roles",
                            'children' => array(
                            )
                        ),
                    )
                )
            ),
            "Organizations" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 2,
                'title_underscored' => "organizations",
                'children' => array(
                    array(
                        "New Organization" => array(
                            'depth' => 1,
                            'path' => "/organizations/type",
                            'weight' => 1,
                            'title_underscored' => "new_organization",
                            'children' => array()
                        ),
                        "Organizations" => array(
                            'depth' => 1,
                            'path' => "/organizations",
                            'weight' => 1,
                            'title_underscored' => "organizations",
                            'children' => array()
                        ),
                        "ATPs" => array(
                            'depth' => 1,
                            'path' => "/organizations/atps",
                            'weight' => 1,
                            'title_underscored' => "atps",
                            'children' => array()
                        ),
                        "ATCs" => array(
                            'depth' => 1,
                            'path' => "/organizations/atcs",
                            'weight' => 1,
                            'title_underscored' => "atcs",
                            'children' => array()
                        ),
                    )
                )
            ),
            "Evaluation Template" => array(
                'depth' => 0,
                'path' => "/courses/ev-templates",
                'weight' => 4,
                'title_underscored' => "Evaluation_Template",
                'children' => array(
                )
            ),
            "System" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 5,
                'title_underscored' => "system",
                'children' => array(
                    array(
                        "Settings" => array(
                            'depth' => 1,
                            'path' => "/system/settings",
                            'weight' => 1,
                            'title_underscored' => "settings",
                            'children' => array(
                            )
                        ),
                    )
                )
            ),
        )
    ),
);
