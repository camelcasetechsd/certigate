<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'loggable_metadata_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    'vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Gedmo\Loggable\Entity' => 'loggable_metadata_driver',
                ),
            ),
        ),
    ),
);
