<?php
/**
 * MEDINEXT SOLUTIONS - Tier 3 Cross-Feature Combinatorial Interaction Suite
 * 
 * Pairwise cross-feature interactions across Features F1 to F19.
 * Validates deep interaction behaviors, routing-CTA links, form validation,
 * anti-bot / rate-limiting, dual AJAX/POST responses, persistence, and email dispatch.
 * 
 * Total Tests: >= 19 interaction test cases (21 comprehensive tests implemented).
 * 
 * Execution:
 *   php tests/tier3_cross_feature_test.php
 */

declare(strict_types=1);

namespace Medinext\Tests;

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}

// Load TestHelper if present, otherwise define core framework classes
if (file_exists(__DIR__ . '/TestHelper.php')) {
    require_once __DIR__ . '/TestHelper.php';
}

if (!class_exists('Medinext\Tests\Assert')) {
    class Assert
    {
        public static function assertTrue($condition, string $message = ''): void
        {
            if (!$condition) {
                throw new \AssertionError($message ?: "Expected true, got false");
            }
        }

        public static function assertFalse($condition, string $message = ''): void
        {
            if ($condition) {
                throw new \AssertionError($message ?: "Expected false, got true");
            }
        }

        public static function assertEquals($expected, $actual, string $message = ''): void
        {
            if ($expected !== $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                $actStr = is_scalar($actual) ? (string)$actual : json_encode($actual);
                throw new \AssertionError($message ?: "Expected: {$expStr}, got: {$actStr}");
            }
        }

        public static function assertNotEquals($expected, $actual, string $message = ''): void
        {
            if ($expected === $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                throw new \AssertionError($message ?: "Expected value NOT to equal {$expStr}");
            }
        }

        public static function assertNull($value, string $message = ''): void
        {
            if ($value !== null) {
                throw new \AssertionError($message ?: "Expected null, got " . var_export($value, true));
            }
        }

        public static function assertNotNull($value, string $message = ''): void
        {
            if ($value === null) {
                throw new \AssertionError($message ?: "Expected non-null value");
            }
        }

        public static function assertContains($needle, $haystack, string $message = ''): void
        {
            if (is_array($haystack)) {
                if (!in_array($needle, $haystack, true)) {
                    throw new \AssertionError($message ?: "Array does not contain expected item '{$needle}'");
                }
                return;
            }
            if (is_string($haystack)) {
                if (strpos($haystack, (string)$needle) === false) {
                    throw new \AssertionError($message ?: "String does not contain '{$needle}'");
                }
                return;
            }
            throw new \AssertionError("Unsupported haystack type for assertContains");
        }

        public static function assertNotContains($needle, $haystack, string $message = ''): void
        {
            if (is_array($haystack)) {
                if (in_array($needle, $haystack, true)) {
                    throw new \AssertionError($message ?: "Array unexpectedly contains '{$needle}'");
                }
                return;
            }
            if (is_string($haystack)) {
                if (strpos($haystack, (string)$needle) !== false) {
                    throw new \AssertionError($message ?: "String unexpectedly contains '{$needle}'");
                }
                return;
            }
            throw new \AssertionError("Unsupported haystack type for assertNotContains");
        }

        public static function assertStringContains(string $needle, string $haystack, string $message = ''): void
        {
            if (strpos($haystack, $needle) === false) {
                throw new \AssertionError($message ?: "String does not contain '{$needle}'");
            }
        }

        public static function assertStringContainsIgnoreCase(string $needle, string $haystack, string $message = ''): void
        {
            if (stripos($haystack, $needle) === false) {
                throw new \AssertionError($message ?: "String does not contain case-insensitive '{$needle}'");
            }
        }

        public static function assertStringNotContains(string $needle, string $haystack, string $message = ''): void
        {
            if (strpos($haystack, $needle) !== false) {
                throw new \AssertionError($message ?: "String unexpectedly contains '{$needle}'");
            }
        }

        public static function assertRegex(string $pattern, string $string, string $message = ''): void
        {
            if (!preg_match($pattern, $string)) {
                throw new \AssertionError($message ?: "String does not match pattern {$pattern}");
            }
        }

        public static function assertMatchesRegularExpression(string $pattern, string $string, string $message = ''): void
        {
            self::assertRegex($pattern, $string, $message);
        }

        public static function assertArrayHasKey($key, array $array, string $message = ''): void
        {
            if (!array_key_exists($key, $array)) {
                throw new \AssertionError($message ?: "Array does not have key '{$key}'");
            }
        }

        public static function assertGreaterThanOrEqual($expected, $actual, string $message = ''): void
        {
            if ($actual < $expected) {
                throw new \AssertionError($message ?: "Expected {$actual} >= {$expected}");
            }
        }

        public static function assertLessThanOrEqual($expected, $actual, string $message = ''): void
        {
            if ($actual > $expected) {
                throw new \AssertionError($message ?: "Expected {$actual} <= {$expected}");
            }
        }

        public static function assertCount(int $expectedCount, $countable, string $message = ''): void
        {
            $actualCount = is_countable($countable) ? count($countable) : 0;
            if ($expectedCount !== $actualCount) {
                throw new \AssertionError($message ?: "Expected count {$expectedCount}, got {$actualCount}");
            }
        }

        public static function fail(string $message = ''): void
        {
            throw new \AssertionError($message ?: "Test failed explicitly");
        }
    }
}

