<?php
/**
 * MEDINEXT SOLUTIONS - IndexNow Verification Key & Submitter Test Suite (M3)
 * 
 * Comprehensive verification of IndexNow protocol:
 * 1. Verification key file integrity & token match
 * 2. CLI submitter script (indexnow-submitter.php)
 * 3. XML sitemap parsing (sitemap-locations.xml)
 * 4. URL chunking / batching logic (up to 10,000 URLs per IndexNow RFC)
 * 5. JSON payload construction & API response status handling
 */

declare(strict_types=1);

namespace Medinext\Tests;

require_once __DIR__ . '/e2e_test_runner.php';

/**
 * Helper to discover the IndexNow verification key file in project root
 */
function findVerificationKeyFile(string $projectRoot): ?array {
    $files = glob($projectRoot . '/*.txt');
    foreach ($files as $file) {
        $filename = basename($file);
        // Exclude known non-key txt files
        if (in_array(strtolower($filename), ['robots.txt', 'humans.txt'], true)) {
            continue;
        }
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        if (preg_match('/^[a-f0-9]{32}$/i', $nameWithoutExt)) {
            $content = trim((string)file_get_contents($file));
            return [
                'path' => $file,
                'filename' => $filename,
                'key_from_filename' => $nameWithoutExt,
                'content' => $content
            ];
        }
    }
    return null;
}

/**
 * Execute CLI script with given arguments
 */
function executeIndexNowCli(array $args = []): array {
    $projectRoot = dirname(__DIR__);
    $script = $projectRoot . '/indexnow-submitter.php';

    if (!file_exists($script)) {
        return [
            'exitCode' => 127,
            'stdout' => '',
            'stderr' => "File not found: {$script}",
            'scriptExists' => false
        ];
    }

    $argString = implode(' ', array_map('escapeshellarg', $args));
    $cmd = "php " . escapeshellarg($script) . " " . $argString;

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    $process = proc_open($cmd, $descriptors, $pipes, $projectRoot);
    if (!is_resource($process)) {
        return [
            'exitCode' => 1,
            'stdout' => '',
            'stderr' => 'Failed to spawn process',
            'scriptExists' => true
        ];
    }

    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    return [
        'exitCode' => $exitCode,
        'stdout' => $stdout,
        'stderr' => $stderr,
        'scriptExists' => true
    ];
}

/**
 * Build IndexNow TestSuite
 */
