<?php    
    session_start();

    include '../config/connect.php';

    $uid=$_SESSION['u_id'];

    if(empty($uid)){
        header('location:../index.php');
    }

    $qry=mysqli_query($con,"SELECT * from users WHERE u_id='{$uid}'");

    if(mysqli_num_rows($qry) > 0){

        $row=mysqli_fetch_assoc($qry);

        if($row){

            if($row['verification'] != 'Verified'){

                echo "Not Verified Email Address....!!!";
                header('location:../verify.php');
            }
            else{

                $imgs=mysqli_query($con,"SELECT * from users_imgs WHERE u_id='{$uid}'");
    
    
                $res=mysqli_num_rows($imgs);
    
                if(empty($res)){
    
                    $_SESSION['u_id']=$uid;
            
                    header('location:imageupload.php');
                }
                else{

                    if($res > 0){
                        $row1=mysqli_fetch_assoc($imgs);
                    }

                }
           
            }
        }
        
    }

    if(empty($_GET['p_id'])){
        header('location:bids.php');
    }

    $p_id=$_GET['p_id'];

    $selectOwner=mysqli_query($con,"SELECT owner from products where p_id='$p_id'");
    $row2=mysqli_fetch_assoc($selectOwner);
    $owner=$row2['owner'];

    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid' ");
	$imgs1=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");
    $row=mysqli_fetch_assoc($qry);
    $row_img=mysqli_fetch_assoc($imgs1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


</head>
<body>
    
<header class="header">

    <a href="index.php" class="logo" style="text-decoration:none;">
        <table>
            <tr>
                <td><image src="images/logo.png" /></td>
                <td><span>ZOO BIDS</span></td>
            </tr>
        </table>
    </a>
<!-- 
    <form action="" class="search-form">
        <input type="search" id="search-box" placeholder="search here...">
        <label for="search-box" class="fas fa-search"></label>
    </form> -->

    <table class="button-header">

        <tr>

            <td>
                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                </div>
            </td>

            <td>

            <ul>
                
                <!-- <li><a href="#">Update</a></li> -->
                <li>

                    <a style="color:white;">
                        <img src="<?php echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>" />

                        <?php echo $row['name'];?>
                    </a>
                    <ul class="dropdown">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="db_operations/logout.php?logout_id=<?php echo $uid;?>">Log out</a></li>
                    </ul>
                </li>
            </ul>

            </td>

        </tr>

    </table>

</header>


<div class="side-bar">

    <div id="close-side-bar" class="fas fa-times"></div>

    <div class="user">
        <img src="<?php echo 'data:image;base64,'.base64_encode($row1['profile_pic']).''?>" height="100" width="100" />'
        <h3><?php echo $row['name'];?></h3>
    </div>


    <nav class="navbar1">
        <a href="index.php"> <i class="fas fa-angle-right"></i> Home </a>
        <a href="auction.php"> <i class="fas fa-angle-right"></i> Auction </a>
        <a href="bids.php"> <i class="fas fa-angle-right"></i> Bids </a>
        <a href="product_history.php"> <i class="fas fa-angle-right"></i>History </a>
        <a href="complaint.php"> <i class="fas fa-angle-right"></i> Complaint</a>
        <a href="profile.php"> <i class="fas fa-angle-right"></i> Profile</a>
    </nav>

</div><br><br><br><br><br><br>

<!-- <div class=".options"> -->
<center>
            <div class="col-6 mt-4">
                <div class="card p-3">
                    <p class="mb-0 fw-bold h4">Payment Methods</p>
                </div>
            </div>
            
            <div class="col-6">
                <div class="card p-3">
                    <div class="card-body border p-0">
                        <p id="cash">
                            <a class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-between" href="db_operations/payment.php?cod=true&p_id=<?php echo $p_id; ?>"
                                data-bs-toggle="collapse"  role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">COD</span>
            
                            </a>
                        </p>
                        
                    </div>

                <?php

                    $sql=mysqli_query($con,"SELECT verify from users_imgs where u_id='$owner'");

                    $row=mysqli_fetch_assoc($sql);

                    $verify=$row['verify'];

                    if($verify == 'true'){

                ?>

                    <div class="card-body border p-0">
                        <p>
                            <a class="btn btn-primary p-2 w-100 h-100 d-flex align-items-center justify-content-between" href="qr_payment.php?qr=true&p_id=<?php echo $p_id; ?>"
                                data-bs-toggle="collapse" id="qr_scan" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">QR CODE</span>
                               
                            </a>
                        </p>
                        <!-- <div class="collapse show p-3 pt-0" id="collapseExample">
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    
                                </div>
                            </div>
                        </div> -->
                    </div>

                <?php
                    }
                    else{
                ?>
                    <div class="card-body border p-0">
                        <p>
                            <!-- <a class="btn btn-primary p-2 w-100 h-100 d-flex align-items-center justify-content-between"
                                data-bs-toggle="collapse" id="qr_scan" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">QR CODE</span>
                               
                            </a> -->

                            <h3>Owner QRCode image Is Not Verified.</h3>
                            <h3>After Verified You Are Able to Pay Using Google Pay QR Scanning.</h3>
                        </p>
                        <!-- <div class="collapse show p-3 pt-0" id="collapseExample">
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    
                                </div>
                            </div>
                        </div> -->
                    </div>
                <?php
                    }
                ?>

                </div>
            </div>

            <!-- <div class="col-6">
                <div class="btn btn-primary payment">
                    Make Payment
                </div>
            </div> -->
        </div>
    </div>
<!-- </div> -->

</div>

<div>

<div class="card-body border p-0">
                        <p>
                            <a class="btn btn-primary p-2 w-10 h-10 d-flex align-items-center justify-content-between" href="bids.php"
                                data-bs-toggle="collapse" id="qr_scan" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <span class="fw-bold">BACK</span>
                               
                            </a>
                        </p>
                        
                    </div>
                </center><br><br><br><br><br><br><br><br><br><br><br>

<!-- footer section starts  -->



<section class="credit" align="center">

    <p> created by <span>MIDHUNKUMAR N, RAVIRAGAV N M, KAVIYAN MK, PRIYADHARSHIKA, RENUKA, NIVASHINI K.</span> | all rights reserved! </p>


</section>

<script src="js/script.js"></script>


</body>
</html>