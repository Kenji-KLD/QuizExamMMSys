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




