<?php
    session_start();
  //header for read api
    // use * under allow access control origin because we will not be accepting any api key using tokens or authorisations
    header('Access-Control-Allow-Origin:*');
    //content type as html
    header('Content-Type:application/json');

    $host='localhost';
    $sys_db= 'nia_systems';
    $username='root';
    $password='@kr0t3k01-23';
    $sys_con;


    $sys_con = new mysqli($host,$username, $password, $sys_db);
    if (mysqli_connect_errno()) 
    {
        echo "Failed to connect to database: ";
        return;
    }
 


    //include our required classes
    /*include '..\..\config\Database.php';*/

    $dt=new DateTime('now', new DateTimezone('Africa/Accra'));
    $ladate = $dt->format('Y-m-d');
    $latodayz = $ladate;
    $ladatez = $dt->format('Y-m-d H:i:s');
    $latimez = $dt->format('H:i:s');


    //instantiate database and connect
    /*$database=new Database();
    $sys_con=$database->connect('app_db');*/


    


    //user query
    try 
    {
        
        
        /*
        GRANT ACCESS TO ALL ATEO 
        */

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'2','TEO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='16'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='2' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success ATEO 1\n";
        } 
        else 
        {
            echo "Error adding ATEO access 1 : ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'38','TEO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='16'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='38' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success ATEO 2\n";
        } 
        else 
        {
            echo "Error adding ATEO access 2 : ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'39','TEO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='16'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='39' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success ATEO 3\n";
        } 
        else 
        {
            echo "Error adding ATEO access 3 : ".mysqli_error($this->con);
        }



        /*
        GRANT ACCESS TO ALL ATSO
        */

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'4','RTSO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='15'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='4' )"
        ;
        if ($result === TRUE) 
        {
            echo "Success ATSO 1\n";
        } 
        else 
        {
            echo "Error adding ATSO access 1: ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'6','RTSO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='15'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='6' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success ATSO 2\n";
        } 
        else 
        {
            echo "Error adding ATSO access 2: ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'38','RTSO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='15'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='38' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success ATSO 3\n";
        } 
        else 
        {
            echo "Error adding ATSO access 3 : ".mysqli_error($this->con);
        }


        /*
        GRANT ACCESS TO ALL AAO
        */

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'2','DAO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='22'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='2' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success AAO 1\n";
        } 
        else 
        {
            echo "Error adding AAO access 1: ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'38','DAO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='22'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='38' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success AAO 2\n";
        } 
        else 
        {
            echo "Error adding AAO access 2: ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'39','DAO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='22'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='39' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success AAO 3\n";
        } 
        else 
        {
            echo "Error adding AAO access 3 : ".mysqli_error($this->con);
        }


        /*
        GRANT ACCESS TO ALL RO
        */

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'2','RO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='23'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='2' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success RO 1\n";
        } 
        else 
        {
            echo "Error adding AAO access 1 : ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'38','RO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='23'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='38' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success RO 2\n";
        } 
        else 
        {
            echo "Error adding RO access 2 : ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'39','RO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='23'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='39' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success RO 3\n";
        } 
        else 
        {
            echo "Error adding RO access 3 : ".mysqli_error($this->con);
        }


        /*
        GRANT ACCESS TO ALL DRO
        */

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'2','DRO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='21'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='2' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success DRO 1\n";
        } 
        else 
        {
            echo "Error adding DRO access 1 : ".mysqli_error($this->con);
        }

        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'38','DRO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='21'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='38' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success DRO 2\n";
        } 
        else 
        {
            echo "Error adding DRO access 2 : ".mysqli_error($this->con);
        }


        $query = "INSERT INTO 
        nia_systems.`access_controls` 
        (nia_systems.`access_controls`.`staff_nid`, nia_systems.`access_controls`.`app_id`, nia_systems.`access_controls`.`role_code`, nia_systems.`access_controls`.`grant_access`) 
        SELECT nia_hr.staff_list.staff_nid,'39','DRO','Yes' 
        FROM nia_hr.staff_list 
        INNER JOIN 
        nia_hr.staff_unit ON nia_hr.staff_unit.staff_nid=nia_hr.staff_list.staff_nid
        WHERE 

        nia_hr.staff_unit.position_id='21'
        AND 
        nia_hr.staff_list.staff_nid 
        NOT IN 
        (SELECT nia_systems.`access_controls`.`staff_nid` FROM nia_systems.`access_controls` WHERE nia_systems.`access_controls`.`staff_nid`=nia_hr.staff_list.staff_nid AND nia_systems.`access_controls`.`app_id`='39' )"
        ;
        $result = mysqli_query($sys_con, $query);
        if ($result === TRUE) 
        {
            echo "Success DRO 3\n";
        } 
        else 
        {
            echo "Error adding DRO access 3 : ".mysqli_error($this->con);
        }
        

    } 
    catch (Exception $e) 
    {
        echo "\nLogin Failed: system error-> ".$e;
        return;
    }


?>