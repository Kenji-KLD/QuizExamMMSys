<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $processed = $Model->updatePassword($_COOKIE['session_token'], $_POST['oldPass'], $_POST['newPass']);

    echo json_encode([
        'processed' => $processed,
        'error_message' => $processed == true ? '' : 'Old Password Invalid'
    ]);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>