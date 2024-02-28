
<?php
  session_start();

 


  if(!isset($id)){
     
  }

  include '../config/connect.php';

  error_reporting(0);

  if(!empty($_GET['page'])){
    $page=$_GET['page'];
}
else{
    $page=1;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="website icon" type='png' href="images/logo.png"/>


    <!-- Montserrat Font -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> -->

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    

    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auctions.css">
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="css/complaints.css">
  </head>
  <body>


    <div class="grid-container">

      <!-- Header -->
      <header class="header">

        <div class="menu-icon" onclick="openSidebar()">
          <span class="material-icons-outlined">menu</span>
        </div>


      </header>
      <!-- End Header -->

      <!-- Sidebar -->
      <!-- <aside id="sidebar">

<div class="sidebar-title">
<div class="sidebar-brand">
          <image src="images/logo.png" height="50px" width="50px"> Zoo Bids
        </div>
  <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
</div>

<ul class="sidebar-list">

  <li class="sidebar-list-item">
    <a href="index.php">
      <span class="material-icons-outlined">dashboard</span> Dashboard
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="auctions.php">
      <span class="material-icons-outlined">inventory_2</span> Auctions
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="users.php">
      <span class="material-icons-outlined">person</span> Users
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="complaints.php">
      <span class="material-icons-outlined">report</span> Complaints
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="#">
      <span class="material-icons-outlined">settings</span> Profile
    </a>
  </li>

  <br><br><br>
  <li class="sidebar-list-item">
    <a href="controller/logout.php?logout_id=<?php echo $id; ?>">
      <span class="material-icons-outlined">logout</span> Log OUT
    </a>
  </li>

</ul>

</aside> -->
<aside id="sidebar">

<div class="sidebar-title">
<div class="sidebar-brand">
          <image src="images/logo.png" height="50px" width="50px"> Zoo Bids
        </div>
  <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
</div>

<ul class="sidebar-list">

  <li class="sidebar-list-item">
    <a href="index.php">
      <span class="material-icons-outlined">dashboard</span> Dashboard
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="auctions.php">
      <span class="material-icons-outlined">inventory_2</span> Auctions
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="users.php">
      <span class="material-icons-outlined">person</span> Users
    </a>
  </li>

  <li class="sidebar-list-item">
    <a href="complaints.php">
      <span class="material-icons-outlined">report</span> Complaints
    </a>
  </li>

  <!-- <li class="sidebar-list-item">
    <a href="#">
      <span class="material-icons-outlined">settings</span> Profile
    </a>
  </li> -->

  <br><br><br>
  <li class="sidebar-list-item">
    <a href="controller/logout.php?logout_id=<?php echo $id; ?>">
      <span class="material-icons-outlined">logout</span> Log OUT
    </a>
  </li>

</ul>

</aside>
      <!-- End Sidebar -->
      <!-- Main -->
      <main class="main-container">

      <?php
            if(empty($_GET['p_id'])){
      ?>

        <div class="main-title">
          <p class="font-weight-bold">Auction Event</p>
        </div>

        
            <!-- Button for Add New Category -->
            <button type="button" class="add-category" id="showform">

              <span class="material-icons-outlined" id="addcategory">add</span> Add Category

            </button>



          <div class="content">



            <div class="charts-card" id='auction-list'>

            <?php

            // include "../../config/connect.php";

            if (isset($_REQUEST['displayAmount'])) {
              // $selected = $_REQUEST['displayAmount'];
              $num_per_page=$_REQUEST['displayAmount'];
            
            } else {
            // $selected = 10;
            $num_per_page=05;
            }

            //statuus selection
            if(isset($_REQUEST['status'])){
              $select=$_REQUEST['status'];

            }else{
              $select="all";

            }

            if(isset($_REQUEST['searching'])){
              $search=$_REQUEST['searching'];
            }

            
//Create array of display amount option for user to choose
$options = array(5,10,15,20,50);

//selection of active & closed auction
$auction = array('all','active','close','recent Added');

            echo '<form method="post">
              <select name="displayAmount" class="box" onchange="this.form.submit();">';

            //iterate through the option and set current option as selected.
              foreach ($options as $option) {
                if($option == $num_per_page){
                    echo "<option selected='selected'> $option </option>";
                }else{
                    echo "<option> $option </option>";
                }
              }
  
              echo  '</select> ';

              echo '<select name="status" class="box" onchange="this.form.submit();">';

            //iterate through the option and set current option as selected.
              foreach ($auction as $status) {
                if($status == $select){
                    echo "<option selected='selected'> $status </option>";
                }else{
                    echo "<option> $status </option>";
                }
              }

              echo '</select> ';
?>
                <input type="text" name="searching" id="searching" placeholder='Search.....' class='search' onchange="this.form.submit()">
                
              </form>

<?php

  $start_from=($page-1)*$num_per_page;


  if($select == 'all' && empty($search)){
    $sql="SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id  GROUP BY a_id LIMIT $start_from,$num_per_page";
  }
  else if($select !== 'all' && $select !== 'recent Added' && !empty($search)){
    $sql="SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id  where auction.auction_status='$select' and products.product_name like '%$search%' GROUP BY a_id LIMIT $start_from,$num_per_page";
  }
  else if($select == 'recent Added'){
    $sql="SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id  GROUP BY a_id ORDER BY a_id DESC LIMIT $start_from,$num_per_page";
  }
  else if(!empty($_REQUEST['searching'])){
    $sql="SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where products.product_name LIKE '%$search%' GROUP BY a_id LIMIT $start_from,$num_per_page";

  }
  else{
    $sql="SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where auction.auction_status='$select' GROUP BY a_id LIMIT $start_from,$num_per_page";
  
  }
  $queryrun=mysqli_query($con,$sql);

  $rows=mysqli_num_rows($queryrun);

          if($rows > 0){

?>

      <?php
        if($select == 'all'){
      ?>
          <h1 class="text-center">Auction Lists</h1>
      <?php
        }else{
      ?>
          <h1 class="text-center"><?php echo $select; ?>Auction Lists</h1>
      <?php
        }
      ?>

      
            
      <div class="header_fixed">
        <table>
              <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Owner</th>
                    <th>Auction Status</th>
                    <th>Action</th>
                </tr>
              </thead>
             
              <tbody>

              <?php    
                    while($data=mysqli_fetch_assoc($queryrun)){
              ?>
                <tr>
                    <td><?php echo $data['p_id'];?></td>
                    <td> <img src="<?php echo 'data:image;base64,'.base64_encode($data['product_imgs']).''?>" /></td>
                    <td><?php echo $data['product_name'];?></td>
                    <td>
                      <textarea name="" id="" cols="15" rows="15" disabled>
                        <?php 
                          $desc= $data['product_desc'];

                          // $description=str_replace('\n','<br>',$desc);
                          $description=preg_replace('#\[nl\]#','<br>',$desc);
                          $description=preg_replace('#\[sp\]#',"&nbsp",$desc);

                          echo $description;
                        ?>
                      </textarea>
                    </td>
                    <td><?php echo $data['price'];?></td>
                    <td><?php echo $data['name'];?></td>
                    <td><?php echo $data['auction_status'];?></td>
                    <td><a href="auctions.php?p_id=<?php echo $data['p_id']; ?>"><button>View</button></a></td>
                </tr>

                <?php
                    
                    }//while loop

                ?>

                  <tr>

                  <td>
                        <?php
                            if($page>1){

                        ?>
                                <a href="auctions.php?page=<?php echo $page-1;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>&searching=<?php echo $search; ?>" class='btn btn-danger'><button >Previous</button></a>
                        <?php
                            }
                        ?>
                    </td>

                    

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

            <?php

                  }else{
            ?>

                  <div class='empty'>
                    <i class='fa-sharp fa-solid fa-trash'></i> 
                    <h1> Not Found Any Auction List ...!!! </h1> 
                  </div>                    

            <?php
                  }//if condition

                   //this is for counting number of pages
                  if($select == 'all' && empty($_REQUEST['searching'])){
                    $res1=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id GROUP BY a_id");
                  }
                  else if($select !== 'all' && $select !== 'recent Added' && !empty($_REQUEST['searching'])){
                    $res1=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where auction.auction_status='$select' and products.product_name like '%$search%' GROUP BY a_id");
                  }
                  else if($select == 'recent Added'){
                    $res1=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id GROUP BY a_id ORDER BY a_id DESC");
                  }
                  else if(!empty($_REQUEST['searching'])){
                    $res1=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where products.product_name like '%$search%' GROUP BY a_id");
                  }
                  else{
                    $res1=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where auction.auction_status='$select' GROUP BY a_id");
                  }

                  $count=mysqli_num_rows($res1);

                  $total_page=$count/$num_per_page;
                

                  $pages=ceil($total_page);

                  for($i=1;$i<=$pages;$i++){

                    if($i < $pages){
                      ?> 
                                 <a href="auctions.php?page=<?php echo $i;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>&searching=<?php echo $search; ?>"></a>
           
                     <?php
                               }
                               else if($page == $i){
                                 break;
                               }
                               else if($page > $i){
                     ?>
                                 <a href="auctions.php?page=<?php echo $i;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>&searching=<?php echo $search; ?>"><button>Click Here To Back</button> </a>
           
                     <?php
                               }//end
                  }
          ?>
                     <td>
                      <?php
                          if($i>$page){
                      ?>
                            <a href="auctions.php?page=<?php echo $page+1;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>&searching=<?php echo $search; ?>"  class='btn btn-danger' id='next'><button type='button' >Next</button></a>
                      <?php
                          }
                         
                      ?>
                    </td>

                  </tr>


                </tbody>

            </table>
      </div>


           </div>

           <div>

           <?php 
            }else{

              $pid=$_GET['p_id'];

              $productdata=mysqli_query($con,"SELECT DISTINCT * from products INNER JOIN auction on products.p_id=auction.p_id INNER JOIN product_imgs on products.p_id=product_imgs.p_id INNER JOIN users on products.owner=users.u_id where products.p_id='$pid'");

              $display=mysqli_fetch_assoc($productdata);

              $img=mysqli_query($con,"SELECT * from product_imgs where p_id='$pid'");

            //include "controller/fetch_auction.php"; 

            
          ?>


          <h2 style="text-align:center">Product Details</h2>
          <br><br>

          <div class="card">

            <h2 style="text-align:center">Product Name:<?php echo $display['product_name']; ?></h2>

            <div class="carousel-container">

                <div class="carousel">

                  <div class="item main">
                    <img src="<?php echo 'data:image;base64,'.base64_encode($display['product_imgs']).''?>" height="300px" />
                  </div>
                  
                  <?php
                    while($row_imgs=mysqli_fetch_assoc($img)){
                  ?>
                  <div class="item">
                    <img src="<?php echo 'data:image;base64,'.base64_encode($row_imgs['product_imgs']).''?>" height="300px" />
                  </div>
                  <?php
                    }
                  ?>
                    
                </div>

                <div class="navigation">
                <div class="prev nav-btn"><</div>
                <div class="next nav-btn">></div>
              </div>

            </div>

            <br><br><br>

            <!-- <img src="<?php //echo 'data:image;base64,'.base64_encode($display['product_imgs']).''?>"> -->

            <h1>Owner:<?php echo $display['name']; ?></h1>

            <h1>Owner Email:<?php echo $display['email']; ?></h1>

            <p class="price"><?php echo number_format($display['price']); ?></p>

            <p><?php echo $display['product_desc']; ?></p>

            <p>

              <a href="auctions.php"><button>Back</button></a>

              <br><br>
              
              <a href="controller/product_remove.php?p_id=<?php echo $pid;?>&email=<?php echo $display['email']; ?>"><button>Remove</button></a>
            
            </p>

          </div>

          </div>

          <?php
            }
          ?>




          </div>

          <div class="popup-form" id="popup-form">
            
            <div class="closebtn">&times;</div>

            <button type="button" name="add-subcategory" id='add-subcategory'><span class="material-icons-outlined">add</span>Add Subcategory</button>
            <button type="button" name="add-subcategory" id='add-category'><span class="material-icons-outlined">add</span>Add Category</button>

            <h3><span class="material-icons-outlined">add</span> Add New Category</h3>

              <div id="category">

                <form action="controller/add_category.php" method="post">

                    <div class="form-element">
                      <label for="category">Enter Category</label>
                      <input type="text" name="category">
                    </div>

                    <!-- <div class="status">
                      <select name="status" id="status">
                          <optiontive">Active</option>
                          <optionose">Close</option>
                      </select>
                    </div> -->

                    <div class="form-element">
                      <input type="submit" Value="Add Category" name="add_category">
                    </div>

                </form>

              </div>

                <div id="subcategory">

                  <form action="controller/add_subcategory.php" method="post">

                    <select name="categories" >
                      <option value="none" selected>Select Category</option>

                      <?php
                        $sql=mysqli_query($con,"SELECT * from item_category");

                        while($display=mysqli_fetch_assoc($sql)){

                      ?>
                        <option value="<?php echo $display['c_id']; ?>"><?php echo $display['category_name']; ?></option>

                      <?php
                        }
                      ?>

                    </select>

                    <div class="form-element">
                      <label for="subcategory">Enter SubCategory Name</label>
                      <input type="text" name="subcategory">
                    </div>

                    <!-- <div class="status">
                      <select name="status" id="status">
                          <optiontive">Active</option>
                          <optionose">Close</option>
                      </select>
                    </div> -->

                    <div class="form-element">
                      <input type="submit" name="add_subcategory">
                    </div>

                  </form>

                </div>
            
      </div>

      </main>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

      <script>
          $("#subcategory").hide();
          $("#add-category").hide();

          $("#add-category").click(function() {
              $("#category").show();
              $("#subcategory").hide();
          });

          $("#add-subcategory").click(function() {

              $("#category").hide();
              $("#add-subcategory").hide();
              $("#add-category").show();
              $("#subcategory").show();
          });

//image slider javascript
const prev = document.querySelector('.prev');
const next = document.querySelector('.next');
const images = document.querySelector('.carousel').children;
const totalImages = images.length;
let index = 0;

prev.addEventListener('click', () => {
  nextImage('next');
})

next.addEventListener('click', () => {
  nextImage('prev');
})

function nextImage(direction) {
  if(direction == 'next') {
    index++;
    if(index == totalImages) {
      index = 0;
    }
  } else {
    if(index == 0) {
      index = totalImages - 1;
    } else {
      index--;
    }
  }

  for(let i = 0; i < images.length; i++) {
    images[i].classList.remove('main');
  }
  images[index].classList.add('main');
}
</script>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>

      <script src="js/auction_data.js"></script>
      
      <!-- End Main -->
    
<?php
  include "footer.php";
?>
