<?php
require_once "connection.php"; 
include "1AdminFacultyClass.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $subject_id = $_POST['subject_id'];
        $age = $_POST ['age'];
        $gender = $_POST ['gender'];
        $address = $_POST ['address'];

        $registration = new RegistrationFaculty ($username, $password, $fName, $mName, $lName, $email, $subject_id, $age, $gender, $address);

        if ($registration->validate()) {
            if ($registration->register()) {
                header("Location: 1AdminProfessors.php");
            } else {
                echo "<script>";
                echo "alert('Registration failed. Please try again later.');";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('All fields are required.');";
            echo "</script>";
        }

    } elseif (isset($_POST['delete'])) {
        
        $user_ID = $_POST['user_ID']; 

        $delete = new DeleteFaculty($user_ID);

            if ($delete->delete()) {
                header("Location: 1AdminProfessors.php");
            } else {
                echo "<script>";
                echo "Failed to Delete faculty.";
                echo "</script>";
            }
       
    } elseif (isset($_POST['edit'])) {
        
        $user_ID = $_POST['user_ID'];
        $username = $_POST['username'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $subject_id = $_POST['subject_id'];
        $age = $_POST ['age'];
        $gender = $_POST ['gender'];
        $address = $_POST ['address'];
    
        $editFaculty = new EditFaculty ($user_ID, $username, $password, $fName, $mName, $lName, $email, $subject_id, $age, $gender, $address);
    
        if ($editFaculty->validate()) {
            if ($editFaculty->edit()) {
                header("Location: 1AdminProfessors.php");
          
            } else {
                echo "<script>";
                echo "Failed to update faculty details.";
                echo "</script>";
                
            }
        } else {
            echo "Invalid input data.";
        }
}
}
?>