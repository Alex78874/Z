<div class="register">
    <h1 class="register-title">Ajouter un compte administrateur</h1>
    <div id="errorMessages" class="register-error-messages" style="color: red;"></div>
    <form id="registerForm" class="register-form" method="post" action="<?= url(path: 'admin/register') ?>">
        <fieldset class="register-fieldset">
            <label for="username" class="register-label">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" class="register-input" required minlength="3"
                maxlength="20" placeholder="Entrez votre nom d'utilisateur">

            <label for="email" class="register-label">Email :</label>
            <input type="email" id="email" name="email" class="register-input" required
                placeholder="Entrez votre adresse email">

            <label for="password" class="register-label">Mot de passe :</label>
            <input type="password" id="password" name="password" class="register-input" required minlength="8"
                placeholder="Entrez un mot de passe sécurisé">

            <label for="confirm_password" class="register-label">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" class="register-input" required
                placeholder="Confirmez votre mot de passe">
        </fieldset>

        <button type="submit" class="register-button-admin">S'inscrire</button>
    </form>
</div>