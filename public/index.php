<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Router.php';

$router = new Router();
$uri = $_SERVER['REQUEST_URI'];

// Dispatcher la requête
$router->dispatch($uri);
