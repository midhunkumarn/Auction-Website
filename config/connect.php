<?php
    $server="localhost";
    $uname="root";
    $password="";
    $db="bids_try";

    $con=mysqli_connect($server,$uname,$password,$db);

    // mysqli_select_db($db);

    if(!$con){
        echo "connection Failed.....";
    }
?>