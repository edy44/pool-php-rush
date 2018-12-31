<?php $title_for_layout = "Fiche produit ".$name; ?>

<h3> <?= $name?> </h3>

<div class="share col-12" data-toggle="tooltip" title="Partager la Page sur les Réseaux Sociaux">
    <label class="icon_share"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></label>
    <button type="button" class="btn share_btn share_twitter" data-toggle="tooltip" title="Partager la Page sur Twitter">
        <img class="share_img" alt="Partage Twitter" src="">
    </button>
    <button type="button" class="btn share_btn share_facebook" data-toggle="tooltip" title="Partager la Page sur Facebook">
        <img class="share_img" alt="Partage Facebook" src="">
    </button>
    <button type="button" class="btn share_btn share_gplus" data-toggle="tooltip" title="Partager la Page sur Google+">
        <img class="share_img" alt="Partage Google+" src="">
    </button>
    <button type="button" class="btn share_btn share_linkedin" data-toggle="tooltip" title="Partager la Page sur Linkedin">
        <img class="share_img" alt="Partage Linkedin" src="">
     </button>
    <a href="mailto:?subject='.$title_for_layout.'&body=%0A%0A'.$title_for-layout.'%0A'">
        <button type="button" class="btn share_btn share_mail" data-toggle="tooltip" title="Partager la Page par Mail">
            <img class="share_img" alt="Partage Mail" src="">
        </button>
    </a>
</div>

<h4>Prix TTC : <?php echo $price ?> EUR </h4>
<h4>n° de catégorie : <?php echo $category ?> </h4>
<h4>n° d'identifiant : <?php echo $id ?>  </h4>

<script src="../Public/js/share.js"></script>
