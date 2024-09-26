<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Inscription</h1>
<form method="post" action="/register">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" required>

    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">S'inscrire</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>