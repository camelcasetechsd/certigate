<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
            'cacheUtilities' => 'Utilities\Service\Cache',
            'cacheHandlerUtilities' => 'Utilities\Service\CacheHandler',
            'objectUtilities' => 'Utilities\Service\Object',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
            'Utilities\Service\Cache' => 'Utilities\Service\Cache\CacheFactory',
            'Utilities\Service\CacheHandler' => 'Utilities\Service\Cache\CacheHandlerFactory',
        ),
        'invokables' => array(
            'Utilities\Service\Object' => 'Utilities\Service\Object',
        )
    ),
);
