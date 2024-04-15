<?php
require_once 'db_connect.php';

class Model{
    private $db;

    public function __construct(){
        $this->db = new Connection();
    }

    public function fetchQuestionnaire($input_questionnaireID){
        $data = [];
        $query = "
        SELECT questionNumber, questionText, questionChoices, questionAnswer 
        FROM QuestionBank
        WHERE questionnaire_ID = ?
        ";
        
        $stmt = $db->prepare($query); $db->bind_param("i", $input_questionnaireID);
        $stmt->execute(); $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        
        $result->free();
        $stmt->close();
        
        return json_encode($data);
    }

    public function fetchSectionList($input_section){
        $query = "
        SELECT a.fName AS fName, a.mName AS mName, a.lName AS lName, s.student_ID AS student_ID
        FROM Account a
        JOIN Student s ON a.user_ID = s.user_ID
        JOIN Classroom c ON s.student_ID = c.student_ID
        JOIN Section se ON c.section_ID = se.section_ID
        WHERE se.section_ID = ?;
        ";

        $stmt = $db->prepare($query); $db->bind_param("s", $input_section);
        $stmt->execute(); $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        $result->free();
        $stmt->close();
        
        return json_encode($data);
    }
}
?>