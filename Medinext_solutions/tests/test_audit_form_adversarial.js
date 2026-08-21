/**
 * MEDINEXT SOLUTIONS - Milestone 1 Interactive & Client Validation Adversarial Test Harness
 * Targets: AuditForm in Medinext_solutions/assets/js/main.js & free-practice-audit.php
 */

const fs = require('fs');
const path = require('path');

// ANSI Color Helpers
const colors = {
    reset: '\x1b[0m',
    green: '\x1b[32m',
    red: '\x1b[31m',
    yellow: '\x1b[33m',
    blue: '\x1b[34m',
    cyan: '\x1b[36m',
    bold: '\x1b[1m'
};

let totalTests = 0;
let passedTests = 0;
let failedTests = 0;
const failureDetails = [];

function assert(condition, testName, details = '') {
    totalTests++;
    if (condition) {
        passedTests++;
        console.log(`  ${colors.green}✓ PASS${colors.reset}: ${testName}`);
    } else {
        failedTests++;
        console.log(`  ${colors.red}✗ FAIL${colors.reset}: ${testName} ${details ? `(${details})` : ''}`);
        failureDetails.push({ testName, details });
    }
}

function assertEqual(actual, expected, testName) {
    const pass = actual === expected;
    assert(pass, testName, `Expected: ${JSON.stringify(expected)}, Got: ${JSON.stringify(actual)}`);
}

function assertThrows(fn, testName) {
    totalTests++;
    try {
        fn();
        failedTests++;
        console.log(`  ${colors.red}✗ FAIL${colors.reset}: ${testName} (Expected exception but none was thrown)`);
        failureDetails.push({ testName, details: 'Expected exception but none was thrown' });
    } catch (e) {
        passedTests++;
        console.log(`  ${colors.green}✓ PASS${colors.reset}: ${testName}`);
    }
}

console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}`);
console.log(`${colors.bold}${colors.cyan} MEDINEXT SOLUTIONS - Practice Audit Interactive Adversarial Test Suite${colors.reset}`);
console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}\n`);

// Read main.js and free-practice-audit.php
const mainJsPath = path.resolve(__dirname, '../assets/js/main.js');
const freePracticeAuditPhpPath = path.resolve(__dirname, '../free-practice-audit.php');

if (!fs.existsSync(mainJsPath)) {
    console.error(`Error: ${mainJsPath} does not exist`);
    process.exit(1);
}

const mainJsCode = fs.readFileSync(mainJsPath, 'utf8');
const phpTemplateCode = fs.readFileSync(freePracticeAuditPhpPath, 'utf8');

// Build robust Mock DOM classes
class MockElement {
    constructor(tagName = 'div', id = '', name = '') {
        this.tagName = tagName.toUpperCase();
        this.id = id;
        this.name = name;
        this.value = '';
        this.textContent = '';
        this.innerHTML = '';
        
        const set = new Set();
        this.classList = {
            add: (...classes) => classes.forEach(c => c && set.add(c)),
            remove: (...classes) => classes.forEach(c => set.delete(c)),
            contains: (c) => set.has(c),
            toggle: (c) => {
                if (set.has(c)) { set.delete(c); return false; }
                else { set.add(c); return true; }
            }
        };

        this.attributes = {};
        this.style = {};
        this.children = [];
        this.parentElement = null;
        this.nextElementSibling = null;
        this.listeners = {};
        this.checked = false;
        this.disabled = false;
        this.required = false;
        this.dataset = {};
        this.selectionStart = 0;
        this.selectionEnd = 0;
        this.focused = false;
    }

    setAttribute(name, val) {
        this.attributes[name] = String(val);
    }
    getAttribute(name) {
        return this.attributes[name] !== undefined ? this.attributes[name] : null;
    }
    removeAttribute(name) {
        delete this.attributes[name];
    }
    hasAttribute(name) {
        return this.attributes[name] !== undefined;
    }

    addEventListener(event, handler) {
        if (!this.listeners[event]) this.listeners[event] = [];
        this.listeners[event].push(handler);
    }
    dispatchEvent(event) {
        const list = this.listeners[event.type] || [];
        list.forEach(h => h(event));
    }

    querySelector(selector) {
        return this._findInTree(this, selector);
    }
    querySelectorAll(selector) {
        const results = [];
        this._findAllInTree(this, selector, results);
        return results;
    }
    closest(selector) {
        let curr = this;
        while (curr) {
            if (this._matches(curr, selector)) return curr;
            curr = curr.parentElement;
        }
        return null;
    }
    scrollIntoView() {}
    focus() { this.focused = true; }
    reset() { 
        this.value = ''; 
        this.querySelectorAll('input, select, textarea').forEach(el => {
            el.value = '';
            el.checked = false;
        });
    }
    setSelectionRange(start, end) { this.selectionStart = start; this.selectionEnd = end; }

