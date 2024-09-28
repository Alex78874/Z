<?php

class UserController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
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

        if (!$user) {
            send404('Utilisateur non trouvé.');
        }

        $data = [
            'user' => $user,
        ];

        view(view: '/user/profile', data: $data);
    }
}
