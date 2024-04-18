<?php

class Addsection {

    private $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function validate(){
        if(empty($this->course)) {
            return false;
        }


        return true;
    }
    public function add() {
        include "connection.php"; 

        try {
           
            $stmt = $conn->prepare("INSERT INTO section (course) VALUES (?)");

        
            $stmt->bind_param('s', $this->course);

            
            $stmt->execute();

           
            return true;
        } catch(Exception $e) {
           
            return false;
        }
    }

}


?>