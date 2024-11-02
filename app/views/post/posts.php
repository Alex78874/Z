<link rel="stylesheet" href="../css/posts.css">

<div class="create-post">
    <h2>Créer un nouveau post</h2>
    <form id="create-post-form" method="post" action="post">
        <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>
        <button type="submit">Publier</button>
    </form>
</div>

<br><br>

<div class="posts-container">
    <?php if (!empty($posts)): ?>
        <!-- Pour debug -->
        <!-- <?= "<pre>" . json_encode($posts, JSON_PRETTY_PRINT) . "</pre>" ?> -->

        <?php foreach ($posts as $post): ?>
            <div class="post" data-post-id="<?= htmlspecialchars($post['id']) ?>"> <!-- Ajout de l'attribut data-post-id -->
                <div class="post-header">
                    <div class="post-user">
                        <strong><?= htmlspecialchars($post['username']) ?></strong> <!-- Nom de l'utilisateur -->
                        <span class="post-date"><?= htmlspecialchars($post['publication_date']) ?></span>
                        <!-- Date de publication -->
                    </div>
                </div>
                <div class="post-content">
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p> <!-- Contenu du post -->
                </div>
                <div class="post-footer">
                    <span class="like-count"><?= htmlspecialchars($post['like_count']) ?> Like(s)</span>

                    <!-- Affichage du nombre de commentaires -->
                    <span class="comment-count"><?= htmlspecialchars($post['comment_count']) ?> Commentaire(s)</span>

                    <!-- Formulaire pour liker un post -->
                    <form method="post" action="<?= url("/post/like") ?>" class="like-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <button type="submit">Like</button>
                    </form>

                    <!-- Bouton pour répondre à un post -->
                    <form method="post" action="<?= url("/post/reply") ?>" class="reply-form">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <input type="hidden" name="parent_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                        <button type="submit">Répondre</button>
                    </form>

                    <!-- Bouton pour afficher le post -->
                    <a href="<?= url("/post/{$post['id']}") ?>">Voir le post</a>

                    <!-- Autres actions (like, commenter, etc.) -->
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- <a href="<?= url('post/delete/' . $post['id']); ?>" class="btn-delete">Supprimer le post</a> -->
                        <button class="btn-delete-post" data-post-id="<?= htmlspecialchars($post['id']); ?>">Supprimer le post</button>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun post à afficher.</p>
    <?php endif; ?>
</div>

<script src="../js/create_post.js"></script>
<script src="../js/like_post.js"></script>
<script src="<?= url('js/delete_post.js') ?>"></script>