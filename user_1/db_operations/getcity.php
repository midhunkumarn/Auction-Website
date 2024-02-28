<?php
    include "../../config/connect.php";

    $pincodes=$_POST['pincodes'];

    $city=mysqli_query($con,"SELECT * from state where pincode='$pincodes'");

    $row1=mysqli_fetch_assoc($city);
?>
    <th>

        <span class="material-icons-outlined ">location_city</span> City <br><br>
        <span class="material-icons-outlined ">place</span> State

    </th>

    <td>
        <input value="<?php echo $row1['city']; ?>" type="text" class="form-control col-md-6" name="city" id="city" disabled> <br><br>
        <input value="<?php echo $row1['state']; ?>" type="text" class="form-control col-md-6" name="state" id="state" disabled>
    </td>

    <br><br><br>
<!-- 
    <td>
        <input value="<?php //echo $row1['city']; ?>" type="text" class="form-control col-md-6" name="city" id="city" disabled>
	</td> -->


    <td>
	</td>