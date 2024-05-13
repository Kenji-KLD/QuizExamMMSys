<?php
session_start();
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
        $birthdate = $_POST['birthdate'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];

        $mysqlDate = date('Y-m-d', strtotime($birthdate));
    
        $registration = new RegistrationStudent($student_ID, $username, $password, $fName, $mName, $lName, $email, $section_ID, $mysqlDate, $sex, $address);
    
        if ($registration->validate()) {
            if ($registration->register()) {
                $_SESSION['notif'] = "Successful";
                header("Location: 2AdminStudents.php");
            } else {
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 2AdminStudents.php");
            }
        } else {
            $_SESSION['notif'] = "Data Entry Already Exist";
            header("Location: 2AdminStudents.php");
        }
    }
    elseif (isset($_POST['delete'])) {
        
        $user_ID = $_POST['user_ID']; 

        $delete = new DeleteStudent($user_ID);

            if ($delete->delete()) {
                $_SESSION['notif'] = "Deleted Successfully";
                header("Location: 2AdminStudents.php");
            } else {
                $_SESSION['notif'] = "Failed to delete";
                header("Location: 2AdminStudents.php");
            }
       
    } elseif (isset($_POST['edit'])) {
        $user_ID = $_POST['user_ID'];
        $username = $_POST['username'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $birthdate = $_POST['birthdate'];
        $address = $_POST['address'];
        $sex = $_POST['sex'];
        $password = $_POST['password'];
        $student_ID = $_POST['student_ID']; 
        $section_ID = $_POST['section_ID'];
        

        $mysqlDate = date('Y-m-d', strtotime($birthdate));
    
        
        $editStudent = new EditStudent($user_ID, $username, $fName, $mName, $lName, $email, $mysqlDate, $address, $sex, $password, $student_ID, $section_ID);
    
        
        // if ($editStudent->validate()) {
           
            if ($editStudent->edit()) {
               
                $_SESSION['notif'] = "Successful";
                header("Location: 2AdminStudents.php");
            } else {
                $_SESSION['notif'] = "Invalid input data";
                header("Location: 2AdminStudents.php");
            }
        // } else {
        //     $_SESSION['notif'] = "Data Entry Already Existing";
        //     header("Location: 2AdminEditView.php");
        // }
    }elseif (isset($_POST['import'])) {
        if (isset($_FILES['accounts_file']['tmp_name']) && !empty($_FILES['accounts_file']['tmp_name'])) {
            $userImporter = new ImportStudent($_FILES['accounts_file']['tmp_name']);
            $userImporter->import();
        } else {
            $_SESSION['notif'] = "No file uploaded";
            header("Location: 2AdminStudents.php");
        }
    }
    
    
}

?>