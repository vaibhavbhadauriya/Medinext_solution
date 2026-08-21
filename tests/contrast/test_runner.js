/**
 * MEDINEXT SOLUTIONS - Master Color Contrast & Accessibility Test Runner
 * 
 * Executes Tier 1 (Feature Coverage), Tier 2 (Boundary & Corner Cases),
 * Tier 3 (Cross-Feature Combinations), and Tier 4 (Real-World Workloads).
 * 
 * Usage:
 *   node tests/contrast/test_runner.js
 */

'use strict';

const { runTier1Tests } = require('./tier1_feature_tests');
const { runTier2Tests } = require('./tier2_boundary_tests');
const { runTier3Tests } = require('./tier3_pairwise_tests');
const { runTier4Tests } = require('./tier4_workload_tests');
const { runTier5Tests } = require('./tier5_adversarial_tests');

// ANSI Color formatting
const colors = {
    reset: '\x1b[0m',
    green: '\x1b[32m',
    red: '\x1b[31m',
    yellow: '\x1b[33m',
    blue: '\x1b[34m',
    magenta: '\x1b[35m',
    cyan: '\x1b[36m',
    white: '\x1b[37m',
    bold: '\x1b[1m',
    dim: '\x1b[2m'
};

class TestReporter {
    constructor() {
        this.currentTier = '';
        this.totalTests = 0;
        this.passedTests = 0;
        this.failedTests = 0;
        this.totalAssertions = 0;
        this.passedAssertions = 0;
        this.failedAssertions = 0;
        this.failures = [];
        this.tierStats = {};
        this.startTime = Date.now();
        this.currentTestAssertions = 0;
        this.currentTestPassed = true;
    }

    startTier(tierName) {
        this.currentTier = tierName;
        this.tierStats[tierName] = { total: 0, passed: 0, failed: 0, assertions: 0 };
        console.log(`\n${colors.bold}${colors.cyan}======================================================================${colors.reset}`);
        console.log(`${colors.bold}${colors.cyan} ${tierName}${colors.reset}`);
        console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}`);
    }

    test(testName, featureGroup, fn) {
        this.totalTests++;
        this.tierStats[this.currentTier].total++;
        this.currentTestAssertions = 0;
        this.currentTestPassed = true;

        try {
            fn();
            if (this.currentTestPassed) {
                this.passedTests++;
                this.tierStats[this.currentTier].passed++;
                console.log(`  ${colors.green}✓ PASS${colors.reset} [${colors.dim}${featureGroup}${colors.reset}]: ${testName} ${colors.dim}(${this.currentTestAssertions} assertions)${colors.reset}`);
            }
        } catch (err) {
            this.failedTests++;
            this.tierStats[this.currentTier].failed++;
            console.log(`  ${colors.red}✗ FAIL${colors.reset} [${colors.bold}${featureGroup}${colors.reset}]: ${testName}`);
            console.log(`    ${colors.red}Error: ${err.message}${colors.reset}`);
            this.failures.push({
                tier: this.currentTier,
                feature: featureGroup,
                testName,
                error: err.message,
                stack: err.stack
            });
        }
    }

    assert(condition, message) {
        this.totalAssertions++;
        this.tierStats[this.currentTier].assertions++;
        this.currentTestAssertions++;

        if (!condition) {
            this.currentTestPassed = false;
            this.failedAssertions++;
            throw new Error(message || 'Assertion failed');
        } else {
            this.passedAssertions++;
        }
    }

    summary() {
        const duration = ((Date.now() - this.startTime) / 1000).toFixed(3);
        console.log(`\n${colors.bold}${colors.cyan}======================================================================${colors.reset}`);
        console.log(`${colors.bold}${colors.cyan} TEST SUITE EXECUTION SUMMARY${colors.reset}`);
        console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}`);

        for (const [tier, stats] of Object.entries(this.tierStats)) {
            const statusColor = stats.failed === 0 ? colors.green : colors.red;
            console.log(`  ${colors.bold}${tier.padEnd(55)}:${colors.reset} ${statusColor}${stats.passed}/${stats.total} passed${colors.reset} (${stats.assertions} assertions)`);
        }

        console.log(`${colors.cyan}----------------------------------------------------------------------${colors.reset}`);
        console.log(`  ${colors.bold}Total Test Cases   :${colors.reset} ${this.totalTests}`);
        console.log(`  ${colors.bold}Total Assertions   :${colors.reset} ${this.totalAssertions}`);
        console.log(`  ${colors.bold}Passed Assertions  :${colors.reset} ${colors.green}${this.passedAssertions}${colors.reset}`);
        console.log(`  ${colors.bold}Failed Assertions  :${colors.reset} ${this.failedAssertions === 0 ? colors.green + '0' : colors.red + this.failedAssertions}${colors.reset}`);
        console.log(`  ${colors.bold}Execution Duration :${colors.reset} ${duration}s`);
        console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}`);

        if (this.failedTests === 0) {
            console.log(`\n  ${colors.bold}${colors.green}🎉 ALL CONTRAST & ACCESSIBILITY TESTS PASSED (100% WCAG AA/AAA COMPLIANT)${colors.reset}\n`);
            return 0;
        } else {
            console.log(`\n  ${colors.bold}${colors.red}❌ ${this.failedTests} TEST(S) FAILED. REVIEW DETAILS ABOVE.${colors.reset}\n`);
            return 1;
        }
    }
}

function main() {
    console.log(`${colors.bold}${colors.blue}======================================================================${colors.reset}`);
    console.log(`${colors.bold}${colors.blue} MEDINEXT SOLUTIONS — SITE-WIDE COLOR CONTRAST VERIFICATION SUITE${colors.reset}`);
    console.log(`${colors.bold}${colors.blue} Opaque-Box WCAG 2.1 AA / AAA Multi-Tier Automated Test Framework${colors.reset}`);
    console.log(`${colors.bold}${colors.blue}======================================================================${colors.reset}`);

    const reporter = new TestReporter();

    // Execute Tiers 1 through 5
    runTier1Tests(reporter);
    runTier2Tests(reporter);
    runTier3Tests(reporter);
    runTier4Tests(reporter);
    runTier5Tests(reporter);

    const exitCode = reporter.summary();
    process.exit(exitCode);
}

if (require.main === module) {
    main();
}

module.exports = { TestReporter, main };
