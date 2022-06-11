<?php
  session_start();
  if(empty($_SESSION['auth']) || $_SESSION['auth']['role'] != 'admin'){
    header("location ../../auth/logout.php");
    }
  header("location: ajouterUtilisateur.php");