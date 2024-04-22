<?php
session_start();
require_once "connection.php"; 
include 'AdminCrud.php';
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

    
        // if ($editStudent->validate()) {
            if ($editStudent->edit()) {
               
                $_SESSION['notif'] = "Successful";
                header("Location: 3AdminSubjects.php");
            } else {
              
                $_SESSION['notif'] = "Invalid Input Data";
                header("Location: 3AdminSubjects.php");
            }
        // } else {
           
        //     $_SESSION['notif'] = "Data Entry Already Existing";
        //     header("Location: 3AdminEditView.php");
        // }
        
}elseif (isset($_POST['assign'])) {
    $faculty_ID = $_POST['faculty_ID'];
    $subjectID = $_POST['subjectID'];

    // Assuming AssignSubject is a class that handles the assignment of subjects to faculty
    $editStudent = new AssignSubject($faculty_ID, $subjectID);
if($editStudent->checkEmpty()){
    if ($editStudent->validate()) {
        if ($editStudent->assign()) {
            $_SESSION['notif1'] = "Successful";
            header("Location: 3AdminSubjects.php");
        } else {
            $_SESSION['notif1'] = "Invalid Input Data";
            header("Location: 3AdminSubjects.php");
        }
    } else {
        $_SESSION['notif1'] = "Data Entry Already Existing";
        header("Location: 3AdminSubjects.php");
    }
}else{
    $_SESSION['notif1'] = "Choose Faculty and Subject";
        header("Location: 3AdminSubjects.php");
}
}
}
?>