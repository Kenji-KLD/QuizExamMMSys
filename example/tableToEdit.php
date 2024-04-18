<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professors<!DOCTYPE html>
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
        
        .sidebar {
            width: 250px;
            background-color: #212121;
            padding: 20px;
            transition: width 0.3s ease;
        }
        
        .sidebar.closed {
            width: 0;
            overflow: hidden;
        }
        
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar ul li {
            margin-bottom: 20px;
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 24px;
        }
        
        .content {
            flex: 1;
            padding: 20px;
        }

        #navbarToggle {
            position: absolute;
            top: 20px;
            left: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <div class="sidebar" id="sidebar">
            <CENTER>
                <img src="logo.jpg" height="150px" width="150px">
                <br>
                <br>
                <ul>
                    <li><a href="Adminhome.html">Home</a></li>
                    <li><a href="AdminProfessors.html">Professors</a></li>
                    <li><a href="AdminStudents.html">Students</a></li>
                    <li><a href="AdminSubjects.html">Subjects</a></li>
                    <li><a href="AdminSectionClass.php">Sections</a></li>
                    <li><a href="AdminClassrooms.html">Classrooms</a></li>
                </ul>
            </CENTER>
        </div>
        <div class="content">
            <h1><CENTER>Professors</CENTER></h1>
            <?php
include "connection.php";

$sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, SH.subject_ID, S.subjectName
        FROM Account A
        LEFT JOIN Faculty F ON A.user_ID = F.user_ID
        LEFT JOIN SubjectHandle SH ON F.faculty_ID = SH.faculty_ID
        LEFT JOIN Subject S ON SH.subject_ID = S.subject_ID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
   
    echo "<table>";
    echo "<tr><th>User ID</th><th>Username</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Email</th><th>Subjects</th><th>Action</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['user_ID']}</td>";
        echo "<td>{$row['userName']}</td>";
        echo "<td>{$row['fName']}</td>";
        echo "<td>{$row['mName']}</td>";
        echo "<td>{$row['lName']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>";
        if ($row['subject_ID'] !== null) {
          
            do {
                echo $row['subjectName'] . "<br>";
            } while ($row = $result->fetch_assoc());
        } else {
            echo "No Subject Assigned";
        }
        echo "</td>";

        echo "<td>";
        echo "<form method='post' action='EditToTrigger.php'>";
        echo "<input type='hidden' name='user_ID' value='{$row['user_ID']}'>";
        echo "<input type='submit' name='edit' value='Edit'>";
        echo "</form>";
        echo "<form method='post' action='AdminFacultyClass.php' onsubmit='return confirm(\"Are you sure you want to delete this faculty?\");'>";
        echo "<input type='hidden' name='user_ID' value='{$row['user_ID']}'>";
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
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('closed');
        }
    </script>
</body>
</html>

