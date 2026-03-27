-- ============================================================
-- Prompt Repository — Database Schema & Seed Data
-- DevGenius Solutions
-- ============================================================

CREATE DATABASE IF NOT EXISTS prompt_repository
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE prompt_repository;

-- ── DROP ORDER (respect FK) ──────────────────────────────────
DROP TABLE IF EXISTS prompts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- ── USERS ────────────────────────────────────────────────────
CREATE TABLE users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at DATETIME     NOT NULL DEFAULT NOW()
);

-- ── CATEGORIES ───────────────────────────────────────────────
CREATE TABLE categories (
    id   INT          AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- ── PROMPTS ──────────────────────────────────────────────────
CREATE TABLE prompts (
    id          INT  AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200) NOT NULL,
    content     TEXT         NOT NULL,
    user_id     INT          NOT NULL,
    category_id INT          NOT NULL,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_prompt_user     FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    CONSTRAINT fk_prompt_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- ── SEED: CATEGORIES ─────────────────────────────────────────
INSERT INTO categories (name) VALUES
('Code'),
('SQL'),
('DevOps'),
('Marketing'),
('Documentation'),
('Testing'),
('Security');

-- ── SEED: USERS ──────────────────────────────────────────────
-- To generate a hash run: php -r "echo password_hash('DevGenius@2024', PASSWORD_DEFAULT);"
-- Replace each HASH_PLACEHOLDER below with the generated hash.
-- Plain-text password for ALL seed accounts: DevGenius@2024

INSERT INTO users (name, email, password, role) VALUES
('Admin',        'admin@devgenius.io', 'HASH_PLACEHOLDER', 'admin'),
('Alice Martin', 'alice@devgenius.io', 'HASH_PLACEHOLDER', 'user'),
('Bob Dupont',   'bob@devgenius.io',   'HASH_PLACEHOLDER', 'user'),
('Clara Petit',  'clara@devgenius.io', 'HASH_PLACEHOLDER', 'user');

-- ── SEED: PROMPTS ────────────────────────────────────────────
INSERT INTO prompts (title, content, user_id, category_id, created_at) VALUES
(
  'Generate a REST API in PHP',
  'Act as a senior PHP developer. Generate a complete RESTful API for a [resource] with endpoints for CRUD operations. Use PDO for database interaction and return JSON responses. Include proper HTTP status codes (200, 201, 400, 404, 500) and robust input validation.',
  2, 1, NOW()
),
(
  'Write an Optimized SQL Stored Procedure',
  'Act as a database expert. Write an optimized MySQL stored procedure for [task]. Include error handling with SIGNAL SQLSTATE, use transactions where necessary, and comment each section. Follow best practices for index usage and query performance.',
  3, 2, NOW()
),
(
  'Docker Compose Multi-Service Setup',
  'Act as a DevOps engineer. Create a production-ready docker-compose.yml for a [LAMP / MEAN / etc.] stack. Include: web server, database, caching layer. Configure named volumes for data persistence, set up bridge networking, and provide an .env template.',
  4, 3, NOW()
),
(
  'SEO Blog Post Outline Generator',
  'Act as a senior content strategist. Create a detailed SEO-optimized outline for "[topic]". Provide: H1 title, meta description ≤160 chars, H2/H3 structure, target keywords, internal linking suggestions, and a strong CTA. Target audience: [audience].',
  2, 4, NOW()
),
(
  'OpenAPI Documentation Template',
  'Act as a technical writer. Generate full OpenAPI 3.0 documentation for this endpoint: [endpoint details]. Include: description, all parameters, request body schema, success response schemas, error schemas, and usage examples in cURL and JavaScript fetch.',
  3, 5, NOW()
),
(
  'PHPUnit Test Suite Generator',
  'Act as a QA engineer. Write comprehensive PHPUnit tests for this PHP class/function: [code]. Cover happy-path, edge cases, error scenarios, and input validation. Use mocking where appropriate and follow the AAA pattern (Arrange, Act, Assert).',
  4, 6, NOW()
),
(
  'SQL Query Performance Audit',
  'Analyze this SQL query and propose optimizations: [query]. Consider: index usage, JOIN vs subquery efficiency, SELECT * avoidance, and execution plan analysis. Return the optimized query with a brief explanation of each change.',
  2, 2, NOW()
),
(
  'PHP Code Security Review',
  'Act as a cybersecurity expert. Review this PHP code for vulnerabilities: [code]. Check for: SQL injection, XSS, CSRF, insecure session handling, sensitive data exposure, and improper input validation. Rate each finding (Critical / High / Medium / Low) and suggest concrete fixes.',
  3, 7, NOW()
);