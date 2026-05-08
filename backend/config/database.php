<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $database = getenv('DB_DATABASE') ?: 'service_booking_lead_management';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $socket = getenv('DB_SOCKET') ?: '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $connectionAttempts = [];
    $lastError = null;

    if (is_readable($socket)) {
        $connectionAttempts[] = "mysql:unix_socket={$socket};dbname={$database};charset=utf8mb4";
    }

    $connectionAttempts[] = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    foreach ($connectionAttempts as $dsn) {
        try {
            $pdo = new PDO($dsn, $username, $password, $options);
            return $pdo;
        } catch (PDOException $e) {
            $lastError = $e;
        }
    }

    throw $lastError ?? new RuntimeException('Unable to connect to the existing database.');
}
