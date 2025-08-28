<?php
return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'dbname' => $_ENV['DB_NAME'] ?? 'forgeflow',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'ForgeFlow',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        'upload_path' => __DIR__ . '/../uploads/',
        'max_file_size' => 50 * 1024 * 1024, // 50MB
    ],
    'session' => [
        'name' => 'FORGEFLOW_SESSION',
        'lifetime' => 3600, // 1 hour
    ]
];