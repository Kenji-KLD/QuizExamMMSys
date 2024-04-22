<?php

class AddSubject {
    private $subjectID;
    private $subjectName;
    private $unitsAmount;
    private $subjectType;

    // Constructor for initializing the subject details
    public function __construct($subjectID, $subjectName, $unitsAmount, $subjectType) {
        $this->subjectID = $subjectID;
        $this->subjectName = $subjectName;
        $this->unitsAmount = $unitsAmount;
        $this->subjectType = $subjectType;
    }

    // Method to validate if a subject ID already exists in the database
   public function validate() {
        // Include the database connection file
        include "connection.php";
    
        try {
            // Prepare a SQL statement to check for existing user
            $stmt = $conn->prepare("SELECT 1 FROM Subject WHERE subject_ID = ? AND subjectName = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            $stmt->bind_param('ss', $this->subjectID, $this->subjectName);
            $stmt->execute();
    
            // Fetch the results
            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception("Failed to get result: " . $stmt->error);
            }
    
            if ($result->num_rows > 0) {
                
                return false;
            } else {
               
                return true;
            }
        } catch (Exception $e) {
            error_log('Error in validate function: ' . $e->getMessage());
            return null;
        }
    }

    public function add() {
        include "connection.php";

        $conn->begin_transaction(); 
        try {
            $sql = "INSERT INTO Subject (subject_ID, subjectName, unitsAmount, subjectType) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssis', $this->subjectID, $this->subjectName, $this->unitsAmount, $this->subjectType);
            $stmt->execute();
            $stmt->close();
            $conn->commit();
            $conn->close();
            return true;

        } catch (Exception $e) {
            $conn->rollback(); 
            $conn->close();
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 3AdminSubjects.php"); 
            exit;
        }
    }
}


class EditSubject {
    private $subjectID;
    private $subjectName;
    private $unitsAmount;
    private $subjectType;
   
    public function __construct($subjectID, $subjectName, $unitsAmount, $subjectType) {
       $this->subjectID = $subjectID;
       $this->subjectName = $subjectName;
       $this->unitsAmount = $unitsAmount;
       $this->subjectType = $subjectType;
    }

//    public function validate() {
     
//         include "connection.php";
    
//         try {
 
//             $stmt = $conn->prepare("SELECT 1 FROM Subject WHERE subjectName = ?");
//             if (!$stmt) {
//                 throw new Exception("Failed to prepare statement: " . $conn->error);
//             }
    
//             $stmt->bind_param('s', $this->subjectName);
//             $stmt->execute();
    
//             $result = $stmt->get_result();
//             if (!$result) {
//                 throw new Exception("Failed to get result: " . $stmt->error);
//             }
    
//             if ($result->num_rows > 0) {
               
//                 return false;
//             } else {
              
//                 return true;
//             }
//         } catch (Exception $e) {
          
//             error_log('Error in validate function: ' . $e->getMessage());
//             return null; 
//         }
//     }
    

    public function edit() {
        include "connection.php";
        global $conn;
    
        $conn->begin_transaction(); 
        try {
            $stmt = $conn->prepare("UPDATE Subject SET subjectName = ?, unitsAmount = ?, subjectType = ? WHERE subject_ID = ?");
            $stmt->bind_param('siss', $this->subjectName, $this->unitsAmount, $this->subjectType, $this->subjectID);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
                return true;
        } catch (Exception $e) {
            $conn->rollback();
            $conn->close();
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 3AdminSubjects.php"); 
            exit;
        }
    }
}


class DeleteSubject {
    private $subjectID;

    public function __construct($subjectID) {
        $this->subjectID = $subjectID;
    }

    public function delete() {
        include "connection.php";

        try {
            $stmt = $conn->prepare("DELETE FROM Subject WHERE subject_ID = ?");
            $stmt->bind_param('s', $this->subjectID);
            $stmt->execute();
            $stmt->close();

            return true;
        } catch(Exception $e) {
            $conn->rollback();
            $conn->close();
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 3AdminSubjects.php"); 
            exit;
        }
    }
}

class AssignSubject{
    private $faculty_ID;
    private $subjectID;
   
    public function __construct($faculty_ID, $subjectID)
    {
        $this->faculty_ID = $faculty_ID;
        $this->subjectID = $subjectID;
    }

    public function checkEmpty(){
        if(empty($this->faculty_ID) || empty($this->subjectID)){
            return false;
        }
        else{
            return true;
        }
    }
    
    public function validate(){
        include "connection.php";
    
        try {
 
            $stmt = $conn->prepare("SELECT 1 FROM SubjectHandle WHERE faculty_ID = ? AND subject_ID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
    
            $stmt->bind_param('is', $this->faculty_ID, $this->subjectID);
            $stmt->execute();
    
            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception("Failed to get result: " . $stmt->error);
            }
    
            if ($result->num_rows > 0) {
               
                return false;
            } else {
              
                return true;
            }
        } catch (Exception $e) {
          
            error_log('Error in validate function: ' . $e->getMessage());
            return null; 
        }
    }

    public function assign(){
        include "connection.php";
        global $conn;
    
        $conn->begin_transaction(); 
        try {
            $stmt = $conn->prepare("INSERT INTO SubjectHandle(faculty_ID, subject_ID) VALUES (?, ?)");
            $stmt->bind_param('is', $this->faculty_ID, $this->subjectID);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
                return true;
        } catch (Exception $e) {
            $conn->rollback();
            $conn->close();
            $_SESSION['notif1'] = $e->getMessage();
            header("Location: 3AdminSubjects.php"); 
            exit;
        }

    }
}



?>
