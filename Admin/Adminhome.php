<?php
include "connection.php"; // Include your database connection

// Prepare SQL query to count correct and incorrect answers
$sql = "SELECT isCorrect, COUNT(*) AS count FROM AnswerStatistic GROUP BY isCorrect";

// Execute the query and fetch results
$result = $conn->query($sql);

// Initialize an array to store data for the pie chart
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[$row['isCorrect']] = $row['count'];
}

// Close the database connection
$conn->close();
?>
<?php
// Prepare data for Chart.js
$labels = array_keys($data); // Labels for the pie chart (e.g., "0" for incorrect, "1" for correct)
$counts = array_values($data); // Corresponding counts (e.g., count of incorrect answers, count of correct answers)

// Convert data to JSON format for JavaScript
$chartData = json_encode(array(
    'labels' => $labels,
    'counts' => $counts
));
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Quiz and Exam Maker Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #383838;
            color: white;
        }
        
        .container {
            display: flex;
            height: 100vh;
        }
        
        
        .content {
            flex: 1;
            padding: 20px;
        }

        #navbarToggle {
            position: absolute;
            top: 20px;
            left: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php';?> <!-- Include the sidebar -->
        <div class="content">
            <h1><CENTER>Welcome to Quiz and Exam Maker Management System</CENTER></h1>
              <h2>Answer Statistics</h2>
              <h2>Pie Chart of Answer Statistics</h2>
    <canvas id="myPieChart" width="400" height="400"></canvas>

    <script>
        // Parse PHP data into JavaScript
        var chartData = <?php echo $chartData; ?>;

        // Get context of the canvas element
        var ctx = document.getElementById('myPieChart').getContext('2d');

        // Create a new pie chart instance
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Answer Statistics',
                    data: chartData.counts,
                    backgroundColor: ['red', 'green'], // Customize colors here
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // Add other chart options as needed
            }
        });
    </script>
             
        </div>
    </div>

    
</body>

</html>
