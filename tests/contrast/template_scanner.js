/**
 * Static AST & DOM Template Scanner
 * Analyzes all .php and .html templates in Medinext_solutions/
 * for inline styles, CSS color classes, container surface hierarchy, and contrast traps.
 */

'use strict';

const fs = require('fs');
const path = require('path');
const { getContrastRatio, parseColor, isWCAG_AA_Normal, isWCAG_AA_Large } = require('./contrast_calculator');

const MEDINEXT_ROOT = path.resolve(__dirname, '../../Medinext_solutions');

/**
 * Recursively retrieves all .php and .html template files in Medinext_solutions.
 * Excludes tests and vendor folders.
 */
function getAllTemplates(dir = MEDINEXT_ROOT) {
    let results = [];
    if (!fs.existsSync(dir)) {
        return results;
    }

    const list = fs.readdirSync(dir);
    for (const file of list) {
        const fullPath = path.join(dir, file);
        const stat = fs.statSync(fullPath);

        if (stat && stat.isDirectory()) {
            if (file === 'tests' || file === 'vendor' || file === 'logs' || file === '.well-known') {
                continue;
            }
            results = results.concat(getAllTemplates(fullPath));
        } else {
            const ext = path.extname(file).toLowerCase();
            if (ext === '.php' || ext === '.html') {
                results.push({
                    fullPath,
                    relativePath: path.relative(MEDINEXT_ROOT, fullPath).replace(/\\/g, '/'),
                    filename: file
                });
            }
        }
    }
    return results;
}

/**
 * Extracts inline style declarations from HTML / PHP content.
 */
function extractInlineStyles(content) {
    const regex = /<([a-z0-9-]+)\b([^>]*\bstyle=["']([^"']+)["'][^>]*)>/gi;
    const inlineStyles = [];
    let match;

    while ((match = regex.exec(content)) !== null) {
        const tag = match[1].toLowerCase();
        const attributes = match[2];
        const styleStr = match[3];

        const declarations = {};
        const parts = styleStr.split(';');
        for (const part of parts) {
            const colonIdx = part.indexOf(':');
            if (colonIdx !== -1) {
                const prop = part.slice(0, colonIdx).trim().toLowerCase();
                const val = part.slice(colonIdx + 1).trim().toLowerCase().replace('!important', '').trim();
                if (prop && val) {
                    declarations[prop] = val;
                }
            }
        }

        inlineStyles.push({
            tag,
            style: styleStr,
            declarations,
            rawAttributes: attributes
        });
    }

    return inlineStyles;
}

/**
 * Extracts class occurrences on elements from template content.
 */
function extractElementsWithClasses(content) {
    const regex = /<([a-z0-9-]+)\b([^>]*\bclass=["']([^"']+)["'][^>]*)>/gi;
    const elements = [];
    let match;

    while ((match = regex.exec(content)) !== null) {
        const tag = match[1].toLowerCase();
        const rawAttr = match[2];
        const classStr = match[3];
        const classes = classStr.split(/\s+/).filter(Boolean);

        elements.push({
            tag,
            classStr,
            classes,
            rawAttr
        });
    }

    return elements;
}

/**
 * Identifies container hierarchies and checks for surface contrast traps.
 */
function analyzeTemplateContrast(filePath) {
    const fullPath = path.isAbsolute(filePath) ? filePath : path.join(MEDINEXT_ROOT, filePath);
    if (!fs.existsSync(fullPath)) {
        throw new Error(`Template not found: ${fullPath}`);
    }

    const content = fs.readFileSync(fullPath, 'utf8');
    const inlineStyles = extractInlineStyles(content);
    const elements = extractElementsWithClasses(content);

    const issues = [];
    const colorAudit = {
        hasDarkHero: /class=["'][^"']*\b(dark-hero|page-hero|hero-section|svc-hero|cta-section)\b[^"']*["']/i.test(content),
        hasLightCard: /class=["'][^"']*\b(card|svc-card|bg-light|bg-white|section-light)\b[^"']*["']/i.test(content),
        hasFormControls: /class=["'][^"']*\b(form-control|form-select|form-label)\b[^"']*["']/i.test(content),
        hasTables: /<table\b/i.test(content),
        hasButtons: /class=["'][^"']*\bbtn\b[^"']*["']/i.test(content),
        inlineStyleCount: inlineStyles.length,
        elementCount: elements.length
    };

    // Check inline styles for color collisions
    for (const styleItem of inlineStyles) {
        const color = styleItem.declarations['color'];
        const bg = styleItem.declarations['background-color'] || styleItem.declarations['background'];

        if (color && bg && !bg.includes('url') && !bg.includes('gradient')) {
            const parsedColor = parseColor(color);
            const parsedBg = parseColor(bg);

            if (parsedColor && parsedBg) {
                const ratio = getContrastRatio(parsedColor, parsedBg);
                const isLarge = ['h1', 'h2', 'h3', 'h4'].includes(styleItem.tag);
                const minRatio = isLarge ? 3.0 : 4.5;

                if (ratio < minRatio) {
                    issues.push({
                        type: 'INLINE_LOW_CONTRAST',
                        tag: styleItem.tag,
                        style: styleItem.style,
                        ratio,
                        required: minRatio,
                        message: `Inline style on <${styleItem.tag}> has low contrast ratio ${ratio}:1 (requires >= ${minRatio}:1)`
                    });
                }
            }
        }
    }

    return {
        filePath: fullPath,
        relativePath: path.relative(MEDINEXT_ROOT, fullPath).replace(/\\/g, '/'),
        colorAudit,
        inlineStyles,
        elements,
        issues,
        passed: issues.length === 0
    };
}

module.exports = {
    MEDINEXT_ROOT,
    getAllTemplates,
    extractInlineStyles,
    extractElementsWithClasses,
    analyzeTemplateContrast
};
