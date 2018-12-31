<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= $title_for_layout; ?> - Glaires & Pets Incorporated INC</title>
        <link href="../Public/css/materialize.min.css" rel="stylesheet">
        <link href="../../Public/css/materialize.min.css" rel="stylesheet">
        <link href="Public/css/materialize.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="Public/css/modal.css" rel="stylesheet">
        <link href="../Public/css/modal.css" rel="stylesheet">
        <link href="../../Public/css/modal.css" rel="stylesheet">
    </head>

    <body>
        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper">
                    <a class="brand-logo">Bienvenue sur le Site !</a>
                </div>
            </nav>
        </div>

        <div class="container">
            <main>
                <?= $content_for_layout; ?>
            </main>
        </div>

        <footer class="page-footer">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">Plus d'informations</h5>
                        <p class="grey-text text-lighten-4">Pour toutes réclamations, veuillez réessayer plus tard.
                            <br> Bien respectueusement</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="white-text">Contact</h5>
                        <ul>
                            <li>222 rue du Pif</li>
                            <li>06 06 06 06 06</li>
                            <li>Bref</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    © 2018 Copyright Text
                </div>
            </div>
        </footer>
        <script src="../Public/js/materialize.min.js"></script>
    </body>
</html>