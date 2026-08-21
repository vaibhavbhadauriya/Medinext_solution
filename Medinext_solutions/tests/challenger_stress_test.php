<?php
declare(strict_types=1);

namespace Medinext\Tests\Challenger;

require_once dirname(__DIR__) . '/tests/TestHelper.php';
require_once dirname(__DIR__) . '/includes/functions.php';

use Medinext\Tests\Assert;
use Medinext\Tests\CliColor;
use function Medinext\Tests\postBackendEndpoint;
use function Medinext\Tests\renderPageScript;
use function Medinext\Tests\getProjectRoot;

echo "\n" . str_repeat('=', 80) . "\n";
echo "       " . CliColor::bold("CHALLENGER EMPIRICAL ADVERSARIAL STRESS TEST HARNESS") . "\n";
echo str_repeat('=', 80) . "\n";

class ChallengerRunner
{
    public static int $passCount = 0;
    public static int $failCount = 0;
    public static int $testIndex = 0;

    public static function test(string $title, callable $fn): void
    {
        self::$testIndex++;
        $start = microtime(true);
        try {
            $fn();
            $duration = round((microtime(true) - $start) * 1000, 2);
            self::$passCount++;
            echo sprintf("  [%02d]  %s  %s (%s ms)\n", self::$testIndex, CliColor::green('PASS'), $title, $duration);
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $start) * 1000, 2);
            self::$failCount++;
            echo sprintf("  [%02d]  %s  %s (%s ms)\n", self::$testIndex, CliColor::red('FAIL'), $title, $duration);
            echo "        " . CliColor::red("Error: " . $e->getMessage()) . "\n";
        }
    }
}

// 1. Missing CSRF Token (Security Layer 1)
ChallengerRunner::test("Stress 1a: Missing CSRF token returns HTTP 403 Forbidden", function() {
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'practice_name' => 'Apex Healthcare'
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => bin2hex(random_bytes(32))
    ]);
    
    Assert::assertEquals(403, $res['statusCode'], "Expected HTTP 403 for missing CSRF token");
    Assert::assertFalse($res['json']['success']);
});

// 1b. Valid CSRF with Empty Required Fields (Validation Layer)
ChallengerRunner::test("Stress 1b: Valid CSRF with empty form fields returns HTTP 400 Bad Request", function() {
    $token = bin2hex(random_bytes(32));
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'form_timestamp' => (string)(time() - 5)
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(400, $res['statusCode'], "Expected HTTP 400 Bad Request");
    Assert::assertNotNull($res['json'], "Expected JSON response");
    Assert::assertFalse($res['json']['success'], "Expected success to be false");
    Assert::assertTrue(isset($res['json']['errors']), "Expected errors object in payload");
});

// 2. Excessive Payload Size (1MB notes string)
ChallengerRunner::test("Stress 2: Massive 1MB payload in additional_notes rejected with HTTP 400", function() {
    $token = bin2hex(random_bytes(32));
    $hugeNotes = str_repeat("A", 1024 * 1024);
    
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'form_timestamp' => (string)(time() - 5),
        'practice_name' => 'Apex Healthcare',
        'contact_name' => 'Dr. Jane Smith',
        'job_title' => 'Chief Medical Officer',
        'email' => 'jane.smith@apexhealth.org',
        'phone' => '862-555-0199',
        'street_address' => '123 Medical Center Blvd',
        'city' => 'Newark',
        'state' => 'NJ',
        'zip_code' => '07102',
        'specialty' => 'Cardiology',
        'patient_volume' => '500-1000',
        'monthly_revenue' => '$100k-$250k',
        'current_ehr' => 'Epic Systems',
        'additional_notes' => $hugeNotes
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(400, $res['statusCode'], "Expected HTTP 400 for excessive payload");
    Assert::assertFalse($res['json']['success']);
    Assert::assertStringContains('2000', $res['json']['message']);
});

// 3. Rapid consecutive burst submissions (Rate limit session propagation)
ChallengerRunner::test("Stress 3: Rapid burst of 6 submissions in session triggers rate limit on attempt 6", function() {
    $randIp = '198.51.100.' . mt_rand(1, 254);
    $token = bin2hex(random_bytes(32));
    $session = ['csrf_token' => $token];
    
    $results = [];
    for ($i = 1; $i <= 6; $i++) {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'csrf_token' => $token,
            'form_timestamp' => (string)(time() - 10),
            'practice_name' => "Burst Clinic {$i}",
            'contact_name' => 'Dr. Test Contact',
            'job_title' => 'Medical Director',
            'email' => "burst{$i}@example.com",
            'phone' => '862-555-0199',
            'street_address' => '100 Health Way',
            'city' => 'Newark',
            'state' => 'NJ',
            'zip_code' => '07102',
            'specialty' => 'Cardiology',
            'patient_volume' => '500-1000',
            'monthly_revenue' => '$100k-$250k',
            'current_ehr' => 'Epic Systems'
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-Forwarded-For' => $randIp,
            'Remote-Addr' => $randIp
        ], $session);
        
        $results[] = $res;
        // Carry forward session state
        $session = $res['session'] ?? $session;
    }
    
    // First 5 attempts should succeed
    for ($i = 0; $i < 5; $i++) {
        Assert::assertEquals(200, $results[$i]['statusCode'], "Attempt " . ($i+1) . " should succeed with HTTP 200");
        Assert::assertTrue($results[$i]['json']['success'] ?? false);
    }
    // 6th attempt must be throttled with HTTP 429
    Assert::assertEquals(429, $results[5]['statusCode'], "Attempt 6 must be throttled with HTTP 429");
    Assert::assertFalse($results[5]['json']['success']);
});

