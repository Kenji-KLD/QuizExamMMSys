<?php
include 'logout_class.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Logout();
    
    $Model->deleteSessionToken($_COOKIE['session_token']);
    setcookie('session_token', '', time() - 3600, '/');
    setcookie('userDetails', '', time() - 3600, '/');

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>