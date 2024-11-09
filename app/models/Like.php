<?php

class Like extends Model
{
    protected $table = '`Like`'; // Escaping the table name

    public function getLikesByUserId(int $userId): array
    {
        return $this->getWhere(['user_id' => $userId]);
    }

    public function getLikesCountByUserId(int $userId): int
    {
        return $this->getWhereCount(['user_id' => $userId]);
    }

    public function getLikesByPostId(int $postId): array
    {
        return $this->getWhere(['post_id' => $postId]);
    }

    public function getLikesCountByPostId(int $postId): int
    {
        return $this->getWhereCount(['post_id' => $postId]);
    }

    public function hasUserLikedPost(int $userId, int $postId): bool
    {
        return $this->getWhereCount(['user_id' => $userId, 'post_id' => $postId]) > 0;
    }

    public function addLike(int $userId, int $postId): bool
    {
        return $this->create(['user_id' => $userId, 'post_id' => $postId]);
    }

    public function removeLike(int $userId, int $postId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE user_id = :user_id AND post_id = :post_id");
        return $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
    }
}