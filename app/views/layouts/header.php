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
            <ul>
                <li><a href="<?= url('/'); ?>">Accueil</a></li>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li>
                        <a href="<?= url('admin/profile'); ?>">
                            <img src="<?= htmlspecialchars($_SESSION['admin']['avatar']); ?>"
                                alt="Avatar de <?= htmlspecialchars($_SESSION['admin']['username']); ?>"
                                style="width:30px; height:30px; border-radius:50%;">
                            <?= htmlspecialchars($_SESSION['admin']['username']); ?>
                        </a>
                    </li>
                    <li><a href="<?= url('admin/logout'); ?>">Déconnexion</a></li>
                <?php elseif (isset($_SESSION['user'])): ?>
                    <li>
                        <a href="<?= url('user/' . $_SESSION['user']['id']); ?>">
                            <img src="<?= htmlspecialchars($_SESSION['user']['avatar']); ?>"
                                alt=""
                                style="width:30px; height:30px; border-radius:50%;">
                                Profile

                        </a>
                    </li>
                    <li><a href="<?= url('logout'); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= url('login'); ?>">Connexion</a></li>
                    <li><a href="<?= url('register'); ?>">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>