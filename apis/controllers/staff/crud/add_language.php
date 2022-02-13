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
            $response->send_response(400,"Unable to add language. NID is required.",NULL);
            return;
        }
    
    

    if (isset($_POST['staff_language']) AND (!(trim($_POST['staff_language'])=="")) ) 
    {
        $staff_language = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_language']));
    }
    else
    {
        $response->send_response(400,"Unable to add language. language is required.",NULL);
        return;
    }

    if (isset($_POST['staff_language_level']) AND (!(trim($_POST['staff_language_level'])=="")) ) 
    {
        $staff_language_level = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_language_level']));
    }
    else
    {
        $response->send_response(400,"Unable to add language. language Level is required.",NULL);
        return;
    }



    $staff->PIN=strtoupper($pin);
    $staff->LANGUAGE=ucwords($staff_language);
    $staff->LANGUAGE_LEVEL=ucwords($staff_language_level);
    $staff->APPROVAL_STATUS=$approval;
    $staff->ACTION_STATUS="None";

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
    $error_ocured="Yes";
    $hr_con->autocommit(FALSE);
    $result = $staff->add_language($hr_con);

    if($result=="Success")
    {
        $hr_con->commit();
      //insert log for add user
      $logs->USER= $_SESSION['staff_nid'];
      $logs->ACTION= "Add language";
      $logs->STATEMENT= "Added language for : ".$staff->PIN."(".$staff->LANGUAGE.")";
      $logs->DATE= $ladate;
      $logs->TIME= $latimez;
      $logs->add($hr_con);
         

      $response->send_response(201,"Language Added Successfully",NULL);
      return;
    } 
    else 
    {
      $response->send_response(503,"Error Adding Language -> ",NULL);
      return;
    }


?>