    _matches(el, sel) {
        if (!sel || !el) return false;
        const s = sel.trim();
        if (s.startsWith('.')) {
            return el.classList.contains(s.substring(1));
        }
        if (s.startsWith('#')) {
            return el.id === s.substring(1);
        }
        if (s.startsWith('[name="') && s.endsWith('"]')) {
            const name = s.slice(7, -2);
            return el.name === name || el.getAttribute('name') === name;
        }
        if (s.startsWith('[id="') && s.endsWith('"]')) {
            const id = s.slice(5, -2);
            return el.id === id;
        }
        if (s.includes('input[type="checkbox"]')) {
            return el.tagName === 'INPUT' && (el.getAttribute('type') === 'checkbox' || el.type === 'checkbox');
        }
        if (s.includes('input[type="tel"]')) {
            return el.tagName === 'INPUT' && (el.getAttribute('type') === 'tel' || el.classList.contains('phone-mask'));
        }
        if (s.includes('input[name="phone"]')) {
            return el.name === 'phone' || el.getAttribute('name') === 'phone';
        }
        if (s.includes('input[name="zip_code"]')) {
            return el.name === 'zip_code' || el.getAttribute('name') === 'zip_code';
        }
        if (s.includes('.zip-mask')) {
            return el.classList.contains('zip-mask');
        }
        if (s.includes('.phone-mask')) {
            return el.classList.contains('phone-mask');
        }
        if (s.includes('button[type="submit"]')) {
            return el.tagName === 'BUTTON' && (el.getAttribute('type') === 'submit' || el.type === 'submit');
        }
        if (s.includes('textarea')) {
            return el.tagName === 'TEXTAREA';
        }
        return el.tagName.toLowerCase() === s.toLowerCase();
    }

    _findInTree(root, selector) {
        const parts = selector.split(',').map(p => p.trim());
        for (const child of root.children) {
            for (const p of parts) {
                if (this._matches(child, p)) return child;
            }
            const found = this._findInTree(child, selector);
            if (found) return found;
        }
        return null;
    }

    _findAllInTree(root, selector, results) {
        const parts = selector.split(',').map(p => p.trim());
        for (const child of root.children) {
            let matched = false;
            for (const p of parts) {
                if (this._matches(child, p)) {
                    results.push(child);
                    matched = true;
                    break;
                }
            }
            this._findAllInTree(child, selector, results);
        }
    }
}

class MockFormData {
    constructor(formElement) {
        this.data = new Map();
        if (formElement) {
            const inputs = formElement.querySelectorAll('input, select, textarea');
            inputs.forEach(inp => {
                const name = inp.name || inp.getAttribute('name');
                if (!name) return;
                if (inp.getAttribute('type') === 'checkbox' || inp.type === 'checkbox') {
                    if (inp.checked) {
                        const existing = this.data.get(name) || [];
                        existing.push(inp.value);
                        this.data.set(name, existing);
                    }
                } else {
                    this.data.set(name, inp.value);
                }
            });
        }
    }
    get(key) {
        const val = this.data.get(key);
        if (Array.isArray(val)) return val[0];
        return val !== undefined ? val : null;
    }
    getAll(key) {
        const val = this.data.get(key);
        if (Array.isArray(val)) return val;
        if (val !== undefined) return [val];
        return [];
    }
    set(key, val) {
        this.data.set(key, val);
    }
    append(key, val) {
        const existing = this.data.get(key);
        if (existing !== undefined) {
            if (Array.isArray(existing)) existing.push(val);
            else this.data.set(key, [existing, val]);
        } else {
            this.data.set(key, val);
        }
    }
}

// Global document mock registry
const mockDocRegistry = new Map();
const mockDocument = {
    getElementById(id) {
        return mockDocRegistry.get(id) || null;
    },
    dispatchEvent(e) {},
    addEventListener(e, h) {}
};

// Mock XMLHttpRequest
let xhrMockHandler = null;
class MockXMLHttpRequest {
    constructor() {
        this.headers = {};
        this.status = 200;
        this.statusText = 'OK';
        this.responseText = '';
        this.onload = null;
        this.onerror = null;
        this.ontimeout = null;
        this.timeout = 0;
    }
    open(method, url, async) {
        this.method = method;
        this.url = url;
    }
    setRequestHeader(k, v) {
        this.headers[k] = v;
    }
    send(data) {
        if (xhrMockHandler) {
            xhrMockHandler(this, data);
        } else {
            this.status = 200;
            this.responseText = JSON.stringify({ success: true, message: 'XHR fallback success' });
            if (this.onload) this.onload();
        }
    }
}

// Mock Fetch
let fetchMockHandler = null;
globalThis.fetch = (url, options) => {
    if (fetchMockHandler) {
        return fetchMockHandler(url, options);
    }
    return Promise.resolve({
        ok: true,
        text: () => Promise.resolve('{"success": true, "message": "Success"}')
    });
};

// Extract AuditForm object from mainJsCode using sandbox
const auditFormMatch = mainJsCode.match(/const AuditForm = (\{[\s\S]*?\n\};)/);
if (!auditFormMatch) {
    console.error("Error: Could not find `const AuditForm = { ... };` in main.js");
    process.exit(1);
}

const AuditFormObj = new Function(`
    let FormData = arguments[0];
    let CustomEvent = arguments[1];
    let Event = arguments[2];
    let document = arguments[3];
    let XMLHttpRequest = arguments[4];
    ${auditFormMatch[0]}
    return AuditForm;
`)(MockFormData, class CustomEvent { constructor(type, detail) { this.type = type; this.detail = detail; } }, class Event { constructor(type, opts) { this.type = type; this.opts = opts; } }, mockDocument, MockXMLHttpRequest);

