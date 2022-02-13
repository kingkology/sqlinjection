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
  include '../../../models/Region.php';

  //instantiate database and connect
  $database=new Database();
  $app_con=$database->connect('app_db');
  //instantiate access object and assign the connection to it
  $region=new Region();
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
    if (isset($_POST['region_name']) AND (!(trim($_POST['region_name'])=="")) ) 
    {
        $region_name = mysqli_real_escape_string($app_con,strtolower($_POST['region_name']));
    }
    else
    {
        $response->send_response(400,"Unable to add region. region name is required.",NULL);
        return;
    }

    if (isset($_POST['region_color']) AND (!(trim($_POST['region_color'])=="")) ) 
    {
        $region_color = mysqli_real_escape_string($app_con,strtolower($_POST['region_color']));
    }
    else
    {
        $response->send_response(400,"Unable to add region. region color is required.",NULL);
        return;
    }

  


//check pin
    $region->REGION_NAME= ucwords($region_name);
    $region->REGION_COLOR= ucwords($region_color);
    $count = $region->check_region_name($app_con);
    if ($count>0) 
    {
        $response->send_response(400,"region already exists",NULL);
        return;
    }


    //user query
    $result = $region->add_region($app_con);

    if($result=="Success")
    {
        //insert log for add user
        $logs->USER= $_SESSION['user_id'];
        $logs->ACTION= "Add region";
        $logs->STATEMENT= "Added region : ".$region->REGION_NAME;
        $logs->DATE= $ladate;
        $logs->TIME= $latimez;
        $logs->add($app_con);

        $response->send_response(201,"Region Added Successfully",NULL);
        return;
    } 
    else 
    {
        $response->send_response(503,"Error Adding Region",NULL);
        return;
    }


?>