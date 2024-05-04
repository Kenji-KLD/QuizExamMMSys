<?php
 include "connection.php";

 if (isset($_POST['edit'])) {
     $subject_ID = $_POST['subject_ID'];
 
     $sql = "SELECT subject_ID, subjectName, unitsAmount, subjectType  FROM Subject WHERE subject_ID = '{$subject_ID}'";
 
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
                <a class="edit" href="3AdminSubjects.php" style="text-decoration: none;">Go Back</a>
                </div>
            </div>

            
            <div class="col-md-8">
                <div class="form-section" style="margin-left: 50px;">
                    <?php
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc(); 
                
                            echo "<form method='post' action='3AdminSubjectTrigger.php'>";
                            echo "<h2>Edit Subject Details</h2>";
                            echo "Subject ID: <input type='text' name='subject_ID' value='{$row['subject_ID']}' readonly><br>";
                            echo "Subject Name: <input type='text' name='subjectName' value='{$row['subjectName']}'><br>";
                            echo "Units Amount: <input type='number' name='unitsAmount' value='{$row['unitsAmount']}' required min='0'><br>";
                            echo "Subject Type: <input type='text' name='subjectType' value='{$row['subjectType']}'><br>";
                            
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
