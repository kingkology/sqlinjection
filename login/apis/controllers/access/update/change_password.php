<?php
    session_start();
  //header for read api
    // use * under allow access control origin because we will not be accepting any api key using tokens or authorisations
    header('Access-Control-Allow-Origin:*');
    //content type as html
    header('Content-Type:application/json');

    //include our required classes
    include '../../config/Database.php';
    include '../../models/Access.php';
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

    //instantiate access object and assign the connection to it
    $access=new Access();
    //instantiate logs object and assign the connection to it
    $logs=new Logs();
    //instantiate response object and assign the connection to it
    $response=new Response();

    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS



    


//client
    if(isset($_POST['old_password']) AND (!trim($_POST['old_password'])=="") )
    {
        $old_password=mysqli_real_escape_string($app_con, $_POST['old_password']);
    }
    else
    {
      $response->send_response(400,"Unable to change password. Old password is required.",NULL);
      return;
    }

    if(isset($_POST['new_password']) AND (!trim($_POST['new_password'])=="") )
    {
        $new_password=mysqli_real_escape_string($app_con, $_POST['new_password']);
    }
    else
    {
      $response->send_response(400,"Unable to change password. New password is required.",NULL);
      return;
    }


    if(isset($_POST['confirm_password']) AND (!trim($_POST['confirm_password'])=="") )
    {
        $confirm_password=mysqli_real_escape_string($app_con, $_POST['confirm_password']);
    }
    else
    {
      $response->send_response(400,"Unable to change password. Confirm password is required.",NULL);
      return;
    }


    if (!(password_verify($old_password, $_SESSION['current_pwd']) )) 
    {
      $response->send_response(400,"Unable to change password. Old password is invalid.",NULL);
      return;
    }

   

   if (!($new_password==$confirm_password)) 
   {
      $response->send_response(400,"Unable to change password. New passwords do not match.",NULL);
      return;
   }


   if (($new_password==$old_password)) 
   {
      $response->send_response(400,"Unable to change password. Password is similar to old password.",NULL);
      return;
   }


  if ((password_verify($new_password, $_SESSION['current_pwd']))) 
  {
    $response->send_response(400,"Unable to change password. New password can not be the same as old password",NULL);
    return;
  }


  if (!(isset($_SESSION['staff_id']))) 
  {
    $response->send_response(400,"Unable to change password. User ID is required",NULL);
    return;

  }


  //check password strength
  $pwd_strength=password_strength($new_password);

  switch (true) 
  {
    case ($pwd_strength=='weak'):
      $response->send_response(400,"Unable to change password. Weak passwords are unacceptable",NULL);
      return;
      break;
    
    default:
      
      break;
  }
    
    //encrypt password
    /* Secure password hash. */
    $hash = password_hash($new_password, PASSWORD_DEFAULT);

    $access->USER_ID= $_SESSION['staff_id'];
    $access->PWD=$hash;
    $access->DATE_ADDED= $ladate;
    $access->LAST_TIME= $latimez;

    //$old_passwords=$access->get_old_passwords($app_con);
    
    //Client query
    $result = $access->change_password($app_con);
    if ($result=='Success' ) 
    {

        unset($_SESSION['current_pwd']);
        $_SESSION['current_pwd']=$access->PWD;
        //$access->add_new_password($app_con);

        
		//insert log for update_password
        $logs->USER= $_SESSION['staff_id'];
        $logs->ACTION= "Update Password";
        $logs->STATEMENT= "User updated password";
        $logs->DATE= $ladate;
        $logs->TIME= $latimez;
        $logs->add($app_con);

        if(isset($_SESSION['change_default'])){unset($_SESSION['change_default']);}

        $response->send_response(200,"Password Updated Successfully",NULL);
        return;
    }
    else
    {
      switch ($result) {
        case 'No Data Modified':
          $response->send_response(400,"No data modified",NULL);
          return;
          break;
        
        default:
          $response->send_response(400,"Unable to updated password",NULL);
          return;
          break;
      }
      
    }

  


?>