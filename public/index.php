<?php

// Démarrer la session
session_start();

// Charger l'autoloader de Composer si utilisé
require_once __DIR__ . '/../vendor/autoload.php';

// Charger les configurations
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/routes.php';

// Analyser l'URL demandée
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Trouver la route correspondante
if (array_key_exists($uri, $routes)) {
    $controllerName = $routes[$uri]['controller'];
    $actionName = $routes[$uri]['action'];
} else {
    // Rediriger vers une page 404 ou la page d'accueil
    $controllerName = 'HomeController';
    $actionName = 'index';
}

// Inclure le contrôleur et exécuter l'action
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        // Gérer l'erreur si l'action n'existe pas
        echo "L'action demandée n'existe pas.";
    }
} else {
    // Gérer l'erreur si le contrôleur n'existe pas
    echo "Le contrôleur demandé n'existe pas.";
}
