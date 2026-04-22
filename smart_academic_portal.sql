-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 08:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_academic_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `code` varchar(20) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `code`, `teacher_id`) VALUES
(1, 'Web Programming', 'CSE301', 2),
(2, 'Database Management', 'CSE302', 2),
(3, 'Object Oriented Programming', 'CSE303', 8),
(4, 'Data Structures', 'CSE304', 9),
(5, 'Computer Networks', 'CSE305', 8),
(6, 'Machine Learning', 'CSE475', 11),
(7, 'Data Structure ', 'CSE302', 2),
(8, 'Data Structures', 'CSE305', 2);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 4, 1),
(7, 4, 2),
(8, 4, 3),
(9, 4, 4),
(10, 4, 5),
(11, 5, 1),
(12, 5, 2),
(13, 5, 3),
(14, 5, 4),
(15, 5, 5),
(16, 6, 1),
(17, 6, 2),
(18, 6, 3),
(19, 6, 4),
(20, 6, 5),
(21, 7, 1),
(22, 7, 2),
(23, 7, 3),
(24, 7, 4),
(25, 7, 5),
(26, 8, 1),
(27, 8, 2),
(28, 8, 3),
(29, 8, 4),
(30, 8, 5),
(31, 9, 1),
(32, 9, 2),
(33, 9, 3),
(34, 9, 4),
(35, 9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `quiz_avg` float DEFAULT 0,
  `manual_marks` float DEFAULT 0,
  `gpa` float DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `course_id`, `quiz_avg`, `manual_marks`, `gpa`, `updated_at`) VALUES
(1, 3, 1, 7.5, 99.5, 4, '2026-04-21 12:57:38'),
(2, 3, 2, 9, 2, 0, '2026-04-21 17:22:26'),
(3, 3, 3, 7, 75, 3, '2026-04-18 14:45:24'),
(4, 3, 4, 8.5, 88, 3.7, '2026-04-18 14:45:24'),
(5, 3, 5, 7.8, 80, 3.3, '2026-04-18 14:45:24'),
(6, 4, 1, 7.75, 80, 4, '2026-04-21 12:57:38'),
(7, 4, 2, 8, 76, 3.75, '2026-04-21 17:22:26'),
(8, 4, 3, 8.5, 85, 3.6, '2026-04-18 14:45:24'),
(9, 4, 4, 7, 72, 2.9, '2026-04-18 14:45:24'),
(10, 4, 5, 8.2, 83, 3.5, '2026-04-18 14:45:24'),
(11, 5, 1, 8, 90, 4, '2026-04-21 12:57:38'),
(12, 5, 2, 7, 87, 4, '2026-04-21 17:22:26'),
(13, 5, 3, 8, 82, 3.4, '2026-04-18 14:45:24'),
(14, 5, 4, 9.5, 95, 4, '2026-04-18 14:45:24'),
(15, 5, 5, 8.8, 89, 3.8, '2026-04-18 14:45:24'),
(16, 6, 1, 7.5, 68, 3.25, '2026-04-21 12:57:38'),
(17, 6, 2, 8, 72, 3.5, '2026-04-21 17:22:26'),
(18, 6, 3, 6, 65, 2.5, '2026-04-18 14:45:24'),
(19, 6, 4, 7.5, 76, 3.1, '2026-04-18 14:45:24'),
(20, 6, 5, 6.8, 70, 2.8, '2026-04-18 14:45:24'),
(21, 7, 1, 7.75, 92, 4, '2026-04-21 12:57:38'),
(22, 7, 2, 9, 90, 4, '2026-04-21 17:22:26'),
(23, 7, 3, 8.5, 86, 3.6, '2026-04-18 14:45:24'),
(24, 7, 4, 9, 91, 3.8, '2026-04-18 14:45:24'),
(25, 7, 5, 9.2, 93, 3.9, '2026-04-18 14:45:24'),
(26, 8, 1, 7, 60, 3, '2026-04-21 12:57:38'),
(27, 8, 2, 7, 63, 3, '2026-04-21 17:22:26'),
(28, 8, 3, 5, 55, 2.1, '2026-04-18 14:45:24'),
(29, 8, 4, 6.5, 67, 2.7, '2026-04-18 14:45:24'),
(30, 8, 5, 5.8, 61, 2.4, '2026-04-18 14:45:24'),
(31, 9, 1, 7.5, 83, 4, '2026-04-21 12:57:38'),
(32, 9, 2, 8, 20, 0, '2026-04-21 17:22:26'),
(33, 9, 3, 8.8, 88, 3.7, '2026-04-18 14:45:24'),
(34, 9, 4, 8, 81, 3.4, '2026-04-18 14:45:24'),
(35, 9, 5, 8.5, 85, 3.6, '2026-04-18 14:45:24'),
(36, 3, 1, 7.5, 99.5, 4, '2026-04-21 12:57:38'),
(37, 4, 1, 7.75, 80, 4, '2026-04-21 12:57:38'),
(38, 5, 1, 8, 90, 4, '2026-04-21 12:57:38'),
(39, 6, 1, 7.5, 68, 3.25, '2026-04-21 12:57:38'),
(40, 7, 1, 7.75, 92, 4, '2026-04-21 12:57:38'),
(41, 8, 1, 7, 60, 3, '2026-04-21 12:57:38'),
(42, 9, 1, 7.5, 83, 4, '2026-04-21 12:57:38'),
(43, 3, 1, 7.5, 99.5, 4, '2026-04-21 12:57:38'),
(44, 4, 1, 7.75, 80, 4, '2026-04-21 12:57:38'),
(45, 5, 1, 8, 90, 4, '2026-04-21 12:57:38'),
(46, 6, 1, 7.5, 68, 3.25, '2026-04-21 12:57:38'),
(47, 7, 1, 7.75, 92, 4, '2026-04-21 12:57:38'),
(48, 8, 1, 7, 60, 3, '2026-04-21 12:57:38'),
(49, 9, 1, 7.5, 83, 4, '2026-04-21 12:57:38'),
(50, 3, 1, 7.5, 99.5, 4, '2026-04-21 12:57:38'),
(51, 4, 1, 7.75, 80, 4, '2026-04-21 12:57:38'),
(52, 5, 1, 8, 90, 4, '2026-04-21 12:57:38'),
(53, 6, 1, 7.5, 68, 3.25, '2026-04-21 12:57:38'),
(54, 7, 1, 7.75, 92, 4, '2026-04-21 12:57:38'),
(55, 8, 1, 7, 60, 3, '2026-04-21 12:57:38'),
(56, 9, 1, 7.5, 83, 4, '2026-04-21 12:57:38'),
(57, 3, 1, 7.5, 99.5, 4, '2026-04-21 12:57:38'),
(58, 4, 1, 7.75, 80, 4, '2026-04-21 12:57:38'),
(59, 5, 1, 8, 90, 4, '2026-04-21 12:57:38'),
(60, 6, 1, 7.5, 68, 3.25, '2026-04-21 12:57:38'),
(61, 7, 1, 7.75, 92, 4, '2026-04-21 12:57:38'),
(62, 8, 1, 7, 60, 3, '2026-04-21 12:57:38'),
(63, 9, 1, 7.5, 83, 4, '2026-04-21 12:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) NOT NULL,
  `marks` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `marks`) VALUES
