<?php
/**
 * MEDINEXT SOLUTIONS - Test Infrastructure & Helper Framework
 * 
 * Standalone, lightweight test execution framework, assertion library,
 * and HTTP/DOM simulation helpers for E2E and unit testing.
 * Compatible across all test tiers (Tier 1 - Tier 5).
 */

declare(strict_types=1);

namespace Medinext\Tests;

// Ensure clean CLI session initialization
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}

/**
 * ANSI Color Formatting for CLI Output
 */
class CliColor
{
    public static function green(string $text): string
    {
        return self::isSupported() ? "\033[32m{$text}\033[0m" : $text;
    }

    public static function red(string $text): string
    {
        return self::isSupported() ? "\033[31m{$text}\033[0m" : $text;
    }

    public static function yellow(string $text): string
    {
        return self::isSupported() ? "\033[33m{$text}\033[0m" : $text;
    }

    public static function cyan(string $text): string
    {
        return self::isSupported() ? "\033[36m{$text}\033[0m" : $text;
    }

    public static function bold(string $text): string
    {
        return self::isSupported() ? "\033[1m{$text}\033[0m" : $text;
    }

    public static function isSupported(): bool
    {
        return DIRECTORY_SEPARATOR === '/' || false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || str_contains((string)getenv('TERM'), 'xterm');
    }
}

/**
 * Test Assertion Class
 */
