<body>
    <!-- Section Profil Utilisateur -->
    <main>
        <section class="profile">
            <div class="profile-left-container">

                <!-- Afficher les posts de l'utilisateur -->
                <div class="user-posts">
                    <h2>Publication de <?= htmlspecialchars($user['username']); ?></h2>
                    <div class="posts-container">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <hr class="post-separator">

                                <div class="post" data-post-id="<?= htmlspecialchars($post['id']) ?>">
                                    <!-- Ajout de l'attribut data-post-id -->
                                    <div class="post-header">
                                        <div class="post-user">
                                            <img class="post-avatar"
                                                src="<?= htmlspecialchars(url($user['avatar_url'] ?? "images/avatar_1.webp")) ?>"
                                                alt="Avatar de l'utilisateur">
                                            <strong><?= htmlspecialchars($user['username']) ?></strong>
                                            <!-- Nom de l'utilisateur -->
                                            <span class="post-date"><?= htmlspecialchars($post['publication_date']) ?></span>
                                            <!-- Date de publication -->
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p> <!-- Contenu du post -->
                                        <?php if ($post['attachment']): ?>
                                            <img class="post-attachment" src="<?= htmlspecialchars($post['attachment']) ?>"
                                                alt="Image attachée au post">
                                        <?php endif; ?>
                                    </div>

                                    <div class="post-footer">
                                        <!-- Bouton pour liker un post -->
                                        <div class="post-like">
                                            <button class="like-button" data-post-id="<?= htmlspecialchars($post['id']) ?> aria-label="Like post"
                                                data-liked="<?= $post['liked'] ? 'yes' : 'no' ?>">
                                                <?php if ($post['liked']): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                                                    </svg>
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                        <path
                                                            d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                                                    </svg>
                                                <?php endif; ?>
                                            </button>
                                            <!-- Affichage du nombre de likes -->
                                            <span class="like-count"><?= htmlspecialchars($post['like_count']) ?></span>
                                        </div>

                                        <!-- Bouton pour commenter un post -->
                                        <div class="post-comment-count">
                                            <button class="comment-button" data-post-id="<?= htmlspecialchars($post['id']) ?>" aria-label="Comment on post">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                                    <path
                                                        d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path
                                                        d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                                </svg>
                                            </button>
                                            <!-- Affichage du nombre de commentaires -->
                                            <span class="comment-count"><?= htmlspecialchars($post['comment_count']) ?></span>
                                        </div>

                                        <!-- Actions admin -->
                                        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                                            <div class="admin-section">
                                                <div class="admin-actions">
                                                    <button class="btn-delete-post" aria-label="Delete post"
                                                        data-post-id="<?= htmlspecialchars($post['id']); ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="admin-actions">
                                                    <button class="btn-ban-user" aria-label="Ban user"
                                                        data-user-id="<?= htmlspecialchars($post['user_id']); ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-person-fill-slash" viewBox="0 0 16 16">
                                                            <path
                                                                d="M13.879 10.414a2.501 2.501 0 0 0-3.465 3.465zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465m-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <hr class="post-separator">
                        <?php else: ?>
                            <p>Aucun post à afficher.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="profile-right-container">
                <img src="<?= htmlspecialchars($user['avatar_url'] ?? url("images/avatar_1.webp")); ?>"
                    alt="Avatar de <?= htmlspecialchars($user['username']); ?>">

                <div class="profile-name">
                    <h1><?= htmlspecialchars($user['username']); ?></h1>
                    <p><?= htmlspecialchars($user['email']); ?></p>
                </div>

                <hr>

                <div class="profile-stats">
                    <div>
                        <div class="profile-stats-card">
                            <h3><?= htmlspecialchars($postsCount); ?></h3>
                            <p>Posts</p>
                        </div>
                        <div class="profile-stats-card">
                            <h3><?= htmlspecialchars($likesCount); ?></h3>
                            <p>Likes</p>
                        </div>
                    </div>

                    <div>
                        <div class="profile-stats-card">
                            <h3><?= htmlspecialchars($commentsCount); ?></h3>
                            <p>Commentaires</p>
                        </div>
                        <div class="profile-stats-card">
                            <h3><?= htmlspecialchars($user['registration_date']); ?></h3>
                            <p>Date d'inscription</p>
                        </div>
                    </div>
        </section>

        <script src="../js/like_post.js" type="module"></script>
        <script src="../js/delete_post.js"></script>
        <script src="../js/ban_user.js"></script>
        <script src="../js/scripts.js"></script>
    </main>
</body>