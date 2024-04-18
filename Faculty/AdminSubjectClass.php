<?php

class AddSubject {
    private $subjectID;
    private $subjectName;

    public function __construct($subjectID, $subjectName) {
        $this->subjectID = $subjectID;
        $this->subjectName = $subjectName;
    }

    public function validate() {
        // Validate that subjectID and subjectName are not empty
        if (empty($this->subjectID) || empty($this->subjectName)) {
            return false;
        }

        return true;
    }

    public function add() {
        include "connection.php"; 

        try {
         
            $stmt = $conn->prepare("INSERT INTO Subject (subject_ID, subjectName) VALUES (?, ?)");
            $stmt->bind_param('ss', $this->subjectID, $this->subjectName);
            $stmt->execute();

        
            if ($stmt->affected_rows > 0) {
                return true; 
            } else {
                return false; /
            }
        } catch (Exception $e) {
          
            return false;
        }
    }
}

class DeleteSubject {
    private $subjectID;

    public function __construct($subjectID) {
        $this->subjectID = $subjectID;
    }

    public function validate() {
        
        if (empty($this->subjectID)) {
            return false;
        }

        return true;
    }

    public function delete() {
        include "connection.php"; 

        try {
           
            $stmt = $conn->prepare("DELETE FROM Subject WHERE subject_ID = ?");
            $stmt->bind_param('s', $this->subjectID);
            $stmt->execute();

           
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false; 
            }
        } catch (Exception $e) {
            
            return false;
        }
    }
}

class EditSubject {
    private $subjectID;
    private $newSubjectName;

    public function __construct($subjectID, $newSubjectName) {
        $this->subjectID = $subjectID;
        $this->newSubjectName = $newSubjectName;
    }

    public function validate() {
   
        if (empty($this->subjectID) || empty($this->newSubjectName)) {
            return false;
        }

        return true;
    }

    public function edit() {
        include "connection.php"; 

        try {
           
            $stmt = $conn->prepare("UPDATE Subject SET subjectName = ? WHERE subject_ID = ?");
            $stmt->bind_param('ss', $this->newSubjectName, $this->subjectID);
            $stmt->execute();

           
            if ($stmt->affected_rows > 0) {
                return true; 
            } else {
                return false; 
            }
        } catch (Exception $e) {
         
            return false;
        }
    }
}



?>