<?php
include 'connection.php';

function getPassedFailedStats($conn) {
    $sql = "SELECT
                Score.questionSet_ID,
                QuestionSet.questionSetTitle,
                QuestionSet.acadYear,
                QuestionSet.acadTerm,
                QuestionSet.acadSem,
                SUM(CASE WHEN Score.passed = 1 THEN 1 ELSE 0 END) AS passed_count,
                SUM(CASE WHEN Score.passed = 0 THEN 1 ELSE 0 END) AS failed_count
            FROM Score
            JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            GROUP BY Score.questionSet_ID, QuestionSet.questionSetTitle, QuestionSet.acadYear, QuestionSet.acadTerm, QuestionSet.acadSem";
    $result = $conn->query($sql);
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = $row;
    }
    return $stats;
}

function getScoreStats($conn) {
    $sql = "SELECT
                Score.questionSet_ID,
                QuestionSet.questionSetTitle,
                MAX(Score.score) AS highest_score,
                MIN(Score.score) AS lowest_score
            FROM Score
            JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            GROUP BY Score.questionSet_ID, QuestionSet.questionSetTitle";
    $result = $conn->query($sql);
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = $row;
    }
    return $stats;
}

function getLowestAnsweredQuestions($conn) {
    $sql = "SELECT
                qb.questionSet_ID,
                qs.questionSetTitle,
                qb.questionText AS lowest_question_text,
                COUNT(aq.question_ID) AS unanswered_count
            FROM QuestionBank qb
            LEFT JOIN AnswerStatistic aq ON qb.question_ID = aq.question_ID
            JOIN QuestionSet qs ON qb.questionSet_ID = qs.questionSet_ID
            GROUP BY qb.questionSet_ID, qs.questionSetTitle, qb.question_ID
            ORDER BY qb.questionSet_ID, unanswered_count ASC";
    $result = $conn->query($sql);
    $questions = [];
    while ($row = $result->fetch_assoc()) {

        $questions[$row['questionSet_ID']][] = $row;
    }

    $lowestAnsweredQuestions = [];
    foreach ($questions as $questionSet_ID => $questionGroup) {
        $lowestAnsweredQuestions[] = $questionGroup[0];  
    }
    return $lowestAnsweredQuestions;
}

$passedFailedStats = getPassedFailedStats($conn);
$scoreStats = getScoreStats($conn);
$lowestAnsweredQuestions = getLowestAnsweredQuestions($conn);
?>