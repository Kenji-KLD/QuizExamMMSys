

<style>
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
            margin-bottom: 20px;
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 24px;
        }
</style>

<div class="sidebar" id="sidebar">
    <CENTER>
        <img src="IICSlogo.jpg" height="150px" width="150px">
        <br>
        <br>
        <ul>
            <li><a href="Adminhome.php">Home</a></li>
            <li><a href="1AdminProfessors.php">Professors</a></li>
            <li><a href="2AdminStudents.php">Students</a></li>
            <li><a href="3AdminSubjects.php">Subjects</a></li>
            <li><a href="4AdminSections.php">Sections</a></li>
            <li><a href="5AdminClassrooms.php">Classrooms</a></li>
        </ul>
    </CENTER>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('closed');
        }
    </script>
    
</div>