if (!class_exists('Medinext\Tests\Assert')) {
    class Assert
    {
        public static int $assertionCount = 0;

        public static function increment(): void
        {
            self::$assertionCount++;
        }

        public static function getAssertionCount(): int
        {
            return self::$assertionCount;
        }

        public static function resetAssertionCount(): void
        {
            self::$assertionCount = 0;
        }

        public static function assertTrue($condition, string $message = ''): void
        {
            self::increment();
            if (!$condition) {
                throw new \AssertionError($message ?: "Expected true, got false");
            }
        }

        public static function assertFalse($condition, string $message = ''): void
        {
            self::increment();
            if ($condition) {
                throw new \AssertionError($message ?: "Expected false, got true");
            }
        }

        public static function assertEquals($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($expected !== $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                $actStr = is_scalar($actual) ? (string)$actual : json_encode($actual);
                throw new \AssertionError($message ?: "Expected: {$expStr}, got: {$actStr}");
            }
        }

        public static function assertNotEquals($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($expected === $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                throw new \AssertionError($message ?: "Expected value NOT to equal {$expStr}");
            }
        }

        public static function assertSame($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($expected !== $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                $actStr = is_scalar($actual) ? (string)$actual : json_encode($actual);
                throw new \AssertionError($message ?: "Expected strict identity: {$expStr}, got: {$actStr}");
            }
        }

        public static function assertNotSame($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($expected === $actual) {
                $expStr = is_scalar($expected) ? (string)$expected : json_encode($expected);
                throw new \AssertionError($message ?: "Expected strict difference, but values are identical ({$expStr})");
            }
        }

        public static function assertNull($value, string $message = ''): void
        {
            self::increment();
            if ($value !== null) {
                throw new \AssertionError($message ?: "Expected null, got " . var_export($value, true));
            }
        }

        public static function assertNotNull($value, string $message = ''): void
        {
            self::increment();
            if ($value === null) {
                throw new \AssertionError($message ?: "Expected non-null value");
            }
        }

        public static function assertContains($needle, $haystack, string $message = ''): void
        {
            self::increment();
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
            self::increment();
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
            self::increment();
            if (strpos($haystack, $needle) === false) {
                throw new \AssertionError($message ?: "String does not contain '{$needle}'");
            }
        }

        public static function assertStringContainsIgnoreCase(string $needle, string $haystack, string $message = ''): void
        {
            self::increment();
            if (stripos($haystack, $needle) === false) {
                throw new \AssertionError($message ?: "String does not contain case-insensitive '{$needle}'");
            }
        }

        public static function assertStringNotContains(string $needle, string $haystack, string $message = ''): void
        {
            self::increment();
            if (strpos($haystack, $needle) !== false) {
                throw new \AssertionError($message ?: "String contains unexpected '{$needle}'");
            }
        }

        public static function assertRegex(string $pattern, string $string, string $message = ''): void
        {
            self::increment();
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
            self::increment();
            if (!array_key_exists($key, $array)) {
                throw new \AssertionError($message ?: "Array does not have key '{$key}'");
            }
        }

        public static function assertGreaterThanOrEqual($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($actual < $expected) {
                throw new \AssertionError($message ?: "Expected {$actual} >= {$expected}");
            }
        }

        public static function assertLessThanOrEqual($expected, $actual, string $message = ''): void
        {
            self::increment();
            if ($actual > $expected) {
                throw new \AssertionError($message ?: "Expected {$actual} <= {$expected}");
            }
        }

        public static function assertCount(int $expectedCount, $countable, string $message = ''): void
        {
            self::increment();
            $actualCount = is_countable($countable) ? count($countable) : 0;
            if ($expectedCount !== $actualCount) {
                throw new \AssertionError($message ?: "Expected count {$expectedCount}, got {$actualCount}");
            }
        }

        public static function assertValidJson(string $jsonString, string $message = ''): mixed
        {
            self::increment();
            $decoded = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \AssertionError($message ?: ("Invalid JSON: " . json_last_error_msg() . " -> " . substr($jsonString, 0, 100)));
            }
            return $decoded;
        }

        public static function assertDomContains(string $needleOrSelector, string $html, string $message = ''): void
        {
            self::increment();
            if (str_starts_with($needleOrSelector, '#')) {
                $id = substr($needleOrSelector, 1);
                $pattern = '/id=["\']' . preg_quote($id, '/') . '["\']/i';
                if (!preg_match($pattern, $html)) {
                    throw new \AssertionError($message ?: "DOM does not contain element with ID '{$id}'");
                }
            } elseif (str_starts_with($needleOrSelector, '.')) {
                $class = substr($needleOrSelector, 1);
                $pattern = '/class=["\'][^"\']*\b' . preg_quote($class, '/') . '\b[^"\']*["\']/i';
                if (!preg_match($pattern, $html)) {
                    throw new \AssertionError($message ?: "DOM does not contain element with class '{$class}'");
                }
            } else {
                if (strpos($html, $needleOrSelector) === false) {
                    throw new \AssertionError($message ?: "DOM does not contain expected snippet '{$needleOrSelector}'");
                }
            }
        }

        public static function fail(string $message = ''): void
        {
            self::increment();
            throw new \AssertionError($message ?: "Test failed explicitly");
        }
    }
}

/**
 * Individual Test Case
 */
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
            $startAsserts = Assert::getAssertionCount();
            $start = microtime(true);
            try {
                ($this->callback)();
                $duration = (microtime(true) - $start) * 1000;
                $assertsRun = Assert::getAssertionCount() - $startAsserts;
                return [
                    'status' => 'PASS',
                    'name' => $this->name,
                    'tier' => $this->tier,
                    'duration_ms' => round($duration, 2),
                    'assertions' => $assertsRun,
                    'error' => null
                ];
            } catch (\Throwable $e) {
                $duration = (microtime(true) - $start) * 1000;
                $assertsRun = Assert::getAssertionCount() - $startAsserts;
                return [
                    'status' => 'FAIL',
                    'name' => $this->name,
                    'tier' => $this->tier,
                    'duration_ms' => round($duration, 2),
                    'assertions' => $assertsRun,
                    'error' => $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine()
                ];
            }
        }
    }
}

