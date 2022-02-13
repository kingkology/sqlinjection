<?php

class Logs
{

    //innitialise database
    private $con;

    //errand properties
    public $USER;
    public $ACTION;
    public $STATEMENT;
    public $DATE;
    public $TIME;

    public function add($db)
    {
        $this->con= $db;

        $query = 'INSERT INTO logs (
        user_id,
        log_action,
        log_description,
        log_date,
        log_time
        )
        VALUES
        ("'.$this->USER.'","'.$this->ACTION.'","'.$this->STATEMENT.'","'.$this->DATE.'","'.$this->TIME.'")';
        $result = mysqli_query($this->con, $query);
        //return $query;
        if ($result === TRUE) {
            return "Success";
        } else {
            return "Error in creating log ".mysqli_error($this->con);
        }

    }

    public function fetch_all($db)
    {

        $this->con= $db;

        $query = 'SELECT * FROM logs ';
        
        $result = mysqli_query($this->con, $query);
        return $result; 
    }


}

?>