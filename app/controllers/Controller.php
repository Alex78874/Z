<?php

class Controller
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    protected function view(string $view, array $data = [], bool $layout = true): void
    {
        // Extraire les variables pour les utiliser dans la vue
        extract($data);
    
        $viewPath = __DIR__ . '/../views/';
        $layoutPath = $viewPath . 'layouts/';
    
        if ($layout) {
            require_once $layoutPath . 'header.php';
        }
        
        $viewFile = $viewPath . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("La vue '$view' n'existe pas.");
        }
        
        if ($layout) {
            require_once $layoutPath . 'footer.php';
        }
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }

    protected function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
    }

    protected function send404(string $message = 'Page non trouvée.'): void
    {
        header("HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }
}