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
        view(view: 'home/index', data: $data);
    }
}
