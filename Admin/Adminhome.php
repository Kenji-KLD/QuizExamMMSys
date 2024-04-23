<!-- <?php 

include "connection.php";
$sql = "SELECT isCorrect, COUNT(*) as count FROM AnswerStatistic GROUP BY isCorrect";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['isCorrect'] ? 'Correct' : 'Incorrect';
    $data[$key] = $row['count'];
}

$conn->close();

// Encode data to pass to JavaScript
$chartData = json_encode($data);

?> -->

<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('myDonutChart').getContext('2d');
    var data = <?php echo $chartData; ?>;
    
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Correct', 'Incorrect'],
            datasets: [{
                label: 'Answer Statistics',
                data: [data.Correct, data.Incorrect],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script> -->

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
        #mostIncorrectList {
        color: darksalmon;
        }
        
        #mostIncorrect {
        color: darksalmon;
        }

        #mostcorrect {
        color: darksalmon;
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
        <?php include 'sidebar.php';?>
        <div class="content">
            
            <h1><CENTER>Welcome to Quiz and Exam Maker Management System</CENTER></h1>
            <canvas id="myDonutChart" width="400" height="400"></canvas>
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('myDonutChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Correct', 'Incorrect'],
                    datasets: [{
                        label: 'Answer Statistics',
                        data: [200, 50],  // Example data
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',  // Correct Answers Color
                            'rgba(255, 99, 132, 0.6)'   // Incorrect Answers Color
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            fontColor: 'white'
                        }
                    }
                }
            });
        });
    </script>
    </div>
    <div class = "content">
    <h2>Commonly Misanswered Questions:</h2>
            <ul id="mostIncorrectList"></ul>
        <script>
    var mostIncorrectQuestions = [
                'What is the difference between a statically typed language and a dynamically typed language?',
                'Can you explain the concept of recursion in programming?',
                'What are the benefits of using version control systems like Git in software development projects?',
                'How does a garbage collector work in programming languages like Java?',
                'What is the purpose of polymorphism in object-oriented programming?',
                'Describe what an API (Application Programming Interface) is and how it is used in modern software development.',
                'What is the difference between SQL and NoSQL databases, and when might you choose one over the other?',


            ];

            // Display most incorrect questions in the list
            var mostIncorrectList = document.getElementById('mostIncorrectList');
            mostIncorrectQuestions.forEach(function(question) {
                var listItem = document.createElement('li');
                listItem.textContent = question;
                mostIncorrectList.appendChild(listItem);
            });
                
            </script>
    </div>
        </div>
    </div>

    
</body>

</html>
