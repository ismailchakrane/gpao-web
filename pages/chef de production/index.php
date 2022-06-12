<?php
  session_start();
  if(empty($_SESSION['auth']) ){
    header("location: ../../auth/logout.php");
  }
  else if($_SESSION['auth']['role'] != 'chef de production'){
    header('location: ../../index.php');
  }
  else{
    header('location: fabrications.php');
  }
