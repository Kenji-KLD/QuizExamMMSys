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
                        <h2>Section Form</h2>
                        <form action="4AdminSectionsTrigger.php" method="post">
                            <label for="sectionID">Section ID:</label>
                            <input type="text" id="sectionID" name="sectionID" required>

                            <label for="courseName">Course Name:</label>
                            <input type="text" id="courseName" name="courseName" required>
                            
                            <input type="submit" name = "add" value="Submit">
                        </form>
                    </div>
                </CENTER>
            </div>
            <div class="table-container">
                <CENTER>
                    <div class="form-wrapper">
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
        echo "<input type='submit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='4AdminSectionsTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='section_ID' value='{$section['section_ID']}'>"; // Access student_ID safely
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
