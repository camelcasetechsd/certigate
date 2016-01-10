<?php

/**
 * Global Configuration Override
 *
 */
return array(
    'static_menus' => array(
        'primary_menu' => array(
            "Overview" => array(
                'depth' => 0,
                'path' => "/overview/",
                'weight' => 1,
                'title_underscored' => "overview",
                'children' => array()
            ),
            "Reports" => array(
                'depth' => 0,
                'path' => "/reports/",
                'weight' => 2,
                'title_underscored' => "reports",
                'children' => array()
            ),
            "Analytics" => array(
                'depth' => 0,
                'path' => "/analytics/",
                'weight' => 3,
                'title_underscored' => "analytics",
                'children' => array()
            ),
            "Export" => array(
                'depth' => 0,
                'path' => "/export/",
                'weight' => 4,
                'title_underscored' => "export",
                'children' => array(
                    array(
                        "Excel" => array(
                            'depth' => 1,
                            'path' => "/export/excel/",
                            'weight' => 1,
                            'title_underscored' => "excel",
                            'children' => array()
                        ),
                        "Pdf" => array(
                            'depth' => 1,
                            'path' => "/export/pdf/",
                            'weight' => 2,
                            'title_underscored' => "pdf",
                            'children' => array()
                        ),)
                )
            ),
        )
    ),
);
