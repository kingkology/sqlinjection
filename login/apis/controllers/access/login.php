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
    include '../../models/Logs.php';
    include '../../models/Response.php';
    include '../../models/specificextract.php';

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


    //Stringify user inputs
    if(isset($_POST['username']) AND (!trim($_POST['username'])=="") )
    {
        $nid=mysqli_real_escape_string($app_con, $_POST['username']);
    }
    else
    {
        $response->send_response(400,"NID error: Please Fill In Ghana Card Pin",NULL);
        return;
    }

    if(isset($_POST['password']) AND (!trim($_POST['password'])=="") )
    {
        $pword=mysqli_real_escape_string($app_con, $_POST['password']);
    }
    else
    {
        $response->send_response(400,"Password error: Password is required",NULL);
        return;
    }

    //check student pin
    $pattern="/[A-Z]{3}-[0-9]{9}-[0-9]{1}/";
    $check_match= preg_match($pattern,$nid);
    if (!($check_match==1)) 
    {
        $response->send_response(400,"NID error: Kindly enter the right Ghana card pin",NULL);
        return;
    }

    
    //assign values to access class properties
    $access->PIN= $nid;
    $access->PWD=md5($pword);
    $access->DATE_ADDED= $ladate;
    $access->LAST_TIME= $latimez;


    //user query
    try 
    {
        
        $result = $access->login_secure($app_con);
        if ($result) 
        {
            $num = $result->num_rows;
            //check if results were returned and assign to array
            if ($num > 0) 
            {
                while ($row = $result->fetch_assoc()) 
                {

                   
                    $_SESSION['student_id'] = $row['student_id'];
                    $_SESSION['student_name']=$row['student_name'];
                    $_SESSION['student_programme']=$row['programme'];

                    //send success response with redirect url
                    $response->send_response(200,"Success","main/");
                    return;
                    
                }
            }
            else
            {
                $response->send_response(400,"Login Error: User not found in the system. ",NULL);
                return;
            }
        }
        else
        {
            $response->send_response(400,"Login Error: User not found in the system. ".$result,NULL);
            return;
        }

    } 
    catch (Exception $e) 
    {
        $response->send_response(503,"Login Failed: system error",NULL);
        return;
    }


?>