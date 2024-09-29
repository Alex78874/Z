<?php

class Post extends Model {
    protected $table = 'Post';

    public function __construct() {
        parent::__construct(); // Appel au constructeur de la classe parente
    }

    public function createPost($userId, $content): bool {
        // Utilisation de la méthode `create` de la classe parente pour insérer un post
        return $this->create([
            'user_id' => $userId,
            'content' => $content,
            'publication_date' => date('Y-m-d H:i:s'), // Ajout de la date de publication actuelle
            'like_count' => 0 // Initialiser le compteur de likes à 0
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

    public function updatePost($id, $content): bool {
        // Utilisation de la méthode `update` de la classe parente pour mettre à jour un post
        return $this->update($id, ['content' => $content]);
    }

    public function deletePost($id): bool {
        // Utilisation de la méthode `delete` de la classe parente pour supprimer un post
        return $this->delete($id);
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
