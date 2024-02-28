<?php
    session_start();

    include '../../config/connect.php';

    $uid=$_SESSION['u_id'];
    $email=$_SESSION['email'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    $name=$_POST['name'];
    $contact_no=$_POST['contact_no'];
    $address=$_POST['address'];
    $pincode=$_POST['pincode'];

    //image extensions
    $extensions=array("jpeg","jpg","png");  //Valid Image Uploading Extensions

    

    $sql=mysqli_query($con,"UPDATE users SET name='$name',contact_no='$contact_no',address='$address',pincode='$pincode' WHERE u_id='$uid'");
       
        if($sql){

            // if(!empty($_FILES['profile_pic']['name'])  !empty($_FILES['qrcode_pic']['name'])){
                
                //profile pic
                // $profile_pic=$_FILES['profile_pic']['name'];

                //qrcode image
                $qrcode=$_FILES['qrcode_pic']['name'];

                //Profile Pic
                // $profile_size=$_FILES['profile_pic']['size']; //Getting img size

                // if(!empty($profile_pic)){
                //     $profile_tmpname=addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));
                // }
                // $profile_type=$_FILES['profile_pic']['type']; //Getting img type

                // $profile_explode=explode('.',$profile_pic);
                // $profile_extension=strtolower(end($profile_explode));  //Getting img extension

                 //QRCode
                $qrcodesize=$_FILES['qrcode_pic']['size'];

                if(!empty($qrcode)){
                    $qrcodetmpname=addslashes(file_get_contents($_FILES['qrcode_pic']['tmp_name']));
                } 

                $qrcodetype=$_FILES['qrcode_pic']['type'];

                $qrcode_explode=explode('.',$qrcode);
                $qrcode_extension=strtolower(end($qrcode_explode));

                //if both profile and qrcode update
                if(!empty($qrcodetmpname)){

                    // $profile_tmpname1=$profile_tmpname;
                    // $qrcodetmpname1=$qrcodetmpname;   

                    if((in_array($qrcode_extension,$extensions) === true)){

                        $datetime=date('Y-m-d h:i:sa');

                        // $newprofile_tmpname=$profile_tmpname;
                        $newqrcodepic_tmpname=$qrcodetmpname;
        
                        $image_update=mysqli_query($con,"UPDATE users_imgs SET qrcode_pic='$newqrcodepic_tmpname',verify='false' where u_id='$uid'");
        
                        if($image_update){
          
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
                                echo "success";
                            }

                        }
                        else{
                            echo "Please Try Again...";
                        }
                        
                    }
                    else{
                        echo "Please Select Image In format ~ JPG,PNG,JPEG";
                    }

                }
                else{
                    echo "success";
                }

        }else{
            echo "error";
        }

?>