<?php


class RegistrationStudent {
    private $student_id;
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;

    public function __construct($student_id, $username, $password, $fName, $mName, $lName, $email) {
        $this->student_id = $student_id;
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
    }

    public function validate() {
        if (empty($this->student_id) || empty($this->username) || empty($this->password) || empty($this->fName) || empty($this->lName) || empty($this->email)) {
            return false;
        }
        return true;
    }

    public function register() {
        include "connection.php"; 

        try {
        
            $stmt1 = $conn->prepare("INSERT INTO Account (userName, password, fName, mName, lName, email) VALUES (?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt1->bind_param('ssssss', $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email);
            $stmt1->execute();

           
            $user_id = $stmt1->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO Student (student_ID, user_ID) VALUES (?, ?)");
            $stmt2->bind_param('si', $this->student_id, $user_id); 
            $stmt2->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

class DeleteStudent{
    private $user_id;

    public function __construct($user_id){
        $this->user_id = $user_id;
    }

    public function delete() {
        include "connection.php"; 

        try {
           
            $stmt1 = $conn->prepare("update Account set username = q1w2e3r4t5y6mamz and hassPassword = z1x2c3v4b5n6m7 where user_id = ?");
            
            $stmt1->bind_param('i', $this->user_id);
            $stmt1->execute();
            $stmt2->execute();
           
            return true;
        } catch(Exception $e) {
           
            return false;
        }
    }


}

class EditStudent {
    private $student_id;
    private $username;
    private $password;
    private $fName;
    private $mName;
    private $lName;
    private $email;

    public function __construct($student_id, $username, $password, $fName, $mName, $lName, $email) {
        $this->student_id = $student_id;
        $this->username = $username;
        $this->password = $password;
        $this->fName = $fName;
        $this->mName = $mName;
        $this->lName = $lName;
        $this->email = $email;
    }

    public function validate() {
      
        if (empty($this->student_id) || empty($this->username) || empty($this->password) || empty($this->fName) || empty($this->lName) || empty($this->email)) {
            return false;
        }

       

        return true;
    }

    public function edit() {
        include "connection.php"; 

        try {
            
            $stmt = $conn->prepare("UPDATE Student SET student_ID = ?, user_ID = (SELECT user_ID FROM Account WHERE userName = ?), password = ?, fName = ?, mName = ?, lName = ?, email = ? WHERE student_ID = ?");
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT); 
            $stmt->bind_param('ssssssss', $this->student_id, $this->username, $hashedPassword, $this->fName, $this->mName, $this->lName, $this->email, $this->student_id);
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