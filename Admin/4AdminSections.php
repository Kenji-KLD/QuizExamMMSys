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
    <title>Sections</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="/dist/js/checkToken.js"></script>
    <script>jQuery(function() {checkToken(0, 0)})</script>
</head>

<body>
<div class="sidebar">
        <img src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="container" style="margin-left: 300px;"> <!-- Adjust margin-left based on sidebar width -->
        <div class="row">
            <div class="col-md-4">
                <form action="4AdminSectionsTrigger.php" method="post" onsubmit='return confirm("Are you sure you want to ADD this Subject?")'>
                    <div class="form-section">
                    <center><h2>Subject Form</h2></center>
                     <?php echo $notif; ?><br>
                            <label for="sectionID">Section ID:</label>
                            <input type="text" id="sectionID" name="sectionID" required>

                            <label for="courseName">Course Name:</label>
                            <input type="text" id="courseName" name="courseName" required>
                            
                            <input type="submit" class = 'submit' name = "add" value="Submit">
                    </div>
                </form>
                
        </div>
        <div class="col-md-6" style="margin-left: 100px;">
        <div class="list-section">
        <center><h2>Subject List</h2></center><br>
                <table >
                <tr>
                    <th>Section ID</th>
                    <th>Section Course</th>
                    <th>Action</th>
                </tr>
                
                <?php
                $sectionData = fetchSectiontData($conn);

                if (!empty($sectionData)) {
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
                        echo "<input type='submit' class = 'archive'  name='delete' value='archive'>";
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
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>