<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Mustache\View\Renderer' =>  'CustomMustache\Service\RendererFactory',
        ),
    ),
);