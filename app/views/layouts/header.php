<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Mon Application'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens vers les feuilles de style CSS -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/styles.css">
</head>
<body>
<!-- Navigation principale -->
<header>
    <nav>
        <ul>
            <li><a href="<?php echo $basePath; ?>/">Accueil</a></li>
            <li><a href="<?php echo $basePath; ?>/tweet/create">Nouveau Tweet</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="<?php echo $basePath; ?>/user/<?php echo $_SESSION['user']['id']; ?>">Profil</a></li>
                <li><a href="<?php echo $basePath; ?>/logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="<?php echo $basePath; ?>/login">Connexion</a></li>
                <li><a href="<?php echo $basePath; ?>/register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Contenu principal -->
<main>
