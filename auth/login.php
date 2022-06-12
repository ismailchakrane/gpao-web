<?php
session_start();

if(!empty($_SESSION['auth'])){
  header('location: ../pages/' . $_SESSION['auth']['role'] . '/index.php');
}
require_once '../Utils/Database.php';

if (!empty($_POST)) {
  $employe = Database::select("employe", "email", $_POST['email']);

  if(!empty($employe)){
    if($employe[0]['password'] == $_POST['Password'] ){
      session_start();
      $_SESSION['auth'] = $employe[0];
      header('location: ../pages/' . $_SESSION['auth']['role'] . '/index.php');
      exit();
    } 
    else{
      $errors = "email or password incorrect";
    }
  }
  else {
    $errors = "email or password incorrect";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <link rel="stylesheet" href="../ressources/vendors/feather/feather.css">
  <link rel="stylesheet" href="../ressources/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../ressources/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../ressources/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../ressources/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../ressources/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../ressources/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../ressources/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../ressources/images/logo.svg" alt="logo">
              </div>
              <h4>Bonjour</h4>
              <h6 class="fw-light">veuillez se connecter pour continuer</h6>
              <form class="pt-3" action="" method="POST">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" placeholder="Password" name="Password" required>
                </div>
                <div class="mt-4 form-group">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">Se connecter</button>
                </div>
                <?php if (isset($errors)) : ?>
                  <div class="form-group">
                    <div class="alert alert-danger" role="alert">
                    <?php echo $errors ?>
                    </div>
                  </div>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../ressources/vendors/js/vendor.bundle.base.js"></script>
  <script src="../ressources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="../ressources/js/off-canvas.js"></script>
  <script src="../ressources/js/hoverable-collapse.js"></script>
  <script src="../ressources/js/template.js"></script>
  <script src="../ressources/js/settings.js"></script>
  <script src="../ressources/js/todolist.js"></script>
</body>

</html>