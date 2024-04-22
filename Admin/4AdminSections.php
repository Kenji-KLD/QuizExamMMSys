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
    <title>Sections</title>
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
            width: 150%; /* Adjust based on your design requirements */
            max-width: 800px; /* Maximum width of the table display */
            background-color: #212121; /* Light grey background */
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2); /* Subtle shadow for depth */
            overflow-y: auto; /* Enables vertical scrolling */
            max-height: 90vh; /* Maximum height of the form */
            border-radius: 8px; /* Rounded corners for aesthetics */
            margin-left: 50px;
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
            case 'sectionID':
                regex = /[!@#$%^&*(),._+\=\[\]{};':"\\|<>\/?]+/;
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
</script>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="form-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Section Form</h2><br>
                        <?php echo $notif; ?><br>
                        <br><form action="4AdminSectionsTrigger.php" method="post">
                            <label for="sectionID">Section ID:</label>
                            <input type="text" id="sectionID" name="sectionID" required>

                            <label for="courseName">Course Name:</label>
                            <input type="text" id="courseName" name="courseName" required>
                            
                            <input type="submit" class = 'submit' name = "add" value="Submit">
                        </form>
                    </div>
                </CENTER>
            </div>
            <div class="table-container1">
                <CENTER>
                    <div class="form-wrapper1">
                        <h2>Section List</h2>
                        <?php
include "connection.php";
include "AdminCrud.php";

$sectionData = fetchSectiontData($conn);

if (!empty($sectionData)) {
    echo "<table>";
    echo "<tr><th><CENTER>Section ID</CENTER></th><th><CENTER>Section Course</CENTER></th><th><CENTER>Action</CENTER></th></tr>";

    foreach ($sectionData as $section) {
        echo "<tr>";
        echo "<td>{$section['section_ID']}</td>";
        echo "<td>{$section['course']}</td>";
        

        echo "<td>";
        echo "<form method='post' action='4AdminEditView.php'>";
        echo "<input type='hidden' name='section_ID' value='{$section['section_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' class = 'edit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='4AdminSectionsTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='section_ID' value='{$section['section_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' class = 'delete'  name='delete' value='Delete'>";
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
