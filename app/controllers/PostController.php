<?php

class PostController extends Controller
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

    // Méthode pour créer un nouveau post
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->startSession();

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
            $attachmentPath = null;

            // Vérifier si un fichier a été téléchargé sans erreur
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['attachment']['tmp_name'];
                $fileType = mime_content_type($fileTmpPath);

                // Types MIME autorisés
                $allowedMimeTypes = ['image/webp'];
                if (in_array($fileType, $allowedMimeTypes)) {
                    $uploadDir = __DIR__ . '/../../public/images/';
                    // Générer un nom de fichier unique
                    $newFileName = uniqid('img_') . '.webp';
                    $destPath = "{$uploadDir}{$newFileName}";

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $attachmentPath = "/images/{$newFileName}";
                    }
                }
            }

            if (!empty(trim($content))) {
                $success = $this->postModel->createPost($userId, $content, $attachmentPath);
                if ($success) {
                    $newPost = $this->postModel->getLastInsertedPost();
                    $user = $this->userModel->getById($userId);
                    $comment_count = $this->postModel->getCommentCount($newPost['id']);
                    $like_count = $this->likeModel->getLikesCountByPostId($newPost['id']);

                    // Préparer les données du nouveau post
                    $postData = [
                        'id' => $newPost['id'],
                        'username' => $user['username'] ?? 'Utilisateur inconnu',
                        'user_avatar' => $user['avatar_url'] ?? 'images/avatar.png',
                        'publication_date' => $newPost['publication_date'],
                        'content' => $newPost['content'],
                        'comment_count' => $comment_count,
                        'like_count' => $like_count,
                        'attachment' => $newPost['attachment']
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
                $liked = $this->likeModel->hasUserLikedPost($_SESSION['user']['id'], $post['id']);

                $postsData[] = [
                    'id' => $post['id'],
                    'username' => $user['username'] ?? 'Utilisateur inconnu',
                    'user_avatar' => $user['avatar_url'] ?? 'images/avatar.png',
                    'publication_date' => $post['publication_date'],
                    'content' => $post['content'],
                    'like_count' => $like_count,
                    'comment_count' => $comment_count,
                    'liked' => $liked,
                    'attachment' => $post['attachment']
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
            $this->startSession();

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
                        echo json_encode(['success' => true, 'message' => 'Post liké avec succès', 'liked' => true, 'likeCount' => $likeCount]);
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
                        echo json_encode(['success' => true, 'message' => 'Like retiré avec succès', 'liked' => false, 'likeCount' => $likeCount]);
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
            $this->startSession();

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

            if ($this->isAjaxRequest()) {
                $inputData = json_decode(file_get_contents('php://input'), true);
                $content = $inputData['reply_content'] ?? '';
                $replyTo = $inputData['post_id'] ?? null;
                $parentId = $inputData['parent_id'] ?? null;
            } else {
                $content = $_POST['reply_content'] ?? '';
                $replyTo = $_POST['post_id'] ?? null;
                $parentId = $_POST['parent_id'] ?? null;
            }

            if (!empty(trim($content))) {
                $reply = $this->postModel->createReplyPost($userId, $content, $replyTo, $parentId);
                if ($reply) {
                    if ($this->isAjaxRequest()) {
                        $user = $this->userModel->getById($userId);
                        $comment = [
                            'id' => $reply['id'],
                            'username' => $user['username'] ?? 'Utilisateur inconnu',
                            'user_avatar' => $user['avatar_url'] ?? 'images/avatar.png',
                            'publication_date' => $reply['publication_date'],
                            'content' => $reply['content'],
                            'like_count' => 0,
                            'comment_count' => 0,
                            'liked' => false,
                            'attachment' => $reply['attachment']
                        ];
                        echo json_encode(['success' => true, 'message' => 'Réponse créée avec succès', 'comment' => $comment]);
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

        if (isset($_SESSION['user'])) {
            $liked = $this->likeModel->hasUserLikedPost($_SESSION['user']['id'], $post['id']);
        } else {
            $liked = false;
        }

        if ($post) {
            $user = $this->userModel->getById($post['user_id']);
            $post['username'] = $user['username'] ?? 'Utilisateur inconnu';
            $post['user_avatar'] = $user['avatar_url'] ?? url('images/avatar.png');
            $post['comment_count'] = $comment_count;
            $post['like_count'] = $like_count;
            $post['liked'] = $liked;

            $post['comments'] = array_map(function ($comment) {
                $user = $this->userModel->getById($comment['user_id']);
                $comment_count = $this->postModel->getCommentCount($comment['id']);
                $like_count = $this->likeModel->getLikesCountByPostId($comment['id']);
                $liked = $this->likeModel->hasUserLikedPost($_SESSION['user']['id'], $comment['id']);

                $comment['username'] = $user['username'] ?? 'Utilisateur inconnu';
                $comment['user_avatar'] = $user['avatar_url'] ?? url('images/avatar.png');
                $comment['comment_count'] = $comment_count;
                $comment['like_count'] = $like_count;
                $comment['liked'] = $liked;
                return $comment;
            }, $comments);

            $data = [
                'post' => $post,
            ];
            $this->view('post/post', data: $data);
        } else {
            echo "Post non trouvé.";
        }
    }

    // // Méthode pour afficher tous les posts d'un utilisateur
    // public function userPosts($userId): void
    // {
    //     $posts = $this->postModel->getPostsByUserId($userId);
    //     $user = $this->userModel->getById($userId);
    //     $this->view('post/user_posts', ['posts' => $posts, 'user' => $user]);
    // }

    public function delete($id): void
    {
        $this->startSession();
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            if ($this->isAjaxRequest()) {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
                exit();
            } else {
                $this->redirect('/admin/login');
                exit();
            }
        }

        $success = $this->postModel->deletePost($id);
        if ($this->isAjaxRequest()) {
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Post supprimé avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du post.']);
            }
            exit();
        } else {
            if ($success) {
                $this->redirect('/');
            } else {
                echo "Erreur lors de la suppression du post.";
            }
        }
    }
}
