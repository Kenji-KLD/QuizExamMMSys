<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $sessionData = $Model->readSessionData($_COOKIE['session_token']);

    echo json_encode([
        'leaderboard' => $Model->readLeaderboard($_POST['secHandle_ID']),
        'student_ID' => $Model->readStudentDetails($sessionData['user_ID'])['student_ID'],
        'secHandleData' => $Model->readSecHandleID($_POST['secHandle_ID'])
    ]);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>