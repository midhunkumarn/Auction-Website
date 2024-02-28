<?php
    session_start();

    include '../config/connect.php';
    require 'db_operations/auction_close.php';

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
 
    if(!empty($_GET['bid_amount'])){
        $mybid=$_GET['bid_amount'];
        $highest_bid=$_GET['highest_bid'];

    }

    //Fetching user bid history
    $bid_history=mysqli_query($con,"SELECT * from bids INNER JOIN auction ON bids.a_id=auction.a_id where u_id='$uid'");

    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid'");
	$imgs1=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");
    $row=mysqli_fetch_assoc($qry);
    $row_img=mysqli_fetch_assoc($imgs1);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bids.css">
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


</header>
<!-- End -->

    
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


    <?php
        if(mysqli_num_rows($bid_history) > 0){

            //for getting all history
            
    ?>
        
        <div class="container">
            <div class="cart">
                <div class="top">
                    <h2>Bids Details</h2>
                    <h2 id="itemA">Total Bids <?php echo mysqli_num_rows($bid_history); ?></h2>
                </div>
                <table class="table-head">
                    <tr>
                        <th width="150" class="head-img">Image</th>
                        <th width="200">Name</th>
                        <th width="150">Price</th>
                        <th width="150">Status</th>
                        <th width="230">Bid Amount</th>

                       

                        <th width="260">Options</th>

                       

                       

                    </tr>
                    
                    
                </table>
                <table id="root" cellspacing="0">

        <?php
            while($row2=mysqli_fetch_assoc($bid_history)){

                //product id of Product that user bid
                $p_id=$row2['p_id'];

                //payment is done or not
                $payment=mysqli_query($con,"SELECT price,confirm,paid_amount from products where p_id='$p_id'");
                $disp_pay=mysqli_fetch_assoc($payment);
                $price=$disp_pay['price'];
                $confirm=$disp_pay['confirm'];
                $paid_amount=$disp_pay['paid_amount'];
                $bid_amount=$row2['bid_amount'];
                $auction_status=$row2['auction_status'];
                $current_bid=$row2['current_bid'];

                //Fetching product id of product for getting the details of details
                $select_product=mysqli_query($con,"SELECT * from products INNER JOIN product_imgs on products.p_id=product_imgs.p_id where products.p_id='$p_id' LIMIT 1");

                while($row3=mysqli_fetch_assoc($select_product)){

        
        ?>
                   <tr>
                        <td width="150"><div class="img-box"><img class="img" src="<?php echo 'data:image;base64,'.base64_encode($row3['product_imgs']).''?>"></div></td>
                        <td width="200"><p style='font-size:15px;'><?php echo $row3['product_name'];?></p></td>
                        <td width="150"><h2 style='font-size:15px; color:red; '><i class="fa-solid fa-indian-rupee-sign"></i><?php echo number_format($row3['price']);?></h2></td>
                        <td width="150">
                            
                            <h2 style='font-size:15px; color:red; '>
                                <?php

                                    $zone=mysqli_query($con,"SELECT wining_bidder from auction where p_id='$p_id'");

                                    $row4=mysqli_fetch_assoc($zone);

                                    $winning_bidder=$row4['wining_bidder'];

                                    if($uid == $winning_bidder){

                                        if($auction_status == 'close'){
                                            echo "Winner";
                                        }else{
                                            echo "Wining Zone";
                                        }
                                    }
                                    else{
                                        echo "Runner UP";
                                    }
                                ?>
                            </h2>

                        </td>

                        <td width="90"><h2 style='font-size:15px; color:red; '><i class="fa-solid fa-indian-rupee-sign"></i><?php echo number_format($bid_amount);?></h2></td>
                        
                        <td width="300">
                            
                                    <!-- condition for edit button -->
                            <?php
                                if($auction_status == 'active'){
                            ?>        
                                    <a href="increase_bid.php?a_id=<?php echo $row2['a_id'];?>" > <button type="button" name="edit" id="edit"><i class='fa-solid fa-pen-to-square'></i></button></a>
                                    <a href="db_operations/remove_bid.php?p_id=<?php echo $row2['p_id']; $_SESSION['bid']=$row2['bid_amount'];?>"><button type="button" name="delete"><i class='fa-solid fa-trash' ></i></button></a>
                                    <a href="product_detail.php?p_id=<?php echo $row2['p_id']; ?>"><button type="button" name="delete"><i class='fa-solid fa-eye' ></i></button></a>
                            <?php
                                }
                                else{
                            ?>
                                    <a href="db_operations/remove_bid.php?p_id=<?php echo $row2['p_id']; $_SESSION['bid']=$row2['bid_amount'];?>"><button type="button" name="delete"><i class='fa-solid fa-trash' ></i></button></a>
                                    <a href="product_detail.php?p_id=<?php echo $row2['p_id']; ?>"><button type="button" name="delete"><i class='fa-solid fa-eye' ></i></button></a>
                            <?php
                                }
                            ?>
                            <!-- end condition -->
                        </td>
                        
                        <!-- Condition for Payment Option -->
                        <?php
                            if($winning_bidder == $uid){

                                //if auction close then display payment buttons
                                if($auction_status == 'close'){

                                    //if paid amount not equal to price
                                    if($paid_amount < $bid_amount && $confirm == 'recieved'){
                        ?>
                                        <td width="200">
                                            <a href="db_operations/payment.php?cod=half&p_id=<?php echo $p_id; ?> "><button style="background:cyan;">Cash</button></a>&nbsp;
                                            <a href="qr_payment.php?qr=true&p_id=<?php echo $p_id; ?> "><button style="background:cyan;">QR Scan</button></a>
                                        </td>
                        <?php
                                    }
                                    else if($confirm == 'pending'){
                        ?>
                                        <td width="90">
                                            <a href="payment.php?p_id=<?php echo $row2['p_id']; $_SESSION['payment']=true;?> "><button style="background:cyan;">Payment Options</button></a>
                                        </td>
                        <?php
                                    }else if($confirm == 'recieved' && $paid_amount == $bid_amount){
                                        echo "<td>Wait<td>"; 
                                    }
                                    else if($confirm == 'done'){
                                        echo "<td>Payment Done</td>";
                                    }
                                    else if($confirm == 'cod'){
                                        echo '<td>Cash Payment</td>';
                                    }
                                    else{
                        ?>
                                    <td>
                                        <a href="payment.php?p_id=<?php echo $row2['p_id'];?>"><button>Payment Failed..!!</button></a>
                                    </td>    

                        <?php
                                    }//payment condition

                                }//auction condition
                                
                            }//winning Bidder Condition...!!
                        ?>

                        <!-- Condition for payment option done -->
                   </tr>



        <?php
                    }//while loop ends 

            }

                    
        ?>
                </table>
                <hr>
            </div>

      </div>

        <?php
      
            }else{
        ?>
                <div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> Bid History Is Empty...!!! </h1> </div>
        <?php
            }

        ?>

       

<!-- footer section starts  -->

<!-- <section class="quick-links">

<a href="index.php" class="logo"> Auction </a>

</section> -->

<section class="credit">

<p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK, NIVASHINI K, PRIYADHARSHIKA, RENUKA</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->


<script src="js/script.js"></script>

</body>

</html>