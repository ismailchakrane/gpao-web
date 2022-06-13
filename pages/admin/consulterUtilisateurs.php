<?php
  session_start();
  if(empty($_SESSION['auth']) ){
    header("location: ../../auth/logout.php");
  }
  else if($_SESSION['auth']['role'] != 'admin'){
    header('location: ../../index.php');
  }

require_once '../../Utils/Database.php';

if (!empty($_POST)) {
    if(isset($_POST['supprimer'])){
        Database::delete("employe","id",$_POST['supprimer']);
        header("location: consulterUtilisateurs.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin GPAO</title>
    <link rel="stylesheet" href="../../ressources/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../ressources/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../ressources/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../ressources/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../ressources/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../ressources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../ressources/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../../ressources/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
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
                    <a class="navbar-brand brand-logo" href="../../../index.php">
                        <img src="../../ressources/images/logo.svg" alt="logo" />
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="../../../index.php">
                        <img src="../../ressources/images/logo-mini.svg" alt="logo" />
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Bonjour, <span class="text-black fw-bold"><?php echo $_SESSION['auth']['nom'] . ' ' . $_SESSION['auth']['prenom']; ?></span></h1>
                        <h3 class="welcome-sub-text">Admin</h3>
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
                                <p class="fw-light text-muted mb-0"><?php echo $_SESSION['auth']['email'] ;?></p>
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
                    
                    <li class="nav-item nav-category">Administration</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="menu-icon mdi mdi-account"></i>
                            <span class="menu-title">Utilisateurs</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="ajouterUtilisateur.php">Nouveau Utilisateur</a></li>
                                <li class="nav-item"> <a class="nav-link" href="#">Consulter Utilisateurs</a></li>

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
                  <h4 class="card-title">Produits</h4>
                  <p class="card-description">
                    Liste des produits</code>
                  </p>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Email</th>
                          <th>Nom</th>
                          <th>Prenom</th>
                          <th>Date embauche</th>
                          <th>Sexe</th>
                          <th>Role</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        $users = Database::selectAll("employe");
                        foreach ($users as $u) {

                            if($u['role'] != "admin") {

                        ?>

                          <tr>
                            <td><?php echo $u['email'] ?></td>
                            <td><?php echo $u['nom'] ?></td>
                            <td><?php echo $u['prenom'] ?></td>
                            <td><?php echo $u['date_embauche'] ?></td>
                            <td><?php echo $u['sexe'] ?></td>
                            <td><?php echo $u['role'] ?></td>

                            <form method="POST" action="">
                              <td><button type="button" name="supprimer" value="<?php echo $u['id']?>" class="btn btn-danger">Supprimer</button></td>
                            </form>
                          </tr>
                        <?php }} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>







          </div>
        </div>
      </div>
        </div>
    </div>

    <script src="../../ressources/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../ressources/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="../../ressources/vendors/select2/select2.min.js"></script>
    <script src="../../ressources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../../ressources/js/off-canvas.js"></script>
    <script src="../../ressources/js/hoverable-collapse.js"></script>
    <script src="../../ressources/js/template.js"></script>
    <script src="../../ressources/js/settings.js"></script>
    <script src="../../ressources/js/todolist.js"></script>
    <script src="../../ressources/js/file-upload.js"></script>
    <script src="../../ressources/js/typeahead.js"></script>
    <script src="../../ressources/js/select2.js"></script>

</body>

</html>