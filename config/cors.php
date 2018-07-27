<?php

return [
    'supportsCredentials' => true,
    'allowedOrigins' => [
        env('APP_URL')
    ],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
