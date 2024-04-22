<?php

class AddSection {
    private $sectionID;
    private $courseName;
   
    public function __construct($sectionID, $courseName) {
        $this->sectionID = $sectionID;
        $this->courseName = $courseName;
    }

    public function validate() {
        // Include the database connection file
        include "connection.php";
    
        try {
            // Prepare a SQL statement to check for existing user
            $stmt = $conn->prepare("SELECT 1 FROM Section WHERE section_ID = ? AND course = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
    
            // Bind the input parameters to the prepared statement
            $stmt->bind_param('ss', $this->sectionID, $this->courseName);
            $stmt->execute();
    
            // Fetch the results
            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception("Failed to get result: " . $stmt->error);
            }
    
            // Check the number of rows in the result
            if ($result->num_rows > 0) {
                // If rows are found, data exists, return false
                return false;
            } else {
                // If no rows are found, data does not exist, return true
                return true;
            }
        } catch (Exception $e) {
            // Optionally, handle exceptions and errors if necessary
            error_log('Error in validate function: ' . $e->getMessage());
            return null; // Or consider re-throwing the exception depending on your error handling strategy
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
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 4AdminSections.php"); // Redirect with error message
            exit;
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
