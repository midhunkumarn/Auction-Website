<?php
    session_start();

    include '../../config/connect.php';

    $u_id=$_SESSION['u_id'];

    $email=$_SESSION['email'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    // if(empty($uid)){
    //     header('location:index.php');
    // }

    $profile_pic=$_FILES['profile_pic']['name']; //Getting img name

    $qrcode=$_FILES['qrcode']['name'];


     //image extensions
     $extensions=array("jpeg","jpg","png");  //Valid Image Uploading Extensions

    $sql=mysqli_query($con,"SELECT * from users_imgs where u_id='$u_id'");


    if(mysqli_num_rows($sql) > 0){
        echo "image already uploaded";
    }
    else{
        
        if(isset($profile_pic) && isset($qrcode)){

            //Profile Pic
            $profile_size=$_FILES['profile_pic']['size']; //Getting img size
            $profile_tmpname=addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));
            $profile_type=$_FILES['profile_pic']['type']; //Getting img type

            $profile_explode=explode('.',$profile_pic);
            $profile_extension=strtolower(end($profile_explode));  //Getting img extension
        
            //QRCode
            $qrcodesize=$_FILES['qrcode']['size'];
            $qrcodetmpname=addslashes(file_get_contents($_FILES['qrcode']['tmp_name']));
            $qrcodetype=$_FILES['qrcode']['type'];

            $qrcode_explode=explode('.',$qrcode);
            $qrcode_extension=strtolower(end($qrcode_explode));

            if((in_array($profile_extension,$extensions) === true) && (in_array($qrcode_extension,$extensions) === true)){
            
                $time=time();
                $newprofilename=$profile_tmpname . $time;   //Creating a Unique Profile Image Name 
                $newqrcodename=$qrcodetmpname . $time;   //Creating a Unique Qrcode Image Name
    
                // echo $newprofilename;
                // echo $newqrcodename;
    
                $sql1=mysqli_query($con,"INSERT INTO users_imgs VALUES('$u_id','$newprofilename','$newqrcodename','false')");
    
                if($sql1){

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
                        $mail->Subject ="QRCode is in Verification";
                        $mail->Body ="QRCode is in Verification";
                        $mail->AltBody ="Your Uploaded QRCode Image is Veirfied By System First And Then You Are Able To Recieve Money On Your GOOGLE Pay Account Using QRCOde Scanning.";
                        $mail->MsgHTML("<h3>Your Uploaded QRCode Image is Veirfied By System First And Then You Are Able To Recieve Money On Your GOOGLE Pay Account Using QRCOde Scanning. </h1>");

                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            echo "image uploaded";
                        }

                }
                else{
                    echo "image not uploaded";
                }
    
            }else{
                echo "Select Images in extension of  ~ JPG,JPEG,PNG";
            }
        
        }
        else{
            echo "please select image";
        }
    }

?>