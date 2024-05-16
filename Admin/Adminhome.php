<?php
include 'connection.php';

// Function to get passed/failed statistics
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

// Function to get highest and lowest score statistics
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

// Function to get the lowest answered question per questionnaire
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
        // Group by questionSet_ID
        $questions[$row['questionSet_ID']][] = $row;
    }

    // Extract the lowest unanswered question for each questionnaire
    $lowestAnsweredQuestions = [];
    foreach ($questions as $questionSet_ID => $questionGroup) {
        $lowestAnsweredQuestions[] = $questionGroup[0];  // The first entry is the one with the least answers due to ordering
    }
    return $lowestAnsweredQuestions;
}

$passedFailedStats = getPassedFailedStats($conn);
$scoreStats = getScoreStats($conn);
$lowestAnsweredQuestions = getLowestAnsweredQuestions($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <img src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="container" style="margin-left: 300px;">
        <div class="row">
            <div class="col-md-4">

                <div class="form-section">
                    <h2>Number of Passed/Failed</h2>
                    <?php foreach ($passedFailedStats as $stats): ?>
                        <p>Assessment Title: <?= $stats['questionSetTitle']; ?></p>
                        <p>Academic Year: <?= $stats['acadYear']; ?></p>
                        <p>Academic Term: <?= $stats['acadTerm']; ?></p>
                        <p>Academic Semester: <?= $stats['acadSem']; ?></p>
                        <p>Passed: <?= $stats['passed_count']; ?></p>
                        <p>Failed: <?= $stats['failed_count']; ?></p>
                        <hr>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-section">
                    <h2>Highest and Lowest Score</h2>
                    <?php foreach ($scoreStats as $stats): ?>
                        <p>Assessment Title: <?= $stats['questionSetTitle']; ?></p>
                        <p>Highest Score: <?= $stats['highest_score']; ?></p>
                        <p>Lowest Score: <?= $stats['lowest_score']; ?></p>
                        <hr>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-section">
                    <h2>Lowest Answered Question</h2>
                    <?php foreach ($lowestAnsweredQuestions as $question): ?>
                        <p>Assessment Title: <?= $question['questionSetTitle']; ?></p>
                        <p>Lowest Unanswered Count: <?= $question['unanswered_count']; ?></p>
                        <p>Lowest Question Text: <?= $question['lowest_question_text']; ?></p>
                        <hr>
                    <?php endforeach; ?>
                </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
