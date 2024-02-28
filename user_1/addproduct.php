<?php
    session_start();

    include '../config/connect.php';

    $uid=$_SESSION['u_id'];

    if(empty($uid)){
        header('location:../index.php');
    }

    if(!isset($_GET['addproduct'])){
        header('location:auction.php');
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
    date_default_timezone_set('Asia/Kolkata');
    $today=date('Y-m-d h:i:s');
    $today_date=date('Y-m-d h:i:s',strtotime($today));
    $category=mysqli_query($con,"SELECT * from item_category");
    $maxdate="2050-12-31 24:00:00";
    
    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users  where u_id='$uid'");
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

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- swiper css link  -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" /> -->

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/add_product.css">
    <link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">

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
        <a href="product_history.php"> <i class="fas fa-angle-right"></i> History </a>
        <a href="complaint.php"> <i class="fas fa-angle-right"></i> Complaint</a>
        <a href="profile.php"> <i class="fas fa-angle-right"></i> Profile</a>
    </nav>

</div>

<!-- side-bar section ends -->

<div class="main-block">
      <div class="left-part">
        <image src=" ">
      </div>
        <form action="db_operations/add_product.php" method="post" class="addproduct-form" enctype="multipart/form-data"  autocomplete='off'>

            <h1>Add Product Details</h1>

            <div class="error-text">Error</div>

            <div class="info">

            <input class="fname field" type="text" name="product_name" id="product_name" class="field" placeholder="product name">

            <select class="field" name="category" id="category">
                <option value="none" selected>Select Category</option>

                    <?php
                    //Selectin category from database
                    while($row=mysqli_fetch_assoc($category)){
                    ?>
                    <option value="<?php echo $row['c_id'];?>"><?php echo $row['category_name'];?></option>
                    <?php
                    }
                    ?>
            </select>

                <select class="field" name="subcategory" id="subcategory">
                    <option value="none" selected>Select Subcategory</option>               
                </select>

            </div>

            <p>Product Description:</p>
            <div>
                <textarea placeholder="Enter Product Details" name="product_desc" id="product_desc" class="field"></textarea>
            </div>
            <p><?php echo ("$today") ?></p>
            <p>Select Auction End Date & Time :-</p>
            <div class="info">
                <input type="datetime-local" name="end_time" id="end_time" class="field" >
                <p>Select Your Product Image :-</p>
                <input type="file" name="product_imgs[]" id="product_imgs" class="field" multiple>
                <input type="text" name="price" class="field" pattern="[0-9]*" oninvalid="this.setCustomValidity('Enter Only Numbers')" oninput="this.setCustomValidity('')" placeholder="Enter Price">
            </div>
        
            <button type="submit" class="btn" name="add_product">Submit</button>

        </form>
    </div>


    <!-- footer section starts  -->



<section class="credit">

<p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN M K, NIVASHINI, PRIYADHARSHIKA, RENUKA.</span> | all rights reserved!  </p>


</section>

<!-- footer section ends -->

<script src="js/add_product.js"></script>
<script src="js/script.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
	$('#category').on('change', function() {
			var category_id = this.value;
			$.ajax({
				url: "db_operations/get_subcategory.php",
				type: "POST",
				data: {
					c_id: category_id
				},
				cache: false,
				success: function(dataResult){
					$("#subcategory").html(dataResult);
				}
			});
		
		
	});
});
//const now = new Date().getTime()
var now = "ss"
<?php $my = 'now'; ?>
</script>


</body>
</html>