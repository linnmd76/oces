-- OCES - CLEAN INIT (final)
-- Creates a fresh schema with tidy names and indexes. Run once, then (optionally) seed_with_users.sql.

CREATE DATABASE IF NOT EXISTS course_enrollment
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE course_enrollment;

-- Drop in dependency order (children first), then recreate clean.
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS waitlist;
DROP TABLE IF EXISTS enrollment;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

-- USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY ux_users_username (username),
  UNIQUE KEY ux_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- COURSES
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL,
  title VARCHAR(255) NOT NULL,
  semester ENUM('spring','summer','fall') NOT NULL,
  capacity INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_courses_code_sem (code, semester)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ENROLLMENT
CREATE TABLE enrollment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  status ENUM('enrolled','dropped') NOT NULL DEFAULT 'enrolled',
  enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_enrollment_user_course (user_id, course_id),
  KEY ix_enrollment_course_status (course_id, status),
  CONSTRAINT fk_enroll_user  FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
  CONSTRAINT fk_enroll_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- WAITLIST
CREATE TABLE waitlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  position INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_waitlist_user_course (user_id, course_id),
  KEY ix_waitlist_course_position (course_id, position),
  CONSTRAINT fk_wait_user  FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
  CONSTRAINT fk_wait_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NOTIFICATIONS (in-app)
CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` BOOLEAN NOT NULL DEFAULT 0,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
