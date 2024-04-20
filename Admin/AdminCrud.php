<?php

function fetchFacultyData($conn) {
    $sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.age, A.address, A.sex, SH.subject_ID, S.subjectName
            FROM Account A
            LEFT JOIN Faculty F ON A.user_ID = F.user_ID
            LEFT JOIN SubjectHandle SH ON F.faculty_ID = SH.faculty_ID
            LEFT JOIN Subject S ON SH.subject_ID = S.subject_ID
            WHERE F.faculty_ID IS NOT NULL AND A.userName != 'q1w2e3r4t5y6mamz'";

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
                        'age' => $row['age'],
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
   // Query to fetch student data
$sql = "SELECT A.user_ID, A.userName, A.fName, A.mName, A.lName, A.email, A.age, A.address, A.sex, S.student_ID, C.section_ID, Sec.course
FROM Account A
LEFT JOIN Student S ON A.user_ID = S.user_ID
LEFT JOIN Class C ON S.student_ID = C.student_ID
LEFT JOIN Section Sec ON C.section_ID = Sec.section_ID
WHERE S.student_ID IS NOT NULL AND A.userName != 'q1w2e3r4t5y6mamz'";

$result = $conn->query($sql);

// Process query result into an array
$studentData = [];
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$studentData[] = [
    'user_ID' => $row['user_ID'],
    'userName' => $row['userName'],
    'fName' => $row['fName'],
    'mName' => $row['mName'],
    'lName' => $row['lName'],
    'email' => $row['email'],
    'age' => $row['age'],
    'address' => $row['address'],
    'sex' => $row['sex'],
    'student_ID' => $row['student_ID'], // Include student_ID in the array
    'sectionInfo' => [
        'section_ID' => $row['section_ID'],
        'course' => $row['course']
    ]
];
}
}

    return $studentData;
}

function fetchSubjectData($conn){
    $sql = "SELECT subject_ID, subjectName, unitsAmount, subjectType FROM Subject WHERE subject_ID != 'q1w2e3r4t' and subject_ID != 'N/A'";

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
    $sql = "SELECT section_ID, course FROM Section WHERE course != 'q1w2e3r4t' AND section_ID != 'N/A'";

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

    $sectionData = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $sectionData[] = [
                'student_ID' => $row['student_ID'],
                'section_ID' => $row['section_ID']
            ];
        }
    }
    return $sectionData;

}



?>
