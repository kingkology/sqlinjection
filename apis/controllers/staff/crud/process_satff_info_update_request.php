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


    if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") AND ($_SESSION['app_code']=="UO" OR $_SESSION['app_code']=="UH" OR $_SESSION['app_code']=="DHOD" OR $_SESSION['app_code']=="HOD" OR $_SESSION['app_code']=="SUPA") )) 
    {
      $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
      return;
    }


    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data

    if (isset($_GET['type']) AND (!(trim($_GET['type'])=="")) ) 
    {
      $type = mysqli_real_escape_string($hr_con,$_GET['type']);
      
    }
    else
    {
      $response->send_response(400,"Unable to process request. Request type is required.",NULL);
      return;
    }

    if (isset($_GET['status']) AND (!(trim($_GET['status'])=="")) ) 
    {
      $status = mysqli_real_escape_string($hr_con,$_GET['status']);
      
    }
    else
    {
      $response->send_response(400,"Unable to process request. Request status is required.",NULL);
      return;
    }


    if (isset($_GET['id']) AND (!(trim($_GET['id'])=="")) ) 
    {
      $id = mysqli_real_escape_string($hr_con,$_GET['id']);
      
    }
    else
    {
      $response->send_response(400,"Unable to process request. Request id is required.",NULL);
      return;
    }


    


    if (isset($_GET['staff']) AND (!(trim($_GET['staff'])=="")) ) 
    {
      $staff_id = mysqli_real_escape_string($hr_con,$_GET['staff']);
      
    }
    else
    {
      $response->send_response(400,"Unable to process request. Staff id is required.",NULL);
      return;
    }



    switch ($type) 
    {
      case 'spouse':
        if ($status=="approve") 
        {
          $approval="Approved";
        }
        elseif ($status=="reject") 
        {
          $approval="Rejected";
        }
        else
        {
          $response->send_response(400,"Unable to process request. Request status is invalid.",NULL);
          return;
        }

        if (isset($_GET['spouse_name']) AND (!(trim($_GET['spouse_name'])=="")) ) 
        {
          $details = mysqli_real_escape_string($hr_con,$_GET['spouse_name']);
          
        }
        else
        {
          $response->send_response(400,"Unable to process request. Spouse name is required.",NULL);
          return;
        }

        $staff->APPROVAL_STATUS=$approval;
        $staff->SPOUSE_ID=$id;
        $result = $staff->process_spouse_request($hr_con);
        
        break;


        case 'certificate':
          if ($status=="approve") 
          {
            $approval="Approved";
          }
          elseif ($status=="reject") 
          {
            $approval="Rejected";
          }
          else
          {
            $response->send_response(400,"Unable to process request. Request status is invalid.",NULL);
            return;
          }

          if (isset($_GET['cert_name']) AND (!(trim($_GET['cert_name'])=="")) ) 
          {
            $details = mysqli_real_escape_string($hr_con,$_GET['cert_name']);
            
          }
          else
          {
            $response->send_response(400,"Unable to process request. certificate data is required.",NULL);
            return;
          }

          $staff->APPROVAL_STATUS=$approval;
          $staff->CERTIFICATE_ID=$id;
          $result = $staff->process_certificate_request($hr_con);
          
          break;


        case 'child':
          if ($status=="approve") 
          {
            $approval="Approved";
          }
          elseif ($status=="reject") 
          {
            $approval="Rejected";
          }
          else
          {
            $response->send_response(400,"Unable to process request. Request status is invalid.",NULL);
            return;
          }

          if (isset($_GET['child_name']) AND (!(trim($_GET['child_name'])=="")) ) 
          {
            $details = mysqli_real_escape_string($hr_con,$_GET['child_name']);
            
          }
          else
          {
            $response->send_response(400,"Unable to process request. child data is required.",NULL);
            return;
          }

          $staff->APPROVAL_STATUS=$approval;
          $staff->CHILD_ID=$id;
          $result = $staff->process_child_request($hr_con);
          
          break;
      
      default:
        return;
        break;
    }
    
    
    

    if($result=="Success")
    {
      //insert log
      $logs->USER= $_SESSION['staff_nid'];
      $logs->ACTION= ucwords($status)." ".ucwords($type)." Request";
      $logs->STATEMENT= ucwords($status)." Request for adding ".$type. "(Staff: ".$staff_id." ".$type.": ".$details.")";
      $logs->DATE= $ladate;
      $logs->TIME= $latimez;
      $logs->add($hr_con);
         

      $response->send_response(201,"Request updated Successfully ".$approval,NULL);
      return;
    } 
    else 
    {
      $response->send_response(503,"Error Updating Request -> ",NULL);
      return;
    }


?>