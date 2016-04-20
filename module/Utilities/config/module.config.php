<?php

return array(
    'service_manager' => array(
        'aliases' => array(
            'wrapperQuery' => 'Utilities\Service\Query',
            'paginatorWrapperQuery' => 'Utilities\Service\Paginator',
            'cacheUtilities' => 'Utilities\Service\Cache',
            'objectUtilities' => 'Utilities\Service\Object',
            'fileUtilities' => 'Utilities\Service\File',
            'loggerUtilities' => 'Utilities\Service\Logger',
            'validationUtilities' => 'Utilities\Service\Validator',
        ),
        'factories' => array(
            'Utilities\Service\Query' => 'Utilities\Service\Query\QueryFactory',
            'Utilities\Service\Paginator' => 'Utilities\Service\Paginator\PaginatorFactory',
            'Utilities\Service\Cache' => 'Utilities\Service\Cache\CacheFactory',
            'Utilities\Service\Object' => 'Utilities\Service\ObjectFactory',
            'Utilities\Service\Logger' => 'Utilities\Service\Logger\LoggerFactory',
            'Utilities\Service\Fixture\FixtureLoader' => 'Utilities\Service\Fixture\FixtureLoaderFactory',
            'Utilities\Service\View\FormView' => 'Utilities\Service\View\FormViewFactory',
        ),
        'invokables' => array(
            'Utilities\Service\File' => 'Utilities\Service\File',
            'Utilities\Service\Distance' => 'Utilities\Service\Distance',
        ),
    ),
    'validators' => array(
        'invokables' => array(
            'TenDaysAfterValidator' => 'Utilities\Service\Validator\TenDaysAfterValidator',
        ),
    ),
);
