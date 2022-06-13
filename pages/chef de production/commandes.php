<?php
session_start();
if (empty($_SESSION['auth'])) {
    header("location: ../../auth/logout.php");
} else if ($_SESSION['auth']['role'] != 'chef de production') {
    header('location: ../../index.php');
}

require_once "../../Utils/Database.php";

function verify($id)
{
    $commande_products = Database::select("commande_produits", "idCommande", $id);
    $str = "Impossible de valider la commande : ";
    $error = false;
    foreach ($commande_products as $cp) {
        $product = Database::select("product", "code", $cp['idProduit']);
        if ($product[0]['quantite'] < $cp['quantite']) {
            $str .= $cp['idProduit'] . ' est besion de ' . intval($cp['quantite']) - intval($product[0]['quantite']) . ' unité';
            $error = true;
        }
    }
    if ($error)
        return $str;
    else
        return "";
}

if (isset($_POST)) {
    if (isset($_POST['valider'])) {

        $commande_products = Database::select("commande_produits", "idCommande", $_POST['valider']);

        for($i = 0; $i < count($commande_products) ; $i++) {
            $product = Database::select("product", "code", $commande_products[$i]['idProduit']);
    
            Database::update("product", array("code"), array($product[0]['code']), array("quantite"), array((intval($product[0]['quantite']) - intval($commande_products[$i]['quantite']))));
        }
        Database::update("commande", array("idCommande"), array($_POST['valider']), array("done"), array("1"));

    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Responsable de production GPAO</title>
    <link rel="stylesheet" href="../../ressources/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../ressources/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../ressources/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../ressources/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../ressources/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../ressources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../ressources/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../../ressources/images/favicon.png" />
</head>

<body>
    <?php
    if (isset($error)) {
        echo "<script> $('body').each(function() { alert(' . $str. '); }); </script>";
    }
    ?>
    <div class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.php">
                        <img src="../../ressources/images/logo.svg" alt="logo" />
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="index.php">
                        <img src="../../ressources/images/logo-mini.svg" alt="logo" />
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Bonjour, <span class="text-black fw-bold"><?php echo $_SESSION['auth']['nom'] . ' ' . $_SESSION['auth']['prenom']; ?></span></h1>
                        <h3 class="welcome-sub-text">Respondable de production</h3>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="../../ressources/images/face.jpg" alt="Profile image"> </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="../../ressources/images/face.jpg" alt="Profile image">
                                <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['auth']['nom'] . ' ' . $_SESSION['auth']['prenom']; ?></p>
                                <p class="fw-light text-muted mb-0"><?php echo $_SESSION['auth']['email']; ?></p>
                            </div>
                            <a class="dropdown-item" href="../../auth/logout.php"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">


            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                <li class="nav-item nav-category">Services</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                            <i class="menu-icon mdi mdi-cash"></i>
                            <span class="menu-title">Approvisionnement</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="form-elements">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="matieres.php">Ajouter matière première</a></li>
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                            <i class="menu-icon mdi mdi-chart-bar"></i>
                            <span class="menu-title">Fabrication</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="charts">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="produits.php">Lancer la fabrication</a></li>
                                <li class="nav-item"><a class="nav-link" href="fabrications.php">Consulter fabrications</a></li>

                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#elm" aria-expanded="false" aria-controls="elm">
                            <i class="menu-icon mdi mdi-briefcase-download"></i>
                            <span class="menu-title">Commandes</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="elm">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link" href="#">Valider commandes</a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </nav>


            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Commandes</h4>
                                    <p class="card-description">
                                        Liste des commandes</code>
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Produits</th>
                                                    <th>Etat</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $commandes = Database::selectAllByOrder("commande", "done");
                                                foreach ($commandes as $c) {
                                                    $cmd_produits =  Database::select("commande_produits", "idCommande", $c['idCommande']);
                                                    $str = "";
                                                    foreach ($cmd_produits as $cp) {
                                                        $str .= $cp['idProduit'] . ' (' .  $cp['quantite'] . ') ';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $c['idCommande'] ?></td>
                                                        <td><?php echo $c['date'] ?></td>
                                                        <td><?php echo $str ?></td>
                                                        <?php if ($c['done'] == "0") : ?>
                                                            <td><label class="badge badge-danger">En attente</label></td>
                                                            <form method="POST" action="">
                                                                <?php
                                                                if (verify($c['idCommande']) != "") { ?>
                                                                    <td><button type="button" onclick="func<?php echo  $c['idCommande']  ?>()" class="btn btn-warning">Valider</button></td>
                                                                    <script>
                                                                        function func<?php echo $c['idCommande'] ?>() {
                                                                            alert('<?= verify($c['idCommande']) ?>');
                                                                        }
                                                                    </script>
                                                                <?php } else { ?>
                                                                    <td><button type="submit" name="valider" value="<?php echo $c['idCommande'] ?>" class="btn btn-warning">Valider</button></td>
                                                                <?php } ?>
                                                            </form>
                                                        <?php else : ?>
                                                            <td><label class="badge badge-success">Validé</label></td>
                                                            <td></td>
                                                        <?php endif; ?>

                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <script src="../../ressources/vendors/js/vendor.bundle.base.js"></script>
                <script src="../../ressources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
                <script src="../../ressources/js/off-canvas.js"></script>
                <script src="../../ressources/js/hoverable-collapse.js"></script>
                <script src="../../ressources/js/template.js"></script>
                <script src="../../ressources/js/settings.js"></script>
                <script src="../../ressources/js/todolist.js"></script>

</body>

</html>