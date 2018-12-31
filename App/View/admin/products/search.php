<?php $title_for_layout="Résultats pour ".$search; ?>
<?php $search_for_layout=$search; ?>

<h5><?= (!empty($search))?'Résultats de votre recherche : ':'Aucun résultat pour votre recherche :'; ?><?= $search; ?></h5>

<div class="row" data-toggle="tooltip" title="Choisissez un filtre pour la recherche">
    <label class="col s3">Trier la recherche </label><br>
    <select class="browser-default col s3" onchange="document.location = this.options[this.selectedIndex].value;">
        <option value="../products/search?sort=name_asc&page=<?= (isset($_GET['search']))?$page.'&search='.$_GET['search']:$page; ?>" <?= (isset($_GET['sort'])&&($_GET['sort']=="name_asc"))?"selected":""; ?>>Par nom croissant</option>
        <option value="../products/search?sort=name_dsc&page=<?= (isset($_GET['search']))?$page.'&search='.$_GET['search']:$page; ?>" <?= (isset($_GET['sort'])&&($_GET['sort']=="name_dsc"))?"selected":""; ?>>Par nom décroissant</option>
        <option value="../products/search?sort=price_asc&page=<?= (isset($_GET['search']))?$page.'&search='.$_GET['search']:$page; ?>" <?= (isset($_GET['sort'])&&($_GET['sort']=="price_asc"))?"selected":""; ?>>Par prix croissant</option>
        <option value="../products/search?sort=price_dsc&page=<?= (isset($_GET['search']))?$page.'&search='.$_GET['search']:$page; ?>" <?= (isset($_GET['sort'])&&($_GET['sort']=="price_dsc"))?"selected":""; ?>>Par prix décroissant</option>
    </select><br>
</div>

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
    <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?($page-1).'&search='.$_GET['search']:($page-1); ?>"><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
    <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?$i.'&search='.$_GET['search']:$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
    <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?($page+1).'&search='.$_GET['search']:($page+1); ?>"><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
</ul>
