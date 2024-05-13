<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();

    // Verifying JSON Integrity
    if(isset($_POST['attendanceData'])) {
        $attendanceData = json_decode($_POST['attendanceData'], true);
    }
    else{
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }

    foreach($attendanceData as $student){
        $Model->updateSetDisallow($student['student_ID'], $_POST['questionSet_ID'], $student['isDisallowed']);
    }

    echo json_encode([
        'processed' => true
    ]);
            
    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>