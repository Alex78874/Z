<?php
session_start();

require_once __DIR__ . '/../functions/utils.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

$uri = $_SERVER['REQUEST_URI'];

// Récupérer le chemin de base
$basePath = getBasePath();

// Pour déboguer
var_dump($uri);
var_dump($basePath);

// Dispatcher la requête
$router->dispatch($uri);
