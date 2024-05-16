<?php

function fetchFacultyData($conn) {
    $sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.birthdate, A.address, A.sex, SH.subject_ID, S.subjectName
    FROM Account A
    LEFT JOIN Faculty F ON A.user_ID = F.user_ID
    LEFT JOIN SubjectHandle SH ON F.faculty_ID = SH.faculty_ID 
    LEFT JOIN Subject S ON SH.subject_ID = S.subject_ID AND S.subjectName NOT LIKE 'deleted_%'
    WHERE F.faculty_ID IS NOT NULL AND NOT A.userName LIKE 'deleted_%'";

    $result = $conn->query($sql);
    $facultyData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!isset($facultyData[$row['user_ID']])) {
                $facultyData[$row['user_ID']] = [
                    'userInfo' => [
                        'user_ID' => $row['user_ID'],
                        'userName' => $row['userName'],
                        'fName' => $row['fName'],
                        'mName' => $row['mName'],
                        'lName' => $row['lName'],
                        'email' => $row['email'],
                        'birthdate' => $row['birthdate'],
                        'address' => $row['address'],
                        'sex' => $row['sex']
                    ],
                    'subjects' => []
                ];
            }
            if ($row['subject_ID'] !== null) {
                $facultyData[$row['user_ID']]['subjects'][] = $row['subjectName'];
            }
        }
    }

    return $facultyData;
}


function fetchStudentData($conn) {
    // Query to fetch student data including section information
    $sql = "SELECT 
                A.user_ID, 
                A.userName, 
                A.password, 
                A.fName, 
                A.mName, 
                A.lName, 
                A.email, 
                A.birthdate, 
                A.sex, 
                A.address, 
                S.student_ID,
                Sec.section_ID AS student_section_ID,
                Sec.course AS section_course
            FROM 
                Account A
            LEFT JOIN 
                Student S ON A.user_ID = S.user_ID
            LEFT JOIN 
                Class C ON S.student_ID = C.student_ID
            LEFT JOIN 
                SectionHandle SH ON C.secHandle_ID = SH.secHandle_ID
            LEFT JOIN 
                Section Sec ON SH.section_ID = Sec.section_ID
            WHERE 
                S.student_ID IS NOT NULL 
                AND A.userName NOT LIKE 'deleted%'";

    $result = $conn->query($sql);

    // Process query result into an array
    $studentData = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Prepare section information
            $sectionInfo = [
                'section_ID' => $row['student_section_ID'],
                'course' => $row['section_course']
            ];

            // Prepare student data entry
            $studentData[] = [
                'user_ID' => $row['user_ID'],
                'userName' => $row['userName'],
                'password' => $row['password'],
                'fName' => $row['fName'],
                'mName' => $row['mName'],
                'lName' => $row['lName'],
                'email' => $row['email'],
                'birthdate' => $row['birthdate'],
                'sex' => $row['sex'],
                'address' => $row['address'],
                'student_ID' => $row['student_ID'],
                'sectionInfo' => $sectionInfo // Include section information in the array
            ];
        }
    }

    return $studentData;
}


function fetchSubjectData($conn){
    $sql = "SELECT subject_ID, subjectName, unitsAmount, subjectType 
    FROM Subject 
    WHERE subjectName NOT LIKE 'deleted_%'";

    $result = $conn->query($sql);

    $subjectData = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
        $subjectData[] = [
            'subject_ID' => $row['subject_ID'],
            'subjectName' => $row['subjectName'],
            'unitsAmount' => $row['unitsAmount'],
            'subjectType' => $row['subjectType']
        ];
        }
        }
            return $subjectData;

}

function fetchSectiontData($conn){
    $sql = "SELECT section_ID, course FROM Section WHERE course NOT LIKE 'delete_%'";

    $result = $conn->query($sql);

    $sectionData = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $sectionData[] = [
                'section_ID' => $row['section_ID'],
                'course' => $row['course']
            ];
        }
    }
    return $sectionData;

}

function fetchClassData($conn){
    $sql = "SELECT student_ID, section_ID FROM Class";

    $result = $conn->query($sql);

    $classData = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $classData[] = [
                'student_ID' => $row['student_ID'],
                'section_ID' => $row['section_ID']
            ];
        }
    }
    return $classData;

}
function fetchFacultyList($conn) {
    // Define the SQL query to join Faculty and Account tables
    $sql = "SELECT Faculty.faculty_ID, Account.fName, Account.mName, Account.lName
            FROM Faculty
            JOIN Account ON Faculty.user_ID = Account.user_ID
            WHERE NOT Account.userName LIKE 'delete%'
            ORDER BY Account.lName, Account.fName"; 

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    $facultyList = [];
    while ($row = $result->fetch_assoc()) {
        $fullName = $row['fName'];
        if (!empty($row['mName'])) {
            $fullName .= ' ' . $row['mName'];
        }
        $fullName .= ' ' . $row['lName'];
        $facultyList[] = [
            'faculty_ID' => $row['faculty_ID'],
            'fullName' => $fullName
        ];
    }

    $stmt->close();
    return $facultyList;
}




?>
