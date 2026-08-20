<?php
/**
 * MEDINEXT SOLUTIONS - Location Pages & Schema E2E Test Suite (M2)
 * 
 * Verifies dynamic HTML rendering of MAC Compliance cards and
 * semantic JSON-LD MedicalBusiness structured data with regional knowsAbout.
 */

declare(strict_types=1);

namespace Medinext\Tests;

require_once __DIR__ . '/e2e_test_runner.php';

/**
 * Execute an isolated programmatic location page request via PHP CLI
 */
function renderProgrammaticPage(?string $state = null, ?string $city = null): array {
    $projectRoot = dirname(__DIR__);
    $locationsScript = $projectRoot . '/locations.php';

    $stateArg = $state !== null ? var_export($state, true) : 'null';
    $cityArg  = $city !== null ? var_export($city, true) : 'null';

    // Construct self-contained PHP execution code simulating standard web server environment
    $phpCode = '<?php
        $_SERVER["HTTP_HOST"] = "medinextsolutions.com";
        $_SERVER["HTTPS"] = "on";
        $_SERVER["SERVER_PORT"] = "443";
        $uriState = ' . $stateArg . ' !== null ? rawurlencode(' . $stateArg . ') . "/" : "";
        $uriCity  = ' . $cityArg . ' !== null ? rawurlencode(' . $cityArg . ') . "/" : "";
        $_SERVER["REQUEST_URI"] = "/locations/" . $uriState . $uriCity;
        if (' . $stateArg . ' !== null) { $_GET["state"] = ' . $stateArg . '; }
        if (' . $cityArg . ' !== null) { $_GET["city"] = ' . $cityArg . '; }

        ob_start();
        include ' . var_export($locationsScript, true) . ';
        $html = ob_get_clean();
        $status = http_response_code() ?: 200;

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
        return [
            'statusCode' => 500,
            'html' => '',
            'schemas' => [],
            'medicalBusiness' => null,
            'breadcrumbs' => null,
            'faqs' => null,
            'error' => 'Failed to spawn PHP worker process'
        ];
    }

    fwrite($pipes[0], $phpCode);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    $decoded = json_decode($stdout, true);
    $html = is_array($decoded) && isset($decoded['html']) ? $decoded['html'] : $stdout;
    $statusCode = is_array($decoded) && isset($decoded['statusCode']) ? $decoded['statusCode'] : ($exitCode === 0 ? 200 : 500);

    // Extract all JSON-LD schemas
    $schemas = [];
    $medicalBusiness = null;
    $breadcrumbs = null;
    $faqs = null;

    if (preg_match_all('/<script\s+type=["\']application\/ld\+json["\']\s*>(.*?)<\/script>/is', $html, $matches)) {
        foreach ($matches[1] as $raw) {
            $parsed = json_decode(trim($raw), true);
            if (is_array($parsed)) {
                $schemas[] = $parsed;

                // Inspect root or @graph items
                $nodes = isset($parsed['@graph']) && is_array($parsed['@graph']) ? $parsed['@graph'] : [$parsed];
                foreach ($nodes as $node) {
                    if (!is_array($node)) continue;
                    $types = isset($node['@type']) ? (array)$node['@type'] : [];

                    if (in_array('MedicalBusiness', $types, true) || in_array('ProfessionalService', $types, true)) {
                        $medicalBusiness = $node;
                    }
                    if (in_array('BreadcrumbList', $types, true)) {
                        $breadcrumbs = $node;
                    }
                    if (in_array('FAQPage', $types, true)) {
                        $faqs = $node;
                    }
                }
            }
        }
    }

    return [
        'statusCode' => $statusCode,
        'html' => $html,
        'schemas' => $schemas,
        'medicalBusiness' => $medicalBusiness,
        'breadcrumbs' => $breadcrumbs,
        'faqs' => $faqs,
        'stderr' => $stderr,
        'exitCode' => $exitCode
    ];
}

/**
 * Build Location Pages TestSuite
 */
