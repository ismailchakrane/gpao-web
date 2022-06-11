<?php
session_start();

if(!empty($_SESSION['auth']) && isset($_SESSION['auth'])){
    switch ($_SESSION['auth']['role']) {
        case 'admin':
            header('location: pages/admin/index.php');
            break;

        case 'ingenieur':
            header('location: pages/ingenieur/index.php');
            break;
        
        case 'chef de production':
            header('location: pages/chef de production/index.php');
            break;

        case 'responsable de stock':
            header('location: pages/chef de stock/index.php');
            break;

        case 'responsable de commandes':
            header('location: pages/chef de commandes/index.php');
            break;
    }
    
} else{
    header('location: auth/login.php');
}
