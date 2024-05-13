INSERT INTO Account(userName, password, fName, mName, lName, email, age, sex, address) VALUES
    ("Kenji667", "$2y$10$uaopU3O/dB95DSCNNEtApObugn4BUAkXHSAXBjWLwbMz8.ltpNJPW", 
    "Kenji", "", "Gabunada", 
    "kgabunada@kld.edu.ph", 21, "Male",
    "B2 L29 Scotland St. Sunnycrest Village Salitran II, Dasmariñas, Cavite 4114"),
    ("MasterAibu", "$2y$10$oOl/QWtH.3OnhmItx0xgAuOkNSFfZ0vgnXoHM/o0f3cH/IwAWGa9O", 
    "Aibu", "", "Kuan", 
    "akuan@kld.edu.ph", 20, "Male", 
    "Congressional Avenue, Dasmariñas, Cavite 4114"),
    ("admin", "$2y$10$hKTXF3EesCmn9sRCaCbzE.vZxfbNd1J6XHU9PVuzw7i4oYk2.UbuK", 
    "Admin", "", "Lorem Ipsum", 
    "aloremipsum@kld.edu.ph", 30, "Male", 
    "Congressional Avenue, Dasmariñas, Cavite 4114"),
    ("faculty", "$2y$10$/l/3bssfbuFr/8FqC1vNsO21jGqZMsf2vIXXuF84jL9bLbJ9YRSES", 
    "Faculty", "", "Lorem Ipsum", 
    "floremipsum@kld.edu.ph", 32, "Male", 
    "Congressional Avenue, Dasmariñas, Cavite 4114"),
    ("Hiroshi", "$2y$10$Nec0GYyGV2drsr6sJrFT/uNvpSMyhoPsAbVqDYbCEV3pwtOSxjagO", 
    "David", "Lavilla", "Agonia", 
    "dlagonia@kld.edu.ph", 19, "Male",
    "Greenwich St. Chester Place Burol Main, Dasmariñas, Cavite 4114")
;

INSERT INTO Subject(subject_ID, subjectName, unitsAmount, subjectType) VALUES
    ("GEC9100", "Filipino 1", 3, "Lecture"),
    ("PCIS2219", "Business Law", 3, "Lecture"),
    ("GEC2000", "Ethics", 3, "Lecture"),
    ("PE2204", "PATHFIT 4", 2, "Lecture"),
    ("PCIS2209", "Organization and Management Concepts", 3, "Lecture"),
    ("CCIS2205", "Information Management Lec", 2, "Lecture"),
    ("CCIS2205L", "Information Management Lab", 1, "Laboratory"),
    ("PCIS2205", "Systems Analysis and Design", 3, "Lecture"),
    ("PCIS2206", "Business Process Design and Management", 3, "Lecture"),
    ("GEE5000", "People, Earth and Ecosystem", 3, "Lecture")
;

INSERT INTO Section(section_ID, course) VALUES
    ("BSIS201", "Bachelor Science in Information Systems"),
    ("BSIS206", "Bachelor Science in Information Systems")
;

INSERT INTO Student(student_ID, user_ID) VALUES
    ("KLD-22-000247", 1),
    ("KLD-22-000192", 2),
    ("KLD-22-000420", 5)
;

INSERT INTO Admin(user_ID) VALUES
    (3)
;

INSERT INTO Faculty(user_ID) VALUES
    (4)
;

INSERT INTO Class(student_ID, section_ID) VALUES
    ("KLD-22-000247", "BSIS201"),
    ("KLD-22-000192", "BSIS201"),
    ("KLD-22-000420", "BSIS201")
;

INSERT INTO SectionSubjectList(section_ID, subject_ID) VALUES
    ("BSIS201", "GEE5000"),
    ("BSIS201", "PCIS2209")
;

INSERT INTO SubjectHandle(faculty_ID, subject_ID) VALUES
    (1, "PCIS2205")
;

INSERT INTO SectionHandle(subHandle_ID, section_ID) VALUES
    (1, "BSIS201")
;

INSERT INTO QuestionSet(secHandle_ID, questionSetTitle, questionSetType, questionTotal, rubrics, deadline, timeLimit, acadYear, acadTerm, acadSem) VALUES
    (1, "Quiz 1: Ecosystem", "QUIZ", 10,
    "Choose the letter which is most correct.",
    '2024-05-01 00:00:00', 7200, "2023-2024", "Midterm", "2nd Semester")
;

INSERT INTO QuestionBank(questionSet_ID, questionFormat, questionNumber, questionText, questionAnswer, pointsGiven) VALUES
    (1, "MULTIPLE_CHOICE", 1, 'Who coined the term "Ecology"?', "A. Ernst Haeckel", 1),
    (1, "MULTIPLE_CHOICE", 2, 'Who coined the term "Ecosystem"?', "A. Arthur Tansley", 1),
    (1, "MULTIPLE_CHOICE", 3, 'It is an environment that provides the things an organism needs to live, grow, and reproduce.', "A. Habitat", 1),
    (1, "MULTIPLE_CHOICE", 4, 'What is/are the component/s of an ecosystem?', "A. Both A & B", 1),
    (1, "MULTIPLE_CHOICE", 5, 'Who coined the term "Ecosystem 5"?', "A. Testing 5", 1),
    (1, "MULTIPLE_CHOICE", 6, 'Who coined the term "Ecosystem 6"?', "A. Testing 6", 1),
    (1, "MULTIPLE_CHOICE", 7, 'Who coined the term "Ecosystem 7"?', "A. Testing 7", 1),
    (1, "MULTIPLE_CHOICE", 8, 'Who coined the term "Ecosystem 8"?', "A. Testing 8", 1),
    (1, "MULTIPLE_CHOICE", 9, 'Who coined the term "Ecosystem 9"?', "A. Testing 9", 1),
    (1, "MULTIPLE_CHOICE", 10, 'Who coined the term "Ecosystem 10"?', "A. Testing 10", 1)
;

INSERT INTO ChoiceBank(question_ID, choiceLabel) VALUES
    (1, "A. Ernst Haeckel"),
    (1, "B. Ernesto Heneral"),
    (1, "C. Arthur Tansley"),
    (1, "D. Charles Elton"),
    (2, "A. Arthur Tansley"),
    (2, "B. Ernesto Heneral"),
    (2, "C. Ernst Haeckel"),
    (2, "D. Charles Elton"),
    (3, "A. Habitat"),
    (3, "B. Ecosystem"),
    (3, "C. Biome"),
    (3, "D. Ecology"),
    (4, "A. Both A & B"),
    (4, "B. Abiotic Factors"),
    (4, "C. Biotic Factors"),
    (4, "D. None of the above"),
    (5, "A. Testing 5"),
    (5, "B. Testing 5"),
    (5, "C. Testing 5"),
    (5, "D. Testing 5"),
    (6, "A. Testing 6"),
    (6, "B. Testing 6"),
    (6, "C. Testing 6"),
    (6, "D. Testing 6"),
    (7, "A. Testing 7"),
    (7, "B. Testing 7"),
    (7, "C. Testing 7"),
    (7, "D. Testing 7"),
    (8, "A. Testing 8"),
    (8, "B. Testing 8"),
    (8, "C. Testing 8"),
    (8, "D. Testing 8"),
    (9, "A. Testing 9"),
    (9, "B. Testing 9"),
    (9, "C. Testing 9"),
    (9, "D. Testing 9"),
    (10, "A. Testing 10"),
    (10, "B. Testing 10"),
    (10, "C. Testing 10"),
    (10, "D. Testing 10")
;