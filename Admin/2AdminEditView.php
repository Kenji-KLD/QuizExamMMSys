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
    $user_ID = $_POST['user_ID'];

    $sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.age, A.gender, A.address, S.student_ID, C.section_ID
            FROM Account A
            LEFT JOIN Student S ON A.user_ID = S.user_ID
            LEFT JOIN Class C ON S.student_ID = C.student_ID
            WHERE A.user_ID = {$user_ID}";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 

        echo "<h2>Edit Student Details</h2>";
        echo "<form method='post' action='2AdminStudentTrigger.php'>";
        echo "<input type='hidden' name='user_ID' value='{$row['user_ID']}'>";
        echo "Student ID: <input type='text' name='student_ID' value='{$row['student_ID']}' readonly><br>";
        echo "Username: <input type='text' name='username' value='{$row['userName']}'><br>";
        echo "First Name: <input type='text' name='fName' value='{$row['fName']}'><br>";
        echo "Middle Name: <input type='text' name='mName' value='{$row['mName']}'><br>";
        echo "Last Name: <input type='text' name='lName' value='{$row['lName']}'><br>";
        echo "Email: <input type='email' name='email' value='{$row['email']}'><br>";
        echo "age: <input type='number' name='age' value='{$row['age']}'><br>";
        echo "Address: <input type='text' name='address' value='{$row['address']}'><br>";


        echo "Gender:<br> <select id='gender' name='gender'>";
        // $stmt = "SELECT gender FROM Account WHERE user_ID = ?";
        // $stmtResult = mysqli_query($conn, $stmt);

        // if(mysqli_num_rows($stmtResult) > 0){
        //     $stmtRow = mysqli_fetch_row($stmtResult);
        //     $gender = $stmtRow['gender'];
        //     $selected = ($gender == $row['gender']) ? "selected"  :  "";
        // }
        echo "<option value = 'MALE' > Male </option>";
        echo  "<option value = 'FEMALE' > Female </option>";
        echo "</select><br>";

        echo "New Password (leave blank to keep current): <input type='password' name='password'><br>";

        echo "Section Assigned: ";
        echo "<select id='section_ID' name='section_ID' readonly>";
        $query = "SELECT section_ID, course FROM Section";
        $sectionResult = mysqli_query($conn, $query);

        if (mysqli_num_rows($sectionResult) > 0) {
            while ($sectionRow = mysqli_fetch_assoc($sectionResult)) {
                $sectionID = $sectionRow['section_ID'];
                $course = $sectionRow['course'];
                $selected = ($sectionID == $row['section_ID']) ? "selected" : "";
                echo "<option value='$sectionID' $selected>$sectionID</option>";
            }
        }
        echo "</select><br>";

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
