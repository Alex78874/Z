<?php

class HomeController
{
    protected $userModel;
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $posts = [];
        $posts = $this->postModel->getAll();

        // Préparer les données des tweets avec les informations d'utilisateur
        foreach ($posts as $post) {
            $user = $this->userModel->getById($post['user_id']);
            $posts[] = [
                'id' => $post['id'],
                'username' => $user['username'] ?? 'Utilisateur inconnu',
                'publication_date' => $post['publication_date'],
                'content' => $post['content'],
                'like_count' => $post['like_count']
            ];
        }

        // Appeler la vue avec les tweets préparés
        view('home/index', ['title' => 'Accueil', 'posts' => $posts]);
    }
}
