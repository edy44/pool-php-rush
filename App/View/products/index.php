<?php $title_for_layout = "Liste des Produits"; ?>

<h5><?= ($number<0)?'Aucun produit ne correspond à vos critères': ''; ?></h5>

<div class="row">
     <div class="col s12">
         <?php for ($i = $min ; $i <= $max ; $i++): ?>
         <div class="col s12 m3">
             <?= (!empty($products))?'<div class="card">':''?>
                     <!--div class="card-image">
                         <img src="batman/batman-figurine.jpg">
                     </div-->
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
                <li class="category <?= ($_GET['id']==$category['id'])?'active':''; ?>"><a href="../products/index?id=<?= $category['id']; ?>"><?= $category['name']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>
 </div>

 <ul class="pagination">
     <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?($page-1).'&id='.$_GET['id']:($page-1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
     <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?$i.'&id='.$_GET['id']:$i; ?>"><?= $i; ?></a></li>
     <?php endfor; ?>
     <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../products/index?page=<?= (isset($_GET['id']))?($page+1).'&id='.$_GET['id']:($page+1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
 </ul>

