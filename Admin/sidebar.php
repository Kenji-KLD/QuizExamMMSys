<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Toggle Button</title>
    <style>
        .toggle-btn {
            color: white;
            font-size: 24px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 10px;
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
            margin-bottom: 10px; /* Reduce spacing between list items */
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            background-image: linear-gradient(to right, #007BFF, #0056b3); /* Blue gradient */
            transition: background-image 0.3s ease;
        }

        /* Hover effect for list items */
        .sidebar ul li a:hover {
            background-image: linear-gradient(to right, #0056b3, #003366); /* Darker blue gradient on hover */
        }

        /* Centering content inside sidebar */
        .sidebar-content {
            text-align: center;
        }

        /* Styling for logout button */
        .logout-btn {
            display: block;
            width: 100%; /* Make button full width of container */
            padding: 15px; /* Increase padding for larger button */
            font-size: 18px; /* Adjust font size */
            color: white;
            border: none;
            border-radius: 8px; /* Rounded corners */
            cursor: pointer;
            background-image: linear-gradient(135deg, #F84502, #FFD700); /* Orange to Yellow gradient */
            transition: background-image 0.3s ease;
        }

        /* Hover effect for logout button */
        .logout-btn:hover {
            background-image: linear-gradient(135deg, #FFD700, #F84502); /* Yellow to Orange gradient on hover */
        }
    </style>
    <!-- Include Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <!-- Toggle button for sidebar -->
        <!-- <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button> -->
        <div class="sidebar-content">
            <img src="IICSlogo.jpg" height="150px" width="150px" alt="IICS Logo">
            <ul>
                <li><a href="Adminhome.php">Home</a></li>
                <li><a href="1AdminProfessors.php">Professors</a></li>
                <li><a href="2AdminStudents.php">Students</a></li>
                <li><a href="3AdminSubjects.php">Subjects</a></li>
                <li><a href="4AdminSections.php">Sections</a></li>
                <!-- <li><a href="5AdminClassrooms.php">Classrooms</a></li> -->
            </ul>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <form onclick="logout();">
            <button class="submit">Logout</button>
            </form>     
        </div>
    </div>

</body>   
<script src="..\dist\js\logout.js"></script>
<script src="..\dist\js\checkToken.js"></script>
<script>jQuery(function() {checkToken(0, 0)})</script>
<!-- <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('closed');
        }

    </script> -->
</html>
