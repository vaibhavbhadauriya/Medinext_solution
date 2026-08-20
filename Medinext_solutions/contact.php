<?php
/**
 * MEDINEXT SOLUTIONS - Contact Page
 * Handles both display and AJAX form submission
 */

$pageTitle = 'Contact MEDINEXT SOLUTIONS | Get a Free RCM Consultation';
$pageDescription = 'Contact the RCM experts at MEDINEXT SOLUTIONS today for a free consultation. Find out how our specialized healthcare billing services can optimize your practice\'s cash flow.';
$pageKeywords = 'contact MEDINEXT SOLUTIONS, RCM consultation, medical billing quote, outsource medical billing, healthcare billing inquiry, revenue cycle management contact';

require_once 'includes/functions.php';

// ============================================
// Handle AJAX Form Submissions
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    @ini_set('display_errors', '0');

    $respondJson = static function (bool $success, string $message, array $data = [], int $httpCode = 200): void {
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        jsonResponse($success, $message, $data, $httpCode);
    };
    
    // Newsletter subscription
    if (isset($_POST['action']) && $_POST['action'] === 'newsletter') {
        $email = filter_input(INPUT_POST, 'newsletter_email', FILTER_SANITIZE_EMAIL);
        
        if (!$email || !isValidEmail($email)) {
            $respondJson(false, 'Please enter a valid email address.', [], 400);
        }

        if (isRateLimited('newsletter_subscribe', 3, 5)) {
            $respondJson(false, 'Too many attempts. Please try again later.', [], 429);
        }

        $result = saveNewsletterSubscription($email);
        $respondJson($result['success'], $result['message']);
    }

    // Contact form submission
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrfToken)) {
        $respondJson(false, 'Invalid security token. Please refresh and try again.', [], 403);
    }

    if (isRateLimited('contact_form', 5, 15)) {
        $respondJson(false, 'Too many submissions. Please try again in 15 minutes.', [], 429);
    }

    // Validate inputs
    $errors = [];

    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    if (strlen($fullName) < 2) {
        $errors['full_name'] = 'Name must be at least 2 characters.';
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (!$email || !isValidEmail($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    $phone = sanitizeInput($_POST['phone'] ?? '');
    if ($phone && !isValidPhone($phone)) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    $practiceName = sanitizeInput($_POST['practice_name'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    if (strlen($message) < 10) {
        $errors['message'] = 'Message must be at least 10 characters.';
    }

    if (!empty($errors)) {
        $respondJson(false, 'Please correct the errors below.', ['errors' => $errors], 422);
    }

    // Save to database
    $data = [
        'full_name'     => $fullName,
        'email'         => $email,
        'phone'         => $phone,
        'practice_name' => $practiceName,
        'message'       => $message
    ];

    $submissionId = saveContactSubmission($data);

    if ($submissionId === false) {
        $respondJson(false, 'An error occurred while saving your message. Please try again.');
    }

    // Prevent any mailer debug/noise from breaking JSON response
    ob_start();
    sendContactEmail($data);
    ob_end_clean();

    $respondJson(true, 'Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.', [
        'submission_id' => $submissionId
    ]);
}

// ============================================
// Display page
// ============================================
require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- PAGE HERO -->
<!-- ============================================ -->
<section class="page-hero">
    <div class="hero-mesh-gradient">
        <div class="mesh-orb mesh-orb-1"></div>
        <div class="mesh-orb mesh-orb-2"></div>
    </div>
    <div class="container">
        <div class="page-hero-content">
            <nav class="breadcrumb-nav" data-aos="fade-down">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="ph ph-house"></i> Home</a></li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </nav>
            <h1 class="page-hero-title" data-aos="fade-up">
                Get In <span class="gradient-text">Touch</span>
            </h1>
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                Ready to maximize your practice revenue? Contact us for a free consultation and discover how our specialized billing services can help.
            </p>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- CONTACT SECTION -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="row g-5">
            <!-- Left: Map & Info -->
            <div class="col-lg-5" data-aos="fade-right">
                <!-- Map -->
                <div class="map-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215573291249!2d-73.98823492422648!3d40.75797933440898!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1702000000000!5m2!1sen!2sus"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="MEDINEXT SOLUTIONS Location">
                    </iframe>
                </div>

                <!-- Contact Info Cards -->
                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="ph ph-map-pin"></i>
                    </div>
                    <div>
                        <div class="contact-info-label">Office Address</div>
                        <div class="contact-info-value">
                            234 Old Stage Rd<br>
                            East Brunswick, NJ 08816 USA
                        </div>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="ph ph-phone"></i>
                    </div>
                    <div>
                        <div class="contact-info-label">Phone Number</div>
                        <div class="contact-info-value">
                            <a href="tel:+19088290133">908-829-0133</a>
                        </div>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="ph ph-envelope-simple"></i>
                    </div>
                    <div>
                        <div class="contact-info-label">Email Address</div>
                        <div class="contact-info-value">
                            <a href="mailto:info@medinextsolutions.com">Info@medinextsolutions.com</a>
                        </div>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="ph ph-clock"></i>
                    </div>
                    <div>
                        <div class="contact-info-label">Availability</div>
                        <div class="contact-info-value">24/7 Support Available</div>
                    </div>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="contact-form-wrapper">
                    <h3 class="mb-2">Send Us a Message</h3>
                    <p class="text-muted mb-5" style="font-size: var(--fs-sm);">
                        Fill out the form below and our team will get back to you within 24 hours.
                    </p>

                    <!-- Contact Form -->
                    <form id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="fullName">
                                        Full Name <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="fullName" name="full_name" placeholder="John Doe" required minlength="2">
                                    <div class="invalid-feedback">Please enter your full name.</div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">
                                        Email Address <span class="required">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">
                                        Phone Number
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="(555) 123-4567">
                                    <div class="invalid-feedback">Please enter a valid phone number.</div>
                                </div>
                            </div>

                            <!-- Practice Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="practiceName">
                                        Practice Name
                                    </label>
                                    <input type="text" class="form-control" id="practiceName" name="practice_name" placeholder="Your Practice Name">
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="message">
                                        Message <span class="required">*</span>
                                    </label>
                                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tell us about your billing needs..." required minlength="10"></textarea>
                                    <div class="invalid-feedback">Please enter a message (at least 10 characters).</div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-accent btn-lg w-100">
                                    <i class="ph ph-paper-plane-tilt"></i>
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Success Overlay -->
                    <div id="formSuccessOverlay" class="form-success-overlay">
                        <lottie-player
                            src="https://lottie.host/06c36a5b-65c3-4753-88e6-e8af0a47d446/kNDHTsfnDl.json"
                            background="transparent"
                            speed="1"
                            style="width: 180px; height: 180px;"
                            autoplay>
                        </lottie-player>
                        <h3 class="form-success-title">Message Sent!</h3>
                        <p class="form-success-text">
                            Thank you for contacting us. Our team will get back to you within 24 hours.
                        </p>
                        <a href="index.php" class="btn btn-primary">
                            <i class="ph ph-house"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- ============================================ -->
<!-- STRUCTURED DATA (JSON-LD) -->
<!-- ============================================ -->

<!-- SCHEMA 5c ? BreadcrumbList (Contact Us) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "@id": "https://medinextsolutions.com/contact-us/#breadcrumb",
  "name": "Contact Us Breadcrumbs",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://medinextsolutions.com/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Contact Us",
      "item": "https://medinextsolutions.com/contact-us/"
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>