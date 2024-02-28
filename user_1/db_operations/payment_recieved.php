<?php
    session_start();

    $uid=$_SESSION['u_id'];

    include '../../config/connect.php';

    $recieve=$_GET['recieve'];
    $winning_bidder=$_GET['wining_bidder'];
    $pid=$_GET['p_id'];


    $sql=mysqli_query($con,"SELECT email from users where u_id='$winning_bidder'");
    $res=mysqli_fetch_assoc($sql);

    $sql1=mysqli_query($con,"SELECT product_name,price,paid_amount from products where p_id='$pid'");
    $res2=mysqli_fetch_assoc($sql1);

    $sql2=mysqli_query($con,"SELECT bid_amount from bids where p_id='$pid'");
    $res3=mysqli_fetch_assoc($sql2);

    $email=$res['email'];
    $product_name=$res2['product_name'];
    $paid_amount=$res2['paid_amount'];
    $price=$res2['price'];
    $bid_amount=$res3['bid_amount'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    if($recieve !== 'false'){

        // echo "hello";

        if($paid_amount == $bid_amount){
            $update=mysqli_query($con,"UPDATE products set confirm='done' where p_id='$pid'");
        }
        else if($paid_amount < $bid_amount){
            $update=mysqli_query($con,"UPDATE products set confirm='recieved' where p_id='$pid'");
        }


        if($update){
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
                        $mail->Subject ="Payment Chacked and Confirmed By Owner";
                        $mail->Body ="Payment Chacked and Confirmed By Owner";
                        $mail->AltBody ="Your Payment is Confirmed By Owner of Product $product_name.";
                        $mail->MsgHTML("<h3>Your Payment is Confirmed By Owner of Product $product_name.</h3><br>
                                        <h3>Contact to the Owner to get Product $product_name.</h3>");
                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            header("location:../product_view.php?p_id=$pid");
                        }
        }else{
            echo "Try Again Later...!!!";
        }

    }
    else{
        // echo "bye";
        $update=mysqli_query($con,"UPDATE products set confirm='pending' where p_id='$pid'");

        if($update){

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
                        $mail->Subject ="Payment is Not confirmed";
                        $mail->Body ="Payment is Not confirmed";
                        // $mail->AltBody ="Your Google Pay Payment Screenshot Not Uploaded Original.";
                        $mail->MsgHTML("<h2>Your Payment is Not confirmed by owner....!!.<br> Your uploaded Google Pay Payment Screenshot is not correct. <br> If something wrong Then Talk To Owner of Product.</h2>");
                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            header("location:../product_view.php?p_id=$pid");
                        }
        }else{
            echo "try again later";
        }
    }
?>