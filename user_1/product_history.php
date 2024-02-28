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

        if(!empty($_GET['page'])){
            $page=$_GET['page'];
        }
        else{
            $page=1;
        }

    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid'");
	$imgs1=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");
    $row=mysqli_fetch_assoc($qry);
    $row_img=mysqli_fetch_assoc($imgs1);
?>

<!DOCTYPE html>

<html>

<head>

	<title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


	<link rel="stylesheet" href="css/product_history.css">
	<link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>
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
                        <img src="<?php echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>" />

                        <?php echo $row['name'];?>
                    </a>
                    <ul class="dropdown">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="db_operations/logout.php?logout_id=<?php echo $uid;?>">Log out</a></li>
                    </ul>
                </li>
            </ul>

                <!-- <ul class="profile-wrapper">
                    <li>
                        user profile
                        <div class="profile">
                            <img src="<?php// echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>" />
                            <a href="http://swimbi.com" class="name"><?php //echo $row['name'];?></a>
                            
                            more menu
                            <ul class="menu">
                                <li><a href="profile.php">Profile</a></li>
                                <li><a href="../forgotpass.php">Change Password</a></li>
                                <li><a href="db_operations/logout.php?logout_id=<?php //echo $uid;?>">Log out</a></li>
                            </ul>
                        </div>
                    </li>
                </ul> -->

            </td>

        </tr>

    </table>

   

    
    <!-- <div class="profile"> -->

    <!-- </div> -->

   

</header>

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
<!-- End -->

    <div style="align-items:center;text-align:center;">
        <form method="post">
            <select name="status" id="status" class="box" onchange='this.form.submit()'>
                <option value="">Select Option</option>
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="close">Close</option>
            </select>
        </form>
    </div>

<div class="container" id="all-product">

        <?php

         //statuus selection
         if(isset($_REQUEST['status'])){
            $select=$_REQUEST['status'];

          }else{
            $select="all";

          }

          $num_per_page=2;

          $start_from=($page-1)*$num_per_page;


             //fetching product history
        if($select == 'all'){
            $allhistory2=mysqli_query($con,"SELECT DISTINCT * FROM products INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN auction on products.p_id=auction.p_id Where owner='$uid' GROUP BY a_id limit $start_from,$num_per_page");
        }else{
            $allhistory2=mysqli_query($con,"SELECT DISTINCT * FROM products INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN auction on products.p_id=auction.p_id Where owner='$uid'AND auction_status='$select' GROUP BY a_id limit $start_from,$num_per_page");
        }
            // $res=$allhistory);

            if(mysqli_num_rows($allhistory2) > 0){
            // if($allhistory){

               

            // echo $row1['a_id'];

        ?>

        <h1>Your Products</h1>

        <div class="cart">

        <?php
                 while($row1=mysqli_fetch_array($allhistory2)){
        ?>

        <div class="products" id="history">

                    <div class="product">

                        <img src="<?php echo 'data:image;base64,'.base64_encode($row1['product_imgs']).''?>">
                    

                        <div class="product-info">

                            <h3 class="product-name"><?php echo $row1['product_name']; ?></h3>

                            <h4 class="product-price"><i class="fa-solid fa-indian-rupee-sign"></i><?php echo $row1['price']; ?></h4>

                            <h4>Auction Status: <?php echo $row1['auction_status']; ?></h4>
                            <h4>Auction End ON: 
                                <?php 
                                    $end=$row1['end_time'];
                                    
                                    $ending=date('d-m-Y h:i:s a',strtotime($end));

                                    echo $ending;
                                ?>
                            </h4>

                            <!--<p class="product-remove">

                                <i class="fa fa-trash" ></i>

                                <a href="db_operations/remove_auction.php?a_id=<?php echo $row1['a_id'];?>"><span>Remove</span></a>

                            </p>-->

                            <p class="product-view">

                                <i class="fa fa-eye"></i>

                            <a href="product_view.php?p_id=<?php echo $row1['p_id'];?>"><span>View</span></a> 

                            </p>

                            <p class="product-edit">

                                <i class="fa-solid fa-pen-to-square"></i>

                                <a href="product_edit.php?pid=<?php echo $row1['p_id']; ?>"><span>Edit</span></a>

                            </p>

                           

                        </div>

                    </div>
        <?php

                }
                
        ?>

                </div>

            </div>

        <?php
            }else{
                echo "<div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> Products List Is Empty...!!! </h1> </div>";
            }

            if($select == 'all'){
                $res=mysqli_query($con,"SELECT DISTINCT * FROM products INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN auction on products.p_id=auction.p_id Where owner='$uid' GROUP BY a_id");
              }
              else{
                $res=mysqli_query($con,"SELECT DISTINCT * FROM products INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN auction on products.p_id=auction.p_id Where owner='$uid' and auction_status='$select' GROUP BY a_id");
              }

              $count=mysqli_num_rows($res);

                  $total_page=$count/$num_per_page;
                

                  $pages=ceil($total_page);

            for($i=1;$i<=$pages;$i++){

                if($i < $pages){
?> 
                    <a href="product_history.php?page=<?php echo $i;?>&status=<?php echo $select;?>"></a>
         
            <?php
                    }
                else if($page == $i){
                    break;
                }
                else if($page > $i){
            ?>
                    <a href="product_history.php?page=<?php echo $i;?>&status=<?php echo $select;?>"><button>Click Here To Back</button> </a>
         
            <?php
                }//end

            }//for loop
            
        ?>


    </div>

    <div class="buttons">
        <?php
            if($page>1){
        ?>
                <a href="product_history.php?page=<?php echo $page-1;?>&status=<?php echo $select;?>"><button>Previous</button></a>
        <?php
            }
        ?>

        <?php
            if($i>$page){
        ?>
                <a href="product_history.php?page=<?php echo $page+1;?>&status=<?php echo $select;?>"><button>Next</button></a>
        <?php
            }
        ?>
    </div>

    

</div>

</div>



<!-- footer section starts  -->


<section class="credit">

    <p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK, NIVASHINI K, PRIYADHARSHIKA, RENUKA</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->

<script src="js/script.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

</body>


</html>
