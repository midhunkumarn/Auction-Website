<?php
session_start();

$uid = $_SESSION['u_id'];
$email = $_SESSION['email'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/autoload.php';

include '../../config/connect.php';

$product_name = $_POST['product_name'];
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];
$product_desc = $_POST['product_desc'];
$productid = uniqid();

date_default_timezone_set('Asia/Kolkata');
$end_time = $_POST['end_time'];
$ending = date('Y-m-d H:i:s', strtotime($end_time)); // Corrected date format
$today = date('Y-m-d H:i:s');
$today_date = date('Y-m-d H:i:s', strtotime($today));
$price = $_POST['price'];
$auction_status = "active";
$insert = false;

$extensions = array("jpeg", "jpg", "png"); // Valid image uploading extensions

if (!empty($product_name) && !empty($category) && !empty($subcategory) && !empty($product_desc) && !empty($end_time) && !empty($price)) {

    if ($ending > $today_date) {

        foreach ($_FILES['product_imgs']['name'] as $key => $image) {
            $product_img = $_FILES['product_imgs']['name'][$key];
            $product_img_size = $_FILES['product_imgs']['size'][$key];
            $product_img_tmpname = addslashes(file_get_contents($_FILES['product_imgs']['tmp_name'][$key]));
            $product_img_type = $_FILES['product_imgs']['type'][$key];

            $product_img_explode = explode('.', $product_img);
            $product_img_extension = strtolower(end($product_img_explode));

            if (in_array($product_img_extension, $extensions) === true) {
                if (count($_FILES['product_imgs']['name']) <= 3 && count($_FILES['product_imgs']['name']) >= 1) { // Fixed condition
                    $sql3 = mysqli_query($con, "INSERT into product_imgs Values('$productid','$product_img_tmpname')");
                    $insert = true;
                } else {
                    echo "Please Select 1 to 3 Images"; // Updated message
                    $insert = false; // Set insert to false
                    break;
                }
            } else {
                echo "Please Select Files in JPG, PNG, JPEG"; // Updated message
                $insert = false; // Set insert to false
                break;
            }
        }

        if ($insert) { // Check insert value

            $sql = mysqli_query($con, "INSERT into products (p_id,product_name,product_desc,category,subcategory,price,owner) Values ('$productid','$product_name','$product_desc','$category','$subcategory','$price','$uid')");

            $sql2 = mysqli_query($con, "INSERT into auction (a_id,p_id,end_time,auction_status) Values (null,'$productid','$ending','$auction_status')"); // Use $ending
            $insert = true;

            $mail = new PHPMailer(true);

            
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
                        $mail->Subject ="$product_name Added To An Auction";
                        $mail->Body ="$product_name Added to An Auction";
                        $mail->AltBody ="Your Product $product_name is Added to an Auction With Base Price $price";
                        $mail->MsgHTML("<h3>Your Product $product_name is Added to an Auction With Base Price $price <br> Auction Ends On $ending.</h3> <h1> Wait for Getting Bidders.....!!! </h1>");
            if (!$mail->Send()) {
                echo "Error Sending Mail";
            } else {
                $product_name = '';
                $category = '';
                $subcategory = '';
                $product_desc = '';
                $end_time = '';

                echo "success";
            }
        } else {
            // Handle image upload error
        }

    } else {
        echo "Select Future Date & Time";
    }
} else {
    echo "All input fields required...!";
}

// ... (your closing PHP tag) ...
?>
