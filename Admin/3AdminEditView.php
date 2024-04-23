<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Dark background */
            color: #E0E0E0; /* Light text */
            padding: 20px;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center; /* Center content horizontally */
            align-items: flex-start; /* Align items at the start of the cross axis */
            gap: 20px; /* Spacing between sidebar and form */
        }

        .sidebar {
            min-width: 200px; /* Minimum width of the sidebar */
            background-color: #333333;
            padding: 10px;
            border-radius: 8px;
        }

        form {
            flex: 1; /* Take remaining space in the container */
            padding: 10px;
            background-color: #333333; /* Darker element background */
            border-radius: 8px;
        }

        h2 {
            color: #BB86FC; /* Light purple similar to ChatGPT highlights */
            text-align: center; /* Center the heading */
        }

        input[type='text'],
        input[type='email'],
        input[type='number'],
        input[type='password'],
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #555; /* Darker border for inputs */
            background-color: #222; /* Dark input background */
            color: #E0E0E0; /* Light text */
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type='submit'] {
            width: 10%;
            padding: 10px;
            background: linear-gradient(to bottom right, orange, yellow);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<script>
function restrictSpecialChars(input) {
        var fieldName = input.name;
        var regex;
        
        // Define regex based on field name
        switch (fieldName) {
            case 'fName':
            case 'mName':
            case 'lName':
            case 'username':
                regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|.<>\/?]+/;
                break;
            case 'address':
                regex = /[!$%^&*()_+\=\[\]{};:"\\|<>\?]+/;
                break;
            case 'email':
                regex = /[!#$%^&*()_+\-=\[\]{};,':"\\|<>\/?]+/;
                break;
            default:
                regex = /[!@#$%^&*()_+\=\[\]{};':"\\|<>\/?]+/;
                break;
        }
        
        if (regex.test(input.value)) {
            input.value = input.value.replace(regex, '');
        }
    }

    // Attach the restrictSpecialChars function to the input fields
    document.addEventListener('DOMContentLoaded', function() {
        var inputFields = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]');
        inputFields.forEach(function(input) {
            input.addEventListener('input', function() {
                restrictSpecialChars(this);
            });
        });
    });
    function validatePassword() {
        var passwordInput = document.getElementById('password');
        var password = passwordInput.value;

        // Check if the password meets the criteria
        if (password.length < 8 || /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password) || /\s/.test(password)) {
            alert('Password must be at least 8 characters long and must not contain any spaces.');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>
<div class="container">
    <?php
    include "connection.php";
    include "sidebar.php";

    if (isset($_POST['edit'])) {
        $subject_ID = $_POST['subject_ID'];
    
        $sql = "SELECT subject_ID, subjectName, unitsAmount, subjectType  FROM Subject WHERE subject_ID = '{$subject_ID}'";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 

            echo "<form method='post' action='3AdminSubjectTrigger.php'>";
            echo "<h2>Edit Subject Details</h2>";
            echo "Subject ID: <input type='text' name='subject_ID' value='{$row['subject_ID']}' readonly><br>";
            echo "Subject Name: <input type='text' name='subjectName' value='{$row['subjectName']}'><br>";
            echo "Units Amount: <input type='number' name='unitsAmount' value='{$row['unitsAmount']}' required min='0'><br>";
            echo "Subject Type: <input type='text' name='subjectType' value='{$row['subjectType']}'><br>";
            
            echo "<input type='submit' name='edit' value='Save Changes'>";
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

</body>
</html>
