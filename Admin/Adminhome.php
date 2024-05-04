<?php
include 'connection.php';   

$query = "SELECT isCorrect, COUNT(*) as count FROM AnswerStatistic GROUP BY isCorrect";
$result = $conn->query($query);

$correct = 0;
$incorrect = 0;

while($row = $result->fetch_assoc()) {
    if ($row['isCorrect']) {
        $correct = $row['count'];
    } else {
        $incorrect = $row['count'];
    }
}

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
               
            </div>

            <div class="col-md-4">
       
            </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>