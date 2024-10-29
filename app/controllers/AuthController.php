<?php

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
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
                if ($this->isAjaxRequest()) {
                    $this->json(['success' => false, 'errors' => $errors]);
                } else {
                    $this->view('user/register', ['errors' => $errors]);
                }
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->createUser($username, $email, $hashedPassword);

            $this->redirect('/login');
        } else {
            $this->view('user/register');
        }
    }

    public function login(): void
    {
        $this->startSession();

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
                $this->view('user/login', ['errors' => $errors]);
                return;
            }

            // Récupérer l'utilisateur par email
            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'avatar' => $user['avatar_url'] ?? "/../app/assets/images/default_avatar.png",
                    'registration_date' => $user['registration_date'],
                ];
                $this->redirect('/');
            } else {
                // Authentification échouée
                $errors[] = 'Email ou mot de passe incorrect.';
                $this->view('user/login', ['errors' => $errors]);
            }
        } else {
            $this->view('user/login');
        }
    }

    public function logout(): void
    {
        $this->startSession();
        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
    }
}
