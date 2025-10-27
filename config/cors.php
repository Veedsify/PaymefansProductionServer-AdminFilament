<?php
return [
      'paths' => ['/*', 'sanctum/csrf-cookie'],
      'allowed_methods'   => ['*'],
      'allowed_origins'   => [
            'https://api.paymefans.com',
            'https://localhost:5173',
            'https://paymefans.com',
      ],
      'allowed_headers'   => ['*'],
      'exposed_headers'   => [],
      'max_age'           => 3600,
      'supports_credentials' => false,
];
