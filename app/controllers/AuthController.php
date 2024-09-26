<?php

class AuthController
{
    public function register(): void
    {
        // Si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Vous pouvez ajouter ici la logique de validation et d'enregistrement de l'utilisateur
            // Par exemple, utiliser un modèle User pour enregistrer l'utilisateur en base de données

            // Après l'inscription, vous pouvez rediriger l'utilisateur vers la page de connexion ou le tableau de bord
            header(header: 'Location: /login');
            exit();
        }

        // Afficher le formulaire d'inscription
        $this->render(view: 'user/register');
    }

    public function index_register(): void
    {
        // Afficher le formulaire d'inscription
        $this->render(view: 'user/register');
    }

    private function render($view, $data = []): void
    {
        // Extraire les variables pour les utiliser dans la vue
        extract(array: $data);

        // Inclure le header
        include __DIR__ . '/../views/layouts/header.php';
        // Inclure la vue principale
        include __DIR__ . '/../views/' . $view . '.php';
        // Inclure le footer
        include __DIR__ . '/../views/layouts/footer.php';
    }

    private function send404($message = 'Page non trouvée'): never
    {
        header(header: "HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }
}
