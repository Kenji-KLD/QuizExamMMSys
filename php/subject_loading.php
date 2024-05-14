<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $sessionData = $Model->readSessionData($_COOKIE['session_token']);

    echo json_encode([
        'secHandleData' => $Model->readSecHandleID($_POST['secHandle_ID']),
        'assessmentAllowed' => $Model->readAssessmentAllowed($sessionData['student_ID']),
        'assessmentScored' => $Model->readAssessmentScored($sessionData['student_ID'])
    ]);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>