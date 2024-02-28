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
    $imgs2 = mysqli_query($con," SELECT * from product_imgs");
    $row_img1 = mysqli_fetch_assoc($imgs2);

    error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOO BIDS</title>
    <script src="auction.js"></script>
    <link rel="website icon" type='png' href="images/logo.png"/>

    

    <!-- font awesome cdn link  -->
    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auction.css">

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZOO BIDS</title>
 
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
            $fetch_category=mysqli_query($con,"SELECT * from item_category");

            

            if(isset($_REQUEST['category'])){
                $categories=$_REQUEST['category'];
            }
            else{
                $categories='All';
            }
           
              

           

    if(isset($_GET['page'])){
        $page=$_GET['page'];
    }
    else{
        $page=1;
    }


    $num_per_page=4;

    if(isset($_REQUEST['searching'])){

        $search=$_REQUEST['searching'];
        
    }
    else{
        $search="";
    }
    $start_from=($page-1)*$num_per_page;

    if(!empty($search) ){

        //$sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and owner!='$uid' AND product_name LIKE '%$search%' GROUP BY a_id  LIMIT $start_from,$num_per_page";
       $sql = "SELECT * FROM products WHERE product_name = '$search'";
    }

    else if($categories !== 'All' ){
        $sql = " SELECT * FROM products WHERE category = '$categories' ";
    }
    else if(empty($categories))
    {
        $sql="select * from products ";
    }
    else if($categories === 'All'){

         //$sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and owner!='$uid' GROUP BY a_id  LIMIT $start_from,$num_per_page";
            $sql="select * from products ";

    }
    
    
    else{

        $sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and owner!='$uid' AND category='$categories' GROUP BY a_id  LIMIT $start_from,$num_per_page";

    }
?>
 
                    <div class="headers">

                        <form  method="post" action="" >
                            
                            <div class="navbars">
                                
                                <select name="category" class="dropdown" onchange="this.form.submit();">
                                    <option value="">Select options</option>
                                    <option value="All">All</option>
                                <?php
                                    while($display=mysqli_fetch_assoc($fetch_category)){
                                ?>                         
                                        <option value='<?php echo $display['c_id'];?>'> <?php echo $display['category_name']; ?> </option>
                                <?php
                                    }
                                ?>
                                </select>
                               

                               <!-- <div class="search">
                                    <input type="text" placeholder="Product Search(Name,Price)..." name="searching" id="searching">
                                </div>-->
                                <div class="searchbox1">
                                    <!-- <input type="text" placeholder="Product Search(Name,Price)..." name="searching" id="searching"> -->
                                    <div class="searchicon">
  
        <div class="search-container">
                <input type="text" name="searching" placeholder="Search..." class="search-input" id="searching">
                <a href="#" class="search-btn">
                <i class="fas fa-search"></i>      
                </a>
            </div>
            </div>
                                </div>


                            </form>

                                <div class="cart">

                                    <a href="addproduct.php?addproduct=<?php echo md5($uid);?> ">
                                        <button type="button">Add Product</button>
                                    </a>

                                </div>

                            </div>

                        <!-- </form> -->
                        
                    </div>

<?php
    
    $queryrun=mysqli_query($con,$sql);

        $rows=mysqli_num_rows($queryrun);

  
            if($rows > 0){

                    // echo $data['p_id'];
?>
                    <div class="container">

                        <div id="root">

                        <?php
                            while($data=mysqli_fetch_assoc($queryrun)){
                        ?>

                            <div class="box">

                                <div class="left">
                                    <p><?php echo $data['product_name'];?></p>
                                    <h2>Price:<span class="material-icons-outlined">currency_rupee</span><?php echo number_format($data['price']);?></h2>
                                    <a href="product_detail.php?p_id=<?php echo $data['p_id']; ?>"><button>View Details</button></a>    
                                </div>
                                <?php 
                                    while($rower=mysqli_fetch_assoc($imgs2))
                                    {
                                        $id = $rower['p_id'];
                                        if($id == $data['p_id'])
                                        {
                                            $imgg = $rower['product_imgs'];
                                            break; 
                                        }
                                    }
                                ?>
                                <div class="img-box">
                                <img src="<?php echo 'data:image;base64,'.base64_encode($imgg).''?> " />

                                </div>
                            </div>

                        <?php
                            }
                        ?>

                        </div>

                    </div>

                   
        

            <?php

            }else{
                if($categories == 'All'){
                    echo "<div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> Auction List Is Empty...!!! </h1> </div>";
                }
                else if(!empty($search)){
                    echo "<div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> $search Product not found...!!! </h1> </div>";
                }
                else{
                    echo "<div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> Auction List Is Empty for this category...!!! </h1> </div>";
                }
            }//if condition

                   //this is for counting number of pages
                if($categories == 'All'){
                  $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and products.owner!='$uid' GROUP BY a_id");
                }
                else if(!empty($search)){
                    $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and products.owner!='$uid' AND product_name LIKE '%$search%' GROUP BY a_id");
                }
                else if(!empty($search) && !empty($categories)){
                    $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and products.owner!='$uid' AND product_name LIKE '%$search%' AND c_id='$categories' GROUP BY a_id");
                }
                else if(!empty($categories)){
                  $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction.auction_status='active' and products.owner!='$uid' AND c_id='$categories' GROUP BY a_id");
                }

                  $count=mysqli_num_rows($res1);

                  //get total page
                  $total_page=$count/$num_per_page;

                  //exaect num of total pages
                  $pages=ceil($total_page);
                  for($i=1;$i<=$pages;$i++){

                    if($i < $pages){
           ?> 

                    <a href="auction.php?page=<?php echo $i;?>&category=<?php echo $categories; ?>&searching=<?php echo $search; ?>"></a>

            <?php
                    }else if($page == $i){
                            break;
                    }
                    else if($page > $i){
            ?>
                      <a href="complaints.php?page=<?php echo $i;?>&category=<?php echo $categories; ?>&searching=<?php echo $search; ?>"><button>Click Here To Back</button> </a>

            <?php
                    }
                        
                  }

                //   if(mysqli_num_rows($queryrun) > 0){
            ?>

            <div class="buttons">

                <?php
                    if($page > 1){
                ?>
                    <a href="auction.php?page=<?php echo $page-1;?>&category=<?php echo $categories;?>&searching=<?php echo $search; ?>"><button>Previous</button></a>

                <?php
                    }//previous button

                ?>
                    <h2>You Are on Page : <button class='page'><?php echo $page; ?></button></h2>    
                <?php
                    //next buttn
                    if($i > $page){
                ?>
                    <a href="auction.php?page=<?php echo $page+1;?>&category=<?php echo $categories;?>&searching=<?php echo $search; ?>"><button>Next</button></a>
                <?php
                    }

               // }//mysqli_num_rows condition
                ?>

            </div>
            
</section>
<!--popup -->
   

        
            <!-- <div class="box">
                <div class="img-box">
                    <img src=${image}></img>
                </div>
                <div class="left">
                    <p>${title}</p>
                    <h2>${price}</h2>
                    <button>Add to Cart</button>    
                </div>
            </div>


        </div> -->

        <!-- <div class="sidebar">sidebar</div> -->

<!-- footer section starts  -->



<section class="credit">

    <p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK, NIVASHINI K, PRIYADHARSHIKA, RENUKA</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>
</html>