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
  include '../../../models/uploadpdf.php';
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

    $uploadpdf=new Uploadpdf();

    $approval="Pending";


    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data

        if (isset($_GET['pin']) AND (!(trim($_GET['pin'])=="")) ) 
        {
            $pin = mysqli_real_escape_string($hr_con,$_GET['pin']);
            $approval="Approved";
        }
        else
        {
            $response->send_response(400,"Unable to add education. NID is required.",NULL);
            return;
        }


    

    if (isset($_POST['staff_qualifications']) AND (!(trim($_POST['staff_qualifications'])=="")) ) 
    {
        $staff_qualifications = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_qualifications']));
    }
    else
    {
        $response->send_response(400,"Unable to add education. Qualification is required.",NULL);
        return;
    }

    if (isset($_POST['staff_qualification_date']) AND (!(trim($_POST['staff_qualification_date'])=="")) ) 
    {
        $staff_qualification_date = mysqli_real_escape_string($hr_con,$_POST['staff_qualification_date']);
    }
    else
    {
        $response->send_response(400,"Unable to add education. Qualification date is required.",NULL);
        return;
    }


        //upload image
    $mainimg=strtolower($_FILES['staff_qualifications_cert']['name']);
    $the_extension=substr($mainimg, strrpos($mainimg, '.'));
    $mainimg=$pin.substr($mainimg, strrpos($mainimg, '.'));
    

    $staff_qualification_date=date_create($staff_qualification_date);
    $staff_qualification_date=date_format($staff_qualification_date,'Y-m-d');


    $pdf_storage_path = "../../../../assets/images/staff_certs/";

    $staff->CERTIFICATE=$mainimg;


    $autoinc=0;
    $count=0;
    $ncodez=$mainimg;
    $count = $staff->check_certificate_id($hr_con);
    while ( $count >0) 
    {
      $ncodez="";
      $autoinc=($autoinc*1)+(1*1);
      $ncodez=$pin."(".$autoinc.")".$the_extension;
      $staff->CERTIFICATE=$ncodez;
      $count = $staff->check_certificate_id($hr_con);
    }



    $mainimg=$ncodez;

    $staff->PIN=$pin;
    $staff->QUALIFICATION=ucwords($staff_qualifications);
    $staff->CERTIFICATE_DATE=$staff_qualification_date;
    $staff->CERTIFICATE=$mainimg;
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

    //UPLOAD PDF CLASS('STORAGE PATH',' NAME FROM FORM',NAME TO BE GIVEN TO IMAGE,new name)
    $pdfresults=$uploadpdf->upload($pdf_storage_path,'staff_qualifications_cert',$mainimg);

    switch ($pdfresults)
    {
        case 'empty':
          $response->send_response(400,"file is empty",NULL);
          return;
        break;
        case 'not supported':
          $response->send_response(400,"file type not supported",NULL);
          return;
        break;
        case 'success':
            // query
            $hr_con->autocommit(FALSE);
            $result = $staff->add_education($hr_con);

            if($result=="Success")
            {
              
              //insert log for add user
              $logs->USER= $_SESSION['staff_nid'];
              $logs->ACTION= "Add certificate";
              $logs->STATEMENT= "Added certificate for : ".$staff->PIN."(".$staff->QUALIFICATION.")";
              $logs->DATE= $ladate;
              $logs->TIME= $latimez;
              $logs->add($hr_con);

              $response->send_response(201,"Certificate Added Successfully",NULL);
              $hr_con->commit();
              return;
            } 
            else 
            {
              $response->send_response(503,"Error Adding Certificate -> ",NULL);
              return;
            }

        break;             
        default:
            $response->send_response(400,"002 Unknown error->",NULL);
            return;
        break;
    }
    

    


   


?>