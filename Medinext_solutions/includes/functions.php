<?php
/**
 * MEDINEXT SOLUTIONS - Helper Functions
 * 
 * Production-grade security, sanitization, validation, database persistence,
 * flat-file fallback logging, two-tier email notifications, and response utilities.
 * PHP 8.2+ compatible.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

/**
 * 1. Sanitize input string (strips null bytes, control characters, encodes HTML entities)
 */
function sanitizeInput(string $input): string
{
    // Strip null-byte characters and dangerous ASCII control characters (\x01-\x08, \x0B-\x0C, \x0E-\x1F)
    $clean = str_replace("\0", '', $input);
    $clean = preg_replace('/[\x01-\x08\x0B-\x0C\x0E-\x1F]/', '', $clean);
    return htmlspecialchars(trim((string)$clean), ENT_QUOTES, 'UTF-8');
}

/**
 * 2. Validate email address (RFC 5322, CRLF / header injection rejection, length constraint)
 */
function isValidEmail(string $email): bool
{
    $email = trim($email);
    if (strlen($email) === 0 || strlen($email) > 255) {
        return false;
    }
    // Reject CRLF header injection and URL-encoded line breaks
    if (preg_match('/[\r\n]|%0a|%0d/i', $email)) {
        return false;
    }
    // Reject multi-recipient smuggling
    if (strpos($email, ',') !== false || strpos($email, ';') !== false) {
        return false;
    }
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 3. Validate dialable phone number (10-15 digits, rejects alphabetic chars)
 */
function isValidPhone(string $phone): bool
{
    $phone = trim($phone);
    if (preg_match('/[a-zA-Z]/', $phone)) {
        return false;
    }
    $digits = preg_replace('/[^0-9]/', '', $phone);
    return strlen($digits) >= 10 && strlen($digits) <= 15;
}

/**
 * 4. Validate 5-digit or 9-digit US Postal ZIP Code
 */
function isValidZip(string $zip): bool
{
    return (bool) preg_match('/^\d{5}(-\d{4})?$/', trim($zip));
}

/**
 * 5. Validate 2-letter US Postal State / Jurisdiction Code
 */
function isValidState(string $state): bool
{
    $state = strtoupper(trim($state));
    $validStates = [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL',
        'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME',
        'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH',
        'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR',
        'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV',
        'WI', 'WY'
    ];
    return in_array($state, $validStates, true);
}

/**
 * 6. Get client IP address resolving proxies and cloud headers safely
 */
function getClientIP(): string
{
    $headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', (string)$_SERVER[$header]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '0.0.0.0';
}

/**
 * 7. Generate cryptographically secure 64-char hex CSRF token
 */
function generateCSRFToken(): string
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || strlen($_SESSION['csrf_token']) !== 64 || !ctype_xdigit($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * 8. Validate CSRF token using constant-time hash_equals
 */
function validateCSRFToken(?string $token): bool
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    if (empty($token) || !is_string($token) || strlen($token) !== 64) {
        return false;
    }
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || strlen($_SESSION['csrf_token']) !== 64) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * 9. Rate limiting check (DB-backed with session and file fallback)
 */
function isRateLimited(string $actionType, int $maxAttempts = 5, int $windowMinutes = 15): bool
{
    $clientIp = getClientIP();

    // 1. Try DB-backed rate limiting query
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) as attempt_count 
             FROM activity_log 
             WHERE action_type = :action 
             AND ip_address = :ip 
             AND created_at > DATE_SUB(NOW(), INTERVAL :window MINUTE)"
        );
        $stmt->execute([
            ':action' => $actionType,
            ':ip'     => $clientIp,
            ':window' => $windowMinutes
        ]);
        $result = $stmt->fetch();
        if (($result['attempt_count'] ?? 0) >= $maxAttempts) {
            return true;
        }
    } catch (PDOException $e) {
        error_log('Rate limit check DB fallback: ' . $e->getMessage());
    } catch (\Throwable $t) {
        error_log('Rate limit check general fallback: ' . $t->getMessage());
    }

    // 2. Session-based rate limit fallback
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }

    $now = time();
    $cutoff = $now - ($windowMinutes * 60);

    if (!isset($_SESSION['rate_limits'][$actionType]) || !is_array($_SESSION['rate_limits'][$actionType])) {
        $_SESSION['rate_limits'][$actionType] = [];
    }

    // Prune stale timestamps
    $_SESSION['rate_limits'][$actionType] = array_filter(
        $_SESSION['rate_limits'][$actionType],
        fn($ts) => is_numeric($ts) && (int)$ts > $cutoff
    );

    return count($_SESSION['rate_limits'][$actionType]) >= $maxAttempts;
}

