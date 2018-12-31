<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Administration - <?= $title_for_layout; ?></title>
        <link href="../../Public/css/materialize.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../../Public/css/admin.css" rel="stylesheet">
    </head>

    <body >

    <div class="navbar-fixed">
        <nav class="nav-extended">
            <div class="nav-wrapper" >
            <a href="../users/index" class="brand-logo"><i class="material-icons left">https</i>ADMINISTRATION</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../../users/logout"><i class="material-icons left">settings_power</i>Déconnection</a></li>
                <li><a href="../../products/index"><i class="material-icons left">home</i>Accueil Site</a></li>
            </ul>

            </div>
            <div class="nav-content">
                <ul class="tabs tabs-transparent">
                    <li class="tab"><a href="../users/index"><i class="material-icons left">group</i>UTILISATEURS</a></li>
                    <li class="tab"><a href="../products/index"><i class="material-icons left">local_offer</i>ARTICLES</a></li>
                    <li class="tab"><a href="../categories/index"><i class="material-icons left">assignment</i>CATEGORIES</a></li>
                    <li>
                        <form action="../products/search" method="get">
                            <div class="input-field">
                                <input class="search" name="search" type="search" required value="<?= (!empty($search_for_layout))?$search_for_layout:''; ?>" placeholder="Rechercher Produits...">
                                <label class="label-icon" for="search"><button class="btn-search" type="submit"><i class="material-icons left">search </i></button></label>
                                <i class="material-icons">close</i>
                            </div>
                        </form>
                    <li>
                </ul>
            </div>
        </nav>
    </div>
        <div class="container">
            <main>
                <div class="alert-flash"><?= $this->getFlash() ?></div>
                <?= $content_for_layout; ?>
            </main>
        </div>

        <footer class="page-footer" >
            <div class="container" >
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">Infos supplémentaires</h5>
                        <p class="grey-text text-lighten-4">Pour toutes réclamations, veuillez réessayer plus tard.
                            <br> Bien respectueusement</p>
                    </div>
                <div class="col l4 offset-l2 s12" >
                    <h5 class="white-text">Contact</h5>
                    <ul>
                        <li>222 rue du Pif</li>
                        <li>06 06 06 06 06</li>
                    </ul>
                </div>
            </div>
            </div>
        <div class="footer-copyright">
            <div class="container">
                © 2018 Copyright Text
                <a class="grey-text text-lighten-4 right" href="#!"> </a>
            </div>
        </div>
        </footer>
        <script src="../../Public/js/materialize.min.js"></script>
    </body>
</html>
