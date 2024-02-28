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
    $email=$_SESSION['email'];

    if(empty($uid)){
        header('location:../index.php');
    }


    // if(!isset($_GET))

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

    // error_reporting(0);

    $user_data=mysqli_query($con,"SELECT * from users where u_id='$uid'");
    $disp=mysqli_fetch_assoc($user_data);
    $name=$disp['name'];
    $p_id=$_GET['p_id'];
    $qr=$_GET['qr'];

    if(empty($_GET['qr'])){
        header('location:payment.php');
    }


    //fetching user bid amount
    $sql2=mysqli_query($con,"SELECT bid_amount from bids where p_id='$p_id'");
    $disp1=mysqli_fetch_assoc($sql2);
    $bid_amount=$disp1['bid_amount'];
    // echo $bid_amount;

    //fetchin owner qrcode pic
    $qr=mysqli_query($con,"SELECT product_name,owner,price,paid_amount from products where p_id='$p_id'");

        $row2=mysqli_fetch_assoc($qr);
        $owner=$row2['owner'];
        $price=$row2['price'];
        $paid_amount=$row2['paid_amount'];
        $product_name=$row2['product_name'];


        $qrpic=mysqli_query($con,"SELECT users.email,users_imgs.qrcode_pic from users Inner Join users_imgs on users.u_id=users_imgs.u_id where users.u_id='$owner'");
        $row3=mysqli_fetch_assoc($qrpic);
        $owner_email=$row3['email'];

        // echo $owner_email;

        //end


    if(empty($uid)){
        header('location:../index.php');
    }

    // if($qr == 'true'){

        if(isset($_POST['done'])){


            $paydone=$_FILES['pay_done']['name'];
            $amount=$_POST['amount'];
            $sum=$amount+$paid_amount;
            $pending_amount=$bid_amount-$sum;
        // echo $sum;
        //     echo $pending_amount;

            $paydone_tmp=addslashes(file_get_contents($_FILES['pay_done']['tmp_name']));

            if(!empty($paydone_tmp) && !empty($amount)){

                if($sum > $bid_amount){
                    $err[]='You are paying more than the product bid price';
                }
                else{              
                
                    $update=mysqli_query($con,"UPDATE products SET googlepay_ss='$paydone_tmp',confirm='recieved',paid_amount='$sum' where p_id='$p_id'");
                
                    if($update){

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
                            $mail->addAddress($owner_email);
                                                // $mail->addAddress($email,$name);

                            $mail->IsHTML(true);
                            // $mail->addAttachment("$paydone","$paydone_tmp");
                            $mail->Subject ="Payment Recieved By QR Scanning On Your Auction Product";
                            // $mail->addEmbeddedImage("$paydone","Paydone SS","$paydone");
                            $mail->addAttachment($_FILES['pay_done']['tmp_name'],$_FILES['pay_done']['name']);


                            $mail->Body ="Payment Recieved By QR Scanning On Your Auction Product";
                            $mail->AltBody ="Your Auction product $product_name's Payment $amount is Recieved From Product Winning Bidder $name.Check Google Pay And Account Balance Confirm The Payment .....!!!. Pending Amount for Product is $pending_amount.";
                            $mail->MsgHTML("<p>Your Auction product $product_name Payment '".number_format($amount)."' is Recieved From Product Winning Bidder $name. <br><br> Check Your Google Pay Account Balance and Confirm The Payment Is Done OR Not .....!!! </h2><br>
                                            <p>Pending Amount for your Product is  '".number_format($pending_amount)."'.</p>
                                            <p>Total Paid Amount: '".number_format($sum)."'</p><br>
                                            <br><p>Please this email for later payment confirmation.</p>
                                            <br>Product Winner $name Uploaded bill image is:");

                            if(!$mail->Send()){
                                $err[]= "Error Sending Mail";
                            }
                            else{
                                echo 'ggg';

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
                                // $mail->addEmbeddedImage("$paydone","Paydone SS","$paydone");
                                $mail->addAttachment($_FILES['pay_done']['tmp_name'],$_FILES['pay_done']['name']);


                                $mail->IsHTML(true);

                                $mail->Subject ="Payment Using QR Scaning";

                                $mail->Body ="Payment Using QR Scaning";
                                $mail->AltBody ="$name Your Payment  is in confirmation for Product $product_name. Wait For Confirmation from owner of Product.You Get Email if Payment is Confirmed or Declined by owner...!!!";
                                $mail->MsgHTML("<p>$name Your Payment is in confirmation for Product $product_name.<br><br> Wait For Confirmation from owner of Product.<br><br>You Get Email if Payment is Confirmed or Declined by owner...!!!.</p> 
                                                <h2>Your Total paid amount is :- '".number_format($sum)."'</h2>
                                                <h2>Your Pending Amount :- '".number_format($pending_amount)."'</h2>
                                                <br><br><p>Your Uploaded Google Payment SS Is : </p>");


                                if(!$mail->Send()){
                                    $err[]="Error Sending Mail";
                                }
                                else{

                                    header('location:bids.php');
                                }
                            }
                    }
                    else{
                        $err[]="Payment Failed...!!";
                    }

                }
                
            }
            else{

                if(empty($paydone_tmp)){
                    $err[]="Please Select Payment Done Google Pay Screen Shot...!!";
                }
                else if(empty($amount)){
                    $err[]="Please Enter Amount...";
                }
                else{
                    $err[]="Both Required Field To Done Qr Scanning Payment";
                }
            }
        
        }


    // }

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
    <title>ONLINE AUCTION</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    .btn.btn-primary {
    background-color: white;
    color: black;
    box-shadow: none;
    border: none;
    font-size: 25px;
    width: fit-content;
    height: 6%;
}
.btn.btn-primary:hover{
    background-color:green;
}
    </style>

</head>
<body>
    
<header class="header">

    <a href="index.php" class="logo" style="text-decoration:none;">
        <table>
            <tr>
                <td><image src="images/logo.png" /></td>
                <td><span>ONLINE AUCTION</span></td>
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

</div>


<form method="post" id="qr_form" enctype="multipart/form-data" autocomplete='off' >

    <?php
        

        if(isset($err)){

            foreach($err as $error){
    ?>
        <div class='error-text'><h1><?php echo $error;?></h1></div>
    <?php
            }
        }
    ?>


    <img src="<?php echo 'data:image;base64,'.base64_encode($row3['qrcode_pic']).''?>" height="500px">

    <br><br>

    <div>
        <h3>Select Payment Done Google Pay ScreenShot :-</h3>
        <input type="file" name="pay_done" id="pay_done" class="field">
    </div>

    <div>
        <h3>Enter Amount You Pay :-</h3>
        <input type="text" name="amount" id="amount" class="field" pattern="[0-9]*" oninvalid="this.setCustomValidity('Enter Only Numbers')" oninput="this.setCustomValidity('')" placeholder="Enter Paid Amount">
    </div>
<center>
    <div>
    <button type="submit" name="done" class='btn btn-primary'>Done</button>
    <!-- </a> -->



<a href="payment.php">
<button id="back" class="btn btn-primary">Back</button>
</a>
</center>
</div>
</form>

<!-- footer section starts  -->


<br><br><br>
<section class="credit" align="center">

    <p> created by <span>TYBCA Student</span> | all rights reserved! </p>


</section>

<script src="js/script.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>
</html>