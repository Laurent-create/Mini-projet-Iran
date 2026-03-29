<?php

declare(strict_types=1);

$driver = getenv('DB_CONNECTION') ?: 'pgsql';
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_DATABASE') ?: 'testbackoffice';
$username = getenv('DB_USERNAME') ?: 'postgres';
$password = getenv('DB_PASSWORD') ?: 'postgres';

if ($driver !== 'pgsql') {
    throw new RuntimeException('Only pgsql is supported in backOffice2.');
}

if (!class_exists('PDO')) {
    throw new RuntimeException('PDO is not available in this PHP runtime. Enable the PDO extension in php.ini.');
}

if (!extension_loaded('pdo_pgsql')) {
    $loaded = implode(', ', PDO::getAvailableDrivers());
    throw new RuntimeException(
        "Missing PDO PostgreSQL driver (pdo_pgsql).\n" .
        "Fix (XAMPP): enable extensions in php.ini: extension=pdo_pgsql and extension=pgsql, then restart Apache.\n" .
        "Currently available PDO drivers: [{$loaded}]"
    );
}

$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

return $pdo;
