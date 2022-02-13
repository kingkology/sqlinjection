<?php
    //the database class
    class Database
    {
        // sepcify db parameters as pprivate because it will bonly be accesed from this class
        
        private $host='localhost';
        private $sys_db= 'result_checker';
        private $username='root';
        private $password='';
        public $sys_con;
        public $hr_con;
        private $selected_db;
        
        //connection function as public since it will be accessed from other pages
        public function connect($db)
        {
            //$this and  -> is used in object oriented to refer to or access parameters in a class. when using this the $ does not apply
            try
            {
                //using mysqli adapter takes 4 parts(database host,database username,database password and database name separated by "," )
                switch ($db) {
                    case 'app_db':
                        $this->sys_con = new mysqli($this->host,$this->username, $this->password, $this->sys_db);
                        if (mysqli_connect_errno()) 
                        {
                            return "Failed to connect to database: ";
                        }
                        return $this->sys_con;
                    break;

                   

                    default:
                        $this->sys_con = new mysqli($this->host,$this->username, $this->password, $this->sys_db);
                        if (mysqli_connect_errno()) 
                        {
                            return "Failed to connect to database: ";
                        }
                        return $this->sys_con;
                    break;
                }

                
            }
            catch (Exception $e)
            {
                return 'Connection Error: ';
            }
            /*return $this->con;*/

        }


    }

?>