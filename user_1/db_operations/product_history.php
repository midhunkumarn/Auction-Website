<?php
    session_start();

    include '../../config/connect.php';

    $uid=$_SESSION['u_id'];

   

    // $product_imgs=mysqli_query($con,"SELECT * FROM product_imgs Where p_id='$p_id'");

    // $row_img=mysqli_fetch_assoc($product_imgs);

?>

<form method="post">

    <select name="auction_status" id="auction_status">

        <option value="">All</option>
        <option value="active">Active</option>
        <option value="close">Close</option>

    </select>

</form>
<?php

    if(!empty($_POST['auction_status'])){

        $auction_status=$_POST['auction_status'];

        // foreach($_POST['auction_status'] as $auction_status){

        //  $allhistory="SELECT * FROM products,auction Where u_id='$uid' and auction_status='$auction_status'";

        // }

    }
    
    

?>

