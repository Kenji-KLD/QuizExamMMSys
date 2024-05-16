
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

    <?php include "get_statistics.php" ?>

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
