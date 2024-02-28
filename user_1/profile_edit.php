<?php
    session_start();

    include '../config/connect.php';

    $uid=$_SESSION['u_id'];

	if(empty($uid)){
        header('location:../index.php');
    }

	if(empty($_GET['profileedit'])){
		header('location:profile.php');
	}

    $qry=mysqli_query($con,"SELECT * from users where u_id='$uid'");

	$imgs=mysqli_query($con,"SELECT * from users_imgs where u_id='$uid'");

    $row=mysqli_fetch_assoc($qry);

    $row_img=mysqli_fetch_assoc($imgs);

	$verify=$row_img['verify'];

    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOO BIDS</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	
	<!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- cusom css file link  -->
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    
<!-- header section starts  -->


<header class="header">

    <a href="index.php" class="logo" style="text-decoration:none;">
        <table>
            <tr>
                <td><image src="images/logo.png" /></td>
                <td><span>ZOO BIDS</span></td>
            </tr>
        </table>
    </a>

    <table class="button-header">

        <tr>

            <td>
                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                </div>
            </td>

            <td>

            <ul>
                
                <!-- <li><a href="#">Update</a></li> -->
                <li>

                    <a style="color:white;">
                        <img src="<?php echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>" />

                        <?php echo $row['name'];?>
                    </a>
                    <ul class="dropdown">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="db_operations/logout.php?logout_id=<?php echo $uid;?>">Log out</a></li>
                    </ul>
                </li>
            </ul>

            </td>

        </tr>

    </table>

</header>
<!-- header section ends -->

<!-- side-bar section starts -->

<div class="side-bar">

    <div id="close-side-bar" class="fas fa-times"></div>

    <div class="user">
        <img src="<?php echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>" height="100" width="100" />'
        <h3><?php echo $row['name'];?></h3>
    </div>

    <nav class="navbar1">
    	<a href="index.php"> <i class="fas fa-angle-right"></i> Home </a>
        <a href="auction.php"> <i class="fas fa-angle-right"></i> Auction </a>
        <a href="bids.php"> <i class="fas fa-angle-right"></i> Bids </a>
        <a href="product_history.php"> <i class="fas fa-angle-right"></i>History </a>
        <a href="complaint.php"> <i class="fas fa-angle-right"></i> Complaint</a>
        <a href="profile.php"> <i class="fas fa-angle-right"></i> Profile</a>
    </nav>

</div>

<!-- <div class="profile-container"> -->

		<!-- Profile Update Form Fields -->

		<form action="db_operation/profile_update.php" method="post" class="profile-data" method="POST" enctype="multipart/form-data" >

		<div class="row col-lg-8 rounded mx-auto mt-5 p-2">

		<div class="error-text"></div>

			<div class="col-md-4 text-center">
				<img src="<?php echo 'data:image;base64,'.base64_encode($row_img['profile_pic']).''?>"  class="js-image img-fluid rounded profile-img" id="profile_pic" name="profile_pic" style="background-blend-mode:multiply;">
				<div>
					<!-- <div class="mb-3">
					  <label for="formFile" class="form-label">Click below to select an image</label>
					  <input onchange="display_image(this.files[0])" class="js-image-input form-control" type="file" name="profile_pic" id="profile_pic">
					</div> -->
				</div>
			</div>

			<div class="col-md-8">
				
				<div class="h2">Edit Profile</div>

					<table class="table table-striped">

					<tr colspan="2"> <th  class="text-center">User Details:</th> <td></td> </tr>

						<tr>
							<th style="display:flex;text-align:center;"><span class="material-icons-outlined ">email</span> Email</th>

							<td>
								<?php echo $row['email']; ?>
								<!-- <input value="" type="text" class="form-control" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" > -->
							</td>
						</tr>

						<tr>
							<th><span class="material-icons-outlined ">badge</span> Full Name</th>

							<td>
								<input value="<?php echo $row['name']; ?>" type="text" class="form-control" name="name"">
							</td>
						</tr>

						<tr>
							<th><span class="material-icons-outlined ">phone_iphone</span> Contact</th>

							<td>
								<input value="<?php echo $row['contact_no']; ?>" type="text" class="form-control" name="contact_no" pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Enter Only 10 Digit Number')" oninput="this.setCustomValidity('')" id="contact_no">
							</td>
						</tr>

                        <tr>
							<th><span class="material-icons-outlined ">edit_note</span> Address</th>

							<td>
								<textarea  class="form-control" name="address"><?php echo $row['address']; ?></textarea>
							</td>
						</tr>

                        <tr>
							<th><span class="material-icons-outlined ">tag</span> Pincode</th>

							<td>
								<input value="<?php echo $row['pincode']; ?>" type="text" class="form-control" name="pincode" id="pincode">
							</td>
						</tr>

                        <tr id="city-state" colspan="2">
							
						</tr>

                        <tr>
							<th><span class="material-icons-outlined ">qr_code_scanner</span> QRCode</th>

							<td>
								<?php
								if($verify == 'false'){
									echo "<h3>Your QR Is In Verification.</h3>";
								}else{
								?>

                                	<img src="<?php echo 'data:image;base64,'.base64_encode($row_img['qrcode_pic']).''?>" class="js-image1 img-fluid rounded profile-img" id="profile_pic" name="profile_pic">


								<?php
								}
								?>

                                <div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Click below to select an image</label>
                                        <input onchange="display_qrcodeimage(this.files[0])" class="js-image-input form-control" type="file" name="qrcode_pic" id="qrcode_pic">
                                    </div>
                                </div>
							</td>
						</tr>

					</table>

					<!-- <div class="progress my-3 d-none">
					  <div class="progress-bar" role="progressbar" style="width: 50%;" >Working... 25%</div>
					</div> -->

					<div class="p-2">
						
						<button type="submit" name="update_profile" class="save btn btn-primary float-end">Save</button>
						
						<a href="profile.php">
							<label class="btn btn-secondary">Back</label>
						</a>

					</div>
				</form>

			</div>
		</div>
<!-- </div> -->



<!-- footer section starts  -->



<section class="credit">

    <p> created by <span>MIDHUNKUMAR N, RAVIRAGAV NM, KAVIYAN MK, NIVASHINI K, PRIYADHARSHIKA, RENUKA</span> | all rights reserved! </p>


</section>

<!-- footer section ends -->

<script src="js/script.js"></script>
<script src="js/profile_update.js"></script>

<script>
var image_added = false;

function display_image(file)
{
	var img = document.querySelector(".js-image");
	img.src = URL.createObjectURL(file);

	image_added = true;
}

function display_qrcodeimage(file)
{
	var img = document.querySelector(".js-image1");
	img.src = URL.createObjectURL(file);

	image_added = true;
}
	
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

<script>
$(document).ready(function() {
	$('#pincode').on('change', function() {
			var pincode = this.value;
			$.ajax({
				url: "db_operations/getcity.php",
				type: "POST",
				data: {
					pincodes: pincode
				},
				cache: false,
				success: function(dataResult){
					$("#city-state").html(dataResult);
				}
			});
		
		
	});
});
</script>


</body>
</html>