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
  include '../../../models/Dataset.php';

  //instantiate database and connect
  $database=new Database();
  $app_con=$database->connect('app_db');
  //instantiate access object and assign the connection to it
  $dataset=new Dataset();
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
        $response->send_response(400,"Unable to add dataset. Property type is required.",NULL);
        return;
    }


    if (isset($_POST['data_name']) AND (!(trim($_POST['data_name'])=="")) ) 
    {
        $data_name = mysqli_real_escape_string($app_con,strtolower($_POST['data_name']));
    }
    else
    {
        $response->send_response(400,"Unable to add dataset. dataset name is required.",NULL);
        return;
    }



    if (isset($_POST['data_type']) AND (!(trim($_POST['data_type'])=="")) ) 
    {
        $data_type = mysqli_real_escape_string($app_con,($_POST['data_type']));
    }
    else
    {
        $response->send_response(400,"Unable to add dataset. Data type is required.",NULL);
        return;
    }


    if (isset($_POST['publish']) AND (!(trim($_POST['publish'])=="")) ) 
    {
        $publish = mysqli_real_escape_string($app_con,strtolower($_POST['publish']));
    }
    else
    {
        $response->send_response(400,"Unable to add dataset. publish status is required.",NULL);
        return;
    }


    

  


    //check 
    $dataset->PROPERTY_TYPE= ucwords($property_type);
    $dataset->DATA_NAME= ucwords($data_name);
    $dataset->DATA_TYPE= ucwords($data_type);
    $dataset->PUBLISH= ucwords($publish);

    $dataset->LAST_DATE=$ladate;
    $dataset->LAST_TIME=$latimez;
    $dataset->ADDED_BY=$_SESSION['user_id'];



    switch (true) 
    {
        case ($dataset->DATA_TYPE=="Radio Button" OR $dataset->DATA_TYPE=="Dropdown"  ):
            if (isset($_POST['multivalue']) AND (!(trim($_POST['multivalue'])=="")) ) 
            {
                $multivalue = mysqli_real_escape_string($app_con,strtolower($_POST['multivalue']));
            }
            else
            {
                $response->send_response(400,"Unable to add dataset. Options for multi option dataset is required.",NULL);
                return;
            }
            break;
        
        default:
            $multivalue = "None";
            break;
    }



    $count = $dataset->check_dataset($app_con);
    if ($count>0) 
    {
        $response->send_response(400,"This dataset already exists for selected property type",NULL);
        return;
    }


    //user query
    $result = $dataset->add_dataset($app_con);

    if($result=="Success")
    {
        $property_dataset_id=$app_con->insert_id;

        $dataset->ID=$property_dataset_id;

        if (!($multivalue == "None")) 
        {
            $multvals=explode(",", $multivalue);
            $countvals=count($multvals);
            for ($i=0; $i < $countvals; $i++) 
            { 
                $dataset->ID=$multvals[$i];
                $mult_result = $dataset->add_multivalue($app_con);
            }

            //insert log for 
            $logs->USER= $_SESSION['user_id'];
            $logs->ACTION= "Add dataset type";
            $logs->STATEMENT= "Added dataset : Property tpye->".$dataset->PROPERTY_TYPE.", Data->".$dataset->DATA_NAME.", Data type->".$dataset->DATA_TYPE.", Multivalues->".$multivalue;
            $logs->DATE= $ladate;
            $logs->TIME= $latimez;
            $logs->add($app_con);
            /*print_r($logs->add($app_con));
            return;*/

            $response->send_response(201,"Dataset Added Successfully".$mult_result,NULL);
            return;

        }
        else
        {
            //insert log for 
            $logs->USER= $_SESSION['user_id'];
            $logs->ACTION= "Add dataset type";
            $logs->STATEMENT= "Added dataset : Property tpye->".$dataset->PROPERTY_TYPE.", Data->".$dataset->DATA_NAME.", Data type->".$dataset->DATA_TYPE.", Multivalues->".$multivalue;
            $logs->DATE= $ladate;
            $logs->TIME= $latimez;
            $logs->add($app_con);
            /*print_r($logs->add($app_con));
            return;*/

            $response->send_response(201,"Dataset Added Successfully",NULL);
            return;
        }


        
    } 
    else 
    {
        $response->send_response(503,"Error Adding Dataset",NULL);
        return;
    }


?>