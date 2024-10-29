<?php

class Reply extends Model {
    protected $table = 'Reply';

    public function __construct() {
        parent::__construct();
    }

    // Méthode pour créer une nouvelle réponse
    public function createReply(array $data): bool {
        return $this->create($data);
    }

    // Méthode pour obtenir une réponse par son ID
    public function getReplyById($id): mixed {
        return $this->getById($id);
    }

    // Méthode pour mettre à jour une réponse
    public function updateReply($id, array $data): bool {
        return $this->update($id, $data);
    }

    // Méthode pour supprimer une réponse
    public function deleteReply($id): bool {
        return $this->delete($id);
    }

    // Méthode pour obtenir toutes les réponses
    public function getAllReplies(): array {
        return $this->getAll();
    }

    // Méthode pour obtenir les réponses d'un post spécifique
    public function getRepliesByPostId($postId): array {
        return $this->getWhere(['post_id' => $postId]);
    }

    // Méthode pour obtenir les réponses d'un utilisateur spécifique
    public function getRepliesByUserId($userId): array {
        return $this->getWhere(['user_id' => $userId]);
    }

    // Méthode pour obtenir les réponses à une réponse spécifique
    public function getRepliesTo($replyId): array {
        return $this->getWhere(['reply_to' => $replyId]);
    }
}