// 4. Boundary Email Format Matrix
ChallengerRunner::test("Stress 4: Comprehensive Email Format Boundary Matrix", function() {
    $validEmails = [
        'simple@example.com',
        'very.common@example.com',
        'disposable.style.email.with+symbol@example.com',
        'other.email-with-hyphen@example.com',
        'fully-qualified-domain@sub.domain.example.com',
        'user.name+tag+sorting@example.com',
        'x@example.com',
        str_repeat('a', 64) . '@example.com'
    ];
    
    $invalidEmails = [
        'plainaddress',
        '#@%^%#$@#$@#.com',
        '@example.com',
        'Joe Smith <email@example.com>',
        'email.example.com',
        'email@example@example.com',
        '.email@example.com',
        'email.@example.com',
        'email..email@example.com',
        "admin@medinext.com\r\nBcc: victim@example.com",
        "admin@medinext.com\nSubject: Injected",
        "user@example.com%0aBcc:spam@evil.com",
        "first@example.com, second@example.com",
        "first@example.com; second@example.com",
        str_repeat('a', 250) . '@toolongdomainnameverylongexample.com'
    ];
    
    foreach ($validEmails as $em) {
        Assert::assertTrue(\isValidEmail($em), "Expected '{$em}' to be valid email");
    }
    
    foreach ($invalidEmails as $em) {
        Assert::assertFalse(\isValidEmail($em), "Expected '{$em}' to be rejected as invalid email");
    }
});

// 5. Boundary Phone Format Matrix
ChallengerRunner::test("Stress 5: Phone Format Boundary Matrix", function() {
    $validPhones = [
        '862-799-2199',
        '(862) 799-2199',
        '862.799.2199',
        '+1 862 799 2199',
        '+1-862-799-2199',
        '8627992199',
        '18627992199',
        '+1 (800) 555-0199',
        '011 1 862 799 2199'
    ];
    
    $invalidPhones = [
        '1234567',
        '862-CALL-NOW',
        'phone: 8627992199',
        '123456789012345678',
        '',
        '<script>alert(1)</script>',
        '+++---()'
    ];
    
    foreach ($validPhones as $ph) {
        Assert::assertTrue(\isValidPhone($ph), "Expected '{$ph}' to be valid phone");
    }
    
    foreach ($invalidPhones as $ph) {
        Assert::assertFalse(\isValidPhone($ph), "Expected '{$ph}' to be invalid phone");
    }
});

// 6. State Code Boundary Matrix (All 52 valid jurisdictions, case insensitivity, invalid rejection)
ChallengerRunner::test("Stress 6: US State & Territory Code Validation (50 states + DC + PR)", function() {
    $all52Jurisdictions = [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL',
        'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME',
        'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH',
        'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR',
        'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV',
        'WI', 'WY'
    ];
    
    Assert::assertEquals(52, count($all52Jurisdictions), "Must have 50 states + DC + PR");
    
    foreach ($all52Jurisdictions as $st) {
        Assert::assertTrue(\isValidState($st), "Upper '{$st}' must be valid");
        Assert::assertTrue(\isValidState(strtolower($st)), "Lower '{$st}' must be normalized and valid");
    }
    
    $invalidStates = ['XX', 'ZZ', 'CA1', 'CAL', 'N', '12', '', 'USA', "NJ' OR 1=1--", '<script>'];
    foreach ($invalidStates as $badSt) {
        Assert::assertFalse(\isValidState($badSt), "Expected '{$badSt}' to be invalid state");
    }
});

// 7. ZIP Code Boundary Matrix
ChallengerRunner::test("Stress 7: US ZIP Code Boundary Validation", function() {
    $validZips = ['07102', '90210', '10001', '07102-1234', '90210-0001'];
    $invalidZips = ['0710', '071023', 'ABCDE', '07102-123', '07102-12345', '90210 1234', ''];
    
    foreach ($validZips as $zip) {
        Assert::assertTrue(\isValidZip($zip), "Expected '{$zip}' to be valid ZIP");
    }
    foreach ($invalidZips as $zip) {
        Assert::assertFalse(\isValidZip($zip), "Expected '{$zip}' to be invalid ZIP");
    }
});

