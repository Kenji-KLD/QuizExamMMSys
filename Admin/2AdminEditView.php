<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Dark background */
            color: #E0E0E0; /* Light text */
            padding: 20px;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center; /* Center content horizontally */
            align-items: flex-start; /* Align items at the start of the cross axis */
            gap: 20px; /* Spacing between sidebar and form */
        }

        .sidebar {
            min-width: 200px; /* Minimum width of the sidebar */
            background-color: #333333;
            padding: 10px;
            border-radius: 8px;
        }

        form {
            flex: 1; /* Take remaining space in the container */
            padding: 10px;
            background-color: #333333; /* Darker element background */
            border-radius: 8px;
        }

        h2 {
            color: #BB86FC; /* Light purple similar to ChatGPT highlights */
            text-align: center; /* Center the heading */
        }

        input[type='text'],
        input[type='email'],
        input[type='number'],
        input[type='password'],
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type='submit'] {
            width: 10%;
            padding: 10px;
            background: linear-gradient(to bottom right, orange, yellow);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    include "connection.php";
    include "sidebar.php";

    if (isset($_POST['edit'])) {
        $user_ID = $_POST['user_ID'];

        $sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.age, A.sex, A.address, S.student_ID, C.section_ID
                FROM Account A
                LEFT JOIN Student S ON A.user_ID = S.user_ID
                LEFT JOIN Class C ON S.student_ID = C.student_ID
                WHERE A.user_ID = {$user_ID}";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 

            echo "<form method='post' action='2AdminStudentTrigger.php'>";
            echo "<input type='hidden' name='user_ID' value='{$row['user_ID']}'>";
            echo "<h2>Edit Student Details</h2>"; // Place the header within the form
            echo "Student ID: <input type='text' name='student_ID' value='{$row['student_ID']}' readonly><br>";
            echo "Username: <input type='text' name='username' value='{$row['userName']}'><br>";
            echo "First Name: <input type='text' name='fName' value='{$row['fName']}'><br>";
            echo "Middle Name: <input type='text' name='mName' value='{$row['mName']}'><br>";
            echo "Last Name: <input type='text' name='lName' value='{$row['lName']}'><br>";
            echo "Email: <input type='email' name='email' value='{$row['email']}'><br>";
            echo "Age: <input type='number' name='age' value='{$row['age']}'><br>";
            echo "Address: <input type='text' name='address' value='{$row['address']}'><br>";
            echo "Gender:<br> <select id='sex' name='sex'>";
            echo "<option value='MALE'>Male</option>";
            echo "<option value='FEMALE'>Female</option>";
            echo "</select><br>";
            echo "New Password (leave blank to keep current): <input type='password' name='password'><br>";
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
</div>

</body>
</html>
