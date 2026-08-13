# Smart Academic Portal

A web-based academic management system designed to simplify and centralize academic activities such as course management, student enrollment, quizzes, grading, and performance tracking.

**Course:** CSE479 - Web Programming
**Section:** 03
**Semester:** Spring 2026
**Group:** 03

---

## Overview

The **Smart Academic Portal (Acad Portal)** is a role-based web application developed to provide a centralized platform for managing academic activities in an educational environment.

Traditional academic processes often require course management, assessments, grading, and student records to be handled manually or across multiple platforms. This project brings these activities together into a single web-based system.

The system provides dedicated dashboards and functionalities for three types of users:

* **Administrator**
* **Teacher**
* **Student**

The application is developed using **HTML, CSS, JavaScript, PHP, MySQL, and Apache Server through XAMPP**.

---

## Objectives

The main objectives of the Smart Academic Portal are:

* Develop a centralized academic management system
* Simplify course and classroom management
* Enable teachers to create and manage quizzes
* Allow students to participate in quizzes and track their progress
* Provide grade and performance monitoring
* Manage student enrollment
* Provide role-based access to different system functionalities
* Reduce manual academic workload
* Improve organization and accessibility of academic information

---

## Features

### Admin Features

The Administrator has the highest level of access and manages the overall system.

#### User Management

* Create user accounts
* View registered users
* Edit user information
* Delete user accounts
* Assign user roles
* Manage student and teacher accounts

#### Course Management

* Add courses
* Update course information
* Remove courses
* Manage course details
* Assign teachers to courses

#### Enrollment Management

* Enroll students into courses
* View current enrollments
* Remove students from courses

#### Dashboard

The Admin dashboard provides an overview of:

* Total users
* Total courses
* Recent registrations
* Overall system activity

---

### Teacher Features

Teachers can manage their academic content and monitor student performance.

#### Classroom and Course Management

* Create classrooms
* Link classrooms with courses
* Manage classroom content
* Organize course-related activities

#### Quiz Management

Teachers can:

* Create quizzes
* Add multiple-choice questions
* Define correct answers
* Assign marks to questions
* Set quiz time limits
* Associate quizzes with courses

#### Grade Management

Teachers can:

* Enter student grades
* Update grades
* Manage course-based academic results

#### Student Progress Monitoring

Teachers can:

* View individual student performance
* Check quiz attempt history
* View grade summaries
* Monitor student progress

#### Results

Teachers can view consolidated quiz results and grades for students enrolled in their courses.

---

### Student Features

Students can access their enrolled courses and academic assessment information.

#### Course Dashboard

Students can:

* View enrolled courses
* Access course-specific information
* Navigate between different subjects

#### Quiz Participation

Students can:

* View available quizzes
* Participate in quizzes
* Complete quizzes within the assigned time
* Submit quiz answers

The quiz system includes a countdown timer implemented using JavaScript.

#### Automated Quiz Scoring

After submitting a quiz:

* Answers are evaluated automatically
* The score is calculated
* The result is stored in the database

#### Progress Tracking

Students can:

* View quiz scores
* Review attempt history
* Track subject-wise performance

#### Grade Viewing

Students can view grades assigned by teachers for their enrolled courses.

---

## User Roles

| Role    | Main Responsibilities                                            |
| ------- | ---------------------------------------------------------------- |
| Admin   | User, course, and enrollment management                          |
| Teacher | Classroom, quiz, grade, and result management                    |
| Student | Course access, quiz participation, grades, and progress tracking |

The system uses role-based access control to ensure that users can only access the pages and features associated with their roles.

---

## Technology Stack

| Layer                   | Technology | Purpose                                  |
| ----------------------- | ---------- | ---------------------------------------- |
| Front-End               | HTML       | Page structure and semantic markup       |
| Front-End               | CSS        | Styling, layout, and responsive design   |
| Front-End               | JavaScript | Client-side interaction and quiz timer   |
| Back-End                | PHP        | Server-side logic, sessions, and routing |
| Database                | MySQL      | Data storage and retrieval               |
| Web Server              | Apache     | HTTP request handling and local hosting  |
| Development Environment | XAMPP      | Local Apache and MySQL environment       |

---

## Project Structure

The project follows a modular structure based on user roles and application functionality.

