<?php
require_once 'db_connect.php';
require_once 'functions.php';

class Model{
    private $conn;
    private $db;

    public function __construct(){
        $this->conn = new Connection();
        $this->db = $this->conn->conn;
    }

    private function logError($error){
        $logDirectory = __DIR__ . '/log';
        $logFilePath = $logDirectory . '/database_error_log.txt';
    
        if (!is_dir($logDirectory)){
            mkdir($logDirectory, 0755, true);
        }
        if (!file_exists($logFilePath)){
            touch($logFilePath);
        }
    
        $timestamp = date("Y-m-d H:i:s");
        if($error instanceof Exception){
            $errorMessage = "Error: " . $error->getMessage();
        }
        else{
            $errorMessage = "Error: " . $error;
        }
        $logMessage = "[" . $timestamp . "]" . " - " . $errorMessage . "\n";
      
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }

    public function createSessionToken($input_username){
        try{ do{ $token = lowercaseNumericString(32); // Generates a unique user session token. 32 characters long.
            $query = "
            SELECT session_token FROM LoginToken WHERE session_token = ?
            ";
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $token);
            $stmt->execute(); $stmt->store_result(); $tokenSimilar_count = $stmt->num_rows;
        } while($tokenSimilar_count > 0);
            $query = "
            INSERT INTO LoginToken(session_token, user_ID, tokenExpiration) VALUES
            (?, (SELECT user_ID FROM Account WHERE userName = ?), NOW() + INTERVAL 1 DAY)
            ";
            $stmt = $this->db->prepare($query); $stmt->bind_param("ss", $token, $input_username);
            $stmt->execute(); $stmt->close();
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readSessionToken($input_username){
        $query = "
        SELECT session_token FROM LoginToken WHERE 
        user_ID = (SELECT user_ID FROM Account WHERE userName = ?)
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_username);
            $stmt->execute(); $stmt->store_result(); $stmt->bind_result($username_sessionToken);
            if($stmt->fetch()){
                return $username_sessionToken;
            }
            else{
                return false;
            }
            $stmt->close();
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readPasswordHash($input_username){
        $query = "
        SELECT password FROM Account WHERE userName = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_username);
            $stmt->execute(); $stmt->store_result(); $stmt->bind_result($username_passwordHash);
            if($stmt->fetch()){
                return $username_passwordHash;
            }
            else{
                return false;
            }
            $stmt->close();
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readQuestionnaire($input_questionnaireID){
        $data = [];
        $query = "
        SELECT questionNumber, questionText, questionChoices, questionAnswer 
        FROM QuestionBank
        WHERE questionnaire_ID = ?
        ";
        
        $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_questionnaireID);
        $stmt->execute(); $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        
        $result->free();
        $stmt->close();
        
        return json_encode($data);
    }

    public function readSectionList($input_section){
        $query = "
        SELECT CONCAT(a.lName, ', ', a.fName, ' ', COALESCE(CONCAT(LEFT(NULLIF(a.mName, ''), 1), '.'), '')) AS fullName, a.age AS age, a.sex AS sex, a.email AS email, a.address AS address
        FROM Account a
        INNER JOIN Student s ON a.user_ID = s.user_ID
        INNER JOIN Class c ON s.student_ID = c.student_ID
        WHERE c.section_ID = ?
        ORDER BY fullName;
        ";
        $data = [];

        $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_section);
        $stmt->execute(); $stmt->bind_result($fullName, $age, $sex, $email, $address);

        // Name of outgoing variables here
        while($stmt->fetch()){
            $studentData = [
                "fullName" => $fullName,
                "age" => $age,
                "sex" => $sex,
                "email" => $email,
                "address" => $address
            ];

            $data[] = $studentData;
        }

        $stmt->close();
        return json_encode($data);
    }
}
?>