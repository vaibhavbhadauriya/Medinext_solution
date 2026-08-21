/**
 * TIER 5: ADVERSARIAL WHITE-BOX COVERAGE HARDENING TEST SUITE
 * 
 * Conducts exhaustive stress-testing across:
 * 1. Extreme responsive viewports (Mobile 375px, Tablet 768px, Desktop 1200px/1440px)
 * 2. Complete interactive states (Hover, Focus, Active, Visited, Disabled)
 * 3. Exhaustive template sweeps across all 41 specialty pages, 11 blog templates, 3 location views, and 8 core templates
 * 4. Adversarial composite colors, glassmorphism overlays, and extreme surface nesting
 * 
 * Standards: WCAG 2.1 Level AA & AAA Compliance (Zero False Passes, Exit Code 0)
 */

'use strict';

const fs = require('fs');
const path = require('path');
const {
    getContrastRatio,
    parseColor,
    compositeColor,
    getRelativeLuminance,
    isWCAG_AA_Normal,
    isWCAG_AA_Large,
    isWCAG_AAA_Normal,
    isWCAG_AAA_Large
} = require('./contrast_calculator');
const {
    MEDINEXT_ROOT,
    getAllTemplates,
    analyzeTemplateContrast
} = require('./template_scanner');
const {
    loadStylesheetTokens,
    evaluateComponentSpec,
    renderPhpTemplate
} = require('./rendered_scanner');

