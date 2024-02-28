<?php
    session_start();

    include '../../config/connect.php';

    if(isset($_SESSION['u_id'])){
        $logout_id=mysqli_real_escape_string($con,$_GET['logout_id']);

        if(isset($logout_id)){
            session_unset();

            session_destroy();

            header('location:../index.php');
        }else{
            header('index.php');
        }
    }else{
        header('location:../index.php');
    }
?>