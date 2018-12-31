<?php $title_for_layout = "Modifier Utilisateur ".$username; ?>

<?= isset($success)?$success:''; ?><br>
<form action="edit?id=<?= $id; ?>" method="post">
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
    <input type="text" name="password_confirmation" value="<?= $password_confirmation; ?>"><br>
    <label>Droits d'utilisateur </label>
    <select class="browser-default" name="admin" value="<?= $admin; ?>">
        <option value="0" <?= ($admin == 0)?'selected':''; ?>>Utilisateur Normal</option>
        <option value="1" <?= ($admin == 1)?'selected':''; ?>>Administrateur</option>
    </select><br>
    <div class="btn-submit">
        <button class="btn waves-effect waves-light" type="submit">Valider<i class="material-icons right">check</i></button>
    </div>
</form>