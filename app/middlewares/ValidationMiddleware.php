<?php

class ValidationMiddleware implements MiddlewareInterface
{
    private $rules;
    private $messages;

    public function __construct($rules, $messages = [])
    {
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public function handle($request, $next)
    {
        $validator = new Validator();

        $errors = $validator->validate($request, $this->rules, $this->messages);

        if (!empty($errors)) {
            // Gérer les erreurs (par exemple, rediriger ou renvoyer une réponse d'erreur)
            // Ici, nous allons simplement afficher les erreurs
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errors]);
            exit;
        }

        $next($request);
    }
}
