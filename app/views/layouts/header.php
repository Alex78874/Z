<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Mon Application'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens vers les feuilles de style CSS -->
    <link rel="stylesheet" href="<?= url('css/header.css'); ?>">
</head>

<body>
    <!-- Navigation principale -->
    <header>
        <nav>
            <ul>
                <li><a href="<?= url(); ?>">Accueil</a></li>
                <li><a href="<?= url('tweet/create'); ?>">Nouveau Post</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li>
                        <a href="<?= url('user/' . $_SESSION['user']['id']); ?>">
                            <img src="<?= htmlspecialchars($_SESSION['user']['avatar']); ?>"
                                alt="Avatar de <?= htmlspecialchars($_SESSION['user']['username']); ?>"
                                style="width:30px; height:30px; border-radius:50%;">
                            <?= htmlspecialchars($_SESSION['user']['username']); ?>
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