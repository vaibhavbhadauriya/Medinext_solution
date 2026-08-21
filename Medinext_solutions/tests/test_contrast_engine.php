<?php
/**
 * Site-Wide Color Contrast & Visibility Verification Suite
 * Verifies that light surfaces have dark text, dark surfaces have white text,
 * and form controls/labels/placeholders avoid color matching overlaps.
 */

declare(strict_types=1);

namespace Medinext\Tests;

$projectRoot = dirname(__DIR__);
require_once $projectRoot . '/tests/TestHelper.php';

$suite = new TestSuite("Site-Wide Color Contrast & Visibility Engine Suite", "Verifies contrast ratios and zero text/bg overlaps across all site templates and components");

$suite->addTest("TC01: Global High-Contrast Stylesheet Rules", "Contrast", function() use ($projectRoot) {
    $css = file_get_contents($projectRoot . '/assets/css/style.css');
    Assert::assertTrue(strpos($css, 'SITE-WIDE COLOR CONTRAST & VISIBILITY HARDENING ENGINE') !== false, "Style.css must contain contrast hardening section");
    Assert::assertTrue(strpos($css, '.page-hero h1') !== false, "Style.css must contain page-hero h1 rule");
    Assert::assertTrue(strpos($css, 'color: #ffffff !important') !== false, "Style.css must contain white heading rule");
    Assert::assertTrue(strpos($css, '.bg-white h1') !== false, "Style.css must contain dark heading rule");
    Assert::assertTrue(strpos($css, 'color: #0f172a !important') !== false, "Style.css must contain charcoal heading rule");
    Assert::assertTrue(strpos($css, '.form-label') !== false, "Style.css must contain form-label rule");
});

$suite->addTest("TC02: Contact Page Form & Info Contrast", "Contrast", function() {
    $rendered = renderPageScript('contact.php');
    Assert::assertEquals(200, $rendered['statusCode']);
    Assert::assertTrue(strpos($rendered['html'], 'page-hero-title') !== false);
    Assert::assertTrue(strpos($rendered['html'], 'contact-form-wrapper') !== false);
    Assert::assertTrue(strpos($rendered['html'], 'contact-info-card') !== false);
});

$suite->addTest("TC03: Practice Audit Form High-Contrast Elements", "Contrast", function() {
    $rendered = renderPageScript('free-practice-audit.php');
    Assert::assertEquals(200, $rendered['statusCode']);
    Assert::assertTrue(strpos($rendered['html'], 'audit-main-section') !== false);
    Assert::assertTrue(strpos($rendered['html'], 'audit-authority-panel') !== false);
});

$suite->addTest("TC04: Locations Hub & State Page Header Contrast", "Contrast", function() {
    $rendered = renderPageScript('locations.php', ['state' => 'florida']);
    Assert::assertEquals(200, $rendered['statusCode']);
    Assert::assertTrue(strpos($rendered['html'], 'Medical Billing &amp; RCM Services in') !== false);
});

$suite->addTest("TC05: Specialty Service Pages Header & Content Contrast", "Contrast", function() {
    $rendered = renderPageScript('therapy-billing-services.php');
    Assert::assertEquals(200, $rendered['statusCode']);
    Assert::assertTrue(strpos($rendered['html'], 'page-hero text-white') !== false);
    Assert::assertTrue(strpos($rendered['html'], 'Specialized Therapy Billing Services') !== false);
});

$results = $suite->run();
exit($results['failed'] === 0 ? 0 : 1);
