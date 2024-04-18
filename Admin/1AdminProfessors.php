<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professors</title>
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
                <h2>Professor Form</h2>
                <form action="1AdminProfessorsTrigger.php" method="post">

                    <label for="fName">First Name:</label><br>
                    <input type="text" id="fName" name="fName" required><br><br>

                    <label for="mName">Middle Name:</label><br>
                    <input type="text" id="mName" name="mName"><br><br>

                    <label for="lName">Last Name:</label><br>
                    <input type="text" id="lName" name="lName" required><br><br>

                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" required><br><br>

                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" required><br><br>
                    
                    <label for="address">Address:</label><br>
                    <input type="text" id="address" name="address" required><br><br>
                    
                    <label for="age">age:</label><br>
                    <input type="number" id="age" name="age" required><br><br>

                    <label for="gender"> Gender:</label><br>
                    <select name="gender" id="gender">
                    <option value="MALE"> Male </option>
                    <option value="FEMALE"> Female </option>
                    </select><br><br>

                    <label for="subject_id">Subjects Assigned:</label><br>
                    <select id="subject_id" name="subject_id">
                    <?php
                     include "connection.php";
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
                    </select><br><br>


                    <input type="submit" name="add" value="Submit">
                    </form>
                    </div>
                    </CENTER>
                    </div>
                    
            <div class="table-container">
                <CENTER>
                <div class="form-wrapper">
                <h2>Professor List</h2>
                <?php
include "connection.php";
include "AdminCrud.php";

// Fetch faculty data using the reusable function
$facultyData = fetchFacultyData($conn);

// Display the retrieved data
if (!empty($facultyData)) {
    echo "<table>";
    echo "<tr><th> Name </th><th>Email</th><th>Age</th><th>Address</th><th>Gender</th><th>Subjects</th><th>Action</th></tr>";

    foreach ($facultyData as $faculty) {
        echo "<tr>";
        echo "<td>{$faculty['userInfo']['fName']} {$faculty['userInfo']['mName']} {$faculty['userInfo']['lName']}</td>";
        echo "<td>{$faculty['userInfo']['email']}</td>";
        echo "<td>{$faculty['userInfo']['age']}</td>";
        echo "<td>{$faculty['userInfo']['address']}</td>";
        echo "<td>{$faculty['userInfo']['gender']}</td>";
        echo "<td>";

        if (!empty($faculty['subjects'])) {
            foreach ($faculty['subjects'] as $subject) {
                echo htmlspecialchars($subject) . "<br>";
            }
        }
        //  else {
        //     echo "No Subject Assigned";
        // }

        echo "</td>";

        echo "<td>";
        echo "<form method='post' action='1AdminEditView.php'>";
        echo "<input type='hidden' name='user_ID' value='{$faculty['userInfo']['user_ID']}'>";
        echo "<input type='submit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='1AdminProfessorsTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this faculty?\");'>";
        echo "<input type='hidden' name='user_ID' value='{$faculty['userInfo']['user_ID']}'>";
        echo "<input type='submit' name='delete' value='Delete'>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No records found</p>";
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
