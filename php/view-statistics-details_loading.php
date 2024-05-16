<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $secHandleData = $Model->readSecHandleID($_POST['secHandle_ID']);
    echo json_encode([
        'statistics' => $Model->readStatistics($_POST['questionSet_ID']),
        'assessmentData' => $Model->readAssessment($_POST['questionSet_ID'])
    ]);


    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>