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

    // inclusion ("include_path='D:\xampp\php\PEAR'") ; 

    $name=$_POST['name'];
    $contact_no=$_POST['contact_no'];
    $email=$_POST['email'];
    $address=$_POST['address'];
    $pincode=$_POST['pincode'];
    $account_status='1';
    $verification='Unverified';

    //encrypting Password
    $password=md5($_POST['password']);
    $confirmpass=md5($_POST['confirmpass']);

    $secret="6Ldz5a4jAAAAAPKzn4_VYewKmUZ_AA4u1beteoY9";
    $captcha=$_POST['g-recaptcha-response'];
    $ip=$_SERVER['REMOTE_ADDR'];
    $url="https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip=$ip";
    $data=file_get_contents($url);
    $row=json_decode($data,true);

    //checking fields are not empty
    if(!empty($name) && !empty($contact_no) && !empty($email) && !empty($address) && !empty($pincode)  && !empty($password) && !empty($confirmpass)){

        //if email is Valid
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){

            //checking email is already exist or not
            $sql=mysqli_query($con,"SELECT email from users where email='{$email}'");

            $contact=mysqli_query($con,"SELECT contact_no from users where contact_no='{$contact_no}'");

            if(mysqli_num_rows($sql) >0){  //Check for email already exist or not
                echo "$email ~ Already Exist";
            }
            else if(mysqli_num_rows($contact) > 0){ //Check for Contact No is alraedy used or not
                echo "$contact_no ~ Already Exist";
            }
            else{

                if($row['success']=="true"){
                //checking password and confirm password match
                
                    if($password == $confirmpass){

                        $otp=mt_rand(1111,9999); //creating 4 digit otp

                        $enc_otp=md5($otp);

                        $sql2=mysqli_query($con,"INSERT into users VALUES(null,'$name','$contact_no',
                                            '$email','$address','$pincode','$password','$account_status','".md5($otp)."','$verification')");

                        if($sql2){

                            $sql3=mysqli_query($con,"SELECT * from users where email='$email'");

                            if(mysqli_num_rows($sql3) > 0){

                                $row=mysqli_fetch_assoc($sql3); //fetch data

                                //start mail function
                                if($otp){

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
                                    $mail->Subject ="Verification for Email";
                                    $mail->Body ="Verify Your Email Accout";
                                    $mail->AltBody ="Hello User We get Registration Request from email $email. Verify its you. Your OTP is $otp";
                                    $mail->MsgHTML("<h3>Hello User</h3> <br> <h3>We get Registration Request from email $email.</h3> <br> <h3> Verify its you.</h3> <br> <h1>Your OTP is $otp<h1>");

                                    if(!$mail->Send()){
                                        echo "Error Sending Mail";
                                    }
                                    else{

                                        //Set Value null after form submit
                                        $name="";
                                        $contact_no="";
                                        $email="";
                                        $address="";
                                        $pincode="";
                                        $password="";
                                        $confirmpass="";


                                        $_SESSION['u_id']=$row['u_id'];
                                        $_SESSION['email']=$row['email'];
                                        $_SESSION['otp']=$enc_otp;
                                        
                                        echo "success";
                                    }          
                                }
                                //mail function end
                            }
                            else{
                                echo "Don't Recognize Email Account...!!!";
                            }

                        }else{
                            echo "Registration Failed....!!!Try Again";
                        }
                    }
                    else{
                        echo "Confirm Password Don't Match";
                    }
                    // password check ending
                }else{
                    echo "Human Verification Failed....!!!";
                } //I am not robot recaptch verification
            }
            // email and Mobile Number check end

        }else{
            echo "$email ~ This is not Valid Email";
        }
    }
    else{
        echo "All Input Fields Are Required";
    }
    
?>