<?php
    include "connection.php";

    if (isset($_POST['edit'])) {
        $user_ID = $_POST['user_ID'];

        $sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.age, A.sex, A.address, S.student_ID, C.section_ID
                FROM Account A
                LEFT JOIN Student S ON A.user_ID = S.user_ID
                LEFT JOIN Class C ON S.student_ID = C.student_ID
                WHERE A.user_ID = {$user_ID}";

        $result = $conn->query($sql);
?>        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
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
    <div class="container">
        <div class="row">

            <div class="col-md-2" style="margin-left: 100px;">
                <div style = "margin-top: 30px" class="form-section" >
                <a class="edit" href="2AdminStudents.php" style="text-decoration: none;">Go Back</a>
                </div>
            </div>

            
            <div class="col-md-8">
                <div class="form-section" style="margin-left: 50px;">
                    <?php
                        if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc(); 

                        echo "<form method='post' action='2AdminStudentTrigger.php'>";
                        echo "<input type='hidden' name='user_ID' value='{$row['user_ID']}'>";
                        echo "<h2>Edit Student Details</h2>"; // Place the header within the form
                        echo "Student ID: <input type='text' name='student_ID' value='{$row['student_ID']}' readonly><br>";
                        echo "Username: <input type='text' name='username' value='{$row['userName']}'><br>";
                        echo "First Name: <input type='text' name='fName' value='{$row['fName']}'><br>";
                        echo "Middle Name: <input type='text' name='mName' value='{$row['mName']}'><br>";
                        echo "Last Name: <input type='text' name='lName' value='{$row['lName']}'><br>";
                        echo "Email: <input type='email' name='email' value='{$row['email']}'><br>";
                        echo "Age: <input type='number' name='age' value='{$row['age']}'><br>";
                        echo "Address: <input type='text' name='address' value='{$row['address']}'><br>";
                        echo "Gender: <select name='sex'>";
                        echo "<option value='MALE'" . ($row['sex'] == 'MALE' ? ' selected' : '') . ">Male</option>";
                        echo "<option value='FEMALE'" . ($row['sex'] == 'FEMALE' ? ' selected' : '') . ">Female</option>";
                        echo "</select><br>";
                        echo "New Password (leave blank to keep current): <input type='password' name='password'><br>";
                        echo "<input type='submit' class='submit' name='edit' value='Save Changes'>";
                        echo "</form>";
                    } else {
                        echo "<p>No record found for the provided user ID.</p>";
                    }
                    } else {
                    echo "<p>User ID not provided.</p>";
                    }

                    $conn->close();
                    ?>
                </div>
            </div>

   
            <div class="col-md-2">
              <!--Leave this Blank-->
            </div>
            
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
