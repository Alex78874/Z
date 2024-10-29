<?php

class PostController
{
    private $postModel;
    private $userModel;
    private $likeModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->likeModel = new Like();
    }

    // Méthode pour vérifier si la requête est une requête AJAX
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    // Méthode pour créer un nouveau post
    public function create(): void
    {
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
                    $comment_count = $this->postModel->getCommentCount($newPost['id']);
                    $like_count = $this->likeModel->getLikesCountByPostId($newPost['id']);

                    // Préparer les données du nouveau post
                    $postData = [
                        'id' => $newPost['id'],
                        'username' => $user['username'] ?? 'Utilisateur inconnu',
                        'publication_date' => $newPost['publication_date'],
                        'content' => $newPost['content'],
                        'comment_count' => $comment_count,
                        'like_count' => $like_count
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
    public function fetchNewPosts(): void
    {
        if ($this->isAjaxRequest()) {
            // vardump for debugging
            $lastPostId = $_GET['last_post_id'] ?? 0;
            $newPosts = $this->postModel->getNewPosts($lastPostId);

            $postsData = [];
            foreach ($newPosts as $post) {
                $user = $this->userModel->getById($post['user_id']);
                $like_count = $this->likeModel->getLikesCountByPostId($post['id']);
                $comment_count = $this->postModel->getCommentCountParent($post['id']);

                $postsData[] = [
                    'id' => $post['id'],
                    'username' => $user['username'] ?? 'Utilisateur inconnu',
                    'publication_date' => $post['publication_date'],
                    'content' => $post['content'],
                    'like_count' => $like_count,
                    'comment_count' => $comment_count,
                ];
            }

            echo json_encode(['success' => true, 'posts' => $postsData]);
            exit();
        } else {
            // Si ce n'est pas une requête AJAX, rediriger ou afficher une erreur
            redirect('/');
        }
    }

    // Méthode pour liker/disliker un post
    public function like(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user']['id'])) {
                // Vérifier si l'utilisateur est connecté
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour liker.']);
                    exit();
                } else {
                    redirect('/login');
                    exit();
                }
            }

            $userId = $_SESSION['user']['id'];
            $data = json_decode(file_get_contents('php://input'), true);
            $postId = $data['post_id'] ?? null;

            $userAlreadyLiked = $this->likeModel->hasUserLikedPost($userId, $postId);

            if ($postId && !$userAlreadyLiked) {
                $success = $this->likeModel->addLike($userId, $postId);
                $likeCount = $this->likeModel->getLikesCountByPostId($postId);
                if ($success) {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Post liké avec succès', 'likeCount' => $likeCount]);
                        exit();
                    } else {
                        redirect('/');
                    }
                } else {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du like.']);
                        exit();
                    } else {
                        echo "Erreur lors de l'ajout du like.";
                    }
                }
            } elseif ($postId && $userAlreadyLiked) {
                // Unlike post
                $success = $this->likeModel->removeLike($userId, $postId);
                $likeCount = $this->likeModel->getLikesCountByPostId($postId);
                if ($success) {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Like retiré avec succès', 'likeCount' => $likeCount]);
                        exit();
                    } else {
                        redirect('/');
                    }
                } else {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Erreur lors du retrait du like.']);
                        exit();
                    } else {
                        echo "Erreur lors du retrait du like.";
                    }
                }
            }
        }
    }

    // Méthode pour créer un post de réponse à un post
    public function create_reply(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user']['id'])) {
                // Vérifier si l'utilisateur est connecté
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour répondre.']);
                    exit();
                } else {
                    redirect('/login');
                    exit();
                }
            }

            $userId = $_SESSION['user']['id'];
            $content = $_POST['reply_content'] ?? '';
            $replyTo = $_POST['post_id'] ?? null;
            $parentId = $_POST['parent_id'] ?? null;

            if (!empty(trim($content))) {
                $success = $this->postModel->createReplyPost($userId, $content, $replyTo, $parentId);
                if ($success) {
                    if ($this->isAjaxRequest()) {
                        echo json_encode(['success' => true, 'message' => 'Réponse créée avec succès']);
                        exit();
                    } else {
                        redirect("/post/{$replyTo}");
                    }
                } else {
                    if ($this->isAjaxRequest()) {
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la réponse']);
                        exit();
                    } else {
                        echo "Erreur lors de la création de la réponse";
                    }
                }
            } else {
                if ($this->isAjaxRequest()) {
                    echo json_encode(['success' => false, 'message' => 'Le contenu de la réponse ne peut pas être vide.']);
                    exit();
                } else {
                    echo "Le contenu de la réponse ne peut pas être vide.";
                }
            }
        }
    }

    // Méthode pour afficher un post spécifique
    public function show($id): void
    {
        $post = $this->postModel->getPostById($id);
        $comment_count = $this->postModel->getCommentCount($id);
        $comments = $this->postModel->getComments($id);
        $like_count = $this->likeModel->getLikesCountByPostId($post['id']);

        if ($post) {
            $user = $this->userModel->getById($post['user_id']);
            $post['username'] = $user['username'] ?? 'Utilisateur inconnu';
            $post['comment_count'] = $comment_count;
            $post['like_count'] = $like_count;

            $post['comments'] = array_map(function ($comment) {
                $user = $this->userModel->getById($comment['user_id']);
                $comment_count = $this->postModel->getCommentCount($comment['id']);
                $like_count = $this->likeModel->getLikesCountByPostId($comment['id']);

                $comment['username'] = $user['username'] ?? 'Utilisateur inconnu';
                $comment['comment_count'] = $comment_count;
                $comment['like_count'] = $like_count;
                return $comment;
            }, $comments);

            $data = [
                'post' => $post,
            ];
            view('post/post', $data);
        } else {
            echo "Post non trouvé.";
        }
    }

    // Méthode pour afficher tous les posts d'un utilisateur
    public function userPosts($userId): void
    {
        $posts = $this->postModel->getPostsByUserId($userId);
        $user = $this->userModel->getById($userId);
        view('post/user_posts', ['posts' => $posts, 'user' => $user]);
    }
}
