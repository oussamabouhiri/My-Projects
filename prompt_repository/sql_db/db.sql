create database prompt_repository;
use prompt_repository;
create table users(
	id integer AUTO_INCREMENT PRIMARY KEY,
    name varchar(100),
    email varchar(100) UNIQUE,
    `password` varchar(255),
    created_at DATE
);

create table categories(
	id integer AUTO_INCREMENT PRIMARY KEY,
    name varchar(100)
);
create table prompts(
	id integer AUTO_INCREMENT PRIMARY KEY,
    title varchar(100),
    content text,
    user_id integer ,
    category_id integer,
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories (name) VALUES
('Code'),
('Marketing'),
('DevOps'),
('SQL'),
('AI'),
('Testing'),
('Security'),
('Frontend'),
('Backend'),
('Data Science');

INSERT INTO users (name, email, password, created_at) VALUES
('Ali Ahmed', 'ali@example.com', 'Ali@1234', '2026-03-20'),
('Sara Ben', 'sara@example.com', 'Sara@1234', '2026-03-21'),
('Youssef Karim', 'youssef@example.com', 'Youss@1234', '2026-03-21'),
('Nadia Amine', 'nadia@example.com', 'Nadia@1234', '2026-03-22'),
('Omar Hassan', 'omar@example.com', 'Omar@1234', '2026-03-22'),
('Lina Idrissi', 'lina@example.com', 'Lina@1234', '2026-03-23'),
('Karim Said', 'karim@example.com', 'Karim@1234', '2026-03-23'),
('Fatima Zahra', 'fatima@example.com', 'Fatima@1234', '2026-03-24'),
('Hassan Rami', 'hassan@example.com', 'Hassan@1234', '2026-03-24'),
('Salma Noor', 'salma@example.com', 'Salma@1234', '2026-03-25');

INSERT INTO prompts (title, content, user_id, category_id, created_at) VALUES
('Generate login system', 'Create a secure login system in PHP using sessions', 1, 1, '2026-03-25'),
('SEO strategy', 'Give me a full SEO plan for a tech startup', 2, 2, '2026-03-25'),
('Docker setup', 'Write a Dockerfile for a PHP MySQL app', 3, 3, '2026-03-24'),
('SQL optimization', 'Optimize this SQL query for better performance', 4, 4, '2026-03-24'),
('AI chatbot', 'Build a chatbot using OpenAI API in PHP', 5, 5, '2026-03-23'),
('Unit testing', 'Write PHPUnit tests for a login system', 6, 6, '2026-03-23'),
('Security audit', 'Check this PHP code for vulnerabilities', 7, 7, '2026-03-22'),
('Responsive UI', 'Create responsive navbar using CSS and Bootstrap', 8, 8, '2026-03-22'),
('REST API', 'Build a REST API in PHP with CRUD operations', 9, 9, '2026-03-21'),
('Data analysis', 'Analyze dataset using Python and pandas', 10, 10, '2026-03-21'),
('Form validation', 'Validate form inputs in PHP securely', 1, 1, '2026-03-20'),
('Email campaign', 'Create a marketing email campaign template', 2, 2, '2026-03-20'),
('CI/CD pipeline', 'Explain CI/CD pipeline using GitHub Actions', 3, 3, '2026-03-19'),
('Database schema', 'Design a scalable database schema for blog', 4, 4, '2026-03-19'),
('AI content writing', 'Generate blog post using AI tools', 5, 5, '2026-03-18');


ALTER TABLE users
ADD COLUMN role varchar(50) AFTER `password`;

UPDATE users SET role = 'admin' WHERE id IN (1, 2, 3, 4);
UPDATE users SET role = 'user' WHERE id IN (5, 6, 7, 8, 9, 10);