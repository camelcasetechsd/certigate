<?php
return array(
    'hydrators' => array(
        'factories' => array(
            'DoctrineModule\Stdlib\Hydrator\DoctrineObject' => 'CustomDoctrine\Service\DoctrineObjectHydratorFactory'
        )
    ),
);