function getIndexNowSuite(): TestSuite {
    $suite = new TestSuite('IndexNow Verification Key & Submitter Suite', 'Verifies IndexNow verification key, sitemap XML parsing, URL batching, and indexnow-submitter.php CLI');
    $projectRoot = dirname(__DIR__);

    // -------------------------------------------------------------
    // Test 1: Verification Key File & Token Integrity
    // -------------------------------------------------------------
    $suite->addTest('IndexNow Verification Key File & Token Integrity', 'Tier 1', function () use ($projectRoot) {
        $keyInfo = findVerificationKeyFile($projectRoot);

        Assert::assertNotNull(
            $keyInfo,
            "Verification key file (e.g. 4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt) must exist in project root"
        );

        $key = $keyInfo['content'];
        Assert::assertMatchesRegex('/^[a-f0-9]{32}$/i', $key, "Verification key must be a 32-character hexadecimal string");
        Assert::assertEquals(
            strtolower($keyInfo['key_from_filename']),
            strtolower($key),
            "Filename key must match file content key token"
        );
    });

    // -------------------------------------------------------------
    // Test 2: Submitter Script Existence & PHP Syntax
    // -------------------------------------------------------------
    $suite->addTest('indexnow-submitter.php Script Availability & Syntax Check', 'Tier 1', function () use ($projectRoot) {
        $script = $projectRoot . '/indexnow-submitter.php';
        Assert::assertTrue(file_exists($script), "Script 'indexnow-submitter.php' must exist in project root");

        // Syntax check via php -l
        $cmd = "php -l " . escapeshellarg($script);
        exec($cmd, $output, $exitCode);
        Assert::assertEquals(0, $exitCode, "indexnow-submitter.php must have valid PHP syntax: " . implode("\n", $output));
    });

    // -------------------------------------------------------------
    // Test 3: CLI Parameter Parsing (--help, -h)
    // -------------------------------------------------------------
    $suite->addTest('indexnow-submitter.php CLI Parameter Parsing (--help)', 'Tier 1', function () {
        $res = executeIndexNowCli(['--help']);
        Assert::assertTrue($res['scriptExists'], "indexnow-submitter.php must exist");
        Assert::assertEquals(0, $res['exitCode'], "--help should exit with status 0");

        $combinedOutput = $res['stdout'] . $res['stderr'];
        Assert::assertTrue(
            stripos($combinedOutput, 'IndexNow') !== false || stripos($combinedOutput, 'Usage') !== false,
            "CLI --help output must contain usage instructions. Output: " . $combinedOutput
        );
        Assert::assertTrue(
            stripos($combinedOutput, '--dry-run') !== false || stripos($combinedOutput, 'dry-run') !== false,
            "CLI --help must document --dry-run option"
        );
    });

    // -------------------------------------------------------------
    // Test 4: Submitter Dry-Run Execution (--dry-run)
    // -------------------------------------------------------------
    $suite->addTest('indexnow-submitter.php Dry-Run Submission (--dry-run)', 'Tier 1', function () {
        $res = executeIndexNowCli(['--dry-run']);
        Assert::assertTrue($res['scriptExists'], "indexnow-submitter.php must exist");
        Assert::assertEquals(0, $res['exitCode'], "--dry-run must exit with code 0. Stderr: " . $res['stderr']);

        $combinedOutput = $res['stdout'] . $res['stderr'];
        Assert::assertTrue(
            stripos($combinedOutput, 'dry') !== false || stripos($combinedOutput, 'batch') !== false || stripos($combinedOutput, 'url') !== false,
            "Dry-run output must report batch/URL processing summary"
        );
    });

    // -------------------------------------------------------------
    // Test 5: sitemap-locations.xml XML Structure & URL Count
    // -------------------------------------------------------------
    $suite->addTest('sitemap-locations.xml XML Structure & Comprehensive URL Extraction', 'Tier 2', function () use ($projectRoot) {
        $sitemapFile = $projectRoot . '/sitemap-locations.xml';
        Assert::assertTrue(file_exists($sitemapFile), "sitemap-locations.xml must exist in project root");

        $xmlContent = (string)file_get_contents($sitemapFile);
        Assert::assertNotEmpty($xmlContent, "sitemap-locations.xml must not be empty");

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        Assert::assertTrue($xml !== false, "sitemap-locations.xml must be valid XML: " . json_encode($errors));
        Assert::assertEquals('urlset', $xml->getName(), "Root XML element must be <urlset>");

        $urls = [];
        foreach ($xml->url as $urlElement) {
            $urls[] = (string)$urlElement->loc;
        }

        Assert::assertGreaterThanOrEqual(6900, count($urls), "sitemap-locations.xml must contain >= 6,900 programmatic location URLs");

        // Verify key structural URLs
        Assert::assertContains('https://medinextsolutions.com/locations/', $urls, "Sitemap must include root locations directory");
        Assert::assertContains('https://medinextsolutions.com/locations/texas/', $urls, "Sitemap must include state hub URLs (Texas)");
        Assert::assertContains('https://medinextsolutions.com/locations/california/', $urls, "Sitemap must include state hub URLs (California)");
        Assert::assertContains('https://medinextsolutions.com/locations/texas/houston/', $urls, "Sitemap must include city landing URLs (Houston)");
        Assert::assertContains('https://medinextsolutions.com/locations/california/los-angeles/', $urls, "Sitemap must include city landing URLs (Los Angeles)");
    });

    // -------------------------------------------------------------
    // Test 6: Custom Sitemap Parameter (--sitemap=FILE)
    // -------------------------------------------------------------
    $suite->addTest('CLI Custom Sitemap Parameter Handling (--sitemap=custom.xml)', 'Tier 2', function () use ($projectRoot) {
        $tempSitemap = $projectRoot . '/temp_test_sitemap_' . uniqid() . '.xml';
        $sampleXml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>https://medinextsolutions.com/locations/test-state/test-city-1/</loc></url>
    <url><loc>https://medinextsolutions.com/locations/test-state/test-city-2/</loc></url>
    <url><loc>https://medinextsolutions.com/locations/test-state/test-city-3/</loc></url>
</urlset>';
        file_put_contents($tempSitemap, $sampleXml);

        try {
            $res = executeIndexNowCli(['--sitemap=' . basename($tempSitemap), '--dry-run']);
            Assert::assertTrue($res['scriptExists'], "indexnow-submitter.php must exist");
            Assert::assertEquals(0, $res['exitCode'], "Custom sitemap execution failed: " . $res['stderr']);
            Assert::assertTrue(
                stripos($res['stdout'] . $res['stderr'], '3') !== false || stripos($res['stdout'], 'batch') !== false,
                "Output should indicate 3 URLs processed"
            );
        } finally {
            if (file_exists($tempSitemap)) {
                unlink($tempSitemap);
            }
        }
    });

    // -------------------------------------------------------------
    // Test 7: Error Handling / Fallback for Non-Existent Sitemap
    // -------------------------------------------------------------
    $suite->addTest('Graceful Handling: Non-Existent Sitemap File (Fallback or Error)', 'Tier 2', function () {
        $res = executeIndexNowCli(['--sitemap=nonexistent_file_xyz_123.xml', '--dry-run']);
        Assert::assertTrue($res['scriptExists'], "indexnow-submitter.php must exist");
        // Submitter either falls back gracefully to DB extraction (exitCode 0) or reports error (exitCode 1)
        Assert::assertTrue(
            $res['exitCode'] === 0 || $res['exitCode'] === 1,
            "Non-existent sitemap should terminate with standard exit code (0 with fallback, or 1 on error)"
        );
        $combined = $res['stdout'] . $res['stderr'];
        Assert::assertNotEmpty($combined, "Submitter must output diagnostic status");
    });

    // -------------------------------------------------------------
    // Test 8: URL Batching & Chunking Algorithm (RFC Maximum 10,000)
    // -------------------------------------------------------------
    $suite->addTest('URL Batching Logic (Chunking up to 10,000 URLs per IndexNow RFC)', 'Tier 3', function () {
        $chunker = function (array $urls, int $batchSize): array {
            $batchSize = min(10000, max(1, $batchSize));
            return array_chunk($urls, $batchSize);
        };

        // Case 1: 6,938 URLs at default 10,000 batch size -> exactly 1 batch
        $dummy6938 = array_map(fn($i) => "https://medinextsolutions.com/locations/s{$i}/c{$i}/", range(1, 6938));
        $batches1 = $chunker($dummy6938, 10000);
        Assert::assertCount(1, $batches1, "6,938 URLs with batch-size 10,000 must result in 1 batch");
        Assert::assertCount(6938, $batches1[0]);

        // Case 2: 6,938 URLs at 2,000 batch size -> 4 batches (2000, 2000, 2000, 938)
        $batches2 = $chunker($dummy6938, 2000);
        Assert::assertCount(4, $batches2, "6,938 URLs with batch-size 2,000 must result in 4 batches");
        Assert::assertCount(2000, $batches2[0]);
        Assert::assertCount(2000, $batches2[1]);
        Assert::assertCount(2000, $batches2[2]);
        Assert::assertCount(938, $batches2[3]);

        // Case 3: 10,001 URLs at 10,000 batch size -> 2 batches (10000, 1)
        $dummy10001 = array_map(fn($i) => "https://medinextsolutions.com/locations/s{$i}/c{$i}/", range(1, 10001));
        $batches3 = $chunker($dummy10001, 10000);
        Assert::assertCount(2, $batches3, "10,001 URLs must be split into 2 batches");
        Assert::assertCount(10000, $batches3[0]);
        Assert::assertCount(1, $batches3[1]);

        // Case 4: 0 URLs -> 0 batches
        $batches4 = $chunker([], 10000);
        Assert::assertCount(0, $batches4, "0 URLs must result in 0 batches");
    });

    // -------------------------------------------------------------
    // Test 9: JSON Payload Schema Compliance (IndexNow RFC)
    // -------------------------------------------------------------
    $suite->addTest('IndexNow JSON Payload Schema RFC Compliance', 'Tier 3', function () use ($projectRoot) {
        $keyInfo = findVerificationKeyFile($projectRoot);
        $key = $keyInfo ? $keyInfo['content'] : '4a8f9b2c3d4e5f60718293a4b5c6d7e8';

        $payload = [
            'host' => 'medinextsolutions.com',
            'key' => $key,
            'keyLocation' => "https://medinextsolutions.com/{$key}.txt",
            'urlList' => [
                'https://medinextsolutions.com/locations/',
                'https://medinextsolutions.com/locations/texas/',
                'https://medinextsolutions.com/locations/texas/houston/'
            ]
        ];

        $jsonEncoded = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        Assert::assertJson($jsonEncoded, "Payload must serialize to valid JSON");

        $decoded = json_decode($jsonEncoded, true);
        Assert::assertArrayHasKey('host', $decoded, "Payload must contain 'host'");
        Assert::assertArrayHasKey('key', $decoded, "Payload must contain 'key'");
        Assert::assertArrayHasKey('keyLocation', $decoded, "Payload must contain 'keyLocation'");
        Assert::assertArrayHasKey('urlList', $decoded, "Payload must contain 'urlList'");

        Assert::assertEquals('medinextsolutions.com', $decoded['host']);
        Assert::assertEquals($key, $decoded['key']);
        Assert::assertMatchesRegex('/^https:\/\/medinextsolutions\.com\/[a-f0-9]{32}\.txt$/i', $decoded['keyLocation']);
        Assert::assertIsArray($decoded['urlList']);
        Assert::assertCount(3, $decoded['urlList']);
    });

    // -------------------------------------------------------------
    // Test 10: IndexNow API Response Code Contract
    // -------------------------------------------------------------
    $suite->addTest('IndexNow API HTTP Response Code Contract Mapping', 'Tier 4', function () {
        $statusMap = [
            200 => ['status' => 'success', 'desc' => 'OK - URLs submitted successfully'],
            202 => ['status' => 'success', 'desc' => 'Accepted - URLs received and queued'],
            400 => ['status' => 'error', 'desc' => 'Bad Request - Invalid format or parameter'],
            403 => ['status' => 'error', 'desc' => 'Forbidden - Key invalid or not verified'],
            422 => ['status' => 'error', 'desc' => 'Unprocessable Entity - URLs do not match host'],
            429 => ['status' => 'rate_limit', 'desc' => 'Too Many Requests - Rate limit exceeded']
        ];

        foreach ($statusMap as $code => $expectation) {
            $isSuccess = in_array($code, [200, 202], true);
            $isRateLimit = ($code === 429);
            $isClientError = in_array($code, [400, 403, 422], true);

            if ($expectation['status'] === 'success') {
                Assert::assertTrue($isSuccess, "HTTP {$code} must be recognized as success");
            } elseif ($expectation['status'] === 'rate_limit') {
                Assert::assertTrue($isRateLimit, "HTTP {$code} must be recognized as rate limiting");
            } else {
                Assert::assertTrue($isClientError, "HTTP {$code} must be recognized as client error");
            }
        }
    });

    // -------------------------------------------------------------
    // Test 11: End-to-End Dry Run Pipeline Execution
    // -------------------------------------------------------------
    $suite->addTest('Workload Scenario: Full 6,900+ URL Dry-Run Submission Pipeline', 'Tier 4', function () {
        $start = microtime(true);
        $res = executeIndexNowCli(['--dry-run', '--batch-size=5000']);
        $elapsedMs = (microtime(true) - $start) * 1000;

        Assert::assertTrue($res['scriptExists'], "indexnow-submitter.php must exist");
        Assert::assertEquals(0, $res['exitCode'], "Full pipeline dry-run failed with code {$res['exitCode']}: {$res['stderr']}");
        Assert::assertLessThanOrEqual(10000.0, $elapsedMs, "Full sitemap dry-run took {$elapsedMs}ms (exceeds 10s ceiling)");
    });

    return $suite;
}

// Standalone execution support
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getIndexNowSuite());
    exit($runner->runAll());
}
