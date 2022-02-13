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
    $response->send_response(400,"Unable to search certificates. staff NID is required.",NULL);
    return;
}




$staff->PIN=$pin;
$result=$staff->get_education_details($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {

    $id=$row['staff_autoid'];
    $staffid=$row['staff_nid'];
    $cert_name=$row['staff_qualifications']." ".$row['staff_qualification_date'];

    switch ($row['approval_status']) {
      case 'Pending':
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove cert"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_qualifications']; 
                  echo "<br>".$row['staff_qualification_date'];
                  echo '<br><a class="mb-2 mr-2 btn-transition btn btn-outline-primary" target="new" href="assets/images/staff_certs/'.$row['staff_qualifications_cert'].'">Click to view cert</a>';
                  ?>
                  <hr>
                  <div class="mb-2 mr-2 badge badge-danger">Pending HR approval</div>
              </div>
              <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=certificate&status=approve&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&cert_name=<?php echo $cert_name; ?>','call_pagelet','apis/controllers/staff/crud/fetch_education_by_pin.php?pin='+pin.value,'staff_education')">Approve</a></div>
               <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)" onclick="update_values('apis/controllers/staff/crud/process_satff_info_update_request.php?type=certificate&status=reject&id=<?php echo $id; ?>&staff=<?php echo $staffid; ?>&cert_name=<?php echo $cert_name; ?>','call_pagelet','apis/controllers/staff/crud/fetch_education_by_pin.php?pin='+pin.value,'staff_education')">Reject</a></div>  
            </div>
          <?php
        break;

        case 'Rejected':
            ?>
              <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" aria-label="Close" title="remove cert"><span aria-hidden="true">×</span></button>
                    <?php  
                    echo "<br>".$row['staff_qualifications']; 
                    echo "<br>".$row['staff_qualification_date'];
                    echo '<br><a class="mb-2 mr-2 btn-transition btn btn-outline-primary" target="new" href="assets/images/staff_certs/'.$row['staff_qualifications_cert'].'">Click to view cert</a>';
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
                  <button type="button" class="close" aria-label="Close" title="remove cert"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_qualifications']; 
                  echo "<br>".$row['staff_qualification_date'];
                  echo '<br><a class="mb-2 mr-2 btn-transition btn btn-outline-primary" target="new" href="assets/images/staff_certs/'.$row['staff_qualifications_cert'].'">Click to view cert</a>';
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
  <h2><center><u>Add New Certificate</u></center></h2>
<form id="add_education" name="add_education" class="">
  
  <div class="position-relative row form-group"><label for="staff_qualifications" class="col-sm-2 col-form-label"><b>Qualification (eg. MSc Administration)</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_qualifications" id="staff_qualifications" >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_qualification_date" class="col-sm-2 col-form-label"><b>Qualification date</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="date" class="form-control" name="staff_qualification_date" id="staff_qualification_date"  >                                                        
        </div>
      </div>
  </div>

  

  <div class="position-relative row form-group"><label for="staff_qualifications_cert" class="col-sm-2 col-form-label"><b>Certificate (.pdf format)</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="file" class="form-control" name="staff_qualifications_cert" id="staff_qualifications_cert" >
          <input type="password" value="important" id="education_hr_update" name="education_hr_update" hidden readonly>                                                          
        </div>
      </div>
  </div>

  <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/add_education.php?pin=<?php echo $pin; ?>','add_education');">Add certificate</a>
                                     
</form>
</div>