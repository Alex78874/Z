<?php

class UserController
{
    protected $userModel;
    protected $postModel;
    protected $likeModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->likeModel = new Like();
    }

    public function profile($id): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            redirect('/login');
            exit();
        }

        $user = $this->userModel->getUserById(id: $id);
        $commentsCount = $this->postModel->getCommentsCountsByUserId(userId: $id);
        $postsCount = $this->postModel->getPostsCountsByUserId(userId: $id);
        $likesCount = $this->likeModel->getLikesCountByUserId(userId: $id);

        if (!$user) {
            send404('Utilisateur non trouvé.');
        }

        $data = [
            'user' => $user,
            'commentsCount' => $commentsCount,
            'postsCount' => $postsCount,
            'likesCount' => $likesCount
        ];

        view(view: '/user/profile', data: $data);
    }
}
