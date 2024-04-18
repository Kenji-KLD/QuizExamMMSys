<?php
class LogIn{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function validate() {
        if(empty($this->username) || empty($this->password)) {
            return false;
        }
        return true;
    }

    public function login() {
        include "connection.php";
    
        try {
            $stmt = $conn->prepare("SELECT hashPassword, salt FROM account WHERE username = ?");
            $stmt->bind_param('s', $this->username);
            $stmt->execute();
    
            $result = $stmt->get_result();
    
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $storedHashPassword = $row['hashPassword'];
                $salt = $row['salt'];
    
                $enteredPasswordHash = hash('sha256', $this->password . $salt);
    
                if ($enteredPasswordHash === $storedHashPassword) {
                    return true; 
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
        } catch(Exception $e) {
            return false; 
        }
    }
    
    }


?>