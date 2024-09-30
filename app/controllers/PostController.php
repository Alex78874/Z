<?php

class PostController {
    private $postModel;
    private $userModel;

    public function __construct() {
        $this->postModel = new Post();
        $this->userModel = new User();
    }

    // Méthode pour vérifier si la requête est une requête AJAX
    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    // Méthode pour créer un nouveau post
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            if (!isset($_SESSION['user']['id'])) {
                // Vérifier si l'utilisateur est connecté
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour poster.']);
                    exit();
                } else {
                    redirect('/login');
                    exit();
                }
            }
    
            $userId = $_SESSION['user']['id'];
            $content = $_POST['content'] ?? '';
    
            if (!empty(trim($content))) {
                $success = $this->postModel->createPost($userId, $content);
                if ($success) {
                    $newPost = $this->postModel->getLastInsertedPost();
                    $user = $this->userModel->getById($userId);
    
                    // Préparer les données du nouveau post
                    $postData = [
                        'id' => $newPost['id'],
                        'username' => $user['username'] ?? 'Utilisateur inconnu',
                        'publication_date' => $newPost['publication_date'],
                        'content' => $newPost['content'],
                        'like_count' => $newPost['like_count']
                    ];
    
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'post' => $postData]);
                        exit();
                    } else {
                        redirect('/');
                    }
                } else {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du post.']);
                        exit();
                    } else {
                        echo "Erreur lors de la création du post.";
                    }
                }
            } else {
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'message' => 'Le contenu du post ne peut pas être vide.']);
                    exit();
                } else {
                    echo "Le contenu du post ne peut pas être vide.";
                }
            }
        }
    }

    // Méthode pour récupérer les nouveaux posts apres un certain ID
    public function fetchNewPosts(): never {
        if ($this->isAjaxRequest()) {
            $lastPostId = $_GET['last_post_id'] ?? 0;
            $newPosts = $this->postModel->getNewPosts($lastPostId);
    
            $postsData = [];
            foreach ($newPosts as $post) {
                $user = $this->userModel->getById($post['user_id']);
                $postsData[] = [
                    'id' => $post['id'],
                    'username' => $user['username'] ?? 'Utilisateur inconnu',
                    'publication_date' => $post['publication_date'],
                    'content' => $post['content'],
                    'like_count' => $post['like_count']
                ];
            }
    
            echo json_encode(['success' => true, 'posts' => $postsData]);
            exit();
        } else {
            // Si ce n'est pas une requête AJAX, rediriger ou afficher une erreur
            redirect('/');
        }
    }
    
    
    // Méthode pour liker un post
    public function like(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user']['id'])) {
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
            if (!isset($_SESSION['user']['id'])) {
                // Vérifier si l'utilisateur est connecté
                redirect('/login');
                exit();
            }

            $userId = $_SESSION['user']['id'];
            $postId = $_POST['post_id'] ?? null;
            $replyContent = $_POST['reply_content'] ?? '';

            if ($postId && !empty(trim($replyContent))) {
                // Ici, vous devrez probablement insérer la réponse dans une table de réponse
                // Par exemple, `replyModel->createReply($postId, $userId, $replyContent);`
                // Pour l'instant, nous simulons simplement l'insertion de la réponse.
                
                echo "Réponse enregistrée. (Ce code doit être adapté pour une vraie table des réponses.)";
                redirect($_SERVER['HTTP_REFERER']);
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
