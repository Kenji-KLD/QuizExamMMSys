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
    private $gender;
    private $address;


    public function __construct($student_ID, $username, $password, $fName, $mName, $lName, $email, $section_ID, $age, $gender, $address) {
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
        $this->gender = $gender;
        $this->address = $address;
    }

    public function validate() {
        if (empty($this->student_ID) || empty($this->username) || empty($this->password) || empty($this->fName) || empty($this->lName) || empty($this->email) || empty($this->section_ID) || empty($this->age) || empty($this->gender) || empty($this->address)) {
            return false;
        }
        return true;
    }

    public function register() {
        include "connection.php";

        $conn->begin_transaction(); 

        try {
            // Insert into Account table
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email, age, gender, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('ssssssiss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->gender, $this->address);
            $stmt1->execute();
            $stmt1->close();

            // Retrieve user_ID
            $user_ID = $conn->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO Student (student_ID, user_ID) VALUES (?,?)");
            $stmt2->bind_param('si', $this->student_ID, $user_ID);
            $stmt2->execute();
            $stmt2->close();

            // Insert into Student table
            $this->insertClassTable($conn);

            $conn->commit();
            return true;

        } catch (Exception $e) {
            // Rollback the transaction on failure
            $conn->rollback();
            return false;
        }
    }
   public function insertClassTable($conn){
    $stmt = $conn->prepare("INSERT INTO Class (student_ID, section_ID) VALUES (?, ?)");
    $stmt->bind_param('ss', $this->student_ID, $this->section_ID);
    $stmt->execute();
    $stmt->close();

   } 

    
}

class EditStudent {
    private $user_ID, $username, $fName, $mName, $lName, $email, $age, $address, $gender, $password, $student_ID, $section_ID;

    public function __construct($user_ID, $username, $fName, $mName, $lName, $email, $age, $address, $gender, $password, $student_ID, $section_ID) {
       $this->user_ID = $user_ID;
       $this->username = $username;
       $this->fName = $fName;
       $this->mName = $mName;
       $this->lName = $lName;
       $this->email = $email;
       $this->age = $age;
       $this->address = $address;
       $this->gender = $gender;
       $this->password = $password;
       $this->student_ID = $student_ID;
       $this->section_ID = $section_ID;
    }

    public function validate() {
        // Validate required fields
        if (empty($this->username) || empty($this->fName) || empty($this->mName) || empty($this->lName) || empty($this->email) || empty($this->age) || empty($this->address) || empty($this->student_ID)) {
            return false;
        }
        return true;
    }

    public function edit() {
        include "connection.php"; 
        $conn->begin_transaction();
        
        try {
            
            $stmt = $conn->prepare("UPDATE Account SET userName = ?, fName = ?, mName = ?, lName = ?, email = ?, age = ?, gender = ?, address = ? WHERE user_ID = ?");
            $stmt->bind_param('sssssissi', $this->username, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->gender, $this->address, $this->user_ID);
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
            $stmt2 = $conn->prepare("UPDATE Class SET section_ID = ? WHERE student_ID = ?");
            $stmt2->bind_param('ss', $this->section_ID, $this->student_ID);
            $stmt2->execute();
            $stmt2->close();

               
          
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            // Log or display error message
            echo "Error: " . $e->getMessage();
            return false;
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
           
            return false;
        }
    }


}


?>
