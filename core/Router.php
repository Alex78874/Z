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
        $uri = $this->sanitizeUri(uri: $uri);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $pattern = '#^' . $route['path'] . '$#';

            if (preg_match(pattern: $pattern, subject: $uri, matches: $matches)) {
                $allowedMethods = $route['methods'] ?? ['GET'];
                if (!in_array(needle: $requestMethod, haystack: $allowedMethods)) {
                    $this->send405(allowedMethods: $allowedMethods);
                    return;
                }

                // Supprimer le premier élément (la correspondance complète)
                array_shift(array: $matches);

                // Récupérer les paramètres
                $params = [];
                if (isset($route['params'])) {
                    foreach ($route['params'] as $index => $paramName) {
                        $params[$paramName] = $matches[$index];
                    }
                }

                // Gérer les middlewares
                $middlewares = $route['middlewares'] ?? [];
                $this->callActionWithMiddlewares(middlewares: $middlewares, controllerName: $route['controller'], actionName: $route['action'], params: $params);
                return;
            }
        }

        // Si aucune route ne correspond, afficher une page 404
        $this->send404();
    }

    private function callActionWithMiddlewares($middlewares, $controllerName, $actionName, $params): void
    {
        $middlewareStack = [];

        foreach ($middlewares as $middlewareClass) {
            if (class_exists(class: $middlewareClass)) {
                $middlewareStack[] = new $middlewareClass();
            } else {
                throw new Exception(message: "Middleware '$middlewareClass' non trouvé.");
            }
        }

        $request = $_REQUEST; // Vous pouvez créer un objet Request pour plus de sophistication.

        $next = function ($request) use ($controllerName, $actionName, $params): void {
            $this->callAction(controllerName: $controllerName, actionName: $actionName, params: $params);
        };

        // Exécuter les middlewares
        $this->executeMiddlewareStack(stack: $middlewareStack, request: $request, next: $next);
    }

    private function executeMiddlewareStack($stack, $request, $next): void
    {
        if (empty($stack)) {
            $next($request);
            return;
        }

        $middleware = array_shift(array: $stack);

        $middleware->handle($request, function ($request) use ($stack, $next): void {
            $this->executeMiddlewareStack(stack: $stack, request: $request, next: $next);
        });
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

        // Inclure le fichier du contrôleur s'il n'est pas autoloadé
        // require_once __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

        // Vérifier si la classe existe
        if (class_exists(class: $controllerClass)) {
            $controller = new $controllerClass();

            // Vérifier si la méthode existe
            if (method_exists(object_or_class: $controller, method: $actionName)) {
                // Appeler l'action avec les paramètres
                call_user_func_array(callback: [$controller, $actionName], args: $params);
            } else {
                // Méthode non trouvée
                $this->send404(message: "Action '$actionName' non trouvée dans le contrôleur '$controllerClass'.");
            }
        } else {
            // Contrôleur non trouvé
            $this->send404(message: "Contrôleur '$controllerClass' non trouvé.");
        }
    }

    private function send404($message = 'Page non trouvée'): never
    {
        header(header: "HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }

    private function send405($allowedMethods): never
    {
        header(header: 'HTTP/1.1 405 Method Not Allowed');
        header(header: 'Allow: ' . implode(separator: ', ', array: $allowedMethods));
        echo 'Méthode non autorisée. Seules les méthodes suivantes sont autorisées : ' . implode(separator: ', ', array: $allowedMethods);
        exit();
    }
}
