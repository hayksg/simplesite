<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '',
                    'user'     => '',
                    'password' => '',
                    'dbname'   => '',
                ]
            ],
        ],
    ],
];
