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

                if($res > 0){
                    $row1=mysqli_fetch_assoc($imgs);
                }
           
            }
        }
        
    } 


    if(empty($_GET['a_id'])){
        header('location:bids.php');
    }

    $aid=$_GET['a_id'];

    //Fetching user bid history
    $bid_history=mysqli_query($con,"SELECT * from bids INNER JOIN auction ON bids.a_id=auction.a_id where u_id='$uid'");
    date_default_timezone_set('Asia/Kolkata');

    //Todays Date
    $today=date('Y-m-d h:i:s');

    if($_SERVER["REQUEST_METHOD"]=="POST"){


        if(isset($_POST['increase'])){

            $bid_amount=$_POST['bid_amount'];


            $auction=mysqli_query($con,"SELECT * from auction where a_id='$aid'");

            $data=mysqli_fetch_assoc($auction);

            $current_bid=$data['current_bid'];

            $wining_bidder=$data['wining_bidder'];

            $auction_status=$data['auction_status'];

            if(!empty($bid_amount)){


                if($auction_status == 'active'){

                    // if($wining_bidder == $uid){
                    //     echo "<script>alert('You Can Increase Bid if You are OUT of Winning Zone')</script>";
                    // }
                    // els{
            
                        if(!($bid_amount <= $current_bid)){
                            
                            // if($bid_amount !== $bid_amount+100){
                            //     echo "Increase Bid by +100 Rs";
                            // }
                            // else{
                                $update=mysqli_query($con,"UPDATE auction SET current_bid='$bid_amount',wining_bidder='$uid' where a_id='$aid'");
            
                                if($update){
            
                                    $update2=mysqli_query($con,"UPDATE bids SET bid_amount='$bid_amount',bid_time='$today' where a_id='$aid' AND u_id='$uid'");
            
                                    if($update2){ 
                                        header('location:bids.php');           
                                    }
                                }
                                else{
                                    echo "<script>alert('Please Try Again Later ...!!!')</script>";
                                }//success condition
                            // }
                        }
                        else{
                            echo "<script>alert('You have To Increase Bid That is Greater Than $current_bid')</script>";
                        }//increase bid condition
            
                    // }//wining bid condition

                }
                else{
                    echo "<script>alert('Auction is Closed.You Can't Increase Bid')</script>";
                }//auction close condition

            }
            else{
                echo "<script>alert('Please Enter Increased Amount.')</script>";
            }//empty bid amount condition

        }//button condition

    }//post method condition

    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid' ");
	$imgs1=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");
    $row=mysqli_fetch_assoc($qry);
    $row_img=mysqli_fetch_assoc($imgs1);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/increase_bid.css">
    <link rel="stylesheet" href="css/style.css">
    <title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>

    <!-- swiper css link  -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

     <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


</head>
<body>

<!-- header section starts  -->

<header class="header">

    <a href="index.php" class="logo">
        <table>
            <tr>
                <td><image src="images/logo.png" /></td>
                <td><span>ZOO BIDS</span></td>
            </tr>
        </table>
    </a>

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

                        <a>
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


</header><!-- End -->

    
<!-- side-bar section starts -->

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

</div>

<div class="container">

    <div class="summary">
    <div class="top">
            <h1 style="font-size:25px;">Increase Bid</h1>
    </div>
    
        <div class="detail">
            <h2 id="itemB">edit bid</h2>
        </div>

        <div style="margin-top: 30px; padding: 0 30px;"  id="increase-bid">

            <form class="bid-form" method="post"  autocomplete='off'>  

                <!-- <button>Edit Bid</button> -->
                
                <div class='error-text'></div>
            
                <h2>Bid Amount:-</h2>
                <input type="text" placeholder="Enter Increasing Bid Amount" name="bid_amount" id="bid_amount" pattern="[0-9]*" oninvalid="this.setCustomValidity('Enter Only Numbers')" oninput="this.setCustomValidity('')" >&nbsp;&nbsp;
                <a href="bids.php"><button type="button" name="cancel" id="cancel"> <i class='fa-solid fa-xmark' ></i></button></a>
                <button type="submit" class="first-btn" id="promo" name="increase">submit</button>

            </form>

        </div>
        <hr>

</div>
</div>
       
<!-- footer section starts  -->



<section class="credit">

<p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN M K, PRIYADHARSHIKA, RENUKA, NIVASHINI K.</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->


<script src="js/script.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
</body>

</html>