<?php

class AdminAuthController extends Controller
{
    protected $adminModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new Admin();
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
                    $this->view('admin/register', ['errors' => $errors]);
                }
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->adminModel->createUser($username, $email, $hashedPassword);

            $this->redirect('admin/login');
        } else {
            $this->view('admin/register');
        }
    }

    public function login(): void
    {
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
                $this->view('admin/login', ['errors' => $errors]);
                return;
            }

            // Récupérer l'utilisateur par email
            $admin = $this->adminModel->getUserByEmail($email);

            if ($admin && password_verify($password, $admin['password'])) {
                // Authentification réussie
                $this->startSession();
                $_SESSION['admin'] = [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'email' => $admin['email'],
                    'avatar' => $admin['avatar_url'] ?? "/../app/assets/images/default_avatar.png",
                    'registration_date' => $admin['registration_date'],
                ];
                $this->redirect('/');
            } else {
                // Authentification échouée
                $errors[] = 'Email ou mot de passe incorrect.';
                $this->view('admin/login', ['errors' => $errors]);
            }
        } else {
            $this->view('admin/login');
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
