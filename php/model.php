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


    // CREATE FUNCTIONS


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


    // READ FUNCTIONS


    public function readAccountType($input_username){
        $query = "
        SELECT
            (SELECT COUNT(*) FROM Student s WHERE s.user_ID = ac.user_ID) AS student_count,
            (SELECT COUNT(*) FROM Admin ad WHERE ad.user_ID = ac.user_ID) AS admin_count,
            (SELECT COUNT(*) FROM Faculty f WHERE f.user_ID = ac.user_ID) AS faculty_count
        FROM Account ac
        WHERE ac.userName = ?;
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_username);
            $stmt->execute(); $stmt->store_result(); $stmt->bind_result($student_count, $admin_count, $faculty_count);
            $stmt->fetch(); $stmt->close();

            if($student_count > 0){
                return "STUDENT";
            }
            elseif($admin_count){
                return "ADMIN";
            }
            elseif($faculty_count){
                return "FACULTY";
            }
            else{
                return "ID ERROR";
            }
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readHandledSubject($input_facultyID){

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
        
        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_questionnaireID);
            $stmt->execute(); $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            
            $result->free();
            $stmt->close();
            
            return json_encode($data);
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readSectionList($input_section){
        $query = "
        SELECT 
            CONCAT(a.lName, ', ', a.fName, ' ', COALESCE(CONCAT(LEFT(NULLIF(a.mName, ''), 1), '.'), '')) AS fullName, 
            a.age AS age, 
            a.sex AS sex, 
            a.email AS email, 
            a.address AS address
        FROM Account a
        INNER JOIN Student s ON a.user_ID = s.user_ID
        INNER JOIN Class c ON s.student_ID = c.student_ID
        WHERE c.section_ID = ?
        ORDER BY fullName;
        ";
        $data = [];

        try{
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
        catch(Exception $e){
            logError($e);
        }
    }

    public function readSessionToken($input_username){
        $query = "
        SELECT session_token FROM LoginToken 
        WHERE user_ID = (SELECT user_ID FROM Account WHERE userName = ?)
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

    public function readSessionData($input_sessionToken){
        $query = "
        SELECT a.user_ID, a.userName FROM Account a
        INNER JOIN LoginToken l ON a.user_ID = l.user_ID
        WHERE l.session_token = ?;
        ";
        $data = [];
        
        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_sessionToken);
            $stmt->execute(); $stmt->bind_result($user_ID, $userName); $stmt->fetch();

            $sessionData = [
                "user_ID" => $user_ID,
                "userName" => $userName
            ];

            $stmt->close();
            return $sessionData;
        }
        catch(Exception $e){
            logError($e);
        }
    }

    public function readUnhandledSection(){
        $query = "
        SELECT se.section_ID FROM Section se
        LEFT JOIN SectionHandle seh ON se.section_ID = seh.section_ID
        LEFT JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
        WHERE suh.subHandle_ID IS NULL;
        ";
        $section_ID = [];

        $result = $this->db->query($query);

        while($row = $result->fetch_assoc()){
            $section_ID[] = $row['section_ID'];
        }

        $result->free();

        return $section_ID;
    }
}
?>