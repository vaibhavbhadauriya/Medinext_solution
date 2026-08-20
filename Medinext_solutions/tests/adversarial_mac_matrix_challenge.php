<?php
/**
 * MEDINEXT SOLUTIONS - Master Adversarial MAC & Location Challenge Suite
 * 
 * Adversarial Testing, Extreme Fuzzing, Boundary Value Analysis,
 * CMS Statutory Compliance, and Edge Case Empirical Verification.
 */

declare(strict_types=1);

namespace Medinext\Tests\Adversarial;

require_once __DIR__ . '/e2e_test_runner.php';
require_once __DIR__ . '/test_location_pages.php';
require_once dirname(__DIR__) . '/includes/location-helper.php';

use Medinext\Tests\Assert;
use Medinext\Tests\TestSuite;
use Medinext\Tests\TestRunner;
use function Medinext\Tests\renderProgrammaticPage;

function getAdversarialMacSuite(): TestSuite {
    $suite = new TestSuite('Adversarial MAC Matrix & Location Rendering Challenge', 'Adversarial fuzzing, CMS strict mapping, edge cases, and high-throughput stress testing');

    // =========================================================================
    // SECTION 1: STRICT CMS STATUTORY 56-JURISDICTION EXHAUSTIVE VERIFICATION
    // =========================================================================

    $suite->addTest('CMS Statutory 56-Jurisdiction Exhaustive 1:1 Mapping Verification', 'Adversarial', function () {
        $cmsExpected = [
            'J-E' => [
                'contractor' => 'Noridian Healthcare Solutions, LLC',
                'contractor_short' => 'Noridian',
                'headquarters' => 'Fargo, ND',
                'portal_keyword' => 'Noridian',
                'portal_url' => 'https://www.noridianmedicare.com',
                'states' => ['CA', 'HI', 'NV'],
                'territories' => ['AS', 'GU', 'MP']
            ],
            'J-F' => [
                'contractor' => 'Noridian Healthcare Solutions, LLC',
                'contractor_short' => 'Noridian',
                'headquarters' => 'Fargo, ND',
                'portal_keyword' => 'Noridian',
                'portal_url' => 'https://www.noridianmedicare.com',
                'states' => ['AK', 'AZ', 'ID', 'MT', 'ND', 'OR', 'SD', 'UT', 'WA', 'WY'],
                'territories' => []
            ],
            'J-5' => [
                'contractor' => 'Wisconsin Physicians Service (WPS GHA)',
                'contractor_short' => 'WPS GHA',
                'headquarters' => 'Madison, WI',
                'portal_keyword' => 'WPS',
                'portal_url' => 'https://www.wpsgha.com',
                'states' => ['IA', 'KS', 'MO', 'NE'],
                'territories' => []
            ],
            'J-6' => [
                'contractor' => 'National Government Services, Inc. (NGS)',
                'contractor_short' => 'NGS',
                'headquarters' => 'Indianapolis, IN',
                'portal_keyword' => 'NGSConnex',
                'portal_url' => 'https://www.ngsmedicare.com',
                'states' => ['IL', 'MN', 'WI'],
                'territories' => []
            ],
            'J-8' => [
                'contractor' => 'Wisconsin Physicians Service (WPS GHA)',
                'contractor_short' => 'WPS GHA',
                'headquarters' => 'Madison, WI',
                'portal_keyword' => 'WPS',
                'portal_url' => 'https://www.wpsgha.com',
                'states' => ['IN', 'MI'],
                'territories' => []
            ],
            'J-H' => [
                'contractor' => 'Novitas Solutions, Inc.',
                'contractor_short' => 'Novitas',
                'headquarters' => 'Mechanicsburg, PA',
                'portal_keyword' => 'Novitasphere',
                'portal_url' => 'https://www.novitas-solutions.com',
                'states' => ['AR', 'CO', 'LA', 'MS', 'NM', 'OK', 'TX'],
                'territories' => []
            ],
            'J-J' => [
                'contractor' => 'Palmetto GBA, LLC',
                'contractor_short' => 'Palmetto GBA',
                'headquarters' => 'Columbia, SC',
                'portal_keyword' => 'Palmetto',
                'portal_url' => 'https://www.palmettogba.com',
                'states' => ['AL', 'GA', 'TN'],
                'territories' => []
            ],
            'J-M' => [
                'contractor' => 'Palmetto GBA, LLC',
                'contractor_short' => 'Palmetto GBA',
                'headquarters' => 'Columbia, SC',
                'portal_keyword' => 'Palmetto',
                'portal_url' => 'https://www.palmettogba.com',
                'states' => ['NC', 'SC', 'VA', 'WV'],
                'territories' => []
            ],
            'J-N' => [
                'contractor' => 'First Coast Service Options, Inc. (FCSO)',
                'contractor_short' => 'FCSO',
                'headquarters' => 'Jacksonville, FL',
                'portal_keyword' => 'SPOT',
                'portal_url' => 'https://medicare.fcso.com',
                'states' => ['FL'],
                'territories' => ['PR', 'VI']
            ],
            'J-L' => [
                'contractor' => 'Novitas Solutions, Inc.',
                'contractor_short' => 'Novitas',
                'headquarters' => 'Mechanicsburg, PA',
                'portal_keyword' => 'Novitasphere',
                'portal_url' => 'https://www.novitas-solutions.com',
                'states' => ['DE', 'DC', 'MD', 'NJ', 'PA'],
                'territories' => []
            ],
            'J-K' => [
                'contractor' => 'National Government Services, Inc. (NGS)',
                'contractor_short' => 'NGS',
                'headquarters' => 'Indianapolis, IN',
                'portal_keyword' => 'NGSConnex',
                'portal_url' => 'https://www.ngsmedicare.com',
                'states' => ['CT', 'ME', 'MA', 'NH', 'NY', 'RI', 'VT'],
                'territories' => []
            ],
            'J-15' => [
                'contractor' => 'CGS Administrators, LLC',
                'contractor_short' => 'CGS',
                'headquarters' => 'Nashville, TN',
                'portal_keyword' => 'myCGS',
                'portal_url' => 'https://www.cgsmedicare.com',
                'states' => ['KY', 'OH'],
                'territories' => []
            ]
        ];

        $stateToMac = [];
        foreach ($cmsExpected as $macCode => $spec) {
            foreach (array_merge($spec['states'], $spec['territories']) as $loc) {
                $stateToMac[$loc] = $macCode;
            }
        }

        Assert::assertCount(56, $stateToMac, 'Must cover all 50 states + DC + 5 territories (56 total)');

        foreach ($stateToMac as $locCode => $expectedMac) {
            $res = getMacJurisdiction($locCode);
            Assert::assertNotNull($res, "Failed to resolve {$locCode}");
            Assert::assertEquals($expectedMac, $res['code'] ?? null, "Mismatch for {$locCode}");
            $spec = $cmsExpected[$expectedMac];
            Assert::assertEquals($spec['contractor'], $res['contractor'] ?? null, "Contractor mismatch for {$locCode}");
            Assert::assertEquals($spec['contractor_short'], $res['contractor_short'] ?? null, "Contractor short mismatch for {$locCode}");
            Assert::assertEquals($spec['headquarters'], $res['headquarters'] ?? null, "HQ mismatch for {$locCode}");
            Assert::assertEquals($spec['portal_url'], $res['portal_url'] ?? null, "Portal URL mismatch for {$locCode}");
            Assert::assertNotEmpty($res['medicaid_program'] ?? null, "Medicaid program missing for {$locCode}");
            Assert::assertNotEmpty($res['medicaid_agency'] ?? null, "Medicaid agency missing for {$locCode}");
            Assert::assertNotEmpty($res['medicare_timely_filing'] ?? null, "Medicare timely filing missing for {$locCode}");
            Assert::assertNotEmpty($res['medicaid_timely_filing'] ?? null, "Medicaid timely filing missing for {$locCode}");
            Assert::assertNotEmpty($res['appeals_deadline'] ?? null, "Appeals deadline missing for {$locCode}");
            Assert::assertGreaterThanOrEqual(1, count($res['key_lcds'] ?? []), "LCDs missing for {$locCode}");
            Assert::assertGreaterThanOrEqual(1, count($res['billing_nuances'] ?? []), "Nuances missing for {$locCode}");
            Assert::assertGreaterThanOrEqual(3, count($res['knows_about'] ?? []), "knows_about missing for {$locCode}");
        }
    });

    // =========================================================================
    // SECTION 2: ADVERSARIAL FUZZING OF getMacJurisdiction()
    // =========================================================================

    $suite->addTest('Adversarial Fuzzing: Whitespace Variations & Control Characters', 'Adversarial', function () {
        $whitespacePermutations = [
            "TX\t" => 'J-H', "\tTX" => 'J-H', "\nCA\r\n" => 'J-E', "  FL  " => 'J-N',
            "\r\n\tNY\t\r\n" => 'J-K', "   texas   " => 'J-H', "\tcalifornia\n" => 'J-E',
            "  district-of-columbia  " => 'J-L', "  puerto-rico  " => 'J-N',
            "\t\n\r" => null, "   " => null, "" => null
        ];
        foreach ($whitespacePermutations as $input => $expectedMac) {
            $res = getMacJurisdiction((string)$input);
            if ($expectedMac === null) {
                Assert::assertNull($res, "Expected null for whitespace input: " . var_export($input, true));
            } else {
                Assert::assertNotNull($res, "Expected successful lookup for input: " . var_export($input, true));
                Assert::assertEquals($expectedMac, $res['code'] ?? null);
            }
        }
    });

    $suite->addTest('Adversarial Fuzzing: Unicode Homoglyphs, Emojis, Non-ASCII', 'Adversarial', function () {
        $unicodeInputs = [
            "ТХ" => null, "СА" => null, "NУ" => null,
            "🏥 🩺 🇺🇸" => null, "🔥 🗽" => null,
            "Téxas" => null, "Càlifornia" => null, "Püerto-Rico" => null,
            "德克萨斯" => null, "カリフォルニア" => null, "تكساس" => null,
            "T\xE2\x80\x8BX" => null, "\xE2\x80\x8E TX" => null
        ];
        foreach ($unicodeInputs as $input => $expected) {
            $res = getMacJurisdiction((string)$input);
            Assert::assertNull($res, "Expected null for non-ASCII input: " . var_export($input, true));
        }
    });

    $suite->addTest('Adversarial Fuzzing: SQL Injection Payloads', 'Adversarial', function () {
        $sqliPayloads = [
            "' OR '1'='1", "' OR 1=1 --", "TX' OR '1'='1", "CA'; DROP TABLE states; --",
            "NY' UNION SELECT id, name, slug FROM states --", "' OR ''='", "admin'--",
            "1' ORDER BY 1--", "1' AND SLEEP(1)--", "TX'; SHUTDOWN;--", "\" OR \"\"=\"",
            "` OR 1=1 #", "' OR 'x'='x", "1' WAITFOR DELAY '0:0:1'--"
        ];
        foreach ($sqliPayloads as $sqli) {
            $res = getMacJurisdiction($sqli);
            Assert::assertNull($res, "SQL injection string must return null: " . var_export($sqli, true));
        }
    });

    $suite->addTest('Adversarial Fuzzing: Shell Injection, Path Traversal & Payloads', 'Adversarial', function () {
        $adversarialPayloads = [
            "../../../../etc/passwd", "..\\..\\..\\windows\\win.ini", "| whoami",
            "; cat /etc/passwd", "$(id)", "`uname -a`", "<script>alert(1)</script>",
            "<img src=x onerror=alert(1)>", "javascript:alert(1)", "null\0byte.php",
            "TX\x00extra", "CA%00", "<?php phpinfo(); ?>"
        ];
        foreach ($adversarialPayloads as $payload) {
            $res = getMacJurisdiction($payload);
            Assert::assertNull($res, "Adversarial payload must return null: " . var_export($payload, true));
        }
    });

    $suite->addTest('Adversarial Fuzzing: Boundary Values, Extreme Length & Numbers', 'Adversarial', function () {
        Assert::assertNull(getMacJurisdiction(""));
        foreach (range('A', 'Z') as $c) {
            Assert::assertNull(getMacJurisdiction($c));
            Assert::assertNull(getMacJurisdiction(strtolower($c)));
        }
        foreach (['USA', 'CAN', 'MEX', 'TEX', 'CAL', 'FLA', 'NYC', 'LOS'] as $tc) {
            Assert::assertNull(getMacJurisdiction($tc));
        }
        $tenK = str_repeat('A', 10000);
        Assert::assertNull(getMacJurisdiction($tenK));
        $longSlug = str_repeat('texas-', 1000) . 'texas';
        Assert::assertNull(getMacJurisdiction($longSlug));
        foreach (['TX1', '1TX', 'TEXAS1', 'texas-state', 'state-of-texas'] as $invalid) {
            Assert::assertNull(getMacJurisdiction($invalid));
        }
        foreach (['0', '1', '42', '-1', '0.0', '1e5', '0x1F', 'true', 'false', 'null'] as $numStr) {
            Assert::assertNull(getMacJurisdiction($numStr));
        }
    });

    $suite->addTest('Adversarial Fuzzing: Fictional & Military/Outlying Regions', 'Adversarial', function () {
        $nonExistent = [
            'atlantis', 'narnia', 'gotham', 'wakanda', 'middle-earth', 'mordor', 'metropolis',
            'UK', 'GB', 'ON', 'BC', 'QC', 'MX', 'EU', 'AU', 'IND', 'JPN', 'DEU', 'FRA',
            'AA', 'AE', 'AP', 'FM', 'MH', 'PW', 'UM', 'ZZ', 'XX', '99', '00', 'US', 'USA'
        ];
        foreach ($nonExistent as $reg) {
            $res = getMacJurisdiction($reg);
            Assert::assertNull($res, "Non-existent region must return null: " . var_export($reg, true));
        }
    });

    // =========================================================================
    // SECTION 3: DEEP CLINICAL & REGULATORY EDGE CASES
    // =========================================================================

    $suite->addTest('Edge Case 1: Maryland All-Payer Model & Novitas J-L Specifics', 'Adversarial', function () {
        $md = getMacJurisdiction('MD');
        Assert::assertNotNull($md);
        Assert::assertEquals('J-L', $md['code']);
        Assert::assertEquals('Novitas Solutions, Inc.', $md['contractor']);
        Assert::assertEquals('Maryland Department of Health (Maryland Medicaid)', $md['medicaid_program']);

        $hasAllPayer = false;
        foreach ($md['billing_nuances'] as $n) {
            if (stripos($n, 'Maryland') !== false && (stripos($n, 'All-Payer') !== false || stripos($n, 'Total Cost of Care') !== false || stripos($n, 'TCOC') !== false)) {
                $hasAllPayer = true;
            }
        }
        Assert::assertTrue($hasAllPayer, 'Maryland profile must include Maryland All-Payer / TCOC model nuance');
    });

    $suite->addTest('Edge Case 2: California Multi-Locality GPCI & Noridian J-E Specifics', 'Adversarial', function () {
        $ca = getMacJurisdiction('CA');
        Assert::assertNotNull($ca);
        Assert::assertEquals('J-E', $ca['code']);
        Assert::assertEquals('Noridian Healthcare Solutions, LLC', $ca['contractor']);
        Assert::assertEquals('California Department of Health Care Services (Medi-Cal)', $ca['medicaid_program']);

        $hasGPCI = false;
        $hasMediCal = false;
        foreach ($ca['billing_nuances'] as $n) {
            if (stripos($n, 'California Multi-Locality') !== false || stripos($n, 'GPCI') !== false) $hasGPCI = true;
            if (stripos($n, 'Medi-Cal') !== false) $hasMediCal = true;
        }
        Assert::assertTrue($hasGPCI, 'California profile must document Multi-Locality GPCI pricing');
        Assert::assertTrue($hasMediCal, 'California profile must document Medi-Cal crossover rules');
    });

    $suite->addTest('Edge Case 3: Alaska Frontier Floor & Noridian J-F Specifics', 'Adversarial', function () {
        $ak = getMacJurisdiction('AK');
        Assert::assertNotNull($ak);
        Assert::assertEquals('J-F', $ak['code']);
        Assert::assertEquals('Noridian Healthcare Solutions, LLC', $ak['contractor']);

        $hasFloor = false;
        foreach ($ak['billing_nuances'] as $n) {
            if (stripos($n, 'Alaska GPCI Frontier Floor') !== false || stripos($n, 'Frontier Floor') !== false) {
                $hasFloor = true;
            }
        }
        Assert::assertTrue($hasFloor, 'Alaska profile must include GPCI Frontier Floor nuance (ACA 10324)');
    });

    $suite->addTest('Edge Case 4: Michigan Auto No-Fault PIP Parity & WPS J-8 Specifics', 'Adversarial', function () {
        $mi = getMacJurisdiction('MI');
        Assert::assertNotNull($mi);
        Assert::assertEquals('J-8', $mi['code']);
        Assert::assertEquals('Wisconsin Physicians Service (WPS GHA)', $mi['contractor']);

        $hasPip = false;
        foreach ($mi['billing_nuances'] as $n) {
            if (stripos($n, 'Michigan Auto No-Fault') !== false || stripos($n, 'PIP') !== false) {
                $hasPip = true;
            }
        }
        Assert::assertTrue($hasPip, 'Michigan profile must document Auto No-Fault PIP fee schedule parity');
    });

    $suite->addTest('Edge Case 5: Caribbean (PR, VI) & Pacific Territories (GU, AS, MP)', 'Adversarial', function () {
        $pr = getMacJurisdiction('PR');
        $vi = getMacJurisdiction('VI');
        Assert::assertEquals('J-N', $pr['code']);
        Assert::assertEquals('J-N', $vi['code']);
        Assert::assertEquals('First Coast Service Options, Inc. (FCSO)', $pr['contractor']);

        $gu = getMacJurisdiction('GU');
        $as = getMacJurisdiction('AS');
        $mp = getMacJurisdiction('MP');
        Assert::assertEquals('J-E', $gu['code']);
        Assert::assertEquals('J-E', $as['code']);
        Assert::assertEquals('J-E', $mp['code']);
        Assert::assertEquals('Noridian Healthcare Solutions, LLC', $gu['contractor']);
    });

    // =========================================================================
    // SECTION 4: LOCATION RENDERING ENGINE & HTML / SCHEMA ADVERSARIAL TESTS
    // =========================================================================

    $suite->addTest('Location Engine: HTML Render of 12 Diverse State Hubs', 'Adversarial', function () {
        $statesToTest = [
            'texas' => ['code' => 'J-H', 'contractor' => 'Novitas', 'portal' => 'Novitasphere'],
            'california' => ['code' => 'J-E', 'contractor' => 'Noridian', 'portal' => 'Noridian Medicare Portal'],
            'florida' => ['code' => 'J-N', 'contractor' => 'First Coast', 'portal' => 'SPOT'],
            'new-york' => ['code' => 'J-K', 'contractor' => 'National Government Services', 'portal' => 'NGSConnex'],
            'ohio' => ['code' => 'J-15', 'contractor' => 'CGS Administrators', 'portal' => 'myCGS'],
            'illinois' => ['code' => 'J-6', 'contractor' => 'National Government Services', 'portal' => 'NGSConnex'],
            'michigan' => ['code' => 'J-8', 'contractor' => 'Wisconsin Physicians Service', 'portal' => 'WPS GHA'],
            'north-carolina' => ['code' => 'J-M', 'contractor' => 'Palmetto GBA', 'portal' => 'Palmetto GBA eServices'],
            'georgia' => ['code' => 'J-J', 'contractor' => 'Palmetto GBA', 'portal' => 'Palmetto GBA eServices'],
            'missouri' => ['code' => 'J-5', 'contractor' => 'Wisconsin Physicians Service', 'portal' => 'WPS GHA Secure Provider Portal'],
            'washington' => ['code' => 'J-F', 'contractor' => 'Noridian Healthcare Solutions', 'portal' => 'Noridian Medicare Portal'],
            'maryland' => ['code' => 'J-L', 'contractor' => 'Novitas Solutions', 'portal' => 'Novitasphere']
        ];

        foreach ($statesToTest as $stateSlug => $expected) {
            $res = renderProgrammaticPage($stateSlug);
            Assert::assertEquals(200, $res['statusCode'], "State page {$stateSlug} must return HTTP 200");
            Assert::assertNotEmpty($res['html'], "Rendered HTML for state {$stateSlug} must not be empty");
            Assert::assertGreaterThanOrEqual(5000, strlen($res['html']), "Rendered HTML for state {$stateSlug} must be >= 5KB");
            Assert::assertStringContains($expected['code'], $res['html'], "HTML for state {$stateSlug} must contain MAC code {$expected['code']}");
            Assert::assertStringContainsIgnoreCase($expected['contractor'], $res['html'], "HTML for state {$stateSlug} must contain contractor {$expected['contractor']}");
            Assert::assertStringContainsIgnoreCase($expected['portal'], $res['html'], "HTML for state {$stateSlug} must contain portal {$expected['portal']}");
            Assert::assertStringContains('Statewide Regulatory &amp; MAC Compliance Hub', $res['html'], "HTML for state {$stateSlug} must contain Regulatory & MAC Hub card");
        }
    });

    $suite->addTest('Location Engine: HTML Render of 12 Diverse City Landing Pages with Schema', 'Adversarial', function () {
        $citiesToTest = [
            ['state' => 'texas', 'city' => 'houston', 'mac' => 'J-H', 'contractor' => 'Novitas'],
            ['state' => 'california', 'city' => 'los-angeles', 'mac' => 'J-E', 'contractor' => 'Noridian'],
            ['state' => 'florida', 'city' => 'miami', 'mac' => 'J-N', 'contractor' => 'First Coast'],
            ['state' => 'new-york', 'city' => 'new-york', 'mac' => 'J-K', 'contractor' => 'National Government Services'],
            ['state' => 'ohio', 'city' => 'columbus', 'mac' => 'J-15', 'contractor' => 'CGS'],
            ['state' => 'illinois', 'city' => 'chicago', 'mac' => 'J-6', 'contractor' => 'NGS'],
            ['state' => 'michigan', 'city' => 'detroit', 'mac' => 'J-8', 'contractor' => 'Wisconsin Physicians Service'],
            ['state' => 'north-carolina', 'city' => 'charlotte', 'mac' => 'J-M', 'contractor' => 'Palmetto'],
            ['state' => 'georgia', 'city' => 'atlanta', 'mac' => 'J-J', 'contractor' => 'Palmetto'],
            ['state' => 'missouri', 'city' => 'kansas-city', 'mac' => 'J-5', 'contractor' => 'WPS'],
            ['state' => 'washington', 'city' => 'seattle', 'mac' => 'J-F', 'contractor' => 'Noridian'],
            ['state' => 'pennsylvania', 'city' => 'philadelphia', 'mac' => 'J-L', 'contractor' => 'Novitas']
        ];

        foreach ($citiesToTest as $item) {
            $res = renderProgrammaticPage($item['state'], $item['city']);
            Assert::assertEquals(200, $res['statusCode'], "City page {$item['city']} must return HTTP 200");
            Assert::assertNotEmpty($res['html']);
            Assert::assertGreaterThanOrEqual(8000, strlen($res['html']));
            Assert::assertStringContains($item['mac'], $res['html']);
            Assert::assertStringContainsIgnoreCase($item['contractor'], $res['html']);
            Assert::assertStringContains('Regional MAC &amp; State Payer Compliance Hub', $res['html']);

            Assert::assertNotNull($res['medicalBusiness'], "MedicalBusiness schema must be present for {$item['city']}");
            Assert::assertArrayHasKey('knowsAbout', $res['medicalBusiness']);
            Assert::assertIsArray($res['medicalBusiness']['knowsAbout']);
            Assert::assertGreaterThanOrEqual(3, count($res['medicalBusiness']['knowsAbout']));

            $knowsJoined = implode(' | ', $res['medicalBusiness']['knowsAbout']);
            Assert::assertTrue(
                stripos($knowsJoined, 'Medicare') !== false || stripos($knowsJoined, 'MAC') !== false || stripos($knowsJoined, $item['mac']) !== false,
                "knowsAbout for {$item['city']} must reference Medicare/MAC"
            );
        }
    });

    $suite->addTest('Location Engine: Adversarial XSS & Query Parameter Injection Safety', 'Adversarial', function () {
        $xssAttacks = [
            ['state' => '<script>alert("xss")</script>', 'city' => 'houston'],
            ['state' => 'texas', 'city' => '"><script>alert(document.cookie)</script>'],
            ['state' => 'california\' OR 1=1--', 'city' => 'los-angeles'],
            ['state' => '../../etc/passwd', 'city' => 'test'],
            ['state' => 'texas', 'city' => 'houston<img src=x onerror=alert(1)>']
        ];

        foreach ($xssAttacks as $attack) {
            $res = renderProgrammaticPage($attack['state'], $attack['city']);
            Assert::assertTrue(
                $res['statusCode'] === 404 || strpos($res['html'], '<script>alert(') === false,
                "XSS attack state=" . $attack['state'] . " must return 404 or not reflect unescaped script tag"
            );
            Assert::assertTrue(
                strpos($res['html'], '<script>alert(') === false,
                "XSS script payload must never be reflected unescaped"
            );
            Assert::assertTrue(
                strpos($res['html'], 'onerror=alert') === false,
                "XSS image payload must never be reflected unescaped"
            );
        }
    });

    // =========================================================================
    // SECTION 5: HIGH-LOAD STRESS & PERFORMANCE BENCHMARK
    // =========================================================================

    $suite->addTest('High-Load Stress: 100,000 Repeated MAC Lookups & Memory Profiling', 'Adversarial', function () {
        $sampleInputs = ['TX', 'california', 'FL', 'new-york', 'OH', 'IL', 'MI', 'NC', 'GA', 'MO', 'WA', 'PA', 'PR', 'VI', 'GU', 'AS', 'MP', 'DC'];
        $inputCount = count($sampleInputs);

        $memStart = memory_get_usage();
        $startTime = microtime(true);

        for ($i = 0; $i < 100000; $i++) {
            $input = $sampleInputs[$i % $inputCount];
            $res = getMacJurisdiction($input);
            if ($res === null) {
                Assert::fail("Failed lookup at iteration {$i} for input '{$input}'");
            }
        }

        $elapsedMs = (microtime(true) - $startTime) * 1000;
        $memDelta = (memory_get_usage() - $memStart) / 1024; // KB

        Assert::assertLessThanOrEqual(500.0, $elapsedMs, "100,000 lookups took {$elapsedMs}ms (expected < 500ms)");
        Assert::assertLessThanOrEqual(512.0, $memDelta, "Memory increased by {$memDelta}KB during 100,000 iterations (expected < 512KB)");
    });

    return $suite;
}

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getAdversarialMacSuite());
    exit($runner->runAll());
}
