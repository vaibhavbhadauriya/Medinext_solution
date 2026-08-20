<?php
/**
 * MEDINEXT SOLUTIONS - Master E2E Test Runner & Framework
 * 
 * Orchestrates automated verification across all 4 Tiers:
 * Tier 1: Core functionality per feature (MAC Resolution, UI Rendering, Schema, IndexNow)
 * Tier 2: Boundary value analysis & error handling
 * Tier 3: Combinatorial & contract schema verification
 * Tier 4: Real-world end-to-end workload scenarios
 */

declare(strict_types=1);

namespace Medinext\Tests;

class AssertionException extends \Exception {}

class Assert {
    public static int $assertionsCount = 0;

    public static function reset(): void {
        self::$assertionsCount = 0;
    }

    public static function assertTrue(bool $condition, string $message = 'Failed asserting that condition is true'): void {
        self::$assertionsCount++;
        if (!$condition) {
            throw new AssertionException($message);
        }
    }

    public static function assertFalse(bool $condition, string $message = 'Failed asserting that condition is false'): void {
        self::$assertionsCount++;
        if ($condition) {
            throw new AssertionException($message);
        }
    }

    public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void {
        self::$assertionsCount++;
        if ($expected != $actual) {
            $msg = $message ?: sprintf("Failed asserting that %s matches expected %s", var_export($actual, true), var_export($expected, true));
            throw new AssertionException($msg);
        }
    }

    public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void {
        self::$assertionsCount++;
        if ($expected !== $actual) {
            $msg = $message ?: sprintf("Failed asserting that %s is strictly identical to %s", var_export($actual, true), var_export($expected, true));
            throw new AssertionException($msg);
        }
    }

    public static function assertNotNull(mixed $actual, string $message = 'Failed asserting that value is not null'): void {
        self::$assertionsCount++;
        if ($actual === null) {
            throw new AssertionException($message);
        }
    }

    public static function assertNull(mixed $actual, string $message = ''): void {
        self::$assertionsCount++;
        if ($actual !== null) {
            throw new AssertionException($message ?: sprintf("Expected null, got %s", var_export($actual, true)));
        }
    }

    public static function assertIsArray(mixed $actual, string $message = 'Failed asserting that value is an array'): void {
        self::$assertionsCount++;
        if (!is_array($actual)) {
            throw new AssertionException($message ?: sprintf("Expected array, got %s", gettype($actual)));
        }
    }

    public static function assertIsString(mixed $actual, string $message = 'Failed asserting that value is a string'): void {
        self::$assertionsCount++;
        if (!is_string($actual)) {
            throw new AssertionException($message ?: sprintf("Expected string, got %s", gettype($actual)));
        }
    }

    public static function assertNotEmpty(mixed $actual, string $message = 'Failed asserting that value is not empty'): void {
        self::$assertionsCount++;
        if (empty($actual)) {
            throw new AssertionException($message);
        }
    }

    public static function assertArrayHasKey(string|int $key, array $array, string $message = ''): void {
        self::$assertionsCount++;
        if (!array_key_exists($key, $array)) {
            throw new AssertionException($message ?: sprintf("Failed asserting that array contains key '%s'", (string)$key));
        }
    }

    public static function assertContains(mixed $needle, array $haystack, string $message = ''): void {
        self::$assertionsCount++;
        if (!in_array($needle, $haystack, true)) {
            throw new AssertionException($message ?: sprintf("Failed asserting that array contains %s", var_export($needle, true)));
        }
    }

    public static function assertStringContains(string $needle, string $haystack, string $message = ''): void {
        self::$assertionsCount++;
        if (strpos($haystack, $needle) === false) {
            $preview = mb_substr($haystack, 0, 120);
            throw new AssertionException($message ?: sprintf("Failed asserting that text contains '%s'. Preview: '%s...'", $needle, $preview));
        }
    }

    public static function assertStringContainsIgnoreCase(string $needle, string $haystack, string $message = ''): void {
        self::$assertionsCount++;
        if (stripos($haystack, $needle) === false) {
            $preview = mb_substr($haystack, 0, 120);
            throw new AssertionException($message ?: sprintf("Failed asserting that text contains '%s' (case-insensitive). Preview: '%s...'", $needle, $preview));
        }
    }

