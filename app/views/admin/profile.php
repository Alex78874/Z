<body>
    <!-- Section Profil Admin -->
    <main>
        <section class="profile">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($admin['avatar_url'] ?? url("images/avatars/avatar_1.webp")); ?>"
                    alt="Avatar de <?= htmlspecialchars($admin['username']); ?>">
                <h1><?= htmlspecialchars($admin['username']); ?></h1>
                <p>Email : <?= htmlspecialchars($admin['email']); ?></p>
            </div>

            <div class="profile-details">
                <h2>Informations supplémentaires</h2>
                <p>Date d'inscription : <?= htmlspecialchars($admin['registration_date']); ?></p>
                <!-- Ajouter d'autres informations selon vos besoins -->
            </div>

            <div class="profile-actions">
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin']['id'] === $admin['id']): ?>
                    <a href="<?= url('admin/edit/' . $admin['id']); ?>" class="btn">Modifier Profil</a>
                    <a href="<?= url('logout'); ?>" class="btn">Déconnexion</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>