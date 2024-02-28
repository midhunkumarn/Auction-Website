<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php';

include '../config/connect.php';

$uid = $_SESSION['u_id'];
$email=$_SESSION['email'];

if (empty($uid)) {
    header('location:../index.php');
}

if (empty($_GET['p_id'])) {
    header('location:auction.php');
}

$qry = mysqli_query($con, "SELECT * from users WHERE u_id='{$uid}'");

if (mysqli_num_rows($qry) > 0) {

    $row = mysqli_fetch_assoc($qry);

    if ($row) {

        if ($row['verification'] != 'Verified') {
            header('location:../verify.php');
        } else {

            $imgs = mysqli_query($con, "SELECT * from users_imgs WHERE u_id='{$uid}'");
            $res = mysqli_num_rows($imgs);

            if (empty($res)) {
                $_SESSION['u_id'] = $uid;
                header('location:imageupload.php');
            }

            if ($res > 0) {
                $row1 = mysqli_fetch_assoc($imgs);
            }
        }
    }
}

$p_id = $_GET['p_id'];
$product_data = mysqli_query($con, "SELECT * from products INNER JOIN auction on products.p_id=auction.p_id where products.p_id='$p_id' and auction.p_id='$p_id'");

$data = mysqli_fetch_assoc($product_data);
$highest_bid = $data['current_bid'];
$price = $data['price'];
$aid = $data['a_id'];

$select = mysqli_query($con, "SELECT * from bids where a_id='$aid' and u_id='$uid'");

if (mysqli_num_rows($select) > 0) {
    ?>
    <script>
        alert('You Already Bid for This Product...');
        location.href = 'product_detail.php?p_id=<?php echo $p_id; ?>';
    </script>
    <?php
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['bid_amount'])) {

            $bid_amount = $_POST['bid_amount'];

            $sql3 = mysqli_query($con, "SELECT * from auction where a_id='$aid' and p_id='$p_id'");
            $display = mysqli_fetch_assoc($sql3);
            $current_bid = $display['current_bid'] + 100;
            $value = $price * 70 / 100;

            if ($bid_amount >= $value) {
                if (!empty($current_bid)) {
                    if ($bid_amount > $current_bid) {
                        $sql2 = mysqli_query($con, "UPDATE auction SET current_bid='$bid_amount',wining_bidder='$uid' where a_id='$aid' and  p_id='$p_id'");
                        if ($sql2) {
                            $bid = mysqli_query($con, "INSERT INTO bids (bid_id,a_id,p_id,u_id,bid_amount) Values('','$aid','$p_id','$uid',$bid_amount)");
                           /* if ($bid) {

                                $mail=new PHPMailer(true);

                                $mail->IsSMTP();
                                        
                                $mail->Mailer="smtp";
        
                                $mail->SMTPDebug =0;
                                $mail->SMTPAuth =TRUE;
                                $mail->SMTPSecure ='tls';
                                $mail->Port =587;
                                $mail->Host ="smtp.gmail.com";
                                $mail->Username ="zoobidsauctions@gmail.com";
                                $mail->Password ="andxxwkcomlqisan";
        
                                $mail->SetFrom('zoobidsauctions@gmail.com','Zoo Bids');
                                $mail->addAddress($email);
                                                    // $mail->addAddress($email,$name);
        
                                $mail->IsHTML(true);
                                $mail->Subject = "Bid Placed on {$data['product_name']}";
                                $mail->Body = "You have placed a bid of $bid_amount on {$data['product_name']}.";
                                $mail->AltBody = "You have placed a bid of $bid_amount on {$data['product_name']}.";
        
                                if(!$mail->Send()){
                                    echo "Error Sending Mail";
                                } else {
                                    echo "Bid placed successfully. Email sent to bidder.";
                                }

                                header("location:product_detail.php?p_id=$p_id");
                            } else {
                                $errMsg[] = "Not Done";
                            }*/
                        } else {
                            $errMsg[] = "Error";
                        }
                    } else {
                        $errMsg[] = "Please Make Bid Higher than $current_bid";
                    }
                } else {
                    $sql2 = mysqli_query($con, "UPDATE auction SET current_bid='$bid_amount',wining_bidder='$uid' where a_id='$aid' and  p_id='$p_id'");
                    if ($sql2) {
                        $bid = mysqli_query($con, "INSERT INTO bids (bid_id,a_id,p_id,u_id,bid_amount) Values('','$aid','$p_id','$uid',$bid_amount)");
                        if ($bid) {
                            // Email sending logic (same as above)
                            header("location:product_detail.php?p_id=$p_id");
                        } else {
                            $errMsg[] = "Not Done";
                        }
                    } else {
                        $errMsg[] = "Error";
                    }
                }
            } else {
                $errMsg[] = "Price must be Greater than 70%($value) of $price";
            }
        } else {
            $errMsg[] = "Please Enter Bid Amount...";
        }
    }
}
$qry = mysqli_query($con, "SELECT * from users where u_id='$uid'");
$imgs1 = mysqli_query($con, "SELECT * from users_imgs where u_id='$uid'");
$row = mysqli_fetch_assoc($qry);
$row_img = mysqli_fetch_assoc($imgs1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE AUCTION</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- swiper css link  -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" /> -->

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/place_bid.css">
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

                        <?php 
                        echo $row['name'];
                        ?>
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
        <a href="product_history.php"> <i class="fas fa-angle-right"></i> History </a>
        <a href="complaint.php"> <i class="fas fa-angle-right"></i> Complaint</a>
        <a href="profile.php"> <i class="fas fa-angle-right"></i> Profile</a>
    </nav>

</div>

<!-- side-bar section ends -->

<div class="main-block">
      
        <form method="post" class="addproduct-form" autocomplete='off'>

            <h1>Bid Amount For Product</h1>

            <?php
                if(!empty($highest_bid)){
            ?>
            <h1>Highest Bid For This Product:<?php echo $highest_bid; ?></h1>
            <?php
                }
            ?>

            <?php
                if(isset($errMsg)){

                    foreach($errMsg as $error){
                       echo "<div class='error-text'> $error</div>";
                    }

                }
            ?>
            <div class="info">

            <input class="fname field" type="text" name="product_name" id="product_name" class="field" value="<?php echo $data['product_name'];?>" disabled>

            <div class="info">
                <!-- <input type="file" name="product_imgs" id="product_imgs" class="field" multiple> -->
                <input type="text" name="bid_amount" id="bid_amount" class="field" pattern="[0-9]*" oninvalid="this.setCustomValidity('Enter Only Numbers')" oninput="this.setCustomValidity('')" placeholder="Enter Price">
            </div>
        
            <button type="submit" class="btn" name="place_bid">Submit</button>

        </form>

    </div>

</div>


<!-- footer section starts  -->


<section class="credit">

<p> created by <span>TYBCA Student</span> | all rights reserved! </p>


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