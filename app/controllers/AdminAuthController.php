<?php

class AdminAuthController extends Controller
{
    protected $adminModel;
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new Admin();
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
                    $this->view('admin/register', ['errors' => $errors]);
                }
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Créer le compte administrateur
            $this->adminModel->createAdmin($username, $email, $hashedPassword);
            // Créer le compte utilisateur correspondant
            $this->userModel->createUser($username, $email, $hashedPassword);

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

            // Récupérer l'administrateur par email
            $admin = $this->adminModel->getUserByEmail($email);

            if ($admin && password_verify($password, $admin['password'])) {
                // Authentification administrateur réussie
                $this->startSession();
                $_SESSION['admin'] = [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'email' => $admin['email'],
                    'avatar' => $admin['avatar_url'] ?? "images/avatar.png",
                    'registration_date' => $admin['registration_date'],
                ];
                $_SESSION['admin_logged_in'] = true;

                // Connecter le compte utilisateur correspondant
                $user = $this->userModel->getUserByEmail($email);
                if ($user) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'avatar' => $user['avatar_url'] ?? "images/avatar.png",
                        'registration_date' => $user['registration_date'],
                    ];
                }

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
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        unset($_SESSION['admin_logged_in']);
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}