```text
samrt-academic-portal/
│
├── index.php
│
├── auth/
│   ├── login.php
│   └── logout.php
│
├── admin/
│   ├── ...
│   └── ...
│
├── teacher/
│   ├── ...
│   └── ...
│
├── student/
│   ├── ...
│   └── ...
│
├── config/
│   └── db.php
│
├── includes/
│   └── header.php
│
├── assets/
│   ├── style.css
│   └── quiz_timer.js
│
└── README.md
```

### Important Files and Directories

| File / Directory       | Description                                 |
| ---------------------- | ------------------------------------------- |
| `index.php`            | Main entry point of the application         |
| `auth/login.php`       | User authentication and login               |
| `auth/logout.php`      | Handles user logout and session destruction |
| `admin/`               | Administrator-related pages                 |
| `teacher/`             | Teacher-related pages                       |
| `student/`             | Student-related pages                       |
| `config/db.php`        | Database connection configuration           |
| `includes/header.php`  | Shared header and navigation                |
| `assets/style.css`     | Global stylesheet                           |
| `assets/quiz_timer.js` | Quiz countdown timer                        |

---

## Database Structure

The application uses MySQL as its relational database.

### Main Tables

| Table           | Description                                                |
| --------------- | ---------------------------------------------------------- |
| `users`         | Stores users, email, hashed passwords, and roles           |
| `courses`       | Stores course information                                  |
| `classrooms`    | Links teachers with courses                                |
| `enrollments`   | Maps students to enrolled courses                          |
| `quizzes`       | Stores quiz information and time limits                    |
| `questions`     | Stores quiz questions, options, correct answers, and marks |
| `quiz_attempts` | Stores student quiz submissions and scores                 |
| `grades`        | Stores teacher-assigned grades                             |

### Database Relationships

The main relationships include:

```text
Users
  │
  ├── Admin
  │
  ├── Teacher
  │     │
  │     ├── Courses
  │     │     └── Classrooms
  │     │
  │     └── Quizzes
  │           └── Questions
  │
  └── Student
        │
        ├── Enrollments
        │     └── Courses
        │
        ├── Quiz Attempts
        │     └── Quizzes
        │
        └── Grades
```

---

## Requirements

Before running the project, make sure the following software is installed:

* **XAMPP**
* **Apache**
* **MySQL**
* A modern web browser
* A code editor such as VS Code

PHP is included with XAMPP.

---

## Installation and Setup

### 1. Install XAMPP

Download and install XAMPP on your computer.

After installation, open the **XAMPP Control Panel**.

Start:

```text
Apache
MySQL
```

Both services should be running before accessing the application.

---

### 2. Clone the Repository

Open Command Prompt or PowerShell and navigate to your XAMPP `htdocs` directory.

For example:

```bash
cd C:\xampp\htdocs
```

Clone the repository:

```bash
git clone https://github.com/YOUR_USERNAME/smart-academic-portal.git
```

Then enter the project directory:

```bash
cd smart-academic-portal
```

---

### 3. Move the Project to XAMPP

If the repository was cloned somewhere else, copy the project folder into:

```text
C:\xampp\htdocs\
```

The final structure should look similar to:

```text
C:\xampp\htdocs\smart-academic-portal\
```

---

### 4. Create the Database

Open your browser and go to:

```text
http://localhost/phpmyadmin
```

Create a new MySQL database for the project.

For example:

```text
acadportal
```

Import the project's SQL database file if one is provided in the repository.

If the repository does not contain an SQL dump, the database tables must be created according to the project's database schema.

---

### 5. Configure Database Connection

Open:

```text
config/db.php
```

Configure the database credentials according to your local MySQL setup.

A typical XAMPP configuration is:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "acadportal";
```

Use the actual database name and credentials configured on your machine.

---

### 6. Start the Application

Make sure **Apache** and **MySQL** are running in XAMPP.

Then open:

```text
http://localhost/smart-academic-portal/
```

The application should load through the Apache server.

---

## Authentication

The system uses session-based authentication.

When a user logs in:

1. The login credentials are verified.
2. The user's role is identified.
3. PHP session variables store authentication information.
4. The user is redirected to the appropriate dashboard.
5. Access to restricted pages is controlled according to the user's role.

The three available roles are:

```text
Admin
Teacher
Student
```

---

## Application Flow

The general application flow is:

```text
                    ┌───────────────┐
                    │   Login Page  │
                    └───────┬───────┘
                            │
                            ▼
                  ┌───────────────────┐
                  │ Authentication    │
                  │ & Role Detection  │
                  └─────────┬─────────┘
                            │
              ┌─────────────┼─────────────┐
              │             │             │
              ▼             ▼             ▼
        ┌──────────┐  ┌──────────┐  ┌──────────┐
        │   Admin  │  │ Teacher  │  │ Student  │
        │ Dashboard│  │ Dashboard│  │ Dashboard│
        └────┬─────┘  └────┬─────┘  └────┬─────┘
             │             │             │
             ▼             ▼             ▼
        User/Course    Quiz/Grade    Course/Quiz
        Management     Management    & Progress
