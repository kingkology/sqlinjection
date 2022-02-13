<?php
    session_start();
  //header for read api
    // use * under allow access control origin because we will not be accepting any api key using tokens or authorisations
    header('Access-Control-Allow-Origin:*');
    //content type as html
    header('Content-Type:application/json');

    //include our required classes
    include '../../config/Database.php';
    include '../../models/access.php';
    include '../../models/logs.php';
    include '../../models/Response.php';


    $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
    $ladate = $dt->format('Y-m-d');
    $latodayz = $ladate;
    $ladatez = $dt->format('Y-m-d H:i:s');
    $latimez = $dt->format('H:i:s');


    //instantiate database and connect
    $database=new Database();
    $app_con=$database->connect('app_db');
    $hr_con=$database->connect('hr_db');

    //instantiate access object and assign the connection to it
    $access=new Access();
    //instantiate logs object and assign the connection to it
    $logs=new Logs();
    //instantiate response object and assign the connection to it
    $response=new Response();

    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS


//client
    if(isset($_POST['nid']) AND (!trim($_POST['nid'])=="") )
    {
        $nid=mysqli_real_escape_string($app_con, $_POST['nid']);
    }
    else
    {
      $response->send_response(400,"Unable to reset password. NID is required.",NULL);
      return;
    }

    if(isset($_POST['sname']) AND (!trim($_POST['sname'])=="") )
    {
        $sname=mysqli_real_escape_string($app_con, $_POST['sname']);
    }
    else
    {
      $response->send_response(400,"Unable to reset password. Surname is required.",NULL);
      return;
    }


    if(isset($_POST['dob']) AND (!trim($_POST['dob'])=="") )
    {
        $dob=mysqli_real_escape_string($app_con, $_POST['dob']);
    }
    else
    {
      $response->send_response(400,"Unable to reset password. Date of Birth is required.",NULL);
      return;
    }


    if(isset($_POST['uemail']) AND (!trim($_POST['uemail'])=="") )
    {
        $uemail=mysqli_real_escape_string($app_con, $_POST['uemail']);
    }
    else
    {
      $response->send_response(400,"Unable to reset password. Email is required.",NULL);
      return;
    }

   

   //encrypt password
   /* Secure password hash. */
   $hash = password_hash("1234", PASSWORD_DEFAULT);


   $access->PIN= $nid;
   $access->SURNAME=$sname;
   $access->DOB=$dob;
   $access->EMAIL=$uemail;
   $access->PWD=$hash;
   $access->DATE_ADDED= $ladate;
   $access->LAST_TIME= $latimez;


  //check password strength
  $check_user=$access->check_details($hr_con);
  /*echo $check_user;
  return;*/
  if ($check_user<1) 
  {
    $response->send_response(400,"Unable to reset password. Data provided is invalid",NULL);
    return;
  }

 
  //check pin
  $pattern="/GHA-[0-9]{9}-[0-9]{1}/";
  $check_match= preg_match($pattern,$nid);
  if (!($check_match==1)) 
  {
      $response->send_response(400,"NID error: Kindly enter the right Ghana card pin",NULL);
      return;
  }

    
    

    //$old_passwords=$access->get_old_passwords($app_con);
    
    //Client query
    $result = $access->reset_password($app_con);
    if ($result=='Success' ) 
    {

        
        
		  //insert log for update_password
        $logs->USER= $nid;
        $logs->ACTION= "Reset Password";
        $logs->STATEMENT= "User reset password";
        $logs->DATE= $ladate;
        $logs->TIME= $latimez;
        $logs->add($app_con);

        

        $response->send_response(200,"Password Reset Successful. Login with 1234 as the default password",NULL);
        return;
    }
    else
    {
      $response->send_response(400,"Unable to reset password -> ".$result,NULL);
      return;
    }

  


?>