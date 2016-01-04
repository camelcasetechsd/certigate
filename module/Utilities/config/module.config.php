<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
            'objectUtilities' => 'Utilities\Service\Object',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
        ),
        'invokables' => array(
            'Utilities\Service\Object' => 'Utilities\Service\Object',
        )
    ),
);
