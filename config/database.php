<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'sistemavscode');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_SOCKET', '/tmp/mysql.sock');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    if (defined('DB_SOCKET') && DB_SOCKET && file_exists(DB_SOCKET)) {
        $dsn = "mysql:unix_socket=" . DB_SOCKET . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    }
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
