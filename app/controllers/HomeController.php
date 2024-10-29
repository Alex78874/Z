<?php

class HomeController
{
    protected $userModel;
    protected $postModel;
    protected $likeModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->likeModel = new Like();
    }

    public function index(): void
    {
        // $posts = $this->postModel->getAll();
        $posts = $this->postModel->getWhereNull(conditions: ['parent_id' => null]);
        $postsData = [];

        // Préparer les données des tweets avec les informations d'utilisateur
        foreach ($posts as $post) {
            $user = $this->userModel->getById($post['user_id']);
            $like_count = $this->likeModel->getLikesCountByPostId($post['id']);
            $comment_count = $this->postModel->getCommentCountParent($post['id']);
            
            $postsData[] = [
                'id' => $post['id'],
                'username' => $user['username'] ?? 'Utilisateur inconnu',
                'publication_date' => $post['publication_date'],
                'content' => $post['content'],
                'like_count' => $like_count,
                'comment_count' => $comment_count,
            ];
        }

        // Appeler la vue avec les tweets préparés
        view('home/index', ['title' => 'Accueil', 'posts' => $postsData]);
    }
}
