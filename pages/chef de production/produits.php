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
  $product_materials = Database::select("product_materials", "id_product", $id);
  $str = "Impossible de lancer la fabrication : ";
  $error = false;
  foreach ($product_materials as $pm) {
    $material = Database::select("materials", "code", $pm['id_material']);

    if ($material[0]['quantite'] < $pm['quantity']) {
      $str .= $pm['id_material'] . ' est besion de ' . intval($pm['quantity']) - intval($material[0]['quantite']) . ' unité';
      $error = true;
    }
  }
  if ($error)
    return $str;
  else
    return "";
}

function verify2($id,$qt)
{
  $product_materials = Database::select("product_materials", "id_product", $id);
  $strerr = "La production ne peut pas etre lancer la fabrication : <br/>";
  $error = false;
  foreach ($product_materials as $pm) {
    $material = Database::select("materials", "code", $pm['id_material']);

    if ($material[0]['quantite'] < (intval($qt) * intval($pm['quantity']))) {
      $strerr .= $pm['id_material'] . ' est besion de ' . (intval($qt) * intval($pm['quantity']))- intval($material[0]['quantite']) . ' unité <br/>';
      $error = true;
    }
  }
  if ($error)
    return $strerr;
  else
    return "";
}

if (!empty($_POST)) {
  if (isset($_POST['lancer'])) {

    
    if(verify2($_POST['lancer'],$_POST['Q']) == ""){

      $product_materials = Database::select("product_materials", "id_product", $_POST['lancer']);
      for ($i = 0; $i < count($product_materials); $i++) {

        $material = Database::select("materials", "code", $product_materials[$i]['id_material']);
  
        Database::update("materials", array("code"), array($material[0]['code']), array("quantite"), 
        array( 
          (
            intval($material[0]['quantite']) - ( intval($product_materials[$i]['quantity']) * intval($_POST['Q'])) 
          )
        )
      );
      }
      Database::insert("fabrication", array("id_product","dateDebut","dateFin","quantity"), array($_POST['lancer'],$_POST['dateD'],$_POST['dateF'],$_POST['Q']));
      Database::update("product",array("code"),array($_POST['lancer']),array("quantite"),array(intval($_POST['Q']) + intval($_POST['PQ'])));

    }
    else
      $str_err = verify2($_POST['lancer'],$_POST['Q']);

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Chef de production GPAO</title>
  <link rel="stylesheet" href="../../ressources/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../ressources/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../ressources/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../ressources/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../ressources/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../../ressources/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../ressources/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../ressources/images/favicon.png" />



  <script src="assets/JS/nav.js" defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            <h3 class="welcome-sub-text">chef de production</h3>
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
                <li class="nav-item"><a class="nav-link" href="#">Lancer la fabrication</a></li>
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
                  <?php if(isset($str_err)): ?>
                    <div class="form-group">
                      <div class="alert alert-danger" role="alert">
                        <?php echo $str_err ?>
                      </div>
                    </div>
                  <?php endif; ?>
                  <h4 class="card-title">Produits</h4>
                  <p class="card-description">
                    Liste des produits</code>
                  </p>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Code</th>
                          <th>Nom</th>
                          <th>Description</th>
                          <th>Quantité</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        $products = Database::selectAll("product");
                        foreach ($products as $p) {
                        ?>

                          <tr>
                            <td><?php echo $p['code'] ?></td>
                            <td><?php echo $p['name'] ?></td>
                            <td><?php echo $p['description'] ?></td>
                            <td><?php echo $p['quantite'] ?></td>
                            <form method="POST" action="">
                              <?php if (verify($p["code"]) == "") : ?>
                                <input type="text" name="PQ" value="<?php echo $p['quantite'] ?>" hidden/>
                                <td>
                                  <button type="button" id="B<?php echo $p['code']; ?>" class="btn btn-secondary" data-toggle="modal" data-target="#M<?php echo $p['code']; ?>">
                                    Lancer la fabrication
                                  </button>
                                </td>


                                <div class="modal fade" id="M<?php echo $p['code']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $p['code']; ?>" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Produit : <?php echo $p['code']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="row">
                                          <div>
                                            <div class="form-group row">
                                                <div class="row">
                                                  <div class="col-md-9">
                                                    <div class="form-group">
                                                      <div class="form-check">
                                                          <h5  >Date début</h5>
                                                          <input type="date" name="dateD" class="col-sm-6" required/>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  </div>
                                                  <div class="row">
                                                  <div class="col-md-9">
                                                    <div class="form-group">
                                                      <div class="form-check">
                                                      <h5 >Date début</h5>
                                                        <input type="date" name="dateF" class="col-sm-6" required/>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                <div class="row">
                                                  <div class="col-md-9">
                                                    <div class="form-group">
                                                      <div class="form-check">
                                                      <h5>Quantité</h5>
                                                        <input type="number" name="Q" class="col-sm-6" required/>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                            </div>
                                          </div>
                                        </div>
                                        
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="lancer" value="<?php echo $p['code']; ?>" class="btn btn-primary">Lancer fabrication</button>
                                      </div>

                                    </div>
                                  </div>
                                </div>

                              <?php else : ?>
                                <td><button type="button" onclick="func<?php echo $p['code'] ?>()" class="btn btn-secondary">Lancer la fabrication</button></td>
                                <script>
                                  function func<?php echo $p['code'] ?>() {
                                    alert("<?php echo verify($p["code"]) ?>");
                                  }
                                </script>
                              <?php endif; ?>
                            </form>
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