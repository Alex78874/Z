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
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Navigation principale -->
    <header class="nav">
        <nav>
            <div class="nav-links">
                <div class="nav-home">
                    <a href="<?= url('/'); ?>">
                        <img src="<?= url('images/logo.svg'); ?>" alt="Logo">
                    </a>
                </div>
                <!-- Barre de recherche au milleu de la navbar -->
                <div class="nav-search">
                    <form action="<?= url('search'); ?>" method="get">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                        <input type="text" name="q" placeholder="Rechercher sur Z"
                            value="<?= isset($q) ? htmlspecialchars($q) : ''; ?>">
                    </form>
                </div>

                <?php if (isset($_SESSION['user'])): ?>

                    <div class="nav-user">
                        <div class="nav-profile">
                            <a href="<?= url('user/' . $_SESSION['user']['id']); ?>">
                                <p>
                                    <?= htmlspecialchars($_SESSION['user']['username']); ?>
                                </p>
                                <img src="<?= htmlspecialchars($_SESSION['user']['avatar']); ?>"
                                    alt="Avatar de <?= htmlspecialchars($_SESSION['user']['username']); ?>">
                            </a>
                        </div>

                        <div class="nav-logout">
                            <a href="<?= url('logout'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                    <path fill-rule="evenodd"
                                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="nav-connection">
                        <div class="nav-login">
                            <a href="<?= url('login'); ?>">
                                Connexion
                            </a>
                        </div>
                        <div class="nav-register">
                            <a href="<?= url('register'); ?>">
                                Inscription
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>