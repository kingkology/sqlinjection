<?php
  session_start();
  // required headers for post api
  //header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //include our required classes
  include '../../../config/Database.php';
  include '../../../models/Logs.php';
  include '../../../models/Response.php';

  //instantiate database and connect
  $database=new Database();
  $app_con=$database->connect('app_db');
  

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');




    

    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data
    if (isset($_GET['property_type']) AND (!(trim($_GET['property_type'])=="")) ) 
    {
        $property_type = mysqli_real_escape_string($app_con,$_GET['property_type']);
    }
    else
    {
      ?>
        <div class="position-relative form-group">
          <h2>
            <center>
              <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-danger">
                Unable to fetch query fields. Property Type is required.
              </a>
            </center>
          </h2>
        </div>
      <?php
      return;
    }


    switch ($property_type) 
    {
      case 'Office':

        ?>

          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">
              <div class="position-relative form-group" >
                  <label style="color:blue;"><font color="red">*</font> Field</label><br>
                  <select class="form-control" id="filter_field" name="filter_field">
                      <option >Region</option>
                      <option>District</option>
                      <option >Town</option>
                      <option >Neighbourhood</option>
                      <option >Metropolitan</option>
                      <option >Municipal</option>
                      <option >Allotee</option>
                      <option >Number of Storey</option>
                      <option >Property Grade</option>
                      <option >Property Condition</option>
                      <option >Status</option>
                  </select>           
              </div>
          </div>

          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
              <label style="color:blue;"><font color="red">*</font> Type</label><br>
              <select class="form-control" id="filter_type" name="filter_type">
                  <option value="1" selected="">like</option>
                  <option value="2">=</option>
                  <option value="3">&lt;</option>
                  <option value="4">&lt;=</option>
                  <option value="5">&gt;</option>
                  <option value="6">&gt;=</option>
                  <option value="7">!=</option>
              </select>                                   
            </div>

          </div>


          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">

            <div class="position-relative form-group">
                <label style="color:blue;"><font color="red">*</font> Value</label>
                <input style="width:100%;box-shadow:1px 1px 1px 1px"  class=" form-control " type="text" name="filter_value" id="filter_value">        
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Search</label>
                <br>
                <button type="button" class="btn btn-warning" id="tabulator-html-filter-go" onclick="call_pagelet('../apis/controllers/property_data/tables/search_offices.php?start=0&limit=300&property_type='+property_type.value+'&filter_field='+filter_field.value+'&filter_type='+filter_type.value+'&filter_value='+filter_value.value,'mytable');"><i class="fa fa-search"></i></button>
                
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Load All</label>
                <br>
                <button onclick="call_pagelet('../apis/controllers/property_data/tables/load_offices.php?start=0&limit=300','mytable');" type="button" class="btn btn-danger" id="tabulator-html-filter-reset">Load All</button>
                
            </div> 

          </div>


        <div style="overflow:auto;">

            <table class="table ">
              <thead>
                <tr>


                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_numb"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_numb"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_edit"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_edit"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_region"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_region"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_district"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_district"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_town"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_town"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_neighbourhood"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_neighbourhood"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_metro"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_metro"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_mun"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_mun"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_allotee"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_allotee"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_n_storey"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_n_storey"><br></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_prop_grade"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_prop_grade"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_prop_con"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_prop_con"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_status"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_status"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_encumbs"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_encumbs"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_encroachment"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_encroachment"></label>                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input switch-success" id="col_est_val"  onchange="hide_show_column(this.id,'mytable')" checked>
                        <label class="custom-control-label" for="col_est_val"></label>                          
                    </div>
                  </th>


                </tr>
                <tr>


                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">
                      #                          
                    </div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">View</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Region</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">District</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Town</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Neighbourhood</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Metropolitan</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Municipal</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Allotee</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Number of Storey</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Property Grade</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Property Condition</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Status</div>
                  </th>


                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Encumbrances</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Encroachment</div>
                  </th>

                  <th class="border border-b-2 dark:border-dark-5">
                    <div class="custom-control">Estimated Value</div>
                  </th>


                </tr>
              </thead>
            </table>
            <hr>  

        </div>


        <?php
      break;

      case 'Residential':

        ?>

          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">
              <div class="position-relative form-group" >
                  <label style="color:blue;"><font color="red">*</font> Field</label><br>
                  <select class="form-control" id="filter_field" name="filter_field">
                      <option >Region</option>
                      <option >District</option>
                      <option >Town</option>
                      <option >Neighbourhood</option>
                      <option >Metropolitan</option>
                      <option >Municipal</option>
                      <option >Allotee</option>
                      <option >Number of Storey</option>
                      <option >Property Grade</option>
                      <option >Property Condition</option>
                      <option >Status</option>
                      <option >Resident Type</option>
                      <option >Number of Bedrooms</option>
                  </select>           
              </div>
          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
              <label style="color:blue;"><font color="red">*</font> Type</label><br>
              <select class="form-control" id="filter_type" name="filter_type">
                  <option value="1" selected="">like</option>
                  <option value="2">=</option>
                  <option value="3">&lt;</option>
                  <option value="4">&lt;=</option>
                  <option value="5">&gt;</option>
                  <option value="6">&gt;=</option>
                  <option value="7">!=</option>
              </select>                                   
            </div>

          </div>


          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">

            <div class="position-relative form-group">
                <label style="color:blue;"><font color="red">*</font> Value</label>
                <input style="width:100%;box-shadow:1px 1px 1px 1px"  class=" form-control " type="text" name="filter_value" id="filter_value">        
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Search</label>
                <br>
                <button type="button" class="btn btn-warning" id="tabulator-html-filter-go" onclick="call_pagelet('../apis/controllers/property_data/tables/search_residence.php?start=0&limit=300&property_type='+property_type.value+'&filter_field='+filter_field.value+'&filter_type='+filter_type.value+'&filter_value='+filter_value.value,'mytable');"><i class="fa fa-search"></i></button>
                
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Load All</label>
                <br>

                <button onclick="call_pagelet('../apis/controllers/property_data/tables/load_residence.php?start=0&limit=300','mytable');" type="button" class="btn btn-danger" id="tabulator-html-filter-reset">Load All</button>
                
            </div> 

          </div>


          <div style="overflow:auto;">

              <table class="table ">
                <thead>
                  <tr>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_numb"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_numb"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_edit"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_edit"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_region"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_region"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_district" onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_district"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_town"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_town"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_neighbourhood"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_neighbourhood"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_metro"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_metro"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_mun"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_mun"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_allotee"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_allotee"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_n_storey"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_n_storey"><br></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_prop_con"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_prop_con"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_status"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_status"></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_encumbs"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_encumbs"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_encroachment"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_encroachment"></label>                          
                      </div>
                    </th>



                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_res"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_res"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_numb_rooms"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_numb_rooms"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_est_val"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_est_val"></label>                          
                      </div>
                    </th>


                  </tr>

                  <tr>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">#</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">View</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Region</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">District</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Town</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Neighbourhood</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Metropolitan</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Municipal</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Allotee</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Number of Storey</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Property Condition</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Status</div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Encumbrances</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Encroachment</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Resident Type</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Number of Bedrooms</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Estimated Value</div>
                    </th>

                  </tr>
                </thead>
              </table>
              <hr>  

          </div>

        <?php
      break;

      case 'Land':

        ?>

          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">
              <div class="position-relative form-group" >
                  <label style="color:blue;"><font color="red">*</font> Field</label><br>
                  <select class="form-control" id="filter_field" name="filter_field">
                      <option >Region</option>
                      <option >District</option>
                      <option >Town</option>
                      <option >Neighbourhood</option>
                      <option >Metropolitan</option>
                      <option >Municipal</option>
                      <option >Allotee</option>
                      <option >Proposed Use</option>
                      <option >Property Condition</option>
                      <option >Status</option>
                      <option >Land Size</option>
                  </select>           
              </div>
          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
              <label style="color:blue;"><font color="red">*</font> Type</label><br>
              <select class="form-control" id="filter_type" name="filter_type">
                  <option value="1" selected="">like</option>
                  <option value="2">=</option>
                  <option value="3">&lt;</option>
                  <option value="4">&lt;=</option>
                  <option value="5">&gt;</option>
                  <option value="6">&gt;=</option>
                  <option value="7">!=</option>
              </select>                                   
            </div>

          </div>


          <div class="col-sm-12 col-md-3 col-l-3 col-xl-3 ">

            <div class="position-relative form-group">
                <label style="color:blue;"><font color="red">*</font> Value</label>
                <input style="width:100%;box-shadow:1px 1px 1px 1px"  class=" form-control " type="text" name="filter_value" id="filter_value">        
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Search</label>
                <br>
                <button type="button" class="btn btn-warning" id="tabulator-html-filter-go" onclick="call_pagelet('../apis/controllers/property_data/tables/search_land.php?start=0&limit=300&property_type='+property_type.value+'&filter_field='+filter_field.value+'&filter_type='+filter_type.value+'&filter_value='+filter_value.value,'mytable');"><i class="fa fa-search"></i></button>
                
            </div>

          </div>


          <div class="col-sm-12 col-md-2 col-l-2 col-xl-2 ">

            <div class="position-relative form-group">
                <label style="color:white;">Load All</label>
                <br>

                <button onclick="call_pagelet('../apis/controllers/property_data/tables/load_land.php?start=0&limit=300','mytable');" type="button" class="btn btn-danger" id="tabulator-html-filter-reset">Load All</button>
                
            </div> 

          </div>



          <div style="overflow:auto;">

              <table class="table ">
                <thead>
                  <tr>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_numb"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_numb"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_edit"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_edit"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_region"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_region"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_district"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_district"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_town"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_town"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_neighbourhood"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_neighbourhood"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_metro"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_metro"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_mun"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_mun"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_allotee"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_allotee"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_ls"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_ls"><br></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_puse"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_puse"><br></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_prop_con"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_prop_con"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_status"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_status"></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_encumbs"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_encumbs"></label>                          
                      </div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_encroachment"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_encroachment"></label>                          
                      </div>
                    </th>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switch-success" id="col_est_val"  onchange="hide_show_column(this.id,'mytable')" checked>
                          <label class="custom-control-label" for="col_est_val"></label>                          
                      </div>
                    </th>


                  </tr>

                  <tr>


                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">#</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">View</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Region</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">District</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Town</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Neighbourhood</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Metropolitan</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Municipal</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Allotee</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Land Size</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Proposed Use</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Property Condition</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Status</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Encumbrances</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Encroachment</div>
                    </th>

                    <th class="border border-b-2 dark:border-dark-5">
                      <div class="custom-control">Estimated Value</div>
                    </th>

                  </tr>
                </thead>
              </table>
              <hr>  

          </div>


        <?php
      break;
      
      default:
        ?>

          <div class="position-relative form-group">
            <h2>
              <center>
                <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-danger">
                  Unable to fetch query fields. Property Type is required.
                </a>
              </center>
            </h2>
          </div>

        <?php
        break;
    }


?>