<?php
session_start();
if (empty($_SESSION['auth']) ||  !isset($_SESSION['auth'])  || $_SESSION['auth']['role'] != 'ingenieur') {
  header("location: ../../auth/logout.php");
}

require_once "../../Utils/Database.php";

if (!empty($_POST)) {
  $product = Database::select("product", "code", $_POST['code']);
  if ($product) {
    $errors = "Produit déja existant";
  } else {
    $keys = array("code", "name", "description", "creator", "dateE","state");
    $values = array(str_replace(' ', '', $_POST["code"]), $_POST["nom"], $_POST["description"], $_SESSION['auth']['id'], date("Y-m-d"), "non bloque");
    Database::insert("product", $keys, $values);

    $keys = array("id_product", "id_material", "quantity");
    $mts = Database::selectAll("materials");
    foreach ($mts as $m) {
      if (!empty($_POST[$m['code']])) {
        $qt = "q" .  $m['code']; // quantitté de chaque matiere
        $values = array(str_replace(' ', '', $_POST["code"]), $m['id'], $_POST[$qt]);
        Database::insert("product_materials", $keys, $values);
      }
    };

    $keys = array("id_produit", "quantite");
    $values = array(str_replace(' ', '', $_POST["code"]), "0");
    Database::insert("stock_produit", $keys, $values);

  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ingenieur Admin</title>
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
              <img class="img-xs rounded-circle" src="../../images/faces/face8.jpg" alt="Profile image"> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['auth']['nom'] . ' ' . $_SESSION['auth']['prenom']; ?></p>
                <p class="fw-light text-muted mb-0"><?php echo $_SESSION['auth']['email']; ?></p>
              </div>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
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
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>


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
                <li class="nav-item"><a class="nav-link" href="#">Ajouter un produit</a></li>
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
                <li class="nav-item"><a class="nav-link" href="ajouterMatiere.php">Ajouter une matière</a></li>
              </ul>
            </div>
          </li>


          <li class="nav-item nav-category">help</li>
          <li class="nav-item">
            <a class="nav-link" href="http://bootstrapdash.com/demo/star-admin2-free/docs/documentation.html">
              <i class="menu-icon mdi mdi-file-document"></i>
              <span class="menu-title">Documentation</span>
            </a>
          </li>
        </ul>
      </nav>


      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ajouter produit</h4>
                  <form class="form-sample" action="" method="POST">
                    <p class="card-description">
                      Ajouter un nouveau produit
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
                            <?php echo "Produit ajouté" ?>
                          </div>
                        </div>
                      <?php endif; ?>
                    <?php } ?>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Code</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="code" name="code" required />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Matière première</label>
                          <div class="col-sm-9">
                            <select class="form-control" id="matiere">
                              <?php
                              $materials = Database::selectAll("materials");
                              foreach ($materials as $m) { ?>
                                <option value="<?php echo $m['code'] ?>"><?php echo $m['name'] ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Nom</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="nom" name="nom" required />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Qantité</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="quantité" id="quantite" required />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Description</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="description" name="description" required />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <ol id="appendUlItems">
                          </ol>
                          <div class="col-sm-4">
                          </div>
                          <div class="col-sm-5">
                            <div class="form-check">
                              <button type="button" id="ajoutMatiere" class="btn btn-primary btn-icon-text">
                                Ajouter matière première
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <button style="transform: translateX(310%)" type="submit" class="btn btn-primary btn-icon-text">
                      Ajouter le produit
                    </button>
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

        <script>
          $(document).ready(function() {
            $("#ajoutMatiere").click(function() {
              matiere = $('#matiere').val();
              quantité = $('#quantite').val();

              $("#appendUlItems").append('<li> ' + matiere + "  (" + quantité + ') </li>');
              $("#appendUlItems").append('<input type="text" name="' + matiere + '" value="' + matiere + '" hidden/>');
              $("#appendUlItems").append('<input type="text" name="q' + matiere + '" value="' + quantité + '" hidden/>');
            });
          });
        </script>
</body>

</html>