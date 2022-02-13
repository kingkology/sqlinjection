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
    include '../../../models/Staff.php';
     //get offices models
    include '../../../../../admin_portal/apis/models/offices/offices.php';

    $database=new Database();
    $hr_con=$database->connect('hr_db');
    $sys_con=$database->connect('app_db');
    //instantiate response object and assign the connection to it
    $response=new Response();
    $staff=new Staff();
    //instantiate office class
    $offices=new Offices();


    $christmas="";

    if ($lamonth==12)
    {
      $christmas="christmas";
    }

    //DECLARE AUTO COUNT
    $getid=0;
    //Table array for response payload
    $table_response=array();


    if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") AND ($_SESSION['app_code']=="HOD" OR $_SESSION['app_code']=="DHOD" OR $_SESSION['app_code']=="SUPA") )) 
    {  
      $response->send_response(409,"Illegal access! or No valid authorization detected. Kindly check login or check your access rights.",NULL);
      return;
    }



    if (isset($_POST['start']) AND (!(trim($_POST['start'])==""))) 
    {
        $start = mysqli_real_escape_string($sys_con,$_POST['start']);
    }
    else
    {
      $response->send_response(400,"Unable to search record. start value is required",NULL);
      return;
    }

    if (isset($_POST['limit']) AND (!(trim($_POST['limit'])==""))) 
    {
        $limit = mysqli_real_escape_string($sys_con,$_POST['limit']);
    }
    else
    {

      $response->send_response(400,"Unable to search record. limit value is required",NULL);
      return;
    }


    
    $staff->START=$start;
    $staff->LIMIT=$limit;

    
    $result=$staff->get_contract_expiry_staff($hr_con);

    //echo $result; 

    if ($result) 
    {
      if($result->num_rows>0)
      {

        $data_item=array("th1" =>"No.", "th2" =>"Name","th3" =>"Contact","th4" =>"Office","th5" =>"Staff Department" ,"th6" =>"Contract Expiry","th7" =>"Status");
        $new_data[]=$data_item;
        $table_response[]=$new_data;

        while($row = $result->fetch_assoc()) 
        {
            $new_data=array();
            $getid=$getid+1;
            $pin=$row['staff_nid'];
            $surnamez=$row['staff_surname'];
            $firstnamez=$row['staff_forenames'];
            $contact=$row['staff_contact'];
            $department=$row['department_name'];
            $staff_status=$row['staff_status'];
            $appointment_expiry=$row['appointment_expiry'];

            $staff->PIN=$pin;
            $office=$row['office_code'];
            $user_img=$row['staff_picture'];
            $user_img=($user_img=='None')?'assets/images/staff_images/default.png':'assets/images/staff_images/'.$user_img;

            $offices->OFFICE_CODE=$office;
            $office=$offices->get_office_name($sys_con)." (".$office.")";

            $main_page="../views/general/add_user.php?id=".$pin;


            $data_item=array("type" =>"text","name" =>"no".$getid,"id" =>"no".$getid ,"value" =>$getid);
            $new_data[]=$data_item;


            $data_item=array("type" =>"text","name" =>"user".$getid,"id" =>"user".$getid ,"value" => '<div class="widget-content p-0"><div class="widget-content-wrapper"><div class="widget-content-left mr-3"><div class="widget-content-left"><img width="40" class="rounded-circle" src="'.$user_img.'" alt=""></div></div><div class="widget-content-left flex2"><div class="widget-heading">'.$pin. '</div><div class="widget-subheading opacity-7">'.$firstnamez. ' ' .$surnamez.'</div></div></div></div>' );
            $new_data[]=$data_item;

            $data_item=array("type" =>"text","name" =>"contact".$getid,"id" =>"contact".$getid ,"value" =>$contact );
            $new_data[]=$data_item;

            $data_item=array("type" =>"text","name" =>"office".$getid,"id" =>"office".$getid ,"value" =>$office );
            $new_data[]=$data_item;

            $data_item=array("type" =>"text","name" =>"department".$getid,"id" =>"department".$getid ,"value" =>$department );
            $new_data[]=$data_item;

            $data_item=array("type" =>"text","name" =>"appointment_expiry".$getid,"id" =>"appointment_expiry".$getid ,"value" =>$appointment_expiry );
            $new_data[]=$data_item;


            $data_item=array("type" =>"text","name" =>"staff_status".$getid,"id" =>"staff_status".$getid ,"value" =>$staff_status );
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