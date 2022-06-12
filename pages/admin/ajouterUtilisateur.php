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
    if ($_POST['password'] != $_POST['password2']) {
        $errors = "incorrect password !";
    } else if (!empty(Database::select("employe", "email",$_POST['email'] ))) {
        $errors = "email déja existe !";
    } else {
        $keys = array("nom", "prenom", "email","password", "sexe", "role", "date_embauche");
        $values = array($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password']  ,$_POST['sexe'], $_POST['role'], date("Y-m-d", strtotime($_POST['hiringDtae'])));
        Database::insert("employe", $keys, $values);
        $success = $_POST['role']. " bien crée !";
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
                                    <img src="images/faces/face10.jpg" alt="image" class="img-sm profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                                    <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face12.jpg" alt="image" class="img-sm profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                                    <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face1.jpg" alt="image" class="img-sm profile-pic">
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
                                <p class="fw-light text-muted mb-0"><?php echo $_SESSION['auth']['email'] ;?></p>
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
                    <li class="nav-item nav-category">Services</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="menu-icon mdi mdi-account"></i>
                            <span class="menu-title">Users</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="#">Nouveau Utilisateur</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Nouveau Utilisateur</h4>
                                    <form class="forms-sample" action="" method="POST">
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
                                                    <?php echo $success ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php } ?>

                                        <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Nom</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="nom" id="exampleInputEmail2" placeholder="nom" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Prenom</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="prenom" id="exampleInputEmail2" placeholder="prenom" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" name="email" id="exampleInputEmail2" placeholder="email" required>
                                            </div>
                                        </div>

                                        <div class="form-group now grid-margin stretch-card">
                                            <label class="col-sm-3 col-form-label">Role</label>
                                            <div class="col-sm-8">
                                                <select class="js-example-basic-single w-50" name="role">
                                                    <option value="ingenieur">Ingénieur</option>
                                                    <option value="chef de production">Chef de production</option>
                                                    <option value="responsable de commandes">Resp. de Commandes</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Sexe</label>
                                            <div class="col-sm-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="sexe" id="membershipRadios1" value="F" >
                                                        Femme
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="sexe" id="membershipRadios2" value="H" checked>
                                                        Homme
                                                    </label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Date d'embauche</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="exampleInputMobile" name="hiringDtae" placeholder="date d'embauche" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Mot de passe</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="exampleInputPassword2" name="password" placeholder="mot de passe" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">Confirmation du mot de passe</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="exampleInputConfirmPassword2" name="password2" placeholder="confirmation du mot de passe" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary me-2">Ajouter Utilisateur</button>
                                    </form>
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