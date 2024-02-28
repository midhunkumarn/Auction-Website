<?php
  session_start();

  include '../config/connect.php';
 

  if(!isset($id)){
 
  }

  
  if(isset($_REQUEST['page'])){
    $page=$_REQUEST['page'];
  }else{
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

    <script src="https://kit.fontawesome.com/92d70a2fd8.js" crossorigin="anonymous"></script>
    
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

   
        <div class="main-title">
          <p class="font-weight-bold">Complaints List</p>
        </div>

          <div class="content">

            <!-- <form action=""> -->

              
            <!-- </form> -->
          


            <div class="charts-card" id='auction-list'>

            <?php

              if (isset($_REQUEST['displayAmount'])) {
                // $selected = $_REQUEST['displayAmount'];
                $num_per_page=$_REQUEST['displayAmount'];
              $start_from=($page-1)*$num_per_page;


              } else {
                $num_per_page=05;
              $start_from=($page-1)*$num_per_page;

              }

              //statuus selection
              if(isset($_REQUEST['status'])){
                $select=$_REQUEST['status'];

              }else{
                $select="All";

              }


              //Create array of display amount option for user to choose
              $options = array(5,2,10,15,20,50);

              //selection of active & closed auction
              $complaints = array('All','accepted','declined','pending');


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
              foreach ($complaints as $status) {
                if($status == $select){
                    echo "<option selected='selected'> $status </option>";
                }
                else{
                    echo "<option> $status </option>";
                }
              }

              echo '</select> </form>';


              if($select == 'All'){
                $sql="SELECT * from complaints LIMIT $start_from,$num_per_page";
              }else{
                $sql="SELECT * from complaints where status='$select' LIMIT $start_from,$num_per_page";
              } 

              $queryrun=mysqli_query($con,$sql);

              $rows=mysqli_num_rows($queryrun);

                      if($rows > 0){

?>
      <!-- <h1 class="text-center">Active Auction Lists</h1>

      
            
      <div class="header_fixed"> -->
        <table>
              <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint Description</th>
                    <th>Complaint BY</th>
                    <th>Complaint Status</th>
                    <th>Action</th>
                </tr>
              </thead>
             
              <tbody>

              <?php    
                    while($data=mysqli_fetch_assoc($queryrun)){

                      $uid=$data['u_id'];

                      $sql2=mysqli_query($con,"SELECT * from users where u_id='$uid'");

                      $display2=mysqli_fetch_assoc($sql2);
              ?>
                <tr>
                    <td><?php echo $data['complaint_id'];?></td>
                    <!-- <td> <img src="<?php //echo 'data:image;base64,'.base64_encode($data['product_imgs']).''?>" /></td> -->
                    <td>


                        </textarea>

                        <textarea name="" id="" cols="20" rows="15" disabled>
                        <?php 
                        $desc= $data['complaint_description'];

                          // $description=str_replace('\n','<br>',$desc);
                          $description=preg_replace('#\[nl\]#','<br>',$desc);
                          $description=preg_replace('#\[sp\]#',"&nbsp",$desc);

                          echo ltrim($description);
                        ?>
                      </textarea>
                    </td>
                    <td><?php echo $display2['name'];?></td>
                    <td><?php echo $data['status'];?></td>
                    <td>
                         <!-- displaying status buttons -->
                         <?php
                        if($data['status'] == 'pending'){
                        ?>


                          <a href="controller/complaint_status.php?complaint_id=<?php echo $data['complaint_id']; ?>&decline=false"><button>Accept</button></a>
                          <a href="controller/complaint_status.php?complaint_id=<?php echo $data['complaint_id']; ?>&decline=true"><button>Decline</button></a>


                        <?php
                        }
                        else if($data['status'] !== 'pending' && $data['status'] !== 'declined' && $data['status'] !== 'completed'){
                        ?>


                          <a href="controller/complaint_status.php?complaint_id=<?php echo $data['complaint_id']; ?>&completed=true">
                            <button>
                              <span class="material-icons-outlined">done</span>
                            </button>
                          </a>


                        <?php
                        }
                        else if($data['status'] == 'completed'){
                        ?>

                          <a href="controller/complaint_status.php?complaint_id=<?php echo $data['complaint_id']; ?>&delete=true">
                            <button>
                              <span class="material-icons-outlined">delete</span>
                            </button>
                          </a>

                        <?php
                        }
                        else if($data['status'] == 'accepted'){
                        ?>
                          <button>Accepted</button>
                        <?php
                        }else if($data['status'] == 'declined'){
                        ?>
                          <a href="controller/complaint_status.php?complaint_id=<?php echo $data['complaint_id']; ?>&delete=true">
                            <button>
                              <span class="material-icons-outlined">delete</span>
                            </button>
                          </a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>

                <?php
                    
                    }//while loop

                ?>

                  <tr>

                    <td>
                        <?php
                            if($page>1){
                        ?>
                              <a href='complaints.php?page=<?php echo $page-1; ?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>' class='btn btn-danger'><button >Previous</button></a>";
                        <?php
                            }
                        ?>
                    </td>

                    <td></td>
                    <td></td>
                    <td></td>

            <?php

                }else{

            ?>
                  <div class='empty'>
                    <i class='fa-sharp fa-solid fa-trash'></i> 
                    <h1> Not Found Any Complaint ...!!! </h1> 
                  </div>

                    <!-- <h1 style="text-align:center; align-items:center; ">Not Found An Active Auction List...!!!!</h1>' -->
            <?php
                  }//if condition

                   //this is for counting number of pages

                  if($select == 'All'){ 
                    $res1=mysqli_query($con,"SELECT * from complaints ");
                  }else{
                    $res1=mysqli_query($con,"SELECT * from complaints Where status='$select' ");
                  }

                  $count=mysqli_num_rows($res1);

                  $total_page=$count/$num_per_page;

                  $pages=ceil($total_page);
                  
                  for($i=1;$i<=$pages;$i++){

                    //set new page on button click
                    if($i < $pages){
           ?> 
                      <a href="complaints.php?page=<?php echo $i;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>"></a>

          <?php
                    }
                    else if($page == $i){
                      break;
                    }
                    else if($page > $i){
          ?>
                      <a href="complaints.php?page=<?php echo $i;?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>"><button>Click Here To Back</button> </a>

          <?php
                    }//end
                  }
          ?>
                    <td>
                    <?php
                        if($i>$page){
                    ?>
                          <a href='complaints.php?page=<?php echo $page+1; ?>&displayAmount=<?php echo $num_per_page; ?>&status=<?php echo $select;?>' class='btn btn-danger'><button type='button'>Next</button></a>";
                    <?php   
                        }
                    ?>
                    </td>

                  </tr>


                </tbody>

            </table>
      </div>

            


          </div>

          
      </main>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="js/auction_data.js"></script>

      <script>
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }
</script>
      
      <!-- End Main -->
    
<?php
  include "footer.php";
?>
