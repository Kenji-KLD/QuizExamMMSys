<?php
include "logout_class.php";

$Model = new logout();
    $Model->deleteSessionToken($_COOKIE['session_token']);
    setcookie('session_token', '', time() - 3600, '/');
    setcookie('userDetails', '', time() - 3600, '/');
    $Model = null; 
    exit();

?>