<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Mon Application'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens vers les feuilles de style CSS -->
    <link rel="stylesheet" href="<?php echo url('css/header.css'); ?>">
</head>

<body>
    <!-- Navigation principale -->
    <header>
        <nav>
            <ul>
                <li><a href="<?php echo url(); ?>">Accueil</a></li>
                <li><a href="<?php echo url('tweet/create'); ?>">Nouveau Post</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li>
                        <a href="<?php echo url('user/' . $_SESSION['user']['id']); ?>">
                            <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>"
                                alt="Avatar de <?php echo htmlspecialchars($_SESSION['user']['username']); ?>"
                                style="width:30px; height:30px; border-radius:50%;">
                            <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                        </a>
                    </li>
                    <li><a href="<?php echo url('logout'); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url('login'); ?>">Connexion</a></li>
                    <li><a href="<?php echo url('register'); ?>">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>