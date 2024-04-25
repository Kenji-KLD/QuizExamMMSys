<?php
session_start(); // Start session to use session variables

// Check if $_SESSION['notif'] is set and display it
$notif = isset($_SESSION['notif']) ? $_SESSION['notif'] : "";

// Unset the session variable to clear the message after displaying
unset($_SESSION['notif']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
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
            display: flex; /* Adjusted to flex display */
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%; /* This ensures that the container takes full height of the viewport or its parent container */
            padding: 20px;
        }

        .table-container {
            display: flex;
            justify-content: center;
            align-items: start;
            height: 100%; /* This ensures that the container takes full height of the viewport or its parent container */
            padding: 20px;
        }
        .form-wrapper {
            width: 100%; /* Adjust based on your design requirements */
            max-width: 800px; /* Maximum width of the table display */
            background-color: #212121; /* Light grey background */
            padding: 50px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2); /* Subtle shadow for depth */
            overflow-y: auto; /* Enables vertical scrolling */
            max-height: 90vh; /* Maximum height of the form */
            border-radius: 8px; /* Rounded corners for aesthetics */
            margin-right: 10px;
        }

        #navbarToggle {
            position: absolute;
            top: 20px;
            left: 20px;
            cursor: pointer;
        }

        .table-container1 {
            display: flex;
            justify-content: center;
            align-items: start;
            height: 100%; /* This ensures that the container takes full height of the viewport or its parent container */
            padding: 20px;
}

        .form-wrapper1 {
            width: 100%; /* Adjust based on your design requirements */
            max-width: 800px; /* Maximum width of the table display */
            background-color: #212121; /* Light grey background */
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2); /* Subtle shadow for depth */
            overflow-y: auto; /* Enables vertical scrolling */
            max-height: 90vh; /* Maximum height of the form */
            border-radius: 8px; /* Rounded corners for aesthetics */
            margin-left: 100px;
}

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #2c2c2c;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }


        th, td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #212121;
        }

        input[class = 'submit' ] ,[class = 'import']{
            width: 100%;
            padding: 10px;
            background: linear-gradient(to bottom right, orange, yellow);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[class='edit'] {
            width: 100%;
            padding: 10px;
            background: linear-gradient(to bottom right, #4e8cff, #0056b3);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input[class = 'delete'] {
            width: 100%;
            padding: 10px;
            background: linear-gradient(to bottom right, #b30000, #ff3333);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        
    </style>
</head>
<body>
<script>
function restrictSpecialChars(input) {
        var fieldName = input.id;
        var regex;
        
        // Define regex based on field name
        switch (fieldName) {
            case 'fName':
            case 'mName':
            case 'lName':
                regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|.<>\/?]+/;
                break;
            case 'password':
            case 'username':
                regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
                break;
            case 'address':
                regex = /[!$%^&*()_+\=\[\]{};:"\\|<>\?]+/;
                break;
            case 'email':
                regex = /[!#$%^&*()_+\-=\[\]{};':"\\|<>\/?]+/;
                break;
            case 'student_ID':
                regex = /[!@#$%^&*()_+\=\[\]{};,':"\\|.<>\/?]+/;
                break;
            default:
                regex = /[!@#$%^&*()_+\=\[\]{};':"\\|<>\/?]+/;
                break;
        }
        
        if (regex.test(input.value)) {
            input.value = input.value.replace(regex, '');
        }
    }

    // Attach the restrictSpecialChars function to the input fields
    document.addEventListener('DOMContentLoaded', function() {
        var inputFields = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]');
        inputFields.forEach(function(input) {
            input.addEventListener('input', function() {
                restrictSpecialChars(this);
            });
        });
    });
    function validatePassword() {
        var passwordInput = document.getElementById('password');
        var password = passwordInput.value;

        // Check if the password meets the criteria
        if (password.length < 8 || /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password) || /\s/.test(password)) {
            alert('Password must be at least 8 characters long and must not contain any spaces.');
            return false; // Prevent form submission
        }
        return confirm("Are you sure you want to ADD this Student?");
    }
</script>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="form-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Student Form</h2>
                        <?php echo $notif; ?>
                        <form action="2AdminStudentTrigger.php" method="post" onsubmit="return validatePassword()">
                            <label for="student_ID">Student ID:</label>
                            <input type="text" id="student_ID" name="student_ID" placeholder="KLD-00-000000" required>

                            <label for="fName">First Name:</label>
                            <input type="text" id="fName" name="fName" required>

                            <label for="mName">Middle Name:</label>
                            <input type="text" id="mName" name="mName">

                            <label for="lName">Last Name:</label>
                            <input type="text" id="lName" name="lName" required>

                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="At least 8 characters" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="name@domain.com" required>

                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" required>

                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" required>

                            <label for="sex">Sex:</label>
                            <select name="sex" id="sex">
                            <option value="MALE"> Male </option>  
                            <option value=" FEMALE "> Female </option>  
                            </select>
                            <!-- <label for="section_ID"> Section </label>
                            <select name="section_ID" id="section_ID">
                    <?php
                    //for drop down list
                     include "connection.php";
                    $query = "SELECT section_ID, course FROM Section WHERE course != 'q1w2e3r4t'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                    $section_ID = $row['section_ID'];
                    
                    echo "<option value=\"$section_ID\">$section_ID</option>";
                    }
                    } 
                    // else {
                    //     echo "<option value=\"N/A\">No section_s available</option>";
                    //  }
                    ?> 
                          </select>  
                             -->

                            <input type="submit" class = 'submit' name="add" value="Submit">

                        </form>
                        <form action="2AdminStudentTrigger.php" method="post" enctype="multipart/form-data">
                        <h3>Or Import CSV Files</h3>
                        <input type="file" id="accounts_file" name="accounts_file" accept=".csv">
                        <br>
                        <input type="submit" class='import' name="import" value="Import">
                        </form>

                    </div>
                </CENTER>
            </div>
            <div class="table-container1">
                <CENTER>
                    <div class="form-wrapper1">
                        <h2>Student List</h2>
                        <?php
include "connection.php";
include "AdminCrud.php";

// Fetch student data using the reusable function
$studentData = fetchStudentData($conn);

// Display the retrieved data
if (!empty($studentData)) {
    echo "<table>";
    echo "<tr><th> ID </th><th> Name </th><th>Email</th><th>Section</th><th> Age </th><th> Sex </th><th> Address </th><th>Action</th></tr>";

    foreach ($studentData as $student) {
        echo "<tr>";
        echo "<td>{$student['student_ID']}</td>";
        echo "<td>{$student['fName']} {$student['mName']} {$student['lName']}</td>";
        echo "<td>{$student['email']}</td>";
        echo "<td>{$student['sectionInfo']['section_ID']}</td>";
        echo "<td>{$student['age']}</td>";
        echo "<td>{$student['sex']}</td>";
        echo "<td>{$student['address']}</td>";
    
        echo "<td>";
        echo "<form method='post' action='2AdminEditView.php'>";
        echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
        echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}' >"; // Access student_ID safely
        echo "<input type='submit' class='edit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='2AdminStudentTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
        echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' class = 'delete' name='delete' value='Delete'>";
        echo "</form>";
        echo "</td>";
    
        echo "</tr>";
    }
    

    echo "</table>";
} else {
    echo "<p>No student records found</p>";
}

$conn->close();
?>


                    </div>
                </CENTER>
            </div>
        </div>
    </div>
</body>
</html>
