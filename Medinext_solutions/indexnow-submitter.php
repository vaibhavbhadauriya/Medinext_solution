<?php
/**
 * MEDINEXT SOLUTIONS - Automated IndexNow Batch Submitter & Verification Engine
 * 
 * Submits programmatic location URLs from sitemap-locations.xml (and core sitemap.xml)
 * to the universal IndexNow protocol API (https://api.indexnow.org/indexnow) or search engine endpoints.
 * 
 * Supports dual execution:
 *   1. CLI Command: php indexnow-submitter.php [--dry-run] [--batch-size=10000] [--sitemap=sitemap-locations.xml] [--all] [--json]
 *   2. Secured Web Endpoint: GET/POST /indexnow-submitter.php?secret=...&dry_run=1
 * 
 * Specification Compliance:
 *   - Key Format: 32-char hex token (4a8f9b2c3d4e5f60718293a4b5c6d7e8)
 *   - Key Location: https://medinextsolutions.com/4a8f9b2c3d4e5f60718293a4b5c6d7e8.txt
 *   - Batch Limits: Max 10,000 URLs per POST request
 *   - HTTP Status Handling: 200 OK, 202 Accepted, 400, 403, 422, 429, 500/503
 * 
 * PHP 8.2+ compatible.
 */

declare(strict_types=1);

// =============================================================================
// DEFAULT CONFIGURATION & CONSTANTS
// =============================================================================

define('INDEXNOW_DEFAULT_HOST', 'medinextsolutions.com');
define('INDEXNOW_DEFAULT_KEY', '4a8f9b2c3d4e5f60718293a4b5c6d7e8');
define('INDEXNOW_DEFAULT_ENDPOINT', 'https://api.indexnow.org/indexnow');
define('INDEXNOW_DEFAULT_SECRET', 'medinext_indexnow_secure_token_2026');
define('INDEXNOW_MAX_BATCH_SIZE', 10000);
define('INDEXNOW_DEFAULT_BATCH_SIZE', 10000);
define('INDEXNOW_VERSION', '1.2.0');

// =============================================================================
// CORE CLASS: IndexNowSubmitter
// =============================================================================

class IndexNowSubmitter
{
    private string $host;
    private string $key;
    private string $keyLocation;
    private string $endpoint;
    private string $secret;
    private string $baseDir;
    private ?string $logFile = null;

    public function __construct(
        ?string $host = null,
        ?string $key = null,
        ?string $endpoint = null,
        ?string $secret = null,
        ?string $baseDir = null
    ) {
        $this->baseDir = $baseDir ? rtrim($baseDir, '/\\') : __DIR__;
        $this->host = $host ?: (getenv('INDEXNOW_HOST') ?: INDEXNOW_DEFAULT_HOST);
        $this->key = $key ?: (getenv('INDEXNOW_KEY') ?: INDEXNOW_DEFAULT_KEY);
        $this->endpoint = $endpoint ?: (getenv('INDEXNOW_ENDPOINT') ?: INDEXNOW_DEFAULT_ENDPOINT);
        $this->secret = $secret ?: (getenv('INDEXNOW_SECRET') ?: INDEXNOW_DEFAULT_SECRET);
        $this->keyLocation = 'https://' . $this->host . '/' . $this->key . '.txt';

        $this->initLogging();
        $this->ensureVerificationKeyFile();
    }

    /**
     * Initialize audit log destination
     */
    private function initLogging(): void
    {
        $logsDir = $this->baseDir . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($logsDir)) {
            @mkdir($logsDir, 0755, true);
        }

