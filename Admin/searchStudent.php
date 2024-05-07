<?php
             
             // Fetch student data using the reusable function
             $studentData = fetchStudentData($conn);
             
             // Filter students based on search query
             if (!empty($_GET['search'])) {
                 $searchTerm = $_GET['search'];
                 $studentData = array_filter($studentData, function ($student) use ($searchTerm) {
                     return strpos(strtolower($student['fName'] . ' ' . $student['lName'] . ' ' . $student['email']), strtolower($searchTerm)) !== false;
                 });
             }
             
             // Display the retrieved and filtered data
             if (!empty($studentData)) {
                 foreach ($studentData as $student) {
                     // Display student details as before
                 }
             } else {
                 echo "<tr><td colspan='7'>No records found</td></tr>";
             }
             
             ?>