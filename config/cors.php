<?php

return [
    'supportsCredentials' => true,
    'allowedOrigins' => [
       env('CORS_URL')
    ],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];
