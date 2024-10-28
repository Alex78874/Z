<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = require_once __DIR__ . '/../config/routes.php';
    }

    /**
     * @throws Exception
     */
    public function dispatch($uri): void
    {
        $uri = $this->sanitizeUri($uri);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $matchedRoute = null;
        $allowedMethods = [];

        // Parcours des routes définies
        foreach ($this->routes as $route) {
            $pattern = '#^' . $route['path'] . '$#';

            // Vérifie si l'URI correspond à la route
            if (preg_match($pattern, $uri, $matches)) {
                $allowedMethods = $route['methods'] ?? ['GET'];

                // Vérifie si la méthode HTTP est autorisée pour cette route
                if (in_array($requestMethod, $allowedMethods)) {
                    $matchedRoute = $route;
                    break; // Route trouvée avec la bonne méthode, on sort de la boucle
                }
            }
        }

        // Gestion des erreurs 404 et 405
        if (!$matchedRoute) {
            // Si aucune route ne correspond à l'URI, envoyer une erreur 404
            if (empty($allowedMethods)) {
                send404();
            } else {
                // Si l'URI correspond mais pas la méthode HTTP, envoyer une erreur 405
                send405(allowedMethods: $allowedMethods);
            }
            return;
        }

        // Supprime le premier élément des correspondances (la correspondance complète)
        array_shift(array: $matches);

        // Récupérer les paramètres
        $params = [];
        if (isset($matchedRoute['params'])) {
            foreach ($matchedRoute['params'] as $index => $paramName) {
                $params[$paramName] = $matches[$index];
            }
        }

        // Appeler directement l'action du contrôleur sans exécuter les middlewares
        $this->callAction(
            controllerName: $matchedRoute['controller'],
            actionName: $matchedRoute['action'],
            params: $params
        );
    }

    private function sanitizeUri($uri): string
    {
        // Supprimer les query strings
        $uri = parse_url(url: $uri, component: PHP_URL_PATH);

        // Obtenir le chemin de base
        $basePath = dirname(path: $_SERVER['SCRIPT_NAME']);

        // Vérifier si le chemin de base est présent dans l'URI
        if (strpos(haystack: $uri, needle: $basePath) === 0) {
            // Supprimer le chemin de base de l'URI
            $uri = substr(string: $uri, offset: strlen(string: $basePath));
        }

        // Supprimer le slash final s'il existe
        $uri = rtrim(string: $uri, characters: '/');
        // Si l'URI est vide, c'est la racine
        if ($uri === '') {
            $uri = '/';
        }

        return $uri;
    }

    private function callAction($controllerName, $actionName, $params = []): void
    {
        // Ajouter le namespace si vous en utilisez
        $controllerClass = $controllerName;

        // Vérifier si la classe existe
        if (class_exists(class: $controllerClass)) {
            $controller = new $controllerClass();

            // Vérifier si la méthode existe
            if (method_exists(object_or_class: $controller, method: $actionName)) {
                call_user_func_array(callback: [$controller, $actionName], args: $params);
            } else {
                send404(message: "Action '$actionName' non trouvée dans le contrôleur '$controllerClass'.");
            }
        } else {
            send404(message: "Contrôleur '$controllerClass' non trouvé.");
        }
    }
}