    public static function assertMatchesRegex(string $pattern, string $string, string $message = ''): void {
        self::$assertionsCount++;
        if (!preg_match($pattern, $string)) {
            throw new AssertionException($message ?: sprintf("Failed asserting that '%s' matches pattern '%s'", $string, $pattern));
        }
    }

    public static function assertCount(int $expectedCount, \Countable|array $countable, string $message = ''): void {
        self::$assertionsCount++;
        $actualCount = count($countable);
        if ($actualCount !== $expectedCount) {
            throw new AssertionException($message ?: sprintf("Failed asserting that count %d matches expected %d", $actualCount, $expectedCount));
        }
    }

    public static function assertGreaterThanOrEqual(int|float $expected, int|float $actual, string $message = ''): void {
        self::$assertionsCount++;
        if ($actual < $expected) {
            throw new AssertionException($message ?: sprintf("Failed asserting that %s is >= %s", (string)$actual, (string)$expected));
        }
    }

    public static function assertLessThanOrEqual(int|float $expected, int|float $actual, string $message = ''): void {
        self::$assertionsCount++;
        if ($actual > $expected) {
            throw new AssertionException($message ?: sprintf("Failed asserting that %s is <= %s", (string)$actual, (string)$expected));
        }
    }

    public static function assertJson(string $jsonString, string $message = ''): void {
        self::$assertionsCount++;
        json_decode($jsonString);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AssertionException($message ?: sprintf("Failed asserting valid JSON: %s", json_last_error_msg()));
        }
    }

    public static function fail(string $message): void {
        self::$assertionsCount++;
        throw new AssertionException($message);
    }
}

class TestResult {
    public string $name;
    public string $tier;
    public bool $passed = false;
    public ?string $errorMessage = null;
    public ?string $file = null;
    public ?int $line = null;
    public float $durationMs = 0.0;
    public int $assertions = 0;
}

class TestSuite {
    public string $name;
    public string $description;
    /** @var array<array{name: string, tier: string, fn: callable}> */
    public array $tests = [];
    /** @var TestResult[] */
    public array $results = [];
    public float $totalDurationMs = 0.0;

    public function __construct(string $name, string $description = '') {
        $this->name = $name;
        $this->description = $description;
    }

    public function addTest(string $name, string $tier, callable $fn): void {
        $this->tests[] = [
            'name' => $name,
            'tier' => $tier,
            'fn' => $fn
        ];
    }

    public function run(): array {
        $this->results = [];
        $startTime = microtime(true);

        foreach ($this->tests as $test) {
            $result = new TestResult();
            $result->name = $test['name'];
            $result->tier = $test['tier'];
            $testStart = microtime(true);
            $assertionsBefore = Assert::$assertionsCount;

            try {
                ($test['fn'])();
                $result->passed = true;
            } catch (AssertionException $e) {
                $result->passed = false;
                $result->errorMessage = $e->getMessage();
                $result->file = $e->getFile();
                $result->line = $e->getLine();
            } catch (\Throwable $e) {
                $result->passed = false;
                $result->errorMessage = get_class($e) . ': ' . $e->getMessage();
                $result->file = $e->getFile();
                $result->line = $e->getLine();
            }

            $result->durationMs = (microtime(true) - $testStart) * 1000;
            $result->assertions = Assert::$assertionsCount - $assertionsBefore;
            $this->results[] = $result;
        }

        $this->totalDurationMs = (microtime(true) - $startTime) * 1000;
        return $this->results;
    }
}

class TestRunner {
    /** @var TestSuite[] */
    public array $suites = [];

    public function addSuite(TestSuite $suite): void {
        $this->suites[] = $suite;
    }

