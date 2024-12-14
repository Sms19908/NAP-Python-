CREATE DATABASE tarea4;

USE tarea4;

CREATE TABLE users (
	id int auto_increment Primary key,
    username VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE tasks (
	task_id INT Primary key auto_increment,
    user_id INT,
    title VARCHAR(255),
    description TEXT,
    due_date DATE
);

CREATE TABLE comments (
	comment_id INT,
	task_id INT ,
    user_id INT,
    comment varchar(255)
);

Drop table comments;

-- Inserts for the users table
-- INSERT INTO users (username, password) VALUES ('alice@test.com', 'password123');
-- INSERT INTO users (username, password) VALUES ('bob@test.com', 'securepassword');
-- INSERT INTO users (username, password) VALUES ('charlie@test.com', 'charlie2024');
-- INSERT INTO users (username, password) VALUES ('diana@test.com', 'diana456');
-- INSERT INTO users (username, password) VALUES ('edward@test.com', 'edward789');

-- INSERT INTO users (username, password) VALUES ('alice@test.com', '$2y$10$1OJjxf.LKwr42bMvDkpNmOECE7zZ/xLoK9Hbg3jCRuUnkJ78lOBY.');
-- INSERT INTO users (username, password) VALUES ('bob@test.com', '$2y$10$tp3Df3qkG.Sktc5L72FsZeiWmFRU1G0rLU/viBLpVg.W3uHq7Yg3e');
-- INSERT INTO users (username, password) VALUES ('charlie@test.com', '$2y$10$gi2ESFhLn63PjqsXElx7SuMLtG2cbdGpoA6pLs.3QbxRm7ltBPrie');
-- INSERT INTO users (username, password) VALUES ('diana@test.com', '$2y$10$u7z9GGE5pSkik1lLaK7BPe2Qmzz9dbUomfPHh0O5PHt/JEFH4ctRG');
-- INSERT INTO users (username, password) VALUES ('edward@test.com', '$2y$10$AKk/.RtMXRrn3NeEkIr4F.LlZY.RHG8M2HFc.vl71jwbWCF.tKck6');


-- Inserts for the comments table
INSERT INTO tasks (user_id, title, description, due_date) VALUES (1,'Comment 1', 'This is the first comment', '2024-12-12');
INSERT INTO tasks (user_id, title, description, due_date) VALUES (2,'Comment 2', 'This is the second comment', '2024-12-13');
INSERT INTO tasks (user_id, title, description, due_date) VALUES (3,'Comment 3', 'This is the third comment', '2024-12-14');
INSERT INTO tasks (user_id, title, description, due_date) VALUES (4,'Comment 4', 'This is the fourth comment', '2024-12-15');
INSERT INTO tasks (user_id, title, description, due_date) VALUES (5,'Comment 5', 'This is the fifth comment', '2024-12-16');


SELECT * FROM tarea4.users;
SELECT * FROM tarea4.tasks;