```

---

## Quiz System

The quiz system allows teachers to create assessments and students to participate in them.

### Teacher Workflow

```text
Create Course
     ↓
Create Classroom
     ↓
Create Quiz
     ↓
Add Questions
     ↓
Set Correct Answers
     ↓
Assign Marks
     ↓
Publish/Make Available
```

### Student Workflow

```text
View Course
     ↓
View Available Quiz
     ↓
Start Quiz
     ↓
Countdown Timer
     ↓
Answer Questions
     ↓
Submit Quiz
     ↓
Automatic Evaluation
     ↓
View Result
```

The quiz timer is implemented in:

```text
assets/quiz_timer.js
```

---

## Grading System

The portal supports both automated and manual academic evaluation.

### Automated Quiz Grading

Quiz answers are automatically evaluated after submission.

The system:

1. Checks submitted answers.
2. Compares them with the correct answers.
3. Calculates the obtained marks.
4. Stores the quiz attempt.
5. Displays the result to the student.

### Teacher-Assigned Grades

Teachers can manually enter or update grades for students on a course basis.

---

## Security and Access Control

The application uses role-based access control.

Each user receives access according to their assigned role.

For example:

```text
Admin
├── User Management
├── Course Management
└── Enrollment Management

Teacher
├── Classroom Management
├── Quiz Management
├── Grade Management
└── Student Progress

Student
├── Course Dashboard
├── Quiz Participation
├── Quiz Results
├── Grades
└── Progress Tracking
```

The system also uses PHP sessions to maintain authenticated user information.

---

## Testing

The project was tested across several areas:

* Functional correctness
* User interaction flow
* Database data handling
* Data storage and retrieval
* Role-based access control
* Module functionality
* Overall system performance

The testing process focused on ensuring that each role could access its intended functionality and that the major application modules operated correctly.

---

## Project Motivation

Academic activities such as course management, quiz conduction, grading, and progress tracking can become difficult when handled manually or across multiple platforms.

The Smart Academic Portal was developed to provide a unified platform where these academic activities can be managed in one place.

The project aims to:

* Reduce manual workload
* Centralize academic information
* Simplify course and quiz management
* Improve assessment management
* Allow students to track academic performance
* Improve accessibility of academic information

---

## Future Improvements

The current system provides the core functionality required for academic management. Possible future improvements include:

* Email notifications
* Assignment submission system
* Course material management
* Online attendance system
* Advanced analytics and performance visualization
* Announcement and messaging system
* Password reset through email
* Improved responsive design
* More advanced permission management
* Online deployment
* REST API integration
* Mobile application support

---

## Team

### Group 03

| Name                     | Student ID    |
| ------------------------ | ------------- |
| **Sams Bin Shahadat**    | 2023-1-60-267 |
| **Md. Ariful Islam Opi** | 2023-1-60-141 |
| **Md. Sabbir Hossain**   | 2022-3-60-024 |

**Course:** CSE479 - Web Programming
**Section:** 03
**Semester:** Spring 2026

---

## Academic Context

This project was developed as part of the **CSE479 - Web Programming** course.

The project demonstrates practical implementation of:

* Front-end web development
* Server-side programming
* PHP session management
* MySQL database management
* Role-based access control
* CRUD operations
* Quiz and assessment systems
* Automated result calculation
* Academic data management

---

## References

* PHP Documentation: https://www.php.net/manual/
* MySQL Reference Manual: https://dev.mysql.com/doc/
* Apache HTTP Server Documentation: https://httpd.apache.org/docs/
* MDN Web Docs: https://developer.mozilla.org/
* W3Schools: https://www.w3schools.com/
* XAMPP: https://www.apachefriends.org/

---

## License

This project was developed as an academic project for educational purposes.

Unless otherwise specified, the source code is intended for academic and learning use.