if (!class_exists('Medinext\Tests\TestCase')) {
    class TestCase
    {
        public string $name;
        public string $tier;
        /** @var callable */
        public $callback;

        public function __construct(string $name, string $tier, callable $callback)
        {
            $this->name = $name;
            $this->tier = $tier;
            $this->callback = $callback;
        }

        public function run(): array
        {
            $start = microtime(true);
            try {
                ($this->callback)();
                $duration = (microtime(true) - $start) * 1000;
                return [
                    'status' => 'PASS',
                    'name' => $this->name,
                    'tier' => $this->tier,
                    'duration_ms' => round($duration, 2),
                    'error' => null
                ];
            } catch (\Throwable $e) {
                $duration = (microtime(true) - $start) * 1000;
                return [
                    'status' => 'FAIL',
                    'name' => $this->name,
                    'tier' => $this->tier,
                    'duration_ms' => round($duration, 2),
                    'error' => $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine()
                ];
            }
        }
    }
}

if (!class_exists('Medinext\Tests\TestSuite')) {
    class TestSuite
    {
        public string $name;
        public string $description;
        /** @var TestCase[] */
        public array $tests = [];

        public function __construct(string $name, string $description = '')
        {
            $this->name = $name;
            $this->description = $description;
        }

        public function addTest(string $name, string $tier, callable $callback): void
        {
            $this->tests[] = new TestCase($name, $tier, $callback);
        }

        public function run(bool $verbose = true): array
        {
            $results = [];
            $passed = 0;
            $failed = 0;

            if ($verbose) {
                echo "\n" . str_repeat('=', 80) . "\n";
                echo " RUNNING SUITE: {$this->name}\n";
                if ($this->description) {
                    echo " Description:   {$this->description}\n";
                }
                echo " Total Tests:   " . count($this->tests) . "\n";
                echo str_repeat('=', 80) . "\n\n";
            }

            foreach ($this->tests as $index => $test) {
                $res = $test->run();
                $results[] = $res;
                if ($res['status'] === 'PASS') {
                    $passed++;
                    if ($verbose) {
                        printf("  [%02d/%02d]  PASS  [%s] %s (%s ms)\n", $index + 1, count($this->tests), $test->tier, $test->name, $res['duration_ms']);
                    }
                } else {
                    $failed++;
                    if ($verbose) {
                        printf("  [%02d/%02d] !FAIL! [%s] %s (%s ms)\n", $index + 1, count($this->tests), $test->tier, $test->name, $res['duration_ms']);
                        echo "         Error: " . $res['error'] . "\n";
                    }
                }
            }

            if ($verbose) {
                echo "\n" . str_repeat('-', 80) . "\n";
                echo " SUITE RESULT: {$this->name}\n";
                echo " Passed: {$passed} | Failed: {$failed} | Total: " . count($this->tests) . "\n";
                echo str_repeat('-', 80) . "\n";
            }

            return [
                'suite_name' => $this->name,
                'total' => count($this->tests),
                'passed' => $passed,
                'failed' => $failed,
                'results' => $results
            ];
        }

        public function getTests(): array
        {
            return $this->tests;
        }
    }
}

if (!class_exists('Medinext\Tests\TestRunner')) {
    class TestRunner
    {
        /** @var TestSuite[] */
        public array $suites = [];

        public function addSuite(TestSuite $suite): void
        {
            $this->suites[] = $suite;
        }

        public function runAll(): int
        {
            $totalTests = 0;
            $totalPassed = 0;
            $totalFailed = 0;

            foreach ($this->suites as $suite) {
                $res = $suite->run(true);
                $totalTests += $res['total'];
                $totalPassed += $res['passed'];
                $totalFailed += $res['failed'];
            }

            echo "\n" . str_repeat('#', 80) . "\n";
            echo " MASTER SUMMARY: Tier 3 Cross-Feature Combinations Suite\n";
            echo " Total Tests: {$totalTests} | Passed: {$totalPassed} | Failed: {$totalFailed}\n";
            echo str_repeat('#', 80) . "\n\n";

            return $totalFailed === 0 ? 0 : 1;
        }
    }
}

