<h1>Ajouter compte Admin</h1>
<div id="errorMessages" style="color: red;"></div>
<form id="registerForm" method="post" action="<?= url(path: 'admin/register') ?>">
    <fieldset>
        <label for="username">Nom de l'admin :</label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="20" placeholder="Entrez votre nom d'utilisateur">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required placeholder="Entrez votre adresse email">

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required minlength="8" placeholder="Entrez un mot de passe sécurisé">

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirmez votre mot de passe">
    </fieldset>

    <button type="submit">Ajouter</button>
</form>