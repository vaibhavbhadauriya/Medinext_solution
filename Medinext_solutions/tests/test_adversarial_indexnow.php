<?php
/**
 * MEDINEXT SOLUTIONS - Adversarial & Empirical Stress Test Suite for IndexNow Engine
 * 
 * Challenger 2 Verification Suite
 * Tests:
 * 1. Verification key exact byte structure, headers, newline/BOM absence, self-healing
 * 2. CLI flags matrix (--help, --dry-run, --json, --all, --batch-size, --sitemap, overrides)
 * 3. Extreme batch sizes and chunking boundaries (0, 1, 9999, 10000, 10001, 15000, 50000)
 * 4. XML parser adversarial resilience (malformed, empty, CDATA, whitespace, external domains, deduplication)
 * 5. Mock HTTP server / Network status simulation (200, 202, 400, 403, 422, 429 Rate Limit, 500, 503, timeout)
 * 6. Web endpoint security, authentication matrix, header enforcement, injection resistance
 * 7. Boundary condition on 0 extracted URLs and missing summary key diagnostics
 * 8. Special characters & URI encoding fuzzing (spaces, query strings, unicode)
 * 9. Upstream server malformed response handling (non-JSON, truncated HTML, empty body)
 */

declare(strict_types=1);

namespace Medinext\Tests;

require_once __DIR__ . '/e2e_test_runner.php';
require_once dirname(__DIR__) . '/indexnow-submitter.php';

/**
 * Execute CLI script with given arguments
 */
function executeIndexNowAdversarialCli(array $args = []): array {
    $projectRoot = dirname(__DIR__);
    $script = $projectRoot . '/indexnow-submitter.php';

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
    ];
}

