<?php
require_once "connection.php"; 
include "3AdminSubjectClass.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $subjectID = $_POST['subjectID'];
        $subjectName = $_POST['subjectName'];
        $unitsAmount = $_POST['unitsAmount'];
        $subjectType = $_POST['subjectType'];
        

        $registration = new AddSubject ($subjectID, $subjectName, $unitsAmount, $subjectType);

        if ($registration->validate()) {
            if ($registration->add()) {
                header("Location: 3AdminSubjects.php");
            } else {
                echo "<script>";
                echo "alert('New subject Entry failed. Please try again later.');";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('Subject Entered already Exist.');";
            echo "</script>";
        }
        
    } elseif (isset($_POST['delete'])) {
        
        $subject_ID = $_POST['subject_ID'];

    $deleteSubject = new DeleteSubject($subject_ID);

    if ($deleteSubject->delete()) {
        header("Location: 3AdminSubjects.php");
        exit; // Ensure script stops after redirection
    } else {
        echo "<script>";
        echo "alert('Failed to delete subject.');";
        echo "window.location='3AdminSubjects.php';";
        echo "</script>";
    }


    }  elseif (isset($_POST['edit'])) {
        $subjectID = $_POST['subject_ID'];
        $subjectName = $_POST['subjectName'];
        $unitsAmount = $_POST['unitsAmount'];
        $subjectType = $_POST['subjectType'];
    
        $editStudent = new EditSubject($subjectID, $subjectName, $unitsAmount, $subjectType);

    
        if ($editStudent->validate()) {
            if ($editStudent->edit()) {
                header("Location: 3AdminSubjects.php");
                exit;
            } else {
                echo "<script>alert('Failed to update Subject details.');</script>";
            }
        } else {
            echo "<script>alert('Subject entered already exist.');</script>";
        }
        
}
}
?>