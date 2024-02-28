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
    $a_id=$_GET['a_id'];
    // $p_id=$_GET['p_id'];

    // echo $a_id;

    $pid=mysqli_query($con,"SELECT products.p_id,products.confirm,auction.auction_status,auction.wining_bidder from products INNER JOIN auction on products.p_id=auction.p_id where products.owner='$uid' and auction.a_id='$a_id'");
    $sql=mysqli_query($con,"SELECT * from  auction INNER JOIN bids on auction.a_id=bids.a_id INNER JOIN products on auction.p_id=products.p_id where auction.a_id='$a_id'");
    $row=mysqli_fetch_assoc($pid);
    $p_id=$row['p_id'];
    $confirm=$row['confirm'];
    $auction_status=$row['auction_status'];
    // echo $auction_status;
    $wining_bidder=$row['wining_bidder'];


    $sendmail=false;

    if($auction_status == 'active'){

        // echo "fifenddjs";

        if(mysqli_num_rows($sql) > 0){

            while($row1=mysqli_fetch_assoc($sql)){

                $bidders[]=$row1['u_id'];

                $product_name=$row1['product_name'];

                $owner=$row1['owner'];

            }

            foreach($bidders as $bid){

                // echo $bid;

                $sql2=mysqli_query($con,"SELECT * from users where u_id='$bid'");

                $sql3=mysqli_query($con,"SELECT * from users where u_id='$owner'");

                $sql4=mysqli_query($con,"SELECT bid_amount from bids where u_id='$bid' and a_id='$a_id'");

                //fetch owner info
                $row3=mysqli_fetch_assoc($sql3);
                //fetch bid amount of bidder
                $row4=mysqli_fetch_assoc($sql4);
                //bid amount of every bidder
                $bid_amount=$row4['bid_amount'];
                //owner name of product
                $owner_name=$row3['name'];
                //sending mail to bidder of Product
                while($row2=mysqli_fetch_assoc($sql2)){
                    $email=$row2['email'];
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

                    $mail->IsHTML(true);
                    $mail->Subject ="Bidded Product Is Removed From Auction";
                    $mail->Body ="Bidded Product Is Removed From Auction";
                    $mail->MsgHTML("<h1>Hello User <h1> <br> <h3> Product That You have been Bidded on Bid Amount $bid_amount For $product_name is Removed BY Its Owner $owner_name.</h3> <br> <h1> Make Bid For Another Product....!!! </h1>");

                    if(!$mail->Send()){
                        echo "Error Sending Mail";
                    }
                    else{
                        $sendmail=true;
                    }

                }

            
            }
        }else{
            $remove1=mysqli_query($con,"DELETE from bids where p_id='$p_id'");
            $remove2=mysqli_query($con,"DELETE from auction where p_id='$p_id'");
            $remove3=mysqli_query($con,"DELETE from product_imgs where p_id='$p_id'");
            $remove4=mysqli_query($con,"DELETE from products where p_id='$p_id'");

            if($remove4){
                header('location:../product_history.php');
            }
            else{
                echo "<Script>";
                echo "alert('try again....')";
                echo "<Script>";
            }

        }
        

        if(($sendmail == true)){

            $remove1=mysqli_query($con,"DELETE from bids where p_id='$p_id'");

            if($remove1){

                $remove2=mysqli_query($con,"DELETE from auction where p_id='$p_id'");
                $remove3=mysqli_query($con,"DELETE from product_imgs where p_id='$p_id'");

                if($remove2 && $remove3 ){

                    $remove4=mysqli_query($con,"DELETE from products where p_id='$p_id'");

                    if($remove4){
                        header('location:../product_history.php');
                    }
                    else{
                        echo "<Script>";
                        echo "alert('try again....')";
                        echo "<Script>";
                    }

                }
                else{
                    echo "<Script>";
                    echo "alert('try again....')";
                    echo "<Script>";
                }
                
            }
            else{
                echo "<Script>";
                echo "alert('try again....')";
                echo "<Script>";
            }
        
        }else{
            echo "<Script>";
            echo "alert('try again....')";
            echo "<Script>";
        }

    }else{

        // echo $wining_bidder;

        $query=mysqli_query($con,"SELECT email from users where u_id='$wining_bidder'");

        if($query){

            $res=mysqli_fetch_assoc($query);

            // echo $confirm;


            $winner_email=$res['email'];

            // echo $winner_email;

            if(($confirm == 'pending') || ($confirm == 'cod')){

                // echo "peyment pending";

                // removing product bids
                $remove1=mysqli_query($con,"DELETE from bids where p_id='$p_id'");

                if($remove1){

                    $remove2=mysqli_query($con,"DELETE from auction where p_id='$p_id'");
            
                    $remove3=mysqli_query($con,"DELETE from product_imgs where p_id='$p_id'");
            
                        if($remove2 && $remove3 ){

                            $remove4=mysqli_query($con,"DELETE from products where p_id='$p_id'");

                            if($remove4){

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
                                $mail->addAddress($winner_email);
                                                            // $mail->addAddress($email,$name);

                                $mail->IsHTML(true);
                                $mail->Subject ="Bidded Product Is Removed From Auction";
                                $mail->Body ="Bidded Product Is Removed From Auction";
                                // $mail->AltBody ="Hello User";
                                $mail->MsgHTML("<h1>Hello User <h1> <br> <h3> Product That You have been Bidded on Bid Amount $bid_amount For $product_name is Removed BY Its Owner $owner_name.</h3> <br> <h1> Make Bid For Another Product....!!! </h1>");

                                if(!$mail->Send()){
                                    echo "Error Sending Mail";
                                }
                                else{
                                    header('location:../product_history.php');
                                }

                            }
                            else{
                                echo "<Script>";
                                echo "alert('try again....')";
                                echo "<Script>";
                            }
                        
                        }
                }//remove1
            

            }else if(($confirm == 'done') || ($confirm == 'recieved')){

?>

            <script>
                alert('You Can"t Remove Product.Product payment Recieved from bidder by google pay QR Scanning....!!.');
                location.href='../product_history.php';
            </script>

<?php

            }
        }
        else{
            echo "<Script>";
            echo "alert('try again....')";
            echo "<Script>";
        }

        // $remove1=mysqli_query($con,"DELETE from bids where p_id='$p_id'");


        //     if($remove1){

        //         $remove2=mysqli_query($con,"DELETE from auction where p_id='$p_id'");

        //         $remove3=mysqli_query($con,"DELETE from product_imgs where p_id='$p_id'");

        //         if($remove2 && $remove3 ){

        //             $remove4=mysqli_query($con,"DELETE from products where p_id='$p_id'");

        //             if($remove4){
        //                 header('location:../product_history.php');
        //             }
        //             else{
        //                 echo "Erroe";
        //             }

        //         }
        //         else{
        //             echo "Not Deleted...";
        //         }
                
        //     }
        //     else{
        //         echo "errorq";
        //     }
        
    }

?>