<?php $title_for_layout = "Liste des Produits"; ?>

<button class="btn waves-effect waves-light" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvel Article</button>

<h5>Liste des Produits</h5> <br/>

<table class="centered responsive-table">
    <thead>
        <tr>
            <th><h6>Nom</h6></th>
            <th><h6>Prix</h6></th>
            <th><h6>Categorie</h6></th>
            <th><h6>Actions</h6></th>
        </tr>
    </thead>
    <tbody>
    <?php for ($i = $min ; $i <= $max ; $i++): ?>
        <tr>
            <td><?= $products[$i]['name']; ?></td>
            <td><?= $products[$i]['price']; ?></td>
            <td><?= $products[$i]['category']; ?></td>
            <td>
                <a class="btn waves-effect waves-light btn-edit" type="button" href="../../admin/products/edit?id=<?= $products[$i]['id'] ?>"><i class="material-icons left">edit</i>Modifier</a>
                <a class="btn waves-effect waves-light btn-delete" type="button" href="../../admin/products/delete?id=<?= $products[$i]['id'] ?>"><i class="material-icons right">clear</i>Supprimer</a>
            </td>
        </tr>
    <?php endfor; ?>
    </tbody>
</table>

<ul class="pagination">
    <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?($page-1).'&id='.$_GET['id']:($page-1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
    <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?$i.'&id='.$_GET['id']:$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
    <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?($page+1).'&id='.$_GET['id']:($page+1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
</ul>
