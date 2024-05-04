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
    <title>Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
                        <form action="2AdminStudentTrigger.php" method="post" onsubmit="return validatePassword()">
                        <div class="form-section">
                        <center><h2>Student Form</h2></center>
                        <?php echo $notif; ?>
                            <label for="student_ID">Student ID:</label>
                            <input type="text" id="student_ID" name="student_ID" placeholder="KLD-00-000000" required>

                            <label for="fName">First Name:</label>
                            <input type="text" id="fName" name="fName" required>

                            <label for="mName">Middle Name:</label>
                            <input type="text" id="mName" name="mName">

                            <label for="lName">Last Name:</label>
                            <input type="text" id="lName" name="lName" required>

                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="At least 8 characters" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="name@domain.com" required>

                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" required>

                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" required>

                            <label for="sex">Sex:</label>
                            <select name="sex" id="sex">
                            <option value="MALE"> Male </option>  
                            <option value=" FEMALE "> Female </option>  
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

                            <input type="submit" class = 'submit' name="add" value="Submit">
                   
                        </form>
                        <form action="2AdminStudentTrigger.php" method="post" enctype="multipart/form-data">
                        <h3>Or Import CSV Files</h3>
                        <input type="file" id="accounts_file" class= "acounts_file" name="accounts_file" accept=".csv">
                        <br>
                        <br>
                        <input type="submit" class='import' name="import" value="Import">
                        </form>
                        </div>
                </div>

            <div class="col-md-8">
                <div class="list-section">
                <center><h2>Student List</h2></center><br>
                    <table>
                    <tr>
                        <th> ID </th>
                        <th> Name </th>
                        <th>Email</th>
                        <th>Section</th>
                        <th> Age </th>
                        <th> Sex </th>
                        <th> Address </th>
                        <th>Action</th>
                    </tr>
                        <?php
                        include "connection.php";
                        include "AdminCrud.php";
                        
                        // Fetch student data using the reusable function
                        $studentData = fetchStudentData($conn);
                        
                        // Display the retrieved data
                        if (!empty($studentData)) {
                            foreach ($studentData as $student) {
                                echo "<tr>";
                                echo "<td>{$student['student_ID']}</td>";
                                echo "<td>{$student['fName']} {$student['mName']} {$student['lName']}</td>";
                                echo "<td>{$student['email']}</td>";
                                echo "<td>{$student['sectionInfo']['section_ID']}</td>";
                                echo "<td>{$student['age']}</td>";
                                echo "<td>{$student['sex']}</td>";
                                echo "<td>{$student['address']}</td>";
                            
                                echo "<td>";
                                echo "<form method='post' action='2AdminEditView.php'>";
                                echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
                                echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}' >"; // Access student_ID safely
                                echo "<input type='submit' class='edit' name='edit' value='Edit'>";
                                echo "</form>";
                                echo "<form method='post' action='2AdminStudentTrigger.php' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>";
                                echo "<input type='hidden' name='user_ID' value='{$student['user_ID']}'>";
                                echo "<input type='hidden' name='student_ID' value='{$student['student_ID']}'>"; // Access student_ID safely
                                echo "<input type='submit' class = 'archive' name='delete' value='archive'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
