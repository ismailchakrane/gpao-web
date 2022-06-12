<?php
session_start();
if (empty($_SESSION['auth']) ||  !isset($_SESSION['auth'])  || $_SESSION['auth']['role'] != 'chef de production') {
  header("location: ../../auth/logout.php");
}

require_once "../../Utils/Database.php";

if (!empty($_POST)) {
  if (isset($_POST['btnSubmit'])) {
    $materials = Database::select("product_materials", "id_product", $_POST['btnSubmit']);

    $prb = "Le produit ne peut etre fabriquer car le stock ne couvre pas les besoins <br/> du produits : Le produit est besoin de : <br/> ";
    $e = false;
    $valueToSubtract = 0;

    foreach ($materials as $m) {
      $materialInStock = Database::select("stock_materials", "id_material", $m['id_material']);
      $materialName = Database::select("materials", "id", $m['id_material']);

      if ((intval($materialInStock[0]['quantity'])  - intval($_POST['quantite'])  * intval($m['quantity'])) < 0) {
        $prb .= "* " . abs(intval($materialInStock[0]['quantity'])  - intval($_POST['quantite'])  * intval($m['quantity'])) . " unité de " .  $materialName[0]['code']  . "<br/>";
        $e = true;
      }
      $valueToSubtract = (intval($materialInStock[0]['quantity'])  - intval($_POST['quantite'])  * intval($m['quantity']));
    }
    if ($e) {
      Database::insert("problems", array("id_reporter", "message", "date"), array($_SESSION['auth']['id'], $prb, date("Y-m-d H:i:s")));
      Database::update("product", array("code"), array($_POST["btnSubmit"]), array("state"), array("bloque"));
    } 

    
      $keys = array("id_product	", "dateDebut", "dateFin", "quantity");
      $values = array($_POST["btnSubmit"], date("Y-m-d", strtotime($_POST['dateD'])), date("Y-m-d", strtotime($_POST['dateF'])), $_POST['quantite']);
      Database::insert("fabrication", $keys, $values);

      $materials = Database::select("product_materials", "id_product", $_POST['btnSubmit']);

      foreach ($materials as $m) {
        $materialInStock = Database::select("stock_materials", "id_material", $m['id_material']);
        $materialName = Database::select("materials", "id", $m['id_material']);
        Database::update("stock_materials", array("id_material"), array($materialName[0]['id']), array("quantity"), array($valueToSubtract));
      }

      header("location: consulterProduits.php");
    }


  if (isset($_POST['probBtnSubmit'])) {
    Database::insert("problems", array("id_reporter", "message", "date"), array($_SESSION['auth']['id'], $_POST['probMsg'], date("Y-m-d H:i:s")));
    Database::update("product", array("code"), array($_POST["probBtnSubmit"]), array("state"), array("bloque"));
    header("location: consulterProduits.php");
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
            <img src="../..//ressources/images/logo.svg" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="index.php">
            <img src="../..//ressources/images/logo-mini.svg" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Bonjour, <span class="text-black fw-bold"><?php echo $_SESSION['auth']['nom'] . ' ' . $_SESSION['auth']['prenom']; ?></span></h1>
            <h3 class="welcome-sub-text">Chef de Production</h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">


          <li class="nav-item">
            <form class="search-form" action="#">
              <i class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search Here" title="Search here">
            </form>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="icon-bell"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
              <a class="dropdown-item py-3">
                <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                <span class="badge badge-pill badge-primary float-right">View all</span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="../../images/faces/face10.jpg" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="../../images/faces/face12.jpg" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="../../images/faces/face1.jpg" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
            </div>
          </li>
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

      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
          </li>
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
              </ul>
            </div>
            <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
              <p class="text-gray mb-0">The total number of sessions</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div>
          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="../../images/faces/face1.jpg" alt="image"><span class="online"></span>
                </div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="../../images/faces/face2.jpg" alt="image"><span class="offline"></span>
                </div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="../../images/faces/face3.jpg" alt="image"><span class="online"></span>
                </div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="../../images/faces/face4.jpg" alt="image"><span class="offline"></span>
                </div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="../../images/faces/face5.jpg" alt="image"><span class="online"></span>
                </div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="../../images/faces/face6.jpg" alt="image"><span class="online"></span>
                </div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div>
          <!-- chat tab ends -->
        </div>
      </div>



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
                <li class="nav-item"><a class="nav-link" href="#">Consulter les
                    produits</a></li>
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
                <li class="nav-item"><a class="nav-link" href="consulterMatieres.php">Consulter les
                    matières</a></li>
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
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Produits</h4>
                  <p class="card-description">
                    Liste des produits
                  </p>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>
                            Code
                          </th>
                          <th>
                            Name
                          </th>
                          <th>
                            Description
                          </th>
                          <th>
                            Matières premières
                          </th>
                          <th>
                            Date de modification
                          </th>
                          <th>
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>


                        <?php

                        $products = Database::selectAllByOrder("product");

                        foreach ($products as $p) { ?>
                          <tr>
                            <td class="py-1">
                              <?php echo $p['code']; ?>
                            </td>
                            <td>
                              <?php echo $p['name']; ?>
                            </td>
                            <td>
                              <?php echo $p['description']; ?>
                            </td>
                            <td>
                              <?php
                              $materials = Database::select("product_materials", "id_product", $p['code']);
                              $materialsWithNames = "";
                              $i  = 1;
                              foreach ($materials as $m) {

                                $nameMaterial = Database::select("materials", "id", $m['id_material']);
                                if ($i == 1)
                                  $materialsWithNames .= $nameMaterial[0]['name'] . '(' . $m['quantity'] . ')';
                                else
                                  $materialsWithNames .= ',   ' . $nameMaterial[0]['name'] . '(' . $m['quantity'] . ')';

                                $i++;
                              }

                              echo $materialsWithNames;

                              ?>
                            </td>
                            <td>
                              <?php echo $p['dateE']; ?>
                            </td>

                            <td class="template-demo">
                              <form method="POST" action="">
                                <?php

                                $materials = Database::select("product_materials", "id_product", $p['code']);

                                $problem = "Le produit ne peut etre fabriquer car le stock ne couvre pas les besoins <br/> du produits : Le produit est besoin de : <br/> ";
                                $errors = false;

                                foreach ($materials as $m) {
                                  $materialInStock = Database::select("stock_materials", "id_material", $m['id_material']);
                                  $materialName = Database::select("materials", "id", $m['id_material']);

                                  if ((intval($materialInStock[0]['quantity'])  - intval($m['quantity'])) < 0) {
                                    $problem .= "* " . abs(intval($materialInStock[0]['quantity'])  - intval($m['quantity'])) . " unité de " .  $materialName[0]['code']  . "<br/>";
                                    $errors = true;
                                  }
                                }



                                ?>
                                <?php if (!$errors) : ?>
                                  <button type="button" id="B<?php echo $p['code']; ?>" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo $p['code']; ?>">
                                    Commencer Fabrication
                                  </button>

                                  <div class="modal fade" id="<?php echo $p['code']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $p['code']; ?>" aria-hidden="true">
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
                                            <div class="col-md-6">
                                              <div class="form-group row">
                                                <h5 class="col-sm-6 col-form-label">Date Début</h5>
                                                <input type="date" name="dateD" class="col-sm-6" required />
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group row">
                                                <h5 class="col-sm-6 col-form-label">Date Fin</h5>
                                                <input type="date" name="dateF" class="col-sm-6" required />
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group row">
                                                <h5 class="col-sm-6 col-form-label">Quantité</h5>
                                                <input type="number" name="quantite" class="col-sm-6" required />
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" name="btnSubmit" value="<?php echo $p['code']; ?>" class="btn btn-primary">Commencer Fabrication</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                <?php else : ?>
                                  <button type="button" id="PB<?php echo $p['code']; ?>" class="btn btn-danger" data-toggle="modal" data-target="#P<?php echo $p['code']; ?>">
                                    Commencer Fabrication
                                  </button>

                                  <div class="modal fade" id="P<?php echo $p['code']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $p['code']; ?>" aria-hidden="true">
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
                                                <center>
                                                  <h5>Erreur : </h5>
                                                </center>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div>
                                              <div class="form-group row">
                                                <p><?php echo $problem; ?></p>
                                                <input type="text" value="<?php echo $problem; ?>" name="probMsg" hidden />
                                              </div>
                                            </div>
                                          </div>
                                        </div>


                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" name="probBtnSubmit" value="<?php echo $p['code']; ?>" class="btn btn-danger">Signaler le problème</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                <?php endif; ?>
                              </form>
                            </td>
                          </tr>

                        <?php
                        }
                        ?>



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
  <script src="../../ressources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="../../ressources/js/off-canvas.js"></script>
  <script src="../../ressources/js/hoverable-collapse.js"></script>
  <script src="../../ressources/js/template.js"></script>
  <script src="../../ressources/js/settings.js"></script>
  <script src="../../ressources/js/todolist.js"></script>


</body>

</html>