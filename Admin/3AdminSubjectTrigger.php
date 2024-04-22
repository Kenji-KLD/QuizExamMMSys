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
                $_SESSION['notif'] = "Successful";
                header("Location: 3AdminSubjects.php");
            } else {
              
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 3AdminSubjects.php");
            }
        } else {
          
            $_SESSION['notif'] = "Data Entry already Exist";
            header("Location: 3AdminSubjects.php");
        }
        
    } elseif (isset($_POST['delete'])) {
        
        $subject_ID = $_POST['subject_ID'];

    $deleteSubject = new DeleteSubject($subject_ID);

    if ($deleteSubject->delete()) {
        
        $_SESSION['notif'] = "Successful";
        header("Location: 3AdminSubjects.php");
    } else {
       
        $_SESSION['notif'] = "Failed to Delete Data";
        header("Location: 3AdminSubjects.php");
    }


    }  elseif (isset($_POST['edit'])) {
        $subjectID = $_POST['subject_ID'];
        $subjectName = $_POST['subjectName'];
        $unitsAmount = $_POST['unitsAmount'];
        $subjectType = $_POST['subjectType'];
    
        $editStudent = new EditSubject($subjectID, $subjectName, $unitsAmount, $subjectType);

    
        if ($editStudent->validate()) {
            if ($editStudent->edit()) {
               
                $_SESSION['notif'] = "Successful";
                header("Location: 3AdminSubjects.php");
            } else {
              
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 3AdminSubjects.php");
            }
        } else {
           
            $_SESSION['notif'] = "Data Entry Already Existing";
            header("Location: 3AdminEditView.php");
        }
        
}
}
?>