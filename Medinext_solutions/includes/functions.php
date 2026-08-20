<?php
/**
 * MEDINEXT SOLUTIONS - Helper Functions
 * 
 * Utility functions used across the application
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

/**
 * Sanitize input string
 */
function sanitizeInput(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number
 */
function isValidPhone(string $phone): bool
{
    $cleaned = preg_replace('/[^0-9+\-\(\)\s]/', '', $phone);
    return strlen($cleaned) >= 10 && strlen($cleaned) <= 20;
}

/**
 * Get client IP address
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
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '0.0.0.0';
}

/**
 * Rate limiting check (simple IP-based)
 */
function isRateLimited(string $actionType, int $maxAttempts = 5, int $windowMinutes = 15): bool
{
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
            ':ip'     => getClientIP(),
            ':window' => $windowMinutes
        ]);
        $result = $stmt->fetch();
        return ($result['attempt_count'] ?? 0) >= $maxAttempts;
    } catch (PDOException $e) {
        error_log('Rate limit check error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Log activity to database
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
            ':meta'   => !empty($metadata) ? json_encode($metadata) : null
        ]);
    } catch (PDOException $e) {
        error_log('Activity log error: ' . $e->getMessage());
    }
}

/**
 * Save contact form submission
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
            ':name'      => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'] ?? null,
            ':practice'  => $data['practice_name'] ?? null,
            ':specialty' => $data['specialty'] ?? null,
            ':message'   => $data['message'],
            ':ip'        => getClientIP(),
            ':ua'        => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);

        $id = (int) $pdo->lastInsertId();

        logActivity('contact_form', 'New contact form submission', [
            'submission_id' => $id,
            'email' => $data['email']
        ]);

        return $id;
    } catch (PDOException $e) {
        error_log('Contact submission error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Save newsletter subscription
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
 * Send contact email via PHPMailer
 */
function sendContactEmail(array $data): bool
{
    // Check if PHPMailer is available
    $phpmailerPath = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';

    if (!file_exists($phpmailerPath)) {
        // Fallback to PHP mail() if PHPMailer not installed
        $to = SMTP_TO_EMAIL;
        $subject = "New Contact Form Submission - " . ($data['full_name'] ?? 'Unknown');
        $headers = "From: " . SMTP_FROM_EMAIL . "\r\n";
        $headers .= "Reply-To: " . ($data['email'] ?? '') . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $body = buildEmailBody($data);

        return mail($to, $subject, $body, $headers);
    }

    try {
        require_once $phpmailerPath;
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        // SMTP settings
        $mail->SMTPDebug = 0; // Disable debug output
        $mail->Debugoutput = function($str, $level) {}; // Silently discard debug output
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress(SMTP_TO_EMAIL);
        $mail->addReplyTo($data['email'], $data['full_name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form: {$data['full_name']} - MEDINEXT SOLUTIONS";
        $mail->Body    = buildEmailBody($data);
        $mail->AltBody = buildEmailPlainText($data);

        $result = $mail->send();
        if (!$result) {
            error_log('PHPMailer send failed: ' . $mail->ErrorInfo);
            return false;
        }
        return true;
    } catch (Exception $e) {
        $mailerError = isset($mail) ? $mail->ErrorInfo : 'mail object not initialized';
        error_log('PHPMailer exception: ' . $e->getMessage() . ' | ' . $mailerError);
        return false;
    }
}

/**
 * Build HTML email body
 */
function buildEmailBody(array $data): string
{
    $name      = sanitizeInput($data['full_name'] ?? '');
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
 * Build plain text email
 */
function buildEmailPlainText(array $data): string
{
    return sprintf(
        "New Contact Form Submission\n\n" .
        "Name: %s\nEmail: %s\nPhone: %s\n" .
        "Practice: %s\nSpecialty: %s\n\nMessage:\n%s\n\n" .
        "---\nMEDINEXT SOLUTIONS",
        $data['full_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? 'Not provided',
        $data['practice_name'] ?? 'Not provided',
        $data['specialty'] ?? 'Not provided',
        $data['message'] ?? ''
    );
}

/**
 * Generate CSRF token
 */
function generateCSRFToken(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get current page name for nav active state
 */
function getCurrentPage(): string
{
    $page = basename($_SERVER['PHP_SELF'], '.php');
    return $page;
}

/**
 * Check if current page matches for active nav
 */
function isActivePage(string $page): string
{
    return getCurrentPage() === $page ? 'active' : '';
}

/**
 * JSON response helper
 */
function jsonResponse(bool $success, string $message, array $data = [], int $httpCode = 200): void
{
    // Ensure no stray output corrupts JSON payload (debug, warnings, whitespace)
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Get Base URL of website
 */
function getBaseUrl(): string
{
    static $baseUrl = null;
    if ($baseUrl !== null) {
        return $baseUrl;
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $scheme = $isHttps ? 'https' : 'http';

    $isSubdir = strpos($_SERVER['REQUEST_URI'] ?? '', '/Medinext_solution/Medinext_solutions') === 0;
    if ($isSubdir) {
        $baseUrl = $scheme . '://' . $host . '/Medinext_solution/Medinext_solutions';
    } else {
        $baseUrl = $scheme . '://' . $host;
    }

    return $baseUrl;
}