<body>
    <!-- Section Profil Utilisateur -->
    <main>
        <section class="profile">
            <div class="profile-left-container">
                <div class="profile-header">
                    <img src="<?= htmlspecialchars($user['avatar_url'] ?? url('images/avatar.png')); ?>" alt="Avatar de <?= htmlspecialchars($user['username']); ?>">

                    <div class="profile-name">
                        <h1><?= htmlspecialchars($user['username']); ?></h1>
                        <p><?= htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>

                <div class=" profile-details">
                    <h2>Informations supplémentaires</h2>
                    <p>Date d'inscription : <?= htmlspecialchars($user['registration_date']); ?></p>
                    <!-- Ajouter d'autres informations selon vos besoins -->
                </div>

                <div class="profile-actions">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $user['id']): ?>
                        <a href="<?= url('user/edit/' . $user['id']); ?>" class="btn">Modifier Profil</a>
                        <a href="<?= url('logout'); ?>" class="btn">Déconnexion</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-right-container">   
                <img src="<?= htmlspecialchars($user['avatar_url'] ?? url('images/avatar.png')); ?>" alt="Avatar de <?= htmlspecialchars($user['username']); ?>">
                <h2><?= htmlspecialchars($user['username']); ?></h2>

                <hr>

                <div class="profile-stats">
                    <div class="profile-stats-card">
                        <h3><?= htmlspecialchars($postsCount); ?></h3>
                        <p>Posts</p>
                    </div>
                    <div class="profile-stats-card">
                        <h3><?= htmlspecialchars($likesCount); ?></h3>
                        <p>Likes</p>
                    </div>
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
    </main>

</body>