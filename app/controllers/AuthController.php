<?php

class AuthController
{
    private $pdo;
    protected $userModel;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function register(): never {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
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
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit();
            } else {
                // Gestion classique (non-Ajax)
                // Afficher les erreurs sur la page
            }
        }
    
        $userModel = new User();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($username, $email, $hashedPassword);
    
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        } else {
            // Redirection classique
            header('Location: /home');
            exit();
        }
    }
    


    public function login($email, $password): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Préparer la requête SQL pour récupérer l'utilisateur par email
        $user = $this->userModel->getUserByEmail(email: $email);

        if ($user && password_verify(password: $password, hash: $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header(header: 'Location: /home');
            exit();
        } else {
            echo 'Email ou mot de passe incorrect.';
        }
    }

    public function logout(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
        header(header: 'Location: /login');
        exit();
    }

    public function index_register(): void
    {
        // Afficher le formulaire d'inscription
        $this->render(view: 'user/register');
    }

    public function index_login(): void
    {
        // Afficher le formulaire d'inscription
        $this->render(view: 'user/login');
    }

    private function render($view, $data = []): void
    {
        // Extraire les variables pour les utiliser dans la vue
        extract(array: $data);

        // Inclure le header
        include __DIR__ . '/../views/layouts/header.php';
        // Inclure la vue principale
        include __DIR__ . '/../views/' . $view . '.php';
        // Inclure le footer
        include __DIR__ . '/../views/layouts/footer.php';
    }

    private function send404($message = 'Page non trouvée'): never
    {
        header(header: "HTTP/1.0 404 Not Found");
        echo $message;
        exit();
    }
}
