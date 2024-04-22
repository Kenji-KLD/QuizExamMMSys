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
        include "connection.php";
        $stmt = $conn->prepare("SELECT 1 FROM Subject WHERE subject_ID = ? AND subjectName = ?");
        $stmt->bind_param('ss', $this->subjectID, $this->subjectName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        return false;
        }
    }

    // Method to add a new subject to the database
    public function add() {
        include "connection.php"; // Includes the database connection

        $conn->begin_transaction(); // Begins a transaction
        try {
            $sql = "INSERT INTO Subject (subject_ID, subjectName, unitsAmount, subjectType) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssis', $this->subjectID, $this->subjectName, $this->unitsAmount, $this->subjectType);
            $stmt->execute();
            $stmt->close();
            $conn->commit(); // Commits the transaction
            $conn->close();
            return true;
        } catch (Exception $e) {
            $conn->rollback(); // Rolls back the transaction in case of error
            $conn->close();
            return false;
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

    public function validate() {
        include "connection.php";
        $stmt = $conn->prepare("SELECT 1 FROM Subject WHERE subjectName = ?");
        $stmt->bind_param('s', $this->subjectName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        return false;
        }
    }
    

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
            return false;
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
            return false;
        }
    }
}



?>
