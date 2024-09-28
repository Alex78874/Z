<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Profil de <?php echo htmlspecialchars($user['username']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo url('css/styles.css'); ?>">
</head>

<body>
    <!-- Section Profil Utilisateur -->
    <main>
        <section class="profile">
            <div class="profile-header">
                <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>"
                    alt="Avatar de <?php echo htmlspecialchars($user['username']); ?>"
                    style="width: 100px; height: 100px; border-radius: 50%;">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <div class="profile-details">
                <h2>Informations supplémentaires</h2>
                <p>Date d'inscription : <?php echo htmlspecialchars($user['registration_date']); ?></p>
                <!-- Ajouter d'autres informations selon vos besoins -->
            </div>

            <div class="profile-actions">
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $user['id']): ?>
                    <a href="<?php echo url('user/edit/' . $user['id']); ?>" class="btn">Modifier Profil</a>
                    <a href="<?php echo url('logout'); ?>" class="btn">Déconnexion</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Liens vers des fichiers JavaScript -->
    <script src="<?php echo url('js/main.js'); ?>"></script>
</body>

</html>