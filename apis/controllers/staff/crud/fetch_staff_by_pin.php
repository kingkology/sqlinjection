<?php 
session_start();
$_SESSION['current_page']="views/add_staff.php";

include '../../../config/Database.php';
include '../../../models/Department.php';
include '../../../models/Grade.php';
include '../../../models/Position.php';
include '../../../models/Unit.php';
include '../../../models/Staff.php';
include '../../../models/Appointment.php';
include '../../../models/Response.php';
include '../../../../../admin_portal/apis/models/offices/offices.php';

$database=new Database();
$hr_con=$database->connect('hr_db');
$sys_con=$database->connect('app_db');
$department=new Department();
$grade=new Grade();
$position=new Position();
$unit=new Unit();
$offices=new offices();
$staff=new Staff();
$appointment=new Appointment();
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
    $response->send_response(400,"Unable to search staff. staff NID is required.",NULL);
    return;
}




$all_departments="";
$get_departments=$department->get_all_departments($hr_con);
if ($get_departments AND (($get_departments->num_rows)>0) ) 
{
    while ($row = $get_departments->fetch_assoc()) 
    {
        $all_departments=$all_departments.'<option value='.$row['department_id'].'>'.$row['department_name'] .'</option>';
    }
}


$all_grades="";
$get_grades=$grade->get_all_grades($hr_con);
if ($get_grades AND (($get_grades->num_rows)>0) ) 
{
    while ($row = $get_grades->fetch_assoc()) 
    {
        $all_grades=$all_grades.'<option value='.$row["grade_id"].'>'.$row["grade_name"].'</option>';
    }
}



$all_units="";
$get_units=$unit->get_all_units($hr_con);
if ($get_units AND (($get_units->num_rows)>0) ) 
{
    while ($row = $get_units->fetch_assoc()) 
    {
        $all_units=$all_units.'<option value='.$row['unit_id'].'>'.$row['unit_name'] .'</option>';
    }
}


$all_offices="";
$get_offices=$offices->fetch_all($sys_con);
if ($get_offices AND (($get_offices->num_rows)>0) ) 
{
    while ($row = $get_offices->fetch_assoc()) 
    {
        $all_offices=$all_offices.'<option value='.$row['office_code'].'>'.$row['office_name'].'</option>';
    }
}


$all_appointments="";
$get_appointments=$appointment->get_all_staff_appointments($hr_con);
if ($get_appointments AND (($get_appointments->num_rows)>0) ) 
{
    while ($row = $get_appointments->fetch_assoc()) 
    {
        $all_appointments=$all_appointments.'<option value='.$row['appointment_name'].'>';
    }
}

  


