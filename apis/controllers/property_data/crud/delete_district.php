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
    $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
    return;
  }


    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data
    

    if (isset($_POST['id']) AND (!(trim($_POST['id'])=="")) ) 
    {
        $id = mysqli_real_escape_string($hr_con,$_POST['id']);
    }
    else
    {
      $response->send_response(400,"Unable to Delete Department. Department id is required.",NULL);
      return;
    }



  //check 
    $department->ID= $id;
    $count = $department->check_department_id($hr_con);
    if ($count<1) 
    {
      $response->send_response(400,"Department does not exists",NULL);
      return;
    }


    //user query
    $result = $department->delete_department($hr_con);

    if($result=="Success")
    {
        //insert log for add user
        $logs->USER= $_SESSION['staff_nid'];
        $logs->ACTION= "Delete Department";
        $logs->STATEMENT= "Deleted Department : ".$department->DEPARTMENT_NAME.",".$department->ID;
        $logs->DATE= $ladate;
        $logs->TIME= $latimez;
        $logs->add($hr_con);

        $response->send_response(201,"Department Deleted Successfully",NULL);
        return;
    } 
    else 
    {
        $response->send_response(503,"Error Deleting Department",NULL);
        return;
    }


?>