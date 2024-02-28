<?php
    session_start();

    include '../config/connect.php';

    use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\Exception;
     use PHPMailer\PHPMailer\SMTP;

     require '../vendor/phpmailer/phpmailer/src/Exception.php';
     require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
     require '../vendor/phpmailer/phpmailer/src/SMTP.php';
     require '../vendor/autoload.php';


    $uid=$_SESSION['u_id'];

    if(empty($uid)){
        header('location:../index.php');
    }

    if(!isset($_GET['pid'])){
        header('location:product_history.php');
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


    
    $uid=$_SESSION['u_id'];
    $email=$_SESSION['email'];

    $p_id=$_GET['pid'];

    date_default_timezone_set('Asia/Kolkata');



    $product_data=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id where products.p_id='$p_id'");

    $display=mysqli_fetch_assoc($product_data);

    $auction_status=$display['auction_status'];

    //form request code start

    
    date_default_timezone_set('Asia/Kolkata');

    $today=date("Y-m-d h:i:s");


    if($_SERVER["REQUEST_METHOD"]=="POST"){

    // if(isset($_POST['edit_product'])){


        $product_name=$_POST['product_name'];
        $product_desc=$_POST['product_desc'];
        $end_time=$_POST['end_time'];
        $price=$_POST['price'];

        $today=Date('Y-m-d H:i:s');

        $today_date=date('Y-m-d H:i:s',strtotime($today));

        $ending=date('Y-m-d H:i:s',strtotime($end_time));

            $sql=mysqli_query($con,"UPDATE products SET product_name='$product_name',product_desc='$product_desc',price='$price' where p_id='$p_id'");


            if($auction_status !== 'active'){


                    if($end_time > $today_date){


                        $sql2=mysqli_query($con,"UPDATE auction SET start_time='$today',end_time='$end_time',auction_status='active' where p_id='$p_id'");

                        if($sql2){

                            $mail=new PHPMailer(true);

                            $mail->IsSMTP();
                            $mail->Mailer="smtp";

                            $mail->SMTPDebug =0;
                            $mail->SMTPAuth =TRUE;
                            $mail->SMTPSecure ='tls';
                            $mail->Port =587;
                            $mail->Host ="smtp.gmail.com";
                            $mail->Username ="mistrymadan699@gmail.com";
                            $mail->Password ="qmskesryhgwkihzw";

                            $mail->SetFrom('mistrymadan699@gmail.com','Auction System');
                            $mail->addAddress($email);
                                                // $mail->addAddress($email,$name);

                            $mail->IsHTML(true);
                            $mail->Subject ="Product Auction Is Active Again";
                            $mail->Body ="Product Auction Is Active Again";
                            $mail->AltBody ="<h3>Hello User</h3> <br> <h3>Your Product of Name $product_name is Active again for An Auction Till Date $end_time.</h3>";
                            $mail->MsgHTML("<h3>Hello User</h3> <br> <h3>Your Product of Name $product_name is Active again for An Auction Till Date $end_time.</h3>");

                            if(!$mail->Send()){
                                $err[]="Error Sending Mail";
                            }
                            else{

                                header('location:product_history.php');
                            }
                            
                        }

                    }else{
                        $err[]="Select the Future Date&Time Please to active auction again...";
                    }

                
        
            }
            else{

                if($sql){
                    header('location:product_history.php');
                }

                
            }

        }
        

    // }
    //form request code ends

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
    <title>ONLINE AUCTION</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- swiper css link  -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" /> -->

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/product_edit.css">
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
                <td><span>ONLINE AUCTION</span></td>
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
        <image src="images/Adding-Products.png">

      </div>
        <form action="#" method="post" class="addproduct-form"  autocomplete='off'>

            <h1>Edit Auctioned Product Details</h1>

            <?php
                if(!empty($err)){
                    foreach($err as $error){
                       echo "<div class='error-text'> $error</div>";
                    }

                }
            ?>


            <h1>Auction Status: <?php echo $auction_status; ?></h1>

            

            <div class="info">

            <input class="fname field" type="text" name="product_name" id="product_name" class="field" value='<?php echo $display['product_name'];?>'>
            <!-- 
            <select class="field" name="category" id="category">

                    <option value="<?php //echo $display['c_id'];?>"><?php //echo $row['category_name'];?></option>
                   
            </select> -->

                <select class="field" name="subcategory" id="subcategory">
                    <option value="<?php echo $display['subcategory'];?>" selected><?php echo $display['subcategory'];?></option>               
                </select>

            </div>

            <p>Product Description:</p>
            <div>
                <textarea placeholder="Message" name="product_desc" id="product_desc" class="field"><?php echo $display['product_desc'];?></textarea>
            </div>

            <div class="info">

                <?php
                    if($auction_status !== "active"){
                ?>
                    <input type="datetime-local" name="end_time" id="end_time" class="field"  >
                <?php
                    }
                ?>
                <input type="text" name="price" class="field" pattern="[0-9]*" oninvalid="this.setCustomValidity('Enter Only Numbers')" oninput="this.setCustomValidity('')" value="<?php echo $display['price'];?>">
            </div>

            <?php
                if($auction_status == "close"){  
                    echo "<button type='submit' class='btn' name='edit_product'>Click Here To Active Auction</button>";
                }
                else if($auction_status == 'active'){
            ?>
                <button type="submit" class="btn" name="edit_product" >Edit Auction</button>
            <?php
                }
            ?>   
        
        </form>
    </div>

    <div class="back-button">
        <a href='product_history.php'><button>Click Here To Back</button><a>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


</body>
</html>