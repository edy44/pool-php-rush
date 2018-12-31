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

<div class="row">
    <div class="col s12">
        <?php for ($i = $min ; $i <= $max ; $i++): ?>
            <div class="col s12 m3">
                <?= (!empty($products))?'<div class="card">':''?>
                <?= (!empty($products))?'<div class="card-content">':''?>
                <p><?= (!empty($products))?'Titre : ':''?><?= (!empty($products))?$products[$i]['name']:''; ?></p>
                <p><?= (!empty($products))?'Catégorie : ':''?><?= (!empty($products))?$products[$i]['category']:''; ?></p>
                <p><?= (!empty($products))?'Prix : ':''?><?= (!empty($products))?$products[$i]['price']:''; ?><?= (!empty($products))?' €':''?></p>
                <?= (!empty($products))?'</div>':''?>
                <?= (!empty($products))?'<div class="card-action">':''?>
                <?= (!empty($products))?'<a href="../products/view?id='.$products[$i]['id'].'">Voir Détails</a>':''?>
                <?= (!empty($products))?'</div>':''?>
                <?= (!empty($products))?'</div>':''?>
            </div>
        <?php endfor; ?>
    </div>
    <aside>
        <h6><i class="material-icons">assignment</i>CATEGORIES</h6>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li class="category <?= ((isset($_GET['id']))&&($_GET['id']==$category['id']))?'active':''; ?>"><a href="../products/index?id=<?= $category['id']; ?>"><?= $category['name']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>
</div>

<ul class="pagination">
    <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?($page-1).'&search='.$_GET['search']:($page-1); ?>"><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
    <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?$i.'&search='.$_GET['search']:$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
    <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../products/index?sort=<?= isset($_GET['sort'])?$_GET['sort']:'name_asc'; ?>&page=<?= (isset($_GET['search']))?($page+1).'&search='.$_GET['search']:($page+1); ?>"><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
</ul>
