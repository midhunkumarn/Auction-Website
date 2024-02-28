<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="css/forgot.css" />
    <title>ONLINE AUCTION</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

  </head>

  <body>

    <div class="container">

      <div class="forms-container">

        <div class="signin-signup">

          <form action="#" class="forgot-password-form" autocomplete='off'>

            <h2 class="title">Forgot Password</h2>

            <div class="error-text">Error</div>


            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Hello" required />
            </div>

            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" class="password1" placeholder="New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
              <i class="fas fa-eye-slash showhidepw1"></i>
            </div>

            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="confirmpass" class="confirmpass" placeholder="Confirm New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
              <i class="fas fa-eye-slash showhidepw2"></i>
            </div>

            <input type="submit" class="forgot-pass" value="Forgot Password" />
            
          </form>
        </div>
      </div>

      <div class="panels-container">

        <div class="panel left-panel">

          <div class="content">

            <h3>Forgot Your Password</h3>

          </div>

          <img src="img/forgot.svg" class="image" height="80%" alt="" />

        </div>
      </div>

    </div>

    <script src="js/forgot_password.js"></script>
    <script src="js/show_password.js"></script>

  </body>
</html>
