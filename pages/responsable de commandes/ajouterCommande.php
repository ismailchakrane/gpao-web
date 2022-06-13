<?php
session_start();
if (empty($_SESSION['auth'])) {
  header("location: ../../auth/logout.php");
} else if ($_SESSION['auth']['role'] != 'responsable de commandes') {
  header('location: ../../index.php');
}

require_once "../../Utils/Database.php";

if (!empty($_POST)) {


  if (isset($_POST['ajouter'])) {

    $pds = Database::selectAll("product");
    $products = array();
    $quantites = array();

    foreach ($pds as $p) {

      if (isset($_POST[$p['code']]) && $_POST[$p['code'] . 'Q'] != "") {
        array_push($products, $p['code']);
        array_push($quantites, $_POST[$p['code'] . 'Q']);
      }
    }

    if (!empty($products)) {
      $idCmd = Database::insert("commande", array("date", "done"), array(date("Y-m-d"), 0));
      for ($i = 0; $i < count($products); $i++) {
        Database::insert("commande_produits", array("idCommande", "idProduit", "quantite"), array($idCmd, $products[$i], $quantites[$i]));
      }
      $succes = "Commande ajouté";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Responsable de commandes GPAO</title>
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
            <h3 class="welcome-sub-text">Respondable de commandes</h3>
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

          <li class="nav-item nav-category">Commandes</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="menu-icon mdi mdi-briefcase-upload"></i>
              <span class="menu-title">Vente</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">Ajouter commande</a></li>
              </ul>
            </div>
          </li>



        </ul>
      </nav>


      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">



            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ajouter une commande</h4>
                  <p class="card-description">Ajouter une nouvelle commande</p>
                  <?php if (isset($succes)) : ?>
                    <div class="form-group">
                      <div class="alert alert-success" role="alert">
                        <?php echo $succes ?>
                      </div>
                    </div>
                  <?php endif; ?>
                  <form method="POST" action="">

                    <?php
                    $products = Database::selectAllByOrder("product");

                    foreach ($products as $p) {

                    ?>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" name="<?php echo $p['code'] ?>" class="form-check-input">
                                <?php echo $p['code'] ?>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-group row">
                              <div class="col-sm-9">
                                <input type="number" class="form-control" name="<?php echo $p['code'] ?>Q" value="<?php echo $p['code'] ?>" id="exampleInputUsername2" placeholder="Quantité">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <button type="submit" name="ajouter" class="btn btn-primary me-2">Ajouter</button>
                  </form>
                </div>


              </div>
            </div>





            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Produits</h4>
                  <p class="card-description">
                    Liste des produits
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>

                        <tr>
                          <th>Code</th>
                          <th>Nom</th>
                          <th>Description</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $products = Database::selectAllByOrder("product");

                        foreach ($products as $p) {  ?>
                          <tr>
                            <td><?php echo $p['code'] ?></td>
                            <td><?php echo $p['name'] ?></td>
                            <td><?php echo $p['description'] ?></td>
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