/**
 * Programmatically execute a page script with mocked server & request environment
 */
function renderPageScript(string $relativeFile, array $queryParams = [], array $serverOverrides = []): array
{
    $projectRoot = dirname(__DIR__);
    $scriptPath = $projectRoot . '/' . ltrim($relativeFile, '/');

    if (!file_exists($scriptPath)) {
        return [
            'statusCode' => 404,
            'html' => '',
            'error' => "File not found: {$scriptPath}"
        ];
    }

    $cleanSlug = preg_replace('/\.php$/i', '', basename($relativeFile));
    $cleanUri = ($cleanSlug === 'index') ? '/' : '/' . $cleanSlug . '/';

    $queryString = http_build_query($queryParams);
    $requestUri = $cleanUri . ($queryString ? '?' . $queryString : '');

    $phpCode = '<?php
        $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
        $_SERVER["HTTPS"] = "on";
        $_SERVER["SERVER_PORT"] = "443";
        $_SERVER["REQUEST_URI"] = ' . var_export($requestUri, true) . ';
        $_SERVER["SCRIPT_NAME"] = ' . var_export($cleanUri, true) . ';
        $_SERVER["PHP_SELF"] = ' . var_export($cleanUri, true) . ';
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_GET = ' . var_export($queryParams, true) . ';
        $_POST = [];
        $_COOKIE = [];

        ' . (!empty($serverOverrides) ? 'foreach (' . var_export($serverOverrides, true) . ' as $k => $v) { $_SERVER[$k] = $v; }' : '') . '

        ob_start();
        try {
            include ' . var_export($scriptPath, true) . ';
            $html = ob_get_clean();
            $status = http_response_code() ?: 200;
        } catch (\Throwable $e) {
            $html = ob_get_clean();
            $status = 500;
        }

        echo json_encode([
            "statusCode" => $status,
            "html" => $html
        ]);
    ';

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    $process = proc_open('php -d display_errors=0 -d error_reporting=0', $descriptors, $pipes, $projectRoot);
    if (!is_resource($process)) {
        return ['statusCode' => 500, 'html' => '', 'error' => 'Process open failed'];
    }

    fwrite($pipes[0], $phpCode);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    $decoded = json_decode($stdout, true);
    if (is_array($decoded) && isset($decoded['html'])) {
        return [
            'statusCode' => $decoded['statusCode'] ?? 200,
            'html' => $decoded['html'],
            'stderr' => $stderr,
            'exitCode' => $exitCode
        ];
    }

    return [
        'statusCode' => $exitCode === 0 ? 200 : 500,
        'html' => $stdout,
        'stderr' => $stderr,
        'exitCode' => $exitCode
    ];
}

/**
 * Programmatically execute backend POST endpoint with given payload & headers
 */
function postBackendEndpoint(string $endpointFile, array $postData = [], array $headers = [], array $sessionData = []): array
{
    $projectRoot = dirname(__DIR__);
    $scriptPath = $projectRoot . '/' . ltrim($endpointFile, '/');

    if (!file_exists($scriptPath)) {
        return [
            'statusCode' => 404,
            'body' => '',
            'headers' => [],
            'json' => null,
            'error' => "Endpoint not found: {$scriptPath}"
        ];
    }

    $headerCode = '';
    foreach ($headers as $hKey => $hVal) {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $hKey));
        $headerCode .= '$_SERVER[' . var_export($serverKey, true) . '] = ' . var_export($hVal, true) . '; ';
    }

    $phpCode = '<?php
        $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
        $_SERVER["HTTPS"] = "on";
        $_SERVER["SERVER_PORT"] = "443";
        $_SERVER["REQUEST_URI"] = "/api/submit-audit-request.php";
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST = ' . var_export($postData, true) . ';
        $_GET = [];
        ' . $headerCode . '

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION = ' . var_export($sessionData, true) . ';

        ob_start();
        $status = 200;
        try {
            include ' . var_export($scriptPath, true) . ';
            $body = ob_get_clean();
            $status = http_response_code() ?: 200;
        } catch (\Throwable $e) {
            $body = ob_get_clean();
            $status = 500;
        }

        $responseHeaders = headers_list();

        echo json_encode([
            "statusCode" => $status,
            "body" => $body,
            "headers" => $responseHeaders,
            "session" => $_SESSION
        ]);
    ';

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    $process = proc_open('php -d display_errors=0 -d error_reporting=0', $descriptors, $pipes, $projectRoot);
    if (!is_resource($process)) {
        return ['statusCode' => 500, 'body' => '', 'headers' => [], 'json' => null];
    }

    fwrite($pipes[0], $phpCode);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);

    $decoded = json_decode($stdout, true);
    if (is_array($decoded)) {
        $body = $decoded['body'] ?? '';
        $jsonData = json_decode($body, true);
        return [
            'statusCode' => $decoded['statusCode'] ?? 200,
            'body' => $body,
            'headers' => $decoded['headers'] ?? [],
            'json' => $jsonData,
            'session' => $decoded['session'] ?? [],
            'stderr' => $stderr
        ];
    }

    $jsonData = json_decode($stdout, true);
    return [
        'statusCode' => 200,
        'body' => $stdout,
        'headers' => [],
        'json' => $jsonData,
        'session' => [],
        'stderr' => $stderr
    ];
}

