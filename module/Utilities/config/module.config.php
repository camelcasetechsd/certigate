<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
        )
        ),
);
