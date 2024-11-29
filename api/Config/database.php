<?php

return [
    'mysql' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
        'name' => $_ENV['DB_NAME'],
        'port' => $_ENV['DB_PORT'],
    ]
];