<?php
  //verify session code
  session_start();

  //if user verified dont show the verify page
  include ('config/connect.php');
  
  if(empty($_SESSION['u_id'])){
    echo "session not started";
    header('location:index.php');
  }

  // $u_id=$_SESSION['u_id'];
  
  // $qry=mysqli_query($con,"SELECT * from users where u_id='$u_id' AND otp='0'");
  
  // if(mysqli_num_rows($qry) > 0){
  //   $row=mysqli_fetch_assoc($qry);
  
  //   if($row){
  //     $_SESSION['verification']=$row['verification'];
  
  //     if($row['verification'] == "Verified"){
  //       header("location:user/index.php");
  //     }
  //   }

  // }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/verify.css" />
    <title>Sign in & Sign up Form</title>
  </head>

  <body>

    <div class="container">

      <div class="forms-container">

        <div class="otp">

          <form action="db_operations/otp.php" class="otp-form" method="POST"> 

            <h3>Verify Your Email <?php echo $_SESSION['email'];?></h3>

            <h2 class="title">Enter OTP</h2>

            <div class="error-text">Error</div>

            <div class="input-field">
              <input type="number" name="otp1" class="otp_field" placeholder="0" min="0" max="9" required onpaste="false"/>
              <input type="number" name="otp2" class="otp_field" placeholder="0" min="0" max="9" required onpaste="false"/>
              <input type="number" name="otp3" class="otp_field" placeholder="0" min="0" max="9" required onpaste="false"/>
              <input type="number" name="otp4" class="otp_field" placeholder="0" min="0" max="9" required onpaste="false"/>
            </div>
    
            <input type="submit" value="Verify Account" name="verifybtn" class="verifybtn" />
          
          </form>

        
        </div>
      </div>

      <div class="panels-container">

        <div class="panel left-panel">

          <div class="content">

            <h3>Verify Your Email Address</h3>
            <p>
              We Emailed You the Four Digit OTP Code To Enter the Code below to Verify Your Email Account...
            </p>
           
          </div>

          <img src="img/authentication.svg" class="image" height="60%" alt="" />

        </div>
      </div>
    </div>

    <script src="js/verify.js"></script>
    
  </body>
</html>
