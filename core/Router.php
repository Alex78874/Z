<?php
// core/Router.php

class Router
{
    private $routes;

    public function __construct()
    {
        // Charger les routes depuis le fichier de configuration
        $this->routes = require __DIR__ . '/../config/routes.php';
    }

    public function dispatch($uri)
    {
        $uri = $this->sanitizeUri($uri);

        foreach ($this->routes as $route) {
            $pattern = '#^' . $route['path'] . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                // Supprimer le premier élément (la correspondance complète)
                array_shift($matches);

                // Récupérer les paramètres
                $params = [];
                if (isset($route['params'])) {
                    foreach ($route['params'] as $index => $paramName) {
                        $params[$paramName] = $matches[$index];
                    }
                }

                // Appeler le contrôleur et l'action
                return $this->callAction($route['controller'], $route['action'], $params);
            }
        }

        // Si aucune route ne correspond, afficher une page 404
        $this->send404();
    }

    private function sanitizeUri($uri)
    {
        // Supprimer les query strings
        $uri = parse_url($uri, PHP_URL_PATH);
    
        // Obtenir le chemin de base
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        
        // Vérifier si le chemin de base est présent dans l'URI
        if (strpos($uri, $basePath) === 0) {
            // Supprimer le chemin de base de l'URI
            $uri = substr($uri, strlen($basePath));
        }
    
        // Supprimer le slash final s'il existe
        $uri = rtrim($uri, '/');
    
        // Si l'URI est vide, c'est la racine
        if ($uri === '') {
            $uri = '/';
        }
    
        return $uri;
    }
    

    private function callAction($controllerName, $actionName, $params = [])
    {
        // Ajouter le namespace si vous en utilisez
        $controllerClass = $controllerName;

        // Inclure le fichier du contrôleur s'il n'est pas autoloadé
        // require_once __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

        // Vérifier si la classe existe
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            // Vérifier si la méthode existe
            if (method_exists($controller, $actionName)) {
                // Appeler l'action avec les paramètres
                call_user_func_array([$controller, $actionName], $params);
            } else {
                // Méthode non trouvée
                $this->send404("Action '$actionName' non trouvée dans le contrôleur '$controllerClass'.");
            }
        } else {
            // Contrôleur non trouvé
            $this->send404("Contrôleur '$controllerClass' non trouvé.");
        }
    }

    private function send404($message = 'Page non trouvée')
    {
        header("HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }
}
