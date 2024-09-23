<?php
// app/controllers/UserController.php

class UserController
{
    public function profile($id)
    {
        // Charger le modèle utilisateur
        $userModel = new User();

        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($id);

        if ($user) {
            // Passer les données à la vue
            $data = [
                'user' => $user,
            ];

            $this->render('user/profile', $data);
        } else {
            // Gérer l'utilisateur non trouvé
            $this->send404('Utilisateur non trouvé.');
        }
    }

    private function render($view, $data = [])
    {
        // Extraire les variables pour les utiliser dans la vue
        extract($data);

        // Inclure le header
        include __DIR__ . '/../views/layouts/header.php';
        // Inclure la vue principale
        include __DIR__ . '/../views/' . $view . '.php';
        // Inclure le footer
        include __DIR__ . '/../views/layouts/footer.php';
    }

    private function send404($message = 'Page non troaaauvée')
    {
        header("HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }
}
