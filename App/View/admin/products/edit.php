<?php $title_for_layout = "Modifier Article ".$name; ?>

<h5>Modifier l'article <?= $name; ?></h5>

<form action="edit?id=<?= $id; ?>" method="post">
    <label>Name </label>
    <input type="text" name="name" value="<?= $name; ?>">
    <div class="alert-error"><?= isset($error['name'])?$error['name']:''; ?></div><br>
    <label>Price </label>
    <input type="number" name="price" value="<?= $price; ?>">
    <div class="alert-error"><?= isset($error['price'])?$error['price']:''; ?></div><br>
    <label>Category </label>
    <select class="browser-default" name="category_id" value="<?= $category_id; ?>">
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>" <?= ($category['id']==$category_id)?'selected':''; ?>><?= $category['name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <div class="alert-error"><?= isset($error['category_id'])?$error['category_id']:''; ?></div><br>
    <div class="btn-submit">
        <button class="btn waves-effect waves-light" type="submit">Valider<i class="material-icons right">check</i></button>
    </div>
</form>