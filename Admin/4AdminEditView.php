<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section Details</title>
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
</script>

<div class="container">
    <?php
    include "connection.php";
    include "sidebar.php";

    if (isset($_POST['edit'])) {
        $section_ID = $_POST['section_ID'];
    
        $sql = "SELECT section_ID, course FROM section WHERE section_ID = '{$section_ID}'";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 
           
            echo "<form method='post' action='4AdminSectionsTrigger.php'>";
            echo "<h2>Edit section Details</h2>";
            echo "Section ID: <input type='text' name='section_ID' value='{$row['section_ID']}' readonly><br>";
            echo "Course: <input type='text' name='sectionName' value='{$row['course']}'><br>";
            
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
