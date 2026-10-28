<?php
// Environment variables take precedence over optional local configuration.
$localFile = __DIR__ . '/database.local.php';
$local = is_file($localFile) ? require $localFile : [];
return [
    'host' => getenv('DB_HOST') !== false ? getenv('DB_HOST') : ($local['host'] ?? '127.0.0.1'),
    'username' => getenv('DB_USER') !== false ? getenv('DB_USER') : ($local['username'] ?? 'root'),
    'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : ($local['password'] ?? ''),
    'database' => getenv('DB_NAME') !== false ? getenv('DB_NAME') : ($local['database'] ?? 'unihubdb'),
];
