<?php 
function getcount($col,$tname,$sparam,$svalue,$cons)
{

$msg="";
$vals=$svalue;


 
 $mysql_query = "SELECT * FROM ".$tname." WHERE `".$sparam."` = '".$svalue."' ";

//$mysql_query="SELECT ".$col." FROM ".$tname." WHERE ".$sparam."=".$svalue."";
$result = mysqli_query($cons,$mysql_query);

$count= mysqli_num_rows($result);
// If result matched username and password, table row must be 1 row
if($count>0)
{ 

return $count;

}
else
{
	$count=0;
	return $count;
}

    
}



 ?>