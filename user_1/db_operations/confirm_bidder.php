<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    $uid=$_SESSION['u_id'];

    include '../../config/connect.php';

    //auction automatic close code
    date_default_timezone_set('Asia/Kolkata');
    $today=date('Y-m-d H:i:s');
    $today_date=date('Y-m-d H:i:s',strtotime($today));

    if(empty($uid)){
        header('location:../index.php');
    }

    $uid=$_GET['id'];
    $pid=$_GET['p_id'];

    $delete=$_GET['delete'];
    $sql=mysqli_query($con,"SELECT email from users where u_id='$uid'");
    $sql1=mysqli_query($con,"SELECT product_name,confirm from products where p_id='$pid'");
    $row=mysqli_fetch_assoc($sql);
    $row1=mysqli_fetch_assoc($sql1);

    //fetch bidder bid amount
    $fetch_bid=mysqli_query($con,"SELECT bid_amount from bids where u_id='$uid'");
    $row3=mysqli_fetch_assoc($fetch_bid);
    $bidder_bid=$row3['bid_amount'];
    $email=$row['email'];
    $product_name=$row1['product_name'];
    $payment_confirm=$row1['confirm'];

    if($delete !== 'false'){

        if($payment_confirm == 'pending'){


            // $delete_bidders=mysqli_query($con,"SELECT * FROM bids where u_id!='$uid'");
            // $delete_bidders=mysqli_query($con,"SELECT * FROM bids where u_id!='$uid' and p_id='$pid'");
            $delete_bidders=mysqli_query($con,"DELETE FROM bids where u_id='$uid' and p_id='$pid'");
            $update_bidder=mysqli_query($con,"UPDATE auction SET current_bid='0',wining_bidder='0' where p_id='$pid'");

            if($delete_bidder && $update_bidder){
                echo "done";
    
    
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
                $mail->Subject ="You Are Removed from Bid By Owner Of Product $product_name";
                $mail->Body ="You Are Removed from Bid By Owner Of Product $product_name";
                $mail->AltBody ="You are Removed from bid of Product $product_name by Owner Because Your Bid Amount is Not Accepted to owner.";
                $mail->MsgHTML("<h3>You are Removed from bid of Product $product_name by Owner Because Your Bid Amount is Not Accepted to owner.</h3>");
    
                if(!$mail->Send()){
                    echo "Error Sending Mail";
                }
                else{
                    header("location:../product_view.php?p_id=$pid");
                }
    
            }
    
            

        }
        else{
?>
            <script>
                                alert('You cant change bidder.Payment is Recieved or Done By wining bidder...');
                                location.href='../product_view.php?p_id=<?php echo $pid; ?>';
            </script>
                        

<?php
        }
     
    }
    else{

    
        ?>
                        
                                <script>
                                alert('You cant remove bidder.Payment is Recieved or Done By bidder...');
                                location.href='../product_view.php?p_id=<?php echo $pid; ?>';
                                </script>
                        
        <?php
    
}

?>