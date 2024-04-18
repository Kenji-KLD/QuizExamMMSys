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
                header("Location: 4AdminSections.php");
            } else {
                echo "<script>";
                echo "alert('New Section Entry failed. Please try again later.');";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('All fields are required.');";
            echo "</script>";
        }

    } elseif (isset($_POST['delete'])) {
        
        $section_ID = $_POST['section_ID'];

    $deleteSection = new DeleteSection($section_ID);

    if ($deleteSection->delete()) {
        header("Location: 4AdminSections.php");
        exit; // Ensure script stops after redirection
    } else {
        echo "<script>";
        echo "alert('Failed to delete Section.');";
        echo "window.location='4AdminSections.php';";
        echo "</script>";
    }


    }  elseif (isset($_POST['edit'])) {
        $sectionID = $_POST['section_ID'];
        $courseName = $_POST['sectionName'];
    
        $edit = new EditSection ($sectionID, $courseName);

        if ($edit->validate()) {
            if ($edit->edit()) {
                header("Location: 4AdminSections.php");
            } else {
                echo "<script>";
                echo "alert('New Section Entry failed. Please try again later.');";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('Section entered Already Exist.');";
            echo "</script>";
        }

    } 
}
?>