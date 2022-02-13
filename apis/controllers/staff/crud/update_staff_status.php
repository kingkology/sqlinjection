<?php
  session_start();
  // required headers for post api
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //include our required classes
  include '../../../config/Database.php';
  include '../../../models/logs.php';
  include '../../../models/Response.php';
  include '../../../models/Staff.php';
  include '../../../../../login/apis/models/Access.php';

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');


  if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") AND ($_SESSION['app_code']=="UO" OR $_SESSION['app_code']=="SUH" OR $_SESSION['app_code']=="UH" OR $_SESSION['app_code']=="DHOD" OR $_SESSION['app_code']=="HOD" OR $_SESSION['app_code']=="SUPA") )) 
  {
    $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
    return;
  }

    
    //instantiate database and connect
    $database=new Database();
    $hr_con=$database->connect('hr_db');
    $sys_con=$database->connect('app_db');


    //instantiate user object and assign the connectio to it
    $staff=new Staff();
    //instantiate logs object and assign the connection to it
    $logs=new Logs();
    //instantiate logs object and assign the connection to it
    $response=new Response();

    $access=new Access();

    $approval="No";


    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data

    if (isset($_GET['pin']) AND (!(trim($_GET['pin'])=="")) ) 
    {
      $pin = mysqli_real_escape_string($hr_con,$_GET['pin']);
      $approval="Approved";
    }
    else
    {
      $response->send_response(400,"Unable to update staff. NID is required.",NULL);
      return;
    }
    
    

    

    if (isset($_POST['staff_status']) AND (!(trim($_POST['staff_status'])=="")) ) 
    {
        $staff_status = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_status']));
    }
    else
    {
        $response->send_response(400,"Unable to update staff status. staff status is required.",NULL);
        return;
    }


    $staff->PIN=$pin;
    $staff->STAFF_STATUS=ucwords($staff_status);

    $staff->ADDED_BY=$_SESSION['staff_nid'];
    $staff->DATE_ADDED=$ladate;
    $staff->TIME_ADDED=$latimez;
    


//check pin
    $count = $staff->check_id($hr_con);
    if ($count<1) 
    {
      $response->send_response(400,"staff NID does not exist",NULL);
      return;
    }


    // query
    $hr_con->autocommit(FALSE);
    $result = $staff->update_staff_status($hr_con);

    if($result=="Success")
    {
        $hr_con->commit();
      //insert log for add user
      $logs->USER= $_SESSION['staff_nid'];
      $logs->ACTION= "Update Status";
      $logs->STATEMENT= "Updated Status for : ".$staff->PIN;
      $logs->DATE= $ladate;
      $logs->TIME= $latimez;
      $logs->add($hr_con);
         

      $response->send_response(201,"Status updated Successfully",NULL);
      return;
    } 
    else 
    {
      $response->send_response(503,"Error Updating Status -> ",NULL);
      return;
    }


?>