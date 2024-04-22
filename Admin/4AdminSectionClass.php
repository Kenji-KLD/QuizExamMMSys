<?php

class AddSection {
    private $sectionID;
    private $courseName;
   
    public function __construct($sectionID, $courseName) {
        $this->sectionID = $sectionID;
        $this->courseName = $courseName;
    }

    public function validate() {
        include "connection.php";
        $stmt = $conn->prepare("SELECT 1 FROM Account WHERE section_ID = ?");
        $stmt->bind_param('s', $this->sectionID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        return false;
        }
    }

    public function add() {
        include "connection.php";

        $conn->begin_transaction(); 
        try {
            // Insert into Account
            $stmt1 = $conn->prepare("INSERT INTO Section (section_ID, course) VALUES (?, ?)");
            $stmt1->bind_param('ss', $this->sectionID, $this->courseName);
            $stmt1->execute();
            $stmt1->close();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
}

class EditSection {
   private $sectionID;
   private $sectionName;
   
    public function __construct($sectionID, $sectionName) {
       $this->sectionID = $sectionID;
       $this->sectionName = $sectionName;
    }

    public function validate() {
        if (empty($this->sectionID || empty($this->sectionName))) {
            return false;
        }
        return true;
    }
    

    public function edit() {
        include "connection.php";
        global $conn;
    
        $conn->begin_transaction(); 
        try {
            $stmt = $conn->prepare("UPDATE Section SET course = ? WHERE section_ID = ?");
            $stmt->bind_param('ss', $this->sectionName, $this->sectionID);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
                return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
}


class DeleteSection {
    private $section_ID;

    public function __construct($section_ID) {
        $this->section_ID = $section_ID;
    }

    public function delete() {
        include "connection.php";

        try {
            $stmt = $conn->prepare("UPDATE Section SET course = 'q1w2e3r4t' WHERE section_ID = ?");
            $stmt->bind_param('s', $this->section_ID);
            $stmt->execute();
            $stmt->close();

            return true;
        } catch(Exception $e) {
            return false;
        }
    }
}



?>
