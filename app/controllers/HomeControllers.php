<?php

class HomeController
{
    public function index()
    {
        // Charger le modèle si nécessaire
        // $model = new SomeModel();

        // Passer des données à la vue
        $data = [
            'title' => 'Bienvenue sur mon Twitter like',
            'tweets' => [], // Récupérez les tweets depuis le modèle
        ];

        // Inclure la vue
        $this->render('home/index', $data);
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
}
