<?php
 include 'connection.php';  
 include 'AdminCrud.php'; 
session_start(); // Start session to use session variables

// Check if $_SESSION['notif'] is set and display it
$notif = isset($_SESSION['notif']) ? $_SESSION['notif'] : "";

// Unset the session variable to clear the message after displaying
unset($_SESSION['notif']);

// Check if $_SESSION['notif'] is set and display it
$notif1 = isset($_SESSION['notif1']) ? $_SESSION['notif1'] : "";

// Unset the session variable to clear the message after displaying
unset($_SESSION['notif1']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
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
            width: 30%; 
            padding-right: 20px; /* Added padding for spacing */
        }

        .table-container {
            width: 70%; 
        }
        .form-wrapper {
            background-color: #212121;
            padding: 20px;
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
            width: 90%; /* Adjust based on your design requirements */
            max-width: 800px; /* Maximum width of the table display */
            background-color: #212121; /* Light grey background */
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2); /* Subtle shadow for depth */
            overflow-y: auto; /* Enables vertical scrolling */
            max-height: 90vh; /* Maximum height of the form */
            border-radius: 8px; /* Rounded corners for aesthetics */
            margin-left: 1px;
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
            case 'subjectID':
                regex = /[!#$@%^&*()_+\-=.\[\]{};,':"\\|<>\/?]+/;
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
        return true; // Allow form submission
    }
</script>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="form-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Subject Form</h2>
                        <?php echo $notif; ?><br>
                        <br><form action="3AdminSubjectTrigger.php" method="post">
                            <label for="subjectID">Subject ID:</label>
                            <input type="text" id="subjectID" name="subjectID" placeholder="CCIS1101" required>

                            <label for="subjectName">Subject Name:</label>
                            <input type="text" id="subjectName" name="subjectName" placeholder="Computer Programming 1" required>

                            <label for="unitsAmount">Units Amount:</label>
                            <input type="number" id="unitsAmount" name="unitsAmount" required min="0" required>

                            <label for="subjectType">Subject Type:</label>
                            <input type="text" id="subjectType" name="subjectType" placeholder="Lec/Lab" required>
                            
                            <input type="submit" class = 'submit' name = "add" value="Submit">
                        </form>
                    </div>
                </CENTER>
            </div>
            <div class="form-container">
            <CENTER>
                <div class="form-wrapper">
                    <h2>Assign Subjects</h2>
                    <?php echo $notif1 ?><br>
                    <br><form action="3AdminSubjectTrigger.php" method="post">
                    <label for="faculty_ID">Choose a faculty member:</label>
                    <select name="faculty_ID" id="faculty_ID">
                    <option disabled selected>Select Faculty</option>
                
                    <?php
                    try {
                    $facultyMembers = fetchFacultyList($conn);
                    foreach ($facultyMembers as $member) {
                    echo "<option value='{$member['faculty_ID']}'>" . htmlspecialchars($member['fullName']) . "</option>";
                    }
                    } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                    </select><br>

                    <br><label for="subjectID">Subject:</label>
                    <select name="subjectID" id="subjectID">
                    <option disabled selected>Select Subject</option>
                    <?php
                    $query = "SELECT subject_ID, subjectName FROM Subject";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                    $subjectID = $row['subject_ID'];
                    $subjectName = $row['subjectName'];
                    echo "<option value=\"$subjectID\">$subjectName</option>";
                    }
                    } 
                    // else {
                    //     echo "<option value=\"N/A\">No subjects available</option>";
                    // }
                    ?>
                        </select><br>

                        <br><input type="submit" class = 'submit' name = "assign" value="Assign">
                    </form>
                </div>
            </CENTER>
        </div>
            <div class="table-container1">
                <CENTER>
                    <div class="form-wrapper1">
                        <h2>Subject List</h2>
                        <?php


$subjectData = fetchSubjectData($conn);

if (!empty($subjectData)) {
    echo "<table>";
    echo "<tr><th><CENTER>Subject ID</CENTER></th><th><CENTER>Subject Name</CENTER></th><th><CENTER>Units Amount</CENTER></th><th><CENTER>Subject Type</CENTER></th><th><CENTER>Action</CENTER></th></tr>";

    foreach ($subjectData as $subject) {
        echo "<tr>";
        echo "<td>{$subject['subject_ID']}</td>";
        echo "<td>{$subject['subjectName']}</td>";
        echo "<td>{$subject['unitsAmount']}</td>";
        echo "<td>{$subject['subjectType']}</td>";

        echo "<td>";
        echo "<form method='post' action='3AdminEditView.php'>";
        echo "<input type='hidden' name='subject_ID' value='{$subject['subject_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' class = 'edit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='3AdminSubjectTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='subject_ID' value='{$subject['subject_ID']}'>"; // Access student_ID safely
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