/**
 * Test Suite Grouping
 */
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

        public function run(bool $verbose = true, ?string $filter = null): array
        {
            $results = [];
            $passed = 0;
            $failed = 0;
            $suiteStart = microtime(true);

            $filteredTests = [];
            foreach ($this->tests as $test) {
                if ($filter !== null && $filter !== '') {
                    if (stripos($test->name, $filter) === false && stripos($test->tier, $filter) === false) {
                        continue;
                    }
                }
                $filteredTests[] = $test;
            }

            if ($verbose) {
                echo "\n" . str_repeat('=', 80) . "\n";
                echo " RUNNING SUITE: " . CliColor::bold($this->name) . "\n";
                if ($this->description) {
                    echo " Description:   {$this->description}\n";
                }
                echo " Total Tests:   " . count($filteredTests) . "\n";
                echo str_repeat('=', 80) . "\n\n";
            }

            foreach ($filteredTests as $index => $test) {
                $res = $test->run();
                $results[] = $res;
                if ($res['status'] === 'PASS') {
                    $passed++;
                    if ($verbose) {
                        $passLabel = CliColor::green('PASS');
                        printf("  [%02d/%02d]  %s  [%s] %s (%s ms)\n", $index + 1, count($filteredTests), $passLabel, $test->tier, $test->name, $res['duration_ms']);
                    }
                } else {
                    $failed++;
                    if ($verbose) {
                        $failLabel = CliColor::red('!FAIL!');
                        printf("  [%02d/%02d] %s [%s] %s (%s ms)\n", $index + 1, count($filteredTests), $failLabel, $test->tier, $test->name, $res['duration_ms']);
                        echo "         " . CliColor::red("Error: " . $res['error']) . "\n";
                    }
                }
            }

            $suiteDuration = round((microtime(true) - $suiteStart) * 1000, 2);

            if ($verbose) {
                echo "\n" . str_repeat('-', 80) . "\n";
                echo " SUITE RESULT: " . CliColor::bold($this->name) . "\n";
                $passStr = CliColor::green("Passed: {$passed}");
                $failStr = $failed > 0 ? CliColor::red("Failed: {$failed}") : "Failed: 0";
                echo " {$passStr} | {$failStr} | Total: " . count($filteredTests) . " | Duration: {$suiteDuration} ms\n";
                echo str_repeat('-', 80) . "\n";
            }

            return [
                'name' => $this->name,
                'total' => count($filteredTests),
                'passed' => $passed,
                'failed' => $failed,
                'duration_ms' => $suiteDuration,
                'results' => $results
            ];
        }
    }
}

/**
 * Master Test Runner
 */
if (!class_exists('Medinext\Tests\TestRunner')) {
    class TestRunner
    {
        /** @var TestSuite[] */
        public array $suites = [];

        public function addSuite(TestSuite $suite): void
        {
            $this->suites[] = $suite;
        }

        public function runAll(bool $verbose = true, ?string $filter = null): int
        {
            $totalTests = 0;
            $totalPassed = 0;
            $totalFailed = 0;
            $totalDuration = 0.0;
            $suiteSummaries = [];

            $runnerStart = microtime(true);

            foreach ($this->suites as $suite) {
                $res = $suite->run($verbose, $filter);
                $totalTests += $res['total'];
                $totalPassed += $res['passed'];
                $totalFailed += $res['failed'];
                $totalDuration += $res['duration_ms'];
                $suiteSummaries[] = $res;
            }

            $grandTotalDuration = round((microtime(true) - $runnerStart) * 1000, 2);
            $totalAssertions = Assert::getAssertionCount();

            echo "\n" . str_repeat('=', 80) . "\n";
            echo "                    " . CliColor::bold("MASTER E2E TEST RUNNER SUMMARY") . "\n";
            echo str_repeat('=', 80) . "\n";
            printf(" %-48s | %-6s | %-6s | %-6s | %-10s\n", "Suite Name", "Tests", "Pass", "Fail", "Time (ms)");
            echo str_repeat('-', 80) . "\n";

            foreach ($suiteSummaries as $s) {
                $statusColor = $s['failed'] === 0 ? CliColor::green((string)$s['passed']) : CliColor::red((string)$s['passed']);
                $failColor = $s['failed'] > 0 ? CliColor::red((string)$s['failed']) : (string)$s['failed'];
                printf(" %-48s | %-6d | %-6s | %-6s | %-10s\n", substr($s['name'], 0, 48), $s['total'], $statusColor, $failColor, $s['duration_ms']);
            }

            echo str_repeat('=', 80) . "\n";
            $overallResult = $totalFailed === 0 ? CliColor::green("ALL TESTS PASSED (100% SUCCESS)") : CliColor::red("FAILED ({$totalFailed} FAILING TESTS)");
            echo " Status:           " . $overallResult . "\n";
            echo " Grand Total Tests: " . CliColor::bold((string)$totalTests) . "\n";
            echo " Total Passed:      " . CliColor::green((string)$totalPassed) . "\n";
            echo " Total Failed:      " . ($totalFailed > 0 ? CliColor::red((string)$totalFailed) : (string)$totalFailed) . "\n";
            echo " Total Assertions:  " . CliColor::cyan((string)$totalAssertions) . "\n";
            echo " Total Time:        {$grandTotalDuration} ms\n";
            echo str_repeat('=', 80) . "\n\n";

            return $totalFailed === 0 ? 0 : 1;
        }
    }
}

