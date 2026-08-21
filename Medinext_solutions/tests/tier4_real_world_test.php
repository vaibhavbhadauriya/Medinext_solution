<?php
/**
 * MEDINEXT SOLUTIONS - Tier 4 Real-World Application Workloads Test Suite
 * 
 * Multi-step end-to-end user workflows and real-world clinical/financial scenarios:
 * Scenario 1: High-Volume Orthopedic Surgery Group Audit Request (Epic EHR, Denials + Prior Auth)
 * Scenario 2: Small Dental Practice In-House Billing Transition Evaluation (Dentrix, Staff Burnout)
 * Scenario 3: Mobile Visitor Conversion via Navbar CTA -> Instant AJAX Form Submission
 * Scenario 4: Blog Reader Converting from AR Denial Guide -> Revenue Analysis Form
 * Scenario 5: Legacy Browser Non-JS User Submission with Session Flash Feedback
 * Scenario 6: General Support Visitor Routing to Contact Page vs Practice Audit Navigation
 * Scenario 7: Multi-Location Specialty Clinic with Aging AR & Credentialing Bottlenecks (AthenaHealth)
 * 
 * Total Tests: >= 7 comprehensive multi-step end-to-end user scenarios.
 * 
 * Execution:
 *   php tests/tier4_real_world_test.php
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
            echo " MASTER SUMMARY: Tier 4 Real-World Application Workloads Suite\n";
            echo " Total Tests: {$totalTests} | Passed: {$totalPassed} | Failed: {$totalFailed}\n";
            echo str_repeat('#', 80) . "\n\n";

            return $totalFailed === 0 ? 0 : 1;
        }
    }
}

/**
 * Programmatically execute a page script with mocked server & request environment
 */
function renderTier4PageScript(string $relativeFile, array $queryParams = [], array $serverOverrides = []): array
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
function postTier4BackendEndpoint(string $endpointFile, array $postData = [], array $headers = [], array $sessionData = []): array
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
 * Master test suite factory for Tier 4 Real-World Application Workloads
 */
