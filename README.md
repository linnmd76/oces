# Online Course Enrollment System (OCES)

## Overview
The **Online Course Enrollment System (OCES)** is a PHP/MySQL web application built as a capstone project to demonstrate practical software engineering skills.  
It enables students to register accounts, browse available courses, enroll or waitlist, cancel enrollments, and receive in-app notifications.  

The project applies end-to-end software development practices, including:
- Requirements specification (SRS v1.2)
- UML design models
- Relational database schema
- PHP implementation
- Testing and refinement

This repository is intended as a portfolio artifact for instructors, reviewers, and potential employers.

---

## Features
- **User Registration/Login**: Secure authentication with bcrypt password hashing.
- **Course Management**: Browse courses by semester with seat limits.
- **Enrollment**: Enroll if seats are available; waitlist if the course is full.
- **Waitlist Auto-Enroll**: When a student cancels, the first waitlisted user is auto-enrolled.
- **Notifications**: In-app notifications alert users of enrollment status and changes.
- **Session Management**: Idle sessions expire after 20 minutes.

---

## Tech Stack
- **Frontend**: PHP, HTML, CSS
- **Backend**: PHP 8.x
- **Database**: MySQL (via XAMPP)
- **Web Server**: Apache (XAMPP)
- **Tools**: phpMyAdmin, Draw.io for UML diagrams

---

## Project Structure
- / (htdocs folder)
- auth.php
- dashboard.php
- db.php
- delete_course.php
- index.php
- list_courses.php
- login.php
- logout.php
- my_courses.php
- notifications.php
- register.php
- register_course.php
- security.php
- UML/ (UML diagrams and documentation)
- init.sql
- seed_with_users.sql
- README.md


---

## Setup Instructions

### 1. Install Dependencies
- Install **XAMPP** (Apache + MySQL).
- Ensure PHP and MySQL are running.

### 2. Configure Database
1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).  
2. Import `init.sql` (creates schema and tables).  
3. Import `seed_with_users.sql` (adds demo courses and demo accounts).

### 3. Deploy Application
1. Copy project files into `C:\xampp\htdocs\oces` (or similar).  
2. Start Apache and MySQL in XAMPP.  
3. Open browser > [http://localhost/oces](http://localhost/oces).

### 4. Demo Accounts
- **demo1 / Password123!**  
- **demo2 / Secret456!**

---

## Database Schema
- **users**: stores accounts (username, email, name, phone, hashed password).  
- **courses**: stores course info with semester + capacity.  
- **enrollment**: links users to courses with status.  
- **waitlist**: holds overflow enrollment with queue position.  
- **notifications**: stores in-app messages with read/unread state.  

SQL schema is in `init.sql`. Sample data is in `seed_with_users.sql`.

---

## UML Models
- **Use Case Diagram**: Shows system boundary and student interactions.  
- **Class Diagram**: Shows static structure with attributes and operations.  
- **Sequence Diagrams**: Enrollment and cancellation workflows.  
- **Activity Diagram**: Decision logic for seat availability vs. waitlist.  
- **State Diagram**: Course capacity states.  
- **Collaboration Diagram**: Object collaboration during enrollment.  
- **ER Diagram**: Logical schema.  

All diagrams are provided in `UML/` and included in the design document.

---

## Testing
- Manual functional testing of registration, login, enrollment, waitlist, auto-enroll, and notifications.  
- Session expiration validated at 20 minutes idle.  
- Database constraints tested for duplicates (unique keys and foreign keys).

---

## Future Improvements
- Admin dashboard for managing courses.  
- Email notifications in addition to in-app alerts.  
- Pagination and filtering for large course catalogs.  
- Unit testing with PHPUnit.

---

## Author
**Michael Linn**  
Bachelor of Science in Computer Software Technology (Software Development)  
Capstone Project, University of Arizona Global Campus (UAGC)  

---

## License
This project is provided as an academic portfolio artifact.  
Feel free to review, test, and learn from the implementation.  
