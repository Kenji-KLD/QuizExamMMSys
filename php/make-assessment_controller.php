<?php
require_once 'model.php';

function filter_empty_arrays($array) {
    // Check if the array is not empty
    return !empty($array);
}

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && $_SERVER["REQUEST_METHOD"] === "POST"){
    $Model = new Model();

    // Verifying JSON Integrity
    if(isset($_POST['questionSetData'])) {
        $questionSetData = json_decode($_POST['questionSetData'], true);
    }
    else{
        echo json_encode([
            'processed' => false, 
            'error_message' => 'JSON Decoding Fail'
        ]);
    }

    $csvFile = $_FILES['questionData']['tmp_name'];

    // Example: Parse CSV data
    $rows = array_map('str_getcsv', explode("\n", file_get_contents($csvFile)));

    // Prepare arrays and variables
    $questionTotal = 0;
    $questionData = [];

    // Flag to skip the first row (header)
    $skipFirstRow = true;

    foreach ($rows as $row) {
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }

        // Skip the first row (header)
        if ($skipFirstRow) {
            $skipFirstRow = false;
            continue;
        }

        // Extract required fields
        $questionNumber = (int)$row[0];
        $questionText = $row[1];
        $questionFormat = $row[2];
        $choices = array_filter(array_slice($row, 3, 5), 'filter_empty_arrays'); // Extract choices (columns 4 to 8)
        $correctAnswer = $row[8];
        $pointsGiven = (int)$row[9];

        // Prepare JSON formatted data for each row
        $questionData[] = [
            'questionFormat' => $questionFormat,
            'questionNumber' => $questionNumber,
            'questionText' => $questionText,
            'questionAnswer' => $correctAnswer,
            'pointsGiven' => $pointsGiven,
            'choices' => $choices
        ];
    }

    // Generates questionTotal
    foreach($questionData as $question) {
        $questionTotal++;
    }

    $questionSet_ID = $Model->createQuestionSet(
        $_POST['secHandle_ID'],
        $questionSetData['questionSetTitle'],
        $questionSetData['questionSetType'],
        $questionTotal,
        $questionSetData['randomCount'],
        $questionSetData['deadline'],
        $questionSetData['timeLimit'],
        $questionSetData['acadYear'],
        $questionSetData['acadTerm'],
        $questionSetData['acadSem']
    );

    foreach($questionData as $question) {
        $question_ID = $Model->createQuestion(
            $questionSet_ID, 
            $question['questionFormat'],
            $question['questionNumber'],
            $question['questionText'],
            $question['questionAnswer'],
            $question['pointsGiven'],
        );

        foreach($question['choices'] as $choice){
            $Model->createChoice($question_ID, $choice);
        }
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