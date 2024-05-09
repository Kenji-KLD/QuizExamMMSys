<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();

    echo json_encode($Model->readAssessmentList($_POST['secHandle_ID']));

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>