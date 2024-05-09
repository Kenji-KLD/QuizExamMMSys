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
            fName, mName, lName,
            email, sex, age
        FROM Account
        WHERE user_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_userID);
            $stmt->execute(); $stmt->bind_result($fName, $mName, $lName, $email, $sex, $age);

            while($stmt->fetch()){
                $data = [
                    'fName' => $fName,
                    'mName' => $mName,
                    'lName' => $lName,
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

    public function readAssessmentList($input_secHandleID){
        $query = "
        SELECT 
            qs.questionSet_ID AS questionSet_ID, 
            qs.questionSetTitle AS questionSetTitle, 
            seh.section_ID AS section_ID, 
            qs.deadline AS deadline
        FROM SectionHandle seh
        INNER JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
        INNER JOIN Faculty f ON suh.faculty_ID = f.faculty_ID
        INNER JOIN QuestionSet qs ON f.faculty_ID = qs.faculty_ID
        WHERE seh.secHandle_ID = ?
        ";
        $data = [];

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_secHandleID);
            $stmt->execute(); $stmt->bind_result($questionSet_ID, $questionSetTitle, $section_ID, $deadline);

            while($stmt->fetch()){
                $assessmentData = [
                    'questionSet_ID' => $questionSet_ID,
                    'questionSetTitle' => $questionSetTitle,
                    'section_ID' => $section_ID,
                    'deadlineDate' => substr($deadline, 0, 10),
                    'deadlineTime' => substr($deadline, 11)
                ];

                $data[] = $assessmentData;
            }

            $stmt->close();
            return $data;
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

    public function readQuestionnaire($input_questionSetID){
        $data = [];
        $questions = [];

        $query = "
        SELECT 
            qs.questionSetTitle,
            qs.rubrics,
            qb.question_ID,
            qb.questionNumber,
            qb.questionText,
            cb.choiceLabel
        FROM QuestionSet qs
        JOIN QuestionBank qb ON qs.questionSet_ID = qb.questionSet_ID
        LEFT JOIN ChoiceBank cb ON qb.question_ID = cb.question_ID
        WHERE qs.questionSet_ID = ?;
        ";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $input_questionSetID);
            $stmt->execute();
            $stmt->bind_result($questionSetTitle, $rubrics, $question_ID, $questionNumber, $questionText, $choiceLabel);

            while ($stmt->fetch()) {
                // Check if the question_ID already exists in the questions array
                $key = array_search($question_ID, array_column($questions, 'question_ID'));

                if ($key === false) {
                    // If the question_ID doesn't exist, create a new question array
                    $question = [
                        "question_ID" => $question_ID,
                        "questionNumber" => $questionNumber,
                        "questionText" => $questionText,
                        "choices" => []
                    ];
                    $questions[] = $question;
                    $key = count($questions) - 1; // Get the index of the newly added question
                }

                // Add choiceLabel to the corresponding question's choices array
                if ($choiceLabel !== null) {
                    $questions[$key]['choices'][] = $choiceLabel;
                }
            }

            $stmt->close();

            $data = [
                'questionSetTitle' => $questionSetTitle,
                'rubrics' => $rubrics,
                'questions' => $questions
            ];
        }
        catch(Exception $e){
            $this->logError($e);
        }

        return json_encode($data);
    }

    public function readSecHandleID($input_secHandleID){
        $query = "
        SELECT seh.section_ID AS section_ID, su.subjectName AS subjectName
        FROM SectionHandle seh
        INNER JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
        INNER JOIN Subject su ON suh.subject_ID = su.subject_ID
        WHERE seh.secHandle_ID = ?
        ";
        $secHandleData = [];

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_secHandleID);
            $stmt->execute(); $stmt->bind_result($section_ID, $subjectName); $stmt->fetch();

            $secHandleData = [
                "section_ID" => $section_ID,
                "subjectName" => $subjectName
            ];

            $stmt->close();
            return $secHandleData;
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
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }

    public function readSessionExistence($input_sessionToken){
        $query = "
        SELECT session_token FROM LoginToken 
        WHERE session_token = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("s", $input_sessionToken);
            $stmt->execute(); $stmt->store_result();
            if($stmt->num_rows > 0){
                return true;
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

    public function readStudentDetails($input_userID){
        $query = "
        SELECT s.student_ID AS student_ID, c.section_ID AS section_ID, a.email AS email
        FROM Student s
        INNER JOIN Account a ON s.user_ID = a.user_ID
        INNER JOIN Class c ON s.student_ID = c.student_ID
        WHERE a.user_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_userID);
            $stmt->execute(); $stmt->bind_result($student_ID, $section, $email); $stmt->fetch();

            $data = [
                'student_ID' => $student_ID,
                'section' => $section,
                'email' => $email
            ];

            $stmt->close();
            return $data;
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }

    public function readSubjectHandle($input_facultyID){
        $data = [];
        $query = "
        SELECT seh.secHandle_ID AS subHandle_ID, su.subject_ID AS subject_ID, su.subjectName AS subjectName, se.section_ID AS section_ID
        FROM Faculty f
        INNER JOIN SubjectHandle suh ON f.faculty_ID = suh.faculty_ID
        INNER JOIN Subject su ON suh.subject_ID = su.subject_ID
        INNER JOIN SectionHandle seh ON suh.subHandle_ID = seh.subHandle_ID
        INNER JOIN Section se ON seh.section_ID = se.section_ID
        WHERE f.faculty_ID = ?
        ";

        try{
            $stmt = $this->db->prepare($query); $stmt->bind_param("i", $input_facultyID);
            $stmt->execute(); $stmt->bind_result($secHandle_ID, $subject_ID, $subjectName, $section_ID);

            while($stmt->fetch()){
                $subjectHandleData = [
                    'secHandle_ID' => $secHandle_ID,
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