INSERT INTO Account(userName, hashPassword, salt, fName, mName, lName, email) VALUES
    ("Kenji667", "", "", "Kenji", "", "Gabunada", "kgabunada@kld.edu.ph")
;

INSERT INTO Subject(subject_ID, subjectName) VALUES
    ("GEC9100", "Filipino 1"),
    ("PCIS2219", "Business Law"),
    ("GEC2000", "Ethics"),
    ("PE2204", "PATHFIT 4"),
    ("PCIS2209", "Organization and Management Concepts"),
    ("CCIS2205", "Information Management Lec"),
    ("CCIS2205L", "Information Management Lab"),
    ("PCIS2205", "Systems Analysis and Design"),
    ("PCIS2206", "Business Process Design and Management"),
    ("GEE5000", "People, Earth and Ecosystem")
;

INSERT INTO Section(section_ID, course) VALUES
    ("BSIS201", "Bachelor Science in Information Systems"),
    ("BSIS206", "Bachelor Science in Information Systems")
;

INSERT INTO Student(student_ID, userName) VALUES
    ("KLD-22-000247", "Kenji667")
;