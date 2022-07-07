<?php

return [
    'environment' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => (bool)($_ENV['APP_DEBUG'] ?? false),
    'graphql' => [
        'uri' => $_ENV['APP_GRAPHQL_ROUTE'] ?? 'graphql',
    ],
];