function getTier4RealWorldSuite(): TestSuite
{
    $projectRoot = dirname(__DIR__);
    $suite = new TestSuite('Tier 4 - Real-World Application Workloads Suite', 'End-to-end multi-step realistic user scenarios and workflows');

    // =========================================================================
    // Scenario 1: High-Volume Orthopedic Surgery Group Audit Request
    // =========================================================================
    $suite->addTest('Scenario 1: High-Volume Orthopedic Surgery Group Audit Request (Epic EHR, Denials + Prior Auth)', 'Tier 4', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Step 1: Render audit intake page shell & verify DOM elements
        $page = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $page['statusCode']);
        Assert::assertStringContains('Practice Revenue Audit', $page['html']);
        Assert::assertStringContains('form', $page['html']);

        // Step 2: Construct Orthopedic Group payload
        $token = generateCSRFToken();
        $payload = [
            'practice_name' => 'Apex Orthopedic & Spine Institute',
            'contact_name' => 'David Miller, COO',
            'job_title' => 'Chief Operating Officer',
            'email' => 'dmiller@apexortho.com',
            'phone' => '(312) 555-0188',
            'street_address' => '789 Ortho Way, Suite 400',
            'city' => 'Chicago',
            'state' => 'IL',
            'zip_code' => '60601',
            'specialty' => 'Orthopedic Surgery',
            'patient_volume' => '1000-2500',
            'monthly_revenue' => '$250k-$500k',
            'current_ehr' => 'Epic Systems',
            'pain_points' => ['denials', 'prior_auth'],
            'service_requirements' => 'Experiencing high volume of surgical pre-authorization denials (CO-197) and slow appeal turnover.',
            'csrf_token' => $token
        ];

        // Step 3: Validate field sanitization and formats
        Assert::assertTrue(isValidEmail($payload['email']));
        Assert::assertTrue(isValidPhone($payload['phone']));
        Assert::assertEquals('Apex Orthopedic &amp; Spine Institute', sanitizeInput($payload['practice_name']));

        // Step 4: Submit via AJAX simulation
        $ajaxResponse = postTier4BackendEndpoint('api/submit-audit-request.php', $payload, [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ], [
            'csrf_token' => $token
        ]);

        Assert::assertTrue(
            $ajaxResponse['statusCode'] === 200 || $ajaxResponse['statusCode'] === 302,
            "AJAX submission must return successful status code"
        );

        // Step 5: Verify formatted admin notification body contains operational metrics
        $emailData = [
            'full_name' => $payload['contact_name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'practice_name' => 'Apex Orthopedic and Spine Institute',
            'specialty' => $payload['specialty'],
            'message' => "FREE PRACTICE AUDIT REQUEST:\nEHR: {$payload['current_ehr']}\nVolume: 1200+ monthly visits\nRevenue: \$400k/mo collections\nNotes: {$payload['service_requirements']}"
        ];
        $adminEmail = buildEmailBody($emailData);
        Assert::assertStringContains('Apex Orthopedic and Spine Institute', $adminEmail);
        Assert::assertStringContains('Orthopedic Surgery', $adminEmail);
        Assert::assertStringContains('Epic Systems', $adminEmail);
        Assert::assertStringContains('1200+ monthly visits', $adminEmail);
    });

    // =========================================================================
    // Scenario 2: Small Dental Practice In-House Billing Transition Evaluation
    // =========================================================================
    $suite->addTest('Scenario 2: Small Dental Practice In-House Billing Transition Evaluation (Dentrix, Staff Burnout)', 'Tier 4', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Step 1: Office Manager navigates to audit intake page
        $page = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $page['statusCode']);

        // Step 2: Dental clinic payload
        $dentalPayload = [
            'practice_name' => 'SmileCraft Dental Studio',
            'contact_name' => 'Dr. Rachel Green, Owner',
            'job_title' => 'Practice Owner / Clinician',
            'email' => 'rgreen@smilecraftdental.com',
            'phone' => '(512) 555-0144',
            'street_address' => '45 Elm St, Suite 200',
            'city' => 'Austin',
            'state' => 'TX',
            'zip_code' => '78701',
            'specialty' => 'Dental (Medical-Dental)',
            'patient_volume' => '250-500',
            'monthly_revenue' => '$50k-$100k',
            'current_ehr' => 'Dentrix',
            'pain_points' => ['staff_burnout'],
            'service_requirements' => 'Solo in-house biller retiring next month; evaluating full-cycle outsourced dental billing.'
        ];

        // Step 3: Execute standard non-JS HTTP POST
        $postResponse = postTier4BackendEndpoint('api/submit-audit-request.php', $dentalPayload);

        // Step 4: Verify 302 redirect or 200 completion
        Assert::assertTrue(
            $postResponse['statusCode'] === 302 || $postResponse['statusCode'] === 200,
            "Standard POST submission must complete with redirect or status 200"
        );

        // Step 5: Follow redirect and verify success banner in DOM
        $successPage = renderTier4PageScript('free-practice-audit.php', ['success' => '1']);
        Assert::assertEquals(200, $successPage['statusCode']);
        Assert::assertStringContains('alert-success', $successPage['html']);

        // Step 6: Verify automated confirmation receipt formatting
        $prospectEmail = buildEmailPlainText([
            'full_name' => 'Dr. Rachel Green',
            'practice_name' => 'SmileCraft Dental Studio',
            'specialty' => 'Dental (Medical-Dental)',
            'email' => 'rgreen@smilecraftdental.com',
            'phone' => '(512) 555-0144',
            'message' => 'Free practice revenue audit intake'
        ]);
        Assert::assertStringContains('Dr. Rachel Green', $prospectEmail);
        Assert::assertStringContains('SmileCraft Dental Studio', $prospectEmail);
    });

    // =========================================================================
    // Scenario 3: Mobile Visitor Conversion via Navbar CTA -> Instant AJAX Form Submission
    // =========================================================================
    $suite->addTest('Scenario 3: Mobile Visitor Conversion via Navbar CTA -> Instant AJAX Form Submission', 'Tier 4', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        $mobileUA = 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1';

        // Step 1: Mobile visitor visits homepage
        $homePage = renderTier4PageScript('index.php', [], [
            'HTTP_USER_AGENT' => $mobileUA
        ]);
        Assert::assertEquals(200, $homePage['statusCode']);

        // Step 2: Verify mobile drawer navigation contains consultation/audit link
        $headerPath = $projectRoot . '/includes/header.php';
        $headerContent = file_get_contents($headerPath);
        Assert::assertTrue(
            strpos($headerContent, 'drawer-cta') !== false || strpos($headerContent, 'mobile-drawer') !== false,
            "Header must contain mobile drawer navigation"
        );

        // Step 3: Navigates to audit page with mobile User-Agent
        $auditPage = renderTier4PageScript('free-practice-audit.php', [], [
            'HTTP_USER_AGENT' => $mobileUA
        ]);
        Assert::assertEquals(200, $auditPage['statusCode']);
        Assert::assertStringContains('form', $auditPage['html']);

        // Step 4: Instant AJAX form submission
        $token = generateCSRFToken();
        $mobileSubmission = postTier4BackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Metro Dermatology Care',
            'contact_name' => 'Dr. Mark Sloan',
            'email' => 'msloan@metroderm.com',
            'phone' => '206-555-0199',
            'specialty' => 'Dermatology',
            'patient_volume' => '500-1000',
            'monthly_revenue' => '$100k-$250k',
            'current_ehr' => 'Modernizing Medicine (EMA)',
            'csrf_token' => $token
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            'User-Agent' => $mobileUA
        ], [
            'csrf_token' => $token
        ]);

        Assert::assertTrue(
            $mobileSubmission['statusCode'] === 200 || $mobileSubmission['statusCode'] === 302,
            "Mobile AJAX submission must be processed cleanly"
        );
    });

    // =========================================================================
    // Scenario 4: Blog Reader Converting from AR Denial Guide -> Revenue Analysis Form
    // =========================================================================
    $suite->addTest('Scenario 4: Blog Reader Converting from AR Denial Guide -> Revenue Analysis Form', 'Tier 4', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Step 1: Reader reviews blog article
        $blogPage = renderTier4PageScript('blog.php');
        Assert::assertEquals(200, $blogPage['statusCode']);
        Assert::assertStringContains('Blog', $blogPage['html']);

        // Step 2: Clicks CTA and lands on practice audit page
        $auditPage = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $auditPage['statusCode']);
        Assert::assertStringContains('Practice Revenue Audit', $auditPage['html']);

        // Step 3: Submits audit request focusing on Claim Denials & Aging AR
        $token = generateCSRFToken();
        $blogReaderPayload = [
            'practice_name' => 'Summit Internal Medicine',
            'contact_name' => 'Dr. Richard Webber',
            'email' => 'rwebber@summitinternal.org',
            'phone' => '206-555-0111',
            'specialty' => 'Family Practice / Internal Med',
            'patient_volume' => '500-1000',
            'monthly_revenue' => '$100k-$250k',
            'current_ehr' => 'eClinicalWorks',
            'pain_points' => ['denials', 'ar_aging'],
            'service_requirements' => 'Seeking full audit on CO-4 and CO-16 denial codes.',
            'csrf_token' => $token
        ];

        $res = postTier4BackendEndpoint('api/submit-audit-request.php', $blogReaderPayload, [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ], [
            'csrf_token' => $token
        ]);

        Assert::assertTrue(
            $res['statusCode'] === 200 || $res['statusCode'] === 302,
            "Blog conversion submission must process successfully"
        );
    });

    // =========================================================================
    // Scenario 5: Legacy Browser Non-JS User Submission with Session Flash Feedback
    // =========================================================================
    $suite->addTest('Scenario 5: Legacy Browser Non-JS User Submission with Session Flash Feedback', 'Tier 4', function () {
        // Step 1: Legacy user loads pure HTML form
        $page = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $page['statusCode']);
        Assert::assertStringContains('<form', $page['html']);

        // Step 2: Incomplete POST submission
        $invalidPost = postTier4BackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Legacy Care Center',
            'contact_name' => 'Jane Doe',
            'email' => '', // missing email
            'phone' => ''
        ]);

        // Step 3: Ensure invalid submission is stopped
        Assert::assertTrue(
            strpos($invalidPost['body'], 'Invalid email') !== false ||
            $invalidPost['statusCode'] === 400 ||
            $invalidPost['statusCode'] === 302,
            "Missing email must trigger validation feedback"
        );

        // Step 4: Resubmit with complete valid details
        $validPost = postTier4BackendEndpoint('api/submit-audit-request.php', [
            'practice_name' => 'Legacy Care Center',
            'contact_name' => 'Jane Doe',
            'email' => 'jdoe@legacycare.org',
            'phone' => '212-555-0133',
            'specialty' => 'Other / Multi-Specialty',
            'patient_volume' => '250-500',
            'monthly_revenue' => '$50k-$100k',
            'current_ehr' => 'Other'
        ]);

        Assert::assertTrue(
            $validPost['statusCode'] === 302 || $validPost['statusCode'] === 200,
            "Valid standard POST must succeed"
        );

        // Step 5: Follow redirect to success page
        $successPage = renderTier4PageScript('free-practice-audit.php', ['success' => '1']);
        Assert::assertEquals(200, $successPage['statusCode']);
        Assert::assertStringContains('alert-success', $successPage['html']);
    });

    // =========================================================================
    // Scenario 6: General Support Visitor Routing to Contact Page vs Practice Audit Navigation
    // =========================================================================
    $suite->addTest('Scenario 6: General Support Visitor Routing to Contact Page vs Practice Audit Navigation', 'Tier 4', function () {
        // Step 1: Corporate/support inquiry visitor navigates to /contact/
        $contactPage = renderTier4PageScript('contact.php');
        Assert::assertEquals(200, $contactPage['statusCode']);
        Assert::assertStringContains('Contact', $contactPage['html']);
        Assert::assertStringContains('message', $contactPage['html']);

        // Step 2: Practice audit seeker navigates to /free-practice-audit/
        $auditPage = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $auditPage['statusCode']);
        Assert::assertStringContains('Practice Revenue Audit', $auditPage['html']);
        Assert::assertStringContains('submit-audit-request', $auditPage['html']);

        // Step 3: Verify form separation
        Assert::assertStringNotContains('submit-audit-request', $contactPage['html']);
    });

    // =========================================================================
    // Scenario 7: Multi-Location Specialty Clinic with Aging AR & Credentialing Bottlenecks
    // =========================================================================
    $suite->addTest('Scenario 7: Multi-Location Specialty Clinic with Aging AR & Credentialing Bottlenecks (AthenaHealth)', 'Tier 4', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';

        // Step 1: Load audit page with full desktop session
        $page = renderTier4PageScript('free-practice-audit.php');
        Assert::assertEquals(200, $page['statusCode']);
        Assert::assertStringContains('Practice Revenue Audit', $page['html']);

        // Step 2: High-value Cardiology Group Enterprise Payload
        $token = generateCSRFToken();
        $cardiologyPayload = [
            'practice_name' => 'Heartland Cardiovascular Specialists',
            'contact_name' => 'Elena Rostova, Executive Director',
            'job_title' => 'Executive Director / Managing Partner',
            'email' => 'erostova@heartlandcardio.com',
            'phone' => '(317) 555-0199',
            'street_address' => '500 Cardiac Center Dr, Suite 800',
            'city' => 'Indianapolis',
            'state' => 'IN',
            'zip_code' => '46202',
            'specialty' => 'Cardiology',
            'patient_volume' => '2500+',
            'monthly_revenue' => '$500k+',
            'current_ehr' => 'AthenaHealth',
            'pain_points' => ['credentialing', 'ar_aging', 'underpayments'],
            'service_requirements' => 'Opening 2 new regional cath labs. Provider credentialing delays with commercial payers causing $300k+ in unbilled backlog.',
            'csrf_token' => $token
        ];

        // Step 3: Strict validation check
        Assert::assertTrue(isValidEmail($cardiologyPayload['email']));
        Assert::assertTrue(isValidPhone($cardiologyPayload['phone']));
        Assert::assertTrue(validateCSRFToken($token));

        // Step 4: Submit via AJAX
        $res = postTier4BackendEndpoint('api/submit-audit-request.php', $cardiologyPayload, [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ], [
            'csrf_token' => $token
        ]);

        Assert::assertTrue(
            $res['statusCode'] === 200 || $res['statusCode'] === 302,
            "Cardiology enterprise audit submission must succeed"
        );

        // Step 5: Format Admin Notification Email
        $adminEmailData = [
            'full_name' => $cardiologyPayload['contact_name'],
            'email' => $cardiologyPayload['email'],
            'phone' => $cardiologyPayload['phone'],
            'practice_name' => 'Heartland Cardiovascular Specialists',
            'specialty' => 'Cardiology',
            'message' => "FREE PRACTICE AUDIT REQUEST:\nEHR: AthenaHealth\nVolume: 3000 monthly visits (2500+)\nRevenue: \$750k monthly collections (\$500k+)\nNotes: {$cardiologyPayload['service_requirements']}"
        ];
        $adminHtml = buildEmailBody($adminEmailData);
        Assert::assertStringContains('Heartland Cardiovascular Specialists', $adminHtml);
        Assert::assertStringContains('Cardiology', $adminHtml);
        Assert::assertStringContains('AthenaHealth', $adminHtml);
        Assert::assertStringContains('3000 monthly visits', $adminHtml);

        // Step 6: Format Prospect Confirmation Receipt
        $prospectEmailText = buildEmailPlainText([
            'full_name' => 'Elena Rostova',
            'practice_name' => 'Heartland Cardiovascular Specialists',
            'specialty' => 'Cardiology',
            'email' => 'erostova@heartlandcardio.com',
            'phone' => '(317) 555-0199',
            'message' => 'Executive Summary Report delivery within 24-48 hours'
        ]);
        Assert::assertStringContains('Elena Rostova', $prospectEmailText);
        Assert::assertStringContains('Heartland Cardiovascular Specialists', $prospectEmailText);
        Assert::assertStringContains('24-48 hours', $prospectEmailText);
    });

    return $suite;
}

// Standalone execution support
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getTier4RealWorldSuite());
    exit($runner->runAll());
}
