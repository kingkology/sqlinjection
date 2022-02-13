<?php

class Access
{

    //innitialise database
    private $con;

    //errand properties
    public $PIN;
    public $NAME;
    public $PWD;

    public $QUERY_VALUE;

    public $DATE_ADDED;

    public $LAST_DATE;
    public $LAST_TIME;
    public $DATE_FROM;
    public $DATE_TO;



    public function check_id($db)
    {

        $this->con= $db;

        $query = 'SELECT * FROM students WHERE student_id="'.$this->PIN.'"';
        
        $result = mysqli_query($this->con, $query);
        if($result)
        {
            //get returned rows
            $num = $result->num_rows;
            return $num;
            
        }
    }




    public function add_new_student($db)
    {
        $this->con= $db;
        $query = 'INSERT INTO students 
        (
            student_id,
            student_name,
            student_pwd
        ) 
        VALUES 
        (
            "'.$this->PIN.'",
            "'.$this->NAME.'",
            "'.$this->PWD.'"
        )
        ';
        
        $result = mysqli_query($this->con, $query);
        //return $query;
        if ($result === TRUE) 
        {
            return "Success";
        } else {
            return "Error in adding user ".mysqli_error($this->con);
        }
    }



    public function get_all_students($db)
    {

        $this->con= $db;

        $query = 'SELECT * FROM students ';
        
        $result = mysqli_query($this->con, $query);
        return $result; 
    }


    public function login($db)
    {
        $this->con= $db;

        $query='SELECT 
        student_id,
        student_name,
        programme
        from 
        students 
        where 
        student_id = "'.$this->PIN.'" AND student_pwd= "'.$this->PWD.'" ';

        return $query;
        $result = mysqli_query($this->con, $query);
        if ($result)
        {
            return $result;
        }

    }


    public function login_secure($db)
    {
        $this->con= $db;

        $query='SELECT 
        student_id,
        student_name,
        programme
        from 
        students 
        where 
        student_id = "'.$this->PIN.'" AND student_pwd= "'.$this->PWD.'" ';

        $result = mysqli_query($this->con, $query);
        if ($result)
        {
            return $result;
        }

    }



    public function change_password($db)
    {
        $this->con= $db;

        $query = 'UPDATE students SET
        student_pwd="'.$this->PWD.'"
        WHERE 
        student_id="'.$this->PIN.'" 
        ';
        //PREPARED STATEMENT
        $result = mysqli_query($this->con, $query);
        if ($result === TRUE) 
        {
            if ($this->con->affected_rows>0) 
            {
               return "Success"; 
            }
            else
            {
                return "No Data Modified";
            }
        }
        else
        {
            return "Error";
        }

    }


    public function reset_password($db)
    {
        $this->con= $db;

        $query = 'UPDATE students SET
        student_pwd="'.$this->PWD.'"
        WHERE 
        student_id="'.$this->PIN.'" 
        ';
        //PREPARED STATEMENT
        $result = mysqli_query($this->con, $query);
        if ($result === TRUE) 
        {
            if ($this->con->affected_rows>0) 
            {
               return "Success"; 
            }
            else
            {
                return "Password is already default";
            }
        }
        else
        {
            return "Error occured";
        }

    }





}




?>