<?php

class PostController {
    private $postModel;
    private $userModel;

    public function __construct() {
        $this->postModel = new Post();
        $this->userModel = new User();
    }

    // Méthode pour créer un nouveau post
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                // Vérifier si l'utilisateur est connecté
                redirect('/login');
                exit();
            }

            $userId = $_SESSION['user_id'];
            $content = $_POST['content'] ?? '';

            if (!empty(trim($content))) {
                $success = $this->postModel->createPost($userId, $content);
                if ($success) {
                    redirect('/home');
                } else {
                    echo "Erreur lors de la création du post.";
                }
            } else {
                echo "Le contenu du post ne peut pas être vide.";
            }
        }
    }

    // Méthode pour liker un post
    public function like(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                // Vérifier si l'utilisateur est connecté
                redirect('/login');
                exit();
            }

            $postId = $_POST['post_id'] ?? null;

            if ($postId) {
                $success = $this->postModel->incrementLikeCount($postId);
                if ($success) {
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    echo "Erreur lors de l'ajout du like.";
                }
            } else {
                echo "ID du post non valide.";
            }
        }
    }

    // Méthode pour répondre à un post
    public function reply() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                // Vérifier si l'utilisateur est connecté
                redirect('/login');
                exit();
            }

            $userId = $_SESSION['user_id'];
            $postId = $_POST['post_id'] ?? null;
            $replyContent = $_POST['reply_content'] ?? '';

            if ($postId && !empty(trim($replyContent))) {
                // Ici, vous devrez probablement insérer la réponse dans une table de réponse
                // Par exemple, `replyModel->createReply($postId, $userId, $replyContent);`
                // Pour l'instant, nous simulons simplement l'insertion de la réponse.
                
                echo "Réponse enregistrée. (Ce code doit être adapté pour une vraie table des réponses.)";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                echo "Le contenu de la réponse ne peut pas être vide ou ID du post non valide.";
            }
        }
    }

    // Méthode pour afficher un post spécifique
    public function show($postId) {
        $post = $this->postModel->getPostById($postId);
        if ($post) {
            $user = $this->userModel->getById($post['user_id']);
            view('post/show', ['post' => $post, 'user' => $user]);
        } else {
            echo "Post non trouvé.";
        }
    }

    // Méthode pour afficher tous les posts d'un utilisateur
    public function userPosts($userId) {
        $posts = $this->postModel->getPostsByUserId($userId);
        $user = $this->userModel->getById($userId);
        view('post/user_posts', ['posts' => $posts, 'user' => $user]);
    }
}
