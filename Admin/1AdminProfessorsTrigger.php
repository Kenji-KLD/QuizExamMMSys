<?php
session_start();
require_once "connection.php"; 
include "1AdminFacultyClass.php"; 

$notif = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $day = $_POST['day'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        $sex = $_POST ['sex'];
        $address = $_POST ['address'];

        $birthdate = $year . '/' . $month . '/' . $day;


        $registration = new RegistrationFaculty ($username, $password, $fName, $mName, $lName, $email, $birthdate, $sex, $address);

        if ($registration->validate()) {
            if ($registration->register()) {
                $_SESSION['notif'] = "Successful";
                header("Location: 1AdminProfessors.php");
            } else {
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 1AdminProfessors.php");
            }
        } else {
            $_SESSION['notif'] = "Data Entry Already Exist";
            header("Location: 1AdminProfessors.php");
        }
        
    } elseif (isset($_POST['delete'])) {
        
        $user_ID = $_POST['user_ID']; 

        $delete = new DeleteFaculty($user_ID);

            if ($delete->delete()) {
                $_SESSION['notif'] = "Archived Succesfully";
                header("Location: 1AdminProfessors.php");
            } else {
                $_SESSION['notif'] = "Failed to Archive data";
                header("Location: 1AdminProfessors.php");
            }
       
    } elseif (isset($_POST['edit'])) {
        
        $user_ID = $_POST['user_ID'];
        $username = $_POST['username'];
        $fName = $_POST['fName'];
        $mName = $_POST['mName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $birthdate = $_POST ['birthdate'];
        $sex = $_POST ['sex'];
        $address = $_POST ['address'];

        $mysqlDate = date('Y-m-d', strtotime($birthdate));
    
        $editFaculty = new EditFaculty ($user_ID, $username, $password, $fName, $mName, $lName, $email, $mysqlDate, $sex, $address);
    
        // if ($editFaculty->validate()) {
            if ($editFaculty->edit()) {
                $_SESSION['notif'] = "Edit Success";
                header("Location: 1AdminProfessors.php");
          
            } else {
                $_SESSION['notif'] = "Invalid Input data";
                header("Location: 1AdminProfessors.php");
            }
        // } else {
        //     $_SESSION['notif'] = "Data Entry Already Exist";
        //     header("Location: 2AdminEditView.php");
        // }
}
}
?>