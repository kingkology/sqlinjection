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
  include '../../../models/Land.php';

  include '../../../models/Uploadimg.php';

  //instantiate database and connect
  $database=new Database();
  $app_con=$database->connect('app_db');
  //instantiate access object and assign the connection to it
  $property_data=new Land();
  //instantiate logs object and assign the connection to it
  $logs=new Logs();
  //instantiate response object and assign the connection to it
  $response=new Response();

  $uploadimg=new Uploadimg();

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');


  if (!((isset($_SESSION['user_id'])  ) )) 
  {
    $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
    return;
  }


  if ($_POST) 
  {

    $property_data->PROPERTY_TYPE= "Land";
    $property_data->ESTIMATED_VALUE= 0.00;
    $property_data->USER_ROLE= $_SESSION['user_type'];
    $property_data->REMARKS= "None";

    $property_data->RESIDENT_TYPE_OTHER= "None";

    $property_data->USER_ID= $_SESSION['user_id'];
    $property_data->DATE_ADDED= $ladate;
    $property_data->TIME_ADDED= $latimez;


    foreach ($_POST as $key => $value) 
    {
        if (!( (trim($value)=="")  ) )
        {

            $element_name=strtoupper($key);
            ${$key}=(mysqli_real_escape_string($app_con, $value));
            $property_data->$element_name=ucwords(${$key});

           
        }
        else
        {
            $element_name=ucfirst($key);
            $message=str_replace("_", " ", $element_name)." is required.\n";

            $response->send_response(400,"OOPS! Error occured. ".$message,NULL);
            return;
        }
    }
      
  }
  else
  {
    $response->send_response(400,"OOPS! No parameters detected.",NULL);
    return;
  }


  if (!((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" OR $_SESSION['user_type']=="District Supervisor" OR $_SESSION['user_type']=="Regional Supervisor"  OR $_SESSION['user_type']=="National Supervisor"  ) ))) 
  {

    $property_data->REGION=$_SESSION['user_region'];
    $property_data->DISTRICT=$_SESSION['user_district'];

  }


  
    if (!($_FILES['image1']['name'])) 
    {
        $response->send_response(400,"OOPS! Image 1 not detected.",NULL);
        return;
    }
    else
    {
        $mainimg1=strtolower($_FILES['image1']['name']);
        $file_ext1 = substr($mainimg1, strrpos($mainimg1, '.'));
        $file_tmp1= $_FILES['image1']['tmp_name'];
        $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
        $data1 = file_get_contents($file_tmp1);
        $base641 = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
    }

    if (!($_FILES['image2']['name'])) 
    {
        $response->send_response(400,"OOPS! Image 2 not detected.",NULL);
        return;
    }
    else
    {
        $mainimg2=strtolower($_FILES['image2']['name']);
        $file_ext2 = substr($mainimg2, strrpos($mainimg2, '.'));
        $file_tmp2= $_FILES['image2']['tmp_name'];
        $type2 = pathinfo($file_tmp2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($file_tmp2);
        $base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);
    }

    if (!($_FILES['image3']['name'])) 
    {
        $response->send_response(400,"OOPS! Image 3 not detected.",NULL);
        return;
    }
    else
    {
        $mainimg3=strtolower($_FILES['image3']['name']);
        $file_ext3 = substr($mainimg3, strrpos($mainimg3, '.'));
        $file_tmp3= $_FILES['image3']['tmp_name'];
        $type3 = pathinfo($file_tmp3, PATHINFO_EXTENSION);
        $data3 = file_get_contents($file_tmp3);
        $base643 = 'data:image/' . $type3 . ';base64,' . base64_encode($data3);

    }

    

    //check
    
    /*$count = $property_data->check_property_data($app_con);
    if ($count>0) 
    {
        $response->send_response(400,"Property already added",NULL);
        return;
    }*/



    /*if (!((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" OR $_SESSION['user_type']=="District Supervisor" OR $_SESSION['user_type']=="Regional Supervisor"  OR $_SESSION['user_type']=="National  Supervisor"  ) ))) 
    {
        $property_data->USER_ROLE= $_SESSION['user_type'];
    }*/


    $app_con -> autocommit(FALSE);

    $error_occured=false;

    //user query
    $result = $property_data->add_property($app_con);

    if($result=="Success")
    {

        $property_data->ID=$app_con->insert_id;

        if (((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" OR $_SESSION['user_type']=="District Supervisor" OR $_SESSION['user_type']=="Regional Supervisor"  OR $_SESSION['user_type']=="National Supervisor"  ) ))) 
        {
            $review = $property_data->add_review($app_con);
            if (!($review=="Success")) 
            {
                $error_occured=true;
                $app_con ->rollback();
            }
        }
        

        switch (true) 
        {
            case $error_occured:
                $response->send_response(503,"Error Adding Land -> "/*.$review*/,NULL);
                $app_con ->rollback();
                return;
            break;
            
            default:

                $property_data->IMAGE_DATA=$base641;
                $land_img = $property_data->add_image($app_con);
                if (!($land_img=="Success")) 
                {
                    $error_occured=true;
                    $app_con ->rollback();
                }
                else
                {   
                    $property_data->IMAGE_DATA=$base642;
                    $land_img = $property_data->add_image($app_con);
                    if (!($land_img=="Success")) 
                    {
                        $error_occured=true;
                        $app_con ->rollback();
                    }
                    else
                    {
                        $property_data->IMAGE_DATA=$base643;
                        $land_img = $property_data->add_image($app_con);
                        if (!($land_img=="Success")) 
                        {
                            $error_occured=true;
                            $app_con ->rollback();
                        }
                    }
                }

                if (!($error_occured==true)) 
                {
                    //insert log for add user
                    $logs->USER= $_SESSION['user_id'];
                    $logs->ACTION= "Add property";
                    $logs->STATEMENT= "Added property : Property Type -> Land, Region->".$property_data->REGION.", District->".$property_data->DISTRICT.", Town->".$property_data->TOWN.", Allotee->".$property_data->ALLOTEE;
                    $logs->DATE= $ladate;
                    $logs->TIME= $latimez;
                    $add_log=$logs->add($app_con);
                    if (!($add_log=="Success")) 
                    {
                        $error_occured=true;
                        $app_con ->rollback();
                        $response->send_response(503,"Error Adding Land -> "/*.$add_log*/,NULL);
                        return;
                    }
                    else
                    {   
                        $app_con -> commit();
                        $response->send_response(201,"Land Added Successfully",NULL);
                        return;
                    }
                    /*print_r($logs->add($app_con));
                    return;*/   
                }
                else
                {   
                    $app_con -> rollback();
                    $response->send_response(503,"Error Adding Land -> "/*.$add_log*/,NULL);
                    return;
                }

                 
            break;
        }



        
    } 
    else 
    {
        $response->send_response(503,"Error Adding Office -> "/*.$result*/,NULL);
        return;
    }


?>