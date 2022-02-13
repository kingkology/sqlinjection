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
include '../../../models/Residential.php';

//instantiate database and connect
$database=new Database();
$app_con=$database->connect('app_db');

$residence=new Residential();


if (!((isset($_SESSION['user_id'])  AND ($_SESSION['user_type']=="Superadministrator" OR $_SESSION['user_type']=="Administrator" OR $_SESSION['user_type']=="Regional Supervisor" OR $_SESSION['user_type']=="Regional Officer" ) ) )) 
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

$residence->REGION=$_SESSION['user_region'];

$result=$residence->get_all_property_region($app_con);

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
	        <th class="col_prop_con border border-b-2 dark:border-dark-5 whitespace-no-wrap">Property Condition</th>
	        <th class="col_status border border-b-2 dark:border-dark-5 whitespace-no-wrap">Status</th>
	        <th class="col_encumbs border border-b-2 dark:border-dark-5 whitespace-no-wrap">Encumbrances</th>
	        <th class="col_encroachment border border-b-2 dark:border-dark-5 whitespace-no-wrap">Encroachment</th>
	        <th class="col_res border border-b-2 dark:border-dark-5 whitespace-no-wrap">Resident Type</th>
	        <th class="col_numb_rooms border border-b-2 dark:border-dark-5 whitespace-no-wrap">Number of Bedrooms</th>
	        <th class="col_est_val border border-b-2 dark:border-dark-5 whitespace-no-wrap">Estimated Value
	        </th>
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
		<td style="width:100px" class='col_prop_con'><?php echo $row['prop_condition']; ?></td>
		<td style="width:100px" class='col_status'><?php echo $row['status']; ?></td>
		<td style="width:100px" class='col_encumbs'><?php echo $row['encumbrances']; ?></td>
		<td style="width:100px" class='col_encroachment'><?php echo $row['encroachment']; ?></td>
		<td style="width:100px" class='col_prop_grade'><?php echo ($row['resident_type']=="Other") ? $row['resident_type_other'] : $row['resident_type'] ; ?></td>
		<td style="width:100px" class='col_numb_rooms'><?php echo $row['number_of_bedroom']; ?></td>
		<td style="width:100px" class='col_est_val'>GHc <?php echo $row['estimated_value']; ?></td>
		</tr>


		<?php
	}

}
?>