console.log(`${colors.yellow}>>> TEST SUITE 1: Phone Mask Formatting Stress Tests${colors.reset}`);
{
    assertEqual(AuditFormObj.formatPhoneNumber('1234567890'), '(123) 456-7890', 'Format 10 clean digits: 1234567890');
    assertEqual(AuditFormObj.formatPhoneNumber('(123) 456-7890'), '(123) 456-7890', 'Format already formatted: (123) 456-7890');
    assertEqual(AuditFormObj.formatPhoneNumber('abc123def456ghi7890xyz'), '(123) 456-7890', 'Format digits mixed with letters');
    assertEqual(AuditFormObj.formatPhoneNumber('1'), '(1', 'Partial 1 digit: "1" -> "(1"');
    assertEqual(AuditFormObj.formatPhoneNumber('123'), '(123', 'Partial 3 digits: "123" -> "(123"');
    assertEqual(AuditFormObj.formatPhoneNumber('1234'), '(123) 4', 'Partial 4 digits: "1234" -> "(123) 4"');
    assertEqual(AuditFormObj.formatPhoneNumber('123456'), '(123) 456', 'Partial 6 digits: "123456" -> "(123) 456"');
    assertEqual(AuditFormObj.formatPhoneNumber('1234567'), '(123) 456-7', 'Partial 7 digits: "1234567" -> "(123) 456-7"');
    assertEqual(AuditFormObj.formatPhoneNumber('1234567890123456789'), '(123) 456-7890', 'Overflow 19 digits capped to 10 digits');
    assertEqual(AuditFormObj.formatPhoneNumber(''), '', 'Empty string returns empty string');
    assertEqual(AuditFormObj.formatPhoneNumber('   '), '', 'Whitespace-only returns empty string');
    assertEqual(AuditFormObj.formatPhoneNumber(null), '', 'null input handled safely without throwing');
    assertEqual(AuditFormObj.formatPhoneNumber(undefined), '', 'undefined input handled safely');
    assertEqual(AuditFormObj.formatPhoneNumber(8005550199), '(800) 555-0199', 'Number input 8005550199 formatted correctly');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 2: ZIP Code Masking Logic Tests${colors.reset}`);
{
    function applyZipMask(val) {
        let clean = ('' + val).replace(/\D/g, '');
        if (clean.length > 5) clean = clean.substring(0, 5);
        return clean;
    }

    assertEqual(applyZipMask('32804'), '32804', 'Standard 5-digit ZIP "32804"');
    assertEqual(applyZipMask('32804-1234'), '32804', 'ZIP+4 stripped to 5 numeric digits');
    assertEqual(applyZipMask('ABC32D80E4XYZ'), '32804', 'ZIP with alphanumeric junk filtered to digits');
    assertEqual(applyZipMask('902109999999'), '90210', 'Long ZIP string capped strictly at 5 digits');
    assertEqual(applyZipMask(''), '', 'Empty ZIP string remains empty');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 3: Field Validation Edge Cases & Boundaries${colors.reset}`);
{
    // Test 3.1: practice_name boundary
    assertEqual(AuditFormObj.validateField('practice_name', ''), 'Please enter your practice or facility name.', 'practice_name empty returns error');
    assertEqual(AuditFormObj.validateField('practice_name', 'A'), 'Please enter your practice or facility name.', 'practice_name 1 char (<2) returns error');
    assertEqual(AuditFormObj.validateField('practice_name', 'AB'), null, 'practice_name 2 chars is valid (null)');
    assertEqual(AuditFormObj.validateField('practice_name', 'A'.repeat(150)), null, 'practice_name 150 chars is valid');
    assertEqual(AuditFormObj.validateField('practice_name', 'A'.repeat(151)), 'Practice name must be under 150 characters.', 'practice_name 151 chars returns length error');

    // Test 3.2: contact_name boundary
    assertEqual(AuditFormObj.validateField('contact_name', ''), 'Please enter primary contact full name.', 'contact_name empty returns error');
    assertEqual(AuditFormObj.validateField('contact_name', 'D'), 'Please enter primary contact full name.', 'contact_name 1 char (<2) returns error');
    assertEqual(AuditFormObj.validateField('contact_name', 'Dr. Smith'), null, 'contact_name valid string returns null');
    assertEqual(AuditFormObj.validateField('contact_name', 'B'.repeat(100)), null, 'contact_name 100 chars is valid');
    assertEqual(AuditFormObj.validateField('contact_name', 'B'.repeat(101)), 'Name must be under 100 characters.', 'contact_name 101 chars returns length error');

    // Test 3.3: job_title
    assertEqual(AuditFormObj.validateField('job_title', ''), 'Please specify your job title or role.', 'job_title empty returns error');
    assertEqual(AuditFormObj.validateField('job_title', 'Practice Administrator'), null, 'job_title valid option returns null');

    // Test 3.4: email regex adversarial test cases
    const invalidEmails = [
        '',
        '   ',
        'plainaddress',
        '@missingusername.com',
        'username@.com',
        'username@domain',
        'user name@domain.com',
        'username@ domain.com'
    ];
    invalidEmails.forEach(inv => {
        assert(AuditFormObj.validateField('email', inv) !== null, `Invalid email "${inv}" correctly rejected`);
    });

    const validEmails = [
        'doctor@practice.com',
        'first.last@hospital.org',
        'billing+audit@clinic.med.pro',
        'sarah_jenkins123@sub-domain.clinic.co.uk'
    ];
    validEmails.forEach(val => {
        assertEqual(AuditFormObj.validateField('email', val), null, `Valid email "${val}" correctly accepted`);
    });

    // Test 3.5: phone validation
    assertEqual(AuditFormObj.validateField('phone', ''), 'Please enter a valid 10-digit phone number.', 'phone empty returns error');
    assertEqual(AuditFormObj.validateField('phone', '555-1234'), 'Please enter a valid 10-digit phone number.', 'phone 7 digits returns error');
    assertEqual(AuditFormObj.validateField('phone', '(555) 123-4567'), null, 'phone formatted 10 digits returns null');
    assertEqual(AuditFormObj.validateField('phone', '15551234567'), null, 'phone 11 digits returns null');

    // Test 3.6: street_address & city & state
    assertEqual(AuditFormObj.validateField('street_address', '12'), 'Please enter your practice street address.', 'street_address 2 chars (<3) returns error');
    assertEqual(AuditFormObj.validateField('street_address', '123 Main St'), null, 'street_address valid returns null');
    assertEqual(AuditFormObj.validateField('city', 'A'), 'Please enter your city.', 'city 1 char (<2) returns error');
    assertEqual(AuditFormObj.validateField('city', 'Orlando'), null, 'city "Orlando" returns null');
    assertEqual(AuditFormObj.validateField('state', ''), 'Please select your state.', 'state empty returns error');
    assertEqual(AuditFormObj.validateField('state', 'FL'), null, 'state "FL" returns null');

    // Test 3.7: zip_code
    assertEqual(AuditFormObj.validateField('zip_code', '3280'), 'Please enter a valid 5-digit ZIP code.', 'zip 4 digits returns error');
    assertEqual(AuditFormObj.validateField('zip_code', '32804'), null, 'zip 5 digits returns null');
    assertEqual(AuditFormObj.validateField('zip_code', '328041234'), null, 'zip 9 digits returns null');
    assertEqual(AuditFormObj.validateField('zip_code', '328041'), 'Please enter a valid 5-digit ZIP code.', 'zip 6 digits returns error');
    assertEqual(AuditFormObj.validateField('zip_code', 'ABCDE'), 'Please enter a valid 5-digit ZIP code.', 'zip letters returns error');

    // Test 3.8: specialty, patient_volume, monthly_revenue, current_ehr
    assertEqual(AuditFormObj.validateField('specialty', ''), 'Please select your primary specialty.', 'specialty empty returns error');
    assertEqual(AuditFormObj.validateField('specialty', 'Cardiology'), null, 'specialty "Cardiology" returns null');
    assertEqual(AuditFormObj.validateField('patient_volume', ''), 'Please select monthly patient volume.', 'patient_volume empty returns error');
    assertEqual(AuditFormObj.validateField('patient_volume', '501 - 1,000 visits / month'), null, 'patient_volume valid returns null');
    assertEqual(AuditFormObj.validateField('monthly_revenue', ''), 'Please select estimated monthly revenue.', 'monthly_revenue empty returns error');
    assertEqual(AuditFormObj.validateField('monthly_revenue', '$100,001 - $250,000 / month'), null, 'monthly_revenue valid returns null');
    assertEqual(AuditFormObj.validateField('current_ehr', ''), 'Please specify your current EHR / PMS software.', 'current_ehr empty returns error');
    assertEqual(AuditFormObj.validateField('current_ehr', 'Epic Systems'), null, 'current_ehr valid returns null');

    // Test 3.9: additional_notes length limit
    assertEqual(AuditFormObj.validateField('additional_notes', 'A'.repeat(2000)), null, 'notes 2000 chars is valid');
    assertEqual(AuditFormObj.validateField('additional_notes', 'A'.repeat(2001)), 'Notes cannot exceed 2000 characters.', 'notes 2001 chars returns error');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 4: Full Form Validation Schema Testing${colors.reset}`);
{
    // Empty form test
    const emptyFormData = new MockFormData();
    const emptyErrors = AuditFormObj.validateForm(emptyFormData);
    assert(Object.keys(emptyErrors).length === 13, 'Empty form detects all 13 required field errors', `Found ${Object.keys(emptyErrors).length} errors: ${Object.keys(emptyErrors).join(', ')}`);

    // Fully populated valid form test
    const validFormData = new MockFormData();
    validFormData.set('practice_name', 'Sunrise Medical Clinic');
    validFormData.set('contact_name', 'Dr. Sarah Jenkins');
    validFormData.set('job_title', 'Practice Owner / Physician / Clinician');
    validFormData.set('email', 's.jenkins@sunrisemed.com');
    validFormData.set('phone', '(555) 345-6789');
    validFormData.set('street_address', '1317 Edgewater Dr');
    validFormData.set('city', 'Orlando');
    validFormData.set('state', 'FL');
    validFormData.set('zip_code', '32804');
    validFormData.set('specialty', 'Cardiology & Cardiovascular Services');
    validFormData.set('patient_volume', '501 - 1,000 visits / month');
    validFormData.set('monthly_revenue', '$100,001 - $250,000 / month');
    validFormData.set('current_ehr', 'Athenahealth (athenaOne)');
    validFormData.set('additional_notes', 'Looking to reduce denial rate on cardiology procedures.');

    const validErrors = AuditFormObj.validateForm(validFormData);
    assertEqual(Object.keys(validErrors).length, 0, 'Complete valid form produces 0 errors');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 5: JSON Response Parser Resilience Tests${colors.reset}`);
{
    // Test 5.1: Clean JSON
    const cleanJson = '{"success": true, "message": "Received", "data": {"lead_id": 105}}';
    const parsedClean = AuditFormObj.parseServerJson(cleanJson);
    assertEqual(parsedClean.success, true, 'Clean JSON parsed: success is true');
    assertEqual(parsedClean.data.lead_id, 105, 'Clean JSON lead_id extracted correctly');

    // Test 5.2: Leaked PHP Notices & Warnings before JSON output
    const phpWarningResponse = `<br />
<b>Warning</b>: Undefined array key "HTTP_X_REQUESTED_WITH" in <b>C:\\xampp\\htdocs\\Medinext_solution\\Medinext_solutions\\api\\submit-audit-request.php</b> on line 42<br />
<br />
<b>Notice</b>: session_start(): A session had already been started in <b>C:\\xampp\\htdocs\\Medinext_solution\\Medinext_solutions\\includes\\functions.php</b> on line 18<br />
{"success": true, "message": "Success with leaked warnings", "data": {"lead_id": 999}}`;

    const parsedWarning = AuditFormObj.parseServerJson(phpWarningResponse);
    assertEqual(parsedWarning.success, true, 'PHP Warning response safely isolated and parsed');
    assertEqual(parsedWarning.data.lead_id, 999, 'PHP Warning response lead_id intact');

    // Test 5.3: Leaked debug echo and server errors
    const debugEchoResponse = `DEBUG: Connection established to db_medinext
Array ( [ip] => 127.0.0.1 )
{"success": false, "message": "Rate limit exceeded. Please wait 15 minutes.", "errors": {"rate_limit": "Too many requests"}}`;

    const parsedDebug = AuditFormObj.parseServerJson(debugEchoResponse);
    assertEqual(parsedDebug.success, false, 'Server error JSON with debug text parsed correctly');
    assertEqual(parsedDebug.errors.rate_limit, 'Too many requests', 'Error map recovered intact');

    // Test 5.4: Completely invalid HTML / 500 error page
    assertThrows(() => {
        AuditFormObj.parseServerJson('<!DOCTYPE html><html><body><h1>500 Internal Server Error</h1></body></html>');
    }, 'Throws error on non-JSON HTML string');

    // Test 5.5: Empty / null / undefined strings
    assertThrows(() => AuditFormObj.parseServerJson(''), 'Throws error on empty string');
    assertThrows(() => AuditFormObj.parseServerJson(null), 'Throws error on null input');
    assertThrows(() => AuditFormObj.parseServerJson(undefined), 'Throws error on undefined input');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 6: Multi-Select Pill Interactive Logic & Accessibility${colors.reset}`);
{
    // Build Pill DOM fixtures
    const form = new MockElement('form', 'practice-audit-form');
    
    // Create 2 pill labels
    const pill1 = new MockElement('label');
    pill1.classList.add('pain-point-pill', 'audit-pill-label');
    const chk1 = new MockElement('input');
    chk1.setAttribute('type', 'checkbox');
    chk1.type = 'checkbox';
    chk1.name = 'pain_points[]';
    chk1.value = 'Chronic Claim Denials';
    pill1.children.push(chk1);
    chk1.parentElement = pill1;

    const pill2 = new MockElement('label');
    pill2.classList.add('pain-point-pill', 'audit-pill-label');
    const chk2 = new MockElement('input');
    chk2.setAttribute('type', 'checkbox');
    chk2.type = 'checkbox';
    chk2.name = 'pain_points[]';
    chk2.value = 'Aging Accounts Receivable';
    chk2.checked = true; // Pre-checked
    pill2.children.push(chk2);
    chk2.parentElement = pill2;

    form.children.push(pill1, pill2);
    pill1.parentElement = form;
    pill2.parentElement = form;

    AuditFormObj.form = form;
    AuditFormObj.initPills();

    assertEqual(pill1.getAttribute('aria-checked'), 'false', 'Unchecked pill has aria-checked="false"');
    assertEqual(pill1.classList.contains('selected'), false, 'Unchecked pill does not have class "selected"');
    assertEqual(pill2.getAttribute('aria-checked'), 'true', 'Pre-checked pill has aria-checked="true"');
    assertEqual(pill2.classList.contains('selected'), true, 'Pre-checked pill has class "selected"');
    assertEqual(pill1.getAttribute('tabindex'), '0', 'Pill label has tabindex="0" for keyboard navigation');
    assertEqual(pill1.getAttribute('role'), 'checkbox', 'Pill label has role="checkbox" for accessibility');

    pill1.dispatchEvent({ type: 'click', target: pill1, preventDefault() {} });
    assertEqual(chk1.checked, true, 'Clicking pill label checks the underlying checkbox');
    assertEqual(pill1.getAttribute('aria-checked'), 'true', 'Clicking pill updates aria-checked to "true"');
    assertEqual(pill1.classList.contains('selected'), true, 'Clicking pill adds class "selected"');

    pill1.dispatchEvent({ type: 'click', target: pill1, preventDefault() {} });
    assertEqual(chk1.checked, false, 'Clicking selected pill unchecks underlying checkbox');
    assertEqual(pill1.getAttribute('aria-checked'), 'false', 'Clicking selected pill updates aria-checked to "false"');
    assertEqual(pill1.classList.contains('selected'), false, 'Clicking selected pill removes class "selected"');

    let prevented = false;
    pill1.dispatchEvent({ type: 'keydown', key: ' ', preventDefault() { prevented = true; } });
    assert(prevented, 'Space key on pill prevents default scroll behavior');
    assertEqual(chk1.checked, true, 'Space key toggles checkbox to checked');
    assertEqual(pill1.getAttribute('aria-checked'), 'true', 'Space key updates aria-checked="true"');

    pill1.dispatchEvent({ type: 'keydown', key: 'Enter', preventDefault() {} });
    assertEqual(chk1.checked, false, 'Enter key toggles checkbox to unchecked');
    assertEqual(pill1.getAttribute('aria-checked'), 'false', 'Enter key updates aria-checked="false"');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 7: Template Conformance & DOM Element Binding${colors.reset}`);
{
    const requiredIds = [
        'practice-audit-form',
        'csrf_token',
        'form_timestamp',
        'website_hp',
        'audit_form_hp',
        'practice_name',
        'contact_name',
        'job_title',
        'email',
        'phone',
        'street_address',
        'city',
        'state',
        'zip_code',
        'specialty',
        'patient_volume',
        'monthly_revenue',
        'current_ehr',
        'additional_notes',
        'charCount',
        'auditSubmitBtn',
        'auditFormAlert',
        'auditSuccessOverlay',
        'successContactName',
        'successPracticeName',
        'successLeadId',
        'successSpecialty',
        'successContactEmail',
        'successContactPhone'
    ];

    requiredIds.forEach(id => {
        const hasId = phpTemplateCode.includes(`id="${id}"`);
        assert(hasId, `Template contains required DOM id="${id}"`);
    });

    assert(phpTemplateCode.includes('name="csrf_token"'), 'Template has name="csrf_token" input');
    assert(phpTemplateCode.includes('name="website_hp"'), 'Template has anti-bot honeypot name="website_hp"');
    assert(phpTemplateCode.includes('name="audit_form_hp"'), 'Template has anti-bot honeypot name="audit_form_hp"');

    const requiredPainPoints = [
        'Chronic Claim Denials',
        'Aging Accounts Receivable',
        'In-House Billing Staff Turnover',
        'Credentialing',
        'Prior Authorization',
        'Undercoding',
        'Fee Schedule Underpayments'
    ];

    requiredPainPoints.forEach(pp => {
        const hasPp = phpTemplateCode.includes(pp);
        assert(hasPp, `Template contains pain point option: "${pp}"`);
    });
}

