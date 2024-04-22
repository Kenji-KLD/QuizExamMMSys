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
    private $age;
    private $sex;
    private $address;


    public function __construct($student_ID, $username, $password, $fName, $mName, $lName, $email, $section_ID, $age, $sex, $address) {
        $this->student_ID = $student_ID;
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->section_ID;
        $this->age = $age;
        $this->section_ID = $section_ID;
        $this->sex = $sex;
        $this->address = $address;
    }

    public function validate() {
        // Include the database connection file
        include "connection.php";
    
        try {
            // Prepare a SQL statement to check for existing user
            $stmt = $conn->prepare("SELECT 1 FROM Account WHERE userName = ? AND fName = ? AND lName = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
    
            // Bind the input parameters to the prepared statement
            $stmt->bind_param('sss', $this->username, $this->fName, $this->lName);
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
    

    public function register() {
        include "connection.php";

        $conn->begin_transaction(); 

        try {
            // Insert into Account table
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email, age, sex, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('ssssssiss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->sex, $this->address);
            $stmt1->execute();
            $stmt1->close();

            // Retrieve user_ID
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
                // Extract data from CSV row
                $student_ID = $data[0]; // Assuming the first column contains the custom student_ID
                $userName = $data[1];
                $password = password_hash($data[2], PASSWORD_DEFAULT);
                $fName = $data[3];
                $mName = $data[4];
                $lName = $data[5];
                $email = $data[6];
                $age = $data[7];
                $sex = $data[8];
                $address = $data[9];

        
                $stmt = $conn->prepare("INSERT INTO Account(userName, password, fName, mName, lName, email, age, sex, address) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssiss", $userName, $password, $fName, $mName, $lName, $email, $age, $sex, $address);
                $stmt->execute();

                // Get the auto-generated user_ID
                $user_ID = $stmt->insert_id;

                // Insert into Student table with custom student_ID
                $stmt1 = $conn->prepare("INSERT INTO Student(student_ID, user_ID) VALUES (?, ?)");
                $stmt1->bind_param("si", $student_ID, $user_ID);
                $stmt1->execute();

                // Close prepared statements
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
    private $user_ID, $username, $fName, $mName, $lName, $email, $age, $address, $sex, $password, $student_ID;

    public function __construct($user_ID, $username, $fName, $mName, $lName, $email, $age, $address, $sex, $password, $student_ID) {
       $this->user_ID = $user_ID;
       $this->username = $username;
       $this->fName = $fName;
       $this->mName = $mName;
       $this->lName = $lName;
       $this->email = $email;
       $this->age = $age;
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
            
            $stmt = $conn->prepare("UPDATE Account SET userName = ?, fName = ?, mName = ?, lName = ?, email = ?, age = ?, sex = ?, address = ? WHERE user_ID = ?");
            $stmt->bind_param('sssssissi', $this->username, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->sex, $this->address, $this->user_ID);
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
        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE Account SET password = ? WHERE user_ID = ?");
        $stmt->bind_param('si', $hashedPassword, $this->user_ID);
        $stmt->execute();
        $stmt->close();
    }

}



class DeleteStudent{
    private $user_ID;

    public function __construct($user_ID){
        $this->user_ID = $user_ID;
    }

    public function delete() {
        include "connection.php"; 

        try {
           
            $stmt1 = $conn->prepare("UPDATE account SET userName = 'q1w2e3r4t5y6mamz' where user_ID = ?");
            
            $stmt1->bind_param('i', $this->user_ID);
            $stmt1->execute();
           
            return true;
        } catch(Exception $e) {
            $_SESSION['notif'] = $e->getMessage();
            header("Location: 2AdminStudents.php"); // Redirect with error message
            exit;
        }
    }


}


?>
