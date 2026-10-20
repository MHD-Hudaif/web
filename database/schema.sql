-- AL MAQRA (المقرأ) Database Schema
-- Compatible with MySQL and MariaDB

CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'admin',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `category` VARCHAR(100) DEFAULT 'Quran',
    `description` TEXT,
    `duration` VARCHAR(100) DEFAULT '3 - 6 Months',
    `level` VARCHAR(100) DEFAULT 'Beginner to Advanced',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teachers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(150) NOT NULL,
    `gender` VARCHAR(20) DEFAULT 'Male',
    `email` VARCHAR(150),
    `phone` VARCHAR(50),
    `qualification` VARCHAR(255),
    `specialization` VARCHAR(255),
    `languages` VARCHAR(255) DEFAULT 'Malayalam, English, Arabic',
    `status` VARCHAR(50) DEFAULT 'active',
    `bio` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(150) NOT NULL,
    `age` INT DEFAULT NULL,
    `gender` VARCHAR(20) DEFAULT 'Male',
    `parent_name` VARCHAR(150) DEFAULT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `country` VARCHAR(100) DEFAULT 'India',
    `course_id` INT DEFAULT NULL,
    `course_title` VARCHAR(150) DEFAULT NULL,
    `preferred_time` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT,
    `status` VARCHAR(50) DEFAULT 'trial_requested',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `classes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `course_id` INT DEFAULT NULL,
    `teacher_id` INT DEFAULT NULL,
    `schedule_days` VARCHAR(100) DEFAULT 'Mon, Wed, Fri',
    `time_slot` VARCHAR(100) DEFAULT '07:00 PM - 07:45 PM',
    `meeting_link` VARCHAR(255) DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `max_students` INT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_course_id` (`course_id`),
    INDEX `idx_teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `class_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `enrolled_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_class_student` (`class_id`, `student_id`),
    INDEX `idx_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
