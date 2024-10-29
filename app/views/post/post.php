<link rel="stylesheet" href="../css/posts.css">

<div class="create-post">
    <h2>Créer un nouveau post</h2>
    <form id="create-post-form" method="post" action="post">
        <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>
        <button type="submit">Publier</button>
    </form>
</div>

<div class="posts-container">
    <?php if (!empty($post)): ?>

        <!-- Pour debug -->
        <pre><?= json_encode($post, JSON_PRETTY_PRINT) ?></pre>


        <div class="post" data-post-id="<?= htmlspecialchars($post['id']) ?>">
            <div class="post-header"></div>
            <div class="post-user">
                <strong><?= htmlspecialchars($post['username']) ?></strong>
                <span class="post-date"><?= htmlspecialchars($post['publication_date']) ?></span>
            </div>

            <div class="post-content">
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            </div>

            <div class="post-footer">
                <span class="like-count"><?= htmlspecialchars($post['like_count']) ?> Like(s)</span>
                <span class="comment-count"><?= htmlspecialchars($post['comment_count']) ?> Commentaire(s)</span>

                <form method="post" action="<?= url("/post/like") ?>" class="like-form">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <button type="submit">Like</button>
                </form>

                <form method="post" action="<?= url("/post/reply") ?>" class="reply-form">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                    <button type="submit">Répondre</button>
                </form>

                <a href="<?= url("/post/{$post['id']}") ?>">Voir le post</a>
            </div>

            <div class="comments">
                <h3>Commentaires</h3>
                <?php if (!empty($post['comments'])): ?>
                    <?php foreach ($post['comments'] as $comment): ?>
                        <div class="comment" data-comment-id="<?= htmlspecialchars($comment['id']) ?>">
                            <div class="comment-user">
                                <strong><?= htmlspecialchars($comment['username']) ?></strong>
                                <span class="comment-date"><?= htmlspecialchars($comment['publication_date']) ?></span>
                            </div>

                            <div class="comment-content">
                                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            </div>

                            <div class="comment-footer">
                                <span class="like-count
                                "><?= htmlspecialchars($comment['like_count']) ?> Like(s)</span>
                                <span class="reply-count"><?= htmlspecialchars($comment['comment_count']) ?> Réponse(s)</span>
                                <form method="post" action="<?= url("/post/like") ?>" class="like-form">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                    <button type="submit">Like</button>
                                </form>
                                <form method="post" action="<?= url("/post/reply") ?>" class="reply-form">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                    <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                                    <button type="submit">Répondre</button>
                                </form>

                                <a href="<?= url("/post/{$comment['id']}") ?>">Voir le post</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun commentaire à afficher.</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>Aucun post à afficher.</p>
    <?php endif; ?>
</div>

<script src="../js/create_post.js"></script>