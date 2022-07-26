<?php

return [
    'environment' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => (bool)($_ENV['APP_DEBUG'] ?? false),
    'graphqlUri' => $_ENV['APP_GRAPHQL_ROUTE'] ?? '/graphql',
    'curlHost' => $_ENV['CAPTIVEFIRE_ACCESS'] ?? 'http://172.22.0.1:4000/'
];
