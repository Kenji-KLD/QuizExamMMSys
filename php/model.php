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
            $this->logError($e);
        }
    }

    public function createSubHandle($input_facultyID, $input_subjectID, $input_sectionID){
        $query = "
        INSERT INTO SectionHandle(subHandle_ID, section_ID) VALUES
        ((SELECT subHandle_ID FROM SubjectHandle WHERE faculty_ID = ? AND subject_ID = ?), ?)
        ";
        
        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("iss", $input_facultyID, $input_subjectID, $input_sectionID);
            $stmt->execute(); $stmt->close();
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }


    // READ FUNCTIONS


    public function readAccountDetails($input_userID){
        $data = [];
        $query = "
        SELECT
            CONCAT(fName, ' ', COALESCE(CONCAT(LEFT(NULLIF(mName, ''), 1), '.'), ''), ' ', lName) AS fullName,
            email, sex, age
        FROM Account
        WHERE user_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_userID);
            $stmt->execute(); $stmt->bind_result($fullName, $email, $sex, $age);

            while($stmt->fetch()){
                $data = [
                    'fullName' => $fullName,
                    'email' => $email,
                    'sex' => $sex,
                    'age' => $age
                ];
            }

            $stmt->close();
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }
    
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
            elseif($admin_count > 0){
                return "ADMIN";
            }
            elseif($faculty_count > 0){
                return "FACULTY";
            }
            else{
                return "ID ERROR";
            }
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }

    public function readHandledSection($input_facultyID){
        $data = [];
        $query = "
        SELECT se.section_ID FROM Section se
        INNER JOIN SectionHandle seh ON se.section_ID = seh.section_ID
        INNER JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
        WHERE suh.subHandle_ID = (SELECT subHandle_ID FROM SubjectHandle WHERE faculty_ID = ?);
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_facultyID);
            $stmt->execute(); $stmt->bind_result($section_ID);

            while($stmt->fetch()){
                $data[] = $section_ID;
            }

            $stmt->close();
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }

    public function readHandledSubject($input_facultyID){
        $data = [];
        $query= "
        SELECT suh.subject_ID, su.subjectName FROM Subject su
        INNER JOIN SubjectHandle suh ON su.subject_ID = suh.subject_ID
        INNER JOIN Faculty f ON suh.faculty_ID = f.faculty_ID
        WHERE f.faculty_ID = ?;
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_facultyID);
            $stmt->execute(); $stmt->bind_result($subject_ID, $subjectName);

            while($stmt->fetch()){
                $data = [
                    'subject_ID' => $subject_ID,
                    'subjectName' => $subjectName
                ];
            }

            $stmt->close();
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
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
            $this->logError($e);
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
            $this->logError($e);
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
            $this->logError($e);
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
            $this->logError($e);
        }
    }

    public function readSessionData($input_sessionToken){
        $query = "
        SELECT 
            ac.user_ID AS user_ID, 
            ac.userName AS userName,
            s.student_ID AS student_ID,
            ad.admin_ID AS admin_ID,
            f.faculty_ID AS faculty_ID
        FROM Account ac
        INNER JOIN LoginToken l ON ac.user_ID = l.user_ID
        LEFT JOIN Student s ON s.user_ID = ac.user_ID
        LEFT JOIN Admin ad ON ad.user_ID = ac.user_ID
        LEFT JOIN Faculty f ON f.user_ID = ac.user_ID
        WHERE l.session_token = ?;
        ";
        $data = [];
        
        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_sessionToken);
            $stmt->execute(); $stmt->bind_result($user_ID, $userName, $student_ID, $admin_ID, $faculty_ID); $stmt->fetch();

            $sessionData = [
                "user_ID" => $user_ID,
                "userName" => $userName,
                "student_ID" => $student_ID,
                "admin_ID" => $admin_ID,
                "faculty_ID" => $faculty_ID
            ];

            $stmt->close();
            return $sessionData;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }

    public function readSubjectHandle($input_facultyID){
        $query = "
        SELECT su.subject_ID AS subject_ID, su.subjectName AS subjectName, se.section_ID AS section_ID
        FROM Faculty f
        INNER JOIN SubjectHandle suh ON f.faculty_ID = suh.faculty_ID
        INNER JOIN Subject su ON suh.subject_ID = su.subject_ID
        INNER JOIN SectionHandle seh ON suh.subHandle_ID = seh.subHandle_ID
        INNER JOIN Section se ON seh.section_ID = se.section_ID
        WHERE f.faculty_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_facultyID);
            $stmt->execute(); $stmt->bind_result($subject_ID, $subjectName, $section_ID);

            while($stmt->fetch()){
                $subjectHandleData = [
                    'subject_ID' => $subject_ID,
                    'subjectName' => $subjectName,
                    'section_ID' => $section_ID
                ];

                $data[] = $subjectHandleData;
            }

            $stmt->close();
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
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
        
        try{
            $result = $this->db->query($query);

            while($row = $result->fetch_assoc()){
                $section_ID[] = $row['section_ID'];
            }

            $result->free();

            return $section_ID;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }
    

    // DELETE FUNCTIONS


    public function updatePassword($input_sessionToken, $input_oldPassword, $input_newPassword){
        $sessionData = $this->readSessionData($input_sessionToken);
        $newPassword = password_hash($input_newPassword, PASSWORD_BCRYPT);
        $readPasswordQuery = "
        SELECT password FROM Account WHERE user_id = ?
        ";
        $updatePasswordQuery = "
        UPDATE Account SET password = ? WHERE user_id = ?
        ";

        try{
            $stmt1 = $this->db->prepare($readPasswordQuery); $stmt1->bind_param("i", $sessionData['user_ID']);
            $stmt1->execute(); $stmt1->store_result(); $stmt1->bind_result($oldPassword);
            $stmt1->fetch();

            if(password_verify($input_oldPassword, $oldPassword)){
                $stmt2 = $this->db->prepare($updatePasswordQuery); $stmt2->bind_param("si", $newPassword, $sessionData['user_ID']);
                $stmt2->execute(); $stmt2->close(); $stmt1->close();

                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }


    // DELETE FUNCTIONS


    public function deleteSessionToken($input_sessionToken){
        $query = "
        DELETE FROM LoginToken
        WHERE session_token = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_sessionToken);
            $stmt->execute(); $stmt->close();
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }
    
    public function deleteSubHandle($input_facultyID, $input_sectionID){
        $query = "
        DELETE FROM SectionHandle 
        WHERE subHandle_ID = (SELECT subHandle_ID FROM SubjectHandle WHERE faculty_ID = ?) AND section_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("ss", $input_facultyID, $input_sectionID);
            $stmt->execute(); $stmt->close();
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }
}
?>