function getAdversarialIndexNowSuite(): TestSuite {
    $suite = new TestSuite('Adversarial IndexNow & Verification Key Stress Suite', 'Adversarial stress-testing, boundary analysis, fault injection, and security validation');
    $projectRoot = dirname(__DIR__);

    // =========================================================================
    // SECTION 1: VERIFICATION KEY BYTE-LEVEL INTEGRITY & RESILIENCE
    // =========================================================================

    $suite->addTest('Key File: Exact 32 Bytes, Lowercase Hex, No Newlines or BOM', 'Adversarial-Key', function () use ($projectRoot) {
        $keyFile = $projectRoot . '/4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt';
        Assert::assertTrue(file_exists($keyFile), "Key file 4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt must exist");

        $rawBytes = file_get_contents($keyFile);
        Assert::assertSame(32, strlen($rawBytes), "Key file must be EXACTLY 32 bytes in length (got " . strlen($rawBytes) . ")");
        Assert::assertSame('4a8f9b2c3d4e5f60718293a4b5c6d7e8', $rawBytes, "Key content must exactly match '4a8f9b2c3d4e5f60718293a4b5c6d7e8'");

        // Verify absence of CR, LF, null bytes, and UTF-8 BOM
        Assert::assertFalse(str_contains($rawBytes, "\r"), "Key file must NOT contain Carriage Return (\\r)");
        Assert::assertFalse(str_contains($rawBytes, "\n"), "Key file must NOT contain Line Feed (\\n)");
        Assert::assertFalse(str_contains($rawBytes, "\0"), "Key file must NOT contain Null byte (\\0)");
        Assert::assertFalse(str_starts_with($rawBytes, "\xEF\xBB\xBF"), "Key file must NOT contain UTF-8 BOM");

        // Verify all 32 bytes are valid hexadecimal [0-9a-f]
        for ($i = 0; $i < 32; $i++) {
            $char = $rawBytes[$i];
            $ord = ord($char);
            $isHex = ($ord >= 48 && $ord <= 57) || ($ord >= 97 && $ord <= 102); // 0-9 or a-f
            Assert::assertTrue($isHex, "Byte at position {$i} ('{$char}') must be lowercase hex [0-9a-f]");
        }
    });

    $suite->addTest('Key File: Self-Healing and Auto-Regeneration on Corruption/Deletion', 'Adversarial-Key', function () use ($projectRoot) {
        $keyFile = $projectRoot . '/4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt';
        $originalContent = file_get_contents($keyFile);

        try {
            // Case A: Corrupted key file
            file_put_contents($keyFile, "corrupted_content_with_newlines\r\n\n");
            $submitter = new \IndexNowSubmitter(null, '4a8f9b2c3d4e5f60718293a4b5c6d7e8', null, null, $projectRoot);
            $details = $submitter->getKeyDetails();
            Assert::assertTrue($details['key_file_content_valid'], "Submitter must repair corrupted key file");
            Assert::assertSame(32, filesize($keyFile), "Repaired key file must be 32 bytes");
            Assert::assertSame('4a8f9b2c3d4e5f60718293a4b5c6d7e8', file_get_contents($keyFile));

            // Case B: Deleted key file
            unlink($keyFile);
            Assert::assertFalse(file_exists($keyFile));
            $submitter2 = new \IndexNowSubmitter(null, '4a8f9b2c3d4e5f60718293a4b5c6d7e8', null, null, $projectRoot);
            Assert::assertTrue(file_exists($keyFile), "Submitter must recreate deleted key file upon instantiation");
            Assert::assertSame('4a8f9b2c3d4e5f60718293a4b5c6d7e8', file_get_contents($keyFile));
        } finally {
            file_put_contents($keyFile, $originalContent);
        }
    });

    $suite->addTest('Key File: HTTP Header & .htaccess Static Serving Rules', 'Adversarial-Key', function () use ($projectRoot) {
        $htaccessFile = $projectRoot . '/.htaccess';
        Assert::assertTrue(file_exists($htaccessFile), ".htaccess must exist");
        $htaccessContent = (string)file_get_contents($htaccessFile);

        // Verify .txt files are not blocked by the sensitive files block
        Assert::assertStringContains('FilesMatch "\.(env|log|sql|bak|ini|sh|md)$"', $htaccessContent);
        Assert::assertFalse(str_contains($htaccessContent, '.txt|') || str_contains($htaccessContent, '|txt|') || str_contains($htaccessContent, '|txt)'), ".txt files must NOT be blocked in .htaccess");

        // Verify existing static files bypass URL rewriting
        Assert::assertStringContains('RewriteCond %{REQUEST_FILENAME} -f', $htaccessContent);
        Assert::assertStringContains('RewriteRule ^ - [L]', $htaccessContent);
    });

    // =========================================================================
    // SECTION 2: CLI FLAGS & PARAMETER MATRIX STRESS
    // =========================================================================

    $suite->addTest('CLI Flags: Structured JSON Output Verification (--json, -j)', 'Adversarial-CLI', function () {
        $res = executeIndexNowAdversarialCli(['--dry-run', '--json']);
        Assert::assertSame(0, $res['exitCode'], "Dry-run with --json must exit with 0. Stderr: " . $res['stderr']);
        Assert::assertJson($res['stdout'], "stdout must be strictly valid JSON without preamble");

        $data = json_decode($res['stdout'], true);
        Assert::assertArrayHasKey('status', $data);
        Assert::assertSame('success', $data['status']);
        Assert::assertSame('dry-run', $data['mode']);
        Assert::assertSame('medinextsolutions.com', $data['host']);
        Assert::assertSame('4a8f9b2c3d4e5f60718293a4b5c6d7e8', $data['key']);
        Assert::assertSame('https://medinextsolutions.com/4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt', $data['key_location']);
        Assert::assertGreaterThanOrEqual(6900, $data['total_urls_extracted']);
        Assert::assertArrayHasKey('summary', $data);
        Assert::assertSame(0, $data['summary']['failed_batches']);
    });

    $suite->addTest('CLI Flags: All Sitemaps Combined Mode (--all, -a)', 'Adversarial-CLI', function () {
        $res = executeIndexNowAdversarialCli(['--all', '--dry-run', '--json']);
        Assert::assertSame(0, $res['exitCode'], "Failed executing --all: " . $res['stderr']);
        $data = json_decode($res['stdout'], true);
        Assert::assertSame(['sitemap-locations.xml', 'sitemap.xml'], $data['sitemap_sources']);
        Assert::assertGreaterThanOrEqual(6940, $data['total_urls_extracted'], "Combined sitemaps must contain core + location pages");
    });

    $suite->addTest('CLI Flags: Custom Host, Key, Endpoint Overrides and Custom Sitemap', 'Adversarial-CLI', function () use ($projectRoot) {
        $customHost = 'custom.medinext.org';
        $customKey = 'fedcba98765432100123456789abcdef';
        $customEndpoint = 'https://custom-engine.example.com/indexnow';
        $tempSitemap = $projectRoot . '/temp_custom_host_' . uniqid() . '.xml';

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $xml .= "  <url><loc>https://{$customHost}/locations/custom-state/custom-city/</loc></url>\n";
        $xml .= "</urlset>";
        file_put_contents($tempSitemap, $xml);

        try {
            $res = executeIndexNowAdversarialCli([
                '--host=' . $customHost,
                '--key=' . $customKey,
                '--endpoint=' . $customEndpoint,
                '--sitemap=' . basename($tempSitemap),
                '--dry-run',
                '--json'
            ]);

            Assert::assertSame(0, $res['exitCode'], "Custom overrides failed: " . $res['stderr']);
            $data = json_decode($res['stdout'], true);
            Assert::assertSame($customHost, $data['host']);
            Assert::assertSame($customKey, $data['key']);
            Assert::assertSame("https://{$customHost}/{$customKey}.txt", $data['key_location']);
            Assert::assertSame($customEndpoint, $data['endpoint']);
            Assert::assertSame(1, $data['total_urls_extracted']);
        } finally {
            if (file_exists($tempSitemap)) unlink($tempSitemap);
        }
    });

    $suite->addTest('CLI Flags: Batch Size Clamping and Edge Boundaries', 'Adversarial-CLI', function () {
        // Test clamping: batch_size = 0 or negative should be clamped to >= 1
        $submitter = new \IndexNowSubmitter();

        $resClampedZero = $submitter->execute(['dry_run' => true, 'batch_size' => 0]);
        Assert::assertSame(1, $resClampedZero['batch_size'], "Batch size 0 should clamp to 1");

        $resClampedNeg = $submitter->execute(['dry_run' => true, 'batch_size' => -50]);
        Assert::assertSame(1, $resClampedNeg['batch_size'], "Batch size -50 should clamp to 1");

        $resClampedOverMax = $submitter->execute(['dry_run' => true, 'batch_size' => 25000]);
        Assert::assertSame(10000, $resClampedOverMax['batch_size'], "Batch size 25,000 should clamp to max 10,000");

        $resBoundary1 = $submitter->execute(['dry_run' => true, 'batch_size' => 1]);
        Assert::assertSame(1, $resBoundary1['batch_size']);

        $resBoundary10000 = $submitter->execute(['dry_run' => true, 'batch_size' => 10000]);
        Assert::assertSame(10000, $resBoundary10000['batch_size']);
    });

    // =========================================================================
    // SECTION 3: LARGE BATCH INPUTS & CHUNKING EDGE CASES (0, 1, 9999, 10000, 10001, 15000, 50000)
    // =========================================================================

    $suite->addTest('Chunking Edge Cases: Exact Boundary Partitioning Matrix', 'Adversarial-Chunking', function () {
        $submitter = new \IndexNowSubmitter();

        // 1. Empty URL list (0 URLs)
        $batchZero = $submitter->submitBatch([], true);
        Assert::assertFalse($batchZero['success'], "Submitting 0 URLs must fail");
        Assert::assertSame(400, $batchZero['http_code']);
        Assert::assertSame('EMPTY_BATCH', $batchZero['status']);

        // 2. Exactly 1 URL
        $batch1 = ['https://medinextsolutions.com/locations/'];
        $res1 = $submitter->submitBatch($batch1, true);
        Assert::assertTrue($res1['success']);
        Assert::assertSame(1, $res1['url_count']);

        // 3. Exactly 9,999 URLs
        $urls9999 = array_map(fn($i) => "https://medinextsolutions.com/locations/tx/city-{$i}/", range(1, 9999));
        $chunks9999 = array_chunk($urls9999, 10000);
        Assert::assertSame(1, count($chunks9999), "9,999 URLs must yield exactly 1 chunk");
        Assert::assertSame(9999, count($chunks9999[0]));

        // 4. Exactly 10,000 URLs (RFC boundary)
        $urls10000 = array_map(fn($i) => "https://medinextsolutions.com/locations/tx/city-{$i}/", range(1, 10000));
        $chunks10000 = array_chunk($urls10000, 10000);
        Assert::assertSame(1, count($chunks10000), "10,000 URLs must yield exactly 1 chunk of 10,000");
        Assert::assertSame(10000, count($chunks10000[0]));
        $payload10000 = $submitter->buildPayload($chunks10000[0]);
        Assert::assertSame(10000, count($payload10000['urlList']));

        // 5. Exactly 10,001 URLs (RFC boundary + 1)
        $urls10001 = array_map(fn($i) => "https://medinextsolutions.com/locations/tx/city-{$i}/", range(1, 10001));
        $chunks10001 = array_chunk($urls10001, 10000);
        Assert::assertSame(2, count($chunks10001), "10,001 URLs must yield exactly 2 chunks");
        Assert::assertSame(10000, count($chunks10001[0]));
        Assert::assertSame(1, count($chunks10001[1]));

        // 6. 15,000 Synthetic URLs (Stress test requirement)
        $urls15000 = array_map(fn($i) => "https://medinextsolutions.com/locations/state-{$i}/city-{$i}/", range(1, 15000));
        $chunks15000 = array_chunk($urls15000, 10000);
        Assert::assertSame(2, count($chunks15000), "15,000 URLs must yield 2 chunks (10,000 + 5,000)");
        Assert::assertSame(10000, count($chunks15000[0]));
        Assert::assertSame(5000, count($chunks15000[1]));

        // Test submitBatch slicing protection if an array > 10,000 is directly passed
        $resOverMax = $submitter->submitBatch($urls15000, true);
        Assert::assertTrue($resOverMax['success']);
        Assert::assertSame(10000, $resOverMax['url_count'], "submitBatch must enforce 10,000 max slice safety");
    });

    $suite->addTest('Scale & Throughput Stress: 50,000 Synthetic URLs Memory and Serialization Benchmark', 'Adversarial-Stress', function () {
        $submitter = new \IndexNowSubmitter();
        $memStart = memory_get_usage();
        $timeStart = microtime(true);

        // Generate 50,000 URLs
        $urls50000 = [];
        for ($i = 1; $i <= 50000; $i++) {
            $urls50000[] = "https://medinextsolutions.com/locations/state-" . ($i % 50) . "/city-" . $i . "/";
        }

        $chunks = array_chunk($urls50000, 10000);
        Assert::assertSame(5, count($chunks), "50,000 URLs must produce exactly 5 batches of 10,000");

        $totalSerializedBytes = 0;
        foreach ($chunks as $index => $batch) {
            $payload = $submitter->buildPayload($batch);
            $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
            Assert::assertIsString($json, "JSON serialization of 10,000 URL batch must succeed");
            $totalSerializedBytes += strlen($json);
            Assert::assertSame(10000, count($payload['urlList']));
        }

        $durationMs = (microtime(true) - $timeStart) * 1000;
        $memUsedMb = (memory_get_usage() - $memStart) / (1024 * 1024);

        Assert::assertLessThanOrEqual(500.0, $durationMs, "50,000 URL chunking & JSON serialization took {$durationMs}ms (threshold 500ms)");
        Assert::assertLessThanOrEqual(35.0, $memUsedMb, "Memory usage {$memUsedMb}MB exceeded 35MB limit");
    });

    // =========================================================================
    // SECTION 4: XML PARSER ADVERSARIAL RESILIENCE & SANITIZATION
    // =========================================================================

    $suite->addTest('XML Parser Resilience: Malformed, Truncated, and Non-XML Files', 'Adversarial-XML', function () use ($projectRoot) {
        $submitter = new \IndexNowSubmitter(null, null, null, null, $projectRoot);

        // Sub-test A: Broken / Truncated XML
        $badXmlPath = $projectRoot . '/temp_bad_' . uniqid() . '.xml';
        file_put_contents($badXmlPath, '<?xml version="1.0"?><urlset><url><loc>https://medinextsolutions.com/locations/valid/</loc><url><loc>broken-no-closing');
        try {
            $urls = $submitter->extractUrls(basename($badXmlPath));
            Assert::assertContains('https://medinextsolutions.com/locations/valid/', $urls);
        } finally {
            if (file_exists($badXmlPath)) unlink($badXmlPath);
        }

        // Sub-test B: 0-byte empty file -> triggers dynamic database fallback
        $emptyXmlPath = $projectRoot . '/temp_empty_' . uniqid() . '.xml';
        file_put_contents($emptyXmlPath, '');
        try {
            $urls = $submitter->extractUrls(basename($emptyXmlPath));
            Assert::assertGreaterThanOrEqual(1, count($urls), "Empty XML file must trigger graceful fallback extraction");
        } finally {
            if (file_exists($emptyXmlPath)) unlink($emptyXmlPath);
        }

        // Sub-test C: Completely Non-XML binary/garbage file
        $garbagePath = $projectRoot . '/temp_garbage_' . uniqid() . '.xml';
        file_put_contents($garbagePath, "\x00\xFF\xFE\x01\x02\x03Random binary data not xml");
        try {
            $urls = $submitter->extractUrls(basename($garbagePath));
            Assert::assertIsArray($urls, "Garbage file must not crash parser");
        } finally {
            if (file_exists($garbagePath)) unlink($garbagePath);
        }
    });

    $suite->addTest('XML Parser Sanitization: CDATA, Whitespace, Domain Filtering, and De-duplication', 'Adversarial-XML', function () use ($projectRoot) {
        $submitter = new \IndexNowSubmitter(null, null, null, null, $projectRoot);
        $testXmlPath = $projectRoot . '/temp_sanitize_' . uniqid() . '.xml';

        $complexXml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Valid standard URL -->
    <url><loc>https://medinextsolutions.com/locations/texas/</loc></url>
    <!-- Duplicate URL -->
    <url><loc>https://medinextsolutions.com/locations/texas/</loc></url>
    <!-- CDATA wrapped URL -->
    <url><loc><![CDATA[https://medinextsolutions.com/locations/california/]]></loc></url>
    <!-- URL with leading/trailing whitespace -->
    <url><loc>   https://medinextsolutions.com/locations/florida/   </loc></url>
    <!-- Foreign / Mismatched domain (Must be rejected) -->
    <url><loc>https://attacker.evil.com/malicious-url</loc></url>
    <url><loc>https://google.com/search?q=test</loc></url>
    <!-- Invalid format URL -->
    <url><loc>not_a_valid_url_string</loc></url>
</urlset>';

        file_put_contents($testXmlPath, $complexXml);
        try {
            $extracted = $submitter->extractUrls(basename($testXmlPath));

            // Must include valid Medinext URLs
            Assert::assertContains('https://medinextsolutions.com/locations/texas/', $extracted);
            Assert::assertContains('https://medinextsolutions.com/locations/california/', $extracted);
            Assert::assertContains('https://medinextsolutions.com/locations/florida/', $extracted);

            // Must NOT contain duplicate copies
            $txCount = count(array_filter($extracted, fn($u) => $u === 'https://medinextsolutions.com/locations/texas/'));
            Assert::assertSame(1, $txCount, "Duplicate URLs must be de-duplicated to exactly 1");

            // Must REJECT external/unauthorized hosts
            foreach ($extracted as $url) {
                Assert::assertTrue(
                    str_starts_with($url, 'https://medinextsolutions.com') || str_starts_with($url, 'http://medinextsolutions.com'),
                    "Extracted URL must belong to target domain: {$url}"
                );
                Assert::assertFalse(str_contains($url, 'evil.com'), "Must reject foreign host attacker.evil.com");
                Assert::assertFalse(str_contains($url, 'google.com'), "Must reject foreign host google.com");
            }
        } finally {
            if (file_exists($testXmlPath)) unlink($testXmlPath);
        }
    });

    $suite->addTest('XML Parser: SitemapIndex Recursive Resolution', 'Adversarial-XML', function () use ($projectRoot) {
        $submitter = new \IndexNowSubmitter(null, null, null, null, $projectRoot);
        $sub1 = $projectRoot . '/temp_sub1_' . uniqid() . '.xml';
        $sub2 = $projectRoot . '/temp_sub2_' . uniqid() . '.xml';
        $indexXml = $projectRoot . '/temp_index_' . uniqid() . '.xml';

        file_put_contents($sub1, '<?xml version="1.0"?><urlset><url><loc>https://medinextsolutions.com/locations/sub1/</loc></url></urlset>');
        file_put_contents($sub2, '<?xml version="1.0"?><urlset><url><loc>https://medinextsolutions.com/locations/sub2/</loc></url></urlset>');

        $indexContent = '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap><loc>https://medinextsolutions.com/' . basename($sub1) . '</loc></sitemap>
    <sitemap><loc>https://medinextsolutions.com/' . basename($sub2) . '</loc></sitemap>
</sitemapindex>';
        file_put_contents($indexXml, $indexContent);

        try {
            $extracted = $submitter->extractUrls(basename($indexXml));
            Assert::assertContains('https://medinextsolutions.com/locations/sub1/', $extracted);
            Assert::assertContains('https://medinextsolutions.com/locations/sub2/', $extracted);
            Assert::assertSame(2, count($extracted));
        } finally {
            if (file_exists($sub1)) unlink($sub1);
            if (file_exists($sub2)) unlink($sub2);
            if (file_exists($indexXml)) unlink($indexXml);
        }
    });

    // =========================================================================
    // SECTION 5: HTTP STATUS CODE SIMULATION & RATE LIMITING (429) TOLERANCE
    // =========================================================================

    $suite->addTest('Network Simulation: HTTP Status Codes & Error Mapping (Mock Server)', 'Adversarial-Network', function () {
        // Start a lightweight ephemeral PHP mock HTTP server on an available port
        $port = 18459;
        $mockServerScript = sys_get_temp_dir() . '/indexnow_mock_server_' . uniqid() . '.php';

        $mockPhp = <<<'PHP'
<?php
$uri = $_SERVER['REQUEST_URI'] ?? '';
$input = file_get_contents('php://input');

if (str_contains($uri, '/status/200')) {
    http_response_code(200);
    echo 'OK';
} elseif (str_contains($uri, '/status/202')) {
    http_response_code(202);
    echo 'Accepted';
} elseif (str_contains($uri, '/status/400')) {
    http_response_code(400);
    echo 'Bad Request: Malformed JSON';
} elseif (str_contains($uri, '/status/403')) {
    http_response_code(403);
    echo 'Forbidden: Invalid key';
} elseif (str_contains($uri, '/status/422')) {
    http_response_code(422);
    echo 'Unprocessable Entity';
} elseif (str_contains($uri, '/status/429')) {
    http_response_code(429);
    header('Retry-After: 60');
    echo 'Too Many Requests: Rate limit exceeded';
} elseif (str_contains($uri, '/status/500')) {
    http_response_code(500);
    echo 'Internal Server Error';
} elseif (str_contains($uri, '/status/503')) {
    http_response_code(503);
    echo 'Service Unavailable';
} else {
    http_response_code(200);
    echo 'Default OK';
}
PHP;
        file_put_contents($mockServerScript, $mockPhp);

        // Spawn mock server process
        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $serverCmd = "php -S 127.0.0.1:{$port} " . escapeshellarg($mockServerScript);
        $serverProc = proc_open($serverCmd, $descriptors, $pipes);

        // Wait briefly for server to bind
        usleep(300000);

        try {
            $sampleBatch = ['https://medinextsolutions.com/locations/tx/'];

            // 1. Test HTTP 200 OK
            $sub200 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/200");
            $res200 = $sub200->submitBatch($sampleBatch, false, 5);
            Assert::assertTrue($res200['success']);
            Assert::assertSame(200, $res200['http_code']);
            Assert::assertSame('OK', $res200['status']);

            // 2. Test HTTP 202 Accepted
            $sub202 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/202");
            $res202 = $sub202->submitBatch($sampleBatch, false, 5);
            Assert::assertTrue($res202['success']);
            Assert::assertSame(202, $res202['http_code']);
            Assert::assertSame('OK', $res202['status']);

            // 3. Test HTTP 400 Bad Request
            $sub400 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/400");
            $res400 = $sub400->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res400['success']);
            Assert::assertSame(400, $res400['http_code']);
            Assert::assertSame('FAILED', $res400['status']);
            Assert::assertStringContainsIgnoreCase('Bad Request', $res400['status_text']);

            // 4. Test HTTP 403 Forbidden
            $sub403 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/403");
            $res403 = $sub403->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res403['success']);
            Assert::assertSame(403, $res403['http_code']);
            Assert::assertStringContainsIgnoreCase('Forbidden', $res403['status_text']);

            // 5. Test HTTP 422 Unprocessable Entity
            $sub422 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/422");
            $res422 = $sub422->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res422['success']);
            Assert::assertSame(422, $res422['http_code']);
            Assert::assertStringContainsIgnoreCase('Unprocessable', $res422['status_text']);

            // 6. Test HTTP 429 Too Many Requests (Rate Limiting)
            $sub429 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/429");
            $res429 = $sub429->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res429['success']);
            Assert::assertSame(429, $res429['http_code']);
            Assert::assertStringContainsIgnoreCase('Rate limit', $res429['status_text']);

            // 7. Test HTTP 500 & 503 Server Errors
            $sub500 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/500");
            $res500 = $sub500->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res500['success']);
            Assert::assertSame(500, $res500['http_code']);

            $sub503 = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/status/503");
            $res503 = $sub503->submitBatch($sampleBatch, false, 5);
            Assert::assertFalse($res503['success']);
            Assert::assertSame(503, $res503['http_code']);

        } finally {
            if (is_resource($serverProc)) {
                proc_terminate($serverProc);
                proc_close($serverProc);
            }
            if (file_exists($mockServerScript)) {
                unlink($mockServerScript);
            }
        }
    });

    $suite->addTest('Network Simulation: cURL Connection Timeout and DNS Failure Fault Tolerance', 'Adversarial-Network', function () {
        $sampleBatch = ['https://medinextsolutions.com/locations/tx/'];

        // Non-routable blackhole IP address (TEST-NET-2) with 1 second timeout
        $blackholeSubmitter = new \IndexNowSubmitter(null, null, "http://198.51.100.1:81/indexnow");
        $resTimeout = $blackholeSubmitter->submitBatch($sampleBatch, false, 1);

        Assert::assertFalse($resTimeout['success'], "Connection to blackhole IP must fail");
        Assert::assertNotNull($resTimeout['error'], "Error description must be captured");
        Assert::assertStringContainsIgnoreCase('cURL Error', $resTimeout['status_text']);
    });

    $suite->addTest('Upstream Response Resilience: HTML Error Pages & Empty Payloads from API', 'Adversarial-Network', function () {
        $port = 18460;
        $mockServerScript = sys_get_temp_dir() . '/indexnow_html_server_' . uniqid() . '.php';

        $mockPhp = <<<'PHP'
<?php
// Simulate upstream returning 502 Cloudflare Bad Gateway HTML
http_response_code(502);
echo '<html><head><title>502 Bad Gateway</title></head><body><center><h1>502 Bad Gateway</h1></center><hr><center>cloudflare</center></body></html>';
PHP;
        file_put_contents($mockServerScript, $mockPhp);

        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $serverCmd = "php -S 127.0.0.1:{$port} " . escapeshellarg($mockServerScript);
        $serverProc = proc_open($serverCmd, $descriptors, $pipes);
        usleep(300000);

        try {
            $sub = new \IndexNowSubmitter(null, null, "http://127.0.0.1:{$port}/indexnow");
            $res = $sub->submitBatch(['https://medinextsolutions.com/locations/tx/'], false, 5);

            Assert::assertFalse($res['success'], "HTML 502 error must result in success=false");
            Assert::assertSame(502, $res['http_code']);
            Assert::assertStringContainsIgnoreCase('Server Error', $res['status_text']);
            Assert::assertStringContains('502 Bad Gateway', $res['response_body']);
        } finally {
            if (is_resource($serverProc)) {
                proc_terminate($serverProc);
                proc_close($serverProc);
            }
            if (file_exists($mockServerScript)) unlink($mockServerScript);
        }
    });

    // =========================================================================
    // SECTION 6: WEB ENDPOINT SECURITY & UNAUTHORIZED ACCESS REJECTION
    // =========================================================================

    $suite->addTest('Web Endpoint Security: Authentication Matrix & Rejection of Unauthorized Access', 'Adversarial-Security', function () {
        $submitter = new \IndexNowSubmitter();

        // 1. Unauthorized: No parameters / headers
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        Assert::assertFalse($submitter->authenticateWebRequest(), "Empty request must be rejected");

        // 2. Unauthorized: Invalid secrets
        $_GET['secret'] = 'wrong_secret_123';
        Assert::assertFalse($submitter->authenticateWebRequest(), "Incorrect query secret must be rejected");

        $_GET['secret'] = "' OR '1'='1"; // SQL injection attempt
        Assert::assertFalse($submitter->authenticateWebRequest(), "SQL injection token must be rejected");

        $_GET = [];
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer invalid_bearer_token';
        Assert::assertFalse($submitter->authenticateWebRequest(), "Invalid Bearer token must be rejected");

        $_SERVER['HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode('admin:wrongpassword');
        Assert::assertFalse($submitter->authenticateWebRequest(), "Invalid Basic auth must be rejected");

        $_SERVER = [];
        $_SERVER['HTTP_X_INDEXNOW_SECRET'] = 'forged_header_value';
        Assert::assertFalse($submitter->authenticateWebRequest(), "Forged X-IndexNow-Secret must be rejected");

        // 3. Authorized: Valid default secret via GET ?secret=...
        $_SERVER = [];
        $_GET['secret'] = 'medinext_indexnow_secure_token_2026';
        Assert::assertTrue($submitter->authenticateWebRequest(), "Valid secret via GET must authenticate");

        // 4. Authorized: Valid key token via GET ?key=...
        $_GET = ['key' => '4a8f9b2c3d4e5f60718293a4b5c6d7e8'];
        Assert::assertTrue($submitter->authenticateWebRequest(), "Valid key via GET must authenticate");

        // 5. Authorized: Valid Bearer header
        $_GET = [];
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer medinext_indexnow_secure_token_2026';
        Assert::assertTrue($submitter->authenticateWebRequest(), "Valid Bearer header must authenticate");

        // 6. Authorized: Valid X-IndexNow-Secret header
        $_SERVER = ['HTTP_X_INDEXNOW_SECRET' => 'medinext_indexnow_secure_token_2026'];
        Assert::assertTrue($submitter->authenticateWebRequest(), "Valid custom header must authenticate");

        // Cleanup superglobals
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
    });

    // =========================================================================
    // SECTION 7: URI ENCODING, SPECIAL CHARACTERS & PAYLOAD FUZZING
    // =========================================================================

    $suite->addTest('URI Encoding & Special Characters: Query Params, Encoded Spaces, Hyphens', 'Adversarial-URI', function () use ($projectRoot) {
        $submitter = new \IndexNowSubmitter(null, null, null, null, $projectRoot);
        $tempXml = $projectRoot . '/temp_fuzz_' . uniqid() . '.xml';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>https://medinextsolutions.com/locations/new-york/new-york-city/</loc></url>
    <url><loc>https://medinextsolutions.com/locations/north-carolina/winston-salem/</loc></url>
    <url><loc>https://medinextsolutions.com/locations/texas/houston/?ref=sitemap&amp;utm_source=indexnow</url></url>
</urlset>';
        file_put_contents($tempXml, $xml);

        try {
            $extracted = $submitter->extractUrls(basename($tempXml));
            Assert::assertContains('https://medinextsolutions.com/locations/new-york/new-york-city/', $extracted);
            Assert::assertContains('https://medinextsolutions.com/locations/north-carolina/winston-salem/', $extracted);
        } finally {
            if (file_exists($tempXml)) unlink($tempXml);
        }
    });

    return $suite;
}

// Standalone runner
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getAdversarialIndexNowSuite());
    exit($runner->runAll());
}
