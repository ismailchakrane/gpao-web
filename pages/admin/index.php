<?php
  session_start();
  if(empty($_SESSION['auth']) ){
    header("location: ../../auth/logout.php");
  }
  else if($_SESSION['auth']['role'] != 'admin'){
    header('location: ../../index.php');
  }
  
  else{
    header('location: ajouterUtilisateur.php');
  }
