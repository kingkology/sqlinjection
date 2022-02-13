<?php 
session_start();
include '../../../config/Database.php';
include '../../../models/Staff.php';
include '../../../models/Response.php';

$database=new Database();
$hr_con=$database->connect('hr_db');$staff=new Staff();
$response=new Response();


if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") )) 
{
    ?>
        <div class="alert alert-danger fade show" role="alert">
            <h4 class="alert-heading">Illegal access!</h4>
            <p>No valid authorization detected. Kindly check login or check your access rights.</p>
        </div>
    <?php
    return;
}


if (isset($_GET['pin']) AND (!(trim($_GET['pin'])=="")) ) 
{
    $pin = mysqli_real_escape_string($hr_con,$_GET['pin']);
}
else
{
    $response->send_response(400,"Unable to search children. staff NID is required.",NULL);
    return;
}




$staff->PIN=$pin;
$result=$staff->get_kids_details($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {

    $id=$row['child_autoid'];
    $staffid=$row['staff_nid'];
    $child_name=$row['staff_child_surname']." ".$row['staff_child_forenames'];

    switch ($row['approval_status']) {
      case 'Pending':
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove Child"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_child_surname']." ".$row['staff_child_forenames']; 
                  echo "<br>".$row['staff_child_gender'];
                  echo "<br>".$row['staff_child_dob'];
                  ?>
                  <hr>
                  <div class="mb-2 mr-2 badge badge-danger">Pending HR approval</div>
              </div>
              <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=child&status=approve&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&child_name=<?php echo $child_name; ?>','call_pagelet','apis/controllers/staff/crud/fetch_kids_by_pin.php?pin='+pin.value,'staff_kids')">Approve</a></div>
               <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=child&status=reject&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&child_name=<?php echo $child_name; ?>','call_pagelet','apis/controllers/staff/crud/fetch_kids_by_pin.php?pin='+pin.value,'staff_kids')">Reject</a></div>  
            </div>
          <?php
        break;

        case 'Rejected':
            ?>
              <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove Child"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_child_surname']." ".$row['staff_child_forenames']; 
                  echo "<br>".$row['staff_child_gender'];
                  echo "<br>".$row['staff_child_dob'];
                  ?>
                  <hr>
                    <div class="mb-2 mr-2 badge badge-danger">Rejected</div>
                </div>
              </div>
            <?php
          break;
      
      default:
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove Child"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_child_surname']." ".$row['staff_child_forenames']; 
                  echo "<br>".$row['staff_child_gender'];
                  echo "<br>".$row['staff_child_dob'];
                  ?>
                  <hr>
                  <div class="mb-2 mr-2 badge badge-success">HR approved</div>
              </div>
            </div>
          <?php
        break;
    }

  }
}

?>


<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
  <hr>
  <h2><center><u>Add New Child</u></center></h2>
<form id="add_child" name="add_child" class="">
  
  <div class="position-relative row form-group"><label for="staff_child_surname" class="col-sm-2 col-form-label"><b>Child Surname</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_child_surname" id="staff_child_surname" >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_child_forenames" class="col-sm-2 col-form-label"><b>Child Forenames</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_child_forenames" id="staff_child_forenames"  >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="sex" class="col-sm-2 col-form-label"><b>Gender</b></label>
      <div class="col-sm-10">
        <div class="input-group">
            <select type="select"  name="sex" id="sex" class="custom-select">
                <option>Male</option>
                <option>Female</option>
            </select>                                                   
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_child_dob" class="col-sm-2 col-form-label"><b>Child Date Of Birth</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="date" class="form-control" name="staff_child_dob" id="staff_child_dob" >
          <input type="password" value="important" id="child_hr_update" name="child_hr_update" hidden readonly>                                                          
        </div>
      </div>
  </div>

  <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/add_child.php?pin=<?php echo $pin; ?>','add_child');">Add Child</a>
                                     
</form>
</div>