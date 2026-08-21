<?php
/**
 * MEDINEXT SOLUTIONS - Master E2E & Verification Test Suite Runner
 * 
 * Standalone Master Test Runner executing and aggregating all test tiers:
 *   - Tier 1: Feature Coverage Suite (F1 - F19, >= 95 tests)
 *   - Tier 2: Boundary & Corner Cases Suite (F1 - F19, >= 95 tests)
 *   - Tier 3: Cross-Feature Pairwise Interaction Suite (>= 19 tests)
 *   - Tier 4: Real-World Application Workloads Suite (>= 7 scenarios)
 *   - Tier 5: Adversarial Hardening Suite (>= 25 tests)
 * 
 * CLI Usage:
 *   php tests/e2e_test_runner.php
 *   php tests/e2e_test_runner.php --tier=1,2
 *   php tests/e2e_test_runner.php --tier=1 --verbose
 *   php tests/e2e_test_runner.php --filter=CSRF
 */

declare(strict_types=1);

namespace Medinext\Tests;

require_once __DIR__ . '/TestHelper.php';

// Parse command line arguments
$selectedTiers = [1, 2, 3, 4, 5];
$verbose = true;
$filter = null;

if (isset($argv) && is_array($argv)) {
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--tier=')) {
            $tierVal = substr($arg, 7);
            $selectedTiers = array_map('intval', explode(',', $tierVal));
        } elseif ($arg === '--quiet' || $arg === '-q') {
            $verbose = false;
        } elseif ($arg === '--verbose' || $arg === '-v') {
            $verbose = true;
        } elseif (str_starts_with($arg, '--filter=')) {
            $filter = substr($arg, 9);
        } elseif ($arg === '--help' || $arg === '-h') {
            echo "MEDINEXT SOLUTIONS E2E TEST RUNNER\n";
            echo "Usage: php tests/e2e_test_runner.php [options]\n\n";
            echo "Options:\n";
            echo "  --tier=1,2,3,4,5   Comma-separated list of test tiers to execute (default: all)\n";
            echo "  --filter=<string>  Filter tests by name or tier label substring\n";
            echo "  --quiet, -q        Suppress per-test pass output\n";
            echo "  --verbose, -v      Show verbose test execution output (default)\n";
            echo "  --help, -h         Show this help message\n";
            exit(0);
        }
    }
}

echo "\n" . str_repeat('=', 80) . "\n";
echo "       " . CliColor::bold("MEDINEXT SOLUTIONS - MASTER E2E & INTEGRATION TEST HARNESS") . "\n";
echo "       Tiers Selected: " . implode(', ', array_map(fn($t) => "Tier {$t}", $selectedTiers)) . "\n";
if ($filter) {
    echo "       Filter Active:  " . CliColor::yellow($filter) . "\n";
}
echo str_repeat('=', 80) . "\n";

$tierFileMap = [
    1 => ['file' => __DIR__ . '/tier1_feature_coverage_test.php', 'name' => 'Tier 1: Feature Coverage (F1-F19)'],
    2 => ['file' => __DIR__ . '/tier2_boundary_corner_test.php', 'name' => 'Tier 2: Boundary & Corner Cases (F1-F19)'],
    3 => ['file' => __DIR__ . '/tier3_cross_feature_test.php', 'name' => 'Tier 3: Cross-Feature Pairwise Interactions'],
    4 => ['file' => __DIR__ . '/tier4_real_world_test.php', 'name' => 'Tier 4: Real-World Workload Scenarios'],
    5 => ['file' => __DIR__ . '/tier5_adversarial_test.php', 'name' => 'Tier 5: Adversarial Hardening Suite']
];

$phpBinary = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
$masterStart = microtime(true);

$suiteSummaries = [];
$grandTotalTests = 0;
$grandTotalPassed = 0;
$grandTotalFailed = 0;

foreach ($selectedTiers as $tierNum) {
    if (!isset($tierFileMap[$tierNum])) {
        echo CliColor::yellow("Warning: Unknown test tier: Tier {$tierNum}\n");
        continue;
    }

    $tierInfo = $tierFileMap[$tierNum];
    $testFile = $tierInfo['file'];
    $tierName = $tierInfo['name'];

    if (!file_exists($testFile)) {
        echo CliColor::red("Error: Suite file for {$tierName} not found: {$testFile}\n");
        $suiteSummaries[] = [
            'name' => $tierName,
            'total' => 0,
            'passed' => 0,
            'failed' => 1,
            'duration_ms' => 0.0
        ];
        $grandTotalFailed++;
        continue;
    }

    $tierStart = microtime(true);

    // Execute isolated process for each test tier
    $cmd = '"' . $phpBinary . '" -d display_errors=1 -d error_reporting=E_ALL "' . $testFile . '"';

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    $process = proc_open($cmd, $descriptors, $pipes, dirname(__DIR__));
    $stdout = '';
    $stderr = '';

    if (is_resource($process)) {
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
    } else {
        $exitCode = 1;
        $stderr = "Failed to launch process for {$testFile}";
    }

    $tierDuration = round((microtime(true) - $tierStart) * 1000, 2);

    // Output live runner log
    if ($verbose) {
        echo $stdout;
        if (!empty($stderr)) {
            echo CliColor::yellow($stderr) . "\n";
        }
    }

    // Parse suite summary from output
    $passed = 0;
    $failed = 0;
    $total = 0;

    if (preg_match('/Passed:\s*(\d+)\s*\|\s*Failed:\s*(\d+)\s*\|\s*Total:\s*(\d+)/i', $stdout, $matches)) {
        $passed = (int)$matches[1];
        $failed = (int)$matches[2];
        $total = (int)$matches[3];
    } elseif (preg_match('/Total Tests:\s*(\d+)\s*\|\s*Passed:\s*(\d+)\s*\|\s*Failed:\s*(\d+)/i', $stdout, $matches)) {
        $total = (int)$matches[1];
        $passed = (int)$matches[2];
        $failed = (int)$matches[3];
    } else {
        if ($exitCode === 0) {
            $passCount = preg_match_all('/\bPASS\b/', $stdout);
            $passed = $passCount;
            $failed = 0;
            $total = $passCount;
        } else {
            $passed = 0;
            $failed = 1;
            $total = 1;
        }
    }

    $grandTotalTests += $total;
    $grandTotalPassed += $passed;
    $grandTotalFailed += $failed;

    $suiteSummaries[] = [
        'name' => $tierName,
        'total' => $total,
        'passed' => $passed,
        'failed' => $failed,
        'duration_ms' => $tierDuration
    ];
}

$grandTotalDuration = round((microtime(true) - $masterStart) * 1000, 2);

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
$overallResult = $grandTotalFailed === 0 ? CliColor::green("ALL TEST SUITES PASSED (100% SUCCESS)") : CliColor::red("FAILED ({$grandTotalFailed} FAILING TESTS)");
echo " Status:           " . $overallResult . "\n";
echo " Grand Total Tests: " . CliColor::bold((string)$grandTotalTests) . "\n";
echo " Total Passed:      " . CliColor::green((string)$grandTotalPassed) . "\n";
echo " Total Failed:      " . ($grandTotalFailed > 0 ? CliColor::red((string)$grandTotalFailed) : (string)$grandTotalFailed) . "\n";
echo " Total Duration:    {$grandTotalDuration} ms\n";
echo str_repeat('=', 80) . "\n\n";

exit($grandTotalFailed === 0 ? 0 : 1);
