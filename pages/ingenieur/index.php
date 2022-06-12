<?php
  session_start();
  if(empty($_SESSION['auth']) ){
    header("location ../../auth/logout.php");
  }
  else if($_SESSION['auth']['role'] != 'ingenieur'){
    header('location ../../'. $_SESSION['auth']['role'] . '/index.php');
  }
  else{
    header('location: consulterProduits.php');
  }
