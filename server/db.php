<?php
// db.php — opens the database connection. You shouldn't need to edit this file.
//
// Other scripts use it like this:
//     require __DIR__ . '/db.php';
//     $rows = db()->query("SELECT ...")->fetchAll();

if (!file_exists(__DIR__ . '/config.php')) {
    http_response_code(500);
    exit('config.php not found. Copy config.example.php to config.php, fill it in, and upload it.');
}
require __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;   // connect once, then reuse the same connection

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=3306;dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // errors throw exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // rows come back as ['column' => value]
            PDO::ATTR_EMULATE_PREPARES   => false,                   // real prepared statements
        ]);
    }
    return $pdo;
}
