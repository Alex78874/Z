<?php

class HomeController extends Controller
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
        $posts = $this->postModel->getWhereNull(conditions: ['parent_id' => null]);
        $postsData = [];

        // Préparer les données des tweets avec les informations d'utilisateur
        foreach ($posts as $post) {
            $user = $this->userModel->getById($post['user_id']);
            $like_count = $this->likeModel->getLikesCountByPostId($post['id']);
            $comment_count = $this->postModel->getCommentCountParent($post['id']);

            if ($_SESSION['user'] ?? false) {
                $liked = $this->likeModel->hasUserLikedPost($_SESSION['user']['id'], $post['id']);
            } else {
                $liked = false;
            }
                
            $postsData[] = [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'username' => $user['username'] ?? 'Utilisateur inconnu',
                'user_avatar' => $user['avatar_url'] ?? url("images/avatars/avatar_1.webp"),
                'publication_date' => $post['publication_date'],
                'content' => $post['content'],
                'like_count' => $like_count,
                'comment_count' => $comment_count,
                'liked' => $liked,
                'attachment' => $post['attachment'] ?? null
            ];
        }

        // Appeler la vue avec les tweets préparés
        $this->view('home/index', ['title' => 'Accueil', 'posts' => $postsData]);
    }
}
