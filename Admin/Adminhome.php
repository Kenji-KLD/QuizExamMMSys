<?php
include 'connection.php ';
include 'get_statistics.php';


$subjectID = 'your_subject_id';
$term = 'your_academic_term';

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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
