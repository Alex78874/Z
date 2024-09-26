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

    public function handle($request, $next): void
    {
        $validator = new Validator();

        $errors = $validator->validate(data: $request, rules: $this->rules, messages: $this->messages);

        if (!empty($errors)) {
            // Gérer les erreurs (par exemple, rediriger ou renvoyer une réponse d'erreur)
            // Ici, nous allons simplement afficher les erreurs
            header(header: 'Content-Type: application/json');
            echo json_encode(value: ['errors' => $errors]);
            exit;
        }

        $next($request);
    }
}
