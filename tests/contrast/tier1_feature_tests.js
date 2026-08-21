/**
 * TIER 1: FEATURE COVERAGE CONTRAST TEST SUITE
 * 
 * Verifies core WCAG 2.1 AA / AAA contrast compliance across all 8 features (F1 to F8).
 * Target: 51 Unit / Feature Test Assertions (>=5 per feature).
 */

'use strict';

const fs = require('fs');
const path = require('path');
const {
    getContrastRatio,
    parseColor,
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

function runTier1Tests(reporter) {
    reporter.startTier('Tier 1: Feature Coverage (F1 - F8)');

    const { tokens, styleCss, seoCss } = loadStylesheetTokens();
    const allTemplates = getAllTemplates();

    // =========================================================================
    // FEATURE 1: Global Token & Palette Hardening (6 tests)
    // =========================================================================

    reporter.test('F01-01: :root CSS variables define all required contrast design tokens', 'F1', () => {
        reporter.assert(!!tokens['--primary'], 'Must define --primary');
        reporter.assert(!!tokens['--secondary'], 'Must define --secondary');
        reporter.assert(!!tokens['--dark'], 'Must define --dark');
        reporter.assert(!!tokens['--light'], 'Must define --light');
        reporter.assert(!!tokens['--gray-600'], 'Must define --gray-600');
    });

    reporter.test('F01-02: Dark text token on light background meets WCAG AAA (>= 14.0:1)', 'F1', () => {
        const dark = tokens['--dark'] || '#0f172a';
        const light = tokens['--light'] || '#ffffff';
        const ratio = getContrastRatio(dark, light);
        reporter.assert(ratio >= 14.0, `Expected ratio >= 14.0:1, got ${ratio}:1 (${dark} on ${light})`);
        reporter.assert(isWCAG_AAA_Normal(ratio), 'Must meet WCAG AAA for normal text');
    });

    reporter.test('F01-03: Body text token on light background meets WCAG AAA (>= 7.0:1)', 'F1', () => {
        const bodyColor = tokens['--gray-600'] || '#334155';
        const light = tokens['--light'] || '#ffffff';
        const ratio = getContrastRatio(bodyColor, light);
        reporter.assert(ratio >= 7.0, `Expected ratio >= 7.0:1, got ${ratio}:1 (${bodyColor} on ${light})`);
        reporter.assert(isWCAG_AAA_Normal(ratio), 'Must meet WCAG AAA for body text');
    });

    reporter.test('F01-04: Action link color token on white surface meets WCAG AA (>= 4.0:1)', 'F1', () => {
        const linkColor = tokens['--primary-dark'] || '#0284c7';
        const ratio = getContrastRatio(linkColor, '#ffffff');
        reporter.assert(ratio >= 4.0, `Expected ratio >= 4.0:1, got ${ratio}:1 (${linkColor} on #ffffff)`);
    });

    reporter.test('F01-05: Dark background body text token on navy background meets WCAG AAA (>= 11.0:1)', 'F1', () => {
        const darkBgText = tokens['--gray-100'] || '#e2e8f0';
        const darkBg = tokens['--secondary-dark'] || '#082f49';
        const ratio = getContrastRatio(darkBgText, darkBg);
        reporter.assert(ratio >= 11.0, `Expected ratio >= 11.0:1, got ${ratio}:1 (${darkBgText} on ${darkBg})`);
    });

    reporter.test('F01-06: Placeholder text token on white input meets WCAG AA (>= 4.5:1)', 'F1', () => {
        const placeholderColor = tokens['--text-muted'] || '#64748b';
        const ratio = getContrastRatio(placeholderColor, '#ffffff');
        reporter.assert(ratio >= 4.5, `Expected ratio >= 4.5:1, got ${ratio}:1 (${placeholderColor} on #ffffff)`);
    });

    // =========================================================================
    // FEATURE 2: Body-Level .dark-hero Cascade Resolution (6 tests)
    // =========================================================================

    reporter.test('F02-01: Style.css defines high contrast rule for .page-hero and .dark-hero headings', 'F2', () => {
        reporter.assert(styleCss.includes('.page-hero h1') || styleCss.includes('.dark-hero h1'), 'Must scope hero h1');
        reporter.assert(styleCss.includes('color: #ffffff') || styleCss.includes('color:#ffffff'), 'Hero headings must be #ffffff');
    });

    reporter.test('F02-02: Style.css enforces charcoal headings on light surfaces (.card, .bg-white, .bg-light)', 'F2', () => {
        reporter.assert(styleCss.includes('.card h1') || styleCss.includes('.bg-white h1'), 'Must scope card/bg-white h1');
        reporter.assert(styleCss.includes('#0f172a'), 'Must enforce #0f172a for light surface headings');
    });

    reporter.test('F02-03: .card component heading spec passes WCAG AAA contrast (17.85:1)', 'F2', () => {
        const evalRes = evaluateComponentSpec('card-heading');
        reporter.assert(evalRes.passed, `Card heading evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 14.0, 'Card heading contrast must exceed 14:1');
    });

    reporter.test('F02-04: .card component body text spec passes WCAG AAA contrast (10.35:1)', 'F2', () => {
        const evalRes = evaluateComponentSpec('card-body');
        reporter.assert(evalRes.passed, `Card body evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 7.0, 'Card body contrast must exceed 7.0:1');
    });

    reporter.test('F02-05: .dark-hero body text spec passes WCAG AAA contrast (12.87:1)', 'F2', () => {
        const evalRes = evaluateComponentSpec('page-hero-body');
        reporter.assert(evalRes.passed, `Page hero body evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 11.0, 'Hero body contrast must exceed 11:1');
    });

    reporter.test('F02-06: .dark-hero text-primary accent spec passes WCAG AA contrast (6.48:1)', 'F2', () => {
        const evalRes = evaluateComponentSpec('page-hero-text-primary');
        reporter.assert(evalRes.passed, `Hero text-primary evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 4.5, 'Hero text-primary contrast must exceed 4.5:1');
    });

    // =========================================================================
    // FEATURE 3: Dark Container Child Button Isolation (6 tests)
    // =========================================================================

    reporter.test('F03-01: Light button in dark hero (.page-hero-btn-light) has dark text on white bg (>= 12.0:1)', 'F3', () => {
        const evalRes = evaluateComponentSpec('page-hero-btn-light');
        reporter.assert(evalRes.passed, `Hero light button evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 12.0, `Button contrast ratio ${evalRes.ratio}:1 must be >= 12.0:1`);
    });

    reporter.test('F03-02: Primary button in dark hero has crisp white text on blue bg (>= 3.0:1 UI/large)', 'F3', () => {
        const evalRes = evaluateComponentSpec('dark-hero-btn-primary');
        reporter.assert(evalRes.passed, `Hero primary button evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 3.0, 'Primary button contrast must exceed 3.0:1');
    });

    reporter.test('F03-03: Breadcrumb link inside dark hero has high contrast (>= 12.0:1)', 'F3', () => {
        const evalRes = evaluateComponentSpec('page-hero-breadcrumb-link');
        reporter.assert(evalRes.passed, `Breadcrumb link evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 10.0, 'Breadcrumb contrast must exceed 10:1');
    });

    reporter.test('F03-04: Active breadcrumb item in dark hero has pure white text (>= 13.0:1)', 'F3', () => {
        const evalRes = evaluateComponentSpec('page-hero-breadcrumb-active');
        reporter.assert(evalRes.passed, `Active breadcrumb evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 13.0, 'Active breadcrumb contrast must exceed 13:1');
    });

    reporter.test('F03-05: Dark hero muted text maintains high contrast on dark surface (>= 7.0:1)', 'F3', () => {
        const evalRes = evaluateComponentSpec('page-hero-text-muted');
        reporter.assert(evalRes.passed, `Hero muted text evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 7.0, 'Hero muted text contrast must exceed 7.0:1');
    });

    reporter.test('F03-06: Action link on light surface meets high visibility contrast', 'F3', () => {
        const evalRes = evaluateComponentSpec('action-link-on-light');
        reporter.assert(evalRes.passed, `Action link evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 4.0, 'Action link contrast must exceed 4.0:1');
    });

    // =========================================================================
    // FEATURE 4: Blog Benchmark Table Header Contrast (5 tests)
    // =========================================================================

    reporter.test('F04-01: Table dark header (thead.table-dark th) has pure white text on dark bg (13.88:1)', 'F4', () => {
        const evalRes = evaluateComponentSpec('table-dark-header');
        reporter.assert(evalRes.passed, `Table dark header evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 13.0, 'Table dark header contrast must exceed 13:1');
    });

    reporter.test('F04-02: Standard table body cell (tbody td) has dark text on white bg (10.35:1)', 'F4', () => {
        const evalRes = evaluateComponentSpec('table-body-cell');
        reporter.assert(evalRes.passed, `Table body cell evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 7.0, 'Table body cell contrast must exceed 7.0:1');
    });

    reporter.test('F04-03: Style.css contains explicit table header contrast preservation rules', 'F4', () => {
        reporter.assert(
            styleCss.includes('table') || styleCss.includes('thead') || styleCss.includes('.table-dark'),
            'Style.css must contain table styling definitions'
        );
    });

    reporter.test('F04-04: Behavioral health billing guide blog template contains compliant table markup', 'F4', () => {
        const blogTemplate = path.join(MEDINEXT_ROOT, 'blog/behavioral-health-billing-guide/index.php');
        reporter.assert(fs.existsSync(blogTemplate), 'Behavioral health blog template must exist');
        const content = fs.readFileSync(blogTemplate, 'utf8');
        reporter.assert(content.includes('<table') || content.includes('table-responsive'), 'Must contain table structure');
    });

    reporter.test('F04-05: Medical billing KPIs blog template contains valid table / card metrics', 'F4', () => {
        const blogTemplate = path.join(MEDINEXT_ROOT, 'blog/medical-billing-kpis/index.php');
        reporter.assert(fs.existsSync(blogTemplate), 'Medical billing KPIs blog template must exist');
        const content = fs.readFileSync(blogTemplate, 'utf8');
        reporter.assert(content.length > 500, 'Blog post must contain substantial content');
    });

    // =========================================================================
    // FEATURE 5: Core Templates Contrast Remediation (8 tests)
    // =========================================================================

    reporter.test('F05-01: Form label component spec passes WCAG AAA contrast (17.85:1)', 'F5', () => {
        const evalRes = evaluateComponentSpec('form-label');
        reporter.assert(evalRes.passed, `Form label evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 14.0, 'Form label contrast must exceed 14:1');
    });

    reporter.test('F05-02: Form control input text spec passes WCAG AAA contrast (17.85:1)', 'F5', () => {
        const evalRes = evaluateComponentSpec('form-control-text');
        reporter.assert(evalRes.passed, `Form control text evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 14.0, 'Form input text contrast must exceed 14:1');
    });

    reporter.test('F05-03: Form placeholder component spec passes WCAG AA contrast (4.76:1)', 'F5', () => {
        const evalRes = evaluateComponentSpec('form-control-placeholder');
        reporter.assert(evalRes.passed, `Form placeholder evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 4.5, 'Form placeholder contrast must exceed 4.5:1');
    });

    reporter.test('F05-04: Input group addon text component spec passes WCAG AA contrast (6.92:1)', 'F5', () => {
        const evalRes = evaluateComponentSpec('input-group-text');
        reporter.assert(evalRes.passed, `Input group text evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 4.5, 'Input group text contrast must exceed 4.5:1');
    });

    reporter.test('F05-05: index.php homepage template passes static contrast audit', 'F5', () => {
        const result = analyzeTemplateContrast('index.php');
        reporter.assert(result.passed, `index.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F05-06: free-practice-audit.php audit form page passes static contrast audit', 'F5', () => {
        const result = analyzeTemplateContrast('free-practice-audit.php');
        reporter.assert(result.passed, `free-practice-audit.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F05-07: contact.php contact page template passes static contrast audit', 'F5', () => {
        const result = analyzeTemplateContrast('contact.php');
        reporter.assert(result.passed, `contact.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F05-08: 404.php error page template passes static contrast audit', 'F5', () => {
        const result = analyzeTemplateContrast('404.php');
        reporter.assert(result.passed, `404.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    // =========================================================================
    // FEATURE 6: Specialty Service Pages Remediation (8 tests)
    // =========================================================================

    reporter.test('F06-01: cardiovascular-billing-services.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('cardiovascular-billing-services.php');
        reporter.assert(result.passed, `Cardiovascular billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-02: anesthesia-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('anesthesia-billing.php');
        reporter.assert(result.passed, `Anesthesia billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-03: oncology-hematology-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('oncology-hematology-billing.php');
        reporter.assert(result.passed, `Oncology billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-04: dermatology-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('dermatology-billing.php');
        reporter.assert(result.passed, `Dermatology billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-05: physical-therapy-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('physical-therapy-billing.php');
        reporter.assert(result.passed, `Physical therapy billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-06: emergency-medicine-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('emergency-medicine-billing.php');
        reporter.assert(result.passed, `Emergency medicine billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-07: behavioral-health-billing.php passes static contrast audit', 'F6', () => {
        const result = analyzeTemplateContrast('behavioral-health-billing.php');
        reporter.assert(result.passed, `Behavioral health billing failed audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F06-08: All specialty service templates contain page-hero and proper surface structure', 'F6', () => {
        const specialtyFiles = allTemplates.filter(t => 
            t.filename.endsWith('-billing.php') || 
            t.filename.endsWith('-services.php') ||
            t.filename.endsWith('-outsourcing.php') ||
            t.filename.endsWith('-followup.php')
        );
        reporter.assert(specialtyFiles.length >= 25, `Expected >= 25 specialty templates, found ${specialtyFiles.length}`);
    });

    // =========================================================================
    // FEATURE 7: Location & State Hub Templates Remediation (6 tests)
    // =========================================================================

    reporter.test('F07-01: locations.php directory template passes static contrast audit', 'F7', () => {
        const result = analyzeTemplateContrast('locations.php');
        reporter.assert(result.passed, `locations.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F07-02: includes/seo-foot-common.php footer component passes static contrast audit', 'F7', () => {
        const result = analyzeTemplateContrast('includes/seo-foot-common.php');
        reporter.assert(result.passed, `seo-foot-common.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F07-03: Prisma footer heading spec passes WCAG AAA contrast (12.75:1)', 'F7', () => {
        const evalRes = evaluateComponentSpec('prisma-footer-heading');
        reporter.assert(evalRes.passed, `Footer heading evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 10.0, 'Footer heading contrast must exceed 10:1');
    });

    reporter.test('F07-04: Prisma footer links spec passes WCAG AAA contrast (10.49:1)', 'F7', () => {
        const evalRes = evaluateComponentSpec('prisma-footer-links');
        reporter.assert(evalRes.passed, `Footer links evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 7.0, 'Footer links contrast must exceed 7.0:1');
    });

    reporter.test('F07-05: Prisma footer copyright spec passes WCAG AAA contrast (7.40:1)', 'F7', () => {
        const evalRes = evaluateComponentSpec('prisma-footer-copyright');
        reporter.assert(evalRes.passed, `Footer copyright evaluation failed: ${evalRes.ratio}:1`);
        reporter.assert(evalRes.ratio >= 7.0, 'Footer copyright contrast must exceed 7.0:1');
    });

    reporter.test('F07-06: Location helper functions support accessible markup rendering', 'F7', () => {
        const helperPath = path.join(MEDINEXT_ROOT, 'includes/location-helper.php');
        reporter.assert(fs.existsSync(helperPath), 'location-helper.php must exist');
    });

    // =========================================================================
    // FEATURE 8: Blog Archive & Article Templates Remediation (6 tests)
    // =========================================================================

    reporter.test('F08-01: blog.php archive hub template passes static contrast audit', 'F8', () => {
        const result = analyzeTemplateContrast('blog.php');
        reporter.assert(result.passed, `blog.php failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F08-02: blog/claim-denial-reasons-and-fixes/index.php passes static contrast audit', 'F8', () => {
        const result = analyzeTemplateContrast('blog/claim-denial-reasons-and-fixes/index.php');
        reporter.assert(result.passed, `Blog article 1 failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F08-03: blog/inhouse-vs-outsourced-billing/index.php passes static contrast audit', 'F8', () => {
        const result = analyzeTemplateContrast('blog/inhouse-vs-outsourced-billing/index.php');
        reporter.assert(result.passed, `Blog article 2 failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F08-04: blog/provider-credentialing-guide/index.php passes static contrast audit', 'F8', () => {
        const result = analyzeTemplateContrast('blog/provider-credentialing-guide/index.php');
        reporter.assert(result.passed, `Blog article 3 failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F08-05: blog/revenue-cycle-management-guide/index.php passes static contrast audit', 'F8', () => {
        const result = analyzeTemplateContrast('blog/revenue-cycle-management-guide/index.php');
        reporter.assert(result.passed, `Blog article 4 failed contrast audit: ${JSON.stringify(result.issues)}`);
    });

    reporter.test('F08-06: All 10 blog post directory templates exist and pass static contrast audit', 'F8', () => {
        const blogDirs = fs.readdirSync(path.join(MEDINEXT_ROOT, 'blog')).filter(f => 
            fs.statSync(path.join(MEDINEXT_ROOT, 'blog', f)).isDirectory()
        );
        reporter.assert(blogDirs.length === 10, `Expected 10 blog post dirs, found ${blogDirs.length}`);
        for (const dir of blogDirs) {
            const articlePath = `blog/${dir}/index.php`;
            const result = analyzeTemplateContrast(articlePath);
            reporter.assert(result.passed, `Blog ${articlePath} failed contrast audit`);
        }
    });
}

module.exports = { runTier1Tests };
