/**
 * W3C WCAG 2.1 Relative Luminance & Contrast Ratio Engine
 * Mathematical implementation of WCAG 2.1 AA / AAA specifications.
 * 
 * Formulas:
 *   Relative Luminance: L = 0.2126 * R_lin + 0.7152 * G_lin + 0.0722 * B_lin
 *   Contrast Ratio: (L1 + 0.05) / (L2 + 0.05), where L1 >= L2
 */

'use strict';

const NAMED_COLORS = {
    white: '#ffffff',
    black: '#000000',
    transparent: 'rgba(0,0,0,0)',
    slate: '#334155',
    navy: '#082f49',
    blue: '#0ea5e9',
    gray: '#64748b',
    light: '#f8fafc',
    dark: '#0f172a',
    red: '#ef4444',
    green: '#10b981',
    yellow: '#f59e0b',
    primary: '#0ea5e9',
    secondary: '#0c4a6e',
    accent: '#0284c7'
};

/**
 * Parses any CSS color string into an RGBA object: { r, g, b, a }
 * Supports: #rgb, #rgba, #rrggbb, #rrggbbaa, rgb(), rgba(), hsl(), hsla(), named colors.
 */
function parseColor(colorStr) {
    if (!colorStr || typeof colorStr !== 'string') {
        return null;
    }
    const clean = colorStr.trim().toLowerCase();

    if (NAMED_COLORS[clean]) {
        return parseColor(NAMED_COLORS[clean]);
    }

    // Hex formats
    if (clean.startsWith('#')) {
        const hex = clean.slice(1);
        if (hex.length === 3) {
            return {
                r: parseInt(hex[0] + hex[0], 16),
                g: parseInt(hex[1] + hex[1], 16),
                b: parseInt(hex[2] + hex[2], 16),
                a: 1.0
            };
        } else if (hex.length === 4) {
            return {
                r: parseInt(hex[0] + hex[0], 16),
                g: parseInt(hex[1] + hex[1], 16),
                b: parseInt(hex[2] + hex[2], 16),
                a: parseInt(hex[3] + hex[3], 16) / 255
            };
        } else if (hex.length === 6) {
            return {
                r: parseInt(hex.slice(0, 2), 16),
                g: parseInt(hex.slice(2, 4), 16),
                b: parseInt(hex.slice(4, 6), 16),
                a: 1.0
            };
        } else if (hex.length === 8) {
            return {
                r: parseInt(hex.slice(0, 2), 16),
                g: parseInt(hex.slice(2, 4), 16),
                b: parseInt(hex.slice(4, 6), 16),
                a: parseInt(hex.slice(6, 8), 16) / 255
            };
        }
    }

    // RGB / RGBA formats
    const rgbaMatch = clean.match(/^rgba?\s*\(\s*([0-9.]+)\s*,\s*([0-9.]+)\s*,\s*([0-9.]+)(?:\s*,\s*([0-9.]+))?\s*\)$/);
    if (rgbaMatch) {
        return {
            r: Math.min(255, Math.max(0, parseFloat(rgbaMatch[1]))),
            g: Math.min(255, Math.max(0, parseFloat(rgbaMatch[2]))),
            b: Math.min(255, Math.max(0, parseFloat(rgbaMatch[3]))),
            a: rgbaMatch[4] !== undefined ? Math.min(1, Math.max(0, parseFloat(rgbaMatch[4]))) : 1.0
        };
    }

    // HSL / HSLA formats
    const hslaMatch = clean.match(/^hsla?\s*\(\s*([0-9.]+)\s*,\s*([0-9.]+)%\s*,\s*([0-9.]+)%(?:\s*,\s*([0-9.]+))?\s*\)$/);
    if (hslaMatch) {
        const h = parseFloat(hslaMatch[1]) % 360;
        const s = parseFloat(hslaMatch[2]) / 100;
        const l = parseFloat(hslaMatch[3]) / 100;
        const a = hslaMatch[4] !== undefined ? parseFloat(hslaMatch[4]) : 1.0;

        const rgb = hslToRgb(h, s, l);
        return { r: rgb.r, g: rgb.g, b: rgb.b, a };
    }

    return null;
}

/**
 * Helper to convert HSL to RGB
 */
