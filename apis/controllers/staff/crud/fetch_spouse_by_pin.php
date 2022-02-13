<?php 
session_start();
include '../../../config/Database.php';
include '../../../models/Staff.php';
include '../../../models/Response.php';

$database=new Database();
$hr_con=$database->connect('hr_db');
$staff=new Staff();
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
    $response->send_response(400,"Unable to search spouse. staff NID is required.",NULL);
    return;
}



$staff->PIN=$pin;
$result=$staff->get_spouse_details($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {
    $id=$row['spouse_autoid'];
    $staffid=$row['staff_nid'];
    $spouse_name=$row['staff_spouse_surname']." ".$row['staff_spouse_forenames'];
    switch ($row['approval_status']) 
    {
      case 'Pending':
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove spouse"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_spouse_surname']." ".$row['staff_spouse_forenames']; 
                  echo "<br>".$row['staff_spouse_dob'];
                  ?>
                  <hr>
                  <div class="mb-2 mr-2 badge badge-danger">Pending HR approval</div>
              </div>
              <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=spouse&status=approve&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&spouse_name=<?php echo $spouse_name; ?>','call_pagelet','apis/controllers/staff/crud/fetch_spouse_by_pin.php?pin='+pin.value,'staff_spouse')">Approve</a></div>
               <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=spouse&status=reject&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&spouse_name=<?php echo $spouse_name; ?>')">Reject</a></div>  
            </div>
          <?php
        break;

        case 'Rejected':
            ?>
              <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" aria-label="Close" title="remove spouse"><span aria-hidden="true">×</span></button>
                    <?php  
                    echo "<br>".$row['staff_spouse_surname']." ".$row['staff_spouse_forenames']; 
                    echo "<br>".$row['staff_spouse_dob'];
                    echo "<br>".$row['spouse_contact'];
                    echo "<br>".$row['spouse_address'];
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
                  <button type="button" class="close" aria-label="Close" title="remove spouse"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_spouse_surname']." ".$row['staff_spouse_forenames']; 
                  echo "<br>".$row['staff_spouse_dob'];
                  echo "<br>".$row['spouse_contact'];
                  echo "<br>".$row['spouse_address'];
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
  <h2><center><u>Add new spouse</u></center></h2>
<form id="add_spouse" name="add_spouse" class="">
  
  <div class="position-relative row form-group"><label for="staff_spouse_surname" class="col-sm-2 col-form-label"><b>Spouse Surname</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_spouse_surname" id="staff_spouse_surname" >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_spouse_forenames" class="col-sm-2 col-form-label"><b>Spouse Forenames</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_spouse_forenames" id="staff_spouse_forenames"  >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_spouse_dob" class="col-sm-2 col-form-label"><b>Spouse Date Of Birth</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="date" class="form-control" name="staff_spouse_dob" id="staff_spouse_dob" >
          <input type="password" value="important" id="spouse_hr_update" name="spouse_hr_update" hidden readonly>                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_spouse_contact" class="col-sm-2 col-form-label"><b>Spouse Contact</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="number" class="form-control" name="staff_spouse_contact" id="staff_spouse_contact" value="233" >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_spouse_address" class="col-sm-2 col-form-label"><b>Spouse Address</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_spouse_address" id="staff_spouse_address" >                                                        
        </div>
      </div>
  </div>

  <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/add_spouse.php?pin=<?php echo $pin; ?>','add_spouse');">Add spouse</a>
                                     
</form>
</div>
