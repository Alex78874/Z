<link rel="stylesheet" <?php url("css/posts.css") ?>>

<div class="posts-container">
    <?php if (!empty($tweets)): ?>
        <?php foreach ($tweets as $tweet): ?>
            <div class="post">
                <div class="post-header">
                    <div class="post-user">
                        <strong><?= htmlspecialchars($tweet['username']) ?></strong> <!-- Nom de l'utilisateur -->
                        <span class="post-date"><?= htmlspecialchars($tweet['publication_date']) ?></span> <!-- Date de publication -->
                    </div>
                </div>
                <div class="post-content">
                    <p><?= nl2br(htmlspecialchars($tweet['content'])) ?></p> <!-- Contenu du tweet -->
                </div>
                <div class="post-footer">
                    <span class="like-count"><?= htmlspecialchars($tweet['like_count']) ?> Like(s)</span>
                    <form method="post" action="/post/like" class="like-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($tweet['id']) ?>">
                        <button type="submit">Like</button>
                    </form>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun post à afficher.</p>
    <?php endif; ?>
</div>
