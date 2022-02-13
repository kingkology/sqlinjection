<?php 
function getvalue($col,$tname,$sparam,$svalue,$cons)
{

$msg="";
$vals=$svalue;


 
 $mysql_query = "SELECT * FROM ".$tname." WHERE `".$sparam."` LIKE '%".$svalue."%' ";
//$mysql_query="SELECT ".$col." FROM ".$tname." WHERE ".$sparam."=".$svalue."";
$result = mysqli_query($cons,$mysql_query);

$count= mysqli_num_rows($result);
// If result matched username and password, table row must be 1 row
if($count>0)
{ 
while($row = $result->fetch_assoc()) 
 {
 	$msg=$row[''.$col.''];
 	
 	return $msg;
 }
 

}
else
{
	return $svalue;
}

    
}



 ?>