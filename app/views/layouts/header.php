<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Z'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= url('images/favicon.ico'); ?>">
    <link rel="stylesheet" href="<?= url('css/header.css'); ?>">
    <link rel="stylesheet" href="../css/posts.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <!-- Navigation principale -->
    <header class="nav">
        <nav>
            <div class="nav-links">
                <div class="nav-home">
                    <a href="<?= url('/'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-house" viewBox="0 0 16 16">
                            <path
                                d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                        </svg>
                    </a>
                </div>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <div class="nav-profile">
                        <a href="<?= url('admin/profile'); ?>">
                            <img src="<?= htmlspecialchars($_SESSION['admin']['avatar']); ?>"
                                alt="Avatar de <?= htmlspecialchars($_SESSION['admin']['username']); ?>"
                                style="width:30px; height:30px; border-radius:50%;">
                            <?= htmlspecialchars($_SESSION['admin']['username']); ?>
                        </a>
                        <a href="<?= url('admin/logout'); ?>">Déconnexion</a>
                    </div>

                <?php elseif (isset($_SESSION['user'])): ?>
                    <div class="nav-profile">
                        <a href="<?= url('user/' . $_SESSION['user']['id']); ?>">
                            <img src="<?= htmlspecialchars($_SESSION['user']['avatar']); ?>"
                                alt="Avatar de <?= htmlspecialchars($_SESSION['user']['username']); ?>"
                                style="width:30px; height:30px; border-radius:50%;">
                            <?= htmlspecialchars($_SESSION['user']['username']); ?>
                        </a>
                        <a href="<?= url('logout'); ?>">Déconnexion</a>
                    </div>

                <?php else: ?>
                    <div class="nav-login">
                        <a href="<?= url('login'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM5.5 5.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0z" />
                        </a>
                    </div>


                    <div class="nav-register">
                        <a href="<?= url('register'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-plus" viewBox="0 0 16 16">
                                <path
                                    d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM7 8a1 1 0 0 1 1-1h3V4a1 1 0 0 1 2 0v3h3a1 1 0 0 1 0 2h-3v3a1 1 0 0 1-2 0V9H8a1 1 0 0 1-1-1z" />
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>