<?php

class RegistrationFaculty {
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $subject_id;
    private $age;
    private $sex;
    private $address;

    public function __construct($username, $password, $fName, $mName, $lName, $email, $subject_id, $age, $sex, $address) {
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->subject_id = $subject_id;
        $this->age = $age;
        $this->sex = $sex;
        $this->address = $address;
    }

    public function validate() {
        if (empty($this->username) || empty($this->password) || empty($this->fName) || empty($this->lName) || empty($this->email) || empty($this->age) || empty($this->sex) || empty($this->address)) {
            return false;
        }
        return true;
    }

    public function register() {
        include "connection.php";

        $conn->begin_transaction(); 
        try {
            // Insert into Account
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email, age, sex, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('ssssssiss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->sex, $this->address);
            $stmt1->execute();
            $stmt1->close();

            // Retrieve user_ID
            $user_id = $conn->insert_id;

            // Insert into Faculty
            $stmt2 = $conn->prepare("INSERT INTO Faculty (user_ID) VALUES (?)");
            $stmt2->bind_param('i', $user_id);
            $stmt2->execute();
            $stmt2->close();

            // Retrieve faculty_ID
            $faculty_id = $conn->insert_id;

         
            $this->insertSubjectHandle($conn, $faculty_id);
            

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
    private function insertSubjectHandle($conn, $faculty_id) {
        $stmt = $conn->prepare("INSERT INTO SubjectHandle (faculty_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param('is', $faculty_id, $this->subject_id);
        $stmt->execute();
        $stmt->close();
    }
}

class EditFaculty {
    private $user_ID;
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;
    private $subject_id;
    private $age;
    private $gender;
    private $address;

    public function __construct($user_ID, $username, $password, $fName, $mName, $lName, $email, $subject_id, $age, $gender, $address) {
        $this->user_ID = $user_ID;
        $this->username = $username;   
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
        $this->subject_id = $subject_id;
        $this->age = $age;
        $this->gender = $gender;
        $this->address = $address;
    }

    public function validate() {
        if (empty($this->username) || empty($this->fName) || empty($this->lName) || empty($this->email) || empty($this->age) || empty($this->gender) || empty($this->address)) {
            return false;
        }
        return true;
    }

    public function edit() {
        include "connection.php";

        global $conn;

        $conn->begin_transaction(); 
        try {
            $stmt = $conn->prepare("UPDATE account SET userName = ?, fName = ?, mName = ?, lName = ?, email = ?, age = ?, gender = ?, address = ? WHERE user_ID = ?");
                $stmt->bind_param('sssssissi', $this->username, $this->fName, $this->mName, $this->lName, $this->email, $this->age, $this->gender, $this->address, $this->user_ID);
                $stmt->execute();
                $stmt->close();

            if (!empty($this->password)) {
                $this->updatePasswordHandle($conn);
               
            }
            
            
            $stmt2 = $conn->prepare("SELECT faculty_ID FROM Faculty WHERE user_id = ?");
            $stmt2->bind_param('i', $this->user_ID);
            $stmt2->execute();
            $stmt2->bind_result($faculty_id);
            $stmt2->fetch();
            $stored_faculty_id = $faculty_id;
            $stmt2->close();


            $this->updateSubjectHandle($conn, $stored_faculty_id);
                $conn->commit();
                $conn->close();

                return true;

        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    private function updateSubjectHandle($conn, $stored_faculty_id) {
        $stmt = $conn->prepare("UPDATE SubjectHandle SET subject_ID = ? WHERE faculty_ID = ?");
        $stmt->bind_param('si', $this->subject_id, $stored_faculty_id);
        $stmt->execute();
        $stmt->close();
    }
    
    private function updatePasswordHandle($conn){
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE Account SET password = ? WHERE user_ID = ?");
        $stmt->bind_param('si', $hashedPassword, $this->user_ID);
        $stmt->execute();
        $stmt->close();
    }
}


class DeleteFaculty{
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
