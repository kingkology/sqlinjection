<?php
    session_start();
  //header for read api
    // use * under allow access control origin because we will not be accepting any api key using tokens or authorisations
    header('Access-Control-Allow-Origin:*');
    //content type as html
    header('Content-Type:application/json');
 

    //include our required classes
    include '../../config/Database.php';
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
    //instantiate logs object and assign the connection to it
    $logs=new Logs();
    //instantiate response object and assign the connection to it
    $response=new Response();


    //No stringify function and validation
       
    $nid= $_POST['username'];
    //unencrypt user password
    $pword=$_POST['password'];

    //user query
    try 
    {


        $sql = "SELECT student_id, student_name, programme FROM students_vulnerable WHERE student_id='".$nid."' AND student_pwd='".$pword."'";
        $result = mysqli_query($app_con, $sql);

        /*echo $sql;
        return;*/

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
            $response->send_response(400,"Login Error: User not found in the system. ",NULL);
            return;
        }

    } 
    catch (Exception $e) 
    {
        $response->send_response(503,"Login Failed: system error",NULL);
        return;
    }


?>