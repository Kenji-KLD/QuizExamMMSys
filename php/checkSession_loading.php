<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    // Initializing DB Model
    $Model = new Model();
    
    if(!isset($_COOKIE['session_token'])){
        echo json_encode([
            'processed' => false,
            'redirect_url' => $_POST['isLogin'] == true ? '' : '/dist/login.html'
        ]);
    }
    elseif($Model->readSessionData($_COOKIE['session_token']) == false){
        echo json_encode([
            'processed' => false, 
            'redirect_url' => $_POST['isLogin'] == true ? '' : '/dist/login.html'
        ]);
    }
    elseif($Model->readSessionData($_COOKIE['session_token']) != false){
        $sessionData = $Model->readSessionData($_COOKIE['session_token']);
        $sessionData['accountType'] = $Model->readAccountType($sessionData['userName']);
        $sessionData['processed'] = true;
        echo json_encode($sessionData);
    }

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>