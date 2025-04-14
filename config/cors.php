<?php

return [
    'paths' => ['*'],
    'allowed_origins' => ['*'],  // In production, specify your frontend domain
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
