<?php

    include '../config/connect.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    // require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    // require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    // require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    // require '../../vendor/autoload.php';

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../vendor/autoload.php';

    //auction automatic close code
    date_default_timezone_set('Asia/Kolkata');
    $today=date('Y-m-d H:i:s');
    $today_date=date('Y-m-d H:i:s',strtotime($today));
    // echo $today_date;
    $yesterday_date=date('Y-m-d',strtotime('Yesterday'));
    $get_Status=mysqli_query($con,"SELECT * from auction where end_time<='$today_date' AND auction_status='active'");
    $type='';
   
    if(mysqli_num_rows($get_Status) > 0){

        while($fetchStatus=mysqli_fetch_assoc($get_Status)){

            
            $pid=$fetchStatus['p_id'];
            $aid=$fetchStatus['a_id'];
            $enddate=$fetchStatus['end_time'];
            $ending=date('Y-m-d H:i:s',strtotime($enddate));    
            $wining_bidder=$fetchStatus['wining_bidder'];
        //  echo $ending;


            //fetching product name and owner id
            $fetchProduct=mysqli_query($con,"SELECT product_name,owner from products where p_id='$pid'");
            $row1=mysqli_fetch_assoc($fetchProduct);
            $owner=$row1['owner'];
            $product_name=$row1['product_name'];

            //fetching owner email
            $ownerEmail=mysqli_query($con,"SELECT name,email from users where u_id='$owner'");
            $rowOwner=mysqli_fetch_assoc($ownerEmail);
            $auctioner=$rowOwner['email'];
            $auctionerName=$rowOwner['name'];

            if($ending < $today_date && $enddate !== $yesterday_date){
                // if($ending < $today_date && $enddate !== $yesterday_date){

                $update_status=mysqli_query($con,"UPDATE auction SET auction_status='close' where p_id='$pid' and a_id='$aid'");
                $update_status1=mysqli_query($con,"UPDATE auction SET auction_status='close' where p_id='$pid' and a_id='$aid'");

            }



            if($update_status){


                // echo "hello";

                //fetching all bidders of the product
                $selectBidders=mysqli_query($con,"SELECT * from bids where p_id='$pid' and a_id='$aid'");
                $selectBidders1=mysqli_query($con,"SELECT * from bids where p_id='$pid' and a_id='$aid'");

                while($row2=mysqli_fetch_assoc($selectBidders)){

                    $bidderId[]=$row2['u_id'];

                    //fetching bidders email 

                   

                    foreach($bidderId as $bid){

                        if($bid !== $wining_bidder){
                            $type='runnerUp';
                        }
                        elseif($bid == $wining_bidder){
                            $type='winner';
                        }

                        $selectEmail=mysqli_query($con,"SELECT email from users where u_id='$bid'");

                        while($row3=mysqli_fetch_assoc($selectEmail)){
                            $bidderEmail[]=$row3['email'];
                        }//while loop for get bidder emails

                    }

                    //if auction close send mails to winning bidders and runner up bidders
                    if($update_status){

                        foreach($bidderEmail as $bidder){

                            //send mail to runner up bidder
                            if($type == 'runnerUp'){
                                echo 'xkkx';
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
                                    $mail->addAddress($bidder);
                                                        // $mail->addAddress($email,$name);

                                    $mail->IsHTML(true);
                                    $mail->Subject ="Runner Up of $product_name Auction";
                                    $mail->Body ="Runner Up of $product_name Auction";
                                    $mail->AltBody ="You Are Runner Up of Product $product_name Auction.";
                                    $mail->MsgHTML("<h3>You Are Runner Up of Product $product_name Auction.<br> Try to bid for another Product Auction. </h3>");

                                    $mail->Send();

                            }//runner up bidder condition

                            //send mail to wining bidder
                            if($type == 'winner' ){
                                
                                $mail2=new PHPMailer(true);

                                $mail2->IsSMTP();
                                            
                                $mail2->Mailer="smtp";

                                $mail2->SMTPDebug =0;
                                $mail2->SMTPAuth =TRUE;
                                $mail2->SMTPSecure ='tls';
                                $mail2->Port =587;
                                $mail2->Host ="smtp.gmail.com";
                                    $mail2->Username ="zoobidsauctions@gmail.com";
                                    $mail2->Password ="andxxwkcomlqisan";

                                    $mail2->SetFrom('zoobidsauctions@gmail.com','Zoo Bids');
                                    $mail2->addAddress($bidder);
                                                        // $mail->addAddress($email,$name);

                                    $mail2->IsHTML(true);
                                    $mail2->Subject ="Winner of $product_name Auction";
                                    $mail2->Body ="Winner of $product_name Auction";
                                    $mail2->AltBody ="You Are Winner of Product $product_name Auction.";
                                    $mail2->MsgHTML("<h3>You Are Winner of Product $product_name Auction.<br> <br> Login and view Owner Details and Contact to owner of Product.</h3>");

                                    $mail2->Send();
                                    
                            }
                            

                        }//foreach loop for bidders

                        
                        $mail1=new PHPMailer(true);

                        $mail1->IsSMTP();
                                    
                        $mail1->Mailer="smtp";

                        $mail1->SMTPDebug =0;
                        $mail1->SMTPAuth =TRUE;
                        $mail1->SMTPSecure ='tls';
                        $mail1->Port =587;
                        $mail1->Host ="smtp.gmail.com";
                            $mail1->Username ="zoobidsauctions@gmail.com";
                            $mail1->Password ="andxxwkcomlqisan";

                            $mail1->SetFrom('zoobidsauctions@gmail.com','Zoo Bids');
                            $mail1->addAddress($auctioner);
                                                // $mail->addAddress($email,$name);

                            $mail1->IsHTML(true);
                            $mail1->Subject ="$product_name Auction is closed";
                            $mail1->Body ="$product_name Auction is closed";
                            $mail1->AltBody ="Your Product $product_name Auction is Closed.";
                            $mail1->MsgHTML("<h3>Your Product $product_name Auction is Closed.<br> Login and view details of wining bidder for your Product $product_name Auction.</h3>");

                            if($mail1->Send()){
                                if($ending < $today_date && $enddate !== $yesterday_date){

                                    $deleteRunnerUp=mysqli_query($con,"DELETE from bids where p_id='$pid' and a_id='$aid' and u_id!='$wining_bidder'");

                                }
                            }


                    }//if auction close send mails to winning bidders and runner up bidders

                }//while loop for bidder details

            }

        


        }//while loop for auction details
        //end of code for auction close

    }
    else{
    }

?>