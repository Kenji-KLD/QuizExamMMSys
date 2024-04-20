SELECT * FROM SectionHandle WHERE faculty_ID = ?;

SELECT COUNT(a.isCorrect), a.question_ID FROM AnswerStatistic a 
INNER JOIN QuestionBank qb ON a.question_ID = qb.question_ID
INNER JOIN QuestionSet qs ON qb.questionSet_ID = qs.questionSet_ID
WHERE qs.questionSet_ID = ?;

SELECT q.question_ID, 
COUNT(a.isCorrect) AS total_correct_answers
FROM QuestionBank q
LEFT JOIN AnswerStatistic a ON q.question_ID = a.question_ID AND a.isCorrect = TRUE
GROUP BY q.question_ID;
