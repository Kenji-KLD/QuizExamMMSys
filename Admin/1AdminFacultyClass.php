<?php

class RegistrationFaculty {
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $mysqlDate;
    private $sex;
    private $address;

    public function __construct($username, $password, $fName, $mName, $lName, $email, $mysqlDate, $sex, $address) {
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->mysqlDate = $mysqlDate;
        $this->sex = $sex;
        $this->address = $address;
    }

    public function validate() {
     
        include "connection.php";
    
        try {
          
            $stmt = $conn->prepare("SELECT 1 FROM Account WHERE userName = ? AND fName = ? AND lName = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
    
            
            $stmt->bind_param('sss', $this->username, $this->fName, $this->lName);
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

    public function register() {
        include "connection.php";

        $conn->begin_transaction(); 
        try {
           
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email, birthdate, sex, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('sssssssss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email,$this->mysqlDate, $this->sex, $this->address);
            $stmt1->execute();
            $stmt1->close();

            
            $user_id = $conn->insert_id;

             
            $stmt2 = $conn->prepare("INSERT INTO Faculty (user_ID) VALUES (?)");
            $stmt2->bind_param('i', $user_id);
            $stmt2->execute();
            $stmt2->close();

            // // Retrieve faculty_ID
            // $faculty_id = $conn->insert_id;
            // $this->insertSubjectHandle($conn, $faculty_id);
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 1AdminProfessors.php"); // Redirect with error message
            exit;
        }
    }
    // private function insertSubjectHandle($conn, $faculty_id) {
    //     $stmt = $conn->prepare("INSERT INTO SubjectHandle (faculty_id, subject_id) VALUES (?, ?)");
    //     $stmt->bind_param('is', $faculty_id, $this->subject_id);
    //     $stmt->execute();
    //     $stmt->close();
    // }
}

class EditFaculty {
    private $user_ID;
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $mysqlDate;
    private $sex;
    private $address;

    public function __construct($user_ID, $username, $password, $fName, $mName, $lName, $email, $mysqlDate, $sex, $address) {
        $this->user_ID = $user_ID;
        $this->username = $username;   
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->mysqlDate = $mysqlDate;
        $this->sex = $sex;
        $this->address = $address;
    }

    // public function validate() {
    //     // Include the database connection file
    //     include "connection.php";
    
    //     try {
    //         // Prepare a SQL statement to check for existing user
    //         $stmt = $conn->prepare("SELECT 1 FROM Account WHERE userName = ? AND fName = ? AND lName = ?");
    //         if (!$stmt) {
    //             throw new Exception("Failed to prepare statement: " . $conn->error);
    //         }
    
    //         // Bind the input parameters to the prepared statement
    //         $stmt->bind_param('sss', $this->username, $this->fName, $this->lName);
    //         $stmt->execute();
    
    //         // Fetch the results
    //         $result = $stmt->get_result();
    //         if (!$result) {
    //             throw new Exception("Failed to get result: " . $stmt->error);
    //         }
    
    //         // Check the number of rows in the result
    //         if ($result->num_rows > 0) {
    //             // If rows are found, data exists, return false
    //             return false;
    //         } else {
    //             // If no rows are found, data does not exist, return true
    //             return true;
    //         }
    //     } catch (Exception $e) {
    //         // Optionally, handle exceptions and errors if necessary
    //         error_log('Error in validate function: ' . $e->getMessage());
    //         return null; // Or consider re-throwing the exception depending on your error handling strategy
    //     }
    // }

    public function edit() {
        include "connection.php";

        global $conn;

        $conn->begin_transaction(); 
        try {
            $stmt = $conn->prepare("UPDATE account SET userName = ?, fName = ?, mName = ?, lName = ?, email = ?, birthdate = ?, sex = ?, address = ? WHERE user_ID = ?");
                $stmt->bind_param('ssssssssi', $this->username, $this->fName, $this->mName, $this->lName, $this->email,  $this->mysqlDate, $this->sex, $this->address, $this->user_ID);
                $stmt->execute();
                $stmt->close();

            if (!empty($this->password)) {
                $this->updatePasswordHandle($conn);
               
            }
            // $stmt2 = $conn->prepare("SELECT faculty_ID FROM Faculty WHERE user_id = ?");
            // $stmt2->bind_param('i', $this->user_ID);
            // $stmt2->execute();
            // $stmt2->bind_result($faculty_id);
            // $stmt2->fetch();
            // $stored_faculty_id = $faculty_id;
            // $stmt2->close();

            // $this->updateSubjectHandle($conn, $stored_faculty_id);
                $conn->commit();
                $conn->close();

                return true;

        } catch (Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 1AdminProfessors.php"); // Redirect with error message
            exit;
        }
    }

    // private function updateSubjectHandle($conn, $stored_faculty_id) {
    //     $stmt = $conn->prepare("UPDATE SubjectHandle SET subject_ID = ? WHERE faculty_ID = ?");
    //     $stmt->bind_param('si', $this->subject_id, $stored_faculty_id);
    //     $stmt->execute();
    //     $stmt->close();
    // }
    
    private function updatePasswordHandle($conn){
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE Account SET password = ? WHERE user_ID = ?");
        $stmt->bind_param('si', $hashedPassword, $this->user_ID);
        $stmt->execute();
        $stmt->close();
    }
}


class DeleteFaculty {
    private $user_ID;

    public function __construct($user_ID) {
        $this->user_ID = $user_ID;
    }

    // Helper function to generate a random string of characters and digits
    private function generateRandomString($length = 12) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function delete() {
        include "connection.php";

        try {
            // Generate a random string to concatenate with 'deleted'
            $randomString = $this->generateRandomString();

            // Update the userName field to 'deleted' + random string
            $newUserName = 'deleted_' . $randomString;

            $stmt = $conn->prepare("UPDATE account SET userName = ? WHERE user_ID = ?");
            $stmt->bind_param('si', $newUserName, $this->user_ID);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 1AdminProfessors.php");
            exit;
        }
    }
}



?>