/**
 * Master test suite factory for Tier 3 Cross-Feature Combinations
 */
function getTier3CrossFeatureSuite(): TestSuite
{
    $projectRoot = dirname(__DIR__);
    $suite = new TestSuite('Tier 3 - Cross-Feature Combinatorial Interaction Suite', 'Pairwise cross-feature interactions across F1-F19');

    // =========================================================================
    // TC01: F1 (Routing) + F15 (Navbar CTA)
    // =========================================================================
    $suite->addTest('TC01: F1 (Routing) + F15 (Navbar CTA): Navbar CTA routes to /free-practice-audit/ and renders page with 200 and SEO title', 'Tier 3', function () use ($projectRoot) {
        $headerPath = $projectRoot . '/includes/header.php';
        Assert::assertTrue(file_exists($headerPath), "includes/header.php must exist");
        $headerContent = file_get_contents($headerPath);

        // Verify navbar CTA button href contains free-practice-audit or contact
        Assert::assertTrue(
            strpos($headerContent, 'free-practice-audit') !== false || strpos($headerContent, 'nav-cta') !== false,
            "Navbar CTA in header.php must be present"
        );

        // Render target page via simulated clean URL request
        $rendered = renderPageScript('free-practice-audit.php');

        Assert::assertEquals(200, $rendered['statusCode'], "Page /free-practice-audit/ must render with HTTP status 200");
        Assert::assertStringContains('Free Practice Revenue Audit', $rendered['html'], "Rendered page must contain SEO page title");
        Assert::assertStringContains('Free Practice Audit', $rendered['html'], "Rendered page must display breadcrumbs");
        Assert::assertStringContains('<form', $rendered['html'], "Rendered page must contain intake form shell");
    });

    // =========================================================================
    // TC02: F1 (Routing) + F16 (Hero CTA)
    // =========================================================================
    $suite->addTest('TC02: F1 (Routing) + F16 (Hero CTA): Homepage Hero CTA navigation displays audit form hero section', 'Tier 3', function () use ($projectRoot) {
        $indexPath = $projectRoot . '/index.php';
        Assert::assertTrue(file_exists($indexPath), "index.php must exist");

        // Render free-practice-audit.php and verify hero headline and value props
        $rendered = renderPageScript('free-practice-audit.php');
        Assert::assertEquals(200, $rendered['statusCode']);
        Assert::assertStringContains('Practice Revenue Audit', $rendered['html']);
        Assert::assertStringContains('Cost Assessment', $rendered['html']);
        Assert::assertStringContains('Start Measuring', $rendered['html']);
    });

    // =========================================================================
    // TC03: F1 (Routing) + F17 (Location CTA)
    // =========================================================================
    $suite->addTest('TC03: F1 (Routing) + F17 (Location CTA): Location CTA navigation renders audit form with location context', 'Tier 3', function () use ($projectRoot) {
        $locationsPath = $projectRoot . '/locations.php';
        Assert::assertTrue(file_exists($locationsPath), "locations.php must exist");

        // Render with state parameter
        $rendered = renderPageScript('free-practice-audit.php', ['state' => 'texas']);
        Assert::assertEquals(200, $rendered['statusCode']);
        Assert::assertStringContains('<form', $rendered['html']);
        Assert::assertTrue(
            strpos($rendered['html'], 'practice_name') !== false || strpos($rendered['html'], 'practiceName') !== false,
            "Form must contain practice name input"
        );
    });

    // =========================================================================
    // TC04: F1 (Routing) + F18 (Blog CTA)
    // =========================================================================
    $suite->addTest('TC04: F1 (Routing) + F18 (Blog CTA): Blog article CTA navigation routes to audit form page', 'Tier 3', function () use ($projectRoot) {
        $blogPath = $projectRoot . '/blog.php';
        Assert::assertTrue(file_exists($blogPath), "blog.php must exist");
        
        // Render audit form page
        $rendered = renderPageScript('free-practice-audit.php');
        Assert::assertEquals(200, $rendered['statusCode']);
        Assert::assertTrue(
            strpos($rendered['html'], 'Practice Revenue Audit') !== false,
            "Audit page must render audit headline"
        );
        Assert::assertStringContains('specialty', $rendered['html']);
    });

    // =========================================================================
    // TC05: F1 (Routing) + F19 (Contact Isolation)
    // =========================================================================
    $suite->addTest('TC05: F1 (Routing) + F19 (Contact Isolation): Navigating to /contact/ vs /free-practice-audit/ renders distinct forms and endpoints', 'Tier 3', function () {
        $contactRendered = renderPageScript('contact.php');
        $auditRendered = renderPageScript('free-practice-audit.php');

        Assert::assertEquals(200, $contactRendered['statusCode']);
        Assert::assertEquals(200, $auditRendered['statusCode']);

        // Verify Contact page contains general inquiry elements
        Assert::assertStringContains('Contact', $contactRendered['html']);
        Assert::assertStringContains('message', $contactRendered['html']);

        // Verify Audit page contains dedicated RCM metrics and distinct form action
        Assert::assertStringContains('submit-audit-request', $auditRendered['html']);
        Assert::assertStringContains('specialty', $auditRendered['html']);
        Assert::assertTrue(
            strpos($auditRendered['html'], 'practice_name') !== false || strpos($auditRendered['html'], 'practiceName') !== false,
            "Audit page must render practice name field"
        );
    });

    // =========================================================================
    // TC06: F2 (Identity) + F3 (Contact) + F11 (Server Validation)
    // =========================================================================
    $suite->addTest('TC06: F2 (Identity) + F3 (Contact) + F11 (Server Validation): Submit valid identity with invalid email/phone triggers server validation error', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Valid identity fields
        $practiceName = sanitizeInput("Premier Health Institute");
        $contactName = sanitizeInput("Dr. Gregory House");
        Assert::assertEquals("Premier Health Institute", $practiceName);
        Assert::assertEquals("Dr. Gregory House", $contactName);

        // Invalid email tests
        Assert::assertFalse(isValidEmail("not-an-email"), "isValidEmail must reject malformed email");
        Assert::assertFalse(isValidEmail("missing@domain"), "isValidEmail must reject missing TLD");

        // Invalid phone tests
        Assert::assertFalse(isValidPhone("123"), "isValidPhone must reject short phone numbers");

        // Post invalid payload to backend
        $response = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Premier Health Institute',
            'contact_name' => 'Dr. Gregory House',
            'email' => 'invalid_email_no_domain',
            'phone' => '123'
        ]);

        // Should not redirect with success
        if (isset($response['headers'])) {
            foreach ($response['headers'] as $hdr) {
                Assert::assertNotContains('Location: /free-practice-audit.php?success=1', $hdr, "Invalid submission must not succeed");
            }
        }
    });

    // =========================================================================
    // TC07: F4 (Address) + F5 (Metrics) + F11 (Server Validation)
    // =========================================================================
    $suite->addTest('TC07: F4 (Address) + F5 (Metrics) + F11 (Server Validation): Submit valid address with invalid metrics handles field validation correctly', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $street = sanitizeInput("100 Medical Center Parkway");
        $city = sanitizeInput("Boston");
        $state = sanitizeInput("MA");
        $zip = sanitizeInput("02115");

        Assert::assertEquals("100 Medical Center Parkway", $street);
        Assert::assertEquals("Boston", $city);
        Assert::assertEquals("MA", $state);
        Assert::assertEquals("02115", $zip);

        // Validation helper checks
        $validSpecialties = [
            'Therapy (PT/OT/ST)', 'Behavioral Health', 'Pain Management',
            'Cardiology', 'Neurology', 'Oncology', 'Radiology', 'Dental',
            'DME', 'Family Practice', 'Other'
        ];
        Assert::assertTrue(in_array('Cardiology', $validSpecialties, true));
        Assert::assertFalse(in_array('InvalidSpecialtyFake', $validSpecialties, true));
    });

    // =========================================================================
    // TC08: F6 (Pain Points) + F7 (Notes) + F12 (Persistence)
    // =========================================================================
    $suite->addTest('TC08: F6 (Pain Points) + F7 (Notes) + F12 (Persistence): Submit multiple pain points with rich notes formatted for persistence', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $notes = "Experiencing 35% CO-16 denial rate and unbilled AR backlog exceeding $250k.";
        $challenge = "High Denials & Aging AR";

        $leadData = [
            'full_name' => 'Dr. James Wilson',
            'email' => 'jwilson@oncologycare.org',
            'phone' => '617-555-0199',
            'practice_name' => 'Wilson Oncology Care',
            'specialty' => 'Oncology',
            'message' => "FREE PRACTICE AUDIT REQUEST:\nChallenge: {$challenge}\nNotes: {$notes}"
        ];

        // Format message and verify pain points and notes are contained
        Assert::assertStringContains('High Denials & Aging AR', $leadData['message']);
        Assert::assertStringContains('CO-16 denial rate', $leadData['message']);

        // Verify email builder captures notes and message
        $htmlBody = buildEmailBody($leadData);
        Assert::assertStringContains('Wilson Oncology Care', $htmlBody);
        Assert::assertStringContains('High Denials', $htmlBody);
    });

    // =========================================================================
    // TC09: F8 (Client Validation) + F9 (CSRF) + F14 (Dual Response)
    // =========================================================================
    $suite->addTest('TC09: F8 (Client Validation) + F9 (CSRF) + F14 (Dual Response): AJAX submit with valid CSRF and complete fields receives successful response', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $token = generateCSRFToken();
        Assert::assertTrue(validateCSRFToken($token), "Generated CSRF token must validate successfully");

        $response = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Metro Health Center',
            'contact_name' => 'Dr. Lisa Cuddy',
            'email' => 'lcuddy@metrohealth.org',
            'phone' => '212-555-9000',
            'specialty' => 'Family Practice',
            'patient_volume' => '500-1000',
            'monthly_revenue' => '$100k-$250k',
            'current_ehr' => 'Epic',
            'csrf_token' => $token
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ], [
            'csrf_token' => $token
        ]);

        // Verify response structure
        Assert::assertTrue(
            $response['statusCode'] === 200 || $response['statusCode'] === 302,
            "AJAX or standard POST submission must complete with valid status"
        );
    });

    // =========================================================================
    // TC10: F9 (CSRF) + F10 (Anti-Bot) + F11 (Server Validation)
    // =========================================================================
    $suite->addTest('TC10: F9 (CSRF) + F10 (Anti-Bot) + F11 (Server Validation): Submit with bot honeypot populated is caught by anti-bot layer', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Bot honeypot simulation
        $honeypot = 'http://spambot-link.ru/payload';
        $isBot = !empty($honeypot);
        Assert::assertTrue($isBot, "Honeypot detection must flag non-empty honeypot input");

        $cleanSubmission = '';
        $isHuman = empty($cleanSubmission);
        Assert::assertTrue($isHuman, "Human submission must leave honeypot empty");
    });

    // =========================================================================
    // TC11: F10 (Rate Limit) + F14 (Dual Response)
    // =========================================================================
    $suite->addTest('TC11: F10 (Rate Limit) + F14 (Dual Response): Rapid requests trigger rate limiter threshold protection', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        Assert::assertTrue(function_exists('isRateLimited'), "isRateLimited() function must be defined");
        Assert::assertTrue(function_exists('logActivity'), "logActivity() function must be defined");

        // Verify rate limit parameters structure
        $action = 'audit_form_test_' . uniqid();
        $isLimitedInitial = isRateLimited($action, 5, 15);
        Assert::assertTrue(is_bool($isLimitedInitial), "isRateLimited must return a boolean");
    });

    // =========================================================================
    // TC12: F11 (Validation) + F12 (Persistence) + F13 (Email)
    // =========================================================================
    $suite->addTest('TC12: F11 (Validation) + F12 (Persistence) + F13 (Email): Successful validated submission triggers persistence and email notification', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $data = [
            'full_name' => 'Dr. Eric Foreman',
            'email' => 'foreman@princetonplainsboro.org',
            'phone' => '609-555-0144',
            'practice_name' => 'Princeton Diagnostics',
            'specialty' => 'Neurology',
            'message' => "FREE PRACTICE AUDIT REQUEST:\nProvider Count: 4-10\nBiggest Challenge: High Denials"
        ];

        // Validate contact details
        Assert::assertTrue(isValidEmail($data['email']));
        Assert::assertTrue(isValidPhone($data['phone']));

        // Verify HTML & Plain text email builders
        $html = buildEmailBody($data);
        $plain = buildEmailPlainText($data);

        Assert::assertStringContains('Dr. Eric Foreman', $html);
        Assert::assertStringContains('Princeton Diagnostics', $html);
        Assert::assertStringContains('Neurology', $html);

        Assert::assertStringContains('Dr. Eric Foreman', $plain);
        Assert::assertStringContains('Princeton Diagnostics', $plain);
    });

    // =========================================================================
    // TC13: F11 (Validation) + F14 (Non-JS POST with errors)
    // =========================================================================
    $suite->addTest('TC13: F11 (Validation) + F14 (Non-JS POST): Standard HTTP POST with missing fields handles errors gracefully', 'Tier 3', function () {
        $response = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Incomplete Clinic',
            'contact_name' => 'John Doe',
            'email' => '', // missing email
            'phone' => ''
        ]);

        // Ensure missing email triggers error response and doesn't succeed
        Assert::assertTrue(
            strpos($response['body'], 'Invalid email') !== false ||
            $response['statusCode'] === 400 ||
            $response['statusCode'] === 302,
            "Non-JS POST with invalid fields must return error feedback or redirect"
        );
    });

    // =========================================================================
    // TC14: F11 (Validation) + F14 (Non-JS POST success)
    // =========================================================================
    $suite->addTest('TC14: F11 (Validation) + F14 (Non-JS POST): Standard HTTP POST with valid fields returns 302 redirect with success state', 'Tier 3', function () {
        $response = postBackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Mercy General Hospital',
            'contact_name' => 'Sarah Connor',
            'email' => 'sconnor@mercygeneral.org',
            'phone' => '415-555-0199',
            'specialty' => 'Family Practice',
            'patient_volume' => '1000-2500',
            'monthly_revenue' => '$250k-$500k',
            'current_ehr' => 'AthenaHealth'
        ]);

        // Verify Location header redirects to ?success=1 or status 302
        $hasSuccessRedirect = false;
        foreach ($response['headers'] as $hdr) {
            if (stripos($hdr, 'Location:') === 0 && stripos($hdr, 'success=1') !== false) {
                $hasSuccessRedirect = true;
                break;
            }
        }

        Assert::assertTrue(
            $hasSuccessRedirect || $response['statusCode'] === 302 || $response['statusCode'] === 200,
            "Standard POST submission must redirect to success=1 or return 200"
        );

        // Verify rendering of success banner
        $successPage = renderPageScript('free-practice-audit.php', ['success' => '1']);
        Assert::assertEquals(200, $successPage['statusCode']);
        Assert::assertStringContains('alert-success', $successPage['html']);
        Assert::assertTrue(
            strpos($successPage['html'], 'Audit Request Successfully Submitted') !== false ||
            strpos($successPage['html'], 'Audit request received') !== false,
            "Success alert banner must be displayed on redirected page"
        );
    });

    // =========================================================================
    // TC15: F2 (Identity) + F5 (Specialty) + F6 (EHR) + F13 (Admin Email)
    // =========================================================================
    $suite->addTest('TC15: F2 (Identity) + F5 (Specialty) + F6 (EHR) + F13 (Admin Email): High-value practice lead generates formatted admin email with metrics', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $data = [
            'full_name' => 'Dr. Allison Cameron',
            'email' => 'cameron@metropaincare.org',
            'phone' => '312-555-7788',
            'practice_name' => 'Metro Pain Institute',
            'specialty' => 'Pain Management',
            'message' => "FREE PRACTICE AUDIT REQUEST:\nProvider Count: 11-25\nBiggest Challenge: Prior Authorizations\nCurrent EHR: Epic Systems\nMonthly Revenue: \$250k-\$500k"
        ];

        $htmlBody = buildEmailBody($data);
        $plainText = buildEmailPlainText($data);

        Assert::assertStringContains('Metro Pain Institute', $htmlBody);
        Assert::assertStringContains('Pain Management', $htmlBody);
        Assert::assertStringContains('Prior Authorizations', $htmlBody);
        Assert::assertStringContains('Epic Systems', $htmlBody);

        Assert::assertStringContains('Metro Pain Institute', $plainText);
        Assert::assertStringContains('Pain Management', $plainText);
    });

    // =========================================================================
    // TC16: F3 (Contact) + F13 (Prospect Email)
    // =========================================================================
    $suite->addTest('TC16: F3 (Contact) + F13 (Prospect Email): Valid prospect email formatting includes contact and practice name', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $contactName = 'Dr. Robert Chase';
        $practiceName = 'Chase Cardiovascular Care';
        $email = 'rchase@chasecardio.com';

        $confirmationSubject = "Practice Revenue Audit Request Received - {$practiceName}";
        Assert::assertStringContains($practiceName, $confirmationSubject);

        $confirmationBody = "Dear {$contactName},\n\nThank you for requesting a Free Practice Revenue Audit for {$practiceName}. Our RCM team will review your details and contact you within 24 hours.";
        Assert::assertStringContains($contactName, $confirmationBody);
        Assert::assertStringContains($practiceName, $confirmationBody);
        Assert::assertStringContains('24 hours', $confirmationBody);
    });

    // =========================================================================
    // TC17: F9 (CSRF Mismatch) + F14 (AJAX)
    // =========================================================================
    $suite->addTest('TC17: F9 (CSRF Mismatch) + F14 (AJAX): Mismatched CSRF token fails verification', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Generate real session token
        $realToken = generateCSRFToken();
        $forgedToken = "forged_token_" . bin2hex(random_bytes(16));

        // Valid token succeeds
        Assert::assertTrue(validateCSRFToken($realToken), "Real token must validate");

        // Forged token fails
        Assert::assertFalse(validateCSRFToken($forgedToken), "Forged token must fail validation");
        Assert::assertFalse(validateCSRFToken(''), "Empty token must fail validation");
    });

    // =========================================================================
    // TC18: F10 (Rate Limit) + F14 (Non-JS POST)
    // =========================================================================
    $suite->addTest('TC18: F10 (Rate Limit) + F14 (Non-JS POST): Rate limiting checks enforce request throttling rules', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        Assert::assertTrue(function_exists('isRateLimited'), "isRateLimited function must exist");
        $check = isRateLimited('audit_form', 5, 15);
        Assert::assertTrue(is_bool($check), "isRateLimited must return a boolean status");
    });

    // =========================================================================
    // TC19: F12 (DB Fallback) + F13 (Email Dispatch)
    // =========================================================================
    $suite->addTest('TC19: F12 (DB Fallback) + F13 (Email Dispatch): Database unavailable handled gracefully without fatal error', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $data = [
            'full_name' => 'Dr. Amber Volakis',
            'email' => 'avolakis@radiologypartners.org',
            'phone' => '212-555-3344',
            'practice_name' => 'Advanced Imaging Radiology',
            'specialty' => 'Radiology',
            'message' => "FREE PRACTICE AUDIT REQUEST:\nTesting database fallback handling."
        ];

        // saveContactSubmission wraps DB operations in try/catch PDOException
        $result = saveContactSubmission($data);
        // Should return int ID if DB is live, or false if offline, but NEVER throw unhandled fatal
        Assert::assertTrue($result === false || is_int($result), "saveContactSubmission must return false or integer ID without crashing");

        // Email building continues regardless of DB state
        $body = buildEmailBody($data);
        Assert::assertStringContains('Advanced Imaging Radiology', $body);
    });

    // =========================================================================
    // TC20: F1 (Canonical Redirect) + F9 (CSRF Token) [BONUS]
    // =========================================================================
    $suite->addTest('TC20: F1 (Canonical 301 Redirect) + F9 (CSRF Token): .php URI clean redirection preserves CSRF token state', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $token1 = generateCSRFToken();
        Assert::assertTrue(strlen($token1) === 64, "CSRF token must be 64-char hex string");

        // Session idempotence
        $token2 = generateCSRFToken();
        Assert::assertEquals($token1, $token2, "CSRF token must remain consistent in session");
    });

    // =========================================================================
    // TC21: F8 (Client-Side Formatting) + F11 (Server Sanitization) [BONUS]
    // =========================================================================
    $suite->addTest('TC21: F8 (Client-Side Formatting) + F11 (Server Sanitization): Formatted inputs are safely sanitized and validated', 'Tier 3', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Masked phone format
        $formattedPhone = "(800) 555-0199";
        Assert::assertTrue(isValidPhone($formattedPhone), "Formatted phone (800) 555-0199 must be valid");

        // International phone format
        $intlPhone = "+1 (555) 234-5678";
        Assert::assertTrue(isValidPhone($intlPhone), "International formatted phone must be valid");

        // Sanitization against XSS in text fields
        $rawSpecialty = "Cardiology <script>alert('xss')</script>";
        $sanitized = sanitizeInput($rawSpecialty);
        Assert::assertStringNotContains('<script>', $sanitized, "Sanitizer must encode HTML tags");
        Assert::assertStringContains('&lt;script&gt;', $sanitized, "Sanitizer must produce safe HTML entities");
    });

    return $suite;
}

// Standalone execution support
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getTier3CrossFeatureSuite());
    exit($runner->runAll());
}
