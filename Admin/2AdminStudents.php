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
        .form-wrapper input,
        .form-wrapper select {
            display: block;
            margin-bottom: 10px; /* Adjusted margin */
        }

        .form-wrapper select {
            margin-bottom: 20px; /* Larger margin for select element */
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
                        <h2>Student Form</h2>
                        <form action="2AdminStudentTrigger.php" method="post">
                            <label for="student_ID">student ID:</label>
                            <input type="text" id="student_ID" name="student_ID" required>

                            <label for="fName">First Name:</label>
                            <input type="text" id="fName" name="fName" required>

                            <label for="mName">Middle Name:</label>
                            <input type="text" id="mName" name="mName">

                            <label for="lName">Last Name:</label>
                            <input type="text" id="lName" name="lName" required>

                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="age">age:</label>
                            <input type="number" id="age" name="age" required>

                            <label for="address">address:</label>
                            <input type="text" id="address" name="address" required>

                            <label for="gender">Gender:</label>
                            <select name="gender" id="gender">
                            <option value="MALE"> Male </option>  
                            <option value=" FEMALE "> FEMALE </option>  
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
                            <input type="submit" name="add" value="Submit">
                        </form>
                    </div>
                </CENTER>
            </div>
            <div class="table-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Student List</h2>
                        <?php
include "connection.php";
include "AdminCrud.php";

// Fetch student data using the reusable function
$studentData = fetchStudentData($conn);

// Display the retrieved data
if (!empty($studentData)) {
    echo "<table>";
    echo "<tr><th> ID </th><th> Name </th><th>Email</th><th>Section</th><th> Age </th><th> Gender </th><th> Address </th><th>Action</th></tr>";

    foreach ($studentData as $student) {
        echo "<tr>";
        echo "<td>{$student['student_ID']}</td>";
        echo "<td>{$student['fName']} {$student['mName']} {$student['lName']}</td>";
        echo "<td>{$student['email']}</td>";
        echo "<td>{$student['sectionInfo']['section_ID']}</td>";
        echo "<td>{$student['age']}</td>";
        echo "<td>{$student['gender']}</td>";
        echo "<td>{$student['address']}</td>";
    
        echo "<td>";
        echo "<form method='post' action='2AdminEditView.php'>";
        echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
        echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}'>"; // Access student_ID safely
        echo "<input type='submit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='2AdminStudentTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
        echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
        echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}'>"; // Access student_ID safely
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