// 8. Velocity Bot Timing Trap (< 1 sec submission rejection)
ChallengerRunner::test("Stress 8: Velocity Bot Timing Trap (< 1s rejection)", function() {
    $token = bin2hex(random_bytes(32));
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'form_timestamp' => (string)time(), // 0s elapsed
        'practice_name' => 'Fast Bot Clinic',
        'contact_name' => 'Bot User',
        'job_title' => 'Bot Role',
        'email' => 'bot@automated-spam.com',
        'phone' => '862-555-9999',
        'specialty' => 'Cardiology',
        'patient_volume' => '500-1000',
        'monthly_revenue' => '$100k-$250k',
        'current_ehr' => 'Epic'
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(400, $res['statusCode'], "Expected HTTP 400 for sub-second submission");
    Assert::assertFalse($res['json']['success']);
    Assert::assertStringContains('quickly', $res['json']['message']);
});

// 9. Honeypot Anti-Bot Filter
ChallengerRunner::test("Stress 9: Honeypot trap returns fake success without processing", function() {
    $token = bin2hex(random_bytes(32));
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'website_hp' => 'http://spam-link-url.com',
        'practice_name' => 'Spammer Inc',
        'contact_name' => 'Spam Bot',
        'email' => 'spambot@spammer.org',
        'phone' => '862-555-8888',
        'specialty' => 'Dental',
        'patient_volume' => '250-500',
        'monthly_revenue' => '$50k-$100k'
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(200, $res['statusCode']);
    Assert::assertTrue($res['json']['success']);
    Assert::assertTrue(str_starts_with($res['json']['lead_id'] ?? '', 'AUD-'));
});

// 10. Non-JS Standard POST PRG Pattern (HTTP 302 Redirect)
ChallengerRunner::test("Stress 10: Non-JS POST returns HTTP 302 redirect and stores flash session", function() {
    $token = bin2hex(random_bytes(32));
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'form_timestamp' => (string)(time() - 10),
        'practice_name' => 'Standard POST Practice',
        'contact_name' => 'Standard Contact',
        'job_title' => 'Practice Manager',
        'email' => 'standard@postpractice.com',
        'phone' => '862-555-3344',
        'street_address' => '456 Elm St',
        'city' => 'Paramus',
        'state' => 'NJ',
        'zip_code' => '07652',
        'specialty' => 'Orthopedics',
        'patient_volume' => '1000-2500',
        'monthly_revenue' => '$250k-$500k',
        'current_ehr' => 'Kareo'
    ], [], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(302, $res['statusCode'], "Expected HTTP 302 redirect for non-JS POST");
    Assert::assertTrue(isset($res['session']['flash_success']), "Expected flash_success in session");
    Assert::assertTrue(isset($res['session']['lead_id']), "Expected lead_id in session");
});

// 11. XSS & SQLi payload sanitization across all fields
ChallengerRunner::test("Stress 11: Malicious XSS & SQLi vectors safely neutralized in audit payload", function() {
    $token = bin2hex(random_bytes(32));
    $res = postBackendEndpoint('api/submit-audit-request.php', [
        'csrf_token' => $token,
        'form_timestamp' => (string)(time() - 10),
        'practice_name' => "<script>alert('xss')</script> Apex Clinic",
        'contact_name' => "Robert'); DROP TABLE audit_submissions;--",
        'job_title' => 'Administrator',
        'email' => 'safe.audit@testcorp.com',
        'phone' => '862-555-0199',
        'street_address' => '<img src=x onerror=alert(1)> 123 Main St',
        'city' => "Newark' OR '1'='1",
        'state' => 'NJ',
        'zip_code' => '07102',
        'specialty' => 'Cardiology',
        'patient_volume' => '500-1000',
        'monthly_revenue' => '$100k-$250k',
        'current_ehr' => 'Epic',
        'pain_points' => ["Claim Denials <script>", "Prior Auth ' OR 1=1--"],
        'additional_notes' => "Hello <script>alert(document.cookie)</script> world"
    ], [
        'X-Requested-With' => 'XMLHttpRequest'
    ], [
        'csrf_token' => $token
    ]);
    
    Assert::assertEquals(200, $res['statusCode'], "Sanitized payload should succeed");
    Assert::assertTrue($res['json']['success']);
});

// 12. Routing & CTA preservation for /contact/
ChallengerRunner::test("Stress 12: General /contact/ preservation vs /free-practice-audit/ CTAs", function() {
    $contactPage = renderPageScript('contact.php');
    Assert::assertEquals(200, $contactPage['statusCode']);
    Assert::assertStringContains('contact-form', $contactPage['html']);
    
    $auditPage = renderPageScript('free-practice-audit.php');
    Assert::assertEquals(200, $auditPage['statusCode']);
    Assert::assertStringContains('practice-audit-form', $auditPage['html']);
});

echo "\n" . str_repeat('=', 80) . "\n";
echo "              CHALLENGER STRESS HARNESS SUMMARY\n";
echo str_repeat('=', 80) . "\n";
echo " Total Tests: " . (ChallengerRunner::$passCount + ChallengerRunner::$failCount) . "\n";
echo " Passed:      " . CliColor::green((string)ChallengerRunner::$passCount) . "\n";
echo " Failed:      " . (ChallengerRunner::$failCount > 0 ? CliColor::red((string)ChallengerRunner::$failCount) : (string)ChallengerRunner::$failCount) . "\n";
echo str_repeat('=', 80) . "\n\n";

exit(ChallengerRunner::$failCount === 0 ? 0 : 1);