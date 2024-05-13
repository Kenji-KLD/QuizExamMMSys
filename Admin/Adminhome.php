<?php
include 'connection.php';

// Function to execute SQL queries
function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result;
}

// Function to calculate number of passed and failed students per subject term
function calculatePassedFailed($subjectID, $term) {
    $sql = "SELECT SUM(IF(score >= 50, 1, 0)) AS passed_count,
                   SUM(IF(score < 50, 1, 0)) AS failed_count
            FROM Score
            INNER JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            WHERE QuestionSet.subject_ID = '$subjectID' AND QuestionSet.acadTerm = '$term'";

    $result = executeQuery($sql);
    return $result->fetch_assoc();
}

// Function to get highest and lowest scores per subject term
function getHighestLowestScores($subjectID, $term) {
    $sql = "SELECT MAX(score) AS highest_score, MIN(score) AS lowest_score
            FROM Score
            INNER JOIN QuestionSet ON Score.questionSet_ID = QuestionSet.questionSet_ID
            WHERE QuestionSet.subject_ID = '$subjectID' AND QuestionSet.acadTerm = '$term'";

    $result = executeQuery($sql);
    return $result->fetch_assoc();
}

// Function to get the lowest answered question and its details per subject term
// Function to get the lowest answered question and its text per subject term
function getLowestAnsweredQuestion($subjectID, $term) {
    global $conn;

    $sql = "SELECT MIN(qs.questionTotal - IFNULL((SELECT COUNT(*) FROM AnswerStatistic AS as1 WHERE as1.question_ID = qb.question_ID), 0)) AS lowest_unanswered,
                   qb.questionText AS lowest_question_text
            FROM QuestionSet qs
            INNER JOIN QuestionBank qb ON qs.questionSet_ID = qb.questionSet_ID
            WHERE qs.subject_ID = '$subjectID' AND qs.acadTerm = '$term'
            GROUP BY qs.questionSet_ID
            ORDER BY lowest_unanswered ASC
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); // Return the lowest answered question details
    } else {
        return null; // Return null if no data found
    }
}


// Example usage (replace 'your_subject_id' and 'your_academic_term' with actual values)
$subjectID = 'your_subject_id';
$term = 'your_academic_term';

// Retrieve statistics
$passedFailedStats = calculatePassedFailed($subjectID, $term);
$scoreStats = getHighestLowestScores($subjectID, $term);
$lowestAnsweredQuestion = getLowestAnsweredQuestion($subjectID, $term);
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
            <div class="col-md-8">
                <div class="form-section">
                    <h2>Number of Passed/Failed</h2>
                    <p>Passed: <?php echo $passedFailedStats['passed_count']; ?></p>
                    <p>Failed: <?php echo $passedFailedStats['failed_count']; ?></p>
                </div>

                <div class="form-section">
                    <h2>Highest and Lowest Score</h2>
                    <p>Highest Score: <?php echo $scoreStats['highest_score']; ?></p>
                    <p>Lowest Score: <?php echo $scoreStats['lowest_score']; ?></p>
                </div>

                <div class="form-section">
                    <h2>Lowest Answered Question</h2>
                    <?php
                    if ($lowestAnsweredQuestion) {
                        echo "<p>Lowest Unanswered Count: " . $lowestAnsweredQuestion['lowest_unanswered'] . "</p>";
                        echo "<p>Lowest Question Text: " . $lowestAnsweredQuestion['lowest_question_text'] . "</p>";
                    } else {
                        echo "<p>No data found for the lowest answered question.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Additional content or sidebar -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
