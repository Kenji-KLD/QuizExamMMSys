<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    // Initializing DB Model
    $Model = new Model();
    
    // Verifying JSON Integrity
    if(isset($_POST['jsonData'])) {
        $jsonData = json_decode($_POST['jsonData'], true);
    }
    else{
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }

    // Error-handling of Login
    if($jsonData['tac_checkbox'] == false){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'Terms and Condition Not Accepted'
        ]);
    }
    elseif($Model->readPasswordHash($jsonData['userName']) == false){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'Username Does Not Exist'
        ]);
    }
    elseif(password_verify($jsonData['password'], $Model->readPasswordHash($jsonData['userName'])) == false){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'Wrong Password'
        ]);
    }
    elseif(password_verify($jsonData['password'], $Model->readPasswordHash($jsonData['userName'])) == true){
        $Model->createSessionToken($jsonData['userName']);
        $session_token = $Model->readSessionToken($jsonData['userName']);

        setcookie("session_token", $session_token, time() + 86400, "/");
        
        echo json_encode([
            'processed' => true,
            'accountType' => $Model->readAccountType($jsonData['userName']),
        ]);
    }

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>