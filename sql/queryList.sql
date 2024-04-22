MANAGE CLASS:

Default Display:
SELECT CONCAT(a.lName, ', ', a.fName, ' ', COALESCE(CONCAT(LEFT(NULLIF(a.mName, ''), 1), '.'), '')) AS fullName, a.age AS age, a.sex AS sex, a.email AS email, a.address AS address
FROM Account a
INNER JOIN Student s ON a.user_ID = s.user_ID
INNER JOIN Class c ON s.student_ID = c.student_ID
WHERE c.section_ID = ?
ORDER BY fullName;


Search Display:
SELECT CONCAT(a.lName, ', ', a.fName, ' ', COALESCE(CONCAT(LEFT(NULLIF(a.mName, ''), 1), '.'), '')) AS fullName, a.age AS age, a.sex AS sex, a.email AS email, a.address AS address
FROM Account a
INNER JOIN Student s ON a.user_ID = s.user_ID
INNER JOIN Class c ON s.student_ID = c.student_ID
WHERE c.section_ID = ? AND (s.student_ID LIKE ? OR a.email LIKE ?)
ORDER BY fullName;



MAKE ASSESSMENT:

INSERT INTO QuestionSet(faculty_ID, subject_ID, questionSetTitle, questionSetType, questionTotal, randomCount, rubrics, deadline, acadYear, acadTerm, acadSem) VALUES
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);



EDIT ASSESSMENT:

SELECT questionFormat, questionNumber, questionText, pointsGiven



HOME (PROFESSOR):
SELECT su.subject_ID AS subject_ID, su.subjectName AS subjectName, se.section_ID AS section_ID FROM Subject su
INNER JOIN SubjectHandle suh ON su.subject_ID = suh.subject_ID
INNER JOIN Faculty f ON suh.faculty_ID = f.faculty_ID
INNER JOIN SectionHandle seh ON f.faculty_ID = seh.faculty_ID
INNER JOIN Section se ON seh.section_ID = se.section_ID
WHERE f.faculty_ID = ?;



ADD/REMOVE CLASS:
SELECT COUNT(suh.subHandle_ID) FROM SubjectHandle suh
INNER JOIN Subject su ON suh.subject_ID = su.subject_ID
INNER JOIN SectionHandle seh ON suh.subHandle_ID = seh.subHandle_ID
INNER JOIN Section se ON seh.section_ID = se.section_ID
WHERE se.section_ID = ? AND su.subject_ID = ?



SELECT suh.subject_ID, su.subjectName FROM Subject su
INNER JOIN SubjectHandle suh ON su.subject_ID = suh.subject_ID
INNER JOIN Faculty f ON suh.faculty_ID = f.faculty_ID
WHERE f.faculty_ID = 1;

SELECT se.section_ID FROM Section se
LEFT JOIN SectionHandle seh ON se.section_ID = seh.section_ID
LEFT JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
WHERE suh.subHandle_ID IS NULL;

SELECT se.section_ID FROM Section se
INNER JOIN SectionHandle seh ON se.section_ID = seh.section_ID
INNER JOIN SubjectHandle suh ON seh.subHandle_ID = suh.subHandle_ID
WHERE suh.subHandle_ID = (SELECT subHandle_ID FROM SubjectHandle WHERE faculty_ID = 1)



SELECT
    (SELECT COUNT(*) FROM Student s WHERE s.user_ID = ac.user_ID) AS student_count,
    (SELECT COUNT(*) FROM Admin ad WHERE ad.user_ID = ac.user_ID) AS admin_count,
    (SELECT COUNT(*) FROM Faculty f WHERE f.user_ID = ac.user_ID) AS faculty_count
FROM Account ac
WHERE ac.userName = ?;

SELECT a.* FROM Account a
INNER JOIN LoginToken l ON a.user_ID = l.user_ID
WHERE l.session_token = '2gd7ixncim73p2c7mb2hno9ke9ev8k3n';