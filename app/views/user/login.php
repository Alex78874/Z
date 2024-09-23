<!-- app/views/user/login.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Connexion</h1>
<form method="post" action="/login">
    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
