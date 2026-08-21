-- ============================================================
-- MEDINEXT SOLUTIONS - Database Schema
-- MySQL 8.0+ compatible
-- ============================================================

-- Database is created via InfinityFree Panel. 
-- Just import this file into the selected database in phpMyAdmin.

-- ============================================================
-- Contact Form Submissions
-- ============================================================
DROP TABLE IF EXISTS `contact_submissions`;
CREATE TABLE `contact_submissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `practice_name` VARCHAR(150) DEFAULT NULL,
    `specialty` VARCHAR(100) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `is_responded` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Practice Revenue Audit Submissions
-- ============================================================
DROP TABLE IF EXISTS `audit_submissions`;
CREATE TABLE `audit_submissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `practice_name` VARCHAR(150) NOT NULL,
    `contact_name` VARCHAR(100) NOT NULL,
    `job_title` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `street_address` VARCHAR(255) DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(50) DEFAULT NULL,
    `zip_code` VARCHAR(20) DEFAULT NULL,
    `specialty` VARCHAR(100) NOT NULL,
    `patient_volume` VARCHAR(100) NOT NULL,
    `monthly_revenue` VARCHAR(100) NOT NULL,
    `current_ehr` VARCHAR(100) DEFAULT NULL,
    `pain_points` JSON DEFAULT NULL,
    `additional_notes` TEXT DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `lead_ref_id` VARCHAR(50) DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `status` ENUM('new', 'in_review', 'audit_scheduled', 'completed', 'archived') DEFAULT 'new',
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_audit_email` (`email`),
    INDEX `idx_audit_specialty` (`specialty`),
    INDEX `idx_audit_status` (`status`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Newsletter Subscribers
-- ============================================================
DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `is_active` TINYINT(1) DEFAULT 1,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` TIMESTAMP NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    INDEX `idx_email` (`email`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Consultation Requests
-- ============================================================
DROP TABLE IF EXISTS `consultation_requests`;
CREATE TABLE `consultation_requests` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `practice_name` VARCHAR(150) DEFAULT NULL,
    `specialty` VARCHAR(100) DEFAULT NULL,
    `preferred_date` DATE DEFAULT NULL,
    `preferred_time` VARCHAR(20) DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'contacted', 'scheduled', 'completed', 'cancelled') DEFAULT 'pending',
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Activity Log (for tracking all form submissions)
-- ============================================================
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `action_type` VARCHAR(50) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `metadata` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_action_type` (`action_type`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;