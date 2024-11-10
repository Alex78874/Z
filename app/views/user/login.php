<div class="login">
    <h1 class="login-title">Connexion</h1>
    <form method="post" action="<?= url(path: 'login') ?>" class="login-form">
        <label for="email" class="login-label">Email :</label>
        <input type="email" id="email" name="email" required placeholder="Entrez votre adresse email"
            class="login-input">

        <label for="password" class="login-label">Mot de passe :</label>
        <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe"
            class="login-input">

        <button type="submit" class="login-button">Se connecter</button>
    </form>
</div>