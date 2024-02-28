<?php
    session_start();

    include '../../config/connect.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    $uid=$_SESSION['u_id'];

    $email=$_SESSION['email'];

    $complaint=$_POST['complaint_desc'];

    if(!empty($complaint)){

        $sql=mysqli_query($con,"INSERT INTO complaints (complaint_description,u_id) VALUES ('$complaint','$uid')");

        if($sql){

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
                        $mail->Subject ="Complaint Is Registered";
                        $mail->Body ="Complaint Is Registered";
                        $mail->AltBody ="Your Complaint is Registered Successfully.We will Inform You The Status of Your Complaint Through Email on $email ";
                        $mail->MsgHTML("<h2>Your Complaint is Registered Successfully.</h2><h2>We will Inform You The Status of Your Complaint Through Email on<h2> <h1> $email<h1>");

                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            echo "success";
                        }

        }
        else{
            echo "Complaint is Not Registered...!!!";
        }

    }
    else{
        echo "Please Enter Complaint Description";
    }
?>