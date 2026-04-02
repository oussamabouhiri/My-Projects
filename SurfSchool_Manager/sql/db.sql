CREATE DATABASE surf_shcool;
Use surf_shcool;

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY, 
    email varchar(100),
    `password` varchar(100),
    role ENUM('admin','student') NOT NULL default 'student'
); 

CREATE TABLE students(
	id INT AUTO_INCREMENT PRIMARY KEY, 
    name varchar(50),
    country varchar(100),
    `level` ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)

CREATE TABLE lessons(
	id int AUTO_INCREMENT PRIMARY KEY,
    title varchar(150),
    coach varchar(50),
    `date` datetime
)

CREATE TABLE lesson_student(
	lesson_id INT,
    student_id INT,
    payment_status ENUM('paid','pending') Not null default 'pending',
    FOREIGN KEY (lesson_id) REFERENCES lessons(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
)