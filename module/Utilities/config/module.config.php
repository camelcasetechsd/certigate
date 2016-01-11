<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
            'cacheUtilities' => 'Utilities\Service\Cache',
            'objectUtilities' => 'Utilities\Service\Object',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
            'Utilities\Service\Cache' => 'Utilities\Service\Cache\CacheFactory',
        ),
        'invokables' => array(
            'Utilities\Service\Object' => 'Utilities\Service\Object',
        )
    ),
);
