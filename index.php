<?php
  session_start();

  if(!empty($_SESSION['u_id'])){
    header('location:user/index.php');
  }
  elseif (!empty($_SESSION['id'])) {
    header('location:admin/index.php');
  }
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="css/style.css" />
    <title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

  </head>

  <body>

    <div class="container">

      <div class="forms-container">

        <div class="signin-signup">

          <form action="db_operation/login.php" class="sign-in-form" method="POST" autocomplete='off' >

            <h2 class="title">Sign in</h2>

            <div class="error-text">Error</div>

            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="text" placeholder="Enter Email" name="emailid" id="emailid" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required/>
            </div>

            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" class="password" placeholder="Password" title="Enter your 8 digit password" required />
              <i class="fas fa-eye-slash showhidepw"></i>
            </div>

            <!-- <div class="remember-me">
              <input type="checkbox" name="remember" id="remember" class="remember"/>
              <span>Remember Me</span>
            </div> -->

            <input type="submit" value="Login" class="signin solid" />

            <br><br><br>
            <p id="forgot">
              Forgot Password &nbsp;<span><a href="forgotpass.php">Click Here</a></span>
            </p>
           
          </form>

          <form action="./db_operations/signup.php" class="sign-up-form" method="POST" autocomplete='off'>

            <h2 class="title">Sign up</h2>

            <div class="error-text1">Error</div>

            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="name" id="name" placeholder="Your Full Name" required pattern="[a-zA-Z'-'\s]*"/>
            </div>

            <div class="input-field">
              <i class="fas fa-phone"></i>
              <input type="number_format" name="contact_no" required pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Enter Only 10 Digit Number')" oninput="this.setCustomValidity('')" id="contact_no" placeholder="Contact Number" />
            </div>

            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
            </div>

            <div class="textarea">
              <i class="fas fa-address-book"></i>
              <textarea name="address" id="address" placeholder="Enter Your Home Address" ></textarea>
            </div>

            <div class="input-field">
              <i class="fas fa-hashtag"></i>
              <input type="number_format" name="pincode" id="pincode" placeholder="Enter Your Area Pincode" title="Enter Numbers Only"/>
            </div>

            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" class="password1" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
              <i class="fas fa-eye-slash showhidepw1"></i>
            </div>

            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="confirmpass" class="confirmpass" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
              <i class="fas fa-eye-slash showhidepw2"></i>
            </div>

           

            <div>
              <div class="g-recaptcha" data-sitekey="6Ldz5a4jAAAAAET7b2huCPTvrMt-DMhG5yn5uoPi"></div>
            </div>

            <input type="submit" class="btn" value="Sign up" />
            
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Don't Have An Account ?</h3>
        <br>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
          <img src="img/login.svg" class="image" height="60%" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3> Already Sign UP ?</h3>
            <br>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
          <img src="img/signup.svg" class="image" height="60%" alt="" />
        </div>
      </div>
    </div>

    <script src="js/app.js"></script>
    <script src="js/register.js"></script>
    <script src="js/login.js"></script>
    <script src="js/show_password.js"></script>
  </body>
</html>