function hslToRgb(h, s, l) {
    const c = (1 - Math.abs(2 * l - 1)) * s;
    const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
    const m = l - c / 2;
    let r_ = 0, g_ = 0, b_ = 0;

    if (h >= 0 && h < 60) {
        r_ = c; g_ = x; b_ = 0;
    } else if (h >= 60 && h < 120) {
        r_ = x; g_ = c; b_ = 0;
    } else if (h >= 120 && h < 180) {
        r_ = 0; g_ = c; b_ = x;
    } else if (h >= 180 && h < 240) {
        r_ = 0; g_ = x; b_ = c;
    } else if (h >= 240 && h < 300) {
        r_ = x; g_ = 0; b_ = c;
    } else if (h >= 300 && h < 360) {
        r_ = c; g_ = 0; b_ = x;
    }

    return {
        r: Math.round((r_ + m) * 255),
        g: Math.round((g_ + m) * 255),
        b: Math.round((b_ + m) * 255)
    };
}

/**
 * Alpha composite a foreground color onto a background color
 * (Standard Porter-Duff source-over blending).
 */
function compositeColor(fg, bg) {
    const fgParsed = typeof fg === 'string' ? parseColor(fg) : fg;
    const bgParsed = typeof bg === 'string' ? parseColor(bg) : bg;

    if (!fgParsed || !bgParsed) {
        throw new Error(`Invalid colors for composite: fg=${JSON.stringify(fg)}, bg=${JSON.stringify(bg)}`);
    }

    const a = fgParsed.a + bgParsed.a * (1 - fgParsed.a);
    if (a === 0) {
        return { r: 0, g: 0, b: 0, a: 0 };
    }

    const r = Math.round((fgParsed.r * fgParsed.a + bgParsed.r * bgParsed.a * (1 - fgParsed.a)) / a);
    const g = Math.round((fgParsed.g * fgParsed.a + bgParsed.g * bgParsed.a * (1 - fgParsed.a)) / a);
    const b = Math.round((fgParsed.b * fgParsed.a + bgParsed.b * bgParsed.a * (1 - fgParsed.a)) / a);

    return { r, g, b, a };
}

/**
 * Calculates sRGB channel linear luminance component.
 */
function channelLuminance(c8bit) {
    const cLinear = c8bit / 255;
    if (cLinear <= 0.04045) {
        return cLinear / 12.92;
    }
    return Math.pow((cLinear + 0.055) / 1.055, 2.4);
}

/**
 * Calculates WCAG 2.1 relative luminance for an opaque RGB color:
 * L = 0.2126 * R_lin + 0.7152 * G_lin + 0.0722 * B_lin
 */
function getRelativeLuminance(color) {
    const parsed = typeof color === 'string' ? parseColor(color) : color;
    if (!parsed) {
        throw new Error(`Invalid color for luminance calculation: ${JSON.stringify(color)}`);
    }

    const rLin = channelLuminance(parsed.r);
    const gLin = channelLuminance(parsed.g);
    const bLin = channelLuminance(parsed.b);

    return 0.2126 * rLin + 0.7152 * gLin + 0.0722 * bLin;
}

/**
 * Calculates the exact WCAG 2.1 contrast ratio between two colors:
 * CR = (L1 + 0.05) / (L2 + 0.05), where L1 >= L2
 * If fg contains alpha (< 1.0), it is alpha-composited over bg before calculating.
 */
function getContrastRatio(fgColor, bgColor) {
    let fgParsed = typeof fgColor === 'string' ? parseColor(fgColor) : fgColor;
    let bgParsed = typeof bgColor === 'string' ? parseColor(bgColor) : bgColor;

    if (!fgParsed || !bgParsed) {
        throw new Error(`Invalid color inputs: fg=${JSON.stringify(fgColor)}, bg=${JSON.stringify(bgColor)}`);
    }

    // If fg has transparency, composite over bg
    if (fgParsed.a < 1.0) {
        fgParsed = compositeColor(fgParsed, bgParsed);
    }

    const lum1 = getRelativeLuminance(fgParsed);
    const lum2 = getRelativeLuminance(bgParsed);

    const brighter = Math.max(lum1, lum2);
    const darker = Math.min(lum1, lum2);

    const ratio = (brighter + 0.05) / (darker + 0.05);
    return Math.round(ratio * 100) / 100;
}

/**
 * WCAG AA & AAA Compliance Validators
 */
function isWCAG_AA_Normal(cr) {
    return cr >= 4.5;
}

function isWCAG_AA_Large(cr) {
    return cr >= 3.0;
}

function isWCAG_AAA_Normal(cr) {
    return cr >= 7.0;
}

function isWCAG_AAA_Large(cr) {
    return cr >= 4.5;
}

module.exports = {
    parseColor,
    hslToRgb,
    compositeColor,
    channelLuminance,
    getRelativeLuminance,
    getContrastRatio,
    isWCAG_AA_Normal,
    isWCAG_AA_Large,
    isWCAG_AAA_Normal,
    isWCAG_AAA_Large,
    NAMED_COLORS
};
