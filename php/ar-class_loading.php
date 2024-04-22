<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $sessionData = $Model->readSessionData($_COOKIE['session_token']);

    $dropdownData = [
        'handledSubject' => $Model->readHandledSubject($sessionData['faculty_ID']),
        'handledSection' => $Model->readHandledSection($sessionData['faculty_ID']),
        'unhandledSection' => $Model->readUnhandledSection()
    ];

    echo json_encode($dropdownData);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>