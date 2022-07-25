<?php

return [
    'environment' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => (bool)($_ENV['APP_DEBUG'] ?? false),
    'graphqlUri' => $_ENV['APP_GRAPHQL_ROUTE'] ?? '/graphql',
    'curlHost' => $_ENV['CAPTIVEFIRE_ACCESS'] ?? 'locahost:4000/'
];
