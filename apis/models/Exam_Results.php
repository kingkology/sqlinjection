<?php

class Exam_Results
{

    //innitialise database
    private $con;

    //errand properties
    public $ID;
    public $QUERY_VALUE;

    public $DATE_ADDED;
    public $ADDED_BY;

    public $LAST_DATE;
    public $LAST_TIME;
    public $DATE_FROM;
    public $DATE_TO;

    public $START;
    public $LIMIT;


   
   


    public function get_all_my_results($db)
    {
        $this->con= $db;
        $query = 'SELECT students.student_id,students.student_name,course_results.programme,course_results.course_year,course_results.course_code,course_results.course_name,course_results.credit_hours,course_results.cummulative_Assessment,course_results.exams_score FROM students INNER JOIN course_results ON course_results.student_id = students.student_id
        WHERE 
        students.student_id="'.$this->ID.'" 
        ORDER BY course_results.course_year DESC
        ';
        $result = mysqli_query($this->con, $query);
        return $result; 
    }



}




?>