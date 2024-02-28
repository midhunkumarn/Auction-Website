<?php
    session_start();

    include ('../config/connect.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../vendor/autoload.php';

    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $confirmpass=md5($_POST['confirmpass']);

    $forgot_pass_otp=mt_rand(1111,9999); //creating 4 digit otp

    $enc_otp=md5($forgot_pass_otp);

    if(!empty($email) && !empty($password)){

        $sql=mysqli_query($con,"SELECT * from users where email='$email'");

        if($password != $confirmpass){
            echo "Password Not Match......!!!";
        }
        else{
            if(mysqli_num_rows($sql) > 0){

                $row=mysqli_fetch_assoc($sql);
    
                if($row){
                    
                    if($password != $row['password']){

                        $sql2=mysqli_query($con,"UPDATE users SET otp='".md5($forgot_pass_otp)."' where email='$email'"); //set otp 

                        if($sql2){

                            // $row=mysqli_fetch_assoc($sql);

                            // if($row){
                                
                            // }

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
                            $mail->Subject ="Change Password Request";
                            $mail->Body ="Change Password Request";
                            $mail->AltBody ="We got Forgot Password Request From Your Email $email. Your Otp for forgot password is $forgot_pass_otp.";
                            $mail->MsgHTML("<h3>We got Forgot Password Request From Your Email $email</h3> <br>
                                            <h1>Your Otp for forgot password is $forgot_pass_otp.</h1>");

                            if(!$mail->Send()){
                                echo "Error Sending Mail";
                            }
                            else{
                                $_SESSION['u_id']=$row['u_id'];
                                $_SESSION['email']=$row['email'];
                                $_SESSION['otp']=$enc_otp;
                                $_SESSION['password']= $password; //set password to session for change after otp verification
                                
                                $email='';
                                $password='';
                                $confirmpass='';

                                echo "change password";
                            }
                                
                        }
                        else{
                            echo "Something Went Wrong...."; //if otp not set then message displayed
                        }
                        // $sql2=mysqli_query($con,"UPDATE users SET password='{$password}'");
                    }
                    else{
                        echo "Dont use Old Password";
                    }
    
                }
    
            }
            else{
                echo "Email Address not registered...!!!";
            }
        }

    }
    else{
        echo "Enter email and Password...!!!";
    }

?>