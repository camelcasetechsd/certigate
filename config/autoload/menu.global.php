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
            "Users" => array(
                'depth' => 0,
                'path' => "/users",
                'weight' => 2,
                'title_underscored' => "users",
                'children' => array(
                    array(
                        "Roles" => array(
                            'depth' => 0,
                            'path' => "/roles",
                            'weight' => 1,
                            'title_underscored' => "roles",
                            'children' => array(
                            )
                        ),
                    )
                )
            ),
        )
    ),
);
