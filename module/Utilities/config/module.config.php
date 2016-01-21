<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
            'cacheUtilities' => 'Utilities\Service\Cache',
            'objectUtilities' => 'Utilities\Service\Object',
            'loggerUtilities' => 'Utilities\Service\Logger',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
            'Utilities\Service\Cache' => 'Utilities\Service\Cache\CacheFactory',
            'Utilities\Service\Object' => 'Utilities\Service\ObjectFactory',
            'Utilities\Service\Logger' => 'Utilities\Service\Logger\LoggerFactory',
        )
    ),
);
