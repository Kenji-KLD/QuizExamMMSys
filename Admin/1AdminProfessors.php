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
    <title>Professors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

    <div class="sidebar">
        <img src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="container" style="margin-left: 300px;"> <!-- Adjust margin-left based on sidebar width -->
        <div class="row">
            <div class="col-md-4">
                <form action="1AdminProfessorsTrigger.php" method="post" onsubmit="return validatePassword()">
                    <div class="form-section">
                    <center><h2>Professor Form</h2></center>
                    <?php echo $notif; ?><br>
                        <label for="fName">First Name:</label>
                        <input type="text" id="fName" name="fName" required><br>

                        <label for="mName">Middle Name:</label>
                        <input type="text" id="mName" name="mName"><br>

                        <label for="lName">Last Name:</label>
                        <input type="text" id="lName" name="lName" required><br>

                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="" required><br>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="At least 8 characters" required><br>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="name@domain.com" required><br>
                        
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required><br>
                        
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" required min="0" required><br>

                        <label for="sex">Sex:</label>
                        <select name="sex" id="sex">
                            <option value="MALE"> Male </option>
                            <option value="FEMALE"> Female </option>
                        </select><br><br>

                        <input type="submit" class="submit" name="add" value="Submit">
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <div class="list-section">
                <center><h2>Professor List</h2></center><br>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Subjects</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        include "connection.php";
                        include "AdminCrud.php";

                        // Fetch faculty data using the reusable function
                        $facultyData = fetchFacultyData($conn);

                        // Display the retrieved data
                        if (!empty($facultyData)) {
                            foreach ($facultyData as $faculty) {
                                echo "<tr>";
                                echo "<td>{$faculty['userInfo']['fName']} {$faculty['userInfo']['mName']} {$faculty['userInfo']['lName']}</td>";
                                echo "<td>{$faculty['userInfo']['email']}</td>";
                                echo "<td>{$faculty['userInfo']['age']}</td>";
                                echo "<td>{$faculty['userInfo']['address']}</td>";
                                echo "<td>{$faculty['userInfo']['sex']}</td>";
                                echo "<td>";

                                if (!empty($faculty['subjects'])) {
                                    foreach ($faculty['subjects'] as $subject) {
                                        echo htmlspecialchars($subject) . "<br>";
                                    }
                                }

                                echo "</td>";

                                echo "<td>";
                                echo "<form method='post' action='1AdminEditView.php'>";
                                echo "<input type='hidden' name='user_ID' value='{$faculty['userInfo']['user_ID']}'>";
                                echo "<input type='submit' name='edit' class='edit' value='Edit'>";
                                echo "</form>";
                                echo "<form method='post' action='1AdminProfessorsTrigger.php' onsubmit='return confirm(\"Are you sure you want to Archive this faculty?\");'>";
                                echo "<input type='hidden' name='user_ID' value='{$faculty['userInfo']['user_ID']}'>";
                                echo "<input type='submit' name='delete' class='archive' value='archive'>";
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
