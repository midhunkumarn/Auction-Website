<?php
    include "../../config/connect.php";

    $c_id=$_POST['c_id'];

    $subcategory=mysqli_query($con,"SELECT * from item_subcategory where c_id='$c_id'");
?>
    <option value="none" selected>Select Subcategory</option>
<?php
    while($row1=mysqli_fetch_assoc($subcategory)){
?>
        <option value="<?php echo $row1['subcategory_name'];?>"><?php echo $row1['subcategory_name'];?></option>
<?php
    }

?>