<div class="posts-container">
    <?php if (!empty($post)): ?>

        <!-- Pour debug -->
        <!-- <pre><?= json_encode($post, JSON_PRETTY_PRINT) ?></pre> -->

        <div class="post" data-post-id="<?= htmlspecialchars($post['id']) ?>">
            <div class="post-header">
                <div class="post-user">
                    <img class="post-avatar" src="<?= htmlspecialchars($post['user_avatar']) ?>"
                        alt="Avatar de l'utilisateur">
                    <strong><?= htmlspecialchars($post['username']) ?></strong>
                    <span class="post-date"><?= htmlspecialchars($post['publication_date']) ?></span>
                </div>
            </div>

            <div class="post-content">
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <?php if (!empty($post['attachment'])): ?>
                    <img class="post-attachment" src="<?= htmlspecialchars($post['attachment']) ?>" alt="Image attachée au post">
                <?php endif; ?>
            </div>

            <div class="post-footer">
                <div class="post-like">
                    <button class="like-button" data-post-id="<?= htmlspecialchars($post['id']) ?>"
                        data-liked="<?= $post['liked'] ? 'yes' : 'no' ?>">
                        <?php if ($post['liked']): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-heart-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                            </svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-heart" viewBox="0 0 16 16">
                                <path
                                    d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                            </svg>
                        <?php endif; ?>
                    </button>
                    <!-- Affichage du nombre de likes -->
                    <span class="like-count"><?= htmlspecialchars($post['like_count']) ?></span>
                </div>
                <div class="post-comment-count">
                    <button class="comment-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-chat-left-text" viewBox="0 0 16 16">
                            <path
                                d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                            <path
                                d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                        </svg>
                    </button>
                    <span class="comment-count"><?= htmlspecialchars($post['comment_count']) ?></span>
                </div>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <button class="btn-delete-post" data-post-id="<?= htmlspecialchars($post['id']); ?>">Supprimer le post</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="post-createresponse">
                <form method="post" action="<?= url("/post/reply") ?>" class="parent-reply-form">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="parent_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                    <button type="submit">Répondre</button>
                </form>
            </div>

        <div class="comments">
            <?php if (!empty($post['comments'])): ?>
                <?php foreach ($post['comments'] as $comment): ?>
                    <hr class="post-separator">
                    <!-- Pour debug -->
                    <!-- <pre><?= json_encode($comment, JSON_PRETTY_PRINT) ?></pre> -->

                    <div class="post" data-post-id="<?= htmlspecialchars($comment['id']) ?>">
                        <div class="post-header">
                            <div class="post-user">
                                <img class="post-avatar" src="<?= htmlspecialchars($comment['user_avatar']) ?>" alt="Avatar de l'utilisateur">
                                <strong><?= htmlspecialchars($comment['username']) ?></strong>
                                <span class="post-date"><?= htmlspecialchars($comment['publication_date']) ?></span>
                            </div>
                        </div>

                        <div class="post-content">
                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            <?php if (!empty($comment['attachment'])): ?>
                                <img class="post-attachment" src="<?= htmlspecialchars($comment['attachment']) ?>" alt="Image attachée au commentaire">
                            <?php endif; ?>
                        </div>

                        <div class="post-footer">
                            <div class="post-like">
                                <button class="like-button" data-post-id="<?= htmlspecialchars($comment['id']) ?>"
                                    data-liked="<?= $comment['liked'] ? 'yes' : 'no' ?>">
                                    <?php if ($comment['liked']): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                                        </svg>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-heart" viewBox="0 0 16 16">
                                            <path
                                                d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                                        </svg>
                                    <?php endif; ?>
                                </button>
                                <!-- Affichage du nombre de likes -->
                                <span class="like-count"><?= htmlspecialchars($comment['like_count']) ?></span>
                            </div>
                            <div class="post-comment-count">
                                <button class="comment-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                        <path
                                            d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                        <path
                                            d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                    </svg>
                                </button>
                                <span class="reply-count"><?= htmlspecialchars($comment['comment_count']) ?></span>
                            </div>
                            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                                <button class="btn-delete-post" data-post-id="<?= htmlspecialchars($comment['id']); ?>">Supprimer le commentaire</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun commentaire à afficher.</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>Aucun post à afficher.</p>
    <?php endif; ?> 
</div>

<script src="../js/like_post.js" type="module"></script>
<script src="../js/reply_to_post.js"></script>
<script src="../js/delete_post.js"></script>
<script src='../js/scripts.js'></script>