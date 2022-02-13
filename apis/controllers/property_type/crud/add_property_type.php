<?php
  session_start();
  // required headers for post api
  //header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //include our required classes
  include '../../../config/Database.php';
  include '../../../models/Logs.php';
  include '../../../models/Response.php';
  include '../../../models/Property_Type.php';

  //instantiate database and connect
  $database=new Database();
  $app_con=$database->connect('app_db');
  //instantiate access object and assign the connection to it
  $property=new Property_Type();
  //instantiate logs object and assign the connection to it
  $logs=new Logs();
  //instantiate response object and assign the connection to it
  $response=new Response();

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');


  if (!((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" ) ) )) 
  {
    $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
    return;
  }

    

    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data
    if (isset($_POST['property_type']) AND (!(trim($_POST['property_type'])=="")) ) 
    {
        $property_type = mysqli_real_escape_string($app_con,strtolower($_POST['property_type']));
    }
    else
    {
        $response->send_response(400,"Unable to add property type. property type value is required.",NULL);
        return;
    }

    if (isset($_POST['publish']) AND (!(trim($_POST['publish'])=="")) ) 
    {
        $publish = mysqli_real_escape_string($app_con,strtolower($_POST['publish']));
    }
    else
    {
        $response->send_response(400,"Unable to add property type. publish status is required.",NULL);
        return;
    }

  


//check 
    $property->PROPERTY_TYPE= ucwords($property_type);
    $property->PUBLISH= ucwords($publish);

    $property->LAST_DATE=$ladate;
    $property->LAST_TIME=$latimez;
    $property->ADDED_BY=$_SESSION['user_id'];

    $count = $property->check_property_type($app_con);
    if ($count>0) 
    {
        $response->send_response(400,"Property type already exists",NULL);
        return;
    }


    //user query
    $result = $property->add_property_type($app_con);

    if($result=="Success")
    {
        //insert log for 
        $logs->USER= $_SESSION['user_id'];
        $logs->ACTION= "Add property type";
        $logs->STATEMENT= "Added property type : ".$property->PROPERTY_TYPE.", ".$property->PUBLISH;
        $logs->DATE= $ladate;
        $logs->TIME= $latimez;
        $logs->add($app_con);
        /*print_r($logs->add($app_con));
        return;*/

        $response->send_response(201,"Property Type Added Successfully",NULL);
        return;
    } 
    else 
    {
        $response->send_response(503,"Error Adding Property Type",NULL);
        return;
    }


?>