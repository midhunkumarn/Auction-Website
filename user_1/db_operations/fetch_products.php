<?php

    session_start();

    include "../../config/connect.php";

    $uid=$_SESSION['u_id'];

    if(!empty($_POST['c_id'])){

        $c_id=$_POST['c_id'];
    
    }

    if(!empty($_POST['search'])){

        $search=$_POST['search'];

    }


    if(isset($_GET['page'])){
        $page=$_GET['page'];
    }
    else{
        $page=1;
    }

    $start_from=($page-1)*05;

    $num_per_page=2;

    if(!empty($_POST['c_id'])){

        $sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN item_category on products.category=item_category.c_id where auction.auction_status='active' AND products.category='$c_id' AND products.owner!='$uid' GROUP BY a_id LIMIT $start_from,$num_per_page";

    }
    else if(empty($_POST['c_id'])){
        $sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction_status='active' AND products.price LIKE '%$search%' OR products.product_name LIKE '%$search%' and products.owner!='$uid' GROUP BY a_id LIMIT $start_from,$num_per_page";
    }
    else if(!empty($_POST['c_id']) && !empty($_POST['search'])){

        $sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN item_category AND products.category=item_category.c_id where auction.auction_status='active' AND products.category='$c_id' OR products.price LIKE '%$search%' OR products.product_name LIKE '%$search%' AND products.owner!='$uid' GROUP BY a_id LIMIT $start_from,$num_per_page";

    }


   
    // else if(isset($_POST['search'])){

    //     // $search=$_POST['searching'];


    //     $sql="SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN item_category on products.category=item_category.c_id where auction.auction_status='active' AND products.category='$c_id' AND products.owner!='$uid' AND products.product_name LIKE '%$search%' GROUP BY a_id LIMIT $start_from,$num_per_page";
    // }


    $queryrun=mysqli_query($con,$sql);

    if(mysqli_num_rows($queryrun) > 0){

        while($data=mysqli_fetch_assoc($queryrun)){

   

            



?>

            <div class="box">

                    <div class="img-box">

                        <img src="<?php echo 'data:image;base64,'.base64_encode($data['product_imgs']).''?>"></img>

                    </div>

                    <div class="left">

                                    <p><?php echo $data['product_name'];?></p>
                                    <h2><span class="material-icons-outlined">currency_rupee</span><?php echo number_format($data['price']);?></h2>
                                    <a href="product_detail.php?p_id=<?php echo $data['p_id']; ?>"><button>View Details</button></a>

                    </div>
            </div>

        <?php
            }

            if(!empty($_POST['c_id']) ){
                $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction_status='active' and products.category='$c_id' and products.owner!='$uid' GROUP BY a_id");
            }
            else if(empty($_POST['c_id'])){
                $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction_status='active' AND products.price LIKE '%$search%' OR products.product_name LIKE '%$search%' and products.owner!='$uid' GROUP BY a_id");
            }
            else if(!empty($_POST['c_id']) && !empty($_POST['search'])){
                $res1=mysqli_query($con,"SELECT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id where auction_status='active' and products.category='$c_id' AND products.price LIKE '%$search%' OR products.product_name LIKE '%$search%' and products.owner!='$uid' GROUP BY a_id");
            }

            $count=mysqli_num_rows($res1);

                //   echo $count;

            $total_page=$count/$num_per_page;

            $pages=ceil($total_page);
            for($i=1;$i<=$pages;$i++){
        ?> 

            <a href="auction.php?page=<?php echo $i;?>"></a>

        <?php
            }
    }else{
        echo "<div class='empty'><i class='fa-sharp fa-solid fa-trash'></i> <h1> Tis Category Auction List Is Empty...!!! </h1> </div>";
    }
        ?>
                        

