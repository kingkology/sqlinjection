<?php 
session_start();
include '../../config/Database.php';
 //instantiate database and connect
 $database=new Database();
 $sys_con=$database->connect('app_db');

try 
{

	if(isset($_SESSION['staff_nid'] ) ) 
	{
		$nid=$_SESSION['staff_nid'];
		$logstatz=$_SESSION['user_session_id'];
		$sql="SELECT user_session_id FROM logins WHERE (staff_nid='$nid' )";
		$result=mysqli_query($sys_con,$sql);
		if($result->num_rows>0)
		{
			while($row = $result->fetch_assoc()) 
			{
				if ($row['user_session_id']==$logstatz) 
				{

				}
				else
				{
					session_unset(); 

					$_SESSION['message_type'] = "info";
					$_SESSION['backend_message']="Your Account Session Has Expired.";

					echo "out";
					return;
				}

			}

		}

	} 
	else 
	{
		session_unset(); 
		$_SESSION['message_type'] = "info";
		$_SESSION['backend_message']="No Session Found. Please Login";
		echo "out";
		return;
	}


} 
catch (Exception $e) 
{
	echo $e;
}


?>
