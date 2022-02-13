<?php
 session_start();
include '../../config/Database.php';
include '../../models/logs.php';
include '../../models/Access.php';
include '../../models/Response.php';


$dt=new DateTime('now', new DateTimezone('Africa/Accra'));
$ladate = $dt->format('Y-m-d');
$latodayz = $ladate;
$ladatez = $dt->format('Y-m-d H:i:s');
$latimez = $dt->format('H:i:s');

 //instantiate database and connect
 //instantiate database and connect
 $database=new Database();
 $app_con=$database->connect('app_db');
 //instantiate logs object and assign the connection to it
 $logs=new Logs();
 $access=new Access();
 //instantiate response object and assign the connection to it
 $response=new Response();



 if (isset($_SESSION['staff_nid']) AND (!(trim($_SESSION['staff_nid']=="")) )) 
 {
   //insert log for logout
   $logs->USER= $_SESSION['staff_nid'];
   $logs->ACTION= "Logout";
   $logs->STATEMENT= "User Logged out";
   $logs->DATE= $ladate;
   $logs->TIME= $latimez;
   $logs->add($app_con);

   $access->PIN=$_SESSION['staff_nid'];
   $access->LAST_DATE= $ladate;
   $access->LAST_TIME= $latimez;
   $access->LOGIN_SESSION= 0;
   $access->LOGIN_STATUS= 0;
   $access->update_login_status($app_con);


   $_SESSION['message_type'] = "info";
   $_SESSION['backend_message']="Logout successful";

   //$root="http://10.120.201.5/new_ems/";
   $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
   //$root=$root."/new_ems";
   $response->send_response(200,"Success",$root);
   return;
 }
 else
 {
   //$root="http://10.120.201.5/new_ems/";
     $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
     //$root=$root."/new_ems";
     $response->send_response(200,"Success",$root);
     return;
 }




 

?>
