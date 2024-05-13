<?php
require_once 'model.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    date_default_timezone_set('Asia/Manila');
    $Model = new Model();

    // Verifying JSON Integrity
    if(isset($_POST['answerList'])) {
        $answerList = json_decode($_POST['answerList'], true);
    }
    else{
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }
    
    $sessionData = $Model->readSessionData($_COOKIE['session_token']);
    $answerData = $Model->readQuestionAnswer($_POST['questionSet_ID']);

    foreach($answerList as $studentAnswer){
        $question_ID = $studentAnswer['question_ID'];
        $questionAnswer = $studentAnswer['questionAnswer'];

        // Find corresponding correct answer
        $correctAnswer = array_filter($answerData, function ($answer) use ($question_ID) {
            return $answer['question_ID'] == $question_ID;
        });

        if (!empty($correctAnswer)){
            $isCorrect = ($questionAnswer === reset($correctAnswer)['questionAnswer']) ? 1 : 0;
            $Model->createAnswerStatistic($sessionData['student_ID'], $question_ID, $questionAnswer, $isCorrect);
        }
        else{
            $Model->createAnswerStatistic($sessionData['student_ID'], $question_ID, $questionAnswer, 0);
        }
    }

    $Model->createScore($sessionData['student_ID'], $_POST['questionSet_ID'], date('Y-m-d H:i:s'));

    $Model = null; 
    exit();
}
else{
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>