<?php
  session_start();
  // required headers for post api
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //include our required classes
  include '../../../config/Database.php';
  include '../../../models/Logs.php';
  include '../../../models/Response.php';
  include '../../../models/Department.php';

  //instantiate database and connect
  $database=new Database();
  $hr_con=$database->connect('hr_db');

  //instantiate user object and assign the connectio to it
  $department=new Department();
  //instantiate logs object and assign the connection to it
  $logs=new Logs();
  //instantiate logs object and assign the connection to it
  $response=new Response();

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');


  if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") AND ($_SESSION['app_code']=="UO" OR $_SESSION['app_code']=="SUH" OR $_SESSION['app_code']=="UH" OR $_SESSION['app_code']=="DHOD" OR $_SESSION['app_code']=="HOD" OR $_SESSION['app_code']=="SUPA" OR $_SESSION['app_code']=="SYSA") )) 
  {
    ?>
      <div class="position-relative form-group">
        <h2>
          <center>
            <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-danger">
              Illegal access!: No valid authorization detected. Kindly check login or check your access rights
            </a>
          </center>
        </h2>
      </div>
    <?php
    return;
  }

    

    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data
    if (isset($_GET['department_id']) AND (!(trim($_GET['department_id'])=="")) ) 
    {
        $department_id = mysqli_real_escape_string($hr_con,$_GET['department_id']);
    }
    else
    {
      ?>
        <div class="position-relative form-group">
          <h2>
            <center>
              <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-danger">
                Unable to fetch Department Info. Department id is required.
              </a>
            </center>
          </h2>
        </div>
      <?php
      return;
    }


    $department->ID= $department_id;
    //user query
    $result = $department->get_department_by_id($hr_con);
    if ($result AND ($result->num_rows>0)) 
    {
      while ($row = $result->fetch_assoc()) 
      {
        ?>
          <div class="position-relative form-group">
            <label for="grade_name" class="">Department name</label>
            <input name="department_name" id="grade_name" placeholder="eg. IT Officer" type="text" class="form-control" value="<?php echo $row['department_name']; ?>">
            <input name="department_id" id="department_id" type="text" class="form-control" value="<?php echo $row['department_id'] ?>" hidden>
          </div>
          <a class="mt-1 btn btn-primary text-white" onclick="update_form('apis/controllers/departments/crud/update_department.php','edit_department');">Update</a>
          <a class="mt-1 btn btn-danger text-white" onclick="remove('apis/controllers/departments/crud/delete_department.php',<?php echo $row['department_id']; ?>,'edit_department');">Delete</a>
        <?php
      }
    }
    else 
    {
      ?>
              <div class="position-relative form-group">
                <h2>
                  <center>
                    <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-danger">
                      Unable to fetch Department Info. No department found
                    </a>
                  </center>
                </h2>
              </div>
            <?php
            return;
    }
    


?>