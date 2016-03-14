<?php

namespace EStore;

return array(
    'service_manager' => array(
        'factories' => array(
            'EStore\Service\Api' => 'EStore\Service\ApiFactory',
        ),
    ),
);
