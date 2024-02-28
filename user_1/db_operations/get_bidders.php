<?php
    session_start();

    $uid=$_SESSION['u_id'];

    include '../../config/connect.php';

    if(empty($uid)){
        header('location:../index.php');
    }

    $p_id=$_GET['p_id'];

    $sql=mysqli_query($con,"SELECT * from bids INNER JOIN users on bids.u_id=users.u_id INNER JOIN users_imgs on bids.u_id=users_imgs.u_id where bids.p_id='$p_id' ORDER BY bid_amount DESC");

    $sql2=mysqli_query($con,"SELECT wining_bidder,auction_status from auction where p_id='$p_id'");

    $row2=mysqli_fetch_assoc($sql2);

    $wining_bidder=$row2['wining_bidder'];
    $auction_status=$row2['auction_status'];

    if(mysqli_num_rows($sql) > 0){

?>

<table>

    <tr>

            <th colspan="2">Name</th>
            <th>Bid Price</th>
            <th>Contact NO</th>
            <th>Address</th>
        <?php
            if($auction_status == 'close'){
        ?>    
            <th>Options</th>
        <?php
            }
        ?>

    </tr>


<?php

        echo "<h1>Bidders Of Product:</h1>";

        while($row=mysqli_fetch_assoc($sql)){

            $uid=$row['u_id'];
?>




    <tr>

            <td><img src="<?php echo 'data:image;base64,'.base64_encode($row['profile_pic']).''?>" alt="" ></td>

            <td><span class="name"><?php echo $row['name'];?></span></td>

            <td><span class="price"><span class="material-icons-outlined">currency_rupee</span><?php echo number_format($row['bid_amount']);?></span></td>

            <td> <span class="price"><?php echo $row['contact_no']; ?></span></td>

            <td> <span class="price"><?php echo $row['address']; ?></span></td>

            <?php
            
                if($auction_status == 'close'){

            ?>
                <td>
                    <a href="db_operations/confirm_bidder.php?delete=true&id=<?php echo $row['u_id'];?>&p_id=<?php echo $p_id;?>"><span class="material-icons-outlined">delete</span></a>&nbsp;&nbsp;&nbsp;
                </td>
            <?php
                }//auction status if condition
            ?>

    </tr>



<?php
        }

?>
</table>

<?php

    }
    else{
        echo "<h1>Bidders List Is Empty....</h1>";
    }
?>