        if (is_dir($logsDir) && is_writable($logsDir)) {
            $this->logFile = $logsDir . DIRECTORY_SEPARATOR . 'indexnow.log';
        } elseif (is_writable($this->baseDir)) {
            $this->logFile = $this->baseDir . DIRECTORY_SEPARATOR . 'indexnow.log';
        }
    }

    /**
     * Ensure the physical verification key file exists in the web root
     */
    public function ensureVerificationKeyFile(): bool
    {
        $keyFilePath = $this->baseDir . DIRECTORY_SEPARATOR . $this->key . '.txt';
        if (!file_exists($keyFilePath) || trim((string)@file_get_contents($keyFilePath)) !== $this->key) {
            $written = @file_put_contents($keyFilePath, $this->key);
            if ($written !== false) {
                $this->log('INFO', "Created verification key file: {$keyFilePath}");
                return true;
            } else {
                $this->log('WARN', "Unable to write verification key file to: {$keyFilePath}");
                return false;
            }
        }
        return true;
    }

    /**
     * Get verification key details
     */
    public function getKeyDetails(): array
    {
        $keyFilePath = $this->baseDir . DIRECTORY_SEPARATOR . $this->key . '.txt';
        return [
            'key' => $this->key,
            'key_file' => $this->key . '.txt',
            'key_file_path' => $keyFilePath,
            'key_file_exists' => file_exists($keyFilePath),
            'key_file_content_valid' => file_exists($keyFilePath) && trim((string)@file_get_contents($keyFilePath)) === $this->key,
            'key_location_url' => $this->keyLocation,
            'host' => $this->host,
        ];
    }

    /**
     * Extract URLs from XML sitemap file(s) with fallback to database generation
     *
     * @param string|array $sitemapSources Single file path or array of file paths / names
     * @return array List of normalized, unique URLs
     */
    public function extractUrls(string|array $sitemapSources = 'sitemap-locations.xml'): array
    {
        if (is_string($sitemapSources)) {
            $sitemapSources = [$sitemapSources];
        }

        $allUrls = [];

        foreach ($sitemapSources as $source) {
            $resolvedPath = $this->resolvePath($source);
            $urlsFromSource = [];

            if (file_exists($resolvedPath) && filesize($resolvedPath) > 0) {
                $urlsFromSource = $this->parseXmlSitemapFile($resolvedPath);
                $this->log('INFO', "Extracted " . count($urlsFromSource) . " URLs from sitemap file: {$source}");
            } else {
                // Fallback: If sitemap-locations.xml is missing, attempt to generate or load dynamically
                $this->log('WARN', "Sitemap file not found or empty: {$resolvedPath}. Attempting dynamic fallback...");
                $urlsFromSource = $this->fallbackExtractLocationUrls();
                $this->log('INFO', "Fallback extracted " . count($urlsFromSource) . " URLs from database/helpers.");
            }

            foreach ($urlsFromSource as $url) {
                $cleanUrl = trim((string)$url);
                if ($this->isValidTargetUrl($cleanUrl)) {
                    $allUrls[] = $cleanUrl;
                }
            }
        }

        // De-duplicate URLs while preserving order
        $uniqueUrls = array_values(array_unique($allUrls));
        $this->log('INFO', "Total validated unique URLs for submission: " . count($uniqueUrls));
        return $uniqueUrls;
    }

    /**
     * Parse XML sitemap using SimpleXML with stream fallback
     */
    private function parseXmlSitemapFile(string $filePath): array
    {
        $urls = [];

        // Method 1: SimpleXML (fastest for standard XML)
        if (extension_loaded('simplexml')) {
            $prevEntityLoader = null;
            if (\PHP_VERSION_ID < 80000 && function_exists('libxml_disable_entity_loader')) {
                /** @noinspection PhpDeprecationInspection */
                $prevEntityLoader = libxml_disable_entity_loader(true);
            }
            libxml_use_internal_errors(true);

            $xml = simplexml_load_file($filePath, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NOWARNING);
            if ($prevEntityLoader !== null && function_exists('libxml_disable_entity_loader')) {
                /** @noinspection PhpDeprecationInspection */
                libxml_disable_entity_loader($prevEntityLoader);
            }

            if ($xml !== false) {
                // Check if sitemapindex (contains <sitemap><loc>...</loc></sitemap>)
                if ($xml->getName() === 'sitemapindex') {
                    foreach ($xml->sitemap as $sitemapEntry) {
                        if (isset($sitemapEntry->loc)) {
                            $subSitemapUrl = (string)$sitemapEntry->loc;
                            // If it's a local sub-sitemap, parse it
                            $subFilename = basename(parse_url($subSitemapUrl, PHP_URL_PATH) ?: '');
                            if ($subFilename) {
                                $subPath = $this->resolvePath($subFilename);
                                if (file_exists($subPath)) {
                                    $urls = array_merge($urls, $this->parseXmlSitemapFile($subPath));
                                }
                            }
                        }
                    }
                } else {
                    // Standard <urlset>
                    foreach ($xml->url as $urlEntry) {
                        if (isset($urlEntry->loc)) {
                            $urls[] = trim((string)$urlEntry->loc);
                        }
                    }
                }

                if (!empty($urls)) {
                    return $urls;
                }
            }
            libxml_clear_errors();
        }

        // Method 2: XMLReader streaming parser fallback
        if (class_exists('XMLReader')) {
            $reader = new XMLReader();
            if (@$reader->open($filePath)) {
                while ($reader->read()) {
                    if ($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'loc') {
                        $locContent = $reader->readString();
                        if (!empty($locContent)) {
                            $urls[] = trim($locContent);
                        }
                    }
                }
                $reader->close();
                if (!empty($urls)) {
                    return $urls;
                }
            }
        }

        // Method 3: Robust regex streaming parser fallback
        $handle = @fopen($filePath, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/<loc\b[^>]*>(.*?)<\/loc>/i', $line, $matches)) {
                    $extracted = trim($matches[1]);
                    if (!empty($extracted)) {
                        $urls[] = $extracted;
                    }
                }
            }
            fclose($handle);
        }

        return $urls;
    }

    /**
     * Dynamic fallback: extracts URLs directly from location helper / SQLite database
     */
    private function fallbackExtractLocationUrls(): array
    {
        $urls = [];
        $baseUrl = 'https://' . $this->host;

        // Add national hub
        $urls[] = $baseUrl . '/locations/';

        // Check if location-helper.php is available
        $helperPath = $this->baseDir . '/includes/location-helper.php';
        if (file_exists($helperPath)) {
            require_once $helperPath;

            if (function_exists('getAllStates')) {
                $states = getAllStates();
                foreach ($states as $st) {
                    if (!empty($st['slug'])) {
                        $urls[] = $baseUrl . '/locations/' . $st['slug'] . '/';
                    }
                }
            }

            if (function_exists('getLocationPDO')) {
                $pdo = getLocationPDO();
                if ($pdo) {
                    try {
                        $stmt = $pdo->query("
                            SELECT state_slug, city_slug 
                            FROM cities 
                            WHERE ranking <= 2 OR population >= 5000 
                            ORDER BY population DESC
                        ");
                        $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($cities as $ct) {
                            if (!empty($ct['state_slug']) && !empty($ct['city_slug'])) {
                                $urls[] = $baseUrl . '/locations/' . $ct['state_slug'] . '/' . $ct['city_slug'] . '/';
                            }
                        }
                    } catch (Throwable $e) {
                        $this->log('ERROR', "Database query fallback error: " . $e->getMessage());
                    }
                }
            }
        }

        return $urls;
    }

    /**
     * Validate target URL format and host match
     */
    private function isValidTargetUrl(string $url): bool
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parsedHost = parse_url($url, PHP_URL_HOST);
        if (!$parsedHost) {
            return false;
        }

        $expectedCleanHost = preg_replace('/^www\./i', '', strtolower($this->host));
        $parsedCleanHost = preg_replace('/^www\./i', '', strtolower($parsedHost));

        return $parsedCleanHost === $expectedCleanHost;
    }

    /**
     * Build standard IndexNow JSON payload array
     */
    public function buildPayload(array $urlBatch): array
    {
        return [
            'host' => $this->host,
            'key' => $this->key,
            'keyLocation' => $this->keyLocation,
            'urlList' => array_values($urlBatch),
        ];
    }

    /**
     * Submit a single batch of URLs to IndexNow API
     *
     * @param array $urlBatch Array of URLs (max 10,000)
     * @param bool $dryRun If true, skips network request and validates payload
     * @param int $timeout cURL timeout in seconds
     * @return array Execution telemetry result
     */
    public function submitBatch(array $urlBatch, bool $dryRun = false, int $timeout = 30): array
    {
        $batchCount = count($urlBatch);
        if ($batchCount === 0) {
            return [
                'success' => false,
                'http_code' => 400,
                'status' => 'EMPTY_BATCH',
                'status_text' => 'Batch contains 0 URLs',
                'url_count' => 0,
                'latency_ms' => 0,
                'response_body' => '',
                'error' => 'No URLs provided in batch',
            ];
        }

        if ($batchCount > INDEXNOW_MAX_BATCH_SIZE) {
            $urlBatch = array_slice($urlBatch, 0, INDEXNOW_MAX_BATCH_SIZE);
            $batchCount = count($urlBatch);
        }

        $payload = $this->buildPayload($urlBatch);

        // Dry Run Mode: Simulate successful submission with full validation
        if ($dryRun) {
            $this->log('INFO', "[DRY-RUN] Prepared payload for {$batchCount} URLs (Key: {$this->key})");
            return [
                'success' => true,
                'dry_run' => true,
                'http_code' => 200,
                'status' => 'DRY_RUN_OK',
                'status_text' => 'DRY RUN: Validation passed (Simulated HTTP 200 OK - No network request sent)',
                'url_count' => $batchCount,
                'latency_ms' => 1,
                'response_body' => '',
                'payload_preview' => [
                    'host' => $payload['host'],
                    'key' => $payload['key'],
                    'keyLocation' => $payload['keyLocation'],
                    'url_count' => $batchCount,
                    'sample_urls' => array_slice($urlBatch, 0, 5),
                ],
            ];
        }

        // Live Mode: Dispatch via cURL
        $startTime = microtime(true);
        $ch = curl_init($this->endpoint);

        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json, text/plain, */*',
            'User-Agent: Medinext-IndexNow-Submitter/' . INDEXNOW_VERSION . ' (+https://' . $this->host . ')',
        ];

        // Determine CA cert bundle for SSL verification
        $caBundle = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
        if (!$caBundle || !file_exists($caBundle)) {
            $candidatePaths = [
                'C:/xampp/apache/bin/curl-ca-bundle.pem',
                'C:/xampp/php/extras/ssl/cacert.pem',
                'C:/Program Files/Common Files/SSL/cert.pem',
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/pki/tls/certs/ca-bundle.crt',
            ];
            foreach ($candidatePaths as $path) {
                if (file_exists($path)) {
                    $caBundle = $path;
                    break;
                }
            }
        }

        $curlOptions = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];

        if (!empty($caBundle) && file_exists($caBundle)) {
            $curlOptions[CURLOPT_CAINFO] = $caBundle;
        }

        curl_setopt_array($ch, $curlOptions);

        $responseBody = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $durationMs = (int)round((microtime(true) - $startTime) * 1000);
        curl_close($ch);

        // Evaluate Response Status
        $success = false;
        $statusText = '';

        if ($curlErrno !== 0) {
            $statusText = "cURL Error ({$curlErrno}): {$curlError}";
            $this->log('ERROR', "Batch submission failed: {$statusText}");
        } else {
            $statusText = match ($httpCode) {
                200 => 'OK - URLs submitted successfully',
                202 => 'Accepted - URLs received, key verification pending',
                400 => 'Bad Request - Invalid format / malformed payload',
                403 => 'Forbidden - Key invalid, keyLocation unreachable, or key mismatch',
                422 => 'Unprocessable Entity - URLs do not match host or invalid schema',
                429 => 'Too Many Requests - Rate limit exceeded',
                500, 502, 503, 504 => "Server Error - Upstream IndexNow service error ({$httpCode})",
                default => "HTTP Response {$httpCode}",
            };

            $success = in_array($httpCode, [200, 202], true);
            $logLevel = $success ? 'SUCCESS' : 'ERROR';
            $this->log($logLevel, "Submitted {$batchCount} URLs -> HTTP {$httpCode} ({$statusText}) in {$durationMs}ms");
        }

        return [
            'success' => $success,
            'dry_run' => false,
            'http_code' => $httpCode,
            'status' => $success ? 'OK' : 'FAILED',
            'status_text' => $statusText,
            'url_count' => $batchCount,
            'latency_ms' => $durationMs,
            'response_body' => is_string($responseBody) ? substr($responseBody, 0, 500) : '',
            'error' => $curlErrno !== 0 ? $curlError : ($success ? null : $statusText),
        ];
    }

    /**
     * Execute full submission workflow (extract, slice into batches, submit)
     *
     * @param array $options Configuration options
     * @return array Full execution summary
     */
    public function execute(array $options = []): array
    {
        $startTime = microtime(true);

        $dryRun = !empty($options['dry_run']);
        $batchSize = isset($options['batch_size']) ? (int)$options['batch_size'] : INDEXNOW_DEFAULT_BATCH_SIZE;
        $batchSize = max(1, min(INDEXNOW_MAX_BATCH_SIZE, $batchSize));

        $sitemapSources = $options['sitemaps'] ?? ['sitemap-locations.xml'];
        if (!empty($options['include_all'])) {
            $sitemapSources = ['sitemap-locations.xml', 'sitemap.xml'];
        }

        $urls = $this->extractUrls($sitemapSources);
        $totalUrls = count($urls);

        if ($totalUrls === 0) {
            return [
                'status' => 'error',
                'mode' => $dryRun ? 'dry-run' : 'live',
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'message' => 'No valid URLs found for submission',
                'total_urls' => 0,
                'batches_count' => 0,
                'results' => [],
            ];
        }

        $batches = array_chunk($urls, $batchSize);
        $totalBatches = count($batches);
        $results = [];
        $successCount = 0;
        $failCount = 0;

        $this->log('INFO', "Starting submission of {$totalUrls} URLs in {$totalBatches} batch(es) (Batch size: {$batchSize}, Mode: " . ($dryRun ? 'DRY-RUN' : 'LIVE') . ")");

        foreach ($batches as $index => $batchUrls) {
            $batchNum = $index + 1;
            $batchResult = $this->submitBatch($batchUrls, $dryRun);
            $batchResult['batch_number'] = $batchNum;
            $results[] = $batchResult;

            if ($batchResult['success']) {
                $successCount++;
            } else {
                $failCount++;
            }

            // Small pause between multiple live batches if more than 1
            if (!$dryRun && $totalBatches > 1 && $batchNum < $totalBatches) {
                usleep(250000); // 250ms throttle
            }
        }

        $totalDuration = round(microtime(true) - $startTime, 4);

        $summary = [
            'status' => $failCount === 0 ? 'success' : ($successCount > 0 ? 'partial_success' : 'failed'),
            'mode' => $dryRun ? 'dry-run' : 'live',
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'host' => $this->host,
            'key' => $this->key,
            'key_location' => $this->keyLocation,
            'endpoint' => $this->endpoint,
            'sitemap_sources' => $sitemapSources,
            'total_urls_extracted' => $totalUrls,
            'batches_count' => $totalBatches,
            'batch_size' => $batchSize,
            'results' => $results,
            'summary' => [
                'total_urls' => $totalUrls,
                'total_batches' => $totalBatches,
                'successful_batches' => $successCount,
                'failed_batches' => $failCount,
                'execution_time_seconds' => $totalDuration,
            ],
        ];

        return $summary;
    }

    /**
     * Resolve relative file path against base directory
     */
    private function resolvePath(string $path): string
    {
        if (str_starts_with($path, '/') || (DIRECTORY_SEPARATOR === '\\' && preg_match('/^[a-zA-Z]:[\\\\\/]/', $path))) {
            return $path;
        }
        return $this->baseDir . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
    }

    /**
     * Log message to file and optionally stdout
     */
    public function log(string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $sapi = php_sapi_name() === 'cli' ? 'CLI' : 'WEB';
        $logLine = "[{$timestamp}] [{$sapi}] [{$level}] {$message}\n";

        if ($this->logFile) {
            @file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * Authenticate Web requests
     */
    public function authenticateWebRequest(): bool
    {
        // 1. Query parameter secret/token/key
        $token = $_GET['secret'] ?? $_GET['token'] ?? $_GET['key'] ?? $_POST['secret'] ?? $_POST['token'] ?? $_POST['key'] ?? '';
        if (!empty($token) && ($token === $this->secret || $token === $this->key)) {
            return true;
        }

        // 2. HTTP Authorization Header (Bearer or Basic)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (!empty($authHeader)) {
            if (preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $bearer = trim($matches[1]);
                if ($bearer === $this->secret || $bearer === $this->key) {
                    return true;
                }
            } elseif (preg_match('/^Basic\s+(.*)$/i', $authHeader, $matches)) {
                $decoded = base64_decode(trim($matches[1]));
                $parts = explode(':', $decoded, 2);
                $pass = $parts[1] ?? $parts[0] ?? '';
                if ($pass === $this->secret || $pass === $this->key) {
                    return true;
                }
            }
        }

        // 3. Custom Header: X-IndexNow-Secret
        $customHeader = $_SERVER['HTTP_X_INDEXNOW_SECRET'] ?? '';
        if (!empty($customHeader) && ($customHeader === $this->secret || $customHeader === $this->key)) {
            return true;
        }

        return false;
    }
}

// =============================================================================
// CLI DISPATCHER & RUNNER
// =============================================================================

function runCliIndexNowSubmitter(array $argv): int
{
    $options = [
        'dry_run' => false,
        'batch_size' => INDEXNOW_DEFAULT_BATCH_SIZE,
        'sitemaps' => ['sitemap-locations.xml'],
        'include_all' => false,
        'json' => false,
        'host' => null,
        'key' => null,
        'endpoint' => null,
    ];

    // Parse CLI arguments
    for ($i = 1; $i < count($argv); $i++) {
        $arg = $argv[$i];
        if ($arg === '--dry-run' || $arg === '-d') {
            $options['dry_run'] = true;
        } elseif (str_starts_with($arg, '--batch-size=')) {
            $options['batch_size'] = (int)substr($arg, 13);
        } elseif ($arg === '--all' || $arg === '-a') {
            $options['include_all'] = true;
        } elseif (str_starts_with($arg, '--sitemap=')) {
            $options['sitemaps'] = [substr($arg, 10)];
        } elseif (str_starts_with($arg, '--host=')) {
            $options['host'] = substr($arg, 7);
        } elseif (str_starts_with($arg, '--key=')) {
            $options['key'] = substr($arg, 6);
        } elseif (str_starts_with($arg, '--endpoint=')) {
            $options['endpoint'] = substr($arg, 11);
        } elseif ($arg === '--json' || $arg === '-j') {
            $options['json'] = true;
        } elseif ($arg === '--help' || $arg === '-h') {
            printCliHelp();
            return 0;
        }
    }

    $submitter = new IndexNowSubmitter(
        $options['host'],
        $options['key'],
        $options['endpoint']
    );

    if (!$options['json']) {
        echo "\n" . str_repeat('=', 78) . "\n";
        echo " MEDINEXT SOLUTIONS - INDEXNOW AUTOMATED BATCH SUBMITTER v" . INDEXNOW_VERSION . "\n";
        echo str_repeat('=', 78) . "\n";
        echo " Host:         " . ($options['host'] ?: INDEXNOW_DEFAULT_HOST) . "\n";
        echo " Key:          " . ($options['key'] ?: INDEXNOW_DEFAULT_KEY) . "\n";
        echo " Key Location: https://" . ($options['host'] ?: INDEXNOW_DEFAULT_HOST) . "/" . ($options['key'] ?: INDEXNOW_DEFAULT_KEY) . ".txt\n";
        echo " Endpoint:     " . ($options['endpoint'] ?: INDEXNOW_DEFAULT_ENDPOINT) . "\n";
        echo " Sitemaps:     " . implode(', ', $options['include_all'] ? ['sitemap-locations.xml', 'sitemap.xml'] : $options['sitemaps']) . "\n";
        echo " Mode:         " . ($options['dry_run'] ? "\033[1;33mDRY-RUN (Simulation)\033[0m" : "\033[1;32mLIVE SUBMISSION\033[0m") . "\n";
        echo " Batch Size:   " . $options['batch_size'] . " URLs/batch\n";
        echo str_repeat('-', 78) . "\n\n";
    }

    $result = $submitter->execute($options);

    if ($options['json']) {
        echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Extraction & Validation:\n";
        echo "  - Total URLs Found: " . $result['total_urls_extracted'] . "\n";
        echo "  - Total Batches:    " . $result['batches_count'] . "\n\n";

        echo "Batch Execution Results:\n";
        foreach ($result['results'] as $res) {
            $batchNum = $res['batch_number'] ?? 1;
            $urlCount = $res['url_count'];
            $httpCode = $res['http_code'];
            $statusText = $res['status_text'];
            $latency = $res['latency_ms'];
            $statusColor = $res['success'] ? "\033[0;32m" : "\033[0;31m";
            $resetColor = "\033[0m";

            echo "  [Batch #{$batchNum}] {$urlCount} URLs -> HTTP {$statusColor}{$httpCode}{$resetColor} ({$statusText}) [{$latency}ms]\n";

            if (!empty($res['payload_preview'])) {
                echo "    Sample URLs:\n";
                foreach ($res['payload_preview']['sample_urls'] as $sampleUrl) {
                    echo "      * {$sampleUrl}\n";
                }
            }
        }

        echo "\n" . str_repeat('-', 78) . "\n";
        echo " Execution Summary:\n";
        echo "  - Status:           " . ($result['summary']['failed_batches'] === 0 ? "\033[1;32mSUCCESS\033[0m" : "\033[1;31mFAILED\033[0m") . "\n";
        echo "  - Successful Batches: {$result['summary']['successful_batches']} / {$result['summary']['total_batches']}\n";
        echo "  - Total Submitted URLs: {$result['summary']['total_urls']}\n";
        echo "  - Execution Time:   {$result['summary']['execution_time_seconds']} seconds\n";
        echo str_repeat('=', 78) . "\n\n";
    }

    return ($result['summary']['failed_batches'] === 0) ? 0 : 1;
}

function printCliHelp(): void
{
    echo <<<HELP

MEDINEXT SOLUTIONS - IndexNow Automated Batch Submitter
Usage: php indexnow-submitter.php [OPTIONS]

Options:
  --dry-run, -d         Simulate submission without making HTTP network calls
  --batch-size=N        Set batch size (default: 10000, max: 10000)
  --sitemap=FILE        Specify XML sitemap file to parse (default: sitemap-locations.xml)
  --all, -a             Submit both sitemap-locations.xml and sitemap.xml
  --host=DOMAIN         Override target host (default: medinextsolutions.com)
  --key=KEY             Override verification key (default: 4a8f9b2c3d4e5f60718293a4b5c6d7e8)
  --endpoint=URL        Override IndexNow API endpoint (default: https://api.indexnow.org/indexnow)
  --json, -j            Output response as formatted JSON
  --help, -h            Display this help documentation

Examples:
  php indexnow-submitter.php --dry-run
  php indexnow-submitter.php --batch-size=5000
  php indexnow-submitter.php --all --dry-run
  php indexnow-submitter.php --sitemap=sitemap-locations.xml --json

HELP;
}

// =============================================================================
// WEB DISPATCHER & RUNNER
// =============================================================================

function runWebIndexNowSubmitter(): void
{
    header('Content-Type: application/json; charset=utf-8');
    header('X-Robots-Tag: noindex, nofollow, noarchive');

    $submitter = new IndexNowSubmitter();

    // Authenticate Web access
    if (!$submitter->authenticateWebRequest()) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'code' => 401,
            'message' => 'Unauthorized: Valid secret or token required via ?secret=..., ?token=..., or Authorization header.',
            'verification_key_file' => 'https://' . INDEXNOW_DEFAULT_HOST . '/' . INDEXNOW_DEFAULT_KEY . '.txt',
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
    }

    $dryRun = isset($_GET['dry_run']) ? (bool)$_GET['dry_run'] : (isset($_POST['dry_run']) ? (bool)$_POST['dry_run'] : false);
    $batchSize = isset($_GET['batch_size']) ? (int)$_GET['batch_size'] : (isset($_POST['batch_size']) ? (int)$_POST['batch_size'] : INDEXNOW_DEFAULT_BATCH_SIZE);
    $sitemap = $_GET['sitemap'] ?? $_POST['sitemap'] ?? 'sitemap-locations.xml';
    $includeAll = !empty($_GET['all']) || !empty($_POST['all']);

    $options = [
        'dry_run' => $dryRun,
        'batch_size' => $batchSize,
        'sitemaps' => [$sitemap],
        'include_all' => $includeAll,
    ];

    $result = $submitter->execute($options);
    http_response_code($result['status'] === 'failed' ? 500 : 200);
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

// =============================================================================
// ENTRY POINT
// =============================================================================

$isDirectScript = false;
if (php_sapi_name() === 'cli') {
    if (isset($argv[0])) {
        $invokedScript = realpath($argv[0]);
        $thisScript = realpath(__FILE__);
        if ($invokedScript && $thisScript && $invokedScript === $thisScript) {
            $isDirectScript = true;
        }
    }
} else {
    $scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
    if (!empty($scriptFilename) && realpath($scriptFilename) === realpath(__FILE__)) {
        $isDirectScript = true;
    }
}

if ($isDirectScript) {
    if (php_sapi_name() === 'cli' || (isset($argv) && !empty($argv))) {
        $exitCode = runCliIndexNowSubmitter($argv ?? []);
        exit($exitCode);
    } else {
        runWebIndexNowSubmitter();
    }
}
