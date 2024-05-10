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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="sidebar">
        <img src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="container" style="margin-left: 300px;"> <!-- Adjust margin-left based on sidebar width -->
        <div class="row">
            <div class="col-md-3" >
                <form action="3AdminSubjectTrigger.php" method="post" onsubmit='return confirm("Are you sure you want to ADD this Subject?")'>
                    <div class="form-section">
                    <center><h2>Subject Form</h2></center>
                    <center><?php echo $notif ?></center><br>
                            <label for="subjectID">Subject ID:</label>
                            <input type="text" id="subjectID" name="subjectID" placeholder="CCIS1101" required>

                            <label for="subjectName">Subject Name:</label>
                            <input type="text" id="subjectName" name="subjectName" placeholder="Computer Programming 1" required>

                            <label for="unitsAmount" >Units:</label>
                            <select name="unitsAmount" id="unitsAmount" required>
                            <option disabled selected> Select Unit </option>
                            <option value="1"> 1 </option>
                            <option value="2"> 2 </option>
                            <option value="3"> 3 </option>
                            </select>

                            <label for="subjectType">Subject Type:</label>
                            <select name="subjectType" id="subjectType">
                            <option disabled selected> Select Subject Type </option>
                            <option value="Lecture"> Lecture </option>
                            <option value="Laboratory"> Laboratory </option>
                            </select>
                            
                            <input type="submit" class = 'submit' name = "add" value="Submit">
                            </form>
                    </div>
            </div>
            <div class="col-3">
           
                <form action="3AdminSubjectTrigger.php" method="post" onsubmit='return confirm("Are you sure you want to Assign the selected Faculty?")'>
                    <div class="form-section">
                    <center><h2>Coursework Assignment</h2></center>
                    <center><?php echo $notif ?></center><br>
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
                    </div>
                </form>
            </div>
            <div class="col-md-6" >
            <div class = "list-section">
            <center><h2>Subject List</h2></center>
                <table class="list-section">
                <tr>
                    <th>Subject ID</th> 
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Subject Type</th>
                    <th>Action</th>
                </tr>
                <?php
                $subjectData = fetchSubjectData($conn);

                if (!empty($subjectData)) {
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
                        echo "<input type='submit' class = 'archive' name='delete' value='archive'>";
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
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>





