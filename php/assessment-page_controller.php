<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();
    
    $questionData = json_decode(file_get_contents('php://input'), true);
    
    if($questionData == null){
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }

    $Model->updateQuestion($questionData['question_ID'], $questionData['questionText'], $questionData['questionAnswer']);
    foreach($questionData['choice'] as $choice){
        $Model->updateChoice($choice['choice_ID'], $choice['choiceLabel']);
    }

    echo json_encode([
        'processed' => $questionData
    ]);

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>