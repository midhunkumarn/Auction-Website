<?php

session_start();

include '../config/connect.php';
require 'db_operations/auction_close.php';

error_reporting(0);

// include 'db_operations/getstatus.php';

$uid=$_SESSION['u_id'];

if(empty($uid)){
    header('location:../index.php');
}
elseif(empty($_GET['p_id'])){
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

$pid=$_REQUEST['p_id'];

//fetching users name and profile pic
$qry1=mysqli_query($con,"SELECT * from users where u_id='$uid'");
$imgs1=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");
  $row=mysqli_fetch_assoc($qry1);
  $row_img=mysqli_fetch_assoc($imgs1);


//getting current date and time
date_default_timezone_set('Asia/Kolkata');
$today=Date('Y-m-d H:i:s');
$today_date=date('Y-m-d H:i:s',strtotime($today));

//fetching product images
$product_imgs=mysqli_query($con,"SELECT * from product_imgs where p_id='$pid'");
$product_imgs1=mysqli_query($con,"SELECT * from product_imgs where p_id='$pid'");
$data_imgs=mysqli_fetch_assoc($product_imgs);

//fetching product data
$sql=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id where products.p_id='$pid' and auction.p_id='$pid'");
$product_data=mysqli_fetch_assoc($sql);

//current product ending date and time
$enddate=$product_data['end_time'];
$ending=date('Y-m-d H:i:s',strtotime($enddate));

$aid=$product_data['a_id'];
$owner=$product_data['owner'];
$auction_status=$product_data['auction_status'];
$wining_bidder=$product_data['wining_bidder'];
$paid_amount=$product_data['paid_amount'];
// $price=$product_data['price'];

//fetching user bid amount
$bidAmount=mysqli_query($con,"SELECT bid_amount from bids where a_id='$aid'");
$fetch=mysqli_fetch_assoc($bidAmount);
$bid=$fetch['bid_amount'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- swiper css link  -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/product_detail.css">
    <link rel="stylesheet" href="css/style.css">

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

                    <a>
                      
                        <img src="<?php echo 'data:image;base64,' . base64_encode($row_img['profile_pic']) ?>" />

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
<!-- header section ends -->

<!-- side-bar section starts -->
<div class="side-bar">

    <div id="close-side-bar" class="fas fa-times"></div>

    <div class="user">
        <img src="<?php echo 'data:image;base64,'.base64_encode($row1['profile_pic']).''?>" height="100" width="100" />'
        <h3><?php echo $row['name'];?></h3>
        <!-- <a href="db_operations/logout.php?logout_id=<?php echo $uid;?>">log out</a> -->
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

<section class="home">

    <div class="container flex">


      <div class="left">

        <div class="main_image">
          <img src="<?php echo 'data:image;base64,'.base64_encode($data_imgs['product_imgs']).''?>" class="slide product-img">
        </div>

        <div class="option flex">
          <?php
            while($data_imgs1=mysqli_fetch_assoc($product_imgs1)){
          ?>
            <img src="<?php echo 'data:image;base64,'.base64_encode($data_imgs1['product_imgs']).''?>" onclick="img('<?php echo 'data:image;base64,'.base64_encode($data_imgs1['product_imgs']).''?>')">
          <?php
            }
          ?>  
        </div>

      </div>


      <div class="right">

        <h3><?php echo $product_data['product_name']; ?></h3>

        <!-- Script for display countdown -->
        <script type="text/javascript" >

          var count_id='<?php echo $ending; ?>';

          var countDownDate=new Date(count_id).getTime();

          var current='<?php echo $today_date; ?>';

          var x=setInterval(function(){

              //get today date and time
              var now=new Date().getTime();

              //find the diffrence between now and count down date
              var distance=countDownDate - now;

              // document.write(distance);

              //Time Calculation for day,hour,minutes and second
              var days = Math.floor(distance / (1000 * 60 * 60 * 24));
              var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
              var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);

              // Output the result in an element with id="demo"
              document.getElementById("countdown").innerHTML ="Auction Ends In " + days + " Days " + hours + " Hours " + minutes + " Minutes " + seconds + " s";

              // If the count down is over, write some text 
              if (distance < 0) {
                  clearInterval(x);
                  document.getElementById("countdown").innerHTML = "Auction Ended....!!!";
              }
              
          }, 1000);

        </script>
        <!-- End countdown script -->

        <h1 class="timer"><span class="material-icons-outlined">timer</span>&nbsp;&nbsp; <span id="countdown"></span> </h1>

        <h4>Price: <span class="material-icons-outlined">currency_rupee</span><?php echo number_format($product_data['price']); ?> </h4>
<!-- displaying owner details to product winner after auction close -->
        <?php

          //fetching Highest bidder details
          $high_bid=mysqli_query($con,"SELECT current_bid,wining_bidder from auction where a_id='$aid'");
                  
          $row=mysqli_fetch_assoc($high_bid);

          $highest_bid=$row['current_bid'];

          $winning_bidder=$row['wining_bidder'];


        if($auction_status == 'close'){

          if($uid !== $winning_bidder){
            echo "";
          }
          else{
            $owner_info=mysqli_query($con,"SELECT * from users INNER JOIN users_imgs on users.u_id=users_imgs.u_id where users.u_id='$owner'");

            $display=mysqli_fetch_assoc($owner_info);
        ?>

        <div class="owner">

          <h2>Owner:-</h2>

            <div class="owner-data">

              <img src="<?php echo 'data:image;base64,'.base64_encode($display['profile_pic']).''?>" alt="" >


              <span class="name"><?php echo $display['name'];?></span>

            </div>

            <div class="contact">
              
              <span class="material-icons-outlined">phone</span>
              <h1><?php echo $display['contact_no'];?></h1>
            
            </div>

            <div class="email">
              
              <span class="material-icons-outlined">mail</span>
              <h1><?php echo $display['email'];?></h1>
            
            </div>

        </div>

        <?php
            }//wining bidder see owner datailes condition

        }else{
          echo "";
        }//auction status condition
        ?>
<!-- displaying owner details to product winner after auction close  ending condition-->

        <div>

          <h1>Product Details </h1>

          <textarea cols="70" rows='20' disabled>
            <?php 
            $desc=$product_data['product_desc'];

            $description=preg_replace('#\[nl\]#','<br>',$desc);
            $description=preg_replace('#\[sp\]#',"&nbsp",$desc);


            // $description=str_split($desc);
            
            echo ltrim($description);
            
            ?>
          </textarea>

        </div>


<!-- displaying user paid amount -->
        <?php
            if(!empty($product_data['googlepay_ss']) && ($wining_bidder == $uid)){
        ?>

            <div class="highest-bid1">
              <h1>Your Uploaded Google Pay Screenshot:</h1>
              <img src="<?php echo 'data:image;base64,'.base64_encode($product_data['googlepay_ss']).''?>" height="300px" alt="">
              <h3>Your Paid Amount:<?php echo $paid_amount; ?></h3>
              <h3>Your Pending Amount:<?php echo $paid_amount-$bid; ?></h3>
            </div>
        <?php
          }else{
            echo "";
          }
        ?>
<!-- displaying user paid amount end -->




<!-- displaying highest bidder -->
        
        <?php

if($auction_status !== 'close'){

  //fetching Highest bidder details
  $high_bid=mysqli_query($con,"SELECT current_bid from auction where a_id='$aid'");         
  $row=mysqli_fetch_assoc($high_bid);
  $highest_bid=$row['current_bid'];


  if(!empty($highest_bid) && !empty($wining_bidder)){

      //getting Highest  bidder details
      $getuser=mysqli_query($con,"SELECT users.name,users_imgs.profile_pic,bids.bid_amount from users INNER JOIN users_imgs on users.u_id=users_imgs.u_id INNER JOIN bids on users.u_id=bids.u_id where users.u_id='$wining_bidder' and bids.u_id='$wining_bidder'");

      $row2=mysqli_fetch_assoc($getuser);
          
        ?>

       

        <?php
              //getting Highest  bidder details
              $getuser=mysqli_query($con,"SELECT users.name,users_imgs.profile_pic,bids.bid_amount from users INNER JOIN users_imgs on users.u_id=users_imgs.u_id INNER JOIN bids on users.u_id=bids.u_id where users.u_id='$winning_bidder' and bids.u_id='$winning_bidder'");

              $row2=mysqli_fetch_assoc($getuser);
        ?>

        <div class="highest-bid">

            <img src="<?php echo 'data:image;base64,'.base64_encode($row2['profile_pic']).''?>" alt="" >


            <span class="name"><?php echo $row2['name'];?></span>


            <span class="price"><span class="material-icons-outlined">currency_rupee</span><?php echo number_format($highest_bid);?></span>

        </div>

        <?php
           }else{
            echo "";
          }

        }
       
          if($auction_status == 'active'){

        ?>

        <div>
            <button type="button" class="btn" name="bid" id="bid"><a href="makebid.php?p_id=<?php echo $pid; $_SESSION['bid']=$uid; ?>"> Bid Now </a></button>
        </div>

        <?php
          
          }
          else if($auction_status == 'close'){
            echo "";
          }
         
        ?>
       
      </div>
    </div>

    <!-- PHP COde to insert User Bid -->
   
            
            
</section>


<!-- footer section starts  -->

<section class="credit">

    <p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK,NIVASHINI K, PRIYA DHARSHIKA, RENUKA.</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->




<!-- swiper js link      -->
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
    function img(anything) {
      document.querySelector('.slide').src = anything;
    }

    function change(change) {
      const line = document.querySelector('.home');
      line.style.background = change;
    }

  </script>

  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>

</html>