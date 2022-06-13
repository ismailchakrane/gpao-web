<?php
session_start();
if (empty($_SESSION['auth'])) {
    header("location: ../../auth/logout.php");
} else if ($_SESSION['auth']['role'] != 'chef de production') {
    header('location: ../../index.php');
}

require_once "../../Utils/Database.php";

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
                                <li class="nav-item"><a class="nav-link" href="#">Consulter fabrications</a></li>

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
                                <li class="nav-item"><a class="nav-link" href="commandes.php">Valider commandes</a></li>
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
                                    <h4 class="card-title">Fabrications</h4>
                                    <p class="card-description">
                                        Liste des fabrications</code>
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Code du produit
                                                    </th>
                                                    
                                                    <th>
                                                        Date de début
                                                    </th>
                                                    <th>
                                                        Date de fin
                                                    </th>
                                                    <th>
                                                        Quantité
                                                    </th>
                                                    <th>
                                                        Etat
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $fabrications = Database::selectAllByOrder("fabrication", "dateFin");
                                                foreach ($fabrications as $f) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $f['id_product'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $f['dateDebut'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $f['dateFin'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $f['quantity'] ?>
                                                        </td>
                                                        <?php
                                                        $max = strtotime($f['dateFin']) - strtotime($f['dateDebut']);
                                                        $current = strtotime(date("Y-m-d")) - strtotime($f['dateDebut']);
                                                        if (($current * 100) / $max >= 100) {

                                                        ?>
                                                            <td><label class="badge badge-success">Terminé</label></td>

                                                        <?php } else { ?>
                                                            <td>

                                                                <div class="progress">
                                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo ($current * 100) / $max ?>%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        <?php } ?>
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
                <script src="../../ressources/js/off-canvas.js"></script>
                <script src="../../ressources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

</body>


</html>