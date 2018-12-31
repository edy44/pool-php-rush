<?php $title_for_layout = "Liste des Utilisateurs"; ?>

<button class="waves-effect waves-light btn" onclick="location.href='create'" type="button">
    <i class="material-icons left">add_box</i>Nouvel Utilisateur</button>

<h5>Liste des Utilisateurs</h5><br/>

<table class="centered responsive-table highlight">
    <thead>
        <tr>
            <th><h6>Nom</h6></th>
            <th><h6>Email</h6></th>
            <th><h6>Administrateur</h6></th>
            <th><h6>Actions</h6></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $user): ?>
        <tr>
            <td><?= $user['username']; ?></td>
            <td><?= $user['email']; ?></td>
            <td><?= ($user['admin'] == 1)?'Oui':'Non'; ?></td>
            <td>
                <a class="btn waves-effect waves-light btn-edit" type="button" href="../../admin/users/edit?id=<?= $user['id'] ?>"><i class="material-icons left">edit</i>Modifier</a>
                <a class="btn waves-effect waves-light btn-delete" type="button" href="../../admin/users/delete?id=<?= $user['id'] ?>"><i class="material-icons right">clear</i>Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<ul class="pagination">
    <li class="<?= ($page==1)?'disabled':'waves-effect'; ?>"><a href="../users/index?page=<?= (isset($_GET['id']))?($page-1).'&id='.$_GET['id']:($page-1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_left</i>':''; ?></a></li>
    <?php for ($i = 1 ; $i <= $pages ; $i++): ?>
        <li class="<?= ($i==$page)?'active':'waves-effect'; ?>"><a href="../users/index?page=<?= (isset($_GET['id']))?$i.'&id='.$_GET['id']:$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
    <li class="<?= ($page==$pages)?'disabled':'waves-effect'; ?>"><a href="../users/index?page=<?= (isset($_GET['id']))?($page+1).'&id='.$_GET['id']:($page+1); ?>""><?= ($number>=0)?'<i class="material-icons">chevron_right</i>':''; ?></a></li>
</ul>