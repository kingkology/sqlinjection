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
    $response->send_response(400,"Unable to search language. staff NID is required.",NULL);
    return;
}




$staff->PIN=$pin;
$result=$staff->get_language_details($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {
    switch ($row['approval_status']) {
      case 'Pending':
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove language"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_language']; 
                  echo "<br>".$row['staff_language_level'];
                  ?>
                  <hr>
                  <div class="mb-2 mr-2 badge badge-danger">Pending HR approval</div>
              </div>
              <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)">Approve</a></div>
               <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)">Reject</a></div>  
            </div>
          <?php
        break;
      
      default:
          ?>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="close" aria-label="Close" title="remove language"><span aria-hidden="true">×</span></button>
                  <?php  
                  echo "<br>".$row['staff_language']; 
                  echo "<br>".$row['staff_language_level'];
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
  <h2><center><u>Add New Language</u></center></h2>
<form id="add_language" name="add_language" class="">
  
  <div class="position-relative row form-group"><label for="staff_language" class="col-sm-2 col-form-label"><b>Language (eg. English)</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" class="form-control" name="staff_language" id="staff_language" >                                                        
        </div>
      </div>
  </div>

  <div class="position-relative row form-group"><label for="staff_language_level" class="col-sm-2 col-form-label"><b>Language Level</b></label>
      <div class="col-sm-10">
        <div class="input-group">   
          <select type="select"  name="staff_language_level" id="staff_language_level" class="custom-select">
              <option>Fluent</option>
              <option>Moderate</option>
              <option>Some knowledge</option>
          </select>     
          <input type="password" value="important" id="language_hr_update" name="language_hr_update" hidden readonly>                                                   
        </div>
      </div>
  </div>

  

  <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/add_language.php?pin=<?php echo $pin; ?>','add_language');">Add Language</a>
                                     
</form>
</div>