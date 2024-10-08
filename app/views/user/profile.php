<body>
    <!-- Section Profil Utilisateur -->
    <main>
        <section class="profile">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($user['avatar_url']); ?>"
                    alt="Avatar de <?= htmlspecialchars($user['username']); ?>"
                    style="width: 100px; height: 100px; border-radius: 50%;">
                <h1><?= htmlspecialchars($user['username']); ?></h1>
                <p>Email : <?= htmlspecialchars($user['email']); ?></p>
            </div>

            <div class="profile-details">
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
        </section>
    </main>

</body>