<?php

include 'connection.php'; // Ensure your database connection is included

function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result;
}

function calculatePassedFailed($subjectID, $term) {
    global $conn;

    $sql = "SELECT SUM(IF(score >= 50, 1, 0)) AS passed_count,
                   SUM(IF(score < 50, 1, 0)) AS failed_count
            FROM Score
            INNER JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            INNER JOIN SectionHandle ON QuestionSet.secHandle_ID = SectionHandle.secHandle_ID
            INNER JOIN SubjectHandle ON SectionHandle.subHandle_ID = SubjectHandle.subHandle_ID
            WHERE SubjectHandle.subject_ID = '$subjectID' AND QuestionSet.acadTerm = '$term'";

    $result = executeQuery($sql);
    return $result->fetch_assoc();
}

function getHighestLowestScores($subjectID, $term) {
    global $conn;

    $sql = "SELECT MAX(score) AS highest_score, MIN(score) AS lowest_score
            FROM Score
            INNER JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            INNER JOIN SectionHandle ON QuestionSet.secHandle_ID = SectionHandle.secHandle_ID
            INNER JOIN SubjectHandle ON SectionHandle.subHandle_ID = SubjectHandle.subHandle_ID
            WHERE SubjectHandle.subject_ID = '$subjectID' AND QuestionSet.acadTerm = '$term'";

    $result = executeQuery($sql);
    return $result->fetch_assoc();
}

function getLowestAnsweredQuestion($subjectID, $term) {
    global $conn;

    $sql = "SELECT MIN(qs.questionTotal - IFNULL((SELECT COUNT(*) FROM AnswerStatistic AS as1 WHERE as1.question_ID = qb.question_ID), 0)) AS lowest_unanswered,
                   qb.questionText AS lowest_question_text
            FROM QuestionSet qs
            INNER JOIN QuestionBank qb ON qs.questionSet_ID = qb.questionSet_ID
            INNER JOIN SectionHandle ON qs.secHandle_ID = SectionHandle.secHandle_ID
            INNER JOIN SubjectHandle ON SectionHandle.subHandle_ID = SubjectHandle.subHandle_ID
            WHERE SubjectHandle.subject_ID = '$subjectID' AND qs.acadTerm = '$term'
            GROUP BY qs.questionSet_ID
            ORDER BY lowest_unanswered ASC
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); 
    } else {
        return null; 
    }
}

?>
