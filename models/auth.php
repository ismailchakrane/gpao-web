<?php
    require 'db_connect.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    //need to implement special chars escaping to avoid sql injection later

    //---------------------------------------------------------------------

    $qu1 = "select * from admin where username = '$username' and password = '$password'";
    $res1 = mysqli_query($con, $qu1);

    if(mysqli_num_rows($res1) == 1) echo '<script> window.location.replace("success.php") </script>';
    else echo '<script> window.location.replace("index.php") </script>'
?> 
