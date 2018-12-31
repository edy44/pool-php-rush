<?php $title_for_layout = "Créer Catégorie"; ?>

<form action="create" method="post">
    <label>Name </label>
    <input type="text" name="name" value="<?= $name; ?>">
    <div class="alert-error"><?= isset($error['name'])?$error['name']:''; ?></div><br>
    <label>Parent </label>
    <div class="m4">
        <select class="browser-default" name="parent_id" value="<?= $parent_id; ?>">
            <option value="-1">Aucun Parent</option>
            <?php foreach ($parents as $parent): ?>
                <option value="<?= $parent['id']; ?>"><?= $parent['name']; ?></option>
            <?php endforeach; ?>
        </select><br>
    </div>
    <div class="alert-error"><?= isset($error['parent_id'])?$error['parent_id']:''; ?></div><br>
    <div class="btn-submit">
        <button class="btn waves-effect waves-light" type="submit">Valider<i class="material-icons right">check</i></button>
    </div>
</form>