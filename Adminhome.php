<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz and Exam Maker Management System</title>
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
        <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->
        <div class="content">
            <h1><CENTER>Welcome to Quiz and Exam Maker Management System</CENTER></h1>
            <!-- Your content goes here -->
        </div>
    </div>

    
</body>
</html>
