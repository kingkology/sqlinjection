<?php
    session_start();
  // required headers for post api
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
    $ladate = $dt->format('Y-m-d');
    $lamonth = $dt->format('m');
    $latodayz = $ladate;
    $ladatez = $dt->format('Y-m-d H:i:s');
    $latimez = $dt->format('H:i:s');
    
    //include our required classes
    include '../../../config/Database.php';
    include '../../../models/Response.php';
    include '../../../models/Department.php';

    $database=new Database();
    $hr_con=$database->connect('hr_db');
    //instantiate response object and assign the connection to it
    $response=new Response();
    $department=new Department();

    if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") )) 
    {
        $response->send_response(409,"Illegal access! No valid authorization detected. Kindly check login or check your access rights.",NULL);
        return;
    }


    $christmas="";

    if ($lamonth==12)
    {
      $christmas="christmas";
    }

    //DECLARE AUTO COUNT
    $getid=0;
    //Table array for response payload
    $table_response=array();



    if (isset($_POST['start']) AND (!(trim($_POST['start'])==""))) 
    {
        $start = mysqli_real_escape_string($hr_con,$_POST['start']);
    }
    else
    {
      $response->send_response(400,"Unable to search record. start value is required",NULL);
      return;
    }

    if (isset($_POST['limit']) AND (!(trim($_POST['limit'])==""))) 
    {
        $limit = mysqli_real_escape_string($hr_con,$_POST['limit']);
    }
    else
    {

      $response->send_response(400,"Unable to search record. limit value is required",NULL);
      return;
    }


    $department->START=$start;
    $department->LIMIT=$limit;

    
    $result=$department->department_list($hr_con);

    if ($result) 
    {
      if($result->num_rows>0)
      {

        $data_item=array("th1" =>"No.", "th2" =>"Name", "th3" =>"Total Staff");
        $new_data[]=$data_item;
        $table_response[]=$new_data;

        while($row = $result->fetch_assoc()) 
        {
            $new_data=array();
            $getid=$getid+1;
            $department=$row['department_name'];
            $staff=$row['total_staff'];

            $data_item=array("type" =>"text","name" =>"no".$getid,"id" =>"no".$getid ,"value" =>$getid);
            $new_data[]=$data_item;

            $data_item=array("type" =>"text","name" =>"department".$getid,"id" =>"department".$getid ,"value" =>$department );
            $new_data[]=$data_item;
            
            $data_item=array("type" =>"text","name" =>"staff".$getid,"id" =>"staff".$getid ,"value" =>$staff);
            $new_data[]=$data_item;




            $table_response[]=$new_data;
        
        }

        // set response code - 200 ok
        http_response_code(200);
        echo json_encode($table_response);

      }
      else
      {
        $response->send_response(404,"No data Found",NULL);
        return;  
      }
    }
    else
    {
      $response->send_response(404,"No data Found",NULL);
      return; 
    }




  ?>