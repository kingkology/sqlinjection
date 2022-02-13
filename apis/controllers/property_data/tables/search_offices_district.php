<?php 
session_start();
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include our required classes
include '../../../config/Database.php';
include '../../../models/Logs.php';
include '../../../models/Response.php';
include '../../../models/Office.php';

//instantiate database and connect
$database=new Database();
$app_con=$database->connect('app_db');

$office=new Office();


if (!((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" OR $_SESSION['user_type']=="National Supervisor" OR $_SESSION['user_type']=="National Officer" ) ) )) 
{
	?>
	    <div class="alert alert-danger fade show" role="alert">
	        <h4 class="alert-heading">Illegal access!</h4>
	        <p>No valid authorization detected. Kindly check login or check your access rights.</p>
	    </div>
	<?php
	return;
}

$count_it=0;

if (isset($_GET['start']) AND !($_GET['start']=="")) {$start = htmlspecialchars($_GET['start']);}else{$start=0;}
if (isset($_GET['limit']) AND !($_GET['limit']=="")) {$limit = htmlspecialchars($_GET['limit']);}else{$limit=50;}


if (isset($_GET['property_type']) AND (!(trim($_GET['property_type'])=="")) ) 
{
    $property_type = mysqli_real_escape_string($app_con,($_GET['property_type']));
}
else
{
	?>
	    <div class="alert alert-danger fade show" role="alert">
	        <h4 class="alert-heading">Unable to search records.</h4>
	        <p>Property type is required.</p>
	    </div>
	<?php
	return;

}


if (isset($_GET['filter_field']) AND (!(trim($_GET['filter_field'])=="")) ) 
{
    $filter_field = mysqli_real_escape_string($app_con,($_GET['filter_field']));
}
else
{
	?>
	    <div class="alert alert-danger fade show" role="alert">
	        <h4 class="alert-heading">Unable to search records.</h4>
	        <p>Search field is required.</p>
	    </div>
	<?php
	return;
    
}


if (isset($_GET['filter_type']) AND (!(trim($_GET['filter_type'])=="")) ) 
{
    $filter_type = mysqli_real_escape_string($app_con,($_GET['filter_type']));
}
else
{
	?>
	    <div class="alert alert-danger fade show" role="alert">
	        <h4 class="alert-heading">Unable to search records.</h4>
	        <p>Search type is required.</p>
	    </div>
	<?php
	return;
}


if (isset($_GET['filter_value']) AND (!(trim($_GET['filter_value'])=="")) ) 
{
    $filter_value = mysqli_real_escape_string($app_con,($_GET['filter_value']));
}
else
{
	?>
	    <div class="alert alert-danger fade show" role="alert">
	        <h4 class="alert-heading">Unable to search records.</h4>
	        <p>Search value is required.</p>
	    </div>
	<?php
	return;

}


$conjunction="AND";

$the_type="";

switch ($filter_type) 
{
	case '1':
		$the_type='LIKE';
	break;
	case '2':
		$the_type='=';
	break;
	case '3':
		$the_type='<';
	break;
	case '4':
		$the_type='<=';
	break;
	case '5':
		$the_type='>';
	break;
	case '6':
		$the_type='>=';
	break;
	case '7':
		$the_type='!=';
	break;
	
	default:
		?>
		    <div class="alert alert-danger fade show" role="alert">
		        <h4 class="alert-heading">Unable to search records.</h4>
		        <p>Invalid search type found</p>
		    </div>
		<?php
		return;
	break;
}


$office->REGION=$_SESSION['user_region'];

$office->DISTRICT=$_SESSION['user_district'];

if (strtolower($filter_field)=="district") 
{
	$filter_field=$office->DISTRICT;
}

if (strtolower($filter_field)=="region") 
{
	$filter_field=$office->REGION;
}


$office->QUERY_FIELD=str_replace(" ", "_", strtolower($filter_field));
$office->QUERY_TYPE=$the_type;
$office->QUERY_VALUE=$filter_value;


$result=$office->search_property_region($app_con);

/*echo $result;
return;*/

if ($result AND (($result->num_rows>0)) ) 
{
?>

	<thead>

		<tr>
			<th class="col_numb border border-b-2 dark:border-dark-5 whitespace-no-wrap">#</th>
	        <th class="col_edit border border-b-2 dark:border-dark-5 whitespace-no-wrap">View</th>
	        <th class="col_region border border-b-2 dark:border-dark-5 whitespace-no-wrap">Region</th>
	        <th class="col_district border border-b-2 dark:border-dark-5 whitespace-no-wrap">District</th>
	        <th class="col_town border border-b-2 dark:border-dark-5 whitespace-no-wrap">Town</th>
	        <th class="col_neighbourhood border border-b-2 dark:border-dark-5 whitespace-no-wrap">Neighbourhood</th>
	        <th class="col_metro border border-b-2 dark:border-dark-5 whitespace-no-wrap">Metropolitan</th>
	        <th class="col_mun border border-b-2 dark:border-dark-5 whitespace-no-wrap">Municipal</th>
	        <th class="col_allotee border border-b-2 dark:border-dark-5 whitespace-no-wrap">Allotee</th>
	        <th class="col_n_storey border border-b-2 dark:border-dark-5 whitespace-no-wrap">Number of Storey</th>
	        <th class="col_prop_grade border border-b-2 dark:border-dark-5 whitespace-no-wrap">Property Grade</th>
	        <th class="col_prop_con border border-b-2 dark:border-dark-5 whitespace-no-wrap">Property Condition</th>
	        <th class="col_status border border-b-2 dark:border-dark-5 whitespace-no-wrap">Status</th>
	        <th class="col_encumbs border border-b-2 dark:border-dark-5 whitespace-no-wrap">Encumbrances</th>
	        <th class="col_encroachment border border-b-2 dark:border-dark-5 whitespace-no-wrap">Encroachment</th>
	        <th class="col_est_val border border-b-2 dark:border-dark-5 whitespace-no-wrap">Estimated Value</th>
	    </tr>
			</thead>
			<tbody id="listz" name= "listz">


<?php

	while($row = $result->fetch_assoc()) 
	{
		$count_it++;

		?>
		<tr>
		<td style="width:100px" class='col_numb'><?php echo $count_it; ?></td>
		<td style="width:100px" class='col_edit'>
			<a  data-toggle="modal" data-target="#portfolioModal4" class="btn btn-sm btn-info" title="edit" style="margin-bottom:3px" id="notprint" onclick='clear_storage();call_into_innerhtml("../apis/members/members.php?value1=<?php echo $row["data_id"]; ?>"+"&typez=fetch_members","editmember");'><i class="fa fa-eye"></i></a>
		</td>
		<td style="width:100px" class='col_region'><?php echo $row['region']; ?></td>
		<td style="width:100px" class='col_district'><?php echo $row['district']; ?></td>
		<td style="width:100px" class='col_town'><?php echo $row['town']; ?></td>
		<td style="width:100px" class='col_neighbourhood'><?php echo $row['neighbourhood']; ?></td>
		<td style="width:100px" class='col_metro'><?php echo $row['metropolitan']; ?></td>
		<td style="width:100px" class='col_mun'><?php echo $row['municipal']; ?></td>
		<td style="width:100px" class='col_allotee'><?php echo $row['allotee']; ?></td>
		<td style="width:100px" class='col_n_storey'><?php echo $row['number_of_storey']; ?></td>
		<td style="width:100px" class='col_prop_grade'><?php echo ($row['property_grade']=="Other") ? $row['grade_other'] : $row['property_grade'] ; ?></td>
		<td style="width:100px" class='col_prop_con'><?php echo $row['prop_condition']; ?></td>
		<td style="width:100px" class='col_status'><?php echo $row['status']; ?></td>
		<td style="width:100px" class='col_encumbs'><?php echo $row['encumbrances']; ?></td>
		<td style="width:100px" class='col_encroachment'><?php echo $row['encroachment']; ?></td>
		<td style="width:100px" class='col_est_val'>GHc <?php echo $row['estimated_value']; ?></td>
		</tr>


		<?php
	}

}
?>