/**
 * 10. Record rate limit hit into session fallback tracking
 */
function recordRateLimitHit(string $actionType): void
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    if (!isset($_SESSION['rate_limits'][$actionType]) || !is_array($_SESSION['rate_limits'][$actionType])) {
        $_SESSION['rate_limits'][$actionType] = [];
    }
    $_SESSION['rate_limits'][$actionType][] = time();
}

/**
 * 11. Log activity to database activity_log
 */
function logActivity(string $actionType, string $description = '', array $metadata = []): void
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO activity_log (action_type, description, ip_address, user_agent, metadata) 
             VALUES (:action, :desc, :ip, :ua, :meta)"
        );
        $stmt->execute([
            ':action' => $actionType,
            ':desc'   => $description,
            ':ip'     => getClientIP(),
            ':ua'     => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ':meta'   => !empty($metadata) ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : null
        ]);
    } catch (PDOException $e) {
        error_log('Activity log PDO error: ' . $e->getMessage());
    } catch (\Throwable $t) {
        error_log('Activity log general error: ' . $t->getMessage());
    }
}

/**
 * 12. Fallback lead logger writing to logs/audit_leads.log with atomic file lock
 */
function logLeadToFileFallback(string $filename, array $leadData, string $reason = ''): bool
{
    try {
        $logDir = dirname(__DIR__) . '/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        $htaccessFile = $logDir . '/.htaccess';
        if (!file_exists($htaccessFile)) {
            @file_put_contents($htaccessFile, "Require all denied\n");
        }

        $targetFile = $logDir . '/' . basename($filename);
        $logEntry = json_encode([
            'timestamp'   => date('c'),
            'lead_ref_id' => $leadData['reference_id'] ?? $leadData['lead_ref_id'] ?? ('AUD-' . date('ym') . '-' . strtoupper(substr(md5((string)mt_rand()), 0, 6))),
            'lead_data'   => $leadData,
            'ip_address'  => getClientIP(),
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'status'      => 'FALLBACK_FILE_LOGGED',
            'reason'      => $reason
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

        return (bool) @file_put_contents($targetFile, $logEntry, FILE_APPEND | LOCK_EX);
    } catch (\Throwable $e) {
        error_log('Flat-file fallback log failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * 13. Save contact form submission to database with automatic flat-file fallback
 */
function saveContactSubmission(array $data): int|false
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO contact_submissions 
             (full_name, email, phone, practice_name, specialty, message, ip_address, user_agent) 
             VALUES (:name, :email, :phone, :practice, :specialty, :message, :ip, :ua)"
        );
        $stmt->execute([
            ':name'      => $data['full_name'] ?? $data['name'] ?? '',
            ':email'     => $data['email'] ?? '',
            ':phone'     => $data['phone'] ?? null,
            ':practice'  => $data['practice_name'] ?? null,
            ':specialty' => $data['specialty'] ?? null,
            ':message'   => $data['message'] ?? '',
            ':ip'        => getClientIP(),
            ':ua'        => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);

        $id = (int) $pdo->lastInsertId();

        logActivity('contact_form', 'New contact form submission', [
            'submission_id' => $id,
            'email'         => $data['email'] ?? ''
        ]);

        return $id;
    } catch (PDOException $e) {
        error_log('Contact submission DB error: ' . $e->getMessage());
        logLeadToFileFallback('audit_leads.log', $data, 'DB_UNAVAILABLE: ' . $e->getMessage());
        return false;
    } catch (\Throwable $t) {
        error_log('Contact submission general error: ' . $t->getMessage());
        logLeadToFileFallback('audit_leads.log', $data, 'GENERAL_ERROR: ' . $t->getMessage());
        return false;
    }
}

/**
 * 14. Save practice revenue audit submission to database with dynamic schema creation and flat-file fallback
 */
function saveAuditSubmission(array $data): int|string|false
{
    $refId = $data['lead_ref_id'] ?? $data['reference_id'] ?? ('AUD-' . date('ym') . '-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6)));
    $data['lead_ref_id'] = $refId;

    try {
        $pdo = getDBConnection();

        // Check/create audit_submissions table if needed
        $insertAuditSql = "INSERT INTO audit_submissions 
            (practice_name, contact_name, job_title, email, phone, street_address, city, state, zip_code, specialty, patient_volume, monthly_revenue, current_ehr, pain_points, additional_notes, message, lead_ref_id, ip_address, user_agent) 
            VALUES (:practice_name, :contact_name, :job_title, :email, :phone, :street_address, :city, :state, :zip_code, :specialty, :patient_volume, :monthly_revenue, :current_ehr, :pain_points, :additional_notes, :message, :lead_ref_id, :ip, :ua)";

        $painPointsJson = !empty($data['pain_points']) ? (is_array($data['pain_points']) ? json_encode($data['pain_points'], JSON_UNESCAPED_UNICODE) : (string)$data['pain_points']) : null;

        try {
            $stmt = $pdo->prepare($insertAuditSql);
            $stmt->execute([
                ':practice_name'   => $data['practice_name'] ?? '',
                ':contact_name'    => $data['contact_name'] ?? $data['full_name'] ?? '',
                ':job_title'       => $data['job_title'] ?? null,
                ':email'           => $data['email'] ?? '',
                ':phone'           => $data['phone'] ?? '',
                ':street_address'  => $data['street_address'] ?? null,
                ':city'            => $data['city'] ?? null,
                ':state'           => $data['state'] ?? null,
                ':zip_code'        => $data['zip_code'] ?? null,
                ':specialty'       => $data['specialty'] ?? '',
                ':patient_volume'  => $data['patient_volume'] ?? '',
                ':monthly_revenue' => $data['monthly_revenue'] ?? '',
                ':current_ehr'     => $data['current_ehr'] ?? null,
                ':pain_points'     => $painPointsJson,
                ':additional_notes'=> $data['additional_notes'] ?? null,
                ':message'         => $data['message'] ?? '',
                ':lead_ref_id'     => $refId,
                ':ip'              => getClientIP(),
                ':ua'              => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);

            $id = (int)$pdo->lastInsertId();
            logActivity('practice_audit', 'New practice revenue audit intake', [
                'submission_id' => $id,
                'lead_ref_id'   => $refId,
                'email'         => $data['email'] ?? ''
            ]);
            return $id;
        } catch (PDOException $pe) {
            // Check for missing table error (42S02 or error 1146)
            if ($pe->getCode() === '42S02' || str_contains($pe->getMessage(), "Table") && str_contains($pe->getMessage(), "doesn't exist")) {
                $createSql = "CREATE TABLE IF NOT EXISTS `audit_submissions` (
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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                $pdo->exec($createSql);

                $stmt = $pdo->prepare($insertAuditSql);
                $stmt->execute([
                    ':practice_name'   => $data['practice_name'] ?? '',
                    ':contact_name'    => $data['contact_name'] ?? $data['full_name'] ?? '',
                    ':job_title'       => $data['job_title'] ?? null,
                    ':email'           => $data['email'] ?? '',
                    ':phone'           => $data['phone'] ?? '',
                    ':street_address'  => $data['street_address'] ?? null,
                    ':city'            => $data['city'] ?? null,
                    ':state'           => $data['state'] ?? null,
                    ':zip_code'        => $data['zip_code'] ?? null,
                    ':specialty'       => $data['specialty'] ?? '',
                    ':patient_volume'  => $data['patient_volume'] ?? '',
                    ':monthly_revenue' => $data['monthly_revenue'] ?? '',
                    ':current_ehr'     => $data['current_ehr'] ?? null,
                    ':pain_points'     => $painPointsJson,
                    ':additional_notes'=> $data['additional_notes'] ?? null,
                    ':message'         => $data['message'] ?? '',
                    ':lead_ref_id'     => $refId,
                    ':ip'              => getClientIP(),
                    ':ua'              => $_SERVER['HTTP_USER_AGENT'] ?? ''
                ]);
                return (int)$pdo->lastInsertId();
            }

            // Fallback: also try saveContactSubmission
            $altId = saveContactSubmission($data);
            if ($altId !== false) {
                return $altId;
            }
            throw $pe;
        }
    } catch (PDOException $e) {
        error_log('Audit submission DB unavailable: ' . $e->getMessage());
        logLeadToFileFallback('audit_leads.log', $data, 'DB_UNAVAILABLE: ' . $e->getMessage());
        return $refId;
    } catch (\Throwable $t) {
        error_log('Audit submission general error: ' . $t->getMessage());
        logLeadToFileFallback('audit_leads.log', $data, 'GENERAL_ERROR: ' . $t->getMessage());
        return $refId;
    }
}

/**
 * 15. Save newsletter subscription
 */
function saveNewsletterSubscription(string $email): array
{
    try {
        $pdo = getDBConnection();

        // Check if already subscribed
        $stmt = $pdo->prepare("SELECT id, is_active FROM newsletter_subscribers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            if ($existing['is_active']) {
                return ['success' => false, 'message' => 'This email is already subscribed.'];
            }
            // Reactivate
            $stmt = $pdo->prepare(
                "UPDATE newsletter_subscribers SET is_active = 1, unsubscribed_at = NULL WHERE id = :id"
            );
            $stmt->execute([':id' => $existing['id']]);
            return ['success' => true, 'message' => 'Welcome back! Your subscription has been reactivated.'];
        }

        $stmt = $pdo->prepare(
            "INSERT INTO newsletter_subscribers (email, ip_address) VALUES (:email, :ip)"
        );
        $stmt->execute([
            ':email' => $email,
            ':ip'    => getClientIP()
        ]);

        logActivity('newsletter_subscribe', 'New newsletter subscription', ['email' => $email]);

        return ['success' => true, 'message' => 'Thank you for subscribing!'];
    } catch (PDOException $e) {
        error_log('Newsletter subscription error: ' . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred. Please try again.'];
    }
}

/**
 * 16. Send general contact email to admin via PHPMailer or mail()
 */
function sendContactEmail(array $data): bool
{
    ob_start();
    try {
        $phpmailerPath = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';

        if (!file_exists($phpmailerPath)) {
            $to = defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com';
            $subject = $data['subject'] ?? ("New Contact Form Submission - " . ($data['full_name'] ?? 'Unknown'));
            $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
            $replyEmail = $data['email'] ?? '';

            $headers = "From: " . $fromEmail . "\r\n";
            if (!empty($replyEmail)) {
                $headers .= "Reply-To: " . $replyEmail . "\r\n";
            }
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $body = buildEmailBody($data);
            $res = @mail($to, $subject, $body, $headers);
            return $res;
        }

        require_once $phpmailerPath;
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {};
        $mail->Timeout = 2;
        $mail->isSMTP();
        $mail->Host       = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = defined('SMTP_USER') ? SMTP_USER : '';
        $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;

        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
        $fromName  = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'MEDINEXT SOLUTIONS Site';
        $toEmail   = defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com';

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        if (!empty($data['email'])) {
            $mail->addReplyTo($data['email'], $data['full_name'] ?? $data['contact_name'] ?? '');
        }

        $mail->isHTML(true);
        $mail->Subject = $data['subject'] ?? ("New Contact Form: " . ($data['full_name'] ?? 'Inquiry') . " - MEDINEXT SOLUTIONS");
        $mail->Body    = buildEmailBody($data);
        $mail->AltBody = buildEmailPlainText($data);

        return (bool) $mail->send();
    } catch (\Throwable $e) {
        error_log('sendContactEmail exception: ' . $e->getMessage());
        return false;
    } finally {
        ob_end_clean();
    }
}

/**
 * 17. Send practice revenue audit alert email to admin
 */
function sendAuditAdminEmail(array $data): bool
{
    $practiceName = $data['practice_name'] ?? 'Practice';
    $specialty    = $data['specialty'] ?? 'Specialty';
    $leadRefId    = $data['lead_ref_id'] ?? $data['reference_id'] ?? ('AUD-' . date('ym') . '-000000');

    $subject = "[New Audit Request] {$practiceName} ({$specialty}) - {$leadRefId}";
    $data['subject'] = $subject;

    ob_start();
    try {
        $phpmailerPath = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';

        if (!file_exists($phpmailerPath)) {
            $to = defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com';
            $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
            $replyEmail = $data['email'] ?? '';

            $headers = "From: " . $fromEmail . "\r\n";
            if (!empty($replyEmail)) {
                $headers .= "Reply-To: " . $replyEmail . "\r\n";
            }
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $body = buildAuditEmailBody($data);
            return (bool) @mail($to, $subject, $body, $headers);
        }

        require_once $phpmailerPath;
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {};
        $mail->Timeout = 2;
        $mail->isSMTP();
        $mail->Host       = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = defined('SMTP_USER') ? SMTP_USER : '';
        $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;

        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
        $fromName  = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'MEDINEXT SOLUTIONS Site';
        $toEmail   = defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com';

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        if (!empty($data['email'])) {
            $mail->addReplyTo($data['email'], $data['contact_name'] ?? $data['full_name'] ?? '');
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = buildAuditEmailBody($data);
        $mail->AltBody = buildAuditEmailPlainText($data);

        return (bool) $mail->send();
    } catch (\Throwable $e) {
        error_log('sendAuditAdminEmail exception: ' . $e->getMessage());
        return false;
    } finally {
        ob_end_clean();
    }
}

/**
 * 18. Send automated practice revenue audit receipt confirmation to prospect
 */
function sendAuditProspectConfirmationEmail(array $data): bool
{
    $prospectEmail = $data['email'] ?? '';
    if (empty($prospectEmail) || !isValidEmail($prospectEmail)) {
        return false;
    }

    $subject = "Your Practice Revenue Audit Request - MEDINEXT SOLUTIONS";

    ob_start();
    try {
        $phpmailerPath = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';

        if (!file_exists($phpmailerPath)) {
            $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
            $replyEmail = defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com';

            $headers = "From: MEDINEXT SOLUTIONS <" . $fromEmail . ">\r\n";
            $headers .= "Reply-To: MEDINEXT SOLUTIONS <" . $replyEmail . ">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $body = buildProspectConfirmationEmailHtml($data);
            return (bool) @mail($prospectEmail, $subject, $body, $headers);
        }

        require_once $phpmailerPath;
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {};
        $mail->Timeout = 2;
        $mail->isSMTP();
        $mail->Host       = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = defined('SMTP_USER') ? SMTP_USER : '';
        $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;

        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'noreply@medinextsolutions.com';
        $mail->setFrom($fromEmail, 'MEDINEXT SOLUTIONS');
        $mail->addAddress($prospectEmail, $data['contact_name'] ?? $data['full_name'] ?? '');
        $mail->addReplyTo(defined('SMTP_TO_EMAIL') ? SMTP_TO_EMAIL : 'info@medinextsolutions.com', 'MEDINEXT SOLUTIONS Support');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = buildProspectConfirmationEmailHtml($data);
        $mail->AltBody = buildProspectConfirmationEmailPlainText($data);

        return (bool) $mail->send();
    } catch (\Throwable $e) {
        error_log('sendAuditProspectConfirmationEmail exception: ' . $e->getMessage());
        return false;
    } finally {
        ob_end_clean();
    }
}

// Backward-compatibility alias
function sendProspectConfirmationEmail(array $data): bool
{
    return sendAuditProspectConfirmationEmail($data);
}

/**
 * 19. Build HTML email body for general contact
 */
function buildEmailBody(array $data): string
{
    $name      = sanitizeInput($data['full_name'] ?? $data['contact_name'] ?? '');
    $email     = sanitizeInput($data['email'] ?? '');
    $phone     = sanitizeInput($data['phone'] ?? 'Not provided');
    $practice  = sanitizeInput($data['practice_name'] ?? 'Not provided');
    $specialty = sanitizeInput($data['specialty'] ?? 'Not provided');
    $message   = nl2br(sanitizeInput($data['message'] ?? ''));

    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0056D2, #00C9A7); padding: 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 32px; }
        .field { margin-bottom: 16px; }
        .field label { font-weight: 600; color: #0B1120; display: block; margin-bottom: 4px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; }
        .field p { margin: 0; padding: 12px; background: #f8fafc; border-radius: 8px; color: #334155; }
        .footer { background: #f8fafc; padding: 20px 32px; text-align: center; font-size: 13px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Form Submission</h1>
        </div>
        <div class="content">
            <div class="field">
                <label>Full Name</label>
                <p>{$name}</p>
            </div>
            <div class="field">
                <label>Email Address</label>
                <p>{$email}</p>
            </div>
            <div class="field">
                <label>Phone Number</label>
                <p>{$phone}</p>
            </div>
            <div class="field">
                <label>Practice Name</label>
                <p>{$practice}</p>
            </div>
            <div class="field">
                <label>Specialty</label>
                <p>{$specialty}</p>
            </div>
            <div class="field">
                <label>Message</label>
                <p>{$message}</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2025 MEDINEXT SOLUTIONS. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
}

/**
 * 20. Build plain text email body for general contact
 */
function buildEmailPlainText(array $data): string
{
    return sprintf(
        "New Contact Form Submission\n\n" .
        "Name: %s\nEmail: %s\nPhone: %s\n" .
        "Practice: %s\nSpecialty: %s\n\nMessage:\n%s\n\n" .
        "---\nMEDINEXT SOLUTIONS",
        $data['full_name'] ?? $data['contact_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? 'Not provided',
        $data['practice_name'] ?? 'Not provided',
        $data['specialty'] ?? 'Not provided',
        $data['message'] ?? ''
    );
}

/**
 * 21. Build structured HTML email body for practice revenue audit alert
 */
function buildAuditEmailBody(array $data): string
{
    $practiceName   = sanitizeInput($data['practice_name'] ?? 'Not provided');
    $contactName    = sanitizeInput($data['contact_name'] ?? $data['full_name'] ?? 'Not provided');
    $jobTitle       = sanitizeInput($data['job_title'] ?? 'Not specified');
    $email          = sanitizeInput($data['email'] ?? 'Not provided');
    $phone          = sanitizeInput($data['phone'] ?? 'Not provided');
    $street         = sanitizeInput($data['street_address'] ?? 'Not provided');
    $city           = sanitizeInput($data['city'] ?? '');
    $state          = sanitizeInput($data['state'] ?? '');
    $zip            = sanitizeInput($data['zip_code'] ?? '');
    $specialty      = sanitizeInput($data['specialty'] ?? 'Not specified');
    $patientVolume  = sanitizeInput($data['patient_volume'] ?? 'Not specified');
    $monthlyRevenue = sanitizeInput($data['monthly_revenue'] ?? 'Not specified');
    $currentEHR     = sanitizeInput($data['current_ehr'] ?? 'Not specified');
    $additionalNotes= nl2br(sanitizeInput($data['additional_notes'] ?? ''));
    $leadRefId      = sanitizeInput($data['lead_ref_id'] ?? $data['reference_id'] ?? 'AUD-NEW');

    $painPointsList = '';
    if (!empty($data['pain_points'])) {
        $pts = is_array($data['pain_points']) ? $data['pain_points'] : explode(',', (string)$data['pain_points']);
        foreach ($pts as $pt) {
            $ptClean = sanitizeInput(trim((string)$pt));
            if (!empty($ptClean)) {
                $painPointsList .= '<li style="margin-bottom: 4px; color: #dc2626; font-weight: 500;">' . $ptClean . '</li>';
            }
        }
    }
    if (empty($painPointsList)) {
        $painPointsList = '<li style="color: #64748b;">None specified</li>';
    }

    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; padding: 24px; color: #334155; }
        .wrapper { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .hero { background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 100%); padding: 32px; text-align: center; color: #ffffff; }
        .hero h1 { margin: 0 0 8px 0; font-size: 22px; font-weight: 700; letter-spacing: -0.02em; }
        .badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .body { padding: 32px; }
        .section-title { font-size: 13px; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.08em; margin: 24px 0 12px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 6px; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .grid td { padding: 8px 12px; font-size: 14px; vertical-align: top; border-bottom: 1px solid #f8fafc; }
        .grid td.label { font-weight: 600; color: #64748b; width: 35%; }
        .grid td.val { color: #0f172a; font-weight: 500; }
        .notes-box { background: #f8fafc; border-left: 4px solid #0284c7; padding: 14px 18px; border-radius: 4px; font-size: 14px; line-height: 1.6; color: #334155; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="hero">
            <span class="badge">Urgent Inbound Lead</span>
            <h1>Practice Revenue Audit Request</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 14px;">Ref ID: <strong>{$leadRefId}</strong> &bull; {$practiceName}</p>
        </div>
        <div class="body">
            <div class="section-title">[PRACTICE & CONTACT DETAILS]</div>
            <table class="grid">
                <tr><td class="label">Practice Name</td><td class="val">{$practiceName}</td></tr>
                <tr><td class="label">Primary Contact</td><td class="val">{$contactName} ({$jobTitle})</td></tr>
                <tr><td class="label">Work Email</td><td class="val"><a href="mailto:{$email}" style="color: #0284c7;">{$email}</a></td></tr>
                <tr><td class="label">Direct Phone</td><td class="val"><a href="tel:{$phone}" style="color: #0284c7;">{$phone}</a></td></tr>
                <tr><td class="label">Physical Address</td><td class="val">{$street}, {$city}, {$state} {$zip}</td></tr>
            </table>

            <div class="section-title">[CLINICAL & FINANCIAL METRICS]</div>
            <table class="grid">
                <tr><td class="label">Medical Specialty</td><td class="val"><strong>{$specialty}</strong></td></tr>
                <tr><td class="label">Patient Volume</td><td class="val">{$patientVolume}</td></tr>
                <tr><td class="label">Monthly Collections</td><td class="val">{$monthlyRevenue}</td></tr>
                <tr><td class="label">Current EHR / PMS</td><td class="val">{$currentEHR}</td></tr>
            </table>

            <div class="section-title">[RCM PAIN POINTS & CHALLENGES]</div>
            <ul style="margin: 0 0 16px 0; padding-left: 20px;">
                {$painPointsList}
            </ul>

            <div class="section-title">[SPECIFIC AUDIT GOALS & NOTES]</div>
            <div class="notes-box">
                {$additionalNotes}
            </div>
        </div>
        <div class="footer">
            <p style="margin: 0;">MEDINEXT SOLUTIONS &bull; Confidential Administrative Notification</p>
        </div>
    </div>
</body>
</html>
HTML;
}

/**
 * 22. Build plain text email body for practice revenue audit alert
 */
function buildAuditEmailPlainText(array $data): string
{
    $practiceName   = $data['practice_name'] ?? 'Not provided';
    $contactName    = $data['contact_name'] ?? $data['full_name'] ?? 'Not provided';
    $jobTitle       = $data['job_title'] ?? 'Not specified';
    $email          = $data['email'] ?? 'Not provided';
    $phone          = $data['phone'] ?? 'Not provided';
    $street         = $data['street_address'] ?? 'Not provided';
    $city           = $data['city'] ?? '';
    $state          = $data['state'] ?? '';
    $zip            = $data['zip_code'] ?? '';
    $specialty      = $data['specialty'] ?? 'Not specified';
    $patientVolume  = $data['patient_volume'] ?? 'Not specified';
    $monthlyRevenue = $data['monthly_revenue'] ?? 'Not specified';
    $currentEHR     = $data['current_ehr'] ?? 'Not specified';
    $additionalNotes= $data['additional_notes'] ?? 'None provided';
    $leadRefId      = $data['lead_ref_id'] ?? $data['reference_id'] ?? 'AUD-NEW';

    $painPointsStr = '';
    if (!empty($data['pain_points'])) {
        $pts = is_array($data['pain_points']) ? $data['pain_points'] : explode(',', (string)$data['pain_points']);
        foreach ($pts as $pt) {
            $painPointsStr .= " - " . trim((string)$pt) . "\n";
        }
    } else {
        $painPointsStr = " - None specified\n";
    }

    return <<<TEXT
=========================================================
PRACTICE REVENUE AUDIT & COST ASSESSMENT INTAKE
Reference ID: {$leadRefId}
Timestamp: " . date('Y-m-d H:i:s T') . "
=========================================================

[PRACTICE & CONTACT DETAILS]
Practice Name : {$practiceName}
Contact Person: {$contactName}
Role / Title  : {$jobTitle}
Work Email    : {$email}
Phone Number  : {$phone}
Street Address: {$street}
City, ST ZIP  : {$city}, {$state} {$zip}

[CLINICAL & FINANCIAL METRICS]
Specialty     : {$specialty}
Patient Volume: {$patientVolume}
Monthly Rev.  : {$monthlyRevenue}
Current EHR   : {$currentEHR}

[RCM PAIN POINTS & CHALLENGES]
{$painPointsStr}
[SPECIFIC AUDIT GOALS & NOTES]
{$additionalNotes}

=========================================================
MEDINEXT SOLUTIONS
TEXT;
}

/**
 * 23. Build prospect auto-confirmation HTML email template
 */
function buildProspectConfirmationEmailHtml(array $data): string
{
    $contactName  = sanitizeInput($data['contact_name'] ?? $data['full_name'] ?? 'Healthcare Provider');
    $practiceName = sanitizeInput($data['practice_name'] ?? 'your practice');
    $leadRefId    = sanitizeInput($data['lead_ref_id'] ?? $data['reference_id'] ?? ('AUD-' . date('ym') . '-000000'));
    $specialty    = sanitizeInput($data['specialty'] ?? 'Medical Practice');

    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 24px; color: #1e293b; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { background: #0c4a6e; padding: 28px 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0 0 6px 0; font-size: 20px; }
        .body { padding: 32px; line-height: 1.6; }
        .roadmap-step { display: flex; margin-bottom: 16px; }
        .step-num { width: 28px; height: 28px; border-radius: 50%; background: #0284c7; color: #fff; text-align: center; line-height: 28px; font-weight: 700; font-size: 13px; margin-right: 14px; flex-shrink: 0; }
        .footer { background: #f1f5f9; padding: 20px 32px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Practice Revenue Audit Request Received</h1>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">Reference ID: <strong>{$leadRefId}</strong></p>
        </div>
        <div class="body">
            <p>Dear <strong>{$contactName}</strong>,</p>
            <p>Thank you for submitting a complimentary <strong>Practice Revenue Audit & Cost Assessment</strong> request for <strong>{$practiceName}</strong> ({$specialty}). Our senior RCM diagnostic team has received your metrics and commenced initial evaluation.</p>
            
            <h3 style="color: #0c4a6e; font-size: 15px; margin-top: 24px; text-transform: uppercase; letter-spacing: 0.05em;">Your 4-Step Audit Roadmap</h3>
            <div style="margin-top: 16px;">
                <p><strong>Step 1 &bull; Discovery Briefing (Within 24 Hours):</strong> An AAPC-certified billing strategist will contact you to verify billing workflow nuances.</p>
                <p><strong>Step 2 &bull; Sample Remittance & Denial Mapping (48-72 Hours):</strong> Under HIPAA BAA protection, we analyze sample 835 ERA feeds to uncover hidden claim leakage.</p>
                <p><strong>Step 3 &bull; Forensic Diagnostic Delivery:</strong> You receive an executive summary pinpointing denial root causes, coding variance, and aging A/R recovery potential.</p>
                <p><strong>Step 4 &bull; 1-on-1 Strategy Session:</strong> A personalized walkthrough of actionable cash recovery recommendations with zero obligation.</p>
            </div>

            <p style="margin-top: 24px;">If you have urgent questions, call our billing advisory desk directly at <a href="tel:8627992199" style="color: #0284c7; font-weight: 600;">862-799-2199</a> or reply to <a href="mailto:info@medinextsolutions.com" style="color: #0284c7;">info@medinextsolutions.com</a>.</p>
        </div>
        <div class="footer">
            <p style="margin: 0;">&copy; 2025 MEDINEXT SOLUTIONS. 1317 Edgewater Dr #3520, Orlando, FL 32804 &bull; HIPAA Compliant</p>
        </div>
    </div>
</body>
</html>
HTML;
}

/**
 * 24. Build prospect auto-confirmation plain text email
 */
function buildProspectConfirmationEmailPlainText(array $data): string
{
    $contactName  = $data['contact_name'] ?? $data['full_name'] ?? 'Healthcare Provider';
    $practiceName = $data['practice_name'] ?? 'your practice';
    $leadRefId    = $data['lead_ref_id'] ?? $data['reference_id'] ?? ('AUD-' . date('ym') . '-000000');

    return <<<TEXT
Dear {$contactName},

Thank you for requesting a complimentary Practice Revenue Audit & Cost Assessment for {$practiceName}.
Your Reference ID is: {$leadRefId}

Our 4-Step Audit Roadmap:
1. Discovery Briefing (Within 24 Hours)
2. Sample Remittance & Denial Mapping (48-72 Hours)
3. Forensic Diagnostic Delivery
4. 1-on-1 Strategy Session

Questions? Contact us at 862-799-2199 or info@medinextsolutions.com.

---
MEDINEXT SOLUTIONS
1317 Edgewater Dr #3520, Orlando, FL 32804
TEXT;
}

function buildProspectEmailBody(array $data): string
{
    return buildProspectConfirmationEmailHtml($data);
}

/**
 * 25. Detect whether current request expects an AJAX/JSON response
 */
function isAjaxRequest(): bool
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string)$_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        return true;
    }
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtolower((string)$_SERVER['HTTP_ACCEPT']), 'application/json') !== false) {
        return true;
    }
    if (isset($_SERVER['CONTENT_TYPE']) && strpos(strtolower((string)$_SERVER['CONTENT_TYPE']), 'application/json') !== false) {
        return true;
    }
    if (isset($_POST['is_ajax']) && ($_POST['is_ajax'] === '1' || $_POST['is_ajax'] === 'true' || $_POST['is_ajax'] === 1)) {
        return true;
    }
    return false;
}

/**
 * 26. Standardized JSON response helper with buffer cleanup
 */
function jsonResponse(bool $success, string $message, array $data = [], int $httpCode = 200): void
{
    while (ob_get_level() > 1) {
        @ob_end_clean();
    }
    if (ob_get_level() > 0) {
        @ob_clean();
    }

    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    $payload = [
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ];

    echo json_encode($payload, JSON_UNESCAPED_UNICODE);

    // If included in test runner, return rather than hard exit
    if (defined('PHPUNIT_RUNNING') || count(get_included_files()) > 2 || (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) !== 'functions.php')) {
        return;
    }
    exit;
}

/**
 * 27. Set flash message in session
 */
function setFlashMessage(string $key, string $message): void
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    $_SESSION['flash_' . $key] = $message;
}

/**
 * 28. Get flash message from session and clear it
 */
function getFlashMessage(string $key): ?string
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    $sessionKey = 'flash_' . $key;
    if (isset($_SESSION[$sessionKey])) {
        $msg = (string)$_SESSION[$sessionKey];
        unset($_SESSION[$sessionKey]);
        return $msg;
    }
    return null;
}

/**
 * 29. Get current page name for active navigation state
 */
function getCurrentPage(): string
{
    $page = basename($_SERVER['PHP_SELF'] ?? '', '.php');
    return $page;
}

/**
 * 30. Check active navigation CSS class
 */
function isActivePage(string $page): string
{
    return getCurrentPage() === $page ? 'active' : '';
}

/**
 * 31. Get Base URL of website
 */
function getBaseUrl(): string
{
    static $baseUrl = null;
    if ($baseUrl !== null) {
        return $baseUrl;
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
               (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);
    $scheme = $isHttps ? 'https' : 'http';

    $isSubdir = strpos($_SERVER['REQUEST_URI'] ?? '', '/Medinext_solution/Medinext_solutions') === 0;
    if ($isSubdir) {
        $baseUrl = $scheme . '://' . $host . '/Medinext_solution/Medinext_solutions';
    } else {
        $baseUrl = $scheme . '://' . $host;
    }

    return $baseUrl;
}