<?php
    session_start();

    include "../config/connect.php";

    echo "<script>alert('Upload Your Profile Pic And Google Pay QRCode Image....')</script>";

    $u_id=$_SESSION['u_id'];

    $sql=mysqli_query($con,"SELECT name from users where u_id='$u_id'");
    $row=mysqli_fetch_assoc($sql);

    $sql2=mysqli_query($con,"SELECT * from users_imgs where u_id='$u_id'");

    if(mysqli_num_rows($sql2) > 0){
        header('location:index.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE AUCTION</title>
    <link rel="website icon" type='png' href="images/logo.png"/>

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    
    <!-- scss link -->
    <link rel="stylesheet" href="css/image_upload.css">
    


    <!-- Bootstrap CDN -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->

    <title>Image Upload</title>

</head>
<body>
    <form action="db_operations/image_uploads.php" method="post" enctype="multipart/form-data" class="image-upload-form">

        <div class="form">

            <h2> <?php echo $row['name']; ?> Please Upload Profile Picture AND QRcode IMAGE</h2>

            <div class="error-text">Error</div>

            <!-- image upload grid -->
            <div class="grid">
                
                <!-- Profile Pic -->
                <div class="form-element">
                    <h1>Profile Pic</h1>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                    <label for="profile_pic" id="profile_pic-preview">
                        <img src="https://bit.ly/3ubuq5o" alt="Please Select Profile Pic">
                        <div>
                        <span>+</span>
                        </div>
                    </label>
                </div>

                <div class="form-element">
                    <h1>QRCode Image</h1>
                    <input type="file" name="qrcode" id="qrcode" accept="image/*">
                    <label for="qrcode" id="qrcode-preview">
                        <img src="https://bit.ly/3ubuq5o" alt="Please Select QRcode Image">
                        <div>
                        <span>+</span>
                        </div>
                    </label>
                </div>

                <!-- <div class="form-element">
                    <input type="file" id="file-3" accept="image/*">
                    <label for="file-3" id="file-3-preview">
                        <img src="https://bit.ly/3ubuq5o" alt="">
                        <div>
                        <span>+</span>
                        </div>
                    </label>
                </div> -->

            </div>

            <!-- <div class="button> -->
            <!-- </div> -->

        </div>

        <!-- image upload grid -->
        <div class="button">
            <button type="submit" name="upload" class="btn">Upload Images </button>
        </div>

        <!-- <button type="submit" class="btn btn-primary btn-lg">Large button</button> -->

    </form>
   

    <script  src="js/image_upload.js"></script>

    <script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>
</body>
</html>