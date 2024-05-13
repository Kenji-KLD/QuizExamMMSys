<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $sessionData = $Model->readSessionData($_COOKIE['session_token']);
    $studentData = $Model->readStudentDetails($sessionData['user_ID']);
    $studentList = $Model->readSectionList($studentData['section']);
    $profName = $Model->readFacultyName($_POST['secHandle_ID']);

    echo json_encode([
        'profName' => $profName,
        'studentList' => $studentList
    ]);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>