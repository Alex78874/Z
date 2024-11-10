<?php

class Post extends Model {
    protected $table = 'Post';

    public function __construct() {
        parent::__construct(); // Appel au constructeur de la classe parente
        
    }

    public function createPost($userId, $content, $attachementPath): bool {
        // Utilisation de la méthode `create` de la classe parente pour insérer un post
        return $this->create([
            'user_id' => $userId,
            'content' => $content,
            'publication_date' => date('Y-m-d H:i:s'),
            'attachment' => $attachementPath
        ]);
    }

    public function createReplyPost($userId, $content, $reply_to, $parent_id): mixed {
        return $this->createAndGet([
            'user_id' => $userId,
            'content' => $content,
            'publication_date' => date('Y-m-d H:i:s'),
            'reply_to' => $reply_to,
            'parent_id' => $parent_id
        ]);
    }

    public function getPostById($id): mixed {
        // Utilisation de la méthode `getById` de la classe parente pour obtenir un post par son ID
        return $this->getById($id);
    }

    public function getPostsByUserId($userId): array {
        // Requête personnalisée pour obtenir les posts par l'utilisateur
        return $this->getWhere(['user_id' => $userId]);
    }

    public function getPostsCountsByUserId($userId): int {
        // Requête personnalisée pour obtenir le nombre de posts par l'utilisateur
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Post WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function getCommentsCountsByUserId($userId): int {
        // Requête personnalisée pour obtenir le nombre de commentaires par l'utilisateur
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Post WHERE user_id = :user_id AND reply_to IS NOT NULL');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
    

    // Méthode pour obtenir les derniers posts
    public function getLastInsertedPost(): mixed {
        // Requête personnalisée pour obtenir le dernier post inséré
        return $this->getLastInserted();
    }

    // Méthode pour obtenir les nouveaux posts après un certain ID
    public function getNewPosts($lastPostId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM Post WHERE id > :lastId AND parent_id IS NULL ORDER BY id DESC");
        $stmt->execute(['lastId' => $lastPostId]);
        return $stmt->fetchAll();
    }    
    
    // Méthode pour le compte de commentaire (posts) lié à un post
    public function getCommentCount($postId): int {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Post WHERE reply_to = :post_id');
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchColumn();
    }

    // Méthode pour le compte de commentaire (posts) lié à un post parent
    public function getCommentCountParent($postId): int {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM Post WHERE parent_id = :post_id');
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchColumn();
    }

    // Méthode pour obtenir les commentaire (posts) lié à un post
    public function getComments($postId): array {
        return $this->getWhere(['reply_to' => $postId]);
    }

    // Méthode pour obtenir les commentaire (posts) lié à un post parent
    public function getCommentsParent($postId): array {
        return $this->getWhere(['parent_id' => $postId]);
    }

    public function updatePost($id, $content): bool {
        // Utilisation de la méthode `update` de la classe parente pour mettre à jour un post
        return $this->update($id, ['content' => $content]);
    }

    public function deletePost($id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM Post WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function incrementLikeCount($id): bool {
        // Incrémente le nombre de likes d'un post
        $stmt = $this->pdo->prepare('UPDATE Post SET like_count = like_count + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function decrementLikeCount($id): bool {
        // Décrémente le nombre de likes d'un post (ne descend pas en-dessous de 0)
        $stmt = $this->pdo->prepare('UPDATE Post SET like_count = GREATEST(like_count - 1, 0) WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