(1, 1, 'What does HTML stand for?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Hyper Transfer Markup Language', 'Home Tool Markup Language', 'A', 1),
(2, 1, 'Which tag is used for the largest heading?', '<h6>', '<heading>', '<h1>', '<head>', 'C', 1),
(3, 1, 'Which tag creates a hyperlink?', '<link>', '<a>', '<href>', '<url>', 'B', 1),
(4, 1, 'What tag is used for an unordered list?', '<ol>', '<list>', '<ul>', '<li>', 'C', 1),
(5, 1, 'Which attribute specifies the image source?', 'href', 'link', 'src', 'url', 'C', 1),
(6, 1, 'Which tag defines a table row?', '<td>', '<th>', '<table>', '<tr>', 'D', 1),
(7, 1, 'What does CSS stand for?', 'Computer Style Sheets', 'Cascading Style Sheets', 'Creative Style Syntax', 'Colorful Style Sheets', 'B', 1),
(8, 1, 'Which HTML tag is used for a line break?', '<lb>', '<break>', '<br>', '<newline>', 'C', 1),
(9, 1, 'Which tag defines the document body?', '<html>', '<head>', '<body>', '<main>', 'C', 1),
(10, 1, 'Which attribute is used to identify an HTML element uniquely?', 'class', 'name', 'id', 'style', 'C', 1),
(11, 2, 'Which property changes the text color in CSS?', 'font-color', 'text-color', 'color', 'foreground', 'C', 1),
(12, 2, 'Which property sets the background color?', 'bg-color', 'background-color', 'color-background', 'bgcolor', 'B', 1),
(13, 2, 'How do you select an element with id \"header\"?', '.header', '#header', 'header', '*header', 'B', 1),
(14, 2, 'How do you select elements with class \"menu\"?', '#menu', 'menu', '.menu', '*menu', 'C', 1),
(15, 2, 'Which property controls the text size?', 'text-size', 'font-size', 'text-style', 'font-style', 'B', 1),
(16, 2, 'What is the default display value of a div?', 'inline', 'block', 'flex', 'inline-block', 'B', 1),
(17, 2, 'Which property adds space inside an element border?', 'margin', 'spacing', 'padding', 'border-spacing', 'C', 1),
(18, 2, 'Which property adds space outside an element border?', 'padding', 'margin', 'spacing', 'outline', 'B', 1),
(19, 2, 'Which value of position makes element relative to viewport?', 'relative', 'absolute', 'fixed', 'static', 'C', 1),
(20, 2, 'Which property makes text bold?', 'font-weight: bold', 'text-weight: bold', 'font-bold: true', 'text-style: bold', 'A', 1),
(21, 3, 'Which keyword declares a variable in modern JS?', 'var', 'let', 'define', 'variable', 'B', 1),
(22, 3, 'Which method shows a popup in JS?', 'popup()', 'alert()', 'show()', 'dialog()', 'B', 1),
(23, 3, 'Which symbol is used for single line comments in JS?', '<!--', '//', '**', '##', 'B', 1),
(24, 3, 'How do you write an IF statement in JS?', 'if i = 5 then', 'if (i == 5)', 'if i == 5', 'if (i = 5) then', 'B', 1),
(25, 3, 'Which method adds an element to end of an array?', 'push()', 'add()', 'append()', 'insert()', 'A', 1),
(26, 3, 'How do you select an element by ID in JS?', 'document.getElement(\"id\")', 'document.querySelector(\".id\")', 'document.getElementById(\"id\")', 'document.findById(\"id\")', 'C', 1),
(27, 3, 'What does DOM stand for?', 'Document Object Model', 'Data Object Model', 'Document Oriented Model', 'Data Oriented Module', 'A', 1),
(28, 3, 'Which operator checks value AND type equality?', '==', '=', '===', '!==', 'C', 1),
(29, 3, 'How do you declare a function in JS?', 'def myFunc()', 'function myFunc()', 'func myFunc()', 'create myFunc()', 'B', 1),
(30, 3, 'Which event fires when a button is clicked?', 'onhover', 'onchange', 'onclick', 'onfocus', 'C', 1),
(31, 4, 'What does PHP stand for?', 'Personal Home Page', 'PHP Hypertext Preprocessor', 'Private Home Protocol', 'Public Hypertext Processor', 'B', 1),
(32, 4, 'Which tag starts PHP code?', '<%', '<php>', '<?php', '<script>', 'C', 1),
(33, 4, 'How do you declare a variable in PHP?', 'var name', '#name', '$name', '@name', 'C', 1),
(34, 4, 'Which function outputs text in PHP?', 'print_text()', 'console.log()', 'echo', 'output()', 'C', 1),
(35, 4, 'Which superglobal handles form POST data?', '$_GET', '$_POST', '$_FORM', '$_DATA', 'B', 1),
(36, 4, 'How do you start a session in PHP?', 'start_session()', 'session_start()', 'new Session()', 'session()', 'B', 1),
(37, 4, 'Which function connects to MySQL in PHP?', 'mysql_open()', 'db_connect()', 'mysqli_connect()', 'connect_db()', 'C', 1),
(38, 4, 'Which symbol is used for string concatenation in PHP?', '+', '&', '.', ',', 'C', 1),
(39, 4, 'How do you write a single line comment in PHP?', '<!--', '//', '**', '##', 'B', 1),
(40, 4, 'Which function gets the length of a string in PHP?', 'length()', 'str_len()', 'strlen()', 'string_length()', 'C', 1),
(41, 5, 'Which SQL command retrieves data?', 'GET', 'FETCH', 'SELECT', 'RETRIEVE', 'C', 1),
(42, 5, 'Which SQL command inserts new data?', 'ADD', 'INSERT INTO', 'PUT', 'APPEND', 'B', 1),
(43, 5, 'Which SQL command updates existing data?', 'MODIFY', 'CHANGE', 'UPDATE', 'ALTER', 'C', 1),
(44, 5, 'Which SQL command deletes data?', 'REMOVE', 'DROP', 'ERASE', 'DELETE', 'D', 1),
(45, 5, 'Which clause filters rows in SQL?', 'FILTER', 'HAVING', 'WHERE', 'LIMIT', 'C', 1),
(46, 5, 'Which clause sorts results in SQL?', 'SORT BY', 'ORDER BY', 'GROUP BY', 'ARRANGE BY', 'B', 1),
(47, 5, 'What does PRIMARY KEY mean?', 'First column in table', 'Unique identifier for each row', 'Most important column', 'Encrypted column', 'B', 1),
(48, 5, 'What does FOREIGN KEY do?', 'Encrypts data', 'Links two tables together', 'Creates index', 'Speeds up queries', 'B', 1),
(49, 5, 'Which SQL function counts rows?', 'TOTAL()', 'SUM()', 'COUNT()', 'NUM()', 'C', 1),
(50, 5, 'Which JOIN returns all rows from both tables?', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'FULL OUTER JOIN', 'D', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `time_limit` int(11) DEFAULT 30,
  `total_marks` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `title`, `time_limit`, `total_marks`) VALUES
