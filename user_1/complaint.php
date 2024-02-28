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

    date_default_timezone_set('Asia/Kolkata');
    $today=Date('Y-m-d h:i:s');
    $today_date=date('Y-m-d h:i:s',strtotime($today));
    $category=mysqli_query($con,"SELECT * from item_category");

    //fetching users name and profile pic
    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid'");
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
    <link rel="stylesheet" href="css/complaint.css">
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

<!-- <center> -->
<div class="main-block">

    
        <form action="db_operations/complaint.php" method="post" class="complaint-form">
            
            <button id="complaint">Click Here to View Your Complaints</button>
            <button id="complaint1">Back</button>

            <h1>Register Your Complaint</h1>

            <div class="error-text" >Error</div>

            <p id='p'>Complaint Description:</p>
            <div id='div'>
                <textarea placeholder="Complaint Description" name="complaint_desc" id="complaint_desc" class="field"></textarea>
            </div>

            <button type="submit" class="btn" name="add_product" id="btn">Submit</button>

            <div id="complaints">

                <?php
                    $complaints=mysqli_query($con,"SELECT * from complaints where u_id='$uid'");

                    if(mysqli_num_rows($complaints) > 0){


                ?>


                <table>
                    <tr>
                        <th><h4>Complaint Description</h4></th>
                        <th><h4>Complaint Status</h4></th>
                    </tr>

                    <?php
                        while($display=mysqli_fetch_assoc($complaints)){
                    ?>

                    <tr>
                        <td>
                            <textarea>
                                <?php
                                    $desc=$display['complaint_description'];

                                    $complaint=str_replace('\n','<br>',$desc);

                                    echo $complaint;
                                ?>
                            </textarea>
                        </td>

                        <td>
                            <h3><?php echo $display['status'];?></h3>
                        </td>

                       
                    </tr>

                    <?php
                        }
                    ?>
                </table>

                <?php
                    }
                    else{
                        echo "<h1>Empty...</h1>";
                    }
                ?>

            </div>

        </form>

    </div>
                <!-- </center> -->



    <!-- footer section starts  -->

<section class="credit">

<p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK, NIVASHINI K, PRIYADHARSHIKA, RENUKA</span> | all rights reserved! </p>

</section>

<!-- footer section ends -->

<script src="js/complaint.js"></script>
<script src="js/script.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
    $("#complaints").hide();
    $("#complaint1").hide();

     $("#complaint").click(function() {
        $("#complaints").show();
        $("#complaint1").show();
        $("#complaint").hide();
        $("#p").hide();
        $("#div").hide();
        $("#btn").hide();
    });

    $("#complaint1").click(function() {
        $("#complaints").hide();
        $("#complaint1").hide();
        $("#complaint").show();
        $("#p").show();
        $("#div").show();
        $("#btn").show();
    });

    // $("#active").click(function() {
    //     $("#all-product").hide();
    //     $("#active-product").show();
    //     $("#close-product").hide();
    // });

    // $("#close").click(function() {
    //     $("#all-product").hide();
    //     $("#active-product").hide();
    //     $("#close-product").show();
    // });
</script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->


</body>
</html>