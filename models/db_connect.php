<?php
    require_once 'creds.php';

    $con = mysqli_connect($host, $user, $password, $db_name);
    if(mysqli_connect_errno()){
        die("Connection failed".mysqli_connect_error());
    }
?>