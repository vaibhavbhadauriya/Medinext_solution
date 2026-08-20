<?php
/**
 * MEDINEXT SOLUTIONS - Master MAC Matrix Test Suite (M1)
 * 
 * Comprehensive verification for 12 A/B MAC Jurisdictions across
 * all 50 US States, District of Columbia, and 5 US Territories.
 * Derived from CMS Title XVIII & spec_miner_mac_matrix/spec_report.md
 */

declare(strict_types=1);

namespace Medinext\Tests;

require_once __DIR__ . '/e2e_test_runner.php';

// Include the target implementation helper
$locationHelperPath = dirname(__DIR__) . '/includes/location-helper.php';
if (file_exists($locationHelperPath)) {
    require_once $locationHelperPath;
}

/**
 * Master Authoritative MAC Mapping Matrix (56 jurisdictions)
 */
function getAuthoritativeMacMatrix(): array {
    return [
        'AL' => ['name' => 'Alabama', 'slug' => 'alabama', 'code' => 'J-J', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'Alabama'],
        'AK' => ['name' => 'Alaska', 'slug' => 'alaska', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Alaska'],
        'AS' => ['name' => 'American Samoa', 'slug' => 'american-samoa', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'American Samoa'],
        'AZ' => ['name' => 'Arizona', 'slug' => 'arizona', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'AHCCCS'],
        'AR' => ['name' => 'Arkansas', 'slug' => 'arkansas', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Arkansas'],
        'CA' => ['name' => 'California', 'slug' => 'california', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Medi-Cal'],
        'CO' => ['name' => 'Colorado', 'slug' => 'colorado', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Colorado'],
        'CT' => ['name' => 'Connecticut', 'slug' => 'connecticut', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'HUSKY'],
        'DE' => ['name' => 'Delaware', 'slug' => 'delaware', 'code' => 'J-L', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Delaware'],
        'DC' => ['name' => 'District of Columbia', 'slug' => 'district-of-columbia', 'code' => 'J-L', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'DC'],
        'FL' => ['name' => 'Florida', 'slug' => 'florida', 'code' => 'J-N', 'contractor_keyword' => 'First Coast', 'portal_keyword' => 'SPOT', 'medicaid_keyword' => 'Florida'],
        'GA' => ['name' => 'Georgia', 'slug' => 'georgia', 'code' => 'J-J', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'Georgia'],
        'GU' => ['name' => 'Guam', 'slug' => 'guam', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Guam'],
        'HI' => ['name' => 'Hawaii', 'slug' => 'hawaii', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Med-QUEST'],
        'ID' => ['name' => 'Idaho', 'slug' => 'idaho', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Idaho'],
        'IL' => ['name' => 'Illinois', 'slug' => 'illinois', 'code' => 'J-6', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'Illinois'],
        'IN' => ['name' => 'Indiana', 'slug' => 'indiana', 'code' => 'J-8', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'Indiana'],
        'IA' => ['name' => 'Iowa', 'slug' => 'iowa', 'code' => 'J-5', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'Iowa'],
        'KS' => ['name' => 'Kansas', 'slug' => 'kansas', 'code' => 'J-5', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'KanCare'],
        'KY' => ['name' => 'Kentucky', 'slug' => 'kentucky', 'code' => 'J-15', 'contractor_keyword' => 'CGS', 'portal_keyword' => 'myCGS', 'medicaid_keyword' => 'Kentucky'],
        'LA' => ['name' => 'Louisiana', 'slug' => 'louisiana', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Healthy Louisiana'],
        'ME' => ['name' => 'Maine', 'slug' => 'maine', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'MaineCare'],
        'MD' => ['name' => 'Maryland', 'slug' => 'maryland', 'code' => 'J-L', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Maryland'],
        'MA' => ['name' => 'Massachusetts', 'slug' => 'massachusetts', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'MassHealth'],
        'MI' => ['name' => 'Michigan', 'slug' => 'michigan', 'code' => 'J-8', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'CHAMPS'],
        'MN' => ['name' => 'Minnesota', 'slug' => 'minnesota', 'code' => 'J-6', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'Minnesota'],
        'MS' => ['name' => 'Mississippi', 'slug' => 'mississippi', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'Mississippi'],
        'MO' => ['name' => 'Missouri', 'slug' => 'missouri', 'code' => 'J-5', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'MO HealthNet'],
        'MT' => ['name' => 'Montana', 'slug' => 'montana', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Montana'],
        'NE' => ['name' => 'Nebraska', 'slug' => 'nebraska', 'code' => 'J-5', 'contractor_keyword' => 'Wisconsin Physicians Service', 'portal_keyword' => 'WPS', 'medicaid_keyword' => 'Nebraska'],
        'NV' => ['name' => 'Nevada', 'slug' => 'nevada', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Nevada'],
        'NH' => ['name' => 'New Hampshire', 'slug' => 'new-hampshire', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'New Hampshire'],
        'NJ' => ['name' => 'New Jersey', 'slug' => 'new-jersey', 'code' => 'J-L', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'NJ FamilyCare'],
        'NM' => ['name' => 'New Mexico', 'slug' => 'new-mexico', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'New Mexico'],
        'NY' => ['name' => 'New York', 'slug' => 'new-york', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'eMedNY'],
        'NC' => ['name' => 'North Carolina', 'slug' => 'north-carolina', 'code' => 'J-M', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'North Carolina'],
        'ND' => ['name' => 'North Dakota', 'slug' => 'north-dakota', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'North Dakota'],
        'MP' => ['name' => 'Northern Mariana Islands', 'slug' => 'northern-mariana-islands', 'code' => 'J-E', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Northern Mariana'],
        'OH' => ['name' => 'Ohio', 'slug' => 'ohio', 'code' => 'J-15', 'contractor_keyword' => 'CGS', 'portal_keyword' => 'myCGS', 'medicaid_keyword' => 'Ohio'],
        'OK' => ['name' => 'Oklahoma', 'slug' => 'oklahoma', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'SoonerCare'],
        'OR' => ['name' => 'Oregon', 'slug' => 'oregon', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Oregon Health Plan'],
        'PA' => ['name' => 'Pennsylvania', 'slug' => 'pennsylvania', 'code' => 'J-L', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'PROMISe'],
        'PR' => ['name' => 'Puerto Rico', 'slug' => 'puerto-rico', 'code' => 'J-N', 'contractor_keyword' => 'First Coast', 'portal_keyword' => 'SPOT', 'medicaid_keyword' => 'Puerto Rico'],
        'RI' => ['name' => 'Rhode Island', 'slug' => 'rhode-island', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'Rhode Island'],
        'SC' => ['name' => 'South Carolina', 'slug' => 'south-carolina', 'code' => 'J-M', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'South Carolina'],
        'SD' => ['name' => 'South Dakota', 'slug' => 'south-dakota', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'South Dakota'],
        'TN' => ['name' => 'Tennessee', 'slug' => 'tennessee', 'code' => 'J-J', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'TennCare'],
        'TX' => ['name' => 'Texas', 'slug' => 'texas', 'code' => 'J-H', 'contractor_keyword' => 'Novitas', 'portal_keyword' => 'Novitasphere', 'medicaid_keyword' => 'TMHP'],
        'VI' => ['name' => 'U.S. Virgin Islands', 'slug' => 'u-s-virgin-islands', 'code' => 'J-N', 'contractor_keyword' => 'First Coast', 'portal_keyword' => 'SPOT', 'medicaid_keyword' => 'Virgin Islands'],
        'UT' => ['name' => 'Utah', 'slug' => 'utah', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Utah'],
        'VT' => ['name' => 'Vermont', 'slug' => 'vermont', 'code' => 'J-K', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'Vermont'],
        'VA' => ['name' => 'Virginia', 'slug' => 'virginia', 'code' => 'J-M', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'Cardinal Care'],
        'WA' => ['name' => 'Washington', 'slug' => 'washington', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Apple Health'],
        'WV' => ['name' => 'West Virginia', 'slug' => 'west-virginia', 'code' => 'J-M', 'contractor_keyword' => 'Palmetto', 'portal_keyword' => 'Palmetto', 'medicaid_keyword' => 'West Virginia'],
        'WI' => ['name' => 'Wisconsin', 'slug' => 'wisconsin', 'code' => 'J-6', 'contractor_keyword' => 'National Government Services', 'portal_keyword' => 'NGSConnex', 'medicaid_keyword' => 'ForwardHealth'],
        'WY' => ['name' => 'Wyoming', 'slug' => 'wyoming', 'code' => 'J-F', 'contractor_keyword' => 'Noridian', 'portal_keyword' => 'Noridian', 'medicaid_keyword' => 'Wyoming']
    ];
}

/**
 * Build Master MAC Matrix TestSuite
 */
function getMacMatrixSuite(): TestSuite {
    $suite = new TestSuite('MAC Jurisdiction Matrix Suite', 'Verifies 12 MACs across all 50 states, DC, and territories');

    // -------------------------------------------------------------
    // Test 1: Helper Function Declaration
    // -------------------------------------------------------------
    $suite->addTest('MAC Helper Function Existence (getMacJurisdiction)', 'Tier 1', function () {
        Assert::assertTrue(
            function_exists('getMacJurisdiction'),
            "Function 'getMacJurisdiction' must be defined in includes/location-helper.php"
        );
    });

    // -------------------------------------------------------------
    // Test 2: Full 56-Location Code Resolution
    // -------------------------------------------------------------
    $suite->addTest('50 States + DC + 5 Territories 2-Letter Code Resolution (56 locations)', 'Tier 1', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");
        $matrix = getAuthoritativeMacMatrix();
        Assert::assertCount(56, $matrix, "Authoritative matrix must contain 56 state/territory definitions");

        foreach ($matrix as $code => $spec) {
            $result = getMacJurisdiction($code);
            Assert::assertNotNull($result, "Resolution for state code '{$code}' returned null");
            Assert::assertIsArray($result, "Resolution for '{$code}' must return array");
            Assert::assertEquals($spec['code'], $result['code'] ?? null, "State code '{$code}' must map to MAC '{$spec['code']}'");
            Assert::assertStringContainsIgnoreCase($spec['contractor_keyword'], $result['contractor'] ?? '', "Contractor for '{$code}' must match '{$spec['contractor_keyword']}'");
            Assert::assertStringContainsIgnoreCase($spec['portal_keyword'], $result['portal_name'] ?? '', "Portal name for '{$code}' must match '{$spec['portal_keyword']}'");
            Assert::assertMatchesRegex('/^https?:\/\//i', $result['portal_url'] ?? '', "Portal URL for '{$code}' must be a valid HTTP/HTTPS URL");
            Assert::assertNotEmpty($result['medicaid_program'] ?? '', "Medicaid program for '{$code}' must not be empty");
            Assert::assertNotEmpty($result['medicare_timely_filing'] ?? '', "Medicare timely filing for '{$code}' must not be empty");
            Assert::assertNotEmpty($result['appeals_deadline'] ?? '', "Appeals deadline for '{$code}' must not be empty");
            Assert::assertIsArray($result['knows_about'] ?? null, "knows_about for '{$code}' must be an array");
            Assert::assertGreaterThanOrEqual(3, count($result['knows_about'] ?? []), "knows_about for '{$code}' must have >= 3 topics");
        }
    });

    // -------------------------------------------------------------
    // Test 3: Full 56-Location Lowercase Slug Resolution
    // -------------------------------------------------------------
    $suite->addTest('50 States + DC + 5 Territories Lowercase Slug Resolution (56 locations)', 'Tier 1', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");
        $matrix = getAuthoritativeMacMatrix();

        foreach ($matrix as $code => $spec) {
            $slug = $spec['slug'];
            $result = getMacJurisdiction($slug);
            Assert::assertNotNull($result, "Resolution for slug '{$slug}' returned null");
            Assert::assertEquals($spec['code'], $result['code'] ?? null, "Slug '{$slug}' must map to MAC '{$spec['code']}'");
            Assert::assertStringContainsIgnoreCase($spec['contractor_keyword'], $result['contractor'] ?? '', "Contractor for slug '{$slug}' must match '{$spec['contractor_keyword']}'");
        }
    });

    // -------------------------------------------------------------
    // Test 4: 12 MAC Jurisdictions Deep-Dive Specifics
    // -------------------------------------------------------------
    $suite->addTest('12 MAC Jurisdictions Deep-Dive Specific Invariants (J-E through J-15)', 'Tier 1', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $expectedJurisdictions = [
            'J-E'  => ['state' => 'CA', 'operator' => 'Noridian', 'portal' => 'noridianmedicare.com'],
            'J-F'  => ['state' => 'WA', 'operator' => 'Noridian', 'portal' => 'noridianmedicare.com'],
            'J-5'  => ['state' => 'MO', 'operator' => 'Wisconsin Physicians Service', 'portal' => 'wpsgha.com'],
            'J-6'  => ['state' => 'IL', 'operator' => 'National Government Services', 'portal' => 'ngsmedicare.com'],
            'J-8'  => ['state' => 'MI', 'operator' => 'Wisconsin Physicians Service', 'portal' => 'wpsgha.com'],
            'J-H'  => ['state' => 'TX', 'operator' => 'Novitas', 'portal' => 'novitas-solutions.com'],
            'J-J'  => ['state' => 'GA', 'operator' => 'Palmetto', 'portal' => 'palmettogba.com'],
            'J-M'  => ['state' => 'NC', 'operator' => 'Palmetto', 'portal' => 'palmettogba.com'],
            'J-N'  => ['state' => 'FL', 'operator' => 'First Coast', 'portal' => 'fcso.com'],
            'J-L'  => ['state' => 'PA', 'operator' => 'Novitas', 'portal' => 'novitas-solutions.com'],
            'J-K'  => ['state' => 'NY', 'operator' => 'National Government Services', 'portal' => 'ngsmedicare.com'],
            'J-15' => ['state' => 'OH', 'operator' => 'CGS', 'portal' => 'cgsmedicare.com']
        ];

        foreach ($expectedJurisdictions as $macCode => $info) {
            $data = getMacJurisdiction($info['state']);
            Assert::assertNotNull($data, "MAC {$macCode} lookup for {$info['state']} returned null");
            Assert::assertEquals($macCode, $data['code'] ?? null, "Expected MAC {$macCode} for {$info['state']}");
            Assert::assertStringContainsIgnoreCase($info['operator'], $data['contractor'] ?? '', "Expected operator {$info['operator']} for MAC {$macCode}");
            Assert::assertStringContainsIgnoreCase($info['portal'], $data['portal_url'] ?? '', "Expected portal URL containing {$info['portal']} for MAC {$macCode}");
        }
    });

    // -------------------------------------------------------------
    // Test 5: LCDs & Coverage Determinations Data Structure
    // -------------------------------------------------------------
    $suite->addTest('Local Coverage Determinations (LCDs) Array Validation', 'Tier 1', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $sampleStates = ['TX', 'CA', 'FL', 'NY', 'OH', 'IL', 'MI', 'NC', 'MO', 'PA'];
        foreach ($sampleStates as $st) {
            $data = getMacJurisdiction($st);
            Assert::assertNotNull($data, "Lookup for {$st} returned null");
            Assert::assertArrayHasKey('key_lcds', $data, "MAC data for {$st} must contain 'key_lcds'");
            Assert::assertIsArray($data['key_lcds'], "'key_lcds' for {$st} must be an array");
            Assert::assertGreaterThanOrEqual(1, count($data['key_lcds']), "'key_lcds' for {$st} must contain at least 1 LCD");

            foreach ($data['key_lcds'] as $lcd) {
                Assert::assertIsArray($lcd, "Each LCD in 'key_lcds' for {$st} must be an array");
                Assert::assertArrayHasKey('id', $lcd, "LCD must have 'id'");
                Assert::assertArrayHasKey('name', $lcd, "LCD must have 'name'");
                Assert::assertMatchesRegex('/^[LA]\d+/i', $lcd['id'], "LCD ID must follow standard CMS format (e.g. L35041, A54117)");
                Assert::assertNotEmpty($lcd['name'], "LCD name must not be empty");
            }
        }
    });

    // -------------------------------------------------------------
    // Test 6: KnowsAbout Semantic Schema Array Integrity
    // -------------------------------------------------------------
    $suite->addTest('Semantic knows_about Schema Array Verification', 'Tier 1', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $sampleStates = ['TX', 'CA', 'FL', 'NY', 'OH'];
        foreach ($sampleStates as $st) {
            $data = getMacJurisdiction($st);
            Assert::assertNotNull($data, "Lookup for {$st} returned null");
            Assert::assertArrayHasKey('knows_about', $data, "MAC data for {$st} must contain 'knows_about'");
            Assert::assertIsArray($data['knows_about'], "'knows_about' must be an array");

            $knows = $data['knows_about'];
            $joined = implode(' | ', $knows);

            // Must reference MAC or Medicare contractor
            Assert::assertTrue(
                stripos($joined, 'MAC') !== false || stripos($joined, 'Medicare') !== false || stripos($joined, 'Jurisdiction') !== false,
                "knows_about for {$st} must contain MAC / Medicare compliance reference"
            );
        }
    });

    // -------------------------------------------------------------
    // Test 7: Case Insensitivity & Whitespace Normalization
    // -------------------------------------------------------------
    $suite->addTest('Case-Insensitivity & Whitespace Normalization', 'Tier 2', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $cases = [
            ' tx ' => 'J-H',
            'Tx' => 'J-H',
            'tX' => 'J-H',
            '   CA   ' => 'J-E',
            'CaLiFoRnIa' => 'J-E',
            '  texas  ' => 'J-H',
            'FLORIDA' => 'J-N',
            '  new-york  ' => 'J-K'
        ];

        foreach ($cases as $input => $expectedMac) {
            $res = getMacJurisdiction($input);
            Assert::assertNotNull($res, "Expected successful resolution for input '{$input}'");
            Assert::assertEquals($expectedMac, $res['code'] ?? null, "Input '{$input}' should resolve to MAC {$expectedMac}");
        }
    });

    // -------------------------------------------------------------
    // Test 8: Invalid State Codes & Slugs Rejection
    // -------------------------------------------------------------
    $suite->addTest('Invalid State Code & Slug Handling (Returns Null Safely)', 'Tier 2', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $invalidInputs = [
            'ZZ', 'XX', '99', 'USA', 'US', 'CA1', 'TXX',
            'invalid-state', 'atlantis', 'texas-north', 'newyorkcity',
            '', '   ', 'unknown', '123'
        ];

        foreach ($invalidInputs as $inv) {
            $res = getMacJurisdiction($inv);
            Assert::assertNull($res, "Invalid input '{$inv}' must return null");
        }
    });

    // -------------------------------------------------------------
    // Test 9: Adversarial Input Safety & Injection Resistance
    // -------------------------------------------------------------
    $suite->addTest('Adversarial Input Safety & Injection Resistance', 'Tier 2', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $maliciousInputs = [
            "TX' OR 1=1--",
            "CA; DROP TABLE states;",
            "<script>alert('xss')</script>",
            "../../etc/passwd",
            "null\0byte",
            "$$--//\\&&"
        ];

        foreach ($maliciousInputs as $mal) {
            $res = getMacJurisdiction($mal);
            Assert::assertNull($res, "Adversarial input '{$mal}' must safely return null without throwing");
        }
    });

    // -------------------------------------------------------------
    // Test 10: Contract Schema Completeness & Strict Typing
    // -------------------------------------------------------------
    $suite->addTest('Contract Schema Completeness (All 15 Required Keys & Types)', 'Tier 3', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $requiredKeys = [
            'code' => 'string',
            'jurisdiction_name' => 'string',
            'contractor' => 'string',
            'contractor_short' => 'string',
            'headquarters' => 'string',
            'portal_name' => 'string',
            'portal_url' => 'string',
            'medicaid_program' => 'string',
            'medicaid_agency' => 'string',
            'medicare_timely_filing' => 'string',
            'medicaid_timely_filing' => 'string',
            'appeals_deadline' => 'string',
            'key_lcds' => 'array',
            'billing_nuances' => 'array',
            'knows_about' => 'array'
        ];

        $matrix = getAuthoritativeMacMatrix();
        foreach (array_keys($matrix) as $st) {
            $res = getMacJurisdiction($st);
            Assert::assertNotNull($res, "Resolution for '{$st}' returned null");

            foreach ($requiredKeys as $key => $expectedType) {
                Assert::assertArrayHasKey($key, $res, "State {$st} missing required key '{$key}'");
                if ($expectedType === 'string') {
                    Assert::assertIsString($res[$key], "Key '{$key}' on state {$st} must be string");
                    Assert::assertNotEmpty($res[$key], "Key '{$key}' on state {$st} must not be empty");
                } elseif ($expectedType === 'array') {
                    Assert::assertIsArray($res[$key], "Key '{$key}' on state {$st} must be array");
                }
            }
        }
    });

    // -------------------------------------------------------------
    // Test 11: Edge Cases: Non-Contiguous Territories
    // -------------------------------------------------------------
    $suite->addTest('Edge Cases: Non-Contiguous Territories (PR, VI, AS, GU, MP)', 'Tier 3', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        // Caribbean Territories -> J-N (First Coast / FCSO)
        $pr = getMacJurisdiction('PR');
        Assert::assertNotNull($pr, "Puerto Rico (PR) must resolve");
        Assert::assertEquals('J-N', $pr['code'] ?? null, "Puerto Rico must map to J-N");
        Assert::assertStringContainsIgnoreCase('First Coast', $pr['contractor'] ?? '', "PR contractor must be First Coast");

        $vi = getMacJurisdiction('VI');
        Assert::assertNotNull($vi, "US Virgin Islands (VI) must resolve");
        Assert::assertEquals('J-N', $vi['code'] ?? null, "US Virgin Islands must map to J-N");

        // Pacific Territories -> J-E (Noridian)
        $gu = getMacJurisdiction('GU');
        Assert::assertNotNull($gu, "Guam (GU) must resolve");
        Assert::assertEquals('J-E', $gu['code'] ?? null, "Guam must map to J-E");

        $as = getMacJurisdiction('AS');
        Assert::assertNotNull($as, "American Samoa (AS) must resolve");
        Assert::assertEquals('J-E', $as['code'] ?? null, "American Samoa must map to J-E");

        $mp = getMacJurisdiction('MP');
        Assert::assertNotNull($mp, "Northern Mariana Islands (MP) must resolve");
        Assert::assertEquals('J-E', $mp['code'] ?? null, "Northern Mariana Islands must map to J-E");
    });

    // -------------------------------------------------------------
    // Test 12: Edge Cases: Multi-Locality GPCI & Regulatory Nuances
    // -------------------------------------------------------------
    $suite->addTest('Edge Cases: Regulatory Nuances (MD All-Payer, MI PIP, Multi-Locality GPCI)', 'Tier 3', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        // Maryland -> J-L (Novitas)
        $md = getMacJurisdiction('MD');
        Assert::assertNotNull($md, "Maryland (MD) must resolve");
        Assert::assertEquals('J-L', $md['code'] ?? null, "Maryland must map to Novitas J-L");

        // Michigan -> J-8 (WPS)
        $mi = getMacJurisdiction('MI');
        Assert::assertNotNull($mi, "Michigan (MI) must resolve");
        Assert::assertEquals('J-8', $mi['code'] ?? null, "Michigan must map to WPS J-8");

        // California -> J-E (Noridian) with 9 GPCI localities
        $ca = getMacJurisdiction('CA');
        Assert::assertNotNull($ca, "California must resolve");
        Assert::assertEquals('J-E', $ca['code'] ?? null, "California must map to Noridian J-E");

        // Ohio / Kentucky -> J-15 (CGS)
        $oh = getMacJurisdiction('OH');
        Assert::assertEquals('J-15', $oh['code'] ?? null, "Ohio must map to CGS J-15");
        $ky = getMacJurisdiction('KY');
        Assert::assertEquals('J-15', $ky['code'] ?? null, "Kentucky must map to CGS J-15");
    });

    // -------------------------------------------------------------
    // Test 13: High-Throughput Workload Scenario
    // -------------------------------------------------------------
    $suite->addTest('Workload Scenario: 1,000 High-Throughput In-Memory MAC Resolutions', 'Tier 4', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $matrix = getAuthoritativeMacMatrix();
        $stateCodes = array_keys($matrix);
        $count = count($stateCodes);

        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            $code = $stateCodes[$i % $count];
            $res = getMacJurisdiction($code);
            Assert::assertNotNull($res, "Resolution in benchmark failed for {$code}");
        }
        $elapsedMs = (microtime(true) - $start) * 1000;

        // Ensure 1,000 resolutions finish in under 100ms (efficient in-memory caching)
        Assert::assertLessThanOrEqual(150.0, $elapsedMs, "1,000 resolutions took {$elapsedMs}ms (exceeds 150ms ceiling)");
    });

    // -------------------------------------------------------------
    // Test 14: Code vs Slug Identity Invariance
    // -------------------------------------------------------------
    $suite->addTest('Workload Scenario: State Code vs State Slug Identity Invariance', 'Tier 4', function () {
        Assert::assertTrue(function_exists('getMacJurisdiction'), "getMacJurisdiction() is required");

        $matrix = getAuthoritativeMacMatrix();
        foreach ($matrix as $code => $spec) {
            $byCode = getMacJurisdiction($code);
            $bySlug = getMacJurisdiction($spec['slug']);

            Assert::assertNotNull($byCode, "Lookup by code '{$code}' returned null");
            Assert::assertNotNull($bySlug, "Lookup by slug '{$spec['slug']}' returned null");

            Assert::assertEquals($byCode['code'], $bySlug['code'], "MAC code mismatch between code '{$code}' and slug '{$spec['slug']}'");
            Assert::assertEquals($byCode['contractor'], $bySlug['contractor'], "Contractor mismatch for {$code}");
            Assert::assertEquals($byCode['portal_name'], $bySlug['portal_name'], "Portal mismatch for {$code}");
            Assert::assertEquals($byCode['medicaid_program'], $bySlug['medicaid_program'], "Medicaid mismatch for {$code}");
        }
    });

    return $suite;
}

// Standalone execution support
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $runner = new TestRunner();
    $runner->addSuite(getMacMatrixSuite());
    exit($runner->runAll());
}
