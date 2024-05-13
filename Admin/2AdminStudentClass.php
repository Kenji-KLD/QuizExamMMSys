<?php

class RegistrationStudent {
    private $student_ID;
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $section_ID;
    private $mysqlDate;
    private $sex;
    private $address;


    public function __construct($student_ID, $username, $password, $fName, $mName, $lName, $email, $section_ID, $mysqlDate, $sex, $address) {
        $this->student_ID = $student_ID;
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->section_ID;
        $this->mysqlDate = $mysqlDate;
        $this->section_ID = $section_ID;
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
         
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email, birthdate , sex, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('sssssssss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email, $this->mysqlDate, $this->sex, $this->address);
            $stmt1->execute();
            $stmt1->close();

            
            $user_ID = $conn->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO Student (student_ID, user_ID) VALUES (?,?)");
            $stmt2->bind_param('si', $this->student_ID, $user_ID);
            $stmt2->execute();
            $stmt2->close();

            // Insert into Student table
           // $this->insertClassTable($conn);

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 2AdminStudents.php"); // Redirect with error message
            exit;
        }
    }
   public function insertClassTable($conn){
    $stmt = $conn->prepare("INSERT INTO Class (student_ID, section_ID) VALUES (?, ?)");
    $stmt->bind_param('ss', $this->student_ID, $this->section_ID);
    $stmt->execute();
    $stmt->close();

   } 

    
}

class ImportStudent {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function import() {
        include "connection.php";
        
        try {
            $handle = fopen($this->file, 'r');

            fgetcsv($handle, 1000, ",");
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                $student_ID = $data[0]; 
                $userName = $data[1];
                $password = password_hash($data[2], PASSWORD_DEFAULT);
                $fName = $data[3];
                $mName = $data[4];
                $lName = $data[5];
                $email = $data[6];
                $birthdate = $data[7];
                $sex = $data[8];
                $address = $data[9];

        
                $stmt = $conn->prepare("INSERT INTO Account(userName, password, fName, mName, lName, email, birthdate, sex, address) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $userName, $password, $fName, $mName, $lName, $email, $birthdate, $sex, $address);
                $stmt->execute();

                // get the user_ID
                $user_ID = $stmt->insert_id;

                
                $stmt1 = $conn->prepare("INSERT INTO Student(student_ID, user_ID) VALUES (?, ?)");
                $stmt1->bind_param("si", $student_ID, $user_ID);
                $stmt1->execute();

                
                $stmt->close();
                $stmt1->close();
            }
    
            fclose($handle);
            header("Location: 2AdminStudents.php");
        } catch (Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 2AdminStudents.php"); // Redirect with error message
            exit;
        }
    }
}



class EditStudent {
    private $user_ID, $username, $fName, $mName, $lName, $email, $mysqlDate, $address, $sex, $password, $student_ID;

    public function __construct($user_ID, $username, $fName, $mName, $lName, $email, $mysqlDate, $address, $sex, $password, $student_ID) {
       $this->user_ID = $user_ID;
       $this->username = $username;
       $this->fName = $fName;
       $this->mName = $mName;
       $this->lName = $lName;
       $this->email = $email;
       $this->mysqlDate = $mysqlDate;
       $this->address = $address;
       $this->sex = $sex;
       $this->password = $password;
       $this->student_ID = $student_ID;
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
        $conn->begin_transaction();
        
        try {
            
            $stmt = $conn->prepare("UPDATE Account SET userName = ?, fName = ?, mName = ?, lName = ?, email = ?, birthdate = ?, sex = ?, address = ? WHERE user_ID = ?");
            $stmt->bind_param('ssssssssi', $this->username, $this->fName, $this->mName, $this->lName, $this->email, $this->mysqlDate, $this->sex, $this->address, $this->user_ID);
            $stmt->execute();
            // $stmt->close();

              // Update password if provided
              if (!empty($this->password)) {
                $this->updatePasswordHandle($conn);
            }

            // if($stmt->execute()){
            //     $ID = $this->user_ID;
            //     $getRow = "SELECT student_ID FROM Student WHERE user_ID = $ID";
            //     $resultRow = mysqli_query($conn, $getRow);
            //     $student_ID = mysqli_fetch_row($resultRow);

            //     $stmt1 = $conn->prepare("UPDATE Student SET student_ID = ? WHERE user_ID = ? AND student_ID = ?");
            //     $stmt1->bind_param('si', $this->student_ID, $this->user_ID, $student_ID);
            //     $stmt1->execute();
            //     $stmt1->close();
                
            // }
            // $stmt2 = $conn->prepare("UPDATE Class SET section_ID = ? WHERE student_ID = ?");
            // $stmt2->bind_param('ss', $this->section_ID, $this->student_ID);
            // $stmt2->execute();
            // $stmt2->close();

               
          
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 2AdminStudents.php"); // Redirect with error message
            exit;
        }
    }

    private function updatePasswordHandle($conn) { 
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE Account SET password = ? WHERE user_ID = ?");
        $stmt->bind_param('si', $hashedPassword, $this->user_ID);
        $stmt->execute();
        $stmt->close();
    }

}



class DeleteStudent {
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
