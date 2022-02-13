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
      $response->send_response(400,"Unable to add spouse. NID is required.",NULL);
      return;
    }
    
    

    

    if (isset($_POST['staff_spouse_surname']) AND (!(trim($_POST['staff_spouse_surname'])=="")) ) 
    {
        $staff_spouse_surname = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_spouse_surname']));
    }
    else
    {
        $response->send_response(400,"Unable to add spouse. spouse Surname is required.",NULL);
        return;
    }

    if (isset($_POST['staff_spouse_forenames']) AND (!(trim($_POST['staff_spouse_forenames'])=="")) ) 
    {
        $staff_spouse_forenames = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_spouse_forenames']));
    }
    else
    {
        $response->send_response(400,"Unable to add spouse. spouse Forenames is required.",NULL);
        return;
    }

    if (isset($_POST['staff_spouse_dob']) AND (!(trim($_POST['staff_spouse_dob'])=="")) ) 
    {
        $staff_spouse_dob = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_spouse_dob']));
    }
    else
    {
        $response->send_response(400,"Unable to add spouse. spouse Date Of Birth is required.",NULL);
        return;
    }


    if (isset($_POST['staff_spouse_contact']) AND (!(trim($_POST['staff_spouse_contact'])=="")) ) 
    {
        $staff_spouse_contact = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_spouse_contact']));
    }
    else
    {
        $response->send_response(400,"Unable to add spouse. spouse contact is required.",NULL);
        return;
    }


    if (isset($_POST['staff_spouse_address']) AND (!(trim($_POST['staff_spouse_address'])=="")) ) 
    {
        $staff_spouse_address = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_spouse_address']));
    }
    else
    {
        $response->send_response(400,"Unable to add spouse. spouse address is required.",NULL);
        return;
    }


    $staff_spouse_dob=date_create($staff_spouse_dob);
    $staff_spouse_dob=date_format($staff_spouse_dob,'Y-m-d');


    $staff->PIN=$pin;
    $staff->SPOUSE_SURNAME=ucwords($staff_spouse_surname);
    $staff->SPOUSE_FORENAME=ucwords($staff_spouse_forenames);
    $staff->FULLNAME=ucwords($staff_spouse_surname)." ".ucwords($staff_spouse_forenames);
    $staff->SPOUSE_DOB=$staff_spouse_dob;
    $staff->SPOUSE_CONTACT=$staff_spouse_contact;
    $staff->SPOUSE_ADDRESS=$staff_spouse_address;
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
    $hr_con->autocommit(FALSE);
    $result = $staff->add_spouse($hr_con);

    if($result=="Success")
    {
        $hr_con->commit();
      //insert log for add user
      $logs->USER= $_SESSION['staff_nid'];
      $logs->ACTION= "Add spouse";
      $logs->STATEMENT= "Added spouse for : ".$staff->PIN."(".$staff->FULLNAME.")";
      $logs->DATE= $ladate;
      $logs->TIME= $latimez;
      $logs->add($hr_con);
         

      $response->send_response(201,"Spouse Added Successfully",NULL);
      return;
    } 
    else 
    {
      $response->send_response(503,"Error Adding Spouse -> ",NULL);
      return;
    }


?>