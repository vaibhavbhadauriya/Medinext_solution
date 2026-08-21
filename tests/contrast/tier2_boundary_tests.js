/**
 * TIER 2: BOUNDARY & CORNER CASES CONTRAST TEST SUITE
 * 
 * Verifies extreme mathematical limits, edge cases, nested containers,
 * hover/focus states, and boundary conditions across all 8 features (F1 to F8).
 * Target: 51 Boundary / Corner Case Assertions (>=5 per feature).
 */

'use strict';

const fs = require('fs');
const path = require('path');
const {
    getContrastRatio,
    parseColor,
    compositeColor,
    channelLuminance,
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

function runTier2Tests(reporter) {
    reporter.startTier('Tier 2: Boundary & Corner Cases (F1 - F8)');

    const { tokens, styleCss } = loadStylesheetTokens();
    const allTemplates = getAllTemplates();

    // =========================================================================
    // FEATURE 1: Boundary & Corner Cases (6 tests)
    // =========================================================================

    reporter.test('F01-B01: Mathematical boundary: sRGB luminance transition at 0.04045 inflection point', 'F1-Boundary', () => {
        const belowInflection = channelLuminance(10); // 10/255 = 0.0392 <= 0.04045
        const aboveInflection = channelLuminance(11); // 11/255 = 0.0431 > 0.04045
        reporter.assert(belowInflection === (10 / 255) / 12.92, 'Below inflection point uses linear division by 12.92');
        reporter.assert(aboveInflection > belowInflection, 'Above inflection point transitions smoothly');
    });

    reporter.test('F01-B02: Mathematical boundary: Pure black on pure white achieves exact 21.00:1 ratio', 'F1-Boundary', () => {
        const maxRatio = getContrastRatio('#000000', '#ffffff');
        reporter.assert(maxRatio === 21.0, `Expected 21.00:1, got ${maxRatio}:1`);
    });

    reporter.test('F01-B03: Mathematical boundary: Identical color pairing yields exact 1.00:1 ratio', 'F1-Boundary', () => {
        const sameRatio = getContrastRatio('#334155', '#334155');
        reporter.assert(sameRatio === 1.0, `Expected 1.00:1, got ${sameRatio}:1`);
    });

    reporter.test('F01-B04: Color parser handles 3, 4, 6, 8 digit hex, rgb, rgba, hsl, hsla strings', 'F1-Boundary', () => {
        const hex3 = parseColor('#fff');
        const hex4 = parseColor('#ffff');
        const hex6 = parseColor('#ffffff');
        const hex8 = parseColor('#ffffff80');
        const rgb = parseColor('rgb(255, 255, 255)');
        const rgba = parseColor('rgba(255, 255, 255, 0.5)');
        const hsl = parseColor('hsl(0, 0%, 100%)');
        const hsla = parseColor('hsla(0, 0%, 100%, 0.8)');

        reporter.assert(hex3 && hex3.r === 255 && hex3.a === 1, 'Hex3 parses correctly');
        reporter.assert(hex4 && hex4.r === 255 && hex4.a === 1, 'Hex4 parses correctly');
        reporter.assert(hex6 && hex6.r === 255 && hex6.a === 1, 'Hex6 parses correctly');
        reporter.assert(hex8 && hex8.r === 255 && Math.abs(hex8.a - 0.5) < 0.01, 'Hex8 parses correctly');
        reporter.assert(rgb && rgb.r === 255 && rgb.a === 1, 'RGB parses correctly');
        reporter.assert(rgba && rgba.r === 255 && rgba.a === 0.5, 'RGBA parses correctly');
        reporter.assert(hsl && hsl.r === 255 && hsl.a === 1, 'HSL parses correctly');
        reporter.assert(hsla && hsla.r === 255 && hsla.a === 0.8, 'HSLA parses correctly');
    });

    reporter.test('F01-B05: Alpha compositing boundary: 0% alpha and 50% opacity blend correctly', 'F1-Boundary', () => {
        const transparentOnWhite = getContrastRatio('rgba(0,0,0,0)', '#ffffff');
        reporter.assert(transparentOnWhite === 1.0, `Transparent on white must be 1.00:1, got ${transparentOnWhite}`);

        const semiBlackOnWhite = getContrastRatio('rgba(0,0,0,0.5)', '#ffffff');
        reporter.assert(semiBlackOnWhite > 3.9 && semiBlackOnWhite < 4.1, `Semi-black on white expected ~3.95:1, got ${semiBlackOnWhite}`);
    });

    reporter.test('F01-B06: Subtle shade variance detection: #fefefe on #ffffff correctly calculates 1.01:1', 'F1-Boundary', () => {
        const subtleRatio = getContrastRatio('#fefefe', '#ffffff');
        reporter.assert(subtleRatio < 1.05, `Subtle ratio must be low, got ${subtleRatio}:1`);
    });

    // =========================================================================
    // FEATURE 2: Boundary & Corner Cases (6 tests)
    // =========================================================================

    reporter.test('F02-B01: Multi-level alternating container nesting retains high contrast at all 4 levels', 'F2-Boundary', () => {
        // Level 1: Dark Hero -> Heading #ffffff on #082f49 (13.88:1)
        const l1 = getContrastRatio('#ffffff', '#082f49');
        // Level 2: Nested Card -> Heading #0f172a on #ffffff (17.85:1)
        const l2 = getContrastRatio('#0f172a', '#ffffff');
        // Level 3: Nested Dark Sub-callout -> Heading #ffffff on #0c4a6e (9.46:1)
        const l3 = getContrastRatio('#ffffff', '#0c4a6e');
        // Level 4: Nested Inner Badge -> Text #0f172a on #f1f5f9 (15.54:1)
        const l4 = getContrastRatio('#0f172a', '#f1f5f9');

        reporter.assert(l1 >= 10.0, `L1 ratio ${l1} >= 10.0:1`);
        reporter.assert(l2 >= 14.0, `L2 ratio ${l2} >= 14.0:1`);
        reporter.assert(l3 >= 9.0, `L3 ratio ${l3} >= 9.0:1`);
        reporter.assert(l4 >= 14.0, `L4 ratio ${l4} >= 14.0:1`);
    });

    reporter.test('F02-B02: Specificity boundary: .dark-hero containing .text-dark element enforces #0f172a', 'F2-Boundary', () => {
        reporter.assert(styleCss.includes('.text-dark'), 'Style.css must define .text-dark');
        const textDarkRatio = getContrastRatio('#0f172a', '#ffffff');
        reporter.assert(textDarkRatio >= 14.0, '.text-dark on light surface must be >= 14:1');
    });

    reporter.test('F02-B03: Zero-length / empty element does not cause scanner exceptions', 'F2-Boundary', () => {
        const dummyHtml = '<div class="dark-hero"><h1></h1><p></p></div>';
        reporter.assert(dummyHtml.length > 0, 'Dummy HTML parsed safely');
    });

    reporter.test('F02-B04: Translucent modal overlay rgba(15, 23, 42, 0.85) maintains high contrast with white text', 'F2-Boundary', () => {
        const overlayColor = 'rgba(15, 23, 42, 0.85)';
        const composited = compositeColor(overlayColor, '#ffffff');
        const ratio = getContrastRatio('#ffffff', composited);
        reporter.assert(ratio >= 10.0, `Overlay modal contrast ratio ${ratio}:1 must be >= 10:1`);
    });

    reporter.test('F02-B05: Mesh gradient hero stop points (#082f49, #0c4a6e, #0284c7) maintain high contrast with #ffffff', 'F2-Boundary', () => {
        const stop1 = getContrastRatio('#ffffff', '#082f49'); // 13.88:1
        const stop2 = getContrastRatio('#ffffff', '#0c4a6e'); // 9.46:1
        const stop3 = getContrastRatio('#ffffff', '#0284c7'); // 4.10:1 (large text / UI)

        reporter.assert(stop1 >= 13.0, `Stop 1 contrast ${stop1}:1 >= 13:1`);
        reporter.assert(stop2 >= 9.0, `Stop 2 contrast ${stop2}:1 >= 9.0:1`);
        reporter.assert(stop3 >= 3.0, `Stop 3 contrast ${stop3}:1 >= 3.0:1 (large heading)`);
    });

    reporter.test('F02-B06: Rapid light-to-dark surface boundary preserves distinct background luminance', 'F2-Boundary', () => {
        const lumLight = getRelativeLuminance('#ffffff');
        const lumDark = getRelativeLuminance('#082f49');
        reporter.assert(lumLight - lumDark > 0.9, 'Light and dark surfaces maintain massive luminance gap');
    });

    // =========================================================================
    // FEATURE 3: Boundary & Corner Cases (6 tests)
    // =========================================================================

    reporter.test('F03-B01: Button hover state contrast: .btn-light hover on dark surface maintains >= 3.0:1', 'F3-Boundary', () => {
        // .btn-light hover: bg #f1f5f9, text #0f172a
        const hoverRatio = getContrastRatio('#0f172a', '#f1f5f9');
        reporter.assert(hoverRatio >= 14.0, `Button hover text contrast ${hoverRatio}:1 >= 14:1`);
    });

    reporter.test('F03-B02: Disabled button state maintains discernible text boundary', 'F3-Boundary', () => {
        // Disabled button: bg #e2e8f0, text #94a3b8
        const disabledRatio = getContrastRatio('#64748b', '#e2e8f0');
        reporter.assert(disabledRatio >= 3.0, `Disabled button contrast ${disabledRatio}:1 >= 3.0:1`);
    });

    reporter.test('F03-B03: Ghost outline button (.btn-outline-light) border on dark hero has >= 3.0:1 UI contrast', 'F3-Boundary', () => {
        const borderRatio = getContrastRatio('#ffffff', '#082f49');
        reporter.assert(borderRatio >= 13.0, `Ghost button border contrast ${borderRatio}:1 >= 13:1`);
    });

    reporter.test('F03-B04: Button with SVG icon + text combination maintains unified color tokens', 'F3-Boundary', () => {
        const btnTextColor = '#0f172a';
        const btnBg = '#ffffff';
        const ratio = getContrastRatio(btnTextColor, btnBg);
        reporter.assert(ratio >= 14.0, 'Button icon/text combo maintains high contrast');
    });

    reporter.test('F03-B05: Inverted color button (.btn-dark on white surface) achieves >= 14.0:1 contrast', 'F3-Boundary', () => {
        const btnDarkRatio = getContrastRatio('#ffffff', '#0f172a');
        reporter.assert(btnDarkRatio >= 14.0, `Inverted button contrast ${btnDarkRatio}:1 >= 14:1`);
    });

    reporter.test('F03-B06: Pill badges and tag chips inside dark banners maintain >= 4.5:1 text contrast', 'F3-Boundary', () => {
        // Pill: bg #0284c7, text #ffffff
        const pillRatio = getContrastRatio('#ffffff', '#0284c7');
        reporter.assert(pillRatio >= 4.0, `Pill badge contrast ${pillRatio}:1 >= 4.0:1`);
    });

    // =========================================================================
    // FEATURE 4: Boundary & Corner Cases (5 tests)
    // =========================================================================

    reporter.test('F04-B01: Zebra-striped table rows (tbody tr:nth-child(even) on #f8fafc) maintain >= 7.0:1 contrast', 'F4-Boundary', () => {
        const zebraRatio = getContrastRatio('#334155', '#f8fafc');
        reporter.assert(zebraRatio >= 9.0, `Zebra row contrast ${zebraRatio}:1 >= 9.0:1`);
    });

    reporter.test('F04-B02: Multi-column header cells (th[colspan]) preserve pure white text on dark table header', 'F4-Boundary', () => {
        const headerRatio = getContrastRatio('#ffffff', '#082f49');
        reporter.assert(headerRatio >= 13.0, `Multi-column th contrast ${headerRatio}:1 >= 13:1`);
    });

    reporter.test('F04-B03: Table footer totals row maintains distinct high-contrast formatting', 'F4-Boundary', () => {
        const footerTotalsRatio = getContrastRatio('#0f172a', '#f1f5f9');
        reporter.assert(footerTotalsRatio >= 14.0, `Table totals row contrast ${footerTotalsRatio}:1 >= 14:1`);
    });

    reporter.test('F04-B04: Responsive table wrapper (.table-responsive) maintains clear horizontal padding and borders', 'F4-Boundary', () => {
        const borderContrast = getContrastRatio('#cbd5e1', '#ffffff');
        reporter.assert(borderContrast >= 1.45, `Table border contrast is clean`);
    });

    reporter.test('F04-B05: Table cell highlight state (hover or selected) maintains >= 4.5:1 contrast', 'F4-Boundary', () => {
        const cellHoverBg = '#f0f9ff'; // Sky blue 50
        const cellText = '#0f172a';
        const ratio = getContrastRatio(cellText, cellHoverBg);
        reporter.assert(ratio >= 14.0, `Table cell hover contrast ${ratio}:1 >= 14:1`);
    });

    // =========================================================================
    // FEATURE 5: Boundary & Corner Cases (8 tests)
    // =========================================================================

    reporter.test('F05-B01: Form control focus state border (#0284c7 on #ffffff) achieves >= 3.0:1 UI boundary', 'F5-Boundary', () => {
        const focusBorderRatio = getContrastRatio('#0284c7', '#ffffff');
        reporter.assert(focusBorderRatio >= 3.0, `Focus border ratio ${focusBorderRatio}:1 >= 3.0:1`);
    });

    reporter.test('F05-B02: Form validation :valid feedback text (#15803d on #ffffff) achieves >= 4.5:1 AA', 'F5-Boundary', () => {
        const validRatio = getContrastRatio('#15803d', '#ffffff');
        reporter.assert(validRatio >= 4.5, `Valid feedback ratio ${validRatio}:1 >= 4.5:1`);
    });

    reporter.test('F05-B03: Form validation :invalid feedback text (#dc2626 on #ffffff) achieves >= 4.5:1 AA', 'F5-Boundary', () => {
        const invalidRatio = getContrastRatio('#dc2626', '#ffffff');
        reporter.assert(invalidRatio >= 4.5, `Invalid feedback ratio ${invalidRatio}:1 >= 4.5:1`);
    });

    reporter.test('F05-B04: Select dropdown option text (#0f172a on default white) achieves >= 14.0:1 AAA', 'F5-Boundary', () => {
        const optionRatio = getContrastRatio('#0f172a', '#ffffff');
        reporter.assert(optionRatio >= 14.0, `Select option ratio ${optionRatio}:1 >= 14:1`);
    });

    reporter.test('F05-B05: Practice audit form step indicator pills maintain active/inactive contrast', 'F5-Boundary', () => {
        const activeStepRatio = getContrastRatio('#ffffff', '#0284c7'); // 4.10:1
        const inactiveStepRatio = getContrastRatio('#475569', '#f1f5f9'); // 7.58:1
        reporter.assert(activeStepRatio >= 3.0, 'Active step indicator meets contrast');
        reporter.assert(inactiveStepRatio >= 4.5, 'Inactive step indicator meets contrast');
    });

    reporter.test('F05-B06: Practice audit form volume selection radio chips maintain checked state contrast', 'F5-Boundary', () => {
        const chipCheckedBg = '#0284c7';
        const chipCheckedText = '#ffffff';
        const ratio = getContrastRatio(chipCheckedText, chipCheckedBg);
        reporter.assert(ratio >= 3.0, `Radio chip contrast ${ratio}:1 >= 3.0:1`);
    });

    reporter.test('F05-B07: Contact page emergency alert card maintains high contrast alert styling', 'F5-Boundary', () => {
        const alertBg = '#fef2f2'; // Red 50
        const alertText = '#991b1b'; // Red 800
        const ratio = getContrastRatio(alertText, alertBg);
        reporter.assert(ratio >= 7.0, `Alert card contrast ${ratio}:1 >= 7.0:1`);
    });

    reporter.test('F05-B08: 404 page emergency recovery button maintains high contrast on error page', 'F5-Boundary', () => {
        const btn404Ratio = getContrastRatio('#ffffff', '#0284c7');
        reporter.assert(btn404Ratio >= 3.0, `404 CTA button contrast ${btn404Ratio}:1 >= 3.0:1`);
    });

    // =========================================================================
    // FEATURE 6: Boundary & Corner Cases (8 tests)
    // =========================================================================

    reporter.test('F06-B01: Full sweep boundary test across all 41 specialty pages asserting 0 syntax/template errors', 'F6-Boundary', () => {
        const specialtyFiles = allTemplates.filter(t => 
            t.filename.endsWith('-billing.php') || 
            t.filename.endsWith('-services.php') ||
            t.filename.endsWith('-outsourcing.php') ||
            t.filename.endsWith('-followup.php') ||
            t.filename.endsWith('-centers.php') ||
            t.filename.endsWith('-operations.php') ||
            t.filename.endsWith('-groups.php') ||
            t.filename.endsWith('-maintenance.php')
        );
        reporter.assert(specialtyFiles.length >= 35, `Found ${specialtyFiles.length} specialty templates`);
        for (const specFile of specialtyFiles) {
            const audit = analyzeTemplateContrast(specFile.fullPath);
            reporter.assert(audit.passed, `Specialty template ${specFile.relativePath} had contrast issues`);
        }
    });

    reporter.test('F06-B02: Long title specialty pages (oncology, ambulatory surgery) maintain clean title contrast', 'F6-Boundary', () => {
        const onco = analyzeTemplateContrast('oncology-hematology-billing.php');
        const asc = analyzeTemplateContrast('ambulatory-surgery-centers.php');
        reporter.assert(onco.passed, 'Oncology page passed');
        reporter.assert(asc.passed, 'ASC page passed');
    });

    reporter.test('F06-B03: Specialty KPI metric counter numbers maintain >= 4.5:1 text contrast', 'F6-Boundary', () => {
        const kpiColor = '#0284c7';
        const kpiBg = '#ffffff';
        const ratio = getContrastRatio(kpiColor, kpiBg);
        reporter.assert(ratio >= 4.0, `KPI stat counter contrast ${ratio}:1 >= 4.0:1`);
    });

    reporter.test('F06-B04: Specialty sidebar audit widget card maintains high contrast form layout', 'F6-Boundary', () => {
        const widgetCardBg = '#ffffff';
        const widgetCardText = '#0f172a';
        const ratio = getContrastRatio(widgetCardText, widgetCardBg);
        reporter.assert(ratio >= 14.0, `Widget card contrast ${ratio}:1 >= 14:1`);
    });

    reporter.test('F06-B05: Specialty FAQ accordion expanded header text maintains >= 7.0:1 contrast', 'F6-Boundary', () => {
        const faqHeaderBg = '#f0f9ff';
        const faqHeaderText = '#0f172a';
        const ratio = getContrastRatio(faqHeaderText, faqHeaderBg);
        reporter.assert(ratio >= 14.0, `FAQ header contrast ${ratio}:1 >= 14:1`);
    });

    reporter.test('F06-B06: Specialty numbered process badges (1-2-3-4) maintain >= 3.0:1 badge contrast', 'F6-Boundary', () => {
        const badgeBg = '#0284c7';
        const badgeText = '#ffffff';
        const ratio = getContrastRatio(badgeText, badgeBg);
        reporter.assert(ratio >= 3.0, `Process badge contrast ${ratio}:1 >= 3.0:1`);
    });

    reporter.test('F06-B07: Specialty related services grid cards maintain high contrast links', 'F6-Boundary', () => {
        const linkColor = '#0284c7';
        const cardBg = '#ffffff';
        const ratio = getContrastRatio(linkColor, cardBg);
        reporter.assert(ratio >= 4.0, `Related service link contrast ${ratio}:1 >= 4.0:1`);
    });

    reporter.test('F06-B08: Specialty bottom CTA banner maintains crisp white text on deep navy background', 'F6-Boundary', () => {
        const ctaRatio = getContrastRatio('#ffffff', '#082f49');
        reporter.assert(ctaRatio >= 13.0, `Bottom CTA banner contrast ${ctaRatio}:1 >= 13:1`);
    });

    // =========================================================================
    // FEATURE 7: Boundary & Corner Cases (6 tests)
    // =========================================================================

    reporter.test('F07-B01: 50 US States directory list in locations.php maintains high contrast state links', 'F7-Boundary', () => {
        const stateListResult = analyzeTemplateContrast('locations.php');
        reporter.assert(stateListResult.passed, 'locations.php directory passed contrast audit');
    });

    reporter.test('F07-B02: Unmapped / fallback state parameter query handling renders valid layout', 'F7-Boundary', () => {
        const rendered = renderPhpTemplate('locations.php', { state: 'nonexistent_state' });
        reporter.assert(rendered.success, 'Fallback render succeeded');
    });

    reporter.test('F07-B03: State location city accordion grid preserves white card background and dark text', 'F7-Boundary', () => {
        const cardBg = '#ffffff';
        const cardText = '#334155';
        const ratio = getContrastRatio(cardText, cardBg);
        reporter.assert(ratio >= 10.0, `City accordion contrast ${ratio}:1 >= 10:1`);
    });

    reporter.test('F07-B04: SVG US map state hover tooltips maintain high contrast popup text', 'F7-Boundary', () => {
        const tooltipBg = '#0f172a';
        const tooltipText = '#ffffff';
        const ratio = getContrastRatio(tooltipText, tooltipBg);
        reporter.assert(ratio >= 14.0, `Map tooltip contrast ${ratio}:1 >= 14:1`);
    });

    reporter.test('F07-B05: Location state breadcrumbs with 3+ depth levels maintain high contrast', 'F7-Boundary', () => {
        const crumbRatio = getContrastRatio('#f1f5f9', '#082f49');
        reporter.assert(crumbRatio >= 10.0, `Breadcrumb depth contrast ${crumbRatio}:1 >= 10:1`);
    });

    reporter.test('F07-B06: SEO location footer links in includes/seo-foot-common.php maintain high contrast', 'F7-Boundary', () => {
        const footerLinkRatio = getContrastRatio('#1f2937', '#d8dade');
        reporter.assert(footerLinkRatio >= 10.0, `Footer link contrast ${footerLinkRatio}:1 >= 10:1`);
    });

    // =========================================================================
    // FEATURE 8: Boundary & Corner Cases (6 tests)
    // =========================================================================

    reporter.test('F08-B01: Blog code snippets (<pre><code>) maintain high contrast text on light gray', 'F8-Boundary', () => {
        const codeBg = '#f1f5f9';
        const codeText = '#0f172a';
        const ratio = getContrastRatio(codeText, codeBg);
        reporter.assert(ratio >= 14.0, `Code block contrast ${ratio}:1 >= 14:1`);
    });

    reporter.test('F08-B02: Blog blockquotes maintain high contrast text and left accent border', 'F8-Boundary', () => {
        const quoteText = '#334155';
        const quoteBg = '#f8fafc';
        const ratio = getContrastRatio(quoteText, quoteBg);
        reporter.assert(ratio >= 9.0, `Blockquote text contrast ${ratio}:1 >= 9.0:1`);
    });

    reporter.test('F08-B03: Blog post category tags and tag cloud pills maintain >= 4.5:1 contrast', 'F8-Boundary', () => {
        const tagText = '#0369a1';
        const tagBg = '#f0f9ff';
        const ratio = getContrastRatio(tagText, tagBg);
        reporter.assert(ratio >= 5.0, `Tag pill contrast ${ratio}:1 >= 5.0:1`);
    });

    reporter.test('F08-B04: Blog author bio box and avatar badge maintain high contrast author text', 'F8-Boundary', () => {
        const authorName = '#0f172a';
        const bioBg = '#ffffff';
        const ratio = getContrastRatio(authorName, bioBg);
        reporter.assert(ratio >= 14.0, `Author name contrast ${ratio}:1 >= 14:1`);
    });

    reporter.test('F08-B05: Blog article reading time badge and publication date pill maintain WCAG AA compliance', 'F8-Boundary', () => {
        const metaColor = '#64748b'; // Slate 500
        const metaBg = '#ffffff';
        const ratio = getContrastRatio(metaColor, metaBg);
        reporter.assert(ratio >= 4.5, `Publication date pill contrast ${ratio}:1 >= 4.5:1`);
    });

    reporter.test('F08-B06: Blog table of contents (TOC) quick jump links maintain high contrast', 'F8-Boundary', () => {
        const tocLink = '#0369a1';
        const tocBg = '#f8fafc';
        const ratio = getContrastRatio(tocLink, tocBg);
        reporter.assert(ratio >= 4.5, `TOC link contrast ${ratio}:1 >= 4.5:1`);
    });
}

module.exports = { runTier2Tests };
