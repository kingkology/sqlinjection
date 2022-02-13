<?php
  session_start();
  // required headers for post api
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //include our required classes
  include '../../../config/Database.php';
  include '../../../models/logs.php';
  include '../../../models/Response.php';
  include '../../../models/Staff.php';
  include '../../../../../login/apis/models/Access.php';

  $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
  $ladate = $dt->format('Y-m-d');
  $latodayz = $ladate;
  $ladatez = $dt->format('Y-m-d H:i:s');
  $latimez = $dt->format('H:i:s');


  if (!((isset($_SESSION['hr_portal'])  AND $_SESSION['hr_portal']=="Yes") AND ($_SESSION['app_code']=="UO" OR $_SESSION['app_code']=="SUH" OR $_SESSION['app_code']=="UH" OR $_SESSION['app_code']=="DHOD" OR $_SESSION['app_code']=="HOD" OR $_SESSION['app_code']=="SUPA") )) 
  {
    $response->send_response(409,"Illegal access!: No valid authorization detected. Kindly check login or check your access rights.",NULL);
    return;
  }

    
    //instantiate database and connect
    $database=new Database();
    $hr_con=$database->connect('hr_db');
    $sys_con=$database->connect('app_db');


    //instantiate user object and assign the connectio to it
    $staff=new Staff();
    //instantiate logs object and assign the connection to it
    $logs=new Logs();
    //instantiate logs object and assign the connection to it
    $response=new Response();

    $access=new Access();


    //SANITIZE VALUE AND ASSIGN TO APPROPRIATE VARIABLE IN CLASS
    // get posted data
    if (isset($_POST['pin']) AND (!(trim($_POST['pin'])=="")) ) 
    {
        $pin = mysqli_real_escape_string($hr_con,strtolower($_POST['pin']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff NID is required.",NULL);
        return;
    }

    if (isset($_POST['staff_id']) AND (!(trim($_POST['staff_id'])=="")) ) 
    {
        $staff_id = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_id']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff ID is required.",NULL);
        return;
    }

    if (isset($_POST['staff_title']) AND (!(trim($_POST['staff_title'])=="")) ) 
    {
        $staff_title = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_title']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff TITLE is required.",NULL);
        return;
    }

    if (isset($_POST['staff_surname']) AND (!(trim($_POST['staff_surname'])=="")) ) 
    {
        $staff_surname = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_surname']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff SURNAME is required.",NULL);
        return;
    }

    if (isset($_POST['staff_forenames']) AND (!(trim($_POST['staff_forenames'])=="")) ) 
    {
        $staff_forenames = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_forenames']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Forenames is required.",NULL);
        return;
    }

    if (isset($_POST['staff_dob']) AND (!(trim($_POST['staff_dob'])=="")) ) 
    {
        $staff_dob = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_dob']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Date Of Birth is required.",NULL);
        return;
    }

    if (isset($_POST['sex']) AND (!(trim($_POST['sex'])=="")) ) 
    {
        $sex = mysqli_real_escape_string($hr_con,strtolower($_POST['sex']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Gender is required.",NULL);
        return;
    }

    if (isset($_POST['staff_marital_status']) AND (!(trim($_POST['staff_marital_status'])=="")) ) 
    {
        $staff_marital_status = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_marital_status']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Marital Status is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nationality']) AND (!(trim($_POST['staff_nationality'])=="")) ) 
    {
        $staff_nationality = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nationality']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Nationality is required.",NULL);
        return;
    }

    if (isset($_POST['staff_disability']) AND (!(trim($_POST['staff_disability'])=="")) ) 
    {
        $staff_disability = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_disability']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Disability is required.",NULL);
        return;
    }

    if (isset($_POST['staff_email']) AND (!(trim($_POST['staff_email'])=="")) ) 
    {
        $staff_email = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_email']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Email is required.",NULL);
        return;
    }

    if (isset($_POST['staff_contact']) AND (!(trim($_POST['staff_contact'])=="")) ) 
    {
        $staff_contact = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_contact']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Contact is required.",NULL);
        return;
    }

    if (isset($_POST['office']) AND (!(trim($_POST['office'])=="")) ) 
    {
        $office = mysqli_real_escape_string($hr_con,strtolower($_POST['office']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Office is required.",NULL);
        return;
    }

    if (isset($_POST['grade']) AND (!(trim($_POST['grade'])=="")) ) 
    {
        $grade = mysqli_real_escape_string($hr_con,strtolower($_POST['grade']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Grade is required.",NULL);
        return;
    }

    if (isset($_POST['department']) AND (!(trim($_POST['department'])=="")) ) 
    {
        $department = mysqli_real_escape_string($hr_con,strtolower($_POST['department']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Department is required.",NULL);
        return;
    }

    if (isset($_POST['department_position']) AND (!(trim($_POST['department_position'])=="")) ) 
    {
        $department_position = mysqli_real_escape_string($hr_con,strtolower($_POST['department_position']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Department Role is required.",NULL);
        return;
    }

    if (isset($_POST['unit']) AND (!(trim($_POST['unit'])=="")) ) 
    {
        $unit = mysqli_real_escape_string($hr_con,strtolower($_POST['unit']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Unit is required.",NULL);
        return;
    }

    if (isset($_POST['unit_position']) AND (!(trim($_POST['unit_position'])=="")) ) 
    {
        $unit_position = mysqli_real_escape_string($hr_con,strtolower($_POST['unit_position']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Unit Role is required.",NULL);
        return;
    }

    if (isset($_POST['staff_ssnit']) AND (!(trim($_POST['staff_ssnit'])=="")) ) 
    {
        $staff_ssnit = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_ssnit']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff SSNIT is required.",NULL);
        return;
    }

    if (isset($_POST['staff_tin']) AND (!(trim($_POST['staff_tin'])=="")) ) 
    {
        $staff_tin = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_tin']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Tin is required.",NULL);
        return;
    }

    if (isset($_POST['payment_mmethod']) AND (!(trim($_POST['payment_mmethod'])=="")) ) 
    {
        $payment_mmethod = mysqli_real_escape_string($hr_con,strtolower($_POST['payment_mmethod']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Payment method is required.",NULL);
        return;
    }

    if (isset($_POST['staff_bank_account']) AND (!(trim($_POST['staff_bank_account'])=="")) ) 
    {
        $staff_bank_account = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_bank_account']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank Account Number is required.",NULL);
        return;
    }

    if (isset($_POST['staff_account_name']) AND (!(trim($_POST['staff_account_name'])=="")) ) 
    {
        $staff_account_name = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_account_name']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank Account Name is required.",NULL);
        return;
    }

    if (isset($_POST['staff_bank_name']) AND (!(trim($_POST['staff_bank_name'])=="")) ) 
    {
        $staff_bank_name = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_bank_name']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank Name is required.",NULL);
        return;
    }

    if (isset($_POST['staff_bank_branch']) AND (!(trim($_POST['staff_bank_branch'])=="")) ) 
    {
        $staff_bank_branch = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_bank_branch']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank Branch is required.",NULL);
        return;
    }

    if (isset($_POST['staff_bank_district']) AND (!(trim($_POST['staff_bank_district'])=="")) ) 
    {
        $staff_bank_district = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_bank_district']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank District is required.",NULL);
        return;
    }

    if (isset($_POST['staff_bank_region']) AND (!(trim($_POST['staff_bank_region'])=="")) ) 
    {
        $staff_bank_region = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_bank_region']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Bank Region is required.",NULL);
        return;
    }

    if (isset($_POST['staff_mobile_money']) AND (!(trim($_POST['staff_mobile_money'])=="")) ) 
    {
        $staff_mobile_money = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_mobile_money']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Mobile money number is required.",NULL);
        return;
    }

    if (isset($_POST['name_on_number']) AND (!(trim($_POST['name_on_number'])=="")) ) 
    {
        $name_on_number = mysqli_real_escape_string($hr_con,strtolower($_POST['name_on_number']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Name on Number is required.",NULL);
        return;
    }

    if (isset($_POST['staff_mother']) AND (!(trim($_POST['staff_mother'])=="")) ) 
    {
        $staff_mother = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_mother']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Mother's name is required.",NULL);
        return;
    }

    if (isset($_POST['staff_father']) AND (!(trim($_POST['staff_father'])=="")) ) 
    {
        $staff_father = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_father']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff Father's name is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_title']) AND (!(trim($_POST['staff_nextofkin_title'])=="")) ) 
    {
        $staff_nextofkin_title = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_title']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Title is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_surname']) AND (!(trim($_POST['staff_nextofkin_surname'])=="")) ) 
    {
        $staff_nextofkin_surname = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_surname']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Surname is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_forenames']) AND (!(trim($_POST['staff_nextofkin_forenames'])=="")) ) 
    {
        $staff_nextofkin_forenames = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_forenames']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Forename is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_contact']) AND (!(trim($_POST['staff_nextofkin_contact'])=="")) ) 
    {
        $staff_nextofkin_contact = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_contact']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Contact is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_relation']) AND (!(trim($_POST['staff_nextofkin_relation'])=="")) ) 
    {
        $staff_nextofkin_relation = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_relation']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff.  Relation to Next of Kin is required.",NULL);
        return;
    }

    if (isset($_POST['staff_nextofkin_address']) AND (!(trim($_POST['staff_nextofkin_address'])=="")) ) 
    {
        $staff_nextofkin_address = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_address']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Address is required.",NULL);
        return;
    }

    /*if (isset($_POST['staff_nextofkin_postal']) AND (!(trim($_POST['staff_nextofkin_postal'])=="")) ) 
    {
        $staff_nextofkin_postal = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_postal']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Postal is required.",NULL);
        return;
    }*/

    if (isset($_POST['staff_nextofkin_nid']) AND (!(trim($_POST['staff_nextofkin_nid'])=="")) ) 
    {
        $staff_nextofkin_nid = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_nextofkin_nid']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Next of Kin Ghana card is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_title']) AND (!(trim($_POST['staff_emergency_title'])=="")) ) 
    {
        $staff_emergency_title = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_title']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Title is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_surname']) AND (!(trim($_POST['staff_emergency_surname'])=="")) ) 
    {
        $staff_emergency_surname = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_surname']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Surname is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_forename']) AND (!(trim($_POST['staff_emergency_forename'])=="")) ) 
    {
        $staff_emergency_forename = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_forename']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Forename is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_contact']) AND (!(trim($_POST['staff_emergency_contact'])=="")) ) 
    {
        $staff_emergency_contact = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_contact']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Number is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_relation']) AND (!(trim($_POST['staff_emergency_relation'])=="")) ) 
    {
        $staff_emergency_relation = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_relation']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Relation to Emergency Contact is required.",NULL);
        return;
    }

    if (isset($_POST['staff_emergency_address']) AND (!(trim($_POST['staff_emergency_address'])=="")) ) 
    {
        $staff_emergency_address = mysqli_real_escape_string($hr_con,$_POST['staff_emergency_address']);
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Address is required.",NULL);
        return;
    }

    /*if (isset($_POST['staff_emergency_postal']) AND (!(trim($_POST['staff_emergency_postal'])=="")) ) 
    {
        $staff_emergency_postal = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_postal']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Postal Address is required.",NULL);
        return;
    }*/

    if (isset($_POST['staff_emergency_nid']) AND (!(trim($_POST['staff_emergency_nid'])=="")) ) 
    {
        $staff_emergency_nid = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_emergency_nid']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. Emergency Contact Ghana Card is required.",NULL);
        return;
    }

    if (isset($_POST['date_joined']) AND (!(trim($_POST['date_joined'])=="")) ) 
    {
        $date_joined = mysqli_real_escape_string($hr_con,strtolower($_POST['date_joined']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff date joined is required.",NULL);
        return;
    }



    if (isset($_POST['appointment_type']) AND (!(trim($_POST['appointment_type'])=="")) ) 
    {
        $appointment_type = mysqli_real_escape_string($hr_con,strtolower($_POST['appointment_type']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff appointment type is required.",NULL);
        return;
    }

    if (isset($_POST['appointment_expiry']) AND (!(trim($_POST['appointment_expiry'])=="")) ) 
    {
        $appointment_expiry = mysqli_real_escape_string($hr_con,strtolower($_POST['appointment_expiry']));
    }
    else
    {
        $appointment_expiry=NULL;
    }

    if (isset($_POST['staff_address']) AND (!(trim($_POST['staff_address'])=="")) ) 
    {
        $staff_address = mysqli_real_escape_string($hr_con,strtolower($_POST['staff_address']));
    }
    else
    {
        $response->send_response(400,"Unable to Update staff. staff appointment type is required.",NULL);
        return;
    }

    $staff_dob=date_create($staff_dob);
    $staff_dob=date_format($staff_dob,'Y-m-d');

    $date_joined=date_create($date_joined);
    $date_joined=date_format($date_joined,'Y-m-d');


    //check name
       if(1 === preg_match('~[0-9]~', $staff_surname))
       {
            $response->send_response(406,"Invalid surname format",NULL);
            return;
       }


       if(1 === preg_match('~[0-9]~', $staff_forenames))
       {
            $response->send_response(406,"Invalid forenames format",NULL);
            return;
       }

       if(1 === preg_match('~[0-9]~', $staff_mother))
       {
            $response->send_response(406,"Invalid mothers name format",NULL);
            return;
       }


       if(1 === preg_match('~[0-9]~', $staff_father))
       {
            $response->send_response(406,"Invalid fathers name format",NULL);
            return;
       }



       if(1 === preg_match('~[0-9]~', $staff_nextofkin_surname." ".$staff_nextofkin_forenames))
       {
            $response->send_response(406,"Invalid next of king name format",NULL);
            return;
       }


       if(1 === preg_match('~[0-9]~', $staff_emergency_surname." ".$staff_emergency_forename))
       {
            $response->send_response(406,"Invalid emergency contact name format",NULL);
            return;
       }



       //check contacts
       if(1 === preg_match("/^(\233)?[0-9]{9}$/", $staff_contact))
       {
            $response->send_response(406,"Invalid contact format",NULL);
            return;
       }

       if(1 === preg_match("/^(\233)?[0-9]{9}$/", $staff_mobile_money))
       {
            $response->send_response(406,"Invalid mobile money contact format",NULL);
            return;
       }


    

    $staff->PIN=strtoupper($pin);
    $staff->STAFF_ID=$staff_id;
    $staff->TITLE=ucwords($staff_title);
    $staff->SURNAME=ucwords($staff_surname);
    $staff->FORENAME=ucwords($staff_forenames);
    $staff->FULLNAME=ucwords($staff_surname)." ".ucwords($staff_forenames);
    $staff->DOB=$staff_dob;
    $staff->TEL=$staff_contact;
    $staff->EMAIL=strtolower($staff_email);
    $staff->SEX=ucwords($sex);
    $staff->OFFICE=$office;
    $staff->GRADE=$grade;
    $staff->NATIONALITY=ucwords($staff_nationality);
    $staff->DISABILITY=ucwords($staff_disability);
    $staff->MARITAL_STATS=ucwords($staff_marital_status);
    $staff->SSNIT=$staff_ssnit;
    $staff->TIN=$staff_tin;
    $staff->PAYMENT_METHOD=ucwords($payment_mmethod);
    $staff->DATE_JOINED=$date_joined;
    $staff->APPOINTMENT_TYPE=$appointment_type;
    $staff->APPOINTMENT_EXPIRY=$appointment_expiry;
    $staff->STAFF_ADDRESS=$staff_address;
    $staff->STAFF_STATUS='Active';

    $staff->DEPARTMENT=$department;
    $staff->DEPARTMENT_POSITION=$department_position;
    $staff->UNIT=$unit;
    $staff->UNIT_POSITION=$unit_position;

    $staff->BANK_ACCOUNT=$staff_bank_account;
    $staff->BANK_ACCOUNT_NAME=ucwords($staff_account_name);
    $staff->BANK_NAME=ucwords($staff_bank_name);
    $staff->BANK_BRANCH=ucwords($staff_bank_branch);
    $staff->BANK_REGION=ucwords($staff_bank_region);
    $staff->BANK_DISTRICT=ucwords($staff_bank_district);
    $staff->PREFERED_PAYMENT='Yes';
    $staff->APPROVAL_STATUS='Approved';

    $staff->MOBILE_MONEY=$staff_mobile_money;
    $staff->MOBILE_MONEY_NAME=$name_on_number;

    $staff->MOTHER=ucwords($staff_mother);
    $staff->FATHER=ucwords($staff_father);
    $staff->NEXT_OF_KIN_TITLE=ucwords($staff_nextofkin_title);
    $staff->NEXT_OF_KIN_SURNAME=ucwords($staff_nextofkin_surname);
    $staff->NEXT_OF_KIN_FORENAME=ucwords($staff_nextofkin_forenames);
    $staff->NEXT_OF_KIN_TEL=$staff_nextofkin_contact;
    $staff->NEXT_OF_KIN_RELATION=ucwords($staff_nextofkin_relation);
    $staff->NEXT_OF_KIN_ADDRESS=ucwords($staff_nextofkin_address);
    //$staff->NEXT_OF_KIN_POSTAL=ucwords($staff_nextofkin_postal);
    $staff->NEXT_OF_KIN_NID=strtoupper($staff_nextofkin_nid);
    $staff->EMERGENCY_TITLE=ucwords($staff_emergency_title);
    $staff->EMERGENCY_SURNAME=ucwords($staff_emergency_surname);
    $staff->EMERGENCY_FORNAME=ucwords($staff_emergency_forename);
    $staff->EMERGENCY_TEL=$staff_emergency_contact;
    $staff->EMERGENCY_RELATION=ucwords($staff_emergency_relation);
    $staff->EMERGENCY_ADDRESS=ucwords($staff_emergency_address);
    //$staff->EMERGENCY_POSTAL=$staff_emergency_postal;
    $staff->EMERGENCY_NID=strtoupper($staff_emergency_nid);

    //$staff->LOGIN_PASSWORD=password_hash( substr($pin, 4,3).lower($staff_surname), PASSWORD_DEFAULT);

    $staff->STAFF_DECLARATION="None";
    $staff->STAFF_PIC="None";

    $staff->ADDED_BY=$_SESSION['staff_nid'];
    $staff->DATE_ADDED=$ladate;
    $staff->TIME_ADDED=$latimez;

    /*$access->PIN=$pin;
    $access->FORENAME=$staff_forenames;
    $access->SURNAME=$staff_surname;
    $access->OFFICE=$office;
    $access->PWD=password_hash( substr($pin, 4,3).$staff_surname, PASSWORD_DEFAULT);
    $access->DATE_ADDED=$ladate;
    $access->TEL=$staff_contact;*/


    //check pin
    $count = $staff->check_id($hr_con);
    if ($count<1) 
    {
      $response->send_response(400,"staff NID does not exist",NULL);
      return;
    }

    //check STAFF ID
    $count = $staff->check_staff_id($hr_con);
    if ($count<1) 
    {
        $response->send_response(400,"staff ID does not exist",NULL);
        return;
    }


    // query
    $error_ocured="Yes";
    $hr_con->autocommit(FALSE);
    $sys_con->autocommit(FALSE);
    $result = $staff->update_staff($hr_con);
    if ($result=="Success") 
    {
        $result =$staff->update_staff_bank($hr_con);
        if ($result=="Success") 
        {
            $result =$staff->update_staff_department($hr_con);
            if ($result=="Success") 
            {
                $result =$staff->update_staff_unit($hr_con);
                if ($result=="Success") 
                {
                    $result =$staff->update_staff_mobile_money($hr_con);
                    if ($result=="Success") 
                    {
                        $result =$staff->update_staff_family($hr_con);
                        if ($result=="Success") 
                        {
                            $error_ocured="No";
                        }
                    }
                }
            }
        }
    }


    if($error_ocured=="No")
    {
        
      //insert log for add user
      $logs->USER= $_SESSION['staff_nid'];
      $logs->ACTION= "Update staff";
      $logs->STATEMENT= "Updated staff : ".$staff->FULLNAME."(".$staff->PIN.")";
      $logs->DATE= $ladate;
      $logs->TIME= $latimez;
      $logs->add($hr_con);
         

      $response->send_response(201,"staff Updated Successfully",NULL);
      $hr_con->commit();
      $sys_con->commit();
      return;
    } 
    else 
    {
      $response->send_response(503,"Error Updating staff -> ",NULL);
      return;
    }


?>