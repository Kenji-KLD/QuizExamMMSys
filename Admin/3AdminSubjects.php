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
                            <input type="text" id="subjectID" name="subjectID" required>

                            <label for="subjectName">Subject Name:</label>
                            <input type="text" id="subjectName" name="subjectName" required>

                            <label for="unitsAmount">Units Amount:</label>
                            <input type="number" id="unitsAmount" name="unitsAmount" required>

                            <label for="subjectType">Subject Type:</label>
                            <input type="text" id="subjectType" name="subjectType" required>
                            
                            <input type="submit" name = "add" value="Submit">
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
