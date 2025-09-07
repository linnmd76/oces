-- OCES - CLEAN SEED with demo users (final)
-- Run after init.sql to populate demo courses and two demo accounts.

USE course_enrollment;

-- Sample courses
INSERT INTO courses (code, title, semester, capacity) VALUES
('CST101', 'Intro to Computing',      'fall',   2),
('CST201', 'Web Development Basics',  'fall',   2),
('CST316', 'Software Engineering',    'fall',   2),
('CST250', 'Databases',               'spring', 2),
('CST310', 'Networks',                'summer', 2);

-- Demo users (bcrypt password hashes provided)
-- demo1 / Password123!
-- demo2 / Secret456!
INSERT INTO users (username, password_hash, name, phone, email) VALUES
('demo1', '$2b$12$kYoj1PwazVHz91MWsHbVg.beQdqw6MKzThbu8gCLmlKWWXdj5yCkq', 'Demo One', '5551230001', 'demo1@example.com'),
('demo2', '$2b$12$Nm74Igj0RB7WVmxO32DvKeyqkwcsHgSHbuaBYadDS7dQiseqUKJnC', 'Demo Two', '5551230002', 'demo2@example.com');