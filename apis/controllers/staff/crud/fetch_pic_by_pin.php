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
    $response->send_response(400,"Unable to search staff picture. staff NID is required.",NULL);
    return;
}




$staff->PIN=$pin;
$result=$staff->get_staff_image_full($hr_con);
if ($result AND ($result->num_rows>0) ) 
{
  while($row = $result->fetch_assoc()) 
  {


    if ($row['staff_picture_update']=='Yes') 
    {
      switch ($row['staff_picture']) 
      {
        case 'None':
            ?>
              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:300px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images/default.png" style="height:250px;width:100%;" id="pic_Hold" name="pic_Hold">
                    </div> 
                </a>                                
              </div>

              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:300px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images_temp/<?php echo $row['staff_picture_temp']; ?>" style="height:250px;width:100%;" id="pic_Hold2" name="pic_Hold2">
                        <div class="mb-2 mr-2 badge badge-danger"><p><center>Pending HR approval</center></p></div>
                    </div> 
                </a> 
                <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)">Approve</a></div>
                 <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)">Reject</a></div>  
                         
              </div>
            <?php
          break;
        
        default:
            ?>
              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:300px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images/<?php echo $row['staff_picture']; ?>" style="height:250px;width:100%;" id="pic_Hold" name="pic_Hold">
                    </div> 
                </a>   
                <div class="mb-2 mr-2 badge badge-pill badge-info"><?php echo "Date Added: ".$row['pic_date']; ?></div>                              
              </div>

              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:300px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images_temp/<?php echo $row['staff_picture_temp']; ?>" style="height:250px;width:100%;" id="pic_Hold2" name="pic_Hold2">
                        <div class="mb-2 mr-2 badge badge-danger"><p><center>Pending HR approval</center></p></div>
                    </div> 
                </a> 
                <div class="mb-2 mr-2 badge badge-success"><a class="text-white" href="javascript:void(0)">Approve</a></div>
                 <div class="mb-2 mr-2 badge badge-danger"><a class="text-white" href="javascript:void(0)">Reject</a></div>       
              </div>
            <?php
          break;
      }
    }
    else
    {

      switch ($row['staff_picture']) 
      {
        case 'None':
            ?>
              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:300px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images/default.png" style="height:250px;width:100%;" id="pic_Hold" name="pic_Hold">
                    </div> 
                </a>                                
              </div>
            <?php
          break;
        
        default:
            ?>
              
              <div class="col-lg-2 col-xl-2 col-md-2 animate__animated  animate__jackInTheBox">
                <a href="javascript:void(0);">
                    <div class="card-shadow-success border mb-3 card card-body border-success" style="box-shadow:black 2px 2px 2px 2px;height:295px">
                        <img class="img-responsive" src="../hr_portal/assets/images/staff_images/<?php echo $row['staff_picture']; ?>" style="height:250px;width:100%;" id="pic_Hold" name="pic_Hold">
                    </div> 
                </a>  
                <div class="mb-2 mr-2 badge badge-pill badge-info"><?php echo "Date Added: ".$row['pic_date']; ?></div>                                 
              </div>
            <?php
          break;
      }
    }



  }

}


?>


<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
  <hr>
  <h2><center><u>Upload Staff Picture</u></center></h2>
<form id="add_pic" name="add_pic" class="">
  

  <div class="position-relative row form-group"><label for="staff_picture" class="col-sm-2 col-form-label"><b>Select New Picture</b></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="file" class="form-control" name="staff_picture" id="staff_picture" onchange="readURL(this, 'pic_Hold')" >
          <input type="password" value="important" id="pic_hr_update" name="pic_hr_update" hidden readonly>                                                          
        </div>
      </div>
  </div>

  <a href="javascript:void(0);" class="btn btn-primary" onclick="insert_form('apis/controllers/staff/crud/add_pic.php?pin=<?php echo $pin; ?>','add_pic');">Upload Picture</a>
                                     
</form>
</div>