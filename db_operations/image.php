<?php
    
    //Profile Pic
    $profile_pic=$_FILES['profile_pic']['name']; //Getting img name
    $profile_size=$_FILES['profile_pic']['size']; //Getting img size
    $profile_tmpname=$_FILES['profile_pic']['tmp_name'];
    $profile_type=$_FILES['profile_pic']['type']; //Getting img type
    $profile_explode=explode('.',$_FILES['profile_pic']['name']); //Getting img extension
    $profile_extension=strtolower(end($profile_pic));

    //QRCode
    $qrcode=$_FILES['qrcode']['name'];
    $qrcodesize=$_FILES['qrcode']['size'];
    $qrcodetmpname=$_FILES['qrcode']['tmp_name'];
    $qrcodetype=$_FILES['qrcode']['type'];
    $qrcode_explode=explode('.',$_FILES['qrcode_pic']['name']);
    $qrcode_extension=strtolower(end($qrcode));

    //image extensions
    $extensions=array("jpeg","jpg","png");  //Valid Image Uploading Extensions

     //checking file uploaded or not
                    // if(isset($profile_pic) && isset($qrcode)){

                    //     if((in_array($profile_ext,$extensions) === true) && (in_array($qrcode_ext,$extensions) === true)){

                    //         $time=time();
                    //         $newprofilename=$profile_pic . $time;   //Creating a Unique Profile Image Name 
                    //         $newqrcodename=$qrcode . $time;   //Creating a Unique Qrcode Image Name 

                    //         if((move_uploaded_file($newprofilename,$profilefolder)) ){

                    //         }
                    //     }

                    // }else{
                    //     echo "please select profile and qrcode image";
                    // }

                    
    //folder path for image uploads
    $profilefolder="/usr_imgs/profile/".$profile_pic;
//     $qrcodefolder="/usr_imgs/qrcode/".$profile_pic;

//     <!-- <div class="">
//     <i class="fas fa-image"></i>
//     <input type="file" name="profile_pic" oninvalid="this.setCustomValidity('Select Profile Image')" oninput="this.setCustomValidity('')" class="pic" id="profile_pic" />
//   </div>

//   <div class="">
//     <i class="fas fa-image"></i>
//     <input type="file" name="qrcode" oninvalid="this.setCustomValidity('Select QRcode Image')" oninput="this.setCustomValidity('')" class="pic" id="qrcode" />
//   </div> -->
?>