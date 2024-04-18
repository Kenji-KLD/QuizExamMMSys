<?php
include "AdminStudentsClass.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $student_id = $_POST ['student_id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];

        $registration = new RegistrationStudent($student_id, $username, $password, $fName, $mName, $lName, $email);

        if ($registration->validate()) {
            if ($registration->register()) {
                echo "Registration successful!";
            } else {
                echo "Registration failed. Please try again later.";
            }
        } else {
            echo "All fields are required.";
        }

    } elseif (isset($_POST['delete'])) {
        
        $faculty_id = $_POST['faculty_id']; 

        $delete = new DeleteStudent($student_id);

            if ($delete->delete()) {
                echo "Delete successful!";
            } else {
                echo "Delete failed. Please try again later.";
            }
       
    } elseif (isset($_POST['edit'])) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];

        $edit = new EditStudent($username, $password, $fName, $mName, $lName, $email);

        if ($edit->validate()) {
            if ($edit->register()) {
                echo "Edit successful!";
            } else {
                echo "Edit failed. Please try again later.";
            }
        } else {
            echo "All fields are required.";
        }
       
    }
    
    else {
        echo "Invalid action.";
    }
}
?>
