<?php

ini_set(option: 'display_errors', value: 1);
ini_set(option: 'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);

const DB_HOST = 'mysql-garibaldi.alwaysdata.net';
const DB_NAME = 'garibaldi_z';
const DB_USER = 'garibaldi';
const DB_PASS = 'zozolerigolo123@';

// Connexion a la base de donnée
function getPDO(): PDO {
    static $pdo;

    if ($pdo === null) {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            $pdo = new PDO(dsn: $dsn, username: DB_USER, password: DB_PASS, options: $options);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
    return $pdo;
}

// Autoloader des classes
spl_autoload_register(callback: function ($class_name): void {
    $paths = [
        __DIR__ . '/../core/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists(filename: $file)) {
            require_once $file;
            return;
        }
    }
});


