<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classrooms</title>
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
            display: flex; /* Adjusted to flex display */
        }

        .table-container {
            width: 100%; /* Adjusted to full width */
        }
        .form-wrapper {
            background-color: #212121;
            padding: 20px;
        }

        #navbarToggle {
            position: absolute;
            top: 20px;
            left: 20px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #212121;
        }
    </style>
</head>
<body>
    <div class="container">
        <img id="navbarToggle" src="navbartoggle.png" alt="Navbar Toggle" onclick="toggleSidebar()" height="40px" width="40px">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="table-container">
                <CENTER>
                    <div class="form-wrapper">
                        <h2>Classroom List</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th><CENTER>Student ID</CENTER></th>
                                    <th><CENTER>Section ID</CENTER></th>
                                    <th><CENTER>Actions</CENTER></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populate the table dynamically with PHP -->
                                <tr>
                                    <td><CENTER>123456</CENTER></td>
                                    <td><CENTER>SEC001</CENTER></td>
                                    <td><CENTER><button>Edit</button><button>Delete</button></CENTER></td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </CENTER>
            </div>
        </div>
    </div>
</body>
</html>