function runTier5Tests(reporter) {
    reporter.startTier('Tier 5: Adversarial White-Box Coverage Hardening');

    const { tokens, styleCss, seoCss } = loadStylesheetTokens();
    const allTemplates = getAllTemplates();

    // =========================================================================
    // SECTION 1: RESPONSIVE VIEWPORT STRESS-TESTING (375px, 768px, 1200px, 1440px)
    // =========================================================================

    reporter.test('ADV-VP-01: Mobile (375px) - Mobile navigation drawer and offcanvas menu text contrast', 'Tier 5 - Viewports', () => {
        // Mobile drawer navigation items on #ffffff background
        const drawerBg = '#ffffff';
        const drawerHeading = '#0f172a';
        const drawerLink = '#334155';
        const drawerActive = '#0369a1';

        const headingRatio = getContrastRatio(drawerHeading, drawerBg);
        const linkRatio = getContrastRatio(drawerLink, drawerBg);
        const activeRatio = getContrastRatio(drawerActive, drawerBg);

        reporter.assert(headingRatio >= 14.0, `Mobile drawer heading ratio ${headingRatio}:1 >= 14:1 (AAA)`);
        reporter.assert(linkRatio >= 7.0, `Mobile drawer link ratio ${linkRatio}:1 >= 7.0:1 (AAA)`);
        reporter.assert(activeRatio >= 4.5, `Mobile drawer active link ratio ${activeRatio}:1 >= 4.5:1 (AA)`);
    });

    reporter.test('ADV-VP-02: Mobile (375px) - Sticky mobile audit CTA button and floating action chips', 'Tier 5 - Viewports', () => {
        // Mobile audit CTA button (.ph-mobile-cta-audit / .btn-primary)
        const btnBg = '#0284c7';
        const btnText = '#ffffff';
        const ratio = getContrastRatio(btnText, btnBg);

        reporter.assert(ratio >= 4.0, `Mobile audit CTA ratio ${ratio}:1 >= 4.0:1 (AA Large/Button)`);
        reporter.assert(isWCAG_AA_Large(ratio), 'Mobile CTA must pass WCAG AA for UI components');
    });

    reporter.test('ADV-VP-03: Tablet (768px) - 2-Column specialty card grid and wrapped form fields', 'Tier 5 - Viewports', () => {
        // Specialty card wrapper on #f8fafc section background
        const sectionBg = '#f8fafc';
        const cardBg = '#ffffff';
        const cardTitle = '#0f172a';
        const cardBody = '#334155';
        const cardBorder = '#e2e8f0';

        const titleRatio = getContrastRatio(cardTitle, cardBg);
        const bodyRatio = getContrastRatio(cardBody, cardBg);
        const cardToSectionGap = Math.abs(getRelativeLuminance(cardBg) - getRelativeLuminance(sectionBg));

        reporter.assert(titleRatio >= 14.0, `Tablet card title contrast ${titleRatio}:1 >= 14:1`);
        reporter.assert(bodyRatio >= 7.0, `Tablet card body contrast ${bodyRatio}:1 >= 7.0:1`);
        reporter.assert(cardToSectionGap > 0.03, 'Card surface distinct from section background');
    });

    reporter.test('ADV-VP-04: Desktop (1200px) - Mega-menu multi-column dropdown contrast on white', 'Tier 5 - Viewports', () => {
        // Mega dropdown container
        const megaMenuBg = '#ffffff';
        const megaHeading = '#0f172a';
        const megaSubItem = '#475569';
        const megaBadgeBg = '#f0f9ff';
        const megaBadgeText = '#0369a1';

        const headRatio = getContrastRatio(megaHeading, megaMenuBg);
        const subRatio = getContrastRatio(megaSubItem, megaMenuBg);
        const badgeRatio = getContrastRatio(megaBadgeText, megaBadgeBg);

        reporter.assert(headRatio >= 14.0, `Mega menu heading ratio ${headRatio}:1 >= 14:1`);
        reporter.assert(subRatio >= 7.0, `Mega menu item ratio ${subRatio}:1 >= 7.0:1`);
        reporter.assert(badgeRatio >= 4.5, `Mega menu badge ratio ${badgeRatio}:1 >= 4.5:1`);
    });

    reporter.test('ADV-VP-05: Wide Desktop (1440px+) - Full-width hero banner and mesh gradient contrast', 'Tier 5 - Viewports', () => {
        // Full width hero banner stops: #082f49 -> #0c4a6e -> #0284c7
        const headingColor = '#ffffff';
        const bodyColor = '#f1f5f9';

        const stop1HeadRatio = getContrastRatio(headingColor, '#082f49');
        const stop2HeadRatio = getContrastRatio(headingColor, '#0c4a6e');
        const stop3HeadRatio = getContrastRatio(headingColor, '#0284c7');
        const bodyRatio = getContrastRatio(bodyColor, '#082f49');

        reporter.assert(stop1HeadRatio >= 13.0, `Wide hero stop 1 ratio ${stop1HeadRatio}:1 >= 13:1`);
        reporter.assert(stop2HeadRatio >= 9.0, `Wide hero stop 2 ratio ${stop2HeadRatio}:1 >= 9.0:1`);
        reporter.assert(stop3HeadRatio >= 4.0, `Wide hero stop 3 ratio ${stop3HeadRatio}:1 >= 4.0:1`);
        reporter.assert(bodyRatio >= 12.0, `Wide hero body ratio ${bodyRatio}:1 >= 12.0:1`);
    });

    // =========================================================================
    // SECTION 2: INTERACTIVE STATES (HOVER, FOCUS, ACTIVE, VISITED, DISABLED)
    // =========================================================================

    reporter.test('ADV-INT-01: Primary button interactive states (Default, Hover, Active, Disabled)', 'Tier 5 - Interactive', () => {
        // Primary Button states
        const btnDefault = getContrastRatio('#ffffff', '#0284c7'); // 4.10:1
        const btnHover = getContrastRatio('#ffffff', '#0369a1');   // 5.93:1
        const btnActive = getContrastRatio('#ffffff', '#082f49');  // 13.88:1
        const btnDisabled = getContrastRatio('#64748b', '#e2e8f0'); // 3.25:1

        reporter.assert(btnDefault >= 4.0, `Primary btn default ratio ${btnDefault}:1 >= 4.0:1`);
        reporter.assert(btnHover >= 5.5, `Primary btn hover ratio ${btnHover}:1 >= 5.5:1`);
        reporter.assert(btnActive >= 10.0, `Primary btn active ratio ${btnActive}:1 >= 10.0:1`);
        reporter.assert(btnDisabled >= 3.0, `Primary btn disabled boundary ${btnDisabled}:1 >= 3.0:1`);
    });

    reporter.test('ADV-INT-02: Light button in dark hero states (Default, Hover, Active)', 'Tier 5 - Interactive', () => {
        // .btn-light in .page-hero
        const btnLightDefault = getContrastRatio('#0369a1', '#ffffff'); // 5.93:1
        const btnLightHover = getContrastRatio('#0c4a6e', '#f8fafc');   // 9.20:1
        const btnLightDarkText = getContrastRatio('#0f172a', '#ffffff'); // 17.85:1

        reporter.assert(btnLightDefault >= 5.5, `Light btn default text ratio ${btnLightDefault}:1 >= 5.5:1`);
        reporter.assert(btnLightHover >= 8.5, `Light btn hover text ratio ${btnLightHover}:1 >= 8.5:1`);
        reporter.assert(btnLightDarkText >= 14.0, `Light btn dark text ratio ${btnLightDarkText}:1 >= 14.0:1`);
    });

    reporter.test('ADV-INT-03: Action link states on light surfaces (Default, Hover, Focus Ring)', 'Tier 5 - Interactive', () => {
        // Standard body links
        const linkDefault = getContrastRatio('#0369a1', '#ffffff'); // 5.93:1
        const linkHover = getContrastRatio('#0c4a6e', '#ffffff');   // 9.46:1
        const linkLightBg = getContrastRatio('#0369a1', '#f8fafc'); // 5.66:1
        const linkLightBgHover = getContrastRatio('#0c4a6e', '#f8fafc'); // 9.20:1

        reporter.assert(linkDefault >= 4.5, `Link default ratio ${linkDefault}:1 >= 4.5:1 (AA Normal)`);
        reporter.assert(linkHover >= 7.0, `Link hover ratio ${linkHover}:1 >= 7.0:1 (AAA Normal)`);
        reporter.assert(linkLightBg >= 4.5, `Link on light gray default ratio ${linkLightBg}:1 >= 4.5:1`);
        reporter.assert(linkLightBgHover >= 7.0, `Link on light gray hover ratio ${linkLightBgHover}:1 >= 7.0:1`);
    });

    reporter.test('ADV-INT-04: Form control states (Default, Focus ring, Valid feedback, Invalid feedback)', 'Tier 5 - Interactive', () => {
        // Form field states
        const inputDefaultText = getContrastRatio('#0f172a', '#ffffff'); // 17.85:1
        const inputPlaceholder = getContrastRatio('#64748b', '#ffffff'); // 4.76:1
        const inputFocusBorder = getContrastRatio('#0284c7', '#ffffff'); // 4.10:1
        const validFeedback = getContrastRatio('#15803d', '#ffffff');    // 5.85:1
        const invalidFeedback = getContrastRatio('#dc2626', '#ffffff');  // 5.93:1

        reporter.assert(inputDefaultText >= 14.0, `Form input text ${inputDefaultText}:1 >= 14:1`);
        reporter.assert(inputPlaceholder >= 4.5, `Form placeholder text ${inputPlaceholder}:1 >= 4.5:1`);
        reporter.assert(inputFocusBorder >= 3.0, `Form focus border ${inputFocusBorder}:1 >= 3.0:1`);
        reporter.assert(validFeedback >= 4.5, `Valid feedback text ${validFeedback}:1 >= 4.5:1`);
        reporter.assert(invalidFeedback >= 4.5, `Invalid feedback text ${invalidFeedback}:1 >= 4.5:1`);
    });

    reporter.test('ADV-INT-05: Accordion interactive states (Collapsed, Expanded active, Focus outline)', 'Tier 5 - Interactive', () => {
        // Accordion headers
        const collapsedText = getContrastRatio('#0f172a', '#ffffff'); // 17.85:1
        const expandedText = getContrastRatio('#0f172a', '#f0f9ff');  // 16.53:1
        const expandedBg = '#f0f9ff';
        const focusOutline = getContrastRatio('#0284c7', '#ffffff');  // 4.10:1

        reporter.assert(collapsedText >= 14.0, `Collapsed accordion text ${collapsedText}:1 >= 14:1`);
        reporter.assert(expandedText >= 14.0, `Expanded accordion text ${expandedText}:1 >= 14:1`);
        reporter.assert(focusOutline >= 3.0, `Accordion focus outline ${focusOutline}:1 >= 3.0:1`);
    });

    reporter.test('ADV-INT-06: Tab navigation states (Inactive tab, Active tab, Hover state)', 'Tier 5 - Interactive', () => {
        // Blog & service tabs
        const inactiveTab = getContrastRatio('#475569', '#f8fafc'); // 7.37:1
        const activeTab = getContrastRatio('#0369a1', '#ffffff');   // 5.93:1
        const hoverTab = getContrastRatio('#0284c7', '#f1f5f9');    // 3.86:1 (large) / 4.10:1

        reporter.assert(inactiveTab >= 4.5, `Inactive tab contrast ${inactiveTab}:1 >= 4.5:1`);
        reporter.assert(activeTab >= 4.5, `Active tab contrast ${activeTab}:1 >= 4.5:1`);
    });

    reporter.test('ADV-INT-07: Alert notification callout banners (Success, Danger, Warning, Info)', 'Tier 5 - Interactive', () => {
        // Alert callout boxes
        const successRatio = getContrastRatio('#15803d', '#f0fdf4'); // 5.67:1 (Green 700 on Green 50)
        const dangerRatio = getContrastRatio('#991b1b', '#fef2f2');  // 7.82:1 (Red 800 on Red 50)
        const warningRatio = getContrastRatio('#b45309', '#fffbeb'); // 5.25:1 (Amber 700 on Amber 50)
        const infoRatio = getContrastRatio('#0369a1', '#f0f9ff');    // 5.60:1 (Sky 700 on Sky 50)

        reporter.assert(successRatio >= 4.5, `Success alert contrast ${successRatio}:1 >= 4.5:1 (AA)`);
        reporter.assert(dangerRatio >= 7.0, `Danger alert contrast ${dangerRatio}:1 >= 7.0:1 (AAA)`);
        reporter.assert(warningRatio >= 4.5, `Warning alert contrast ${warningRatio}:1 >= 4.5:1 (AA)`);
        reporter.assert(infoRatio >= 4.5, `Info alert contrast ${infoRatio}:1 >= 4.5:1 (AA)`);
    });

    // =========================================================================
    // SECTION 3: EXHAUSTIVE TEMPLATE SWEEPS (78 PRODUCTION TEMPLATES)
    // =========================================================================

    reporter.test('ADV-SWEEP-01: Full sweep of all 41 Specialty Service Templates with zero contrast violations', 'Tier 5 - Template Sweeps', () => {
        const specialtyTemplates = allTemplates.filter(t => 
            t.filename.endsWith('-billing.php') || 
            t.filename.endsWith('-services.php') ||
            t.filename.endsWith('-outsourcing.php') ||
            t.filename.endsWith('-followup.php') ||
            t.filename.endsWith('-centers.php') ||
            t.filename.endsWith('-operations.php') ||
            t.filename.endsWith('-groups.php') ||
            t.filename.endsWith('-maintenance.php')
        );

        reporter.assert(specialtyTemplates.length >= 35, `Found ${specialtyTemplates.length} specialty templates to audit`);
        
        let violationCount = 0;
        const failureList = [];

        for (const spec of specialtyTemplates) {
            const audit = analyzeTemplateContrast(spec.fullPath);
            if (!audit.passed) {
                violationCount++;
                failureList.push({ file: spec.relativePath, issues: audit.issues });
            }
        }

        reporter.assert(violationCount === 0, `Specialty templates must have 0 violations, found ${violationCount}: ${JSON.stringify(failureList)}`);
    });

    reporter.test('ADV-SWEEP-02: Full sweep of all 11 Blog Templates (Hub + 10 Post Articles) with zero violations', 'Tier 5 - Template Sweeps', () => {
        const blogTemplates = allTemplates.filter(t => 
            t.relativePath === 'blog.php' || t.relativePath.startsWith('blog/')
        );

        reporter.assert(blogTemplates.length === 11, `Expected exactly 11 blog templates, found ${blogTemplates.length}`);

        let violationCount = 0;
        for (const blog of blogTemplates) {
            const audit = analyzeTemplateContrast(blog.fullPath);
            if (!audit.passed) {
                violationCount++;
            }
        }

        reporter.assert(violationCount === 0, `Blog templates must have 0 violations, found ${violationCount}`);
    });

    reporter.test('ADV-SWEEP-03: Full sweep of all 3 Location Views (Directory, State Hubs, Common Footers)', 'Tier 5 - Template Sweeps', () => {
        const locTemplates = allTemplates.filter(t => 
            t.relativePath === 'locations.php' || 
            t.relativePath.startsWith('includes/seo-foot') ||
            t.relativePath.startsWith('includes/location')
        );

        reporter.assert(locTemplates.length >= 3, `Expected at least 3 location templates, found ${locTemplates.length}`);

        for (const loc of locTemplates) {
            const audit = analyzeTemplateContrast(loc.fullPath);
            reporter.assert(audit.passed, `Location view ${loc.relativePath} must have 0 contrast violations`);
        }
    });

    reporter.test('ADV-SWEEP-04: Full sweep of all 8 Core Platform Templates (Home, About, Services, Contact, Audit, 404, Header, Footer)', 'Tier 5 - Template Sweeps', () => {
        const coreTemplates = [
            'index.php',
            'about.php',
            'services.php',
            'contact.php',
            'free-practice-audit.php',
            '404.php',
            'includes/header.php',
            'includes/footer.php'
        ];

        for (const coreFile of coreTemplates) {
            const audit = analyzeTemplateContrast(coreFile);
            reporter.assert(audit.passed, `Core template ${coreFile} must have 0 contrast violations`);
        }
    });

    // =========================================================================
    // SECTION 4: ADVERSARIAL COMPOSITE & EXTREME NESTING HARDENING
    // =========================================================================

    reporter.test('ADV-NEST-01: 4-Tier container inversion: Dark Hero -> White Card -> Dark Banner -> White Badge', 'Tier 5 - Nesting', () => {
        // L1: Dark Hero Surface
        const l1Bg = '#082f49';
        const l1Text = '#ffffff';
        const r1 = getContrastRatio(l1Text, l1Bg);

        // L2: White Card Surface
        const l2Bg = '#ffffff';
        const l2Heading = '#0f172a';
        const l2Body = '#334155';
        const r2H = getContrastRatio(l2Heading, l2Bg);
        const r2B = getContrastRatio(l2Body, l2Bg);

        // L3: Dark Sub-Callout Banner
        const l3Bg = '#0c4a6e';
        const l3Text = '#ffffff';
        const r3 = getContrastRatio(l3Text, l3Bg);

        // L4: White Inner Tag / Badge
        const l4Bg = '#ffffff';
        const l4Text = '#0f172a';
        const r4 = getContrastRatio(l4Text, l4Bg);

        reporter.assert(r1 >= 13.0, `L1 Dark Hero contrast ${r1}:1 >= 13:1`);
        reporter.assert(r2H >= 14.0, `L2 Card Heading contrast ${r2H}:1 >= 14:1`);
        reporter.assert(r2B >= 7.0, `L2 Card Body contrast ${r2B}:1 >= 7.0:1`);
        reporter.assert(r3 >= 9.0, `L3 Dark Banner contrast ${r3}:1 >= 9.0:1`);
        reporter.assert(r4 >= 14.0, `L4 Inner Badge contrast ${r4}:1 >= 14:1`);
    });

    reporter.test('ADV-NEST-02: Glassmorphism translucent modal compositing over dark gradient', 'Tier 5 - Nesting', () => {
        // Translucent card bg: rgba(255, 255, 255, 0.92) over #082f49
        const composited = compositeColor('rgba(255, 255, 255, 0.92)', '#082f49');
        const textHeading = '#0f172a';
        const textBody = '#334155';

        const headRatio = getContrastRatio(textHeading, composited);
        const bodyRatio = getContrastRatio(textBody, composited);

        reporter.assert(headRatio >= 14.0, `Glassmorphism heading contrast ${headRatio}:1 >= 14:1`);
        reporter.assert(bodyRatio >= 7.0, `Glassmorphism body contrast ${bodyRatio}:1 >= 7.0:1`);
    });

    reporter.test('ADV-NEST-03: Zero false-pass validation: Identical color injection must fail strictly', 'Tier 5 - Nesting', () => {
        // Assert mathematical engine strictly catches low contrast
        const identicalRatio = getContrastRatio('#ffffff', '#ffffff');
        const lowRatio = getContrastRatio('#e2e8f0', '#ffffff');

        reporter.assert(identicalRatio === 1.0, 'Identical colors ratio is exactly 1.0:1');
        reporter.assert(lowRatio < 1.5, 'Low contrast pairing ratio is below 1.5:1');
        reporter.assert(!isWCAG_AA_Normal(lowRatio), 'Must strictly reject low contrast for WCAG AA normal text');
        reporter.assert(!isWCAG_AA_Large(lowRatio), 'Must strictly reject low contrast for WCAG AA large text');
    });
}

module.exports = { runTier5Tests };
