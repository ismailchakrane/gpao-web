<?php
session_start();
if (empty($_SESSION['auth'])) {
  header("location: ../../auth/logout.php");
} else if ($_SESSION['auth']['role'] != 'ingenieur') {
  header('location: ../../index.php');
} else if (empty($_SESSION['id'])) {
  header('location: consulterProduits.php');
}

require_once "../../Utils/Database.php";
$idProduct = $_SESSION['id'];

if (!empty($_POST)) {

  if ($_POST['nom']) {
    $idP = $_POST['submitBtn'];
    Database::update("product", array("code"), array($idP), array("name"), array($_POST['nom']));
    Database::update("product", array("code"), array($idP), array("dateE"), array(date("Y-m-d")));
  }

  if ($_POST['description']) {
    Database::update("product", array("code"), array($idP), array("description"), array($_POST['description']));
    Database::update("product", array("code"), array($idP), array("dateE"), array(date("Y-m-d")));
  }

  $mts = Database::selectAll("materials");
  $keys = array("quantity");

  foreach ($mts as $m) {
    if (!empty($_POST[$m['code']])) {
      $values = array($_POST[$m['code']]);
      Database::update("product_materials", array("id_product", "id_material"), array($idP, $m['code']), $keys, $values);
      Database::update("product", array("code"), array($idP), array("dateE"), array(date("Y-m-d")));
    }
  };

  unset($_SESSION['id']);
  header("location: consulterProduits.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ingenieur GPAO</title>
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
            <h3 class="welcome-sub-text">Ingénieur</h3>
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


          <li class="nav-item nav-category">Conception</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="menu-icon mdi mdi-basket"></i>
              <span class="menu-title">Produits</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="consulterProduits.php">Consulter les produits</a></li>
                <li class="nav-item"><a class="nav-link" href="ajouterProduit.php">Ajouter un produit</a></li>
              </ul>
            </div>
          </li>


          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="menu-icon mdi mdi-replay"></i>
              <span class="menu-title">Matière première</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="consulterMatieres.php">Consulter les matières</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Ajouter une matière</a></li>
              </ul>
            </div>
          </li>

        </ul>
      </nav>


      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Modifier produit</h4>
                  <form class="form-sample" action="" method="POST">
                    <?php
                    $produit = Database::select("product", "code", $idProduct);
                    $materesPremiers = Database::select("product_materials", "id_product", $idProduct);

                    ?>
                    <p class="card-description">
                      Produit : <?php echo $produit[0]['code'] ?>
                    </p>
                    <?php if (!empty($_POST)) { ?>
                      <?php if (isset($errors)) : ?>
                        <div class="form-group">
                          <div class="alert alert-danger" role="alert">
                            <?php echo $errors ?>
                          </div>
                        </div>
                      <?php else : ?>
                        <div class="form-group">
                          <div class="alert alert-success" role="alert">
                            <?php echo "Produit modifié" ?>
                          </div>
                        </div>
                      <?php endif; ?>
                    <?php } ?>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Nom</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="nom" value="<?php echo $produit[0]['name'] ?>" name="nom" />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Description</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="description" value="<?php echo $produit[0]['description'] ?>" name="description" />
                          </div>
                        </div>

                      </div>

                      <div class="row">


                        <div class="col-md-6">
                          <div class="form-group row">
                            <h5 class="col-sm-6 col-form-label">Matière premier</h5>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <h5 class="col-sm-6 col-form-label">Qantité</h5>
                          </div>
                        </div>
                      </div>

                      <?php foreach ($materesPremiers as $m) { ?>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <?php $nameMat =  Database::select("materials", "code", $m['id_material']); ?>
                              <h2 class="col-sm-6 col-form-label"><?php echo $nameMat[0]['name']; ?></h2>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <input type="text" class="form-control" placeholder="description" value="<?php echo $m['quantity'] ?>" name="<?php echo $nameMat[0]['code']; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>

                      <?php } ?>




                      <div style="padding : 0 100px" class="row">
                      <div  class="col-md-4">
                        <div class="form-group row">
                          <div class="form-check">
                            <button type="submit" value="<?php echo $idProduct ?>" name="submitBtn" class="btn btn-primary btn-icon-text col-sm-12">
                              Modifier le produit
                            </button>
                          </div>
                        </div>
                      </div>
                      </div>


                  </form>
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>

</html>