/**
 * Resolve project root directory
 */
if (!function_exists('Medinext\Tests\getProjectRoot')) {
    function getProjectRoot(): string
    {
        return dirname(__DIR__);
    }
}

if (!function_exists('Medinext\Tests\renderPageScript')) {
    /**
     * Programmatically execute a page script with mocked server & request environment
     */
    function renderPageScript(string $relativeFile, array $queryParams = [], array $serverOverrides = []): array
    {
    $projectRoot = getProjectRoot();
    $scriptPath = $projectRoot . '/' . ltrim($relativeFile, '/');

    if (!file_exists($scriptPath)) {
        return [
            'statusCode' => 404,
            'html' => '',
            'error' => "File not found: {$scriptPath}"
        ];
    }

    $queryString = http_build_query($queryParams);
    $cleanPath = '/' . preg_replace('/\.php$/i', '/', ltrim($relativeFile, '/'));
    if ($cleanPath === '/index/') $cleanPath = '/';
    $requestUri = $cleanPath . ($queryString ? '?' . $queryString : '');

    $phpBinary = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
    $sentinel = '__E2E_PAGE_DELIM_' . bin2hex(random_bytes(6)) . '__';

    $phpCode = '<?php
        $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
        $_SERVER["HTTPS"] = "on";
        $_SERVER["SERVER_PORT"] = "443";
        $_SERVER["REQUEST_URI"] = ' . var_export($requestUri, true) . ';
        $_SERVER["SCRIPT_NAME"] = ' . var_export($cleanPath, true) . ';
        $_SERVER["PHP_SELF"] = ' . var_export($cleanPath, true) . ';
        $_SERVER["QUERY_STRING"] = ' . var_export($queryString, true) . ';
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_GET = ' . var_export($queryParams, true) . ';
        $_POST = [];
        $_COOKIE = [];

        ' . (!empty($serverOverrides) ? 'foreach (' . var_export($serverOverrides, true) . ' as $k => $v) { $_SERVER[$k] = $v; }' : '') . '

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $sentinel = ' . var_export($sentinel, true) . ';

        register_shutdown_function(function() use ($sentinel) {
            $html = "";
            while (ob_get_level() > 0) {
                $html = ob_get_clean() . $html;
            }
            $status = http_response_code() ?: 200;
            $responseHeaders = headers_list();

            echo $sentinel . json_encode([
                "statusCode" => $status,
                "html_b64" => base64_encode((string)$html),
                "headers" => $responseHeaders
            ], JSON_UNESCAPED_UNICODE) . $sentinel;
        });

        ob_start();
        try {
            chdir(' . var_export($projectRoot, true) . ');
            include ' . var_export($scriptPath, true) . ';
        } catch (\Throwable $e) {
            http_response_code(500);
        }
    ';

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    $process = @proc_open('"' . $phpBinary . '" -d display_errors=0 -d error_reporting=0', $descriptors, $pipes, $projectRoot);
    if (is_resource($process)) {
        fwrite($pipes[0], $phpCode);
        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if (preg_match('/' . preg_quote($sentinel, '/') . '(.*?)' . preg_quote($sentinel, '/') . '/s', $stdout, $matches)) {
            $decoded = json_decode($matches[1], true);
            if (is_array($decoded) && isset($decoded['html_b64'])) {
                return [
                    'statusCode' => (int)($decoded['statusCode'] ?? 200),
                    'html' => base64_decode($decoded['html_b64']),
                    'headers' => $decoded['headers'] ?? [],
                    'stderr' => $stderr,
                    'exitCode' => $exitCode
                ];
            }
        }

        $decoded = json_decode($stdout, true);
        if (is_array($decoded) && isset($decoded['html_b64'])) {
            return [
                'statusCode' => (int)($decoded['statusCode'] ?? 200),
                'html' => base64_decode($decoded['html_b64']),
                'headers' => $decoded['headers'] ?? [],
                'stderr' => $stderr,
                'exitCode' => $exitCode
            ];
        }

        return [
            'statusCode' => $exitCode === 0 ? 200 : 500,
            'html' => $stdout,
            'headers' => [],
            'stderr' => $stderr,
            'exitCode' => $exitCode
        ];
    }

    // Direct in-process fallback if proc_open is restricted
    $_SERVER['HTTP_HOST'] = 'medinextsolutions.com';
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
    $_SERVER['REQUEST_URI'] = $requestUri;
    $_SERVER['SCRIPT_NAME'] = $cleanPath;
    $_SERVER['PHP_SELF'] = $cleanPath;
    $_SERVER['QUERY_STRING'] = $queryString;
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET = $queryParams;
    $_POST = [];
    $_COOKIE = [];
    foreach ($serverOverrides as $k => $v) {
        $_SERVER[$k] = $v;
    }

    ob_start();
    try {
        include $scriptPath;
        $html = ob_get_clean();
        $status = http_response_code() ?: 200;
    } catch (\Throwable $e) {
        $html = ob_get_clean();
        $status = 500;
    }

    return [
        'statusCode' => $status,
        'html' => (string)$html,
        'headers' => headers_list(),
        'stderr' => '',
        'exitCode' => $status === 200 ? 0 : 1
    ];
}
}

