<?php
require_once dirname(__DIR__) . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /free-practice-audit.php');
    exit;
}

// Rate limiting basic
if (isRateLimited('audit_form', 5, 15)) {
    die("Too many submissions. Please try again later.");
}

$practiceName = sanitizeInput($_POST['practiceName'] ?? '');
$contactName = sanitizeInput($_POST['contactName'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone = sanitizeInput($_POST['phone'] ?? '');
$specialty = sanitizeInput($_POST['specialty'] ?? '');
$providerCount = sanitizeInput($_POST['providerCount'] ?? '');
$biggestChallenge = sanitizeInput($_POST['biggestChallenge'] ?? '');

if (!$email || !isValidEmail($email)) {
    die("Invalid email address. Please go back and try again.");
}

// Format the message
$message = "FREE PRACTICE AUDIT REQUEST:\n";
$message .= "---------------------------\n";
$message .= "Provider Count: " . $providerCount . "\n";
$message .= "Biggest Challenge: " . $biggestChallenge . "\n";

$data = [
    'full_name'     => $contactName,
    'email'         => $email,
    'phone'         => $phone,
    'practice_name' => $practiceName,
    'specialty'     => $specialty,
    'message'       => $message
];

// Save to DB (using the contact table function)
saveContactSubmission($data);

// Prevent any mailer debug/noise from leaking into HTTP output
ob_start();
sendContactEmail($data);
ob_end_clean();

// Redirect back with success message
header('Location: /free-practice-audit.php?success=1');
exit;
