<?php
require_once 'model.php';
require_once 'functions.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();

    // Verifying JSON Integrity
    if(isset($_POST['student_ID'])) {
        $studentData = json_decode($_POST['student_ID'], true);
    }
    else{
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }

    if(empty($studentData['student_ID'])){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'ERROR: No Student Selected'
        ]);
    }
    else if($_POST['dataFlag'] == 'addStudent'){
        if(!filterID($studentData['student_ID'])){
            echo json_encode([
                'processed' => false, 
                'error_message' => 'ERROR: Student ID Format Mistype'
            ]);
        }
        else{
            $Model->createClass($studentData['student_ID'], $_POST['secHandle_ID']);

            echo json_encode([
                'processed' => true
            ]);
        }
    }
    else if($_POST['dataFlag'] == 'removeStudent'){
        foreach($studentData['student_ID'] as $student){
            $Model->deleteClass($student, $_POST['secHandle_ID']);
        }

        echo json_encode([
            'processed' => true
        ]);
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