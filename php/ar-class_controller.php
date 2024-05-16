<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
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

    if(isset($jsonData['subject_ID']) && $jsonData['subject_ID'] == ''){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'ERROR: No Subject Selected'
        ]);
    }
    else if($jsonData['section_ID'] == ''){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'ERROR: No Section Selected'
        ]);
    }
    else if($_POST['dataFlag'] == 'createSecHandle'){
        $sessionData = $Model->readSessionData($_COOKIE['session_token']);
        $Model->createSecHandle($sessionData['faculty_ID'], $jsonData['subject_ID'], $jsonData['section_ID']);

        echo json_encode(['processed' => true]);
    }
    elseif ($_POST['dataFlag'] == 'deleteSecHandle') {
        $sessionData = $Model->readSessionData($_COOKIE['session_token']);
        $Model->deleteSecHandle($sessionData['faculty_ID'], $jsonData['section_ID']);

        echo json_encode(['processed' => true]);
    }
    else{
        echo json_encode([
            'processed' => false,
            'error_message' => 'Invalid Data Flag'
        ]);
    }

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>