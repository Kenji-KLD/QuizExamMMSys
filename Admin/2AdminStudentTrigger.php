
<?php
require_once "connection.php"; 
include "2AdminStudentClass.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $student_ID = $_POST['student_ID'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $section_ID = 'N/A';
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
    
        $registration = new RegistrationStudent($student_ID, $username, $password, $fName, $mName, $lName, $email, $section_ID, $age, $gender, $address);
    
        if ($registration->validate()) {
            if ($registration->register()) {
                header("Location: 2AdminStudents.php");
                exit; // Ensure script stops after redirection
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('All fields are required.');</script>";
        }
    }
    elseif (isset($_POST['delete'])) {
        
        $user_ID = $_POST['user_ID']; 

        $delete = new DeleteStudent($user_ID);

            if ($delete->delete()) {
                header("Location: 2AdminStudents.php");
            } else {
                echo "<script>";
                echo "Failed to Delete faculty. ";
                echo "</script>";
            }
       
    } if (isset($_POST['edit'])) {
        $user_ID = $_POST['user_ID'];
        $username = $_POST['username'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $age = $_POST['age'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $student_ID = $_POST['student_ID']; 
        $section_ID = $_POST['section_ID'];
    
        // Instantiate EditStudent class with form data
        $editStudent = new EditStudent($user_ID, $username, $fName, $mName, $lName, $email, $age, $address, $gender, $password, $student_ID, $section_ID);
    
        // Validate input data before attempting edit
        if ($editStudent->validate()) {
            // Attempt to edit student details
            if ($editStudent->edit()) {
                // Redirect upon successful edit
                header("Location: 2AdminStudents.php");
                exit;
            } else {
                // Display error message if edit fails
                echo "<script>alert('Failed to update student details.');</script>";
            }
        } else {
            // Handle invalid input data
            echo "Invalid input data.";
        }
    }
    
}

?>