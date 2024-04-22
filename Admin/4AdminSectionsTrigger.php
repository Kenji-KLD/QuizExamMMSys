<?php
require_once "connection.php"; 
include "4AdminSectionClass.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $sectionID = $_POST['sectionID'];
        $courseName = $_POST['courseName'];
        

        $registration = new AddSection ($sectionID, $courseName);

        if ($registration->validate()) {
            if ($registration->add()) {
                $_SESSION['notif'] = "Successful";
                header("Location: 2AdminStudents.php");
            } else {
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 2AdminStudents.php");
            }
        } else {
            $_SESSION['notif'] = "Data Entry Already Existing";
            header("Location: 2AdminStudents.php");
        }

    } elseif (isset($_POST['delete'])) {
        
        $section_ID = $_POST['section_ID'];

    $deleteSection = new DeleteSection($section_ID);

    if ($deleteSection->delete()) {
        $_SESSION['notif'] = "Successful";
        header("Location: 2AdminStudents.php");
    } else {
        $_SESSION['notif'] = "Failed to delete data";
        header("Location: 2AdminStudents.php");
    }


    }  elseif (isset($_POST['edit'])) {
        $sectionID = $_POST['section_ID'];
        $courseName = $_POST['sectionName'];
    
        $edit = new EditSection ($sectionID, $courseName);

        if ($edit->validate()) {
            if ($edit->edit()) {
                $_SESSION['notif'] = "Successful";
                header("Location: 2AdminStudents.php");
            } else {
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 2AdminStudents.php");
            }
        } else {
            $_SESSION['notif'] = "Data Entry Already Existing";
            header("Location: 2AdminStudents.php");
        }

    } 
}
?>