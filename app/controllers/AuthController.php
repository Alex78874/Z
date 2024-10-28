<?php

class AuthController
{
    private $pdo;
    protected $userModel;

    public function __construct()
    {
        $this->pdo = getPDO();
        $this->userModel = new User();
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $errors = [];

            if (empty($username) || strlen($username) < 3) {
                $errors[] = "Le nom d'utilisateur doit comporter au moins 3 caractères.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit comporter au moins 8 caractères.";
            }

            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (!empty($errors)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->createUser($username, $email, $hashedPassword);

            // Redirection classique
            redirect('/login');
            exit();
        } else {
            view(view: 'user/register');
        }
    }

    public function login(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            if (empty($email)) {
                $errors[] = 'L\'email est requis.';
            }

            if (empty($password)) {
                $errors[] = 'Le mot de passe est requis.';
            }

            if (!empty($errors)) {
                view('user/login', ['errors' => $errors]);
                return;
            }

            // Récupérer l'utilisateur par email
            $user = $this->userModel->getUserByEmail(email: $email);

            if ($user && password_verify(password: $password, hash: $user['password'])) {
                // Authentification réussie
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'avatar' => $user['avatar_url'] ?? "/../app/assets/images/default_avatar.png",
                    'registration_date' => $user['registration_date'],
                ];
                redirect(url: '/');
                exit();
            } else {
                // Authentification échouée
                $errors[] = 'Email ou mot de passe incorrect.';
                view(view: 'user/login', data: ['errors' => $errors]);
            }
        } else {
            view(view: 'user/login');
        }
    }

    public function logout(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
        redirect('/');
        exit();
    }
}