$staff->PIN=$pin;
$result=$staff->fetch_staff_by_pin($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {



?>
  <div class="col-md-12 animate__animated  animate__slideInDown">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Edit staff</h5>
                
                <div class="col-md-12 col-lg-12">
  <div class="mb-3 card">
      <div class="card-body">
          <ul class="tabs-animated-shadow nav-justified tabs-animated nav">
              <li class="nav-item">
                  <a role="tab" class="nav-link active" id="tab-c1-0" data-toggle="tab" href="#tab-animated1-0">
                      <span class="nav-text">Basic Info</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-1" data-toggle="tab" href="#tab-animated1-1" onclick="call_pagelet('apis/controllers/staff/crud/fetch_spouse_by_pin.php?pin='+pin.value,'staff_spouse');">
                      <span class="nav-text">Spouse</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-2" data-toggle="tab" href="#tab-animated1-2" onclick="call_pagelet('apis/controllers/staff/crud/fetch_kids_by_pin.php?pin='+pin.value,'staff_kids');">
                      <span class="nav-text">Children</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-3" data-toggle="tab" href="#tab-animated1-3" onclick="call_pagelet('apis/controllers/staff/crud/fetch_education_by_pin.php?pin='+pin.value,'staff_education');">
                      <span class="nav-text">Education</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-4" data-toggle="tab" href="#tab-animated1-4" onclick="call_pagelet('apis/controllers/staff/crud/fetch_language_by_pin.php?pin='+pin.value,'staff_language');">
                      <span class="nav-text">Languages Spoken</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-5" data-toggle="tab" href="#tab-animated1-5" onclick="call_pagelet('apis/controllers/staff/crud/fetch_pic_by_pin.php?pin='+pin.value,'staff_pic');">
                      <span class="nav-text">Staff Picture</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a role="tab" class="nav-link" id="tab-c1-6" data-toggle="tab" href="#tab-animated1-6">
                      <span class="nav-text">Staff Status</span>
                  </a>
              </li>
              
          </ul>
          <div class="tab-content">
              <div class="tab-pane active" id="tab-animated1-0" role="tabpanel">
                <form id="update_basic_data" name="update_basic_data" class="">
                  <div class="position-relative row form-group"><label for="pin" class="col-sm-2 col-form-label"><b>Ghana Card pin</b></label>
                      <div class="col-sm-10">
                        <input class="form-control" name="pin" id="pin" type="text" minlength="15" maxlength="15" pattern="GHA+-[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+-[0-9]" value="<?php echo $row['staff_nid'] ?>" style="background:white;" readonly>
                      </div>
                  </div>
                  <div class="position-relative row form-group"><label for="staff_id" class="col-sm-2 col-form-label"><b>Staff ID</b></label>
                      <div class="col-sm-10">
                        <input style="background:white;" class="form-control" name="staff_id" id="staff_id" type="text" value="<?php echo $row['staff_id'] ?>" readonly>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_title" class="col-sm-2 col-form-label"><b>Title</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_title" id="staff_title" value="<?php echo $row['staff_title'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_surname" class="col-sm-2 col-form-label"><b>Surname</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_surname" id="staff_surname" value="<?php echo $row['staff_surname'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_forenames" class="col-sm-2 col-form-label"><b>Forenames</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_forenames" id="staff_forenames" value="<?php echo $row['staff_forenames'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_dob" class="col-sm-2 col-form-label"><b>Date Of Birth</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="date" class="form-control" name="staff_dob" id="staff_dob" value="<?php echo $row['staff_dob'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="sex" class="col-sm-2 col-form-label"><b>Gender</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select type="select"  name="sex" id="sex" class="custom-select">
                              <option><?php echo $row['staff_gender'] ?></option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>                                                   
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_marital_status" class="col-sm-2 col-form-label"><b>Marital Status</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">   
                          <select type="select"  name="staff_marital_status" id="staff_marital_status" class="custom-select">
                            <option><?php echo $row['staff_marital_stats'] ?></option>
                              <option>Single</option>
                              <option>Married</option>
                              <option>Divorced</option>
                              <option>Widowed</option>
                          </select>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nationality" class="col-sm-2 col-form-label"><b>Nationality</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nationality" id="staff_nationality" value="<?php echo $row['staff_nationality'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_disability" class="col-sm-2 col-form-label"><b>Disability</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select type="select"  name="staff_disability" id="staff_disability" class="custom-select">
                              <option><?php echo $row['staff_disability'] ?></option>
                                <option>Not applicable</option>
                                <option>Amputee</option>
                                <option>Walking disability</option>
                                <option>Deaf</option>
                                <option>Dumb</option>
                                <option>Blind</option>
                            </select>                                                   
                        </div>
                      </div>
                  </div>

                  <hr>

                  <div class="position-relative row form-group"><label for="staff_email" class="col-sm-2 col-form-label"><b>Email</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_email" id="staff_email" value="<?php echo $row['staff_email'] ?>"  >                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="staff_contact" class="col-sm-2 col-form-label"><b>Contact (233XXXXXXXXX)</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="number" class="form-control" name="staff_contact" id="staff_contact" data-toggle="tooltip" data-placement="top" title="Number should start with country code eg:(233)" data-original-title="Number should start with country code eg:(233)" value="<?php echo $row['staff_contact'] ?>">                                                       
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="staff_address" class="col-sm-2 col-form-label"><b>Address</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_address" id="staff_address" value="<?php echo $row['staff_address'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <hr>


                  <div class="position-relative row form-group"><label for="appointment_type" class="col-sm-2 col-form-label"><b>Appointment Type</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="appointment_list" class="form-control" name="appointment_type" id="appointment_type" value="<?php echo $row['appointment_type'] ?>" onchange="if(!($('#appointment_type').val()=='Permanent')){$('#apptype').show();}else{$('#apptype').hide();}">
                            <datalist id="appointment_list" name="appointment_list" >
                              <?php echo $all_appointments; ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group" id="apptype" name="apptype" style="display: None;"><label for="appointment_expiry" class="col-sm-2 col-form-label"><b>Appointment Expiry </b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="date" class="form-control" placeholder="2021-01-20" name="appointment_expiry" id="appointment_expiry" value="<?php echo $row['appointment_expiry'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="office" class="col-sm-2 col-form-label"><b>Office</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="office_list" class="form-control" name="office" id="office"  value="<?php echo $row['office_code'] ?>">
                            <datalist id="office_list" name="office_list" >
                              <?php echo $all_offices; ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="grade" class="col-sm-2 col-form-label"><b>Grade</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="grade_list" class="form-control" name="grade" id="grade" value="<?php echo $row['grade_id'] ?>">
                            <datalist id="grade_list" name="grade_list" >
                              <?php echo $all_grades; ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="department" class="col-sm-2 col-form-label"><b>Department</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="department_list" class="form-control" name="department" id="department" value="<?php echo $row['department_id'] ?>" onchange="call_pagelet('apis/controllers/positions/tables/position_list_search.php?type=Department&id='+this.value,'dpt_postion_list')">
                            <datalist id="department_list" name="department_list" >
                              <?php echo $all_departments; ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="department_position" class="col-sm-2 col-form-label"><b>Department Role</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="dpt_postion_list" class="form-control" name="department_position" id="department_position" value="<?php echo $row['department_position'] ?>">
                            <datalist id="dpt_postion_list" name="dpt_postion_list" >
                              <?php 

                                $position->RELATED_ID=$row['department_id'];
                                $position->POSITION_TYPE="Department";
                                $department_position_list=$position->position_list_search($hr_con);

                                if ($department_position_list AND ($department_position_list->num_rows>0)) 
                                {
                                    $the_list="";
                                    while($position_rows = $department_position_list->fetch_assoc()) 
                                    {
                                        echo '<option value='.$position_rows["position_id"].'>'.$position_rows["position_name"].'</option>';
                                    
                                    }
                                }

                              ?>  
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="unit" class="col-sm-2 col-form-label"><b>Unit</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="unit_list" class="form-control" name="unit" id="unit" value="<?php echo $row['unit_id'] ?>" onchange="call_pagelet('apis/controllers/positions/tables/position_list_search.php?type=Unit&id='+this.value,'unit_postion_list')">
                            <datalist id="unit_list" name="unit_list" >
                              <?php echo $all_units; ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group">
                    <label for="unit_position" class="col-sm-2 col-form-label"><b>Unit Role</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input list="unit_postion_list" class="form-control" name="unit_position" id="unit_position" value="<?php echo $row['unit_position'] ?>">
                            <datalist id="unit_postion_list" name="unit_postion_list" >
                              <?php 

                                $position->RELATED_ID=$row['unit_id'];
                                $position->POSITION_TYPE="Unit";
                                $unit_position_list=$position->position_list_search($hr_con);

                                if ($unit_position_list AND ($unit_position_list->num_rows>0)) 
                                {
                                    $the_list="";
                                    while($position_rows = $unit_position_list->fetch_assoc()) 
                                    {
                                        echo '<option value='.$position_rows["position_id"].'>'.$position_rows["position_name"].'</option>';
                                    
                                    }
                                }

                              ?>
                            </datalist>                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group">
                    <label for="date_joined" class="col-sm-2 col-form-label"><b>Date Recruited</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="date" class="form-control" name="date_joined" id="date_joined"  value="<?php echo $row['staff_date_joined'] ?>" >                                                        
                        </div>
                      </div>
                  </div>

                  <hr>


                  <div class="position-relative row form-group">
                    <label for="staff_ssnit" class="col-sm-2 col-form-label"><b>SSNIT Number</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_ssnit" id="staff_ssnit"  value="<?php echo $row['staff_ssnit'] ?>" >                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group">
                    <label for="staff_tin" class="col-sm-2 col-form-label"><b>Tin Number</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_tin" id="staff_tin"  value="<?php echo $row['staff_tin'] ?>" >                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group" hidden>
                    <label for="payment_mmethod" class="col-sm-2 col-form-label"><b>Payment Method</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                            <select type="select" name="payment_mmethod" id="payment_mmethod" class="custom-select">
                              <option selected><?php echo $row['staff_payment_method'] ?></option>
                                <option>Bank</option>
                                <option>Cash</option>
                                <option>Mobile Money</option>
                            </select>                                                   
                        </div>
                      </div>
                  </div>

                  <div class="alert alert-info fade show" role="alert">
                    <i class="fa fa-info"></i> Bank details
                  </div>

                  <div class="position-relative row form-group">
                    <label for="staff_bank_account" class="col-sm-2 col-form-label"><b>Bank Account Number</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_bank_account" id="staff_bank_account"  value="<?php echo $row['staff_bank_account'] ?>" >                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group">
                    <label for="staff_account_name" class="col-sm-2 col-form-label"><b>Bank Account Name</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_account_name" id="staff_account_name"  value="<?php echo $row['staff_account_name'] ?>" >                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group">
                    <label for="staff_bank_name" class="col-sm-2 col-form-label"><b>Bank Name</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_bank_name" id="staff_bank_name"  value="<?php echo $row['staff_bank_name'] ?>" >                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_bank_branch" class="col-sm-2 col-form-label"><b>Bank Branch</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_bank_branch" id="staff_bank_branch"  value="<?php echo $row['staff_bank_branch'] ?>" >                                                      
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_bank_district" class="col-sm-2 col-form-label"><b>Bank District</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_bank_district" id="staff_bank_district"  value="<?php echo $row['staff_bank_district'] ?>" >                                                      
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="staff_bank_region" class="col-sm-2 col-form-label"><b>Bank Region</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_bank_region" id="staff_bank_region"  value="<?php echo $row['staff_bank_region'] ?>" >                                                      
                        </div>
                      </div>
                  </div>

                  <div class="alert alert-info fade show" role="alert">
                    <i class="fa fa-info"></i> Mobile Money Details
                   </div>

                  <div class="position-relative row form-group"><label for="staff_mobile_money" class="col-sm-2 col-form-label"><b>Mobile Money Number(233XXXXXXXXX)</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="number" class="form-control" name="staff_mobile_money" id="staff_mobile_money" maxlength="13"  value="<?php echo $row['staff_mobile_money'] ?>">                                                        
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="name_on_number" class="col-sm-2 col-form-label"><b>Name on Mobile Money Account</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="name_on_number" id="name_on_number"   value="<?php echo $row['name_on_number'] ?>">                                                      
                        </div>
                      </div>
                  </div>


                  <div class="alert alert-info fade show" role="alert">
                    <i class="fa fa-info"></i> Emergency and Family Details
                   </div>


                  <div class="position-relative row form-group"><label for="staff_mother" class="col-sm-2 col-form-label"><b>Mother</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_mother" id="staff_mother" value="<?php echo $row['staff_mother'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_father" class="col-sm-2 col-form-label"><b>Father</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_father" id="staff_father" value="<?php echo $row['staff_father'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_title" class="col-sm-2 col-form-label"><b>Next of Kin Title</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_title" id="staff_nextofkin_title" value="<?php echo $row['staff_nextofkin_title'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_surname" class="col-sm-2 col-form-label"><b>Next of Kin Surname</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_surname" id="staff_nextofkin_surname" value="<?php echo $row['staff_nextofkin_surname'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_forenames" class="col-sm-2 col-form-label"><b>Next of Kin Forename</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_forenames" id="staff_nextofkin_forenames" value="<?php echo $row['staff_nextofkin_forenames'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_contact" class="col-sm-2 col-form-label"><b>Next of Kin Contact(233XXXXXXXXX)</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="number" class="form-control" name="staff_nextofkin_contact" id="staff_nextofkin_contact" maxlength="13" value="<?php echo $row['staff_nextofkin_contact'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_relation" class="col-sm-2 col-form-label"><b>Relation to Next of Kin</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_relation" id="staff_nextofkin_relation" value="<?php echo $row['staff_nextofkin_relation'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_nextofkin_address" class="col-sm-2 col-form-label"><b>Next of Kin Address</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_address" id="staff_nextofkin_address" value="<?php echo $row['staff_nextofkin_address'] ?>"  >
                                                                                
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="staff_nextofkin_nid" class="col-sm-2 col-form-label"><b>Next of Kin Ghana card</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_nextofkin_nid" id="staff_nextofkin_nid"  minlength="15" maxlength="15" pattern="GHA+-[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+-[0-9]"  value="<?php echo $row['staff_nextofkin_nid'] ?>">
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_title" class="col-sm-2 col-form-label"><b>Emergency Contact Title</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_title" id="staff_emergency_title"  value="<?php echo $row['staff_emergency_title'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_surname" class="col-sm-2 col-form-label"><b>Emergency Contact Surname</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_surname" id="staff_emergency_surname"  value="<?php echo $row['staff_emergency_surname'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_forename" class="col-sm-2 col-form-label"><b>Emergency Contact Forename</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_forename" id="staff_emergency_forename"  value="<?php echo $row['staff_emergency_forename'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_contact" class="col-sm-2 col-form-label"><b>Emergency Contact Number</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="number" class="form-control" name="staff_emergency_contact" id="staff_emergency_contact" maxlength="12"  value="<?php echo $row['staff_emergency_contact'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_relation" class="col-sm-2 col-form-label"><b>Relation to Emergency Contact</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_relation" id="staff_emergency_relation"  value="<?php echo $row['staff_emergency_relation'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>

                  <div class="position-relative row form-group"><label for="staff_emergency_address" class="col-sm-2 col-form-label"><b>Emergency Contact Address</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_address" id="staff_emergency_address"  value="<?php echo $row['staff_emergency_address'] ?>" >
                                                                                
                        </div>
                      </div>
                  </div>


                  <div class="position-relative row form-group"><label for="staff_emergency_nid" class="col-sm-2 col-form-label"><b>Emergency Contact Ghana Card</b></label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="text" class="form-control" name="staff_emergency_nid" id="staff_emergency_nid"  minlength="15" maxlength="15" pattern="GHA+-[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+-[0-9]" value="<?php echo $row['staff_emergency_nid'] ?>">
                                                                                
                        </div>
                      </div>
                  </div>

                  <a href="javascript:void(0);" class="btn btn-primary" id="basicdata_hr_update" name="basicdata_hr_update" onclick="update_form('apis/controllers/staff/crud/update_staff.php','update_basic_data');">Update Basic Data</a>
                                                     
                </form>
              </div>


              <div class="tab-pane" id="tab-animated1-1" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Spouse(s) Information
                    </div>
                  <div class="row" id="staff_spouse" name="staff_spouse">
                    
                  </div>
              </div>

              <div class="tab-pane" id="tab-animated1-2" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Children Information
                    </div>
                  <div class="row" id="staff_kids" name="staff_kids">
                    
                  </div>
              </div>

              <div class="tab-pane" id="tab-animated1-3" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Education Information
                    </div>
                  <div class="row" id="staff_education" name="staff_education">
                    
                  </div>
              </div>

              <div class="tab-pane" id="tab-animated1-4" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Languages Spoken
                    </div>
                  <div class="row" id="staff_language" name="staff_language">
                    
                  </div>
              </div>


              <div class="tab-pane" id="tab-animated1-5" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Staff Picture
                    </div>
                  <div class="row" id="staff_pic" name="staff_pic">
                    
                  </div>
              </div>


              <div class="tab-pane" id="tab-animated1-6" role="tabpanel">
                    <div class="alert alert-info fade show" role="alert">
                      <i class="fa fa-info"></i> Staff Status
                    </div>
                  <div class="row" id="staff_stats" name="staff_stats">

                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <hr>
                      <h2><center><u>Staff Status</u></center></h2>
                    <form id="update_staff_status" name="update_staff_status" class="">
                      

                      <div class="position-relative row form-group"><label for="staff_status" class="col-sm-2 col-form-label"><b>Staff Status</b></label>
                          <div class="col-sm-10">
                            <div class="input-group">   
                              <select type="select"  name="staff_status" id="staff_status" class="custom-select">
                                <option><?php echo $row['staff_status'] ?></option>
                                  <option>Active</option>
                                  <option>Sick Leave</option>
                                  <option>Suspended</option>
                                  <option>Terminated</option>
                                  <option>Retired</option>
                                  <option>Dismissed</option>
                                  <option>Deceased</option>
                                  <option>Maternity Leave</option>
                                  <option>Study Leave</option>
                                  <option>General Leave</option>
                                  <option>Resigned</option>
                              </select>               
                              <input type="password" value="important" id="language_hr_update" name="language_hr_update" hidden readonly>                                                   
                            </div>
                          </div>
                      </div>

                      

                      <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/update_staff_status.php?pin=<?php echo $pin; ?>','update_staff_status');">Update Status</a>
                                                         
                    </form>
                    </div>

                    
                  </div>
              </div>



              
          </div>
      </div>

  </div>
                                       
</div>

            </div>
        </div>
    </div>

<?php 
  }
}
else
{
  $response->send_response(404,"Unable to search staff.",NULL);
}

?>