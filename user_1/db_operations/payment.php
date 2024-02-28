<?php
    include '../../config/connect.php';

    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../../vendor/autoload.php';

    $uid=$_SESSION['u_id'];

    $sql=mysqli_query($con,"SELECT name from users where u_id='$uid'");
    $row=mysqli_fetch_assoc($sql);

    $name=$row['name'];
    $cod=$_GET['cod'];
    $p_id=$_GET['p_id'];
    $sql2=mysqli_query($con,"SELECT products.product_name,users.email from products INNER JOIN users on products.owner=users.u_id where p_id='$p_id'");
    $row2=mysqli_fetch_assoc($sql2);
    $product_name=$row2['product_name'];
    $owner=$row2['email'];

    if(empty($uid)){
        header('location:../index.php');
    }

    if($cod == 'true'){

        $update=mysqli_query($con,"UPDATE products SET confirm='cod' where p_id='$p_id'");

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
                        $mail->addAddress($owner);
                                            // $mail->addAddress($email,$name);

                        $mail->IsHTML(true);
                        $mail->Subject ="Cash Payment on Your Product";
                        $mail->Body ="Cash Payment on Your Product";
                        $mail->AltBody ="Your $product_name is Winned By $name. $name will pay cash When Product is Get By $name.";
                        $mail->MsgHTML("<h2>Your $product_name is Winned By $name. </h2><h1>$name will pay amount Through cash When Product obtained by the $name.<h1>");

                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            header('location:../bids.php');
                        }
                    }else{
                        echo "error";
                    }
    }
    else if($cod == 'half'){

        $update=mysqli_query($con,"UPDATE products SET confirm='cod' where p_id='$p_id'");

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
                        $mail->addAddress($owner);
                                            // $mail->addAddress($email,$name);

                        $mail->IsHTML(true);
                        $mail->Subject ="Pending Payment Through Cash";
                        $mail->Body ="Pending Payment Through Cash";
                        $mail->AltBody ="Your Products $product_name Pending Payment is $name Pay Through Cash. $name will pay pending amount When Product is Get By $name.";
                        $mail->MsgHTML("<p>Your Products $product_name Pending Payment is $name Pay Through Cash.<br><br> $name will pay pending amount When Product is obtained By $name</p>");

                        if(!$mail->Send()){
                            echo "Error Sending Mail";
                        }
                        else{
                            header('location:../bids.php');
                        }

                    }else{
                        echo "Error...!!!";
                    }
                    
        
    }
?>