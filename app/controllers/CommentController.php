<?php

class ReplyController extends Controller {
    private $replyModel;

    public function __construct() {
        parent::__construct();
        $this->replyModel = new Reply();
    }

    // Créer une nouvelle réponse
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'content' => $_POST['content'],
                'user_id' => $_POST['user_id'],
                'post_id' => $_POST['post_id'],
                'reply_to' => $_POST['reply_to'] ?? null,
            ];

            if ($this->replyModel->createReply($data)) {
                $this->json(['success' => true, 'message' => 'Réponse créée avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la création de la réponse']);
            }
        }
    }

    // Obtenir une réponse par son ID
    public function get($id) {
        $reply = $this->replyModel->getReplyById($id);
        if ($reply) {
            $this->json(['success' => true, 'data' => $reply]);
        } else {
            $this->json(['success' => false, 'message' => 'Réponse non trouvée']);
        }
    }

    // Mettre à jour une réponse
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($this->replyModel->updateReply($id, $data)) {
                $this->json(['success' => true, 'message' => 'Réponse mise à jour avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de la réponse']);
            }
        }
    }

    // Supprimer une réponse
    public function delete($id) {
        if ($this->replyModel->deleteReply($id)) {
            $this->json(['success' => true, 'message' => 'Réponse supprimée avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression de la réponse']);
        }
    }

    // Obtenir toutes les réponses
    public function getAll() {
        $replies = $this->replyModel->getAllReplies();
        $this->json(['success' => true, 'data' => $replies]);
    }

    // Obtenir les réponses d'un post spécifique
    public function getByPostId($postId) {
        $replies = $this->replyModel->getRepliesByPostId($postId);
        $this->json(['success' => true, 'data' => $replies]);
    }

    // Obtenir les réponses d'un utilisateur spécifique
    public function getByUserId($userId) {
        $replies = $this->replyModel->getRepliesByUserId($userId);
        $this->json(['success' => true, 'data' => $replies]);
    }

    // Obtenir les réponses à une réponse spécifique
    public function getRepliesTo($replyId) {
        $replies = $this->replyModel->getRepliesTo($replyId);
        $this->json(['success' => true, 'data' => $replies]);
    }
}