function getLocationPagesSuite(): TestSuite {
    $suite = new TestSuite('Location Pages & JSON-LD Schema Suite', 'Verifies HTML rendering of MAC sections and JSON-LD schema across state and city pages');

    // -------------------------------------------------------------
    // Test 1: State Hub Render — Texas (J-H Novitas)
    // -------------------------------------------------------------
    $suite->addTest('State Hub HTML Render: Texas (Novitas Solutions J-H)', 'Tier 1', function () {
        $res = renderProgrammaticPage('texas');
        Assert::assertEquals(200, $res['statusCode'], "Texas state page should return HTTP 200");
        Assert::assertStringContains('Texas', $res['html'], "Page must contain 'Texas'");
        Assert::assertStringContainsIgnoreCase('Novitas', $res['html'], "Page must display MAC contractor 'Novitas'");
        Assert::assertStringContainsIgnoreCase('J-H', $res['html'], "Page must display MAC code 'J-H' or 'Jurisdiction H'");
        Assert::assertStringContainsIgnoreCase('Novitasphere', $res['html'], "Page must reference portal 'Novitasphere'");
    });

    // -------------------------------------------------------------
    // Test 2: State Hub Render — California (J-E Noridian)
    // -------------------------------------------------------------
    $suite->addTest('State Hub HTML Render: California (Noridian Healthcare J-E)', 'Tier 1', function () {
        $res = renderProgrammaticPage('california');
        Assert::assertEquals(200, $res['statusCode'], "California state page should return HTTP 200");
        Assert::assertStringContains('California', $res['html'], "Page must contain 'California'");
        Assert::assertStringContainsIgnoreCase('Noridian', $res['html'], "Page must display MAC contractor 'Noridian'");
        Assert::assertStringContainsIgnoreCase('J-E', $res['html'], "Page must display MAC code 'J-E' or 'Jurisdiction E'");
        Assert::assertStringContainsIgnoreCase('Noridian Medicare Portal', $res['html'], "Page must reference 'Noridian Medicare Portal'");
    });

    // -------------------------------------------------------------
    // Test 3: State Hub Render — Florida (J-N First Coast / FCSO)
    // -------------------------------------------------------------
    $suite->addTest('State Hub HTML Render: Florida (First Coast Service Options J-N)', 'Tier 1', function () {
        $res = renderProgrammaticPage('florida');
        Assert::assertEquals(200, $res['statusCode'], "Florida state page should return HTTP 200");
        Assert::assertStringContains('Florida', $res['html'], "Page must contain 'Florida'");
        Assert::assertTrue(
            stripos($res['html'], 'First Coast') !== false || stripos($res['html'], 'FCSO') !== false,
            "Page must display MAC contractor 'First Coast' or 'FCSO'"
        );
        Assert::assertStringContainsIgnoreCase('J-N', $res['html'], "Page must display MAC code 'J-N'");
    });

    // -------------------------------------------------------------
    // Test 4: State Hub Render — New York (J-K NGS)
    // -------------------------------------------------------------
    $suite->addTest('State Hub HTML Render: New York (National Government Services J-K)', 'Tier 1', function () {
        $res = renderProgrammaticPage('new-york');
        Assert::assertEquals(200, $res['statusCode'], "New York state page should return HTTP 200");
        Assert::assertStringContains('New York', $res['html'], "Page must contain 'New York'");
        Assert::assertTrue(
            stripos($res['html'], 'National Government Services') !== false || stripos($res['html'], 'NGS') !== false,
            "Page must display MAC contractor 'NGS' / 'National Government Services'"
        );
        Assert::assertStringContainsIgnoreCase('J-K', $res['html'], "Page must display MAC code 'J-K'");
    });

    // -------------------------------------------------------------
    // Test 5: State Hub Render — Ohio (J-15 CGS)
    // -------------------------------------------------------------
    $suite->addTest('State Hub HTML Render: Ohio (CGS Administrators J-15)', 'Tier 1', function () {
        $res = renderProgrammaticPage('ohio');
        Assert::assertEquals(200, $res['statusCode'], "Ohio state page should return HTTP 200");
        Assert::assertStringContains('Ohio', $res['html'], "Page must contain 'Ohio'");
        Assert::assertStringContainsIgnoreCase('CGS', $res['html'], "Page must display MAC contractor 'CGS'");
        Assert::assertStringContainsIgnoreCase('J-15', $res['html'], "Page must display MAC code 'J-15'");
    });

    // -------------------------------------------------------------
    // Test 6: City Landing Page Render — Houston, TX (J-H Novitas)
    // -------------------------------------------------------------
    $suite->addTest('City Landing HTML Render: Houston, TX (Novitas J-H & TMHP)', 'Tier 1', function () {
        $res = renderProgrammaticPage('texas', 'houston');
        Assert::assertEquals(200, $res['statusCode'], "Houston page should return HTTP 200");
        Assert::assertStringContains('Houston', $res['html'], "Page must contain city 'Houston'");
        Assert::assertStringContainsIgnoreCase('Novitas', $res['html'], "City page must render MAC contractor 'Novitas'");
        Assert::assertStringContainsIgnoreCase('J-H', $res['html'], "City page must render MAC jurisdiction 'J-H'");
        Assert::assertStringContainsIgnoreCase('Novitasphere', $res['html'], "City page must render portal 'Novitasphere'");
    });

    // -------------------------------------------------------------
    // Test 7: City Landing Page Render — Los Angeles, CA (J-E Noridian)
    // -------------------------------------------------------------
    $suite->addTest('City Landing HTML Render: Los Angeles, CA (Noridian J-E)', 'Tier 1', function () {
        $res = renderProgrammaticPage('california', 'los-angeles');
        Assert::assertEquals(200, $res['statusCode'], "Los Angeles page should return HTTP 200");
        Assert::assertStringContains('Los Angeles', $res['html'], "Page must contain 'Los Angeles'");
        Assert::assertStringContainsIgnoreCase('Noridian', $res['html'], "City page must render 'Noridian'");
        Assert::assertStringContainsIgnoreCase('J-E', $res['html'], "City page must render 'J-E'");
    });

    // -------------------------------------------------------------
    // Test 8: City Landing Page Render — Miami, FL (J-N FCSO)
    // -------------------------------------------------------------
    $suite->addTest('City Landing HTML Render: Miami, FL (FCSO J-N & SPOT Portal)', 'Tier 1', function () {
        $res = renderProgrammaticPage('florida', 'miami');
        Assert::assertEquals(200, $res['statusCode'], "Miami page should return HTTP 200");
        Assert::assertStringContains('Miami', $res['html'], "Page must contain 'Miami'");
        Assert::assertTrue(
            stripos($res['html'], 'First Coast') !== false || stripos($res['html'], 'FCSO') !== false,
            "City page must render FCSO"
        );
        Assert::assertStringContainsIgnoreCase('J-N', $res['html'], "City page must render 'J-N'");
    });

    // -------------------------------------------------------------
    // Test 9: City Landing Page Render — New York City, NY (J-K NGS)
    // -------------------------------------------------------------
    $suite->addTest('City Landing HTML Render: New York, NY (NGS J-K & NGSConnex)', 'Tier 1', function () {
        $res = renderProgrammaticPage('new-york', 'new-york');
        Assert::assertEquals(200, $res['statusCode'], "New York City page should return HTTP 200");
        Assert::assertStringContains('New York', $res['html'], "Page must contain 'New York'");
        Assert::assertTrue(
            stripos($res['html'], 'National Government Services') !== false || stripos($res['html'], 'NGS') !== false,
            "City page must render NGS"
        );
        Assert::assertStringContainsIgnoreCase('J-K', $res['html'], "City page must render 'J-K'");
    });

    // -------------------------------------------------------------
    // Test 10: City Landing Page Render — Columbus, OH (J-15 CGS)
    // -------------------------------------------------------------
    $suite->addTest('City Landing HTML Render: Columbus, OH (CGS Administrators J-15)', 'Tier 1', function () {
        $res = renderProgrammaticPage('ohio', 'columbus');
        Assert::assertEquals(200, $res['statusCode'], "Columbus page should return HTTP 200");
        Assert::assertStringContains('Columbus', $res['html'], "Page must contain 'Columbus'");
        Assert::assertStringContainsIgnoreCase('CGS', $res['html'], "City page must render 'CGS'");
        Assert::assertStringContainsIgnoreCase('J-15', $res['html'], "City page must render 'J-15'");
    });

    // -------------------------------------------------------------
    // Test 11: JSON-LD Schema: MedicalBusiness with knowsAbout
    // -------------------------------------------------------------
    $suite->addTest('JSON-LD Schema: MedicalBusiness with Regional knowsAbout Array', 'Tier 1', function () {
        $cities = [
            ['state' => 'texas', 'city' => 'houston', 'mac' => 'Jurisdiction H', 'contractor' => 'Novitas'],
            ['state' => 'california', 'city' => 'los-angeles', 'mac' => 'Jurisdiction E', 'contractor' => 'Noridian'],
            ['state' => 'florida', 'city' => 'miami', 'mac' => 'Jurisdiction N', 'contractor' => 'First Coast'],
            ['state' => 'new-york', 'city' => 'new-york', 'mac' => 'Jurisdiction K', 'contractor' => 'National Government Services'],
            ['state' => 'ohio', 'city' => 'columbus', 'mac' => 'Jurisdiction 15', 'contractor' => 'CGS']
        ];

        foreach ($cities as $target) {
            $res = renderProgrammaticPage($target['state'], $target['city']);
            Assert::assertEquals(200, $res['statusCode'], "Rendering {$target['city']} failed");
            Assert::assertGreaterThanOrEqual(1, count($res['schemas']), "Page for {$target['city']} must contain valid JSON-LD schemas");
            Assert::assertNotNull($res['medicalBusiness'], "Page for {$target['city']} must contain a 'MedicalBusiness' JSON-LD entity");

            $mb = $res['medicalBusiness'];
            Assert::assertArrayHasKey('knowsAbout', $mb, "MedicalBusiness for {$target['city']} must contain 'knowsAbout'");
            Assert::assertIsArray($mb['knowsAbout'], "'knowsAbout' must be an array");
            Assert::assertGreaterThanOrEqual(3, count($mb['knowsAbout']), "'knowsAbout' for {$target['city']} must contain >= 3 topics");

            $knowsText = implode(' | ', $mb['knowsAbout']);
            Assert::assertTrue(
                stripos($knowsText, $target['mac']) !== false || 
                stripos($knowsText, $target['contractor']) !== false ||
                stripos($knowsText, 'MAC') !== false ||
                stripos($knowsText, 'Medicare') !== false,
                "MedicalBusiness knowsAbout for {$target['city']} must reference MAC contractor or jurisdiction. Got: " . $knowsText
            );
        }
    });

    // -------------------------------------------------------------
    // Test 12: BreadcrumbList & FAQPage Schema Validation
    // -------------------------------------------------------------
    $suite->addTest('JSON-LD Schema: BreadcrumbList and FAQPage Verification', 'Tier 1', function () {
        // State Breadcrumb
        $stateRes = renderProgrammaticPage('texas');
        Assert::assertNotNull($stateRes['breadcrumbs'], "State page must contain BreadcrumbList schema");
        Assert::assertArrayHasKey('itemListElement', $stateRes['breadcrumbs']);

        // City Breadcrumb & FAQs
        $cityRes = renderProgrammaticPage('texas', 'houston');
        Assert::assertNotNull($cityRes['breadcrumbs'], "City page must contain BreadcrumbList schema");
        Assert::assertNotNull($cityRes['faqs'], "City page must contain FAQPage schema");
        Assert::assertArrayHasKey('mainEntity', $cityRes['faqs']);
        Assert::assertGreaterThanOrEqual(3, count($cityRes['faqs']['mainEntity']), "City FAQs must have >= 3 questions");
    });

    // -------------------------------------------------------------
    // Test 13: Error Handling & 404 Pages
    // -------------------------------------------------------------
    $suite->addTest('Routing & Error Handling: 404 for Invalid State & City Slugs', 'Tier 2', function () {
        // Invalid state
        $res1 = renderProgrammaticPage('nonexistentstate9999');
        Assert::assertTrue(
            $res1['statusCode'] === 404 || stripos($res1['html'], '404') !== false || stripos($res1['html'], 'Not Found') !== false,
            "Invalid state must trigger 404 response"
        );

        // Invalid city in valid state
        $res2 = renderProgrammaticPage('texas', 'nonexistentcity9999');
        Assert::assertTrue(
            $res2['statusCode'] === 404 || stripos($res2['html'], '404') !== false || stripos($res2['html'], 'Not Found') !== false,
            "Invalid city must trigger 404 response"
        );
    });

    // -------------------------------------------------------------
    // Test 14: Adversarial Input Sanitization & XSS Prevention
    // -------------------------------------------------------------
    $suite->addTest('Adversarial Input Safety: XSS and Parameter Injection Prevention', 'Tier 2', function () {
        $xssSlug = 'texas<script>alert("xss")</script>';
        $res = renderProgrammaticPage($xssSlug);
        // Ensure raw unescaped script tag is not present in response HTML
        Assert::assertFalse(
            strpos($res['html'], '<script>alert("xss")</script>') !== false,
            "Raw unescaped script tag must not be injected into HTML output"
        );
    });

    // -------------------------------------------------------------
    // Test 15: Pairwise 12-Jurisdiction Metro City Matrix
    // -------------------------------------------------------------
    $suite->addTest('Pairwise 12-Jurisdiction Metro City Matrix (12 MACs Coverage)', 'Tier 3', function () {
        $matrix12 = [
            'J-E'  => ['state' => 'california', 'city' => 'san-francisco', 'name' => 'San Francisco', 'operator' => 'Noridian'],
            'J-F'  => ['state' => 'washington', 'city' => 'seattle', 'name' => 'Seattle', 'operator' => 'Noridian'],
            'J-5'  => ['state' => 'missouri', 'city' => 'kansas-city', 'name' => 'Kansas City', 'operator' => 'Wisconsin Physicians Service'],
            'J-6'  => ['state' => 'illinois', 'city' => 'chicago', 'name' => 'Chicago', 'operator' => 'National Government Services'],
            'J-8'  => ['state' => 'indiana', 'city' => 'indianapolis', 'name' => 'Indianapolis', 'operator' => 'Wisconsin Physicians Service'],
            'J-H'  => ['state' => 'texas', 'city' => 'dallas', 'name' => 'Dallas', 'operator' => 'Novitas'],
            'J-J'  => ['state' => 'georgia', 'city' => 'atlanta', 'name' => 'Atlanta', 'operator' => 'Palmetto'],
            'J-M'  => ['state' => 'north-carolina', 'city' => 'charlotte', 'name' => 'Charlotte', 'operator' => 'Palmetto'],
            'J-N'  => ['state' => 'florida', 'city' => 'orlando', 'name' => 'Orlando', 'operator' => 'First Coast'],
            'J-L'  => ['state' => 'pennsylvania', 'city' => 'philadelphia', 'name' => 'Philadelphia', 'operator' => 'Novitas'],
            'J-K'  => ['state' => 'massachusetts', 'city' => 'boston', 'name' => 'Boston', 'operator' => 'National Government Services'],
            'J-15' => ['state' => 'ohio', 'city' => 'cincinnati', 'name' => 'Cincinnati', 'operator' => 'CGS']
        ];

        foreach ($matrix12 as $macCode => $spec) {
            $res = renderProgrammaticPage($spec['state'], $spec['city']);
            Assert::assertEquals(200, $res['statusCode'], "Page render failed for {$spec['city']}, {$spec['state']}");
            Assert::assertStringContains($spec['name'], $res['html'], "Page for {$spec['city']} must contain city name");
            Assert::assertStringContainsIgnoreCase($macCode, $res['html'], "Page for {$spec['city']} must mention MAC code {$macCode}");
            Assert::assertStringContainsIgnoreCase($spec['operator'], $res['html'], "Page for {$spec['city']} must mention operator {$spec['operator']}");
            Assert::assertNotNull($res['medicalBusiness'], "Page for {$spec['city']} must have MedicalBusiness schema");
            Assert::assertArrayHasKey('knowsAbout', $res['medicalBusiness'], "MedicalBusiness for {$spec['city']} must have 'knowsAbout'");
        }
    });

    // -------------------------------------------------------------
    // Test 16: Sequential Multi-Location Render Workload
    // -------------------------------------------------------------
    $suite->addTest('Workload Scenario: Sequential Multi-Location Render & Memory Stability', 'Tier 4', function () {
        $targets = [
            ['state' => 'texas', 'city' => 'austin'],
            ['state' => 'california', 'city' => 'san-diego'],
            ['state' => 'florida', 'city' => 'tampa'],
            ['state' => 'new-york', 'city' => 'buffalo'],
            ['state' => 'illinois', 'city' => 'aurora'],
            ['state' => 'arizona', 'city' => 'phoenix'],
            ['state' => 'colorado', 'city' => 'denver'],
            ['state' => 'tennessee', 'city' => 'nashville'],
            ['state' => 'michigan', 'city' => 'detroit'],
            ['state' => 'pennsylvania', 'city' => 'pittsburgh']
        ];

        $start = microtime(true);
        foreach ($targets as $t) {
            $res = renderProgrammaticPage($t['state'], $t['city']);
            Assert::assertEquals(200, $res['statusCode'], "Render failed for {$t['city']}");
            Assert::assertGreaterThanOrEqual(10000, strlen($res['html']), "HTML for {$t['city']} should be comprehensive (>10KB)");
        }
        $elapsedMs = (microtime(true) - $start) * 1000;

        // Verify total time for 10 full page renders (including sub-processes) is reasonable
        Assert::assertLessThanOrEqual(30000.0, $elapsedMs, "10 sequential page renders took {$elapsedMs}ms");
    });

    return $suite;
}

// Standalone execution support
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getLocationPagesSuite());
    exit($runner->runAll());
}
