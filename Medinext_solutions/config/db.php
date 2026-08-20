<?php
/**
 * MEDINEXT SOLUTIONS - Database Configuration
 * 
 * PDO database connection with error handling
 * PHP 8.2 compatible
 */

declare(strict_types=1);

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'u551698081_if0_41305392_d');
define('DB_USER', 'u551698081_if0_41305392');
define('DB_PASS', 'Khushal77');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection
 * 
 * @return PDO
 * @throws PDOException
 */
function getDBConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new PDOException('Database connection failed. Please try again later.');
        }
    }

    return $pdo;
}

// PHPMailer Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'info@medinextsolutions.com');
define('SMTP_PASS', 'vbsownwuqbhqeooh');
define('SMTP_FROM_EMAIL', 'noreply@medinextsolutions.com');
define('SMTP_FROM_NAME', 'MEDINEXT SOLUTIONS Site');
define('SMTP_TO_EMAIL', 'info@medinextsolutions.com'); // Target Inbox

// Site Configuration
define('SITE_NAME', 'MEDINEXT SOLUTIONS');
define('SITE_URL', 'https://medinextsolutions.com');
define('SITE_TAGLINE', 'Your Trusted Partner in Revenue Cycle Management.');