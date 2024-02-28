<?php
    session_start();

    include '../../config/connect.php';

    $uid=$_SESSION['u_id'];

    $p_id=$_GET['p_id'];

    $sql=mysqli_query($con,"SELECT * from bids where p_id='$p_id'");

    $row=mysqli_fetch_assoc($sql);

    $userbid=$_SESSION['bid'];

    $a_id=$row['a_id'];

    $sql2=mysqli_query($con,"SELECT * from auction INNER JOIN bids on auction.a_id=bids.a_id where auction.a_id='$a_id' and bids.u_id='$uid'");

    $sql3=mysqli_query($con,"SELECT * from auction INNER JOIN bids on auction.a_id=bids.a_id where auction.a_id='$a_id' and bids.u_id!='$uid'");


    if(mysqli_num_rows($sql3) > 0){

        while($row=mysqli_fetch_assoc($sql3)){


            $bids[]=$row['bid_amount'];

            // if($row['bid_amount'] < $userbid){

            //     $bid_amount=$row['bid_amount'];

                // $winning_bidder=$row['u_id'];

            //     // echo $winning_bidder;

            //     break;   
            // }

        }
        // $a=0;

        foreach($bids as $bid_amount){
        
            
            $highbid=max($bids);

            // echo $highbid;

            $find=mysqli_query($con,"SELECT u_id from bids where bid_amount='$highbid' AND a_id='$a_id'");

            $data=mysqli_fetch_assoc($find);

            $winning_bidder=$data['u_id'];

            // echo $winning_bidder;
        }


        if($winning_bidder != $uid){

            // echo $winning_bidder;

            // echo $highbid;

            // echo "jsjjsdk";
            $sql4=mysqli_query($con,"UPDATE auction SET current_bid='$highbid',wining_bidder='$winning_bidder' where a_id='$a_id' AND wining_bidder='$uid'");

            if($sql4){

            $sql5=mysqli_query($con,"DELETE from bids where a_id='$a_id' AND u_id='$uid' AND p_id='$p_id'");

                if($sql5){

                    unset($_SESSION['bid']);
                    header('location:../bids.php');


                }else{

                    echo "<Script>";
            echo "alert('try again....')";
            echo "<Script>";

                }

            }else{
                echo "<Script>";
            echo "alert('try again....')";
            echo "<Script>";
            }

        }

    }else{


        $sql4=mysqli_query($con,"UPDATE auction SET current_bid=0,wining_bidder=0 where a_id='$a_id' AND wining_bidder='$uid'");

        if($sql4){

            $sql5=mysqli_query($con,"DELETE from bids where a_id='$a_id' AND u_id='$uid' AND p_id='$p_id'");

            if($sql5){

                unset($_SESSION['bid']);
                header('location:../bids.php');

            }else{

                echo "<Script>";
                echo "alert('try again....')";
                echo "<Script>";
                
            }
        }
        else{
            echo "<Script>";
            echo "alert('try again....')";
            echo "<Script>";
        }
    }
    //if records found for user or not 


?>