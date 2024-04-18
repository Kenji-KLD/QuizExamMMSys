<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Dark background */
            color: #E0E0E0; /* Light text */
            padding: 20px;
            margin: 0;
        }
        form {
            background-color: #333333; /* Darker element background */
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
        h2 {
            color: #BB86FC; /* Light purple similar to ChatGPT highlights */
        }
        input[type=number], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=text], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=email], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=password], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }
        /* input[type=submit] {
            width: 100%;
            background-color: #BB86FC; 
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        } */


        /* input[type=submit]:hover {
            background-color: #3700B3; Darker purple on hover
        } */
    </style>
</head>
<body>

<?php
include "connection.php";

if (isset($_POST['edit'])) {
    $subject_ID = $_POST['subject_ID'];

    $sql = "SELECT subject_ID, subjectName, unitsAmount, subjectType  FROM Subject WHERE subject_ID = '{$subject_ID}'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 

        echo "<h2>Edit Subject Details</h2>";
        echo "<form method='post' action='3AdminSubjectTrigger.php'>";
        echo "Subject ID: <input type='text' name='subject_ID' value='{$row['subject_ID']}' readonly><br>";
        echo "Subject Name: <input type='text' name='subjectName' value='{$row['subjectName']}'><br>";
        echo "Units Amount: <input type='number' name='unitsAmount' value='{$row['unitsAmount']}'><br>";
        echo "Subject Type: <input type='text' name='subjectType' value='{$row['subjectType']}'><br>";
        
        echo "<input type='submit' name='edit' value='Save Changes'>";
        echo "</form>";
    } else {
        echo "<p>No record found for the provided user ID.</p>";
    }
} else {
    echo "<p>User ID not provided.</p>";
}

$conn->close();
?>

</body>

</html>
