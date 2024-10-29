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
        // $posts = $this->postModel->getAll();
        $posts = $this->postModel->getWhereNull(conditions: ['parent_id' => null]);
        $postsWithUsernames = [];

        // Préparer les données des tweets avec les informations d'utilisateur
        foreach ($posts as $post) {
            $user = $this->userModel->getById($post['user_id']);
            
            $postsWithUsernames[] = [
                'id' => $post['id'],
                'username' => $user['username'] ?? 'Utilisateur inconnu',
                'publication_date' => $post['publication_date'],
                'content' => $post['content'],
                'like_count' => $post['like_count'],
                'comment_count' => $this->postModel->getCommentCount($post['id'])
            ];
        }

        // Appeler la vue avec les tweets préparés
        view('home/index', ['title' => 'Accueil', 'posts' => $postsWithUsernames]);
    }
}
