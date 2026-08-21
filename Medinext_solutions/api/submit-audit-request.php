<?php
/**
 * MEDINEXT SOLUTIONS - Free Practice Revenue Audit Request Submission Handler
 * Endpoint: /api/submit-audit-request.php
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/includes/functions.php';

// Detect JSON / AJAX request
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    || (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1');

function respond(bool $success, string $message, array $extra = [], int $httpCode = 200) {
    global $isAjax;
    if ($isAjax) {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
        exit;
    } else {
        if ($success) {
            http_response_code(302);
            header('Location: /free-practice-audit.php?success=1');
            echo "Redirecting to /free-practice-audit.php?success=1";
        } else {
            http_response_code(302);
            $err = urlencode($message);
            header("Location: /free-practice-audit.php?error={$err}");
            echo "Invalid email or validation failure: {$message}. Redirecting to /free-practice-audit.php?error={$err}";
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed', [], 405);
}

// 1. Honeypot check
$hp1 = $_POST['website_hp'] ?? '';
$hp2 = $_POST['audit_form_hp'] ?? '';
if (!empty($hp1) || !empty($hp2)) {
    // Silently succeed for bots
    respond(true, 'Audit request received successfully.', ['lead_id' => 'AUD-' . strtoupper(substr(md5((string)time()), 0, 8))]);
}

// 2. Timestamp check (anti-bot speed submission)
$formTimestamp = isset($_POST['form_timestamp']) ? (int)$_POST['form_timestamp'] : 0;
if ($formTimestamp > 0 && (time() - $formTimestamp) < 1) {
    respond(false, 'Submission received too quickly. Please try again.', [], 400);
}

// 3. Rate limiting check
if (isRateLimited('audit_form', 5, 15)) {
    respond(false, 'Too many requests submitted. Please wait 15 minutes before trying again or call us at 862-799-2199.', [], 429);
}

// 4. CSRF Validation (if token provided in session)
if (!empty($_SESSION['csrf_token']) && !empty($_POST['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        respond(false, 'Security validation token expired. Please refresh the page and submit again.', [], 403);
    }
}

// 5. Extract and sanitize inputs
$practiceName   = sanitizeInput($_POST['practice_name'] ?? $_POST['practiceName'] ?? '');
$contactName    = sanitizeInput($_POST['contact_name'] ?? $_POST['contactName'] ?? '');
$jobTitle       = sanitizeInput($_POST['job_title'] ?? '');
$email          = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
$phone          = sanitizeInput($_POST['phone'] ?? '');
$streetAddress  = sanitizeInput($_POST['street_address'] ?? '');
$city           = sanitizeInput($_POST['city'] ?? '');
$state          = sanitizeInput($_POST['state'] ?? '');
$zipCode        = sanitizeInput($_POST['zip_code'] ?? '');
$specialty      = sanitizeInput($_POST['specialty'] ?? '');
$patientVolume  = sanitizeInput($_POST['patient_volume'] ?? $_POST['providerCount'] ?? '');
$monthlyRevenue = sanitizeInput($_POST['monthly_revenue'] ?? '');
$currentEHR     = sanitizeInput($_POST['current_ehr'] ?? '');
$additionalNotes= sanitizeInput($_POST['additional_notes'] ?? '');

// Handle pain points array
$painPoints = [];
if (!empty($_POST['pain_points']) && is_array($_POST['pain_points'])) {
    foreach ($_POST['pain_points'] as $point) {
        $painPoints[] = sanitizeInput((string)$point);
    }
} elseif (!empty($_POST['biggestChallenge'])) {
    $painPoints[] = sanitizeInput((string)$_POST['biggestChallenge']);
}

// 6. Validation of required fields
if (empty($practiceName) || strlen($practiceName) < 2) {
    respond(false, 'Please enter your practice or clinic name.', [], 400);
}
if (empty($contactName) || strlen($contactName) < 2) {
    respond(false, 'Please enter your primary contact full name.', [], 400);
}
if (empty($email) || !isValidEmail($email)) {
    respond(false, 'Please provide a valid work email address.', [], 400);
}
if (empty($phone) || !isValidPhone($phone)) {
    respond(false, 'Please provide a valid 10-digit phone number.', [], 400);
}
if (empty($specialty)) {
    respond(false, 'Please select your medical or dental specialty.', [], 400);
}
if (empty($patientVolume)) {
    respond(false, 'Please select your estimated monthly patient volume.', [], 400);
}
if (empty($monthlyRevenue)) {
    respond(false, 'Please select your monthly collections volume.', [], 400);
}

// Generate human-friendly reference ID
$leadRefId = 'AUD-' . date('ym') . '-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));

// 7. Format comprehensive structured notification body
$messageBody  = "=========================================================\n";
$messageBody .= "PRACTICE REVENUE AUDIT & COST ASSESSMENT INTAKE\n";
$messageBody .= "Reference ID: " . $leadRefId . "\n";
$messageBody .= "Timestamp: " . date('Y-m-d H:i:s T') . "\n";
$messageBody .= "=========================================================\n\n";

$messageBody .= "[PRACTICE & CONTACT DETAILS]\n";
$messageBody .= "Practice Name : " . $practiceName . "\n";
$messageBody .= "Contact Person: " . $contactName . "\n";
$messageBody .= "Role / Title  : " . ($jobTitle ?: 'Not specified') . "\n";
$messageBody .= "Work Email    : " . $email . "\n";
$messageBody .= "Phone Number  : " . $phone . "\n";
$messageBody .= "Street Address: " . ($streetAddress ?: 'Not provided') . "\n";
$messageBody .= "City, ST ZIP  : " . trim(($city ?: '') . ' ' . ($state ?: '') . ' ' . ($zipCode ?: '')) . "\n\n";

$messageBody .= "[CLINICAL & FINANCIAL METRICS]\n";
$messageBody .= "Specialty     : " . $specialty . "\n";
$messageBody .= "Patient Volume: " . $patientVolume . "\n";
$messageBody .= "Monthly Rev.  : " . $monthlyRevenue . "\n";
$messageBody .= "Current EHR   : " . ($currentEHR ?: 'Not specified') . "\n\n";

$messageBody .= "[RCM PAIN POINTS & CHALLENGES]\n";
if (!empty($painPoints)) {
    foreach ($painPoints as $p) {
        $messageBody .= " - " . $p . "\n";
    }
} else {
    $messageBody .= " - None specified\n";
}
$messageBody .= "\n";

if (!empty($additionalNotes)) {
    $messageBody .= "[SPECIFIC AUDIT GOALS & NOTES]\n";
    $messageBody .= $additionalNotes . "\n\n";
}

$messageBody .= "=========================================================\n";
$messageBody .= "Submitted from: " . getClientIP() . "\n";
$messageBody .= "User Agent    : " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n";

// 8. Save submission to database
$dataToSave = [
    'full_name'     => $contactName,
    'email'         => $email,
    'phone'         => $phone,
    'practice_name' => $practiceName,
    'specialty'     => $specialty,
    'message'       => $messageBody
];

$submissionId = saveContactSubmission($dataToSave);

// 9. Send email notification
ob_start();
sendContactEmail([
    'full_name'     => $contactName,
    'email'         => $email,
    'phone'         => $phone,
    'practice_name' => $practiceName,
    'specialty'     => $specialty,
    'subject'       => "[New Audit Request] {$practiceName} ({$specialty}) - {$leadRefId}",
    'message'       => $messageBody
]);
ob_end_clean();

// 10. Return success
respond(true, 'Your practice audit request has been successfully submitted! An RCM architect will reach out within 24 hours.', [
    'lead_id'       => $leadRefId,
    'submission_id' => $submissionId,
    'lead'          => [
        'contact_name'  => $contactName,
        'practice_name' => $practiceName,
        'specialty'     => $specialty,
        'email'         => $email,
        'phone'         => $phone
    ]
]);