(1, 1, 'HTML Basics Quiz', 20, 10),
(2, 1, 'CSS Fundamentals Quiz', 20, 10),
(3, 1, 'JavaScript Basics Quiz', 30, 10),
(4, 1, 'PHP Introduction Quiz', 30, 10),
(5, 2, 'SQL Basics Quiz', 20, 10),
(6, 2, 'Normalization Quiz', 25, 10),
(7, 2, 'Joins & Relations Quiz', 30, 10),
(8, 3, 'OOP Concepts Quiz', 20, 10),
(9, 3, 'Inheritance Quiz', 25, 10),
(10, 3, 'Polymorphism Quiz', 25, 10),
(11, 4, 'Arrays & Linked Lists Quiz', 20, 10),
(12, 4, 'Stacks & Queues Quiz', 25, 10),
(13, 5, 'OSI Model Quiz', 20, 10),
(14, 5, 'TCP/IP Quiz', 25, 10);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `score`, `attempted_at`) VALUES
(1, 1, 3, 9, '2026-04-18 14:45:24'),
(2, 1, 4, 7, '2026-04-18 14:45:24'),
(3, 1, 5, 8, '2026-04-18 14:45:24'),
(4, 1, 6, 6, '2026-04-18 14:45:24'),
(5, 1, 7, 10, '2026-04-18 14:45:24'),
(6, 1, 8, 5, '2026-04-18 14:45:24'),
(7, 1, 9, 8, '2026-04-18 14:45:24'),
(8, 2, 3, 8, '2026-04-18 14:45:24'),
(9, 2, 4, 9, '2026-04-18 14:45:24'),
(10, 2, 5, 7, '2026-04-18 14:45:24'),
(11, 2, 6, 8, '2026-04-18 14:45:24'),
(12, 2, 7, 6, '2026-04-18 14:45:24'),
(13, 2, 8, 9, '2026-04-18 14:45:24'),
(14, 2, 9, 7, '2026-04-18 14:45:24'),
(15, 3, 3, 7, '2026-04-18 14:45:24'),
(16, 3, 4, 8, '2026-04-18 14:45:24'),
(17, 3, 5, 9, '2026-04-18 14:45:24'),
(18, 3, 6, 7, '2026-04-18 14:45:24'),
(19, 3, 7, 8, '2026-04-18 14:45:24'),
(20, 3, 8, 6, '2026-04-18 14:45:24'),
(21, 3, 9, 9, '2026-04-18 14:45:24'),
(22, 4, 3, 6, '2026-04-18 14:45:24'),
(23, 4, 4, 7, '2026-04-18 14:45:24'),
(24, 4, 5, 8, '2026-04-18 14:45:24'),
(25, 4, 6, 9, '2026-04-18 14:45:24'),
(26, 4, 7, 7, '2026-04-18 14:45:24'),
(27, 4, 8, 8, '2026-04-18 14:45:24'),
(28, 4, 9, 6, '2026-04-18 14:45:24'),
(29, 5, 3, 9, '2026-04-18 14:45:24'),
(30, 5, 4, 8, '2026-04-18 14:45:24'),
(31, 5, 5, 7, '2026-04-18 14:45:24'),
(32, 5, 6, 8, '2026-04-18 14:45:24'),
(33, 5, 7, 9, '2026-04-18 14:45:24'),
(34, 5, 8, 7, '2026-04-18 14:45:24'),
(35, 5, 9, 8, '2026-04-18 14:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@acad.com', '0192023a7bbd73250516f069df18b500', 'admin', '2026-04-18 08:25:07'),
(2, 'Mr. Karim', 'teacher@acad.com', 'b96a660dba3176b85743eb7b28eb03e5', 'teacher', '2026-04-18 08:25:07'),
(3, 'Alice', 'student@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 08:25:07'),
(4, 'Bob Hassan', 'bob@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(5, 'Carol Ahmed', 'carol@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(6, 'David Islam', 'david@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(7, 'Emma Khatun', 'emma@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(8, 'Fahim Hossain', 'fahim@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(9, 'Gina Akter', 'gina@acad.com', '32250170a0dca92d53ec9624f336ca24', 'student', '2026-04-18 14:45:24'),
(10, 'Mr. Hasan', 'hasan@acad.com', 'b96a660dba3176b85743eb7b28eb03e5', 'teacher', '2026-04-18 14:45:24'),
(11, 'Ms. Sultana', 'sultana@acad.com', 'b96a660dba3176b85743eb7b28eb03e5', 'teacher', '2026-04-18 14:45:24'),
(12, 'xas', 'abcd111@acad.com', 'e10adc3949ba59abbe56e057f20f883e', 'student', '2026-04-21 17:16:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`),
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
