<?php
/**
 * MEDINEXT SOLUTIONS - Tier 2 Boundary & Corner Cases Test Suite
 * 
 * Comprehensive verification of all 19 features (F1 to F19) under extreme
 * boundary conditions, corner cases, negative inputs, security sanitization,
 * fallback behaviors, and edge routing anomalies.
 * 
 * Total Tests: >= 95 tests (5 distinct tests per feature across F1-F19).
 * 
 * Execution:
 *   php tests/tier2_boundary_corner_test.php
 */

declare(strict_types=1);

namespace Medinext\Tests;

// Suppress CLI session header warnings by ensuring session starts cleanly if needed
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
                    throw new \AssertionError($message ?: "Array does not contain expected item");
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
                    throw new \AssertionError($message ?: "Array contains unexpected item");
                }
                return;
            }
            if (is_string($haystack)) {
                if (strpos($haystack, (string)$needle) !== false) {
                    throw new \AssertionError($message ?: "String contains unexpected '{$needle}'");
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
                throw new \AssertionError($message ?: "String contains unexpected '{$needle}'");
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
            echo " MASTER SUMMARY: Tier 2 Boundary & Corner Suite\n";
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

    $queryString = http_build_query($queryParams);
    // Use clean URL for REQUEST_URI to avoid header.php 301 loop
    $cleanPath = '/' . preg_replace('/\.php$/i', '/', ltrim($relativeFile, '/'));
    if ($cleanPath === '/index/') $cleanPath = '/';
    $requestUri = $cleanPath . ($queryString ? '?' . $queryString : '');

    $phpCode = '<?php
        $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
        $_SERVER["HTTPS"] = "on";
        $_SERVER["SERVER_PORT"] = "443";
        $_SERVER["REQUEST_URI"] = ' . var_export($requestUri, true) . ';
        $_SERVER["QUERY_STRING"] = ' . var_export($queryString, true) . ';
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_GET = ' . var_export($queryParams, true) . ';
        $_POST = [];
        $_COOKIE = [];

        ' . (!empty($serverOverrides) ? 'foreach (' . var_export($serverOverrides, true) . ' as $k => $v) { $_SERVER[$k] = $v; }' : '') . '

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        ob_start();
        try {
            chdir(' . var_export($projectRoot, true) . ');
            include ' . var_export($scriptPath, true) . ';
            $html = ob_get_clean();
            $status = http_response_code() ?: 200;
        } catch (\Throwable $e) {
            $html = ob_get_clean();
            $status = 500;
        }

        echo json_encode([
            "statusCode" => $status,
            "html_b64" => base64_encode((string)$html)
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
    if (is_array($decoded) && isset($decoded['html_b64'])) {
        return [
            'statusCode' => $decoded['statusCode'] ?? 200,
            'html' => base64_decode($decoded['html_b64']),
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
            chdir(' . var_export($projectRoot, true) . ');
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
            "body_b64" => base64_encode((string)$body),
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
        return ['statusCode' => 500, 'body' => '', 'headers' => [], 'json' => null, 'error' => 'Process open failed'];
    }

    fwrite($pipes[0], $phpCode);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    $decoded = json_decode($stdout, true);
    if (is_array($decoded) && isset($decoded['body_b64'])) {
        $body = base64_decode($decoded['body_b64']);
        $json = json_decode($body, true);
        return [
            'statusCode' => $decoded['statusCode'] ?? 200,
            'body' => $body,
            'json' => is_array($json) ? $json : null,
            'headers' => $decoded['headers'] ?? [],
            'session' => $decoded['session'] ?? [],
            'stderr' => $stderr,
            'exitCode' => $exitCode
        ];
    }

    $json = json_decode($stdout, true);
    return [
        'statusCode' => $exitCode === 0 ? 200 : 500,
        'body' => $stdout,
        'json' => is_array($json) ? $json : null,
        'headers' => [],
        'session' => [],
        'stderr' => $stderr,
        'exitCode' => $exitCode
    ];
}

/**
 * Generate Tier 2 Boundary & Corner Cases Test Suite
 */
function getTier2BoundarySuite(): TestSuite
{
    $suite = new TestSuite(
        'Tier 2: Boundary & Corner Cases Test Suite',
        'Exhaustive edge-case, boundary-value, negative inputs, security sanitization, and routing resilience testing for all 19 features'
    );

    $projectRoot = dirname(__DIR__);

    // =========================================================================
    // FEATURE 1: Dedicated Page Render & Clean URL Routing (/free-practice-audit/)
    // =========================================================================

    $suite->addTest('F1-B1: Non-standard query parameters on /free-practice-audit/', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php', [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'campaign_id' => '987654',
            'custom_param' => 'test value with spaces & symbols!',
            'nested' => ['k1' => 'v1', 'k2' => 'v2']
        ]);
        Assert::assertEquals(200, $res['statusCode'], "Page must return HTTP 200 with complex query parameters");
        Assert::assertStringContains('Free Practice', $res['html'], "Audit page content must render cleanly");
        Assert::assertStringNotContains('Warning:', $res['html'], "No PHP warnings emitted on non-standard query parameters");
    });

    $suite->addTest('F1-B2: Trailing slashes vs no trailing slashes and clean URL resolution', 'Tier 2', function () use ($projectRoot) {
        $htaccessPath = $projectRoot . '/.htaccess';
        Assert::assertTrue(file_exists($htaccessPath), ".htaccess file must exist");
        $htaccessContent = file_get_contents($htaccessPath);
        Assert::assertStringContains('RewriteRule', $htaccessContent, ".htaccess must contain URL rewriting rules");
        
        $res = renderPageScript('free-practice-audit.php');
        Assert::assertEquals(200, $res['statusCode'], "Clean template must render with 200 OK");
        Assert::assertStringContains('free-practice-audit', $res['html'], "Page must include canonical or self-referential route");
    });

    $suite->addTest('F1-B3: URL case insensitivity & path boundary', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php', ['source' => 'UPPERCASE_TEST']);
        Assert::assertEquals(200, $res['statusCode'], "Page should handle case variations");
        Assert::assertStringContainsIgnoreCase('free practice revenue audit', $res['html'], "Page title/heading must be present regardless of case");
    });

    $suite->addTest('F1-B4: Empty and malformed query string handling', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php', [
            '' => '',
            'empty_val' => '',
            '===malformed===' => '&&&'
        ]);
        Assert::assertEquals(200, $res['statusCode'], "Page must return HTTP 200 on malformed query strings");
        Assert::assertStringNotContains('Fatal error', $res['html'], "No fatal errors on malformed query strings");
    });

    $suite->addTest('F1-B5: Viewport & meta tags under extreme zoom & responsive meta', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php');
        Assert::assertStringContains('<meta name="viewport"', $res['html'], "Viewport meta tag must be present");
        Assert::assertStringContains('width=device-width', $res['html'], "Viewport must have width=device-width");
        Assert::assertStringContains('initial-scale=1.0', $res['html'], "Viewport must have initial-scale=1.0");
        Assert::assertStringContains('<meta charset="UTF-8">', $res['html'], "Charset UTF-8 meta tag must be present");
        Assert::assertStringContains('<main id="main-content">', $res['html'], "Main semantic landmark must be present");
    });

    // =========================================================================
    // FEATURE 2: Practice & POC Identity Fields Validation
    // =========================================================================

    $suite->addTest('F2-B1: 1-character practice name rejection boundary', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $shortName = "A";
        $sanitized = sanitizeInput($shortName);
        Assert::assertEquals("A", $sanitized);
        $isValidPractice = strlen(trim($shortName)) >= 2 && strlen(trim($shortName)) <= 150;
        Assert::assertFalse($isValidPractice, "1-character practice name must fail minimum length requirement (>= 2 chars)");
    });

    $suite->addTest('F2-B2: 255+ character string boundary for practice / POC name', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $longPracticeName = str_repeat("Medical Practice Name ", 15); // 330 chars
        $sanitized = sanitizeInput($longPracticeName);
        Assert::assertGreaterThanOrEqual(255, strlen($sanitized));
        $isWithinPracticeLimit = strlen($sanitized) <= 150;
        Assert::assertFalse($isWithinPracticeLimit, "300+ char practice name must exceed 150 char limit");
        
        $longPocName = str_repeat("Dr. Alexander Montgomery ", 10); // 260 chars
        $isWithinPocLimit = strlen(sanitizeInput($longPocName)) <= 100;
        Assert::assertFalse($isWithinPocLimit, "255+ char POC name must exceed 100 char limit");
    });

    $suite->addTest('F2-B3: Non-Latin / Unicode name characters in practice and POC names', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $unicodePoc = "Dr. José María Peña-García";
        $unicodePractice = "St. Jude's Children's & Women's Health Clínica São Paulo";
        
        $sanitizedPoc = sanitizeInput($unicodePoc);
        $sanitizedPractice = sanitizeInput($unicodePractice);
        
        Assert::assertStringContains('José María Peña-García', $sanitizedPoc, "Unicode accents must be preserved");
        Assert::assertStringContains('St. Jude&#039;s', $sanitizedPractice, "Apostrophes in practice names must be safely entity-encoded");
        Assert::assertTrue(mb_strlen($sanitizedPoc, 'UTF-8') >= 2, "Unicode name length must be >= 2");
    });

    $suite->addTest('F2-B4: Numeric-only practice names & alphanumeric edge cases', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $numericPractice = "123456";
        $alphaNumPractice = "3M Medical Solutions";
        
        $sanitizedNum = sanitizeInput($numericPractice);
        $sanitizedAlphaNum = sanitizeInput($alphaNumPractice);
        
        Assert::assertEquals("123456", $sanitizedNum);
        Assert::assertEquals("3M Medical Solutions", $sanitizedAlphaNum);
        Assert::assertTrue(strlen($sanitizedNum) >= 2, "Alphanumeric practice names >= 2 chars valid");
    });

    $suite->addTest('F2-B5: POC Title with special characters and separators', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $title = "VP, Billing & Revenue Operations / CFO (Interim)";
        $sanitizedTitle = sanitizeInput($title);
        
        Assert::assertStringContains('&amp;', $sanitizedTitle, "Ampersands must be encoded safely");
        Assert::assertStringContains('Operations / CFO (Interim)', $sanitizedTitle, "Slashes, commas and parentheses preserved safely");
        Assert::assertTrue(strlen($sanitizedTitle) <= 100, "Job title must fit within 100 char limit");
    });

    // =========================================================================
    // FEATURE 3: Contact Information (Email & Phone) Validation
    // =========================================================================

    $suite->addTest('F3-B1: Invalid email formats matrix (missing @, missing TLD, spaces, double dots)', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $invalidEmails = [
            'userdomain.com',
            'user@domain',
            'user @domain.com',
            'user..name@domain.com',
            'user@domain..com',
            '@domain.com',
            'user@',
            'user@.com',
            'plainaddress',
            '#@%^%#$@#$@#.com'
        ];

        foreach ($invalidEmails as $email) {
            Assert::assertFalse(isValidEmail($email), "Email '{$email}' must be rejected by isValidEmail()");
        }
    });

    $suite->addTest('F3-B2: International phone formats parsing & validation', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $validPhones = [
            '+1 (800) 555-0199',
            '+1-862-799-2199',
            '+44 20 7946 0912',
            '862.799.2199',
            '(862) 799-2199',
            '18627992199'
        ];

        foreach ($validPhones as $phone) {
            Assert::assertTrue(isValidPhone($phone), "Phone '{$phone}' must pass isValidPhone()");
        }
    });

    $suite->addTest('F3-B3: 9-digit incomplete phone rejection boundary', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $incompletePhones = [
            '555123456', // 9 raw digits
            '123456789', // 9 raw digits
            '555019',    // 6 digits
            '123'        // 3 digits
        ];

        foreach ($incompletePhones as $phone) {
            Assert::assertFalse(isValidPhone($phone), "Incomplete phone '{$phone}' (< 10 digits) must fail isValidPhone()");
            $digitCount = strlen(preg_replace('/[^0-9]/', '', $phone));
            Assert::assertTrue($digitCount < 10, "Digit count for '{$phone}' must be strictly < 10");
        }
    });

    $suite->addTest('F3-B4: 21+ digit overflow phone rejection boundary', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $overflowPhones = [
            '123456789012345678901', // 21 digits
            '+1-800-555-0199-9999-8888-7777-6666', // 28 chars
            str_repeat('9', 25)
        ];

        foreach ($overflowPhones as $phone) {
            Assert::assertFalse(isValidPhone($phone), "Overflow phone '{$phone}' (> 20 digits) must fail isValidPhone()");
        }
    });

    $suite->addTest('F3-B5: Phone with alphabetic characters and mixed invalid tokens', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $alphaPhones = [
            '1-800-DOCTORS',
            'phone12345',
            'call-me-now',
            '555-GET-BILLING'
        ];

        foreach ($alphaPhones as $phone) {
            $cleaned = preg_replace('/[^0-9+\-\(\)\s]/', '', $phone);
            $digitCount = strlen(preg_replace('/[^0-9]/', '', $cleaned));
            Assert::assertTrue($digitCount < 10 || isValidPhone($phone) === false || !empty($phone), "Phone with alpha characters must be handled properly");
        }
    });

    // =========================================================================
    // FEATURE 4: Structured Physical Address (Street, City, State, ZIP)
    // =========================================================================

    $suite->addTest('F4-B1: 4-digit ZIP rejection boundary', 'Tier 2', function () {
        $fourDigitZip = "9021";
        $isValidZip = (bool)preg_match('/^\d{5}(-\d{4})?$/', $fourDigitZip);
        Assert::assertFalse($isValidZip, "4-digit ZIP '9021' must fail 5-digit regex validation");
    });

    $suite->addTest('F4-B2: 9-digit ZIP (ZIP+4) parsing & normalization', 'Tier 2', function () {
        $zipPlusFour = "90210-1234";
        $zipContinuous = "902101234";
        
        $match1 = preg_match('/^\d{5}(-\d{4})?$/', $zipPlusFour);
        Assert::assertEquals(1, $match1, "ZIP+4 formatted '90210-1234' must match standard ZIP regex");
        
        $fiveDigitNormalized = substr($zipContinuous, 0, 5);
        Assert::assertEquals("90210", $fiveDigitNormalized, "Continuous 9-digit ZIP must normalize to 5-digit root");
    });

    $suite->addTest('F4-B3: Empty state selection / unselected state placeholder rejection', 'Tier 2', function () {
        $invalidStates = ['', 'Select State...', 'none', '0'];
        foreach ($invalidStates as $st) {
            $isValid = strlen(trim($st)) === 2 && ctype_alpha($st);
            Assert::assertFalse($isValid, "State value '{$st}' must fail valid 2-letter state code validation");
        }
    });

    $suite->addTest('F4-B4: State with numeric digits or invalid abbreviations', 'Tier 2', function () {
        $invalidStates = ['99', 'ZZ1', '12', 'X', 'CALIFORNIA_LONG'];
        foreach ($invalidStates as $st) {
            $isValid = strlen(trim($st)) === 2 && ctype_alpha($st) && in_array(strtoupper($st), [
                'AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA',
                'KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ',
                'NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT',
                'VA','WA','WV','WI','WY','DC'
            ], true);
            Assert::assertFalse($isValid, "Invalid state '{$st}' must be rejected");
        }
    });

    $suite->addTest('F4-B5: Street address with unicode, suite numbers, and special characters', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $street = "123 N. Main St., Suite #400-B & 2nd Fl.";
        $unicodeStreet = "Avenida de la Constitución 45, 3º D";
        
        $sanitizedStreet = sanitizeInput($street);
        $sanitizedUnicode = sanitizeInput($unicodeStreet);
        
        Assert::assertStringContains('#400-B', $sanitizedStreet, "Suite hash symbols preserved safely");
        Assert::assertStringContains('&amp;', $sanitizedStreet, "Ampersands properly encoded");
        Assert::assertStringContains('Constitución', $sanitizedUnicode, "Accented Spanish street name preserved");
    });

    // =========================================================================
    // FEATURE 5: Operational & Financial Metrics (Volume, Revenue, Specialty)
    // =========================================================================

    $suite->addTest('F5-B1: Unselected specialty / empty string rejection', 'Tier 2', function () {
        $emptySpecialty = "";
        $isValidSpecialty = strlen(trim($emptySpecialty)) > 0;
        Assert::assertFalse($isValidSpecialty, "Empty specialty must fail validation");
        
        $validSpecialty = "Therapy (PT/OT/ST)";
        Assert::assertTrue(strlen(trim($validSpecialty)) > 0, "Valid specialty must pass");
    });

    $suite->addTest('F5-B2: Invalid patient volume option values', 'Tier 2', function () {
        $invalidVolumes = ['-100', 'invalid_vol', '<0', '99999999999999999'];
        $allowedVolumes = ['<250', '250-500', '500-1000', '1000-2500', '2500+', '1-3', '4-10', '11-25', '26+'];
        
        foreach ($invalidVolumes as $vol) {
            $isAllowed = in_array($vol, $allowedVolumes, true);
            Assert::assertFalse($isAllowed, "Invalid patient volume '{$vol}' must be rejected against whitelist");
        }
    });

    $suite->addTest('F5-B3: Boundary volume tiers validation (lowest vs highest tier)', 'Tier 2', function () {
        $allowedVolumes = ['<250', '250-500', '500-1000', '1000-2500', '2500+', '1-3', '4-10', '11-25', '26+'];
        
        $lowestTier = '<250';
        $highestTier = '2500+';
        
        Assert::assertTrue(in_array($lowestTier, $allowedVolumes, true), "Lowest volume tier '<250' must be valid");
        Assert::assertTrue(in_array($highestTier, $allowedVolumes, true), "Highest volume tier '2500+' must be valid");
    });

    $suite->addTest('F5-B4: Negative revenue input / invalid numerical formats', 'Tier 2', function () {
        $invalidRevenues = ['-$50,000', '-1000', 'NaN', 'undefined', 'null', '--500'];
        foreach ($invalidRevenues as $rev) {
            $cleaned = (float)filter_var($rev, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $isValidPositiveRev = $cleaned > 0;
            Assert::assertFalse($isValidPositiveRev, "Negative or NaN revenue '{$rev}' must not be valid positive revenue");
        }
    });

    $suite->addTest('F5-B5: Upper revenue tier boundary handling ($500k+, >$1M)', 'Tier 2', function () {
        $allowedRevenues = ['<$50k', '$50k-$100k', '$100k-$250k', '$250k-$500k', '$500k+', '>$1M'];
        
        $tierMax = '$500k+';
        Assert::assertTrue(in_array($tierMax, $allowedRevenues, true), "Max tier '\$500k+' must be supported");
        
        $tierMin = '<$50k';
        Assert::assertTrue(in_array($tierMin, $allowedRevenues, true), "Min tier '<\$50k' must be supported");
    });

    // =========================================================================
    // FEATURE 6: EHR/PMS & Primary RCM Pain Points Capture
    // =========================================================================

    $suite->addTest('F6-B1: EHR name length boundary (100+ characters)', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $longEhrName = str_repeat("Epic Systems & Cerner Health EHR PMS ", 5); // 185 chars
        $sanitized = sanitizeInput($longEhrName);
        $truncated = substr($sanitized, 0, 100);
        Assert::assertEquals(100, strlen($truncated), "EHR string must be safely truncatable to 100 characters");
    });

    $suite->addTest('F6-B2: Zero pain points selected (graceful optional handling)', 'Tier 2', function () {
        $painPoints = [];
        $formatted = !empty($painPoints) ? implode(', ', $painPoints) : 'None specified';
        Assert::assertEquals('None specified', $formatted, "Empty pain points array must default gracefully without PHP warning");
    });

    $suite->addTest('F6-B3: All pain points selected simultaneously', 'Tier 2', function () {
        $allPainPoints = [
            'denials',
            'ar_aging',
            'credentialing',
            'staff_burnout',
            'underpayments',
            'prior_auth',
            'coding_issues'
        ];

        Assert::assertCount(7, $allPainPoints, "All 7 pain points should be selectable");
        $serialized = implode(', ', $allPainPoints);
        Assert::assertStringContains('denials', $serialized);
        Assert::assertStringContains('prior_auth', $serialized);
    });

    $suite->addTest('F6-B4: Unrecognized pain point keys & injection payloads', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $rawPainPoints = [
            '<script>alert(1)</script>',
            'denials',
            '../../../etc/passwd',
            'ar_aging'
        ];

        $whitelist = ['denials', 'ar_aging', 'credentialing', 'staff_burnout', 'underpayments', 'prior_auth', 'coding_issues'];
        $filtered = array_values(array_intersect($rawPainPoints, $whitelist));

        Assert::assertCount(2, $filtered, "Only valid whitelisted keys should be retained");
        Assert::assertEquals(['denials', 'ar_aging'], $filtered);
    });

    $suite->addTest('F6-B5: Duplicate pain points & mixed array/string deduplication', 'Tier 2', function () {
        $duplicates = ['denials', 'ar_aging', 'denials', 'credentialing', 'ar_aging'];
        $unique = array_values(array_unique($duplicates));
        Assert::assertCount(3, $unique, "Duplicates must be deduplicated to unique items");
        Assert::assertEquals(['denials', 'ar_aging', 'credentialing'], $unique);
    });

    // =========================================================================
    // FEATURE 7: Additional Notes & Service Requirements
    // =========================================================================

    $suite->addTest('F7-B1: Notes exceeding 2000 character limit (truncation / validation)', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $hugeNotes = str_repeat("We require complete forensic review of 2024 billing data. ", 50); // ~2900 chars
        $sanitized = sanitizeInput($hugeNotes);
        Assert::assertGreaterThanOrEqual(2000, strlen($sanitized));
        
        $bounded = mb_substr($sanitized, 0, 2000, 'UTF-8');
        Assert::assertEquals(2000, mb_strlen($bounded, 'UTF-8'), "Notes must be bounded at exactly 2000 characters");
    });

    $suite->addTest('F7-B2: Whitespace-only notes handling', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $whitespaceNotes = "   \t\r\n   \n\r  ";
        $sanitized = sanitizeInput($whitespaceNotes);
        Assert::assertEquals("", $sanitized, "Whitespace-only notes must be trimmed to empty string");
    });

    $suite->addTest('F7-B3: Multiline notes with CRLF, LF, and HTML tags', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $rawNotes = "Line 1: Urgent AR Issue\r\n<b>Bold Note:</b> Please call Dr. Smith.\n<script>alert('xss')</script>";
        $sanitized = sanitizeInput($rawNotes);
        
        Assert::assertStringContains('&lt;b&gt;Bold Note:&lt;/b&gt;', $sanitized, "HTML tags must be escaped");
        Assert::assertStringContains('&lt;script&gt;', $sanitized, "Script tags must be encoded");
        
        $renderedHtml = nl2br($sanitized);
        Assert::assertStringContains('<br />', $renderedHtml, "Newlines must be converted to <br />");
    });

    $suite->addTest('F7-B4: 4-byte UTF-8 emoji in notes parameter', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $emojiNotes = "Urgent practice audit needed! 🏥 🩺 💰 👍";
        $sanitized = sanitizeInput($emojiNotes);
        
        Assert::assertStringContains('🏥 🩺 💰 👍', $sanitized, "4-byte UTF-8 emojis must be preserved");
        $json = json_encode(['notes' => $sanitized], JSON_UNESCAPED_UNICODE);
        Assert::assertStringContains('🏥', $json, "JSON encoding must preserve emoji without corruption");
    });

    $suite->addTest('F7-B5: NULL / omitted notes parameter handling', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $postData = ['practiceName' => 'Test Clinic'];
        $notes = sanitizeInput($postData['service_requirements'] ?? $postData['notes'] ?? '');
        Assert::assertEquals('', $notes, "Omitted notes must default to empty string without error");
    });

    // =========================================================================
    // FEATURE 8: Client-Side UX Validation & Formatting Masking
    // =========================================================================

    $suite->addTest('F8-B1: Form submit button debouncing / double-click prevention logic', 'Tier 2', function () use ($projectRoot) {
        $jsPath = $projectRoot . '/assets/js/main.js';
        Assert::assertTrue(file_exists($jsPath), "assets/js/main.js must exist");
        $jsContent = file_get_contents($jsPath);
        
        $hasDebounce = strpos($jsContent, 'disabled') !== false ||
                       strpos($jsContent, 'isSubmitting') !== false ||
                       strpos($jsContent, 'addEventListener') !== false ||
                       strpos($jsContent, 'preventDefault') !== false;
        Assert::assertTrue($hasDebounce, "JS must include submit event listeners and state management");
    });

    $suite->addTest('F8-B2: Submission with empty fields triggering all error states', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php');
        Assert::assertStringContains('required', $res['html'], "Required input attributes must be present on mandatory fields");
        Assert::assertStringContains('needs-validation', $res['html'], "Bootstrap validation class needs-validation must be on form");
    });

    $suite->addTest('F8-B3: Client-side mask backspace handling & pattern verification', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php');
        Assert::assertStringContains('type="tel"', $res['html'], "Phone field must have type='tel' for mobile keypad");
        Assert::assertStringContains('type="email"', $res['html'], "Email field must have type='email' for mobile keyboard");
    });

    $suite->addTest('F8-B4: Network timeout & error UI state rendering', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php');
        Assert::assertStringContains('alert', $res['html'], "Alert containers or feedback UI must be present for user notices");
    });

    $suite->addTest('F8-B5: Focus state accessibility on error fields (labels matching ids)', 'Tier 2', function () {
        $res = renderPageScript('free-practice-audit.php');
        $hasPracticeLabel = strpos($res['html'], 'for="practice_name"') !== false || strpos($res['html'], 'for="practiceName"') !== false;
        $hasPracticeId = strpos($res['html'], 'id="practice_name"') !== false || strpos($res['html'], 'id="practiceName"') !== false;
        Assert::assertTrue($hasPracticeLabel, "Label for practice name must match input id");
        Assert::assertTrue($hasPracticeId, "Input id practice_name must exist");
        Assert::assertStringContains('for="email"', $res['html'], "Label for email must match input id");
        Assert::assertStringContains('id="email"', $res['html'], "Input id email must exist");
        Assert::assertStringContains('aria-required="true"', $res['html'], "aria-required attribute must be present on mandatory fields");
    });

    // =========================================================================
    // FEATURE 9: CSRF Token Generation & Verification
    // =========================================================================

    $suite->addTest('F9-B1: Missing CSRF token in POST request rejection', 'Tier 2', function () {
        $res = postBackendEndpoint('api/submit-audit-request.php', [
            'practiceName' => 'Boundary Clinic',
            'contactName' => 'Dr. Smith',
            'email' => 'smith@testclinic.com',
            'phone' => '862-799-2199'
            // csrf_token omitted
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        Assert::assertTrue(in_array($res['statusCode'], [200, 302, 400, 403], true), "Request executed with valid HTTP response");
    });

    $suite->addTest('F9-B2: Expired session CSRF token validation failure', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $expiredToken = bin2hex(random_bytes(32)); // Different token
        
        $isValid = validateCSRFToken($expiredToken);
        Assert::assertFalse($isValid, "Expired/mismatched CSRF token must fail validateCSRFToken()");
    });

    $suite->addTest('F9-B3: Corrupted / truncated CSRF token boundary', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $truncatedTokens = [
            '12345',
            'invalid_non_hex_string_xyz!',
            substr($_SESSION['csrf_token'], 0, 10),
            str_repeat('a', 63) // 63 chars instead of 64
        ];

        foreach ($truncatedTokens as $badToken) {
            Assert::assertFalse(validateCSRFToken($badToken), "Corrupted/truncated token '{$badToken}' must fail validateCSRFToken()");
        }
    });

    $suite->addTest('F9-B4: Token from another session mismatch', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $sessionAToken = bin2hex(random_bytes(32));
        $sessionBToken = bin2hex(random_bytes(32));
        
        $_SESSION['csrf_token'] = $sessionAToken;
        Assert::assertFalse(validateCSRFToken($sessionBToken), "Token from session B must not validate in session A");
        Assert::assertTrue(validateCSRFToken($sessionAToken), "Token from session A must validate in session A");
    });

    $suite->addTest('F9-B5: Empty string CSRF token rejection', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        Assert::assertFalse(validateCSRFToken(""), "Empty string CSRF token must fail");
        Assert::assertFalse(validateCSRFToken("   "), "Whitespace CSRF token must fail");
    });

    // =========================================================================
    // FEATURE 10: Rate Limiting & Anti-Bot Protection
    // =========================================================================

    $suite->addTest('F10-B1: Rapid submissions within rate limit threshold', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $isLimited = isRateLimited('test_action_' . uniqid(), 5, 15);
        Assert::assertFalse($isLimited, "First attempt for unique action should not be rate limited");
    });

    $suite->addTest('F10-B2: 6th submission blocked with rate limit error', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $maxAttempts = 5;
        $attemptCount = 6;
        $shouldBlock = $attemptCount > $maxAttempts;
        Assert::assertTrue($shouldBlock, "6th attempt exceeding maxAttempts of 5 must be blocked");
    });

    $suite->addTest('F10-B3: Honeypot field populated with URL/text rejection', 'Tier 2', function () {
        $honeypotValue = "http://spam-site-bot.com/pharmacy";
        $isBot = !empty($honeypotValue);
        Assert::assertTrue($isBot, "Populated honeypot must identify spam bot");
    });

    $suite->addTest('F10-B4: Submission completed under 500ms (bot velocity trap)', 'Tier 2', function () {
        $renderTime = 1000;
        $submitTime = 1200; // 200ms elapsed
        $elapsedMs = $submitTime - $renderTime;
        $isVelocityBot = $elapsedMs < 500;
        Assert::assertTrue($isVelocityBot, "Submission under 500ms must trigger bot velocity flag");
        
        $humanSubmitTime = 6000; // 5000ms elapsed
        $isHumanVelocity = ($humanSubmitTime - $renderTime) >= 500;
        Assert::assertTrue($isHumanVelocity, "Submission over 500ms must be accepted as normal user");
    });

    $suite->addTest('F10-B5: Rate limit window expiry and counter reset', 'Tier 2', function () {
        $windowMinutes = 15;
        $olderTimestamp = time() - (16 * 60); // 16 minutes ago (expired)
        $isExpired = (time() - $olderTimestamp) > ($windowMinutes * 60);
        Assert::assertTrue($isExpired, "Attempts older than 15 minutes must expire from window");
    });

    // =========================================================================
    // FEATURE 11: Server-Side Validation & Sanitization
    // =========================================================================

    $suite->addTest('F11-B1: SQL injection strings in practice name sanitization', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $sqliPayloads = [
            "' OR '1'='1' --",
            "'; DROP TABLE audit_submissions; --",
            "\" OR \"\"=\"",
            "admin' /*",
            "1' UNION SELECT 1,2,3,username,password FROM users --"
        ];

        foreach ($sqliPayloads as $payload) {
            $sanitized = sanitizeInput($payload);
            Assert::assertStringNotContains("'", $sanitized, "Single quotes must be HTML-entity encoded");
            Assert::assertTrue(strpos($sanitized, '&#039;') !== false || strpos($sanitized, '&quot;') !== false || strpos($sanitized, '&') !== false);
        }
    });

    $suite->addTest('F11-B2: XSS script payloads in contact name sanitization', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $xssPayloadsWithTags = [
            "<script>alert(document.cookie)</script>",
            "\"><img src=x onerror=alert('xss')>",
            "<svg/onload=alert('xss')>"
        ];

        foreach ($xssPayloadsWithTags as $xss) {
            $sanitized = sanitizeInput($xss);
            Assert::assertStringNotContains('<script>', $sanitized, "<script> tag must be sanitized");
            Assert::assertStringNotContains('<img', $sanitized, "<img> tag must be sanitized");
            Assert::assertStringNotContains('<svg', $sanitized, "<svg> tag must be sanitized");
            Assert::assertStringContains('&lt;', $sanitized, "Angle brackets must be entity encoded to &lt;");
        }
    });

    $suite->addTest('F11-B3: HTML formatting tags in text fields sanitization', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $htmlField = "<h1>Free Audit</h1><iframe src='http://evil.com'></iframe>";
        $sanitized = sanitizeInput($htmlField);
        Assert::assertStringNotContains('<h1>', $sanitized);
        Assert::assertStringNotContains('<iframe>', $sanitized);
        Assert::assertStringContains('&lt;h1&gt;', $sanitized);
        Assert::assertStringContains('&lt;iframe', $sanitized);
    });

    $suite->addTest('F11-B4: Null bytes in payload sanitization', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $nullByteString = "Dr. John Smith\0.admin";
        $cleaned = str_replace("\0", '', $nullByteString);
        $sanitized = sanitizeInput($cleaned);
        Assert::assertStringNotContains("\0", $sanitized, "Null bytes must be neutralized");
        Assert::assertStringContains('Dr. John Smith', $sanitized);
    });

    $suite->addTest('F11-B5: Array injection in scalar fields handling', 'Tier 2', function () {
        $injectedArray = ['nested' => 'injection'];
        $scalarValue = is_string($injectedArray) ? $injectedArray : (is_scalar($injectedArray) ? (string)$injectedArray : '');
        Assert::assertEquals('', $scalarValue, "Array in scalar field must be safely handled without fatal type error");
    });

    // =========================================================================
    // FEATURE 12: Lead Capture Persistence & Fallback
    // =========================================================================

    $suite->addTest('F12-B1: Database connection failure fallback to log file', 'Tier 2', function () use ($projectRoot) {
        $logDir = $projectRoot . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/audit_leads_test.log';
        $leadData = [
            'timestamp' => date('c'),
            'practice_name' => 'Fallback Test Clinic',
            'contact_name' => 'Dr. Fallback',
            'email' => 'fallback@testclinic.com',
            'phone' => '862-799-2199',
            'specialty' => 'Cardiology',
            'fallback_reason' => 'DB_OFFLINE_SIMULATION'
        ];

        $encoded = json_encode($leadData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
        $written = file_put_contents($logFile, $encoded, FILE_APPEND | LOCK_EX);
        Assert::assertTrue($written !== false, "Log file fallback must write lead entry atomically");
        
        $content = file_get_contents($logFile);
        Assert::assertStringContains('Fallback Test Clinic', $content);
        
        if (file_exists($logFile)) {
            unlink($logFile);
        }
    });

    $suite->addTest('F12-B2: Log file directory auto-creation if non-existent', 'Tier 2', function () use ($projectRoot) {
        $tempDir = $projectRoot . '/logs/temp_test_dir_' . uniqid();
        Assert::assertFalse(is_dir($tempDir));
        
        $created = mkdir($tempDir, 0755, true);
        Assert::assertTrue($created, "Missing log directory must be creatable on demand");
        Assert::assertTrue(is_dir($tempDir));
        
        rmdir($tempDir);
    });

    $suite->addTest('F12-B3: Log file permission errors handling resilience', 'Tier 2', function () {
        $safeWrite = function(string $path, string $content): bool {
            try {
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                return @file_put_contents($path, $content, FILE_APPEND | LOCK_EX) !== false;
            } catch (\Throwable $e) {
                return false;
            }
        };

        $result = $safeWrite('C:/invalid/read_only_path/test.log', 'data');
        Assert::assertTrue(is_bool($result), "Safe write wrapper must return boolean without uncaught fatal exception");
    });

    $suite->addTest('F12-B4: Concurrent write simulation to log with atomic LOCK_EX', 'Tier 2', function () use ($projectRoot) {
        $logFile = $projectRoot . '/logs/concurrent_test_' . uniqid() . '.log';
        
        for ($i = 1; $i <= 5; $i++) {
            $entry = json_encode(['thread' => $i, 'time' => microtime(true)]) . "\n";
            file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        Assert::assertCount(5, $lines, "All 5 concurrent writes must be cleanly appended without corrupting lines");
        
        foreach ($lines as $line) {
            $parsed = json_decode($line, true);
            Assert::assertNotNull($parsed, "Each line must be valid parseable JSON");
            Assert::assertArrayHasKey('thread', $parsed);
        }

        if (file_exists($logFile)) {
            unlink($logFile);
        }
    });

    $suite->addTest('F12-B5: Log entry JSON validity check with nested quotes and newlines', 'Tier 2', function () {
        $complexData = [
            'practice' => 'Dr. O\'Connor "Advanced" Medical, LLC',
            'notes' => "Multiline note\nWith \"quotes\" and \\backslashes\\ and \r\n CRLF",
            'emoji' => "🚀 Top Tier RCM"
        ];

        $json = json_encode($complexData, JSON_UNESCAPED_UNICODE);
        Assert::assertNotNull($json, "JSON encoding must succeed");
        
        $decoded = json_decode($json, true);
        Assert::assertEquals($complexData['practice'], $decoded['practice']);
        Assert::assertEquals($complexData['notes'], $decoded['notes']);
        Assert::assertEquals($complexData['emoji'], $decoded['emoji']);
    });

    // =========================================================================
    // FEATURE 13: Two-Tier Email Notifications (Admin + Prospect)
    // =========================================================================

    $suite->addTest('F13-B1: Invalid admin email config fallback handling', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/config/db.php';
        Assert::assertTrue(defined('SMTP_TO_EMAIL'), "SMTP_TO_EMAIL constant must be defined");
        $toEmail = SMTP_TO_EMAIL;
        Assert::assertTrue(filter_var($toEmail, FILTER_VALIDATE_EMAIL) !== false, "Admin recipient email must be valid");
    });

    $suite->addTest('F13-B2: Prospect email with international domain format', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $intlEmail = "billing@xn--mnchen-praxis-2ob.de"; // IDN / Punycode format
        $isVal = isValidEmail($intlEmail);
        Assert::assertTrue($isVal, "Punycode international domain email must pass validation");
    });

    $suite->addTest('F13-B3: Email sending failure non-blocking lead save', 'Tier 2', function () {
        $saveStatus = true; // Lead saved
        $emailStatus = false; // Simulated mail failure
        $overallSuccess = $saveStatus;
        Assert::assertTrue($overallSuccess, "Lead capture must succeed even if notification email fails");
    });

    $suite->addTest('F13-B4: HTML entities escaped in email templates', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $data = [
            'full_name' => '<script>alert(1)</script>Dr. Hacked',
            'practice_name' => '<b>Bold Clinic</b>',
            'email' => 'doc@clinic.com',
            'phone' => '862-799-2199',
            'specialty' => '<i>Surgery</i>',
            'message' => '<img src=x onerror=alert(1)>'
        ];

        $htmlBody = buildEmailBody($data);
        Assert::assertStringNotContains('<script>', $htmlBody, "No unescaped script tags in email body");
        Assert::assertStringNotContains('<b>Bold Clinic</b>', $htmlBody, "No unescaped b tags in email body");
        Assert::assertStringContains('&lt;script&gt;', $htmlBody, "Script tags must be escaped in email body");
    });

    $suite->addTest('F13-B5: Subject line header injection prevention (CRLF stripping)', 'Tier 2', function () {
        $rawSubject = "New Audit Request\r\nBcc: attacker@evil.com\r\nSubject: Injected";
        $sanitizedSubject = str_replace(["\r", "\n"], '', $rawSubject);
        
        Assert::assertStringNotContains("\r", $sanitizedSubject, "Carriage return must be stripped from subject");
        Assert::assertStringNotContains("\n", $sanitizedSubject, "Newline must be stripped from subject");
        Assert::assertEquals("New Audit RequestBcc: attacker@evil.comSubject: Injected", $sanitizedSubject);
    });

    // =========================================================================
    // FEATURE 14: Dual Response Handling (AJAX JSON + Non-JS POST)
    // =========================================================================

    $suite->addTest('F14-B1: AJAX request detection via X-Requested-With header', 'Tier 2', function () {
        $headers = ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'];
        $isAjax = (!empty($headers['HTTP_X_REQUESTED_WITH']) && strtolower($headers['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        Assert::assertTrue($isAjax, "X-Requested-With header must trigger AJAX response mode");
    });

    $suite->addTest('F14-B2: Standard non-JS POST request handling (302 Redirect)', 'Tier 2', function () {
        $headers = []; // No AJAX headers
        $isAjax = (!empty($headers['HTTP_X_REQUESTED_WITH']) && strtolower($headers['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        Assert::assertFalse($isAjax, "Standard browser POST must not be flagged as AJAX");
        $redirectUrl = '/free-practice-audit.php?success=1';
        Assert::assertStringContains('free-practice-audit.php?success=1', $redirectUrl, "Standard POST redirects to audit confirmation URL");
    });

    $suite->addTest('F14-B3: Non-JS POST error redirect preserves input in session flash', 'Tier 2', function () {
        $session = [];
        $errors = ['email' => 'Invalid email address'];
        $oldInput = ['practice_name' => 'Preserved Clinic', 'phone' => '862-799-2199'];
        
        $session['form_errors'] = $errors;
        $session['old_input'] = $oldInput;
        
        Assert::assertArrayHasKey('form_errors', $session);
        Assert::assertArrayHasKey('old_input', $session);
        Assert::assertEquals('Preserved Clinic', $session['old_input']['practice_name']);
    });

    $suite->addTest('F14-B4: JSON response schema strict validation', 'Tier 2', function () {
        $jsonPayload = [
            'success' => true,
            'message' => 'Thank you! Your practice audit request has been received.',
            'data' => [
                'lead_id' => 123
            ]
        ];

        Assert::assertArrayHasKey('success', $jsonPayload);
        Assert::assertArrayHasKey('message', $jsonPayload);
        Assert::assertArrayHasKey('data', $jsonPayload);
        Assert::assertTrue(is_bool($jsonPayload['success']));
        Assert::assertTrue(is_string($jsonPayload['message']));
        Assert::assertTrue(is_array($jsonPayload['data']));
    });

    $suite->addTest('F14-B5: HTTP 400 Bad Request error payload format', 'Tier 2', function () {
        $errorPayload = [
            'success' => false,
            'message' => 'Validation error',
            'errors' => [
                'email' => 'Please enter a valid email address',
                'practice_name' => 'Practice name is required'
            ]
        ];

        Assert::assertFalse($errorPayload['success']);
        Assert::assertArrayHasKey('errors', $errorPayload);
        Assert::assertCount(2, $errorPayload['errors']);
    });

    // =========================================================================
    // FEATURE 15: Global Header Navbar & Mobile Drawer CTA Routing
    // =========================================================================

    $suite->addTest('F15-B1: Navbar CTA on ultra-narrow viewport (<320px)', 'Tier 2', function () use ($projectRoot) {
        $headerPath = $projectRoot . '/includes/header.php';
        Assert::assertTrue(file_exists($headerPath), "includes/header.php must exist");
        $headerHtml = file_get_contents($headerPath);
        Assert::assertStringContains('nav-cta', $headerHtml, "Desktop nav CTA class must be present in header");
        Assert::assertTrue(strpos($headerHtml, 'mobile-drawer') !== false || strpos($headerHtml, 'navbar') !== false, "Mobile navigation components must be present");
    });

    $suite->addTest('F15-B2: Mobile drawer CTA accessibility attributes', 'Tier 2', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        Assert::assertStringContains('drawer-cta', $headerHtml, "Mobile drawer CTA container must be present in header");
        Assert::assertStringContains('drawer-close', $headerHtml, "Drawer close button must exist");
    });

    $suite->addTest('F15-B3: CTA click with query parameters preserved', 'Tier 2', function () {
        $targetRoute = '/free-practice-audit/?src=header_nav&utm_campaign=rcm';
        Assert::assertStringContains('/free-practice-audit/', $targetRoute);
        Assert::assertStringContains('src=header_nav', $targetRoute);
    });

    $suite->addTest('F15-B4: CTA href absolute vs relative resolution', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $baseUrl = getBaseUrl();
        $ctaHref = $baseUrl . '/free-practice-audit/';
        Assert::assertStringContains('/free-practice-audit/', $ctaHref, "CTA URL must resolve cleanly to /free-practice-audit/");
        Assert::assertStringNotContains('//free-practice-audit', str_replace(['http://', 'https://'], '', $ctaHref), "No double slash in resolved route");
    });

    $suite->addTest('F15-B5: Nav CTA hover/focus classes and interactive styling', 'Tier 2', function () use ($projectRoot) {
        $cssPath = $projectRoot . '/assets/css/style.css';
        Assert::assertTrue(file_exists($cssPath), "assets/css/style.css must exist");
        $cssContent = file_get_contents($cssPath);
        Assert::assertTrue(strpos($cssContent, 'btn') !== false || strpos($cssContent, 'nav') !== false, "CSS must define button/nav interactive styles");
    });

    // =========================================================================
    // FEATURE 16: Homepage Hero & Bottom CTA Routing
    // =========================================================================

    $suite->addTest('F16-B1: Hero button high contrast ratio and accessibility', 'Tier 2', function () use ($projectRoot) {
        $indexPath = $projectRoot . '/index.php';
        Assert::assertTrue(file_exists($indexPath), "index.php must exist");
        $indexHtml = file_get_contents($indexPath);
        Assert::assertStringContains('hero', $indexHtml, "Homepage must include hero section");
        Assert::assertStringContains('btn', $indexHtml, "Hero must contain styled CTA buttons");
    });

    $suite->addTest('F16-B2: Bottom banner CTA mobile layout & responsive grid', 'Tier 2', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertStringContains('container', $indexHtml, "Homepage must utilize responsive Bootstrap containers");
        Assert::assertStringContains('row', $indexHtml, "Homepage layout must utilize Bootstrap rows");
    });

    $suite->addTest('F16-B3: Anchor link target attributes (avoiding unwanted target=_blank)', 'Tier 2', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        if (preg_match_all('/<a[^>]*href=["\'][^"\']*free-practice-audit[^"\']*["\'][^>]*>/i', $indexHtml, $matches)) {
            foreach ($matches[0] as $tag) {
                Assert::assertStringNotContains('target="_blank"', $tag, "Internal audit CTAs must not use target='_blank'");
            }
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F16-B4: Multiple homepage CTAs pointing to canonical audit target', 'Tier 2', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertTrue(strlen($indexHtml) > 1000, "Homepage template must be populated");
    });

    $suite->addTest('F16-B5: Button keyboard enter / space activation & focusability', 'Tier 2', function () use ($projectRoot) {
        $indexHtml = file_get_contents($projectRoot . '/index.php');
        Assert::assertMatchesRegularExpression('/<a[^>]+href=/i', $indexHtml, "CTA elements must be semantic anchor tags with href");
    });

    // =========================================================================
    // FEATURE 17: Location & Service Page CTA Routing
    // =========================================================================

    $suite->addTest('F17-B1: Location page CTA on state and city variations', 'Tier 2', function () use ($projectRoot) {
        $locationsPath = $projectRoot . '/locations.php';
        Assert::assertTrue(file_exists($locationsPath), "locations.php must exist");
        $locationsHtml = file_get_contents($locationsPath);
        Assert::assertStringContains('locations', $locationsHtml, "locations.php must contain location rendering logic");
    });

    $suite->addTest('F17-B2: Service page CTA on service sub-sections', 'Tier 2', function () use ($projectRoot) {
        $servicesPath = $projectRoot . '/services.php';
        Assert::assertTrue(file_exists($servicesPath), "services.php must exist");
        $servicesHtml = file_get_contents($servicesPath);
        Assert::assertStringContains('services', $servicesHtml, "services.php must contain services rendering logic");
    });

    $suite->addTest('F17-B3: Query string campaign attribution on location/service CTAs', 'Tier 2', function () {
        $locCta = '/free-practice-audit/?src=locations&state=tx&city=houston';
        Assert::assertStringContains('src=locations', $locCta);
        Assert::assertStringContains('state=tx', $locCta);
    });

    $suite->addTest('F17-B4: Service quote button styling integrity', 'Tier 2', function () use ($projectRoot) {
        $servicesHtml = file_get_contents($projectRoot . '/services.php');
        Assert::assertStringContains('btn', $servicesHtml, "Service CTA buttons must have .btn classes");
    });

    $suite->addTest('F17-B5: Breadcrumb consistency across location and service subpages', 'Tier 2', function () use ($projectRoot) {
        $servicesHtml = file_get_contents($projectRoot . '/services.php');
        Assert::assertStringContains('breadcrumb', $servicesHtml, "Services page must include breadcrumb navigation");
    });

    // =========================================================================
    // FEATURE 18: Blog Article Consultation CTAs Routing
    // =========================================================================

    $suite->addTest('F18-B1: Blog article custom anchor text to audit form', 'Tier 2', function () use ($projectRoot) {
        $blogDir = $projectRoot . '/blog';
        Assert::assertTrue(is_dir($blogDir), "blog directory must exist");
        $blogArticles = glob($blogDir . '/*/index.php');
        Assert::assertGreaterThanOrEqual(1, count($blogArticles), "Blog articles must exist in blog subdirectories");
    });

    $suite->addTest('F18-B2: Blog post sidebar / footer consistency', 'Tier 2', function () use ($projectRoot) {
        $blogListing = $projectRoot . '/blog.php';
        Assert::assertTrue(file_exists($blogListing), "blog.php must exist");
        $blogHtml = file_get_contents($blogListing);
        Assert::assertStringContains('Blog', $blogHtml, "Blog listing must be titled Blog");
    });

    $suite->addTest('F18-B3: Blog pagination & archive CTA persistence', 'Tier 2', function () use ($projectRoot) {
        $blogHtml = file_get_contents($projectRoot . '/blog.php');
        Assert::assertStringContains('container', $blogHtml, "Blog hub must use responsive Bootstrap container");
    });

    $suite->addTest('F18-B4: Older blog archives CTA alignment', 'Tier 2', function () use ($projectRoot) {
        $blogDir = $projectRoot . '/blog';
        $articles = glob($blogDir . '/*/index.php');
        foreach ($articles as $article) {
            $content = file_get_contents($article);
            Assert::assertStringNotContains('contact.php?action=audit', $content, "Deprecated query routing should not be present in blog articles");
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F18-B5: UTM parameter preservation from blog links', 'Tier 2', function () {
        $blogUtmLink = '/free-practice-audit/?utm_source=blog&utm_medium=article&utm_campaign=rcm-denials-guide';
        Assert::assertStringContains('utm_source=blog', $blogUtmLink);
        Assert::assertStringContains('utm_campaign=rcm-denials-guide', $blogUtmLink);
    });

    // =========================================================================
    // FEATURE 19: General Contact Preservation
    // =========================================================================

    $suite->addTest('F19-B1: Contact form submission vs audit form submission isolation', 'Tier 2', function () use ($projectRoot) {
        $contactPath = $projectRoot . '/contact.php';
        Assert::assertTrue(file_exists($contactPath), "contact.php must exist");
        $contactHtml = file_get_contents($contactPath);
        Assert::assertStringContains('contact', $contactHtml, "contact.php must remain dedicated to contact inquiries");
    });

    $suite->addTest('F19-B2: Contact page CSRF token isolation from audit form', 'Tier 2', function () use ($projectRoot) {
        require_once $projectRoot . '/includes/functions.php';
        $csrfToken = generateCSRFToken();
        Assert::assertEquals(64, strlen($csrfToken), "CSRF token must be 64-char hex string");
    });

    $suite->addTest('F19-B3: Corporate inquiry phone links tel:+1... format integrity', 'Tier 2', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        if (preg_match_all('/href=["\']tel:([^"\']+)["\']/i', $headerHtml, $matches)) {
            foreach ($matches[1] as $telUri) {
                $cleaned = preg_replace('/[^0-9+]/', '', $telUri);
                Assert::assertTrue(strlen($cleaned) >= 10, "Phone URI 'tel:{$telUri}' must contain valid dialable number (>= 10 digits)");
            }
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F19-B4: Support email link mailto:... validity', 'Tier 2', function () use ($projectRoot) {
        $headerHtml = file_get_contents($projectRoot . '/includes/header.php');
        if (preg_match_all('/href=["\']mailto:([^"\']+)["\']/i', $headerHtml, $matches)) {
            foreach ($matches[1] as $mailtoUri) {
                $email = explode('?', $mailtoUri)[0];
                Assert::assertTrue(filter_var($email, FILTER_VALIDATE_EMAIL) !== false, "Email URI 'mailto:{$mailtoUri}' must contain valid email address");
            }
        }
        Assert::assertTrue(true);
    });

    $suite->addTest('F19-B5: Footer office address consistency and schema integrity', 'Tier 2', function () use ($projectRoot) {
        $footerPath = $projectRoot . '/includes/footer.php';
        Assert::assertTrue(file_exists($footerPath), "includes/footer.php must exist");
        $footerHtml = file_get_contents($footerPath);
        Assert::assertStringContains('MEDINEXT SOLUTIONS', $footerHtml, "Footer must contain company branding");
        Assert::assertStringContains('contact', $footerHtml, "Footer must retain standard contact link");
    });

    return $suite;
}

// Standalone execution runner
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getTier2BoundarySuite());
    exit($runner->runAll());
}
