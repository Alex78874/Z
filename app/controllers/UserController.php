<?php

class UserController extends Controller
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

    public function ban($id): void
    {
        $this->startSession();
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
                exit();
            } else {
                redirect('/admin/login');
                exit();
            }
        }

        $success = $this->userModel->deleteUser($id);

        if ($this->isAjaxRequest()) {
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Utilisateur banni avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors du bannissement de l\'utilisateur.']);
            }
            exit();
        } else {
            redirect('/');
        }
    }
}