if (!function_exists('Medinext\Tests\postBackendEndpoint')) {
    /**
     * Programmatically execute backend POST endpoint with given payload & headers
     */
    function postBackendEndpoint(string $endpointFile, array $postData = [], array $headers = [], array $sessionData = []): array
    {
        $projectRoot = getProjectRoot();
        $scriptPath = $projectRoot . '/' . ltrim($endpointFile, '/');

        if (!file_exists($scriptPath)) {
            return [
                'statusCode' => 404,
                'body' => '',
                'headers' => [],
                'json' => null,
                'session' => [],
                'error' => "Endpoint not found: {$scriptPath}"
            ];
        }

        $headerCode = '';
        foreach ($headers as $hKey => $hVal) {
            $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $hKey));
            $headerCode .= '$_SERVER[' . var_export($serverKey, true) . '] = ' . var_export($hVal, true) . '; ';
        }

        $phpBinary = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
        $sentinel = '__E2E_BACKEND_DELIM_' . bin2hex(random_bytes(6)) . '__';

        $phpCode = '<?php
            $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
            $_SERVER["HTTPS"] = "on";
            $_SERVER["SERVER_PORT"] = "443";
            $_SERVER["REQUEST_URI"] = "/api/submit-audit-request.php";
            $_SERVER["SCRIPT_NAME"] = "/api/submit-audit-request.php";
            $_SERVER["PHP_SELF"] = "/api/submit-audit-request.php";
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST = ' . var_export($postData, true) . ';
            $_GET = [];
            ' . $headerCode . '

            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
            $_SESSION = ' . var_export($sessionData, true) . ';

            $sentinel = ' . var_export($sentinel, true) . ';

            register_shutdown_function(function() use ($sentinel) {
                $body = "";
                while (ob_get_level() > 0) {
                    $body = ob_get_clean() . $body;
                }
                $status = http_response_code() ?: 200;
                $responseHeaders = headers_list();
                $session = isset($_SESSION) ? $_SESSION : [];

                echo $sentinel . json_encode([
                    "statusCode" => $status,
                    "body_b64" => base64_encode((string)$body),
                    "headers" => $responseHeaders,
                    "session" => $session
                ], JSON_UNESCAPED_UNICODE) . $sentinel;
            });

            ob_start();
            try {
                chdir(' . var_export($projectRoot, true) . ');
                include ' . var_export($scriptPath, true) . ';
            } catch (\Throwable $e) {
                http_response_code(500);
            }
        ';

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $process = @proc_open('"' . $phpBinary . '" -d display_errors=0 -d error_reporting=0', $descriptors, $pipes, $projectRoot);
        if (is_resource($process)) {
            fwrite($pipes[0], $phpCode);
            fclose($pipes[0]);

            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $exitCode = proc_close($process);

            if (preg_match('/' . preg_quote($sentinel, '/') . '(.*?)' . preg_quote($sentinel, '/') . '/s', $stdout, $matches)) {
                $decoded = json_decode($matches[1], true);
                if (is_array($decoded) && isset($decoded['body_b64'])) {
                    $body = base64_decode($decoded['body_b64']);
                    $parsedJson = json_decode($body, true);
                    return [
                        'statusCode' => (int)($decoded['statusCode'] ?? 200),
                        'body' => $body,
                        'json' => $parsedJson,
                        'headers' => $decoded['headers'] ?? [],
                        'session' => $decoded['session'] ?? [],
                        'stderr' => $stderr,
                        'exitCode' => $exitCode
                    ];
                }
            }

            $decoded = json_decode($stdout, true);
            if (is_array($decoded) && isset($decoded['body_b64'])) {
                $body = base64_decode($decoded['body_b64']);
                $parsedJson = json_decode($body, true);
                return [
                    'statusCode' => (int)($decoded['statusCode'] ?? 200),
                    'body' => $body,
                    'json' => $parsedJson,
                    'headers' => $decoded['headers'] ?? [],
                    'session' => $decoded['session'] ?? [],
                    'stderr' => $stderr,
                    'exitCode' => $exitCode
                ];
            }

            $parsedJson = json_decode($stdout, true);
            return [
                'statusCode' => $exitCode === 0 ? 200 : 500,
                'body' => $stdout,
                'json' => $parsedJson,
                'headers' => [],
                'session' => [],
                'stderr' => $stderr,
                'exitCode' => $exitCode
            ];
        }

        // Direct in-process fallback
        $_SERVER['HTTP_HOST'] = 'medinextsolutions.com';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $postData;
        $_GET = [];
        foreach ($headers as $hKey => $hVal) {
            $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $hKey));
            $_SERVER[$serverKey] = $hVal;
        }
        $_SESSION = $sessionData;

        ob_start();
        try {
            include $scriptPath;
            $body = ob_get_clean();
            $status = http_response_code() ?: 200;
        } catch (\Throwable $e) {
            $body = ob_get_clean();
            $status = 500;
        }

        return [
            'statusCode' => $status,
            'body' => (string)$body,
            'json' => json_decode((string)$body, true),
            'headers' => headers_list(),
            'session' => $_SESSION,
            'stderr' => '',
            'exitCode' => 0
        ];
    }
}

if (!function_exists('Medinext\Tests\simulateHttpRequest')) {
    /**
     * Universal HTTP Request Simulator
     */
    function simulateHttpRequest(string $method, string $path, array $data = [], array $headers = [], array $session = []): array
    {
        if (strtoupper($method) === 'POST') {
            return postBackendEndpoint($path, $data, $headers, $session);
        }
        return renderPageScript($path, $data, $headers);
    }
}
