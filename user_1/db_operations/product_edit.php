<?php
    session_start();

    

    
    $uid=$_SESSION['u_id'];
    $email=$_SESSION['email'];

    $p_id=$_SESSION['p_id'];

    $product_name=$_POST['product_name'];
    $product_desc=$_POST['product_desc'];
    $end_time=$_POST['end_time'];
    $price=$_POST['price'];

    date_default_timezone_set('Asia/Kolkata');

    $today=Date('Y-m-d h:i:s');

    $today_date=date('Y-m-d h:i:s',strtotime($today));

    $ending=date('Y-m-d H:i:s',strtotime($end_time));


    //Selecting auction status of Product
    $select=mysqli_query($con,"SELECT auction_status from auction where p_id='$p_id'");

    $row=mysqli_fetch_assoc($select);

    $auction_status=$row['auction_status'];

    // if(isset($_POST['done_btn'])){
    //     unset($_SESSION['p_id']);
    //     header('location:product_history.php');
    // }
    if(!empty($product_name) && !empty($product_desc) && !empty($price)){

            $sql=mysqli_query($con,"UPDATE products SET product_name='$product_name',product_desc='$product_desc',price='$price' where p_id='$p_id'");

            // if(isset($_POST['active_product'])){

            if($auction_status == 'close'){

                    if($end_time > $today_date){

                    
                        $sql2=mysqli_query($con,"UPDATE auction SET start_time='$today',end_time='$end_time',auction_status='active' where p_id='$p_id'");

                        if($sql2){

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
                            $mail->Subject ="Product Auction Is Active Again";
                            $mail->Body ="Product Auction Is Active Again";
                            $mail->AltBody ="<h3>Hello User</h3> <br> <h3>Your Product of Name $product_name is Active again for An Auction Till Date $end_time.</h3>";
                            $mail->MsgHTML("<h3>Hello User</h3> <br> <h3>Your Product of Name $product_name is Active again for An Auction Till Date $end_time.</h3>
                                            <br><h3></h3>.");

                            if(!$mail->Send()){
                                echo "Error Sending Mail";
                            }
                            else{

                                unset($_SESSION['p_id']);

                                echo "success";
                            }
                            
                        }

                    }else{
                        echo "Select the date That is Greater Than Now Time...";
                    }

                
        
            }
            else{

                if($sql){
                    unset($_SESSION['p_id']);

                    echo "success";
                }

                // if(isset($_POST['edit_product'])){

                    // $mail=new PHPMailer(true);

                    // $mail->IsSMTP();
                    // $mail->Mailer="smtp";

                    // $mail->SMTPDebug =0;
                    // $mail->SMTPAuth =TRUE;
                    // $mail->SMTPSecure ='tls';
                    // $mail->Port =587;
                    // $mail->Host ="smtp.gmail.com";
                    // $mail->Username ="zoobidsauctions@gmail.com";
                    // $mail->Password ="andxxwkcomlqisan";

                    // $mail->SetFrom('zoobidsauctions@gmail.com','Zoo Bids');
                    // $mail->addAddress($email);
                    //                     // $mail->addAddress($email,$name);

                    // $mail->IsHTML(true);
                    // $mail->Subject ="Product Auction Details Updated";
                    // $mail->Body ="Product Auction Details Updated";
                    // $mail->AltBody ="Hello User We get Registration Request from email $email. Verify its you. Your OTP is $otp";
                    // $mail->MsgHTML("<h3>Hello User</h3> <br> <h3>Your Product of Name $product_name Deatils Are Updated Successfully.</h3>");

                    // if(!$mail->Send()){
                    //     echo "Error Sending Mail";
                    // }
                    // else{

                    //     unset($_SESSION['p_id']);

                    //     echo "success";
                    // }

                // }
                // else{
                //     echo "error";
                // }
                
            }

    }
    else{
        echo "All Field Required...";
    }


?>