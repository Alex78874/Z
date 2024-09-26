<?php

class HomeController
{
    private $pdo;
    protected $userModel;

    public function __construct() {
        $this->pdo = getPDO();
    }

    public function index(): void
    {
        // Passer des données à la vue
        $data = [
            'title' => 'Bienvenue sur mon Twitter like',
            'tweets' => [], // Récupérez les tweets depuis le modèle
        ];

        // Inclure la vue
        $this->render(view: 'home/index', data: $data);
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
}
