<?php include 'connection.php'; ?>
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
                    <div id="passedFailedStats"></div>
                </div>

                <div class="form-section">
                    <h2>Number of Highest and Lowest Score</h2>
                    <div id="highestLowestStats"></div>
                </div>

                <div class="form-section">
                    <h2>Lowest Answered Question</h2>
                    <div id="lowestAnsweredStats"></div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Additional content or sidebar -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Fetch and display sample statistics (simulating get_statistics.php)
        const sampleData = <?php include 'get_statistics.php'; ?>;

        // Display passed/failed statistics
        const passedFailedStats = document.getElementById('passedFailedStats');
        sampleData.forEach(entry => {
            passedFailedStats.innerHTML += `
                <p>${entry.acadYear} ${entry.acadTerm} - ${entry.subject_ID}: Passed: ${entry.passed_count}, Failed: ${entry.failed_count}</p>
            `;
        });

        // Display highest/lowest score statistics
        const highestLowestStats = document.getElementById('highestLowestStats');
        sampleData.forEach(entry => {
            highestLowestStats.innerHTML += `
                <p>${entry.acadYear} ${entry.acadTerm} - ${entry.subject_ID}: Highest Score: ${entry.highest_score}, Lowest Score: ${entry.lowest_score}</p>
            `;
        });

        // Display lowest answered question statistics
        const lowestAnsweredStats = document.getElementById('lowestAnsweredStats');
        sampleData.forEach(entry => {
            lowestAnsweredStats.innerHTML += `
                <p>${entry.acadYear} ${entry.acadTerm} - ${entry.subject_ID}: Lowest Unanswered: ${entry.lowest_unanswered}</p>
            `;
        });
    </script>
</body>
</html>
