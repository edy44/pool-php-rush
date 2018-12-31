<?php $title_for_layout = "Liste des Catégories"; ?>

<button class="btn waves-effect waves-light" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvelle Catégorie</button>

<h5>Liste des Catégories <?= ($parent['name']!="Aucun")?"Enfants de ".$parent['name']:''; ?></h5><br/>

<table class="centered responsive-table">
    <thead>
    <tr>
        <th><h6>Nom</h6></th>
        <th><h6>Parent</h6></th>
        <th><h6>Enfants</h6></th>
        <th><h6>Actions</h6></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['name']; ?></td>
            <td><a href="../../admin/categories/index<?= $parent['parent_id']; ?>"><span class="new badge" data-badge-caption=""><?= $parent['name']; ?></span></td>
            <td><a href="<?= ($category['count']!=0)?'../../admin/categories/index?parent_id='.$category['id']:''; ?>"><span class="new badge" data-badge-caption=""><?= $category['count']; ?></span></a></td>
            <td>
                <a class="btn waves-effect waves-light btn-edit" type="button" href="../../admin/categories/edit?id=<?= $category['id'] ?>"><i class="material-icons left">edit</i>Modifier</a>
                <a class="btn waves-effect waves-light btn-delete" type="button" href="../../admin/categories/delete?id=<?= $category['id'] ?>"><i class="material-icons right">clear</i>Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<ul class="pagination">
    <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../categories/index<?= $url; ?><?= (isset($_GET['id']))?($page-1).'&id='.$_GET['id']:($page-1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
    <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../categories/index<?= $url; ?><?= (isset($_GET['id']))?$i.'&id='.$_GET['id']:$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
    <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../categories/index<?= $url; ?><?= (isset($_GET['id']))?($page+1).'&id='.$_GET['id']:($page+1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
</ul>
