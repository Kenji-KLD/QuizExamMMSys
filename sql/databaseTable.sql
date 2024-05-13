CREATE TABLE Account(
	user_ID BIGINT AUTO_INCREMENT,
	userName VARCHAR(32) UNIQUE NOT NULL,
	password VARCHAR(64) NOT NULL,
	fName VARCHAR(32) NOT NULL,
	mName VARCHAR(32),
	lName VARCHAR(32) NOT NULL,
	email VARCHAR(64) NOT NULL,
	birthdate DATE NOT NULL,
	sex VARCHAR(6) NOT NULL,
	address TEXT NOT NULL,
	PRIMARY KEY(user_ID)
);

CREATE TABLE Subject(
	subject_ID VARCHAR(9),
	subjectName VARCHAR(64) NOT NULL,
	unitsAmount INT NOT NULL,
	subjectType VARCHAR(10) NOT NULL,
	PRIMARY KEY(subject_ID)
);

CREATE TABLE Section(
	section_ID VARCHAR(8),
	course VARCHAR(64) NOT NULL,
	PRIMARY KEY(section_ID)
);

CREATE TABLE Student(
	student_ID VARCHAR(13),
	user_ID BIGINT NOT NULL,
	PRIMARY KEY(student_ID),
	FOREIGN KEY(user_ID) REFERENCES Account(user_ID)
);

CREATE TABLE Admin(
	admin_ID BIGINT AUTO_INCREMENT,
	user_ID BIGINT NOT NULL,
	PRIMARY KEY(admin_ID),
	FOREIGN KEY(user_ID) REFERENCES Account(user_ID)
);

CREATE TABLE Faculty(
	faculty_ID BIGINT AUTO_INCREMENT,
	user_ID BIGINT NOT NULL,
	PRIMARY KEY(faculty_ID),
	FOREIGN KEY(user_ID) REFERENCES Account(user_ID)
);

CREATE TABLE LoginToken(
	session_token VARCHAR(32),
	user_ID BIGINT NOT NULL,
	tokenExpiration DATETIME NOT NULL,
	PRIMARY KEY(session_token),
	FOREIGN KEY(user_ID) REFERENCES Account(user_ID)
);

CREATE TABLE QuestionSet(
	questionSet_ID BIGINT AUTO_INCREMENT,
	faculty_ID BIGINT NOT NULL,
	subject_ID VARCHAR(9) NOT NULL,
	questionSetTitle VARCHAR(64) NOT NULL,
	questionSetType VARCHAR(4) NOT NULL,
	questionTotal INT NOT NULL,
	randomCount INT,
	rubrics TEXT,
	deadline DATETIME NOT NULL,
	timeLimit INT NOT NULL,
	acadYear VARCHAR(9) NOT NULL,
	acadTerm VARCHAR(8) NOT NULL,
	acadSem VARCHAR(15) NOT NULL,
	PRIMARY KEY(questionSet_ID),
	FOREIGN KEY(faculty_ID) REFERENCES Faculty(faculty_ID),
	FOREIGN KEY(subject_ID) REFERENCES Subject(subject_ID)
);

CREATE TABLE QuestionBank(
	question_ID BIGINT AUTO_INCREMENT,
	questionSet_ID BIGINT NOT NULL,
	questionFormat VARCHAR(32) NOT NULL,
	questionNumber INT NOT NULL,
	questionText TEXT NOT NULL,
	questionAnswer TEXT,
	pointsGiven INT NOT NULL,
	PRIMARY KEY(question_ID),
	FOREIGN KEY(questionSet_ID) REFERENCES QuestionSet(questionSet_ID)
);

CREATE TABLE ChoiceBank(
	choice_ID BIGINT AUTO_INCREMENT,
	question_ID BIGINT NOT NULL,
	choiceLabel TEXT NOT NULL,
	PRIMARY KEY(choice_ID),
	FOREIGN KEY(question_ID) REFERENCES QuestionBank(question_ID)
);

CREATE TABLE SubjectHandle(
	subHandle_ID BIGINT AUTO_INCREMENT,
	faculty_ID BIGINT NOT NULL,
	subject_ID VARCHAR(9) NOT NULL,
	PRIMARY KEY(subHandle_ID),
	FOREIGN KEY(faculty_ID) REFERENCES Faculty(faculty_ID),
	FOREIGN KEY(subject_ID) REFERENCES Subject(subject_ID)
);

CREATE TABLE SectionHandle(
	secHandle_ID BIGINT AUTO_INCREMENT,
	subHandle_ID BIGINT NOT NULL,
	section_ID VARCHAR(8) NOT NULL,
	PRIMARY KEY(secHandle_ID),
	FOREIGN KEY(subHandle_ID) REFERENCES SubjectHandle(subHandle_ID),
	FOREIGN KEY(section_ID) REFERENCES Section(section_ID)
);

CREATE TABLE AnswerStatistic(
	student_ID VARCHAR(13) NOT NULL,
	question_ID BIGINT NOT NULL,
	studentAnswer TEXT NOT NULL,
	isCorrect BOOLEAN NOT NULL,
	FOREIGN KEY(student_ID) REFERENCES Student(student_ID),
	FOREIGN KEY(question_ID) REFERENCES QuestionBank(question_ID)
);

CREATE TABLE Score(
	student_ID VARCHAR(13) NOT NULL,
	questionSet_ID BIGINT NOT NULL,
	passed BOOLEAN NOT NULL,
	score INT NOT NULL,
	dateTaken DATETIME NOT NULL,
	FOREIGN KEY(student_ID) REFERENCES Student(student_ID),
	FOREIGN KEY(questionSet_ID) REFERENCES QuestionSet(questionSet_ID)
);

CREATE TABLE SetDisallow(
	student_ID VARCHAR(13) NOT NULL,
	questionSet_ID BIGINT NOT NULL,
	isDisallowed BOOLEAN NOT NULL,
	FOREIGN KEY(student_ID) REFERENCES Student(student_ID),
	FOREIGN KEY(questionSet_ID) REFERENCES QuestionSet(questionSet_ID)
);

CREATE TABLE Class(
	student_ID VARCHAR(13) UNIQUE NOT NULL,
	section_ID VARCHAR(8) NOT NULL,
	FOREIGN KEY(student_ID) REFERENCES Student(student_ID),
	FOREIGN KEY(section_ID) REFERENCES Section(section_ID)
);

CREATE TABLE SectionSubjectList(
	section_ID VARCHAR(8) NOT NULL,
	subject_ID VARCHAR(9) NOT NULL,
	FOREIGN KEY(section_ID) REFERENCES Section(section_ID),
	FOREIGN KEY(subject_ID) REFERENCES Subject(subject_ID)
);