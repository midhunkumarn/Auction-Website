<?php
    session_start();

    include ('../config/connect.php');

    $email=$_POST['emailid'];

    $password=md5($_POST['password']);

    $password1=$_POST['password'];

    if(!empty($email) && !empty($password)){



                $selectUser=mysqli_query($con,"SELECT * from users where email='{$email}' AND password='{$password}'");


               

            if(mysqli_num_rows($selectUser) > 0){

                $sql=mysqli_query($con,"SELECT account_status from users where email='$email'");
                $row=mysqli_fetch_assoc($sql);
                $account_status=$row['account_status'];
                


                if($account_status !== '0'){

                
                    $row=mysqli_fetch_assoc($selectUser);

                    if($row){

                        

                            $_SESSION['u_id']=$row['u_id'];
                            $_SESSION['email']=$row['email'];
                            $_SESSION['Verify_otp']=$row['otp'];

                            echo "success";

                    }else{

                        echo "Email or Password is Incorrect...!!!";
                        
                    }

                }else{
                    echo "Your Account blocked....";
                }

            }
            else if(!(mysqli_num_rows($selectUser) > 0)){

                $selectAdmin=mysqli_query($con,"SELECT * from admin where email='{$email}' AND password='{$password1}'");

                $row2=mysqli_fetch_assoc($selectAdmin);

                if($row2){
                    echo "admin";

                    $_SESSION['id']=$row2['id'];
                    $_SESSION['email']=$row2['email'];
                }
                else{
                    echo "Email or Password is Incorrect...!!!";
                }
                
            }
            else{
                echo "Email or Password is Incorrect...!!!";
            }

       
            
    }else{
        echo "Enter email and Password...!!!";
    }
?>