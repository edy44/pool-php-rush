<?php $title_for_layout = "Connexion"; ?>

<?= isset($error['data'])?$error['data']:''; ?>
<form action="login" method="post">
    <label>Login </label>
    <input type="text" name="email" value="<?= isset($email)?$email:''; ?>">
    <div class="alert-error"><?= isset($error['login'])?$error['login']:''; ?></div><br>
    <label>Password </label>
    <input type="password" name="password" value="<?= isset($password)?$password:''; ?>">
    <div class="alert-error"><?= isset($error['password'])?$error['password']:''; ?></div><br>
    <label>
        <input type="checkbox" name="remamber_me"/>
        <span>Remember Me</span>
    </label>
    <br><br>
    <button class="btn waves-effect waves-light" type="submit">Se connecter<i class="material-icons left">cloud</i></button>
    <div class="row">
        <label class="col s6 message">Toujours pas de compte le Looser ? Alors cliques sur le bouton... </label>
        <a class="btn waves-effect waves-light btn-new" href='../users/create'><i class="material-icons left">face</i>Créer nouveau profil</a>
    </div>
</form>