    public function runAll(): int {
        $totalTests = 0;
        $totalPassed = 0;
        $totalFailed = 0;
        $totalAssertions = 0;
        $runnerStart = microtime(true);

        $cReset  = "\033[0m";
        $cBold   = "\033[1m";
        $cGreen  = "\033[32m";
        $cRed    = "\033[31m";
        $cYellow = "\033[33m";
        $cCyan   = "\033[36m";
        $cGray   = "\033[90m";

        echo "\n" . $cBold . $cCyan . "================================================================================" . $cReset . "\n";
        echo $cBold . " MEDINEXT SOLUTIONS — E2E REGULATORY & COMPLIANCE TEST SUITE" . $cReset . "\n";
        echo " Architecture: Category-Partition, BVA, Pairwise Combinatorial & Workload\n";
        echo $cGray . " Target: PHP " . PHP_VERSION . " | " . date('Y-m-d H:i:s T') . $cReset . "\n";
        echo $cBold . $cCyan . "================================================================================" . $cReset . "\n\n";

        foreach ($this->suites as $suite) {
            echo $cBold . "Suite: " . $suite->name . $cReset . " (" . $suite->description . ")\n";
            echo str_repeat('-', 80) . "\n";

            $results = $suite->run();

            foreach ($results as $r) {
                $totalTests++;
                $totalAssertions += $r->assertions;
                $tierBadge = $cGray . "[" . $r->tier . "]" . $cReset;

                if ($r->passed) {
                    $totalPassed++;
                    $status = $cGreen . "✓ PASS" . $cReset;
                    printf("  %-10s %s %-50s %s(%d asserts, %.1f ms)%s\n", 
                        $status, $tierBadge, $r->name, $cGray, $r->assertions, $r->durationMs, $cReset
                    );
                } else {
                    $totalFailed++;
                    $status = $cRed . "✗ FAIL" . $cReset;
                    printf("  %-10s %s %-50s %s(%d asserts, %.1f ms)%s\n", 
                        $status, $tierBadge, $r->name, $cRed, $r->assertions, $r->durationMs, $cReset
                    );
                    echo "    " . $cRed . "Error: " . $r->errorMessage . $cReset . "\n";
                    if ($r->file && $r->line) {
                        echo "    " . $cGray . "At: " . $r->file . ":" . $r->line . $cReset . "\n";
                    }
                }
            }

            echo sprintf("\n  %sSuite Summary:%s %d tests, %.1f ms\n\n", 
                $cBold, $cReset, count($results), $suite->totalDurationMs
            );
        }

        $totalDuration = (microtime(true) - $runnerStart) * 1000;

        echo $cBold . $cCyan . "================================================================================" . $cReset . "\n";
        echo $cBold . " TEST EXECUTION SUMMARY" . $cReset . "\n";
        echo str_repeat('-', 80) . "\n";
        printf(" Suites Executed: %d\n", count($this->suites));
        printf(" Total Tests:     %d\n", $totalTests);
        printf(" Assertions:      %d\n", $totalAssertions);
        printf(" Passed:          %s%d%s\n", ($totalPassed > 0 ? $cGreen : ''), $totalPassed, $cReset);
        printf(" Failed:          %s%d%s\n", ($totalFailed > 0 ? $cRed : ''), $totalFailed, $cReset);
        printf(" Total Time:      %.2f ms\n", $totalDuration);
        echo $cBold . $cCyan . "================================================================================" . $cReset . "\n";

        if ($totalFailed > 0) {
            echo $cRed . $cBold . " OVERALL STATUS: FAILED (" . $totalFailed . " tests failed)" . $cReset . "\n\n";
            return 1;
        } else {
            echo $cGreen . $cBold . " OVERALL STATUS: ALL TESTS PASSED SUCCESSFULLY" . $cReset . "\n\n";
            return 0;
        }
    }
}

// Master CLI Execution Entrypoint
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();

    // Load and register all test suites
    require_once __DIR__ . '/test_mac_matrix.php';
    require_once __DIR__ . '/test_location_pages.php';
    require_once __DIR__ . '/test_indexnow.php';
    require_once __DIR__ . '/test_adversarial_indexnow.php';
    require_once __DIR__ . '/adversarial_mac_matrix_challenge.php';

    if (function_exists('Medinext\Tests\getMacMatrixSuite')) {
        $runner->addSuite(getMacMatrixSuite());
    }
    if (function_exists('Medinext\Tests\Adversarial\getAdversarialMacSuite')) {
        $runner->addSuite(\Medinext\Tests\Adversarial\getAdversarialMacSuite());
    }
    if (function_exists('Medinext\Tests\getLocationPagesSuite')) {
        $runner->addSuite(getLocationPagesSuite());
    }
    if (function_exists('Medinext\Tests\getIndexNowSuite')) {
        $runner->addSuite(getIndexNowSuite());
    }
    if (function_exists('Medinext\Tests\getAdversarialIndexNowSuite')) {
        $runner->addSuite(getAdversarialIndexNowSuite());
    }

    $exitCode = $runner->runAll();
    exit($exitCode);
}
