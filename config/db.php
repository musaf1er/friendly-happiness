<?php
declare(strict_types=1);

function db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;
    if ($attempted) return $pdo;
    $attempted = true;
    $driver = getenv('DB_DRIVER') ?: 'mysql';
    try {
        if ($driver === 'sqlite') {
            $path = getenv('DB_PATH') ?: __DIR__ . '/../storage/mischief.sqlite';
            if (!is_dir(dirname($path))) mkdir(dirname($path), 0750, true);
            $pdo = new PDO('sqlite:' . $path);
        } else {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $name = getenv('DB_NAME') ?: 'mischief_outlaws';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (Throwable $error) {
        error_log('Database connection failed: ' . $error->getMessage());
        $pdo = null;
    }
    return $pdo;
}
