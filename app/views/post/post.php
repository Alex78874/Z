<link rel="stylesheet" href="../css/posts.css">

<div class="posts-container">
    <?php if (!empty($post)): ?>

        <!-- Pour debug -->
        <!-- <pre><?= json_encode($post, JSON_PRETTY_PRINT) ?></pre> -->

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

                <form method="post" action="<?= url("/post/reply") ?>" class="parent-reply-form">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="parent_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                    <button type="submit">Répondre</button>
                </form>

                <a href="<?= url("/post/{$post['id']}") ?>">Voir le post</a>
            </div>
        </div>

        <hr>

        <h3>Commentaires</h3>
        <div class="comments">
            <?php if (!empty($post['comments'])): ?>
                <?php foreach ($post['comments'] as $comment): ?>

                    <!-- Pour debug -->
                    <!-- <pre><?= json_encode($comment, JSON_PRETTY_PRINT) ?></pre> -->

                    <div class="comment" data-comment-id="<?= htmlspecialchars($comment['id']) ?>">
                        <div class="comment-user">
                            <strong><?= htmlspecialchars($comment['username']) ?></strong>
                            <span class="comment-date"><?= htmlspecialchars($comment['publication_date']) ?></span>
                        </div>

                        <div class="comment-content">
                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>

                        <div class="comment-footer">
                            <span class="like-count"><?= htmlspecialchars($comment['like_count']) ?> Like(s)</span>
                            <span class="reply-count"><?= htmlspecialchars($comment['comment_count']) ?> Réponse(s)</span>

                            <form method="post" action="<?= url("/post/like") ?>" class="like-form">
                                <input type="hidden" name="post_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <button type="submit">Like</button>
                            </form>
                            <form method="post" action="<?= url("/post/reply") ?>" class="reply-form">
                                <input type="hidden" name="post_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <input type="hidden" name="parent_id" value="<?= htmlspecialchars($comment['parent_id']) ?>">
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

<script src="../js/like_post.js"></script>
<script src="../js/reply_to_post.js"></script>