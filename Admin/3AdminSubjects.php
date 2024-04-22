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

        .form-wrapper label,
        .form-wrapper input {
            display: block;
            margin-bottom: 10px; /* Adjusted margin */
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
                        <form action="3AdminSubjectTrigger.php" method="post">
                            <label for="subjectID">Subject ID:</label>
                            <input type="text" id="subjectID" name="subjectID" placeholder="CCIS1101" required>

                            <label for="subjectName">Subject Name:</label>
                            <input type="text" id="subjectName" name="subjectName" placeholder="Computer Programming 1" required>

                            <label for="unitsAmount">Units Amount:</label>
                            <input type="number" id="unitsAmount" name="unitsAmount" required min="0" required>

                            <label for="subjectType">Subject Type:</label>
                            <input type="text" id="subjectType" name="subjectType" placeholder="Lec/Lab" required>
                            
                            <input type="submit" name = "add" value="Submit">
                        </form>
                    </div>
                </CENTER>
            </div>
            <div class="form-container">
            <CENTER>
                <div class="form-wrapper">
                    <h2>Assign Subjects</h2>
                    <form action="assign_subjects_trigger.php" method="post">
                        <label for="professor">Professor:</label>
                        <select name="professor" id="professor">
                            <!-- Options for professors -->
                        </select>

                        <label for="subject">Subject:</label>
                        <select name="subject" id="subject">
                            <!-- Options for subjects -->
                        </select>

                        <input type="submit" value="Assign">
                    </form>
                </div>
            </CENTER>
        </div>
            <div class="table-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Subject List</h2>
                        <?php
include "connection.php";
include "AdminCrud.php";

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
        echo "<input type='submit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='3AdminSubjectTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='subject_ID' value='{$subject['subject_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' name='delete' value='Delete'>";
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
