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
    include '../../../models/District.php';

    $database=new Database();
    $app_con=$database->connect('app_db');
    //instantiate response object and assign the connection to it
    $response=new Response();
    $district=new District();

    $christmas="";

    if ($lamonth==12)
    {
      $christmas="christmas";
    }

    //DECLARE AUTO COUNT
    $getid=0;
    //Table array for response payload
    $table_response=array();



    if (isset($_GET['region']) AND (!(trim($_GET['region'])==""))) 
    {
        $region = mysqli_real_escape_string($app_con,$_GET['region']);
    }
    else
    {
      ?>
          <div class="alert alert-danger fade show" role="alert">
              <h4 class="alert-heading">Error</h4>
              <p>No region detected</p>
          </div>
      <?php
      return;
    }

    $district->REGION_NAME= $region;
  
    $result=$district->get_all_districts_regional($app_con);

    /*echo $district->REGION;
    return;*/

    if ($result) 
    {
      if($result->num_rows>0)
      {

        ?>

        <div class="form-group" id="district_loc" name="district_loc">
            <label class="text-mute small">District</label>
            <input list="dist_range" class="form-control" name="district" id="district" required>
            <datalist id="dist_range" name="dist_range" >
        <?php

        while($row = $result->fetch_assoc()) 
        {
          $district_name=htmlentities($row['district_name']);
          ?>

            <option value="<?php echo $district_name; ?>" ><?php echo $district_name; ?></option>

          <?php
        
        }

       ?>
          </datalist>
        </div>

       <?php
      }
      else
      {
        ?>
            <div class="alert alert-danger fade show" role="alert">
                <h4 class="alert-heading">Error</h4>
                <p>No district found</p>
            </div>
        <?php
        return;
      }
    }
    else
    {
      ?>
          <div class="alert alert-warning fade show" role="alert">
              <h4 class="alert-heading">Error</h4>
              <p>No district found</p>
          </div>
      <?php
      return;
    }




  ?>