console.log(`\n${colors.yellow}>>> TEST SUITE 8: Real-Time Masking & Character Counter DOM Binding${colors.reset}`);
{
    const formEl = new MockElement('form', 'practice-audit-form');
    const phoneInput = new MockElement('input', 'phone', 'phone');
    phoneInput.setAttribute('type', 'tel');
    phoneInput.classList.add('phone-mask', 'form-control');
    formEl.children.push(phoneInput);
    phoneInput.parentElement = formEl;

    const zipInput = new MockElement('input', 'zip_code', 'zip_code');
    zipInput.classList.add('zip-mask', 'form-control');
    formEl.children.push(zipInput);
    zipInput.parentElement = formEl;

    const notesInput = new MockElement('textarea', 'additional_notes', 'additional_notes');
    notesInput.classList.add('form-control');
    formEl.children.push(notesInput);
    notesInput.parentElement = formEl;
    const charCountEl = new MockElement('span', 'charCount');
    mockDocRegistry.set('charCount', charCountEl);

    AuditFormObj.form = formEl;
    AuditFormObj.initMasking();
    AuditFormObj.initCharCounter();

    phoneInput.value = '5551234567';
    phoneInput.dispatchEvent({ type: 'input', target: phoneInput });
    assertEqual(phoneInput.value, '(555) 123-4567', 'Phone input event real-time masked to "(555) 123-4567"');

    zipInput.value = '32804-9999';
    zipInput.dispatchEvent({ type: 'input', target: zipInput });
    assertEqual(zipInput.value, '32804', 'ZIP input event real-time masked to 5 digits "32804"');

    notesInput.value = 'Testing real-time char counter length!';
    notesInput.dispatchEvent({ type: 'input', target: notesInput });
    assertEqual(charCountEl.textContent, 38, 'charCount span live-updated to matching character length');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 9: Error Display & State Transitions${colors.reset}`);
{
    const formEl = new MockElement('form', 'practice-audit-form');
    const practiceInput = new MockElement('input', 'practice_name', 'practice_name');
    practiceInput.classList.add('form-control');
    const practiceFeedback = new MockElement('div');
    practiceFeedback.classList.add('invalid-feedback');
    const formGroup = new MockElement('div');
    formGroup.classList.add('form-group');
    formGroup.children.push(practiceInput, practiceFeedback);
    practiceInput.parentElement = formGroup;
    practiceFeedback.parentElement = formGroup;

    formEl.children.push(formGroup);
    formGroup.parentElement = formEl;

    const alertBanner = new MockElement('div', 'auditFormAlert');
    alertBanner.classList.add('d-none');
    const alertMsg = new MockElement('span');
    alertMsg.classList.add('alert-message');
    alertBanner.children.push(alertMsg);
    alertMsg.parentElement = alertBanner;

    AuditFormObj.form = formEl;
    AuditFormObj.alertBanner = alertBanner;
    AuditFormObj.alertMessage = alertMsg;

    // Show errors
    AuditFormObj.showErrors({ practice_name: 'Practice name is required.' });
    assertEqual(practiceInput.classList.contains('is-invalid'), true, 'practice_name has class "is-invalid"');
    assertEqual(practiceInput.getAttribute('aria-invalid'), 'true', 'practice_name has aria-invalid="true"');
    assertEqual(practiceFeedback.textContent, 'Practice name is required.', 'Feedback text set correctly');
    assertEqual(practiceFeedback.style.display, 'block', 'Feedback display is set to block');
    assertEqual(alertBanner.classList.contains('d-none'), false, 'Top alert banner is shown');
    assertEqual(practiceInput.focused, true, 'First invalid field received auto-focus');

    // Clear errors
    AuditFormObj.clearErrors();
    assertEqual(practiceInput.classList.contains('is-invalid'), false, 'practice_name "is-invalid" removed');
    assertEqual(practiceInput.hasAttribute('aria-invalid'), false, 'aria-invalid removed');
    assertEqual(alertBanner.classList.contains('d-none'), true, 'Top alert banner is hidden');
}

console.log(`\n${colors.yellow}>>> TEST SUITE 10: Honeypot Anti-Bot Trapping Behavior${colors.reset}`);
(async () => {
    let successStateCalled = false;

    const mockForm = new MockElement('form', 'practice-audit-form');
    const hpInput = new MockElement('input');
    hpInput.name = 'website_hp';
    hpInput.value = 'http://spambot-payload.org';
    mockForm.children.push(hpInput);
    hpInput.parentElement = mockForm;

    AuditFormObj.form = mockForm;
    AuditFormObj.isSubmitting = false;

    const origShowSuccess = AuditFormObj.showSuccessState;
    AuditFormObj.showSuccessState = (result, fd) => {
        successStateCalled = true;
        assertEqual(result.data.lead_id, 'SPAM-FILTERED', 'Spam bot traps with simulated fake success lead_id');
    };

    // Trigger submit with bot honeypot populated
    const fakeEvent = { preventDefault() {} };
    AuditFormObj.handleSubmit(fakeEvent);

    // Wait for timer to complete
    await new Promise(resolve => setTimeout(resolve, 900));
    assert(successStateCalled, 'Honeypot trap silently fakes success to mislead bots without backend network load');
    AuditFormObj.showSuccessState = origShowSuccess;
    AuditFormObj.isSubmitting = false;

    console.log(`\n${colors.yellow}>>> TEST SUITE 11: Asynchronous Submission & Success Overlay Transitions${colors.reset}`);
    {
        // Build full form with all fields
        const formEl = new MockElement('form', 'practice-audit-form');
        formEl.setAttribute('action', 'api/submit-audit-request.php');

        const fields = [
            ['practice_name', 'Metropolitan Spine Center'],
            ['contact_name', 'Dr. Marcus Vance'],
            ['job_title', 'Practice Owner / Physician / Clinician'],
            ['email', 'm.vance@metrospine.com'],
            ['phone', '(555) 890-1234'],
            ['street_address', '450 Lexington Ave'],
            ['city', 'New York'],
            ['state', 'NY'],
            ['zip_code', '10017'],
            ['specialty', 'Pain Management & Interventional Spine'],
            ['patient_volume', '1,001 - 2,500 visits / month'],
            ['monthly_revenue', '$250,001 - $500,000 / month'],
            ['current_ehr', 'Epic Systems'],
            ['additional_notes', 'Specialized audit on spine injection coding.']
        ];

        fields.forEach(([name, val]) => {
            const input = new MockElement('input', name, name);
            input.classList.add('form-control');
            input.value = val;
            formEl.children.push(input);
            input.parentElement = formEl;
        });

        const submitBtn = new MockElement('button', 'auditSubmitBtn');
        submitBtn.setAttribute('type', 'submit');
        submitBtn.innerHTML = 'Generate My Free Practice Audit';
        formEl.children.push(submitBtn);
        submitBtn.parentElement = formEl;

        // Overlay mock elements
        const overlay = new MockElement('div', 'auditSuccessOverlay');
        overlay.style.display = 'none';

        const successContactName = new MockElement('span', 'successContactName');
        const successPracticeName = new MockElement('span', 'successPracticeName');
        const successLeadId = new MockElement('span', 'successLeadId');
        const successSpecialty = new MockElement('span', 'successSpecialty');
        const successContactEmail = new MockElement('span', 'successContactEmail');
        const successContactPhone = new MockElement('span', 'successContactPhone');

        mockDocRegistry.set('auditSuccessOverlay', overlay);
        mockDocRegistry.set('successContactName', successContactName);
        mockDocRegistry.set('successPracticeName', successPracticeName);
        mockDocRegistry.set('successLeadId', successLeadId);
        mockDocRegistry.set('successSpecialty', successSpecialty);
        mockDocRegistry.set('successContactEmail', successContactEmail);
        mockDocRegistry.set('successContactPhone', successContactPhone);

        AuditFormObj.form = formEl;
        AuditFormObj.submitBtn = submitBtn;
        AuditFormObj.successOverlay = overlay;
        AuditFormObj.isSubmitting = false;

        // Mock successful backend response
        fetchMockHandler = (url, options) => {
            assertEqual(url, 'api/submit-audit-request.php', 'Fetch targeted correct endpoint');
            assertEqual(options.headers['X-Requested-With'], 'XMLHttpRequest', 'Header X-Requested-With sent');
            assertEqual(options.headers['Accept'], 'application/json', 'Header Accept application/json sent');
            return Promise.resolve({
                ok: true,
                text: () => Promise.resolve(JSON.stringify({
                    success: true,
                    message: 'Audit request received.',
                    data: { lead_id: 4821 }
                }))
            });
        };

        // Trigger submit
        await AuditFormObj.handleSubmit({ preventDefault() {} });

        assertEqual(formEl.style.display, 'none', 'Form is hidden upon successful submission');
        assertEqual(overlay.style.display, 'block', 'Success overlay is displayed (display: block)');
        assertEqual(overlay.classList.contains('active'), true, 'Success overlay receives "active" class');
        assertEqual(successContactName.textContent, 'Dr. Marcus Vance', 'Contact name populated in success card');
        assertEqual(successPracticeName.textContent, 'Metropolitan Spine Center', 'Practice name populated in success card');
        assertEqual(successLeadId.textContent, '#AUD-4821', 'Lead Reference ID populated with #AUD- prefix');
        assertEqual(successSpecialty.textContent, 'Pain Management & Interventional Spine', 'Specialty populated in summary');
        assertEqual(successContactEmail.textContent, 'm.vance@metrospine.com', 'Email populated in summary');
        assertEqual(successContactPhone.textContent, '(555) 890-1234', 'Phone populated in summary');
    }

    console.log(`\n${colors.yellow}>>> TEST SUITE 12: Network Rejection & XHR Fallback Resilience${colors.reset}`);
    {
        const formEl = AuditFormObj.form;
        const overlay = AuditFormObj.successOverlay;
        const successLeadId = mockDocRegistry.get('successLeadId');

        // Repopulate form values (since reset cleared them)
        const fields = [
            ['practice_name', 'Metropolitan Spine Center'],
            ['contact_name', 'Dr. Marcus Vance'],
            ['job_title', 'Practice Owner / Physician / Clinician'],
            ['email', 'm.vance@metrospine.com'],
            ['phone', '(555) 890-1234'],
            ['street_address', '450 Lexington Ave'],
            ['city', 'New York'],
            ['state', 'NY'],
            ['zip_code', '10017'],
            ['specialty', 'Pain Management & Interventional Spine'],
            ['patient_volume', '1,001 - 2,500 visits / month'],
            ['monthly_revenue', '$250,001 - $500,000 / month'],
            ['current_ehr', 'Epic Systems'],
            ['additional_notes', 'Specialized audit on spine injection coding.']
        ];

        fields.forEach(([name, val]) => {
            const input = formEl.querySelector(`[name="${name}"]`);
            if (input) input.value = val;
        });

        // Reset form display
        formEl.style.display = 'block';
        overlay.style.display = 'none';
        overlay.classList.remove('active');
        AuditFormObj.isSubmitting = false;

        // Configure fetch to fail (network error), but XHR to succeed
        fetchMockHandler = () => Promise.reject(new Error('Fetch network error'));
        xhrMockHandler = (xhr, data) => {
            xhr.status = 200;
            xhr.responseText = JSON.stringify({
                success: true,
                message: 'Recovered via XHR fallback',
                data: { lead_id: 5910 }
            });
            if (xhr.onload) xhr.onload();
        };

        await AuditFormObj.handleSubmit({ preventDefault() {} });

        assertEqual(overlay.style.display, 'block', 'XHR fallback successfully recovered and showed success overlay');
        assertEqual(successLeadId.textContent, '#AUD-5910', 'Lead ID recovered via XHR fallback is #AUD-5910');
    }

    console.log(`\n${colors.yellow}>>> TEST SUITE 13: Double-Submit Debouncing Protection${colors.reset}`);
    {
        AuditFormObj.isSubmitting = true;
        let secondSubmitBlocked = true;
        fetchMockHandler = () => {
            secondSubmitBlocked = false;
            return Promise.resolve({ ok: true, text: () => Promise.resolve('{"success":true}') });
        };

        await AuditFormObj.handleSubmit({ preventDefault() {} });
        assert(secondSubmitBlocked, 'Concurrent submission blocked while isSubmitting is true');
        AuditFormObj.isSubmitting = false;
    }

    // Final Report Summary Printout
    console.log(`\n${colors.bold}${colors.cyan}======================================================================${colors.reset}`);
    console.log(`${colors.bold} COMPLETE TEST RESULTS SUMMARY:${colors.reset}`);
    console.log(` Total Tests Executed: ${totalTests}`);
    console.log(` ${colors.green}Passed Tests: ${passedTests}${colors.reset}`);
    console.log(` ${failedTests > 0 ? colors.red : colors.green}Failed Tests: ${failedTests}${colors.reset}`);
    console.log(`${colors.bold}${colors.cyan}======================================================================${colors.reset}\n`);

    if (failedTests > 0) {
        console.log(`${colors.red}FAILURE DETAILS:${colors.reset}`);
        failureDetails.forEach((f, i) => {
            console.log(` ${i + 1}. ${f.testName}: ${f.details}`);
        });
        process.exit(1);
    } else {
        console.log(`${colors.green}${colors.bold}ALL ADVERSARIAL STRESS TESTS PASSED WITH 100% SUCCESS RATE!${colors.reset}\n`);
        process.exit(0);
    }
})();
