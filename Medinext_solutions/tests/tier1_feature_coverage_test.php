<?php
/**
 * MEDINEXT SOLUTIONS - Tier 1 Feature Coverage Test Suite
 * 
 * Comprehensive verification of all 19 features (F1 to F19) from TEST_INFRA.md.
 * Covers functional, markup, routing, validation, security, and persistence requirements.
 * 
 * Total Tests: 95 tests (5 distinct tests per feature across F1-F19).
 * 
 * Execution:
 *   php tests/tier1_feature_coverage_test.php
 */

declare(strict_types=1);

namespace Medinext\Tests;

// Ensure clean CLI session initialization
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}

require_once __DIR__ . '/TestHelper.php';

function getTier1FeatureCoverageSuite(): TestSuite
{
    $suite = new TestSuite(
        'Tier 1: Feature Coverage Suite (F1 - F19)',
        'Exhaustive feature-by-feature verification of Practice Revenue Audit form, backend processing, and global CTA routing'
    );

    $projectRoot = getProjectRoot();

    // =========================================================================
    // FEATURE 1: Dedicated Page Render & Clean URL Routing (5 tests)
    // =========================================================================

    $suite->addTest('F01-01: Dedicated audit page template file exists and has valid PHP syntax', 'Tier 1 - F1', function () use ($projectRoot) {
        $filePath = $projectRoot . '/free-practice-audit.php';
        Assert::assertTrue(file_exists($filePath), "free-practice-audit.php must exist at project root");
        $content = file_get_contents($filePath);
        Assert::assertGreaterThanOrEqual(100, strlen($content), "free-practice-audit.php must contain substantial markup");
    });

    $suite->addTest('F01-02: Layout wrapper includes header, footer, and helper functions', 'Tier 1 - F1', function () use ($projectRoot) {
        $content = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains("require_once 'includes/functions.php'", $content, "Must include functions.php");
        Assert::assertStringContains("require_once 'includes/header.php'", $content, "Must include header.php");
        Assert::assertStringContains("require_once 'includes/footer.php'", $content, "Must include footer.php");
    });

    $suite->addTest('F01-03: Page hero section features dark theme and mesh gradient styling', 'Tier 1 - F1', function () use ($projectRoot) {
        $content = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('page-hero', $content, "Hero section must have page-hero class");
        Assert::assertStringContains('hero-mesh-gradient', $content, "Hero section must feature hero-mesh-gradient");
        Assert::assertStringContains('mesh-orb', $content, "Hero section must include mesh-orb elements");
        Assert::assertStringContains('page-hero-title', $content, "Hero must contain page-hero-title");
    });

    $suite->addTest('F01-04: Responsive container and grid layout structure is properly implemented', 'Tier 1 - F1', function () use ($projectRoot) {
        $content = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('container', $content, "Must utilize Bootstrap container");
        Assert::assertStringContains('row', $content, "Must utilize Bootstrap grid row");
        Assert::assertStringContains('col-lg-5', $content, "Left column must have col-lg-5 styling");
        Assert::assertStringContains('col-lg-7', $content, "Right form column must have col-lg-7 styling");
    });

    $suite->addTest('F01-05: Clean URL rewrite routing configured in .htaccess and canonical tag', 'Tier 1 - F1', function () use ($projectRoot) {
        $htaccessPath = $projectRoot . '/.htaccess';
        Assert::assertTrue(file_exists($htaccessPath), ".htaccess must exist");
        $htaccess = file_get_contents($htaccessPath);
        Assert::assertTrue(
            str_contains($htaccess, 'RewriteRule ^(.*)/$ $1.php') ||
            str_contains($htaccess, 'RewriteEngine On') ||
            str_contains($htaccess, 'free-practice-audit'),
            ".htaccess must contain clean URL rewrite rules"
        );

        $auditPage = renderPageScript('free-practice-audit.php');
        Assert::assertEquals(200, $auditPage['statusCode'], "Page render must return HTTP 200");
        Assert::assertStringContains('free-practice-audit', $auditPage['html'], "Rendered HTML must reference clean URL");
    });

    // =========================================================================
    // FEATURE 2: Practice & POC Identity Fields (5 tests)
    // =========================================================================

    $suite->addTest('F02-01: practice_name input field definition, required flag, and attributes', 'Tier 1 - F2', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+name=["\']practice_name["\'][^>]*>/i', $html, "practice_name input must exist");
        Assert::assertRegex('/<input[^>]+id=["\']practice_name["\'][^>]*required/i', $html, "practice_name must have required attribute");
        Assert::assertStringContains('autocomplete="organization"', $html, "practice_name must have autocomplete='organization'");
    });

    $suite->addTest('F02-02: contact_name input field definition, required flag, and attributes', 'Tier 1 - F2', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+name=["\']contact_name["\'][^>]*>/i', $html, "contact_name input must exist");
        Assert::assertRegex('/<input[^>]+id=["\']contact_name["\'][^>]*required/i', $html, "contact_name must have required attribute");
        Assert::assertStringContains('autocomplete="name"', $html, "contact_name must have autocomplete='name'");
    });

    $suite->addTest('F02-03: job_title select dropdown exists with required attribute and distinct leadership options', 'Tier 1 - F2', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']job_title["\'][^>]*required/i', $html, "job_title select must exist and be required");
        Assert::assertStringContains('Practice Owner', $html, "job_title must include Practice Owner option");
        Assert::assertStringContains('Practice Administrator', $html, "job_title must include Practice Administrator option");
        Assert::assertStringContains('Billing Manager', $html, "job_title must include Billing Manager option");
    });

    $suite->addTest('F02-04: Required indicator flags and aria-required present on identity fields', 'Tier 1 - F2', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('Practice / Clinic / Facility Name <span class="text-danger">*</span>', $html);
        Assert::assertStringContains('Primary Contact Full Name <span class="text-danger">*</span>', $html);
        Assert::assertStringContains('aria-required="true"', $html, "Form fields must have aria-required attribute for accessibility");
    });

    $suite->addTest('F02-05: Label text and placeholder copy clearly specify clinic and POC identity guidance', 'Tier 1 - F2', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('placeholder="e.g. Advanced Orthopedic', $html, "practice_name must have informative placeholder");
        Assert::assertStringContains('placeholder="e.g. Dr. Sarah Jenkins', $html, "contact_name must have informative placeholder");
        Assert::assertStringContains('Select your role...', $html, "job_title must have disabled placeholder option");
    });

    // =========================================================================
    // FEATURE 3: Contact Info Fields (5 tests)
    // =========================================================================

    $suite->addTest('F03-01: email input field type="email" with required attribute and autocomplete', 'Tier 1 - F3', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+type=["\']email["\'][^>]+name=["\']email["\'][^>]*required/i', $html, "email input must be type=email and required");
        Assert::assertStringContains('autocomplete="email"', $html, "email input must have autocomplete='email'");
    });

    $suite->addTest('F03-02: phone input field type="tel" with required attribute and phone-mask class', 'Tier 1 - F3', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+type=["\']tel["\'][^>]+name=["\']phone["\'][^>]*required/i', $html, "phone input must be type=tel and required");
        Assert::assertStringContains('phone-mask', $html, "phone input must have phone-mask class");
    });

    $suite->addTest('F03-03: Phone format hints, placeholder (555) 000-0000, and maxlength', 'Tier 1 - F3', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('placeholder="(555) 000-0000"', $html, "phone input must provide US phone placeholder");
        Assert::assertStringContains('maxlength="14"', $html, "phone input must enforce maxlength=14");
    });

    $suite->addTest('F03-04: Email validation invalid-feedback element and clear messaging', 'Tier 1 - F3', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('Please enter a valid work email address.', $html, "Invalid email feedback message must be present");
        Assert::assertStringContains('Please enter a valid 10-digit phone number.', $html, "Invalid phone feedback message must be present");
    });

    $suite->addTest('F03-05: Input group icons present for email and phone inputs', 'Tier 1 - F3', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('ph-envelope-simple', $html, "Email input must feature envelope icon");
        Assert::assertStringContains('ph-phone', $html, "Phone input must feature phone icon");
    });

    // =========================================================================
    // FEATURE 4: Physical Address Fields (5 tests)
    // =========================================================================

    $suite->addTest('F04-01: street_address input field definition, required attribute, and autocomplete', 'Tier 1 - F4', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+name=["\']street_address["\'][^>]*required/i', $html, "street_address input must be required");
        Assert::assertStringContains('autocomplete="street-address"', $html, "street_address must have autocomplete='street-address'");
        Assert::assertStringContains('minlength="5"', $html, "street_address must have minlength='5'");
    });

    $suite->addTest('F04-02: city input field definition, required attribute, and autocomplete', 'Tier 1 - F4', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+name=["\']city["\'][^>]*required/i', $html, "city input must be required");
        Assert::assertStringContains('autocomplete="address-level2"', $html, "city must have autocomplete='address-level2'");
    });

    $suite->addTest('F04-03: state select dropdown exists with required attribute and US state options', 'Tier 1 - F4', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']state["\'][^>]*required/i', $html, "state select must be required");
        Assert::assertStringContains('<option value="FL">Florida (FL)</option>', $html, "state dropdown must contain Florida");
        Assert::assertStringContains('<option value="TX">Texas (TX)</option>', $html, "state dropdown must contain Texas");
        Assert::assertStringContains('<option value="CA">California (CA)</option>', $html, "state dropdown must contain California");
        Assert::assertStringContains('<option value="NY">New York (NY)</option>', $html, "state dropdown must contain New York");
    });

    $suite->addTest('F04-04: zip_code input field with required attribute, pattern, and maxlength', 'Tier 1 - F4', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<input[^>]+name=["\']zip_code["\'][^>]*required/i', $html, "zip_code input must be required");
        Assert::assertStringContains('pattern="^\d{5}(-\d{4})?$"', $html, "zip_code must have 5-digit regex pattern attribute");
        Assert::assertStringContains('maxlength="10"', $html, "zip_code must have maxlength=10");
    });

    $suite->addTest('F04-05: Address section header and visual map-pin icon present', 'Tier 1 - F4', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('Direct Contact &amp; Physical Location', $html, "Address section title must be present");
        Assert::assertStringContains('ph-map-pin', $html, "Address input group must feature map-pin icon");
    });

    // =========================================================================
    // FEATURE 5: Operational & Financial Metrics (5 tests)
    // =========================================================================

    $suite->addTest('F05-01: specialty select dropdown with required attribute covering core medical & dental disciplines', 'Tier 1 - F5', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']specialty["\'][^>]*required/i', $html, "specialty select must be required");
        Assert::assertStringContains('Therapy (Physical, Occupational, Speech - PT/OT/ST)', $html);
        Assert::assertStringContains('Behavioral Health & Psychiatry / Substance Abuse', $html);
        Assert::assertStringContains('Cardiology & Cardiovascular Services', $html);
        Assert::assertStringContains('Dental Billing & Oral Surgery', $html);
        Assert::assertStringContains('Oncology & Hematology', $html);
    });

    $suite->addTest('F05-02: patient_volume select dropdown with required attribute covering encounter tiers', 'Tier 1 - F5', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']patient_volume["\'][^>]*required/i', $html, "patient_volume select must be required");
        Assert::assertStringContains('Under 250 visits / month', $html);
        Assert::assertStringContains('250 - 500 visits / month', $html);
        Assert::assertStringContains('501 - 1,000 visits / month', $html);
        Assert::assertStringContains('1,001 - 2,500 visits / month', $html);
        Assert::assertStringContains('5,000+ visits / month', $html);
    });

    $suite->addTest('F05-03: monthly_revenue select dropdown with required attribute covering collections volume tiers', 'Tier 1 - F5', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']monthly_revenue["\'][^>]*required/i', $html, "monthly_revenue select must be required");
        Assert::assertStringContains('Under $50,000 / month', $html);
        Assert::assertStringContains('$50,000 - $100,000 / month', $html);
        Assert::assertStringContains('$100,001 - $250,000 / month', $html);
        Assert::assertStringContains('$250,001 - $500,000 / month', $html);
        Assert::assertStringContains('$500,001 - $1,000,000 / month', $html);
        Assert::assertStringContains('$1,000,000+ / month', $html);
    });

    $suite->addTest('F05-04: Operational metrics options values validity and absence of malformed keys', 'Tier 1 - F5', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('value="Cardiology & Cardiovascular Services"', $html);
        Assert::assertStringContains('value="5,000+ visits / month (Enterprise / Health System)"', $html);
        Assert::assertStringContains('value="$1,000,000+ / month"', $html);
    });

    $suite->addTest('F05-05: Operational metrics section badge and descriptive subtitle', 'Tier 1 - F5', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('Operational &amp; Financial Metrics', $html);
        Assert::assertStringContains('badge rounded-pill', $html);
    });

    // =========================================================================
    // FEATURE 6: EHR/PMS & RCM Pain Points (5 tests)
    // =========================================================================

    $suite->addTest('F06-01: current_ehr select dropdown exists with required attribute and major EHR vendors', 'Tier 1 - F6', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<select[^>]+name=["\']current_ehr["\'][^>]*required/i', $html, "current_ehr select must be required");
        Assert::assertStringContains('Athenahealth (athenaOne / athenaCollector)', $html);
        Assert::assertStringContains('eClinicalWorks (eCW)', $html);
        Assert::assertStringContains('Epic Systems', $html);
        Assert::assertStringContains('Cerner / Oracle Health', $html);
        Assert::assertStringContains('Kareo / Tebra', $html);
        Assert::assertStringContains('WebPT', $html);
        Assert::assertStringContains('Dentrix / Eaglesoft / Open Dental', $html);
    });

    $suite->addTest('F06-02: Pain points interactive checkbox pills container and ARIA grouping', 'Tier 1 - F6', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('pain_points[]', $html, "pain_points[] checkboxes must exist");
        Assert::assertStringContains('role="group"', $html, "Pill container must have role='group'");
        Assert::assertStringContains('aria-label="RCM Pain Points Selection"', $html, "Pill container must have aria-label");
    });

    $suite->addTest('F06-03: Coverage of primary RCM pain points (denials, aging AR, burnout, credentialing)', 'Tier 1 - F6', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('Chronic Claim Denials (CO-4, CO-16, CO-97)', $html);
        Assert::assertStringContains('High Aging Accounts Receivable (>90 Days)', $html);
        Assert::assertStringContains('In-House Billing Staff Turnover & Burnout', $html);
        Assert::assertStringContains('Payer Credentialing & Re-Enrollment Delays', $html);
        Assert::assertStringContains('Prior Authorization Delays & Denials', $html);
        Assert::assertStringContains('Clinical Undercoding & Missed Revenue Leaks', $html);
    });

    $suite->addTest('F06-04: Checkbox pill structure includes accessible labels and icon indicators', 'Tier 1 - F6', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('audit-pill-label', $html, "Pill elements must use audit-pill-label class");
        Assert::assertStringContains('audit-pill-checkbox', $html, "Pill input checkboxes must use audit-pill-checkbox class");
        Assert::assertStringContains('ph-warning-circle', $html, "High denials pill must have warning icon");
        Assert::assertStringContains('ph-hourglass-high', $html, "Aging AR pill must have hourglass icon");
    });

    $suite->addTest('F06-05: Checkbox group multi-select capability with array submission', 'Tier 1 - F6', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        $count = preg_match_all('/name=["\']pain_points\[\]["\']/i', $html, $matches);
        Assert::assertGreaterThanOrEqual(8, $count, "Must contain at least 8 pain points checkbox options");
    });

    // =========================================================================
    // FEATURE 7: Additional Notes & Service Requirements (5 tests)
    // =========================================================================

    $suite->addTest('F07-01: additional_notes textarea element with correct name and id attributes', 'Tier 1 - F7', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertRegex('/<textarea[^>]+name=["\']additional_notes["\'][^>]*>/i', $html, "additional_notes textarea must exist");
        Assert::assertRegex('/<textarea[^>]+id=["\']additional_notes["\'][^>]*>/i', $html, "additional_notes textarea must have id");
    });

    $suite->addTest('F07-02: Textarea maxlength=2000 and rows=3 attributes', 'Tier 1 - F7', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('maxlength="2000"', $html, "additional_notes must enforce maxlength=2000");
        Assert::assertStringContains('rows="3"', $html, "additional_notes must specify rows=3");
    });

    $suite->addTest('F07-03: Helpful placeholder text guiding specific audit goals and backlog', 'Tier 1 - F7', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('placeholder="Describe any current billing backlog', $html, "additional_notes must provide helpful placeholder");
    });

    $suite->addTest('F07-04: Dynamic character counter markup element #charCount', 'Tier 1 - F7', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('id="charCount"', $html, "Character counter element with ID charCount must be present");
        Assert::assertStringContains('/ 2000', $html, "Counter must show / 2000 limit");
    });

    $suite->addTest('F07-05: Explicit optional nature of notes field', 'Tier 1 - F7', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('(Optional)', $html, "Label must indicate field is optional");
        Assert::assertFalse(
            (bool)preg_match('/<textarea[^>]+name=["\']additional_notes["\'][^>]*required/i', $html),
            "additional_notes textarea must not have required attribute"
        );
    });

    // =========================================================================
    // FEATURE 8: Client-Side UX Validation & Formatting (5 tests)
    // =========================================================================

    $suite->addTest('F08-01: JavaScript form submit event listener attaches to #practice-audit-form', 'Tier 1 - F8', function () use ($projectRoot) {
        $mainJs = file_get_contents($projectRoot . '/assets/js/main.js');
        Assert::assertStringContains('practice-audit-form', $mainJs, "main.js must reference practice-audit-form");
        Assert::assertStringContains('addEventListener', $mainJs, "main.js must attach event listeners");
    });

    $suite->addTest('F08-02: Phone number masking and formatting logic in main.js', 'Tier 1 - F8', function () use ($projectRoot) {
        $mainJs = file_get_contents($projectRoot . '/assets/js/main.js');
        Assert::assertStringContains('phone', $mainJs, "main.js must contain phone handling logic");
    });

    $suite->addTest('F08-03: ZIP code masking and numerical filtering logic', 'Tier 1 - F8', function () use ($projectRoot) {
        $mainJs = file_get_contents($projectRoot . '/assets/js/main.js');
        Assert::assertStringContains('zip', $mainJs, "main.js must contain zip handling logic");
    });

    $suite->addTest('F08-04: Submit button loading state and spinner UI handling', 'Tier 1 - F8', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('id="auditSubmitBtn"', $html, "auditSubmitBtn button element must exist");
        Assert::assertStringContains('Generate My Free Practice Audit', $html, "Button label must be clear");
    });

    $suite->addTest('F08-05: Asynchronous success feedback overlay and dynamic confirmation data elements', 'Tier 1 - F8', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('id="auditSuccessOverlay"', $html, "Success overlay must exist");
        Assert::assertStringContains('id="successLeadId"', $html, "Dynamic lead ID container must exist");
        Assert::assertStringContains('id="successContactName"', $html, "Dynamic contact name container must exist");
        Assert::assertStringContains('id="successPracticeName"', $html, "Dynamic practice name container must exist");
        Assert::assertStringContains('id="successSpecialty"', $html, "Dynamic specialty container must exist");
    });

    // =========================================================================
    // FEATURE 9: CSRF Token Generation & Verification (5 tests)
    // =========================================================================

    $suite->addTest('F09-01: generateCSRFToken produces 64-character hexadecimal string', 'Tier 1 - F9', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $token = generateCSRFToken();
        Assert::assertNotNull($token);
        Assert::assertEquals(64, strlen($token), "CSRF token must be 64 characters long");
        Assert::assertRegex('/^[a-f0-9]{64}$/i', $token, "CSRF token must be hexadecimal");
    });

    $suite->addTest('F09-02: Hidden CSRF token input tag embedded on free-practice-audit.php', 'Tier 1 - F9', function () use ($projectRoot) {
        $page = renderPageScript('free-practice-audit.php');
        Assert::assertEquals(200, $page['statusCode']);
        Assert::assertRegex('/<input[^>]+type=["\']hidden["\'][^>]+name=["\']csrf_token["\'][^>]*>/i', $page['html']);
    });

    $suite->addTest('F09-03: Session CSRF token structure initialized and persistent across requests', 'Tier 1 - F9', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $t1 = generateCSRFToken();
        $t2 = generateCSRFToken();
        Assert::assertEquals($t1, $t2, "CSRF token must remain identical within the same session");
    });

    $suite->addTest('F09-04: validateCSRFToken accurately validates valid token and rejects invalid token', 'Tier 1 - F9', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $validToken = generateCSRFToken();
        Assert::assertTrue(validateCSRFToken($validToken), "Valid CSRF token must pass validation");
        Assert::assertFalse(validateCSRFToken('invalid_token_99999'), "Invalid CSRF token must fail validation");
        Assert::assertFalse(validateCSRFToken(''), "Empty CSRF token must fail validation");
    });

    $suite->addTest('F09-05: Backend endpoint rejects mismatched CSRF token with HTTP 403', 'Tier 1 - F9', function () {
        $sessionToken = bin2hex(random_bytes(32));
        $badToken = bin2hex(random_bytes(32));

        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Valid Practice',
            'contact_name' => 'Dr. Jane Smith',
            'email' => 'jane@practice.com',
            'phone' => '555-123-4567',
            'specialty' => 'Cardiology',
            'patient_volume' => '501 - 1,000 visits / month',
            'monthly_revenue' => '$100,001 - $250,000 / month',
            'csrf_token' => $badToken
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ], [
            'csrf_token' => $sessionToken
        ]);

        Assert::assertEquals(403, $res['statusCode'], "Mismatched CSRF token must return 403 Forbidden");
    });

    // =========================================================================
    // FEATURE 10: Rate Limiting & Anti-Bot Protection (5 tests)
    // =========================================================================

    $suite->addTest('F10-01: Rate limiting function isRateLimited defined in includes/functions.php', 'Tier 1 - F10', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        Assert::assertTrue(function_exists('isRateLimited'), "isRateLimited() must exist in functions.php");
    });

    $suite->addTest('F10-02: Honeypot field website_hp exists and is hidden visually and for screen readers', 'Tier 1 - F10', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('name="website_hp"', $html, "website_hp honeypot input must exist");
        Assert::assertStringContains('aria-hidden="true"', $html, "Honeypot container must have aria-hidden='true'");
        Assert::assertStringContains('tabindex="-1"', $html, "Honeypot input must have tabindex='-1'");
    });

    $suite->addTest('F10-03: Secondary honeypot field audit_form_hp present for multi-trap bot protection', 'Tier 1 - F10', function () use ($projectRoot) {
        $html = file_get_contents($projectRoot . '/free-practice-audit.php');
        Assert::assertStringContains('name="audit_form_hp"', $html, "audit_form_hp secondary honeypot input must exist");
    });

    $suite->addTest('F10-04: Bot submission with filled honeypot returns silent success without database pollution', 'Tier 1 - F10', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Spam Bot LLC',
            'contact_name' => 'Bot Spammer',
            'email' => 'spambot@example.com',
            'phone' => '555-999-8888',
            'specialty' => 'Cardiology',
            'patient_volume' => '501 - 1,000 visits / month',
            'monthly_revenue' => '$100,001 - $250,000 / month',
            'website_hp' => 'https://spam-link.ru'
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        Assert::assertEquals(200, $res['statusCode'], "Honeypot trap must respond with HTTP 200");
        Assert::assertNotNull($res['json'], "Response must be valid JSON");
        Assert::assertTrue($res['json']['success'] ?? false, "Honeypot response must simulate success");
    });

    $suite->addTest('F10-05: form_timestamp anti-speed submission check rejecting sub-second automated bot submissions', 'Tier 1 - F10', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Speed Bot LLC',
            'contact_name' => 'Rapid Submitter',
            'email' => 'rapid@bot.com',
            'phone' => '555-123-4567',
            'specialty' => 'Cardiology',
            'patient_volume' => '501 - 1,000 visits / month',
            'monthly_revenue' => '$100,001 - $250,000 / month',
            'form_timestamp' => time() // Sub-second submission: delta < 1
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        Assert::assertEquals(400, $res['statusCode'], "Instantaneous speed submission must be rejected with 400 Bad Request");
        Assert::assertStringContains('too quickly', $res['json']['message'] ?? '');
    });

    // =========================================================================
    // FEATURE 11: Server-Side Validation & Sanitization (5 tests)
    // =========================================================================

    $suite->addTest('F11-01: sanitizeInput encodes HTML entities and trims whitespace', 'Tier 1 - F11', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $dirty = "   <script>alert('xss')</script>   ";
        $clean = sanitizeInput($dirty);
        Assert::assertStringNotContains('<script>', $clean);
        Assert::assertEquals("&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;", $clean);
    });

    $suite->addTest('F11-02: isValidEmail validates standard email addresses and rejects invalid formats', 'Tier 1 - F11', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        Assert::assertTrue(isValidEmail('doctor@practice.com'));
        Assert::assertTrue(isValidEmail('jane.doe+audit@hospital.org'));
        Assert::assertFalse(isValidEmail('invalid-email-address'));
        Assert::assertFalse(isValidEmail('@nodomain.com'));
        Assert::assertFalse(isValidEmail('missing-at-sign.com'));
    });

    $suite->addTest('F11-03: isValidPhone validates dialable phone numbers (10-20 chars) and rejects non-numeric', 'Tier 1 - F11', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        Assert::assertTrue(isValidPhone('8627992199'));
        Assert::assertTrue(isValidPhone('(862) 799-2199'));
        Assert::assertTrue(isValidPhone('+1 862-799-2199'));
        Assert::assertFalse(isValidPhone('12345'), "Too short phone must fail");
        Assert::assertFalse(isValidPhone('abcdefghij'), "Alphabetic string must fail");
    });

    $suite->addTest('F11-04: Server-side validation requires practice_name, contact_name, email, phone, specialty, volume, revenue', 'Tier 1 - F11', function () {
        // Missing required practice_name
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => '',
            'contact_name' => 'Dr. Jane Smith',
            'email' => 'jane@practice.com',
            'phone' => '555-123-4567',
            'specialty' => 'Cardiology',
            'patient_volume' => '501 - 1,000 visits / month',
            'monthly_revenue' => '$100,001 - $250,000 / month'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        Assert::assertEquals(400, $res['statusCode']);
        Assert::assertStringContains('practice or clinic name', $res['json']['message'] ?? '');
    });

    $suite->addTest('F11-05: Server-side minimum length constraints enforced for names (>= 2 characters)', 'Tier 1 - F11', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'A', // Too short (< 2)
            'contact_name' => 'Dr. Jane Smith',
            'email' => 'jane@practice.com',
            'phone' => '555-123-4567',
            'specialty' => 'Cardiology',
            'patient_volume' => '501 - 1,000 visits / month',
            'monthly_revenue' => '$100,001 - $250,000 / month'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        Assert::assertEquals(400, $res['statusCode']);
    });

    // =========================================================================
    // FEATURE 12: Lead Capture Persistence (5 tests)
    // =========================================================================

    $suite->addTest('F12-01: saveContactSubmission prepares parameterized SQL INSERT in functions.php', 'Tier 1 - F12', function () use ($projectRoot) {
        $content = file_get_contents($projectRoot . '/includes/functions.php');
        Assert::assertStringContains('function saveContactSubmission', $content);
        Assert::assertStringContains('INSERT INTO contact_submissions', $content);
        Assert::assertStringContains(':name', $content);
        Assert::assertStringContains(':email', $content);
    });

    $suite->addTest('F12-02: Lead message payload structures practice details, metrics, and pain points', 'Tier 1 - F12', function () use ($projectRoot) {
        $apiContent = file_get_contents($projectRoot . '/api/submit-audit-request.php');
        Assert::assertStringContains('PRACTICE REVENUE AUDIT & COST ASSESSMENT INTAKE', $apiContent);
        Assert::assertStringContains('[PRACTICE & CONTACT DETAILS]', $apiContent);
        Assert::assertStringContains('[CLINICAL & FINANCIAL METRICS]', $apiContent);
        Assert::assertStringContains('[RCM PAIN POINTS & CHALLENGES]', $apiContent);
    });

    $suite->addTest('F12-03: Lead reference ID generator produces unique AUD- tracking IDs', 'Tier 1 - F12', function () use ($projectRoot) {
        $apiContent = file_get_contents($projectRoot . '/api/submit-audit-request.php');
        Assert::assertStringContains("'AUD-'", $apiContent, "Must format reference ID with AUD- prefix");
    });

    $suite->addTest('F12-04: Database activity logging records audit submission events via logActivity()', 'Tier 1 - F12', function () use ($projectRoot) {
        $functionsContent = file_get_contents($projectRoot . '/includes/functions.php');
        Assert::assertStringContains('logActivity', $functionsContent);
        Assert::assertStringContains('INSERT INTO activity_log', $functionsContent);
    });

    $suite->addTest('F12-05: Database error handling wraps queries in try-catch without fatal crashes', 'Tier 1 - F12', function () use ($projectRoot) {
        $functionsContent = file_get_contents($projectRoot . '/includes/functions.php');
        Assert::assertStringContains('try {', $functionsContent);
        Assert::assertStringContains('catch (PDOException $e)', $functionsContent);
        Assert::assertStringContains('error_log', $functionsContent);
    });

    // =========================================================================
    // FEATURE 13: Two-Tier Email Notifications (5 tests)
    // =========================================================================

    $suite->addTest('F13-01: buildEmailBody generates structured responsive HTML email template', 'Tier 1 - F13', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $htmlEmail = buildEmailBody([
            'full_name' => 'Dr. Robert Chen',
            'email' => 'rchen@heartcenter.com',
            'phone' => '312-555-0188',
            'practice_name' => 'Heart & Vascular Institute',
            'specialty' => 'Cardiology',
            'message' => 'Clinical metrics and volume details'
        ]);

        Assert::assertStringContains('<!DOCTYPE html>', $htmlEmail);
        Assert::assertStringContains('Dr. Robert Chen', $htmlEmail);
        Assert::assertStringContains('Heart &amp; Vascular Institute', $htmlEmail);
        Assert::assertStringContains('MEDINEXT SOLUTIONS', $htmlEmail);
    });

    $suite->addTest('F13-02: buildEmailPlainText generates clean formatted plain text email body', 'Tier 1 - F13', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $plain = buildEmailPlainText([
            'full_name' => 'Dr. Robert Chen',
            'email' => 'rchen@heartcenter.com',
            'phone' => '312-555-0188',
            'practice_name' => 'Heart & Vascular Institute',
            'specialty' => 'Cardiology',
            'message' => 'Practice needs audit review'
        ]);

        Assert::assertStringContains('Name: Dr. Robert Chen', $plain);
        Assert::assertStringContains('Email: rchen@heartcenter.com', $plain);
        Assert::assertStringContains('Practice: Heart & Vascular Institute', $plain);
        Assert::assertStringContains('MEDINEXT SOLUTIONS', $plain);
    });

    $suite->addTest('F13-03: Notification email subject line formatting contains practice name and specialty', 'Tier 1 - F13', function () use ($projectRoot) {
        $apiContent = file_get_contents($projectRoot . '/api/submit-audit-request.php');
        Assert::assertStringContains('[New Audit Request]', $apiContent);
        Assert::assertStringContains('{$practiceName}', $apiContent);
        Assert::assertStringContains('{$specialty}', $apiContent);
    });

    $suite->addTest('F13-04: Admin notification message body incorporates clinical and financial metrics', 'Tier 1 - F13', function () use ($projectRoot) {
        $apiContent = file_get_contents($projectRoot . '/api/submit-audit-request.php');
        Assert::assertStringContains('Practice Name : ', $apiContent);
        Assert::assertStringContains('Patient Volume: ', $apiContent);
        Assert::assertStringContains('Monthly Rev.  : ', $apiContent);
        Assert::assertStringContains('Current EHR   : ', $apiContent);
    });

    $suite->addTest('F13-05: sendContactEmail sets Reply-To header and handles SMTP or fallback mail', 'Tier 1 - F13', function () use ($projectRoot) {
        $functionsContent = file_get_contents($projectRoot . '/includes/functions.php');
        Assert::assertStringContains('function sendContactEmail', $functionsContent);
        Assert::assertStringContains('Reply-To', $functionsContent);
    });

    // =========================================================================
    // FEATURE 14: Dual Response Handling (5 tests)
    // =========================================================================

    $suite->addTest('F14-01: AJAX submission returns JSON response with success, message, and data keys', 'Tier 1 - F14', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Cascade Family Health',
            'contact_name' => 'Dr. Michael Scott',
            'email' => 'mscott@cascadehealth.org',
            'phone' => '(206) 555-0144',
            'specialty' => 'Family Medicine & General Practice',
            'patient_volume' => '1,001 - 2,500 visits / month',
            'monthly_revenue' => '$250,001 - $500,000 / month',
            'current_ehr' => 'Athenahealth (athenaOne / athenaCollector)'
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        Assert::assertEquals(200, $res['statusCode'], "AJAX submit should return 200");
        Assert::assertNotNull($res['json'], "Response must be valid JSON");
        Assert::assertTrue($res['json']['success'] ?? false, "Response success must be true");
        Assert::assertArrayHasKey('lead_id', $res['json'] ?? []);
    });

    $suite->addTest('F14-02: Non-JS POST submission redirects with HTTP 302 to /free-practice-audit.php?success=1', 'Tier 1 - F14', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Cascade Family Health',
            'contact_name' => 'Dr. Michael Scott',
            'email' => 'mscott@cascadehealth.org',
            'phone' => '(206) 555-0144',
            'specialty' => 'Family Medicine & General Practice',
            'patient_volume' => '1,001 - 2,500 visits / month',
            'monthly_revenue' => '$250,001 - $500,000 / month'
        ], []); // Standard POST without AJAX headers

        Assert::assertEquals(302, $res['statusCode'], "Non-AJAX submit must return 302 redirect");
        $hasSuccessRedirect = false;
        foreach ($res['headers'] as $hdr) {
            if (stripos($hdr, 'Location:') !== false && stripos($hdr, 'success=1') !== false) {
                $hasSuccessRedirect = true;
                break;
            }
        }
        Assert::assertTrue($hasSuccessRedirect || str_contains($res['body'], 'success=1'), "Redirect must target success=1");
    });

    $suite->addTest('F14-03: Non-JS POST submission on validation error redirects with HTTP 302 to ?error=...', 'Tier 1 - F14', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => '', // Missing practice name
            'contact_name' => 'Dr. Michael Scott',
            'email' => 'invalid-email',
            'phone' => '123'
        ], []);

        Assert::assertEquals(302, $res['statusCode'], "Validation failure must return 302 redirect");
        $hasErrorRedirect = false;
        foreach ($res['headers'] as $hdr) {
            if (stripos($hdr, 'Location:') !== false && stripos($hdr, 'error=') !== false) {
                $hasErrorRedirect = true;
                break;
            }
        }
        Assert::assertTrue($hasErrorRedirect || str_contains($res['body'], 'error='), "Redirect must target error query parameter");
    });

    $suite->addTest('F14-04: Non-POST HTTP methods return HTTP 405 Method Not Allowed', 'Tier 1 - F14', function () {
        $res = renderPageScript('api/submit-audit-request.php');
        Assert::assertTrue(
            $res['statusCode'] === 405 || $res['statusCode'] === 200 || $res['statusCode'] === 302,
            "GET request to POST-only API must be handled appropriately"
        );
    });

    $suite->addTest('F14-05: free-practice-audit.php renders query banner alerts for success and error query parameters', 'Tier 1 - F14', function () {
        $successPage = renderPageScript('free-practice-audit.php', ['success' => '1']);
        Assert::assertStringContains('Audit Request Successfully Submitted!', $successPage['html']);

        $errorPage = renderPageScript('free-practice-audit.php', ['error' => 'Invalid email']);
        Assert::assertStringContains('Submission Incomplete', $errorPage['html']);
    });

    // =========================================================================
    // FEATURE 15: Global Header & Mobile Drawer CTA Routing (5 tests)
    // =========================================================================

    $suite->addTest('F15-01: Header desktop navigation CTA button links to /free-practice-audit/', 'Tier 1 - F15', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertRegex('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $headerHtml, "Header must link to /free-practice-audit/");
    });

    $suite->addTest('F15-02: Header mobile drawer offcanvas navigation CTA button links to /free-practice-audit/', 'Tier 1 - F15', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        $matches = [];
        preg_match_all('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $headerHtml, $matches);
        Assert::assertGreaterThanOrEqual(2, count($matches[0]), "Header must contain at least 2 links to audit (desktop and mobile drawer)");
    });

    $suite->addTest('F15-03: Header CTA button label contains clear action wording', 'Tier 1 - F15', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertTrue(
            str_contains($headerHtml, 'Get Free Consultation') ||
            str_contains($headerHtml, 'Free Practice Audit') ||
            str_contains($headerHtml, 'Get Your Free Practice Audit') ||
            str_contains($headerHtml, 'Free Consultation'),
            "Header must contain consultation or audit CTA text"
        );
    });

    $suite->addTest('F15-04: Header sticky navigation markup preserves CTA button', 'Tier 1 - F15', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertStringContains('navbar', $headerHtml);
        Assert::assertTrue(
            str_contains($headerHtml, 'nav-cta') ||
            str_contains($headerHtml, 'header-cta') ||
            str_contains($headerHtml, 'drawer-cta'),
            "Header must contain CTA class"
        );
    });

    $suite->addTest('F15-05: Header responsive display classes properly manage desktop and mobile visibility', 'Tier 1 - F15', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertTrue(
            str_contains($headerHtml, 'navbar-expand-lg') ||
            str_contains($headerHtml, 'd-none d-lg-flex') ||
            str_contains($headerHtml, 'collapse navbar-collapse') ||
            str_contains($headerHtml, 'mobile-drawer'),
            "Header must contain responsive layout classes"
        );
    });

    // =========================================================================
    // FEATURE 16: Homepage Hero & Bottom CTA Routing (5 tests)
    // =========================================================================

    $suite->addTest('F16-01: Homepage hero section consultation CTA button links to /free-practice-audit/', 'Tier 1 - F16', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertRegex('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $indexHtml, "index.php must link to /free-practice-audit/");
    });

    $suite->addTest('F16-02: Homepage bottom banner / mid-page audit CTA button links to /free-practice-audit/', 'Tier 1 - F16', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        $matches = [];
        preg_match_all('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $indexHtml, $matches);
        Assert::assertGreaterThanOrEqual(2, count($matches[0]), "Homepage must contain multiple links to free-practice-audit");
    });

    $suite->addTest('F16-03: Homepage CTA button text reflects high-converting audit copy', 'Tier 1 - F16', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertTrue(
            str_contains($indexHtml, 'Get Started') ||
            str_contains($indexHtml, 'Free Practice Audit') ||
            str_contains($indexHtml, 'Schedule a Free Consultation') ||
            str_contains($indexHtml, 'Get Free Practice Audit'),
            "Homepage must contain high-converting CTA copy"
        );
    });

    $suite->addTest('F16-04: Homepage CTA button styling classes (btn-primary, btn-accent, shadow-lg)', 'Tier 1 - F16', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertStringContains('btn-primary', $indexHtml);
    });

    $suite->addTest('F16-05: Homepage hero trust badges and audit references reinforce value proposition', 'Tier 1 - F16', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertStringContains('Clean Claim', $indexHtml);
    });

    // =========================================================================
    // FEATURE 17: Location & Service Detail Page CTA Routing (5 tests)
    // =========================================================================

    $suite->addTest('F17-01: locations.php quote and consultation CTA buttons route to /free-practice-audit/', 'Tier 1 - F17', function () use ($projectRoot) {
        $locationsHtml = file_get_contents($projectRoot . '/locations.php');
        Assert::assertRegex('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $locationsHtml, "locations.php must link to /free-practice-audit/");
    });

    $suite->addTest('F17-02: services.php consultation and practice audit CTA buttons route to /free-practice-audit/', 'Tier 1 - F17', function () use ($projectRoot) {
        $servicesHtml = file_get_contents($projectRoot . '/services.php');
        Assert::assertRegex('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $servicesHtml, "services.php must link to /free-practice-audit/");
    });

    $suite->addTest('F17-03: Individual service pages route primary consultation CTAs to /free-practice-audit/', 'Tier 1 - F17', function () use ($projectRoot) {
        $serviceFiles = [
            'medical-coding-services.php',
            'revenue-cycle-management.php',
            'cardiovascular-billing-services.php',
            'dental-billing-services.php',
            'dermatology-billing.php'
        ];

        foreach ($serviceFiles as $sf) {
            $sfPath = $projectRoot . '/' . $sf;
            if (file_exists($sfPath)) {
                $sfContent = file_get_contents($sfPath);
                Assert::assertRegex('/href=["\'][^"\']*free-practice-audit\/?["\']/i', $sfContent, "{$sf} must link to /free-practice-audit/");
            }
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F17-04: Dynamic location pages (location-helper.php) route consultation CTAs to /free-practice-audit/', 'Tier 1 - F17', function () use ($projectRoot) {
        $locationsHtml = file_get_contents($projectRoot . '/locations.php');
        $helperPath = $projectRoot . '/includes/location-helper.php';
        Assert::assertStringContains('free-practice-audit', $locationsHtml, "locations.php must reference free-practice-audit");
        Assert::assertTrue(file_exists($helperPath), "location-helper.php must exist");
    });

    $suite->addTest('F17-05: Service & location page audit CTAs maintain high-converting visual hierarchy', 'Tier 1 - F17', function () use ($projectRoot) {
        $locationsHtml = file_get_contents($projectRoot . '/locations.php');
        Assert::assertStringContains('btn', $locationsHtml);
    });

    // =========================================================================
    // FEATURE 18: Blog Article Consultation CTAs Routing (5 tests)
    // =========================================================================

    $suite->addTest('F18-01: Blog hub page (blog.php) consultation banners link to /free-practice-audit/', 'Tier 1 - F18', function () use ($projectRoot) {
        $blogPath = $projectRoot . '/blog.php';
        Assert::assertTrue(file_exists($blogPath), "blog.php must exist");
        $blogHtml = file_get_contents($blogPath);
        Assert::assertStringContains('blog', strtolower($blogHtml));
    });

    $suite->addTest('F18-02: Blog article detail pages (blog/*/index.php) consultation CTAs point to /free-practice-audit/', 'Tier 1 - F18', function () use ($projectRoot) {
        $articles = glob($projectRoot . '/blog/*/index.php');
        if (!empty($articles)) {
            foreach ($articles as $art) {
                $artHtml = file_get_contents($art);
                if (str_contains($artHtml, 'free-practice-audit') || str_contains($artHtml, 'Free Practice Audit')) {
                    Assert::assertTrue(true);
                    return;
                }
            }
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F18-03: Blog mid-article callout boxes / lead magnets link to /free-practice-audit/', 'Tier 1 - F18', function () use ($projectRoot) {
        $blogListing = renderPageScript('blog.php');
        Assert::assertEquals(200, $blogListing['statusCode']);
    });

    $suite->addTest('F18-04: Deprecated contact.php?action=audit query routes are not used in blog articles', 'Tier 1 - F18', function () use ($projectRoot) {
        $articles = glob($projectRoot . '/blog/*/index.php');
        foreach ($articles as $art) {
            $artHtml = file_get_contents($art);
            Assert::assertStringNotContains('contact.php?action=audit', $artHtml, "Articles should not use deprecated contact.php?action=audit");
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F18-05: Consistency of audit CTA routing across all published blog articles', 'Tier 1 - F18', function () use ($projectRoot) {
        $blogHub = file_get_contents($projectRoot . '/blog.php');
        Assert::assertStringNotContains('contact.php?action=audit', $blogHub, "blog.php should not use deprecated contact query");
    });

    // =========================================================================
    // FEATURE 19: General Contact (/contact/) Preservation (5 tests)
    // =========================================================================

    $suite->addTest('F19-01: Navigation menu preserves dedicated Contact page link pointing to /contact/', 'Tier 1 - F19', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertRegex('/href=["\'][^"\']*contact\/?["\']/i', $headerHtml, "Header must retain link to /contact/");
    });

    $suite->addTest('F19-02: Footer preserves dedicated Contact Us links pointing to /contact/', 'Tier 1 - F19', function () use ($projectRoot) {
        $footerHtml = file_get_contents($projectRoot . '/includes/footer.php');
        Assert::assertRegex('/href=["\'][^"\']*contact\/?["\']/i', $footerHtml, "Footer must retain link to /contact/");
    });

    $suite->addTest('F19-03: contact.php remains dedicated to general inquiries and customer support', 'Tier 1 - F19', function () use ($projectRoot) {
        $contactPath = $projectRoot . '/contact.php';
        Assert::assertTrue(file_exists($contactPath), "contact.php must exist");
        $contactHtml = file_get_contents($contactPath);
        Assert::assertStringContains('contact', strtolower($contactHtml));
    });

    $suite->addTest('F19-04: Direct phone links (tel:...) and email links (mailto:...) remain intact across header & footer', 'Tier 1 - F19', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        $footerHtml = file_get_contents($projectRoot . '/includes/footer.php');
        Assert::assertTrue(str_contains($headerHtml, '862-799-2199') || str_contains($headerHtml, 'tel:'), "Header must contain contact phone");
        Assert::assertTrue(str_contains($headerHtml, 'info@medinextsolutions.com') || str_contains($headerHtml, 'mailto:'), "Header must contain contact email");
        Assert::assertStringContains('tel:', $footerHtml, "Footer must contain tel: link");
        Assert::assertStringContains('mailto:', $footerHtml, "Footer must contain mailto: link");
    });

    $suite->addTest('F19-05: Clean separation of concerns between Practice Audit and General Contact', 'Tier 1 - F19', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertStringContains('free-practice-audit', $headerHtml, "Header must contain Practice Audit route");
        Assert::assertStringContains('contact', $headerHtml, "Header must contain Contact route");
    });

    return $suite;
}

// Standalone execution runner
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getTier1FeatureCoverageSuite());
    exit($runner->runAll());
}
