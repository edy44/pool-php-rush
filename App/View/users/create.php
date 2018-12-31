<?php $title_for_layout = "Inscription"; ?>

<form action="create" method="post">
    <label>Nom </label>
    <input type="text" name="username" value="<?= $username; ?>">
    <div class="alert-error"><?= isset($error['username'])?$error['username']:''; ?></div><br>
    <label>Email </label>
    <input type="email" name="email" value="<?= $email; ?>">
    <div class="alert-error"><?= isset($error['email'])?$error['email']:''; ?></div><br>
    <label>Password </label>
    <input type="password" name="password" value="<?= $password; ?>">
    <div class="alert-error"><?= isset($error['password'])?$error['password']:''; ?></div><br>
    <label>Password </label>
    <input type="password" name="password_confirmation" value="<?= $password_confirmation; ?>"><br>
    <div class="btn-submit">
        <button class="btn waves-effect waves-light" type="submit">Valider<i class="material-icons right">check</i></button>
    </div>
</form>