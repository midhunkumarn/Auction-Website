<?php
    session_start();

    include "../config/connect.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../vendor/autoload.php';

    $otp1=$_POST['otp1'];
    $otp2=$_POST['otp2'];
    $otp3=$_POST['otp3'];
    $otp4=$_POST['otp4'];

    $verification="Verified";


    $session_uId=$_SESSION['u_id']; //User id session veriable

    
    
    if(!empty($_SESSION['otp'])){
        $session_otp=$_SESSION['otp']; //Registration OTP Session Veriable
    }

    // echo $session_otp;

    $otp=$otp1.$otp2.$otp3.$otp4;

    $enc_otp=md5($otp);

    // echo $enc_otp;
    
    //Registration Verification Otp Validation
    if(!empty($otp)){

            //check otp with registration time session otp for Registered user verification

            // if($enc_otp == $session_otp){

                if(empty($_SESSION['password']) && empty($_SESSION['Verify_otp'])){

                    // echo "heloo";

                    if($enc_otp == $session_otp){

                        $sql=mysqli_query($con, "SELECT * from users where u_id='$session_uId' AND otp='$enc_otp'");

                        if(mysqli_num_rows($sql) > 0 ){ //if uid and session otp match 
                            $null_otp=0;    //send the otp value 0 its mean varified user
                            $sql2=mysqli_query($con,"UPDATE users SET otp='$null_otp',verification='$verification' WHERE u_id='$session_uId'");

                            if($sql2){

                                $row=mysqli_fetch_assoc($sql);

                                if($row){

                                    $email=$row['email'];

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

                                    $mail->SetFrom('zoobidsauctions@gmail.com','Auction System');
                                    $mail->addAddress($email);
                                                // $mail->addAddress($email,$name);

                                    $mail->IsHTML(true);
                                    $mail->Subject ="Verification Successed";
                                    $mail->Body ="Verified User";
                                    $mail->AltBody ="Congartulation.... \n Your registration for email $email is Successed And You Are Verified User. \n Enjoy Bidding.....!!!";
                                    $mail->MsgHTML("<h3>Congartulation.... <br> Your registration for email $email is Successed And You Are Verified User.</h3> <h1> Enjoy Bidding.....!!! </h1>");

                                    if(!$mail->Send()){
                                        echo "Error Sending Mail";
                                    }
                                    else{

                                        session_unset();
                                        session_destroy();

                                        $_SESSION['u_id']=$row['u_id'];
                                        $_SESSION['verification']= $row['verification'];
                                        
                                        echo "success";
                                    }
                                    
                                }
                                else{
                                    echo "Verification Failed....!!!";
                                }

                            }
                            else{
                                echo "Verification Failed....!!!";
                            }

                        }else{
                            echo "Verification Failed....!!!";
                        }
                    
                    }else{
                        echo "Wrong OTP";
                    }

                }
                // else if(!empty($_SESSION['password'])){
                else{

                    if(!empty($_SESSION['password'])){ 
                        
                        // echo "hello";
                        //Forgot Password 
                        if($enc_otp == $session_otp){

                            $password=$_SESSION['password'];    //Encrypted password
                            
                            $query=mysqli_query($con,"SELECT *  from users where u_id='$session_uId' AND otp='$enc_otp'");

                            if(mysqli_num_rows($query)){

                                $null_otp=0;

                                $query2=mysqli_query($con,"UPDATE users SET password='$password',otp='$null_otp' where u_id='$session_uId'");

                                if($query2){

                                    $row=mysqli_fetch_assoc($query);

                                    if($row){

                                        $email=$row['email'];

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

                                        $mail->SetFrom('zoobidsauctions@gmail.com','Auction System');
                                        $mail->addAddress($email);
                                                    // $mail->addAddress($email,$name);

                                        $mail->IsHTML(true);
                                        $mail->Subject ="Password Updated";
                                        $mail->Body ="Password Updated";
                                        $mail->AltBody ="Your Password Changed Successfully. Now try Your New Password";
                                        $mail->MsgHTML("<h2> Your Password Changed Successfully.</h2>");

                                        if(!$mail->Send()){
                                            echo "Error Sending Mail";
                                        }
                                        else{

                                            session_unset();
                                            session_destroy();

                                            $_SESSION['u_id']=$row['u_id'];
                                            $_SESSION['verification']= $row['verification'];
                                            
                                            echo "success";
                                        }

                                    }else{
                                        echo "Verification Failed....!!!";
                                    } //$row if condition

                                }
                                else{
                                    echo "Verification Failed....!!!";
                                }
                                
                            }
                            else{
                                echo "Verification Failed....!!!";
                            }

                        }else{
                            echo "Wrong OTP";
                        }

                    }
                    elseif(!empty($_SESSION['Verify_otp'])){

                        // echo "hello";

                        $verify_otp=$_SESSION['Verify_otp'];

                        // echo $verify_otp;

                        if($enc_otp == $verify_otp){

                            $sql=mysqli_query($con, "SELECT * from users where u_id='$session_uId' AND otp='$enc_otp'");
    
                            if(mysqli_num_rows($sql) > 0 ){ //if uid and session otp match 

                                $null_otp=0;    //send the otp value 0 its mean varified user
                                $sql2=mysqli_query($con,"UPDATE users SET otp='$null_otp',verification='$verification' WHERE u_id='$session_uId'");
    
                                if($sql2){
    
                                    $row=mysqli_fetch_assoc($sql);
    
                                    if($row){
    
                                        $email=$row['email'];
    
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
    
                                        $mail->SetFrom('zoobidsauctions@gmail.com','Auction System');
                                        $mail->addAddress($email);
                                                    // $mail->addAddress($email,$name);
    
                                        $mail->IsHTML(true);
                                        $mail->Subject ="Verification Successed";
                                        $mail->Body ="Verified User";
                                        $mail->AltBody ="Congartulation.... \n Your registration for email $email is Successed And You Are Verified User. \n Enjoy Bidding.....!!!";
                                        $mail->MsgHTML("<h3>Congartulation.... <br> Your registration for email $email is Successed And You Are Verified User.</h3> <h1> Enjoy Bidding.....!!! </h1>");
    
                                        if(!$mail->Send()){
                                            echo "Error Sending Mail";
                                        }
                                        else{
                                            
                                            unset($_SESSION['Verify_otp']);

                                            $_SESSION['u_id']=$row['u_id'];
                                            // $_SESSION['verification']= $row['verification'];
                                            $_SESSION['email']= $row['email'];
                                            
                                            echo "Verified";
                                        }
                                        
                                    }
                                    else{
                                        echo "Verification Failed....!!!";
                                    }
    
                                }
                                else{
                                    echo "Verification Failed....!!!";
                                }
    
                            }else{
                                echo "Verification Failed....!!!";
                            }
                        
                        }else{
                            echo "Wrong OTP";
                        }
                    }
                
                }
                
            // }
            // else{
            //     echo "wrong otp";
            // }

    }
    else{
        echo "Enter OTP!!!";
    }
?>