<link rel="stylesheet" <?= url("css/posts.css") ?>>

<div class="create-post">
    <h2>Créer un nouveau post</h2>
    <form method="post" action="<?= url("post") ?>">
        <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>
        <button type="submit">Publier</button>
    </form>
</div>

<div class="posts-container">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                    <div class="post-user">
                        <strong><?= htmlspecialchars($post['username']) ?></strong> <!-- Nom de l'utilisateur -->
                        <span class="post-date"><?= htmlspecialchars($post['publication_date']) ?></span> <!-- Date de publication -->
                    </div>
                </div>
                <div class="post-content">
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p> <!-- Contenu du tweet -->
                </div>
                <div class="post-footer">
                    <span class="like-count"><?= htmlspecialchars($post['like_count']) ?> Like(s)</span>
                    
                    <!-- Formulaire pour liker un post -->
                    <form method="post" action="<?= url("/post/like") ?>" class="like-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <button type="submit">Like</button>
                    </form>

                    <!-- Bouton pour répondre à un post -->
                    <form method="post" action="<?= url("/post/reply") ?>" class="reply-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                        <button type="submit">Répondre</button>
                    </form>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun post à afficher.</p>
    <?php endif; ?>
</div>
