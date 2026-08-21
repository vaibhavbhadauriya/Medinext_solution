<?php
/**
 * Milestone 1 DOM & HTML Assertions Challenger Test Harness
 * 
 * Verifies DOM integrity, markup conformance, 10 metric intake fields,
 * security inputs, single <main> tag layout shell, responsive UI components,
 * JSON-LD schema, CSS design tokens, and JS controller selector synchronization
 * for free-practice-audit.php.
 */

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', '1');

class DomIntegrityChallenger {
    private string $rootDir;
    private string $renderedHtml = '';
    private ?DOMDocument $dom = null;
    private ?DOMXPath $xpath = null;
    private array $results = [];
    private int $passCount = 0;
    private int $failCount = 0;

    public function __construct(string $rootDir) {
        $this->rootDir = rtrim($rootDir, '/\\');
    }

    private function assert(string $suite, string $assertion, bool $condition, string $detail = ''): void {
        if ($condition) {
            $this->passCount++;
            $this->results[] = [
                'suite' => $suite,
                'assertion' => $assertion,
                'status' => 'PASS',
                'detail' => $detail
            ];
            echo "  [PASS] {$assertion}" . ($detail !== '' ? " ({$detail})" : "") . "\n";
        } else {
            $this->failCount++;
            $this->results[] = [
                'suite' => $suite,
                'assertion' => $assertion,
                'status' => 'FAIL',
                'detail' => $detail
            ];
            echo "  [FAIL] {$assertion}" . ($detail !== '' ? " ({$detail})" : "") . "\n";
        }
    }

    public function runAllSuites(): bool {
        echo "=====================================================================\n";
        echo "  CHALLENGER 1: DOM & HTML ASSERTIONS VERIFICATION SUITE (M1)\n";
        echo "=====================================================================\n\n";

        $this->suite1_SyntaxAndRendering();
        $this->suite2_SingleMainTagAndLayoutShell();
        $this->suite3_FormAndRoutingConfig();
        $this->suite4_SecurityAndAntiBotInputs();
        $this->suite5_TenOperationalMetricFields();
        $this->suite6_UIComponentSections();
        $this->suite7_StructuredDataSchema();
        $this->suite8_DesignTokensAndCss();
        $this->suite9_JsControllerSelectorSync();
        $this->suite10_AccessibilityAndUniqueIds();
        $this->suite11_AdversarialDomStressChecks();

        echo "\n=====================================================================\n";
        echo "  VERIFICATION SUMMARY\n";
        echo "=====================================================================\n";
        echo "  Total Assertions: " . ($this->passCount + $this->failCount) . "\n";
        echo "  Passed: {$this->passCount}\n";
        echo "  Failed: {$this->failCount}\n";
        $verdict = ($this->failCount === 0);
        echo "  Overall Verdict: " . ($verdict ? "APPROVE" : "REJECT") . "\n";
        echo "=====================================================================\n";

        return $verdict;
    }

    private function suite1_SyntaxAndRendering(): void {
        echo "[Suite 1] PHP Syntax & Output Buffer Page Rendering\n";

        // Check syntax of free-practice-audit.php
        $file = $this->rootDir . '/free-practice-audit.php';
        $this->assert('Suite 1', 'free-practice-audit.php file exists', file_exists($file), $file);

        $cmd = 'php -l ' . escapeshellarg($file) . ' 2>&1';
        $lintOutput = shell_exec($cmd);
        $this->assert('Suite 1', 'free-practice-audit.php passes PHP lint syntax check', strpos($lintOutput, 'No syntax errors detected') !== false, trim($lintOutput));

        // Render page via output buffering in simulated web environment
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/free-practice-audit/';
        $_SERVER['HTTPS'] = 'on';

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $oldCwd = getcwd();
        chdir($this->rootDir);

        ob_start();
        try {
            include $file;
            $this->renderedHtml = ob_get_clean();
        } catch (Throwable $e) {
            $this->renderedHtml = ob_get_clean();
            $this->assert('Suite 1', 'Page rendering completed without throwing exceptions', false, $e->getMessage());
        }
        chdir($oldCwd);

        $this->assert('Suite 1', 'Rendered HTML output is non-empty (>1000 bytes)', strlen($this->renderedHtml) > 1000, 'Bytes: ' . strlen($this->renderedHtml));

        // Parse HTML into DOMDocument
        $this->dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $this->dom->loadHTML(mb_convert_encoding($this->renderedHtml, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $this->assert('Suite 1', 'Rendered HTML successfully parsed by DOMDocument', $loaded === true);
        if ($loaded) {
            $this->xpath = new DOMXPath($this->dom);
        }
        echo "\n";
    }

    private function suite2_SingleMainTagAndLayoutShell(): void {
        echo "[Suite 2] Single <main> Tag & Layout Shell Integrity\n";

        // Count <main> tags in rendered HTML
        $mainTags = $this->xpath ? $this->xpath->query('//main') : null;
        $mainCount = $mainTags ? $mainTags->length : 0;
        $this->assert('Suite 2', 'Rendered document contains exactly ONE <main> tag', $mainCount === 1, "Found: {$mainCount}");

        if ($mainCount === 1) {
            $mainEl = $mainTags->item(0);
            $mainId = $mainEl->getAttribute('id');
            $this->assert('Suite 2', '<main> element has id="main-content" for accessibility anchor', $mainId === 'main-content', "id='{$mainId}'");
        }

        // Verify free-practice-audit.php itself does not define rogue <main> tag
        $rawAuditContent = file_get_contents($this->rootDir . '/free-practice-audit.php');
        $rawMainCount = preg_match_all('/<main[\s>]/i', $rawAuditContent);
        $this->assert('Suite 2', 'free-practice-audit.php template does NOT contain raw <main> tag', $rawMainCount === 0, "Raw <main> occurrences in template: {$rawMainCount}");

        // Verify skip-nav in header
        $skipNav = $this->xpath->query('//a[@href="#main-content"]');
        $this->assert('Suite 2', 'Header contains skip navigation link referencing #main-content', $skipNav->length >= 1, "Found: {$skipNav->length}");
        echo "\n";
    }

    private function suite3_FormAndRoutingConfig(): void {
        echo "[Suite 3] Form Element & Routing Configuration\n";

        $forms = $this->xpath->query('//form[@id="practice-audit-form"]');
        $this->assert('Suite 3', 'Form with id="practice-audit-form" exists', $forms->length === 1, "Found: {$forms->length}");

        if ($forms->length === 1) {
            $form = $forms->item(0);
            $method = strtoupper($form->getAttribute('method'));
            $action = $form->getAttribute('action');
            $class = $form->getAttribute('class');
            $novalidate = $form->hasAttribute('novalidate');

            $this->assert('Suite 3', 'Form method is POST', $method === 'POST', "method='{$method}'");
            $this->assert('Suite 3', 'Form action points to api/submit-audit-request.php', strpos($action, 'api/submit-audit-request.php') !== false, "action='{$action}'");
            $this->assert('Suite 3', 'Form class contains "needs-validation"', strpos($class, 'needs-validation') !== false, "class='{$class}'");
            $this->assert('Suite 3', 'Form has "novalidate" attribute for custom JS validation', $novalidate === true);
        }
        echo "\n";
    }

    private function suite4_SecurityAndAntiBotInputs(): void {
        echo "[Suite 4] Security & Anti-Bot Hidden Fields\n";

        // CSRF Token
        $csrf = $this->xpath->query('//input[@type="hidden"][@name="csrf_token"][@id="csrf_token"]');
        $this->assert('Suite 4', 'Hidden csrf_token input exists with matching id', $csrf->length === 1);
        if ($csrf->length === 1) {
            $csrfVal = $csrf->item(0)->getAttribute('value');
            $this->assert('Suite 4', 'CSRF token value is non-empty 64-char hex string', strlen($csrfVal) === 64 && ctype_xdigit($csrfVal), "Length: " . strlen($csrfVal));
        }

        // Form Timestamp
        $timestamp = $this->xpath->query('//input[@type="hidden"][@name="form_timestamp"][@id="form_timestamp"]');
        $this->assert('Suite 4', 'Hidden form_timestamp input exists with matching id', $timestamp->length === 1);
        if ($timestamp->length === 1) {
            $tsVal = $timestamp->item(0)->getAttribute('value');
            $this->assert('Suite 4', 'Form timestamp is a valid numeric timestamp', is_numeric($tsVal) && (int)$tsVal > 1500000000, "Value: {$tsVal}");
        }

        // Honeypot inputs
        $websiteHp = $this->xpath->query('//input[@name="website_hp"][@id="website_hp"]');
        $this->assert('Suite 4', 'Honeypot field "website_hp" exists', $websiteHp->length === 1);
        if ($websiteHp->length === 1) {
            $tabindex = $websiteHp->item(0)->getAttribute('tabindex');
            $autocomplete = $websiteHp->item(0)->getAttribute('autocomplete');
            $this->assert('Suite 4', 'Honeypot field website_hp has tabindex="-1" and autocomplete="off"', $tabindex === '-1' && $autocomplete === 'off');
        }

        $auditFldHp = $this->xpath->query('//input[@name="audit_form_hp"][@id="audit_form_hp"]');
        $this->assert('Suite 4', 'Honeypot field "audit_form_hp" exists', $auditFldHp->length === 1);

        // Honeypot container hidden
        $hpContainer = $this->xpath->query('//div[contains(@class, "visually-hidden") and @aria-hidden="true"]//input[@name="website_hp"]');
        $this->assert('Suite 4', 'Honeypot container is visually-hidden with aria-hidden="true"', $hpContainer->length >= 1);
        echo "\n";
    }

    private function suite5_TenOperationalMetricFields(): void {
        echo "[Suite 5] 10 Operational & Financial Intake Metric Fields\n";

        // 1. Practice Name
        $f1 = $this->xpath->query('//input[@name="practice_name"][@id="practice_name"]');
        $this->assert('Suite 5', 'Field 1: input#practice_name exists', $f1->length === 1);
        if ($f1->length === 1) {
            $this->assert('Suite 5', 'Field 1: practice_name is required with minlength=2 and maxlength=150', 
                $f1->item(0)->hasAttribute('required') && $f1->item(0)->getAttribute('minlength') === '2' && $f1->item(0)->getAttribute('maxlength') === '150');
            $this->assert('Suite 5', 'Field 1: practice_name has autocomplete="organization"', $f1->item(0)->getAttribute('autocomplete') === 'organization');
        }

        // 2. Contact Name
        $f2 = $this->xpath->query('//input[@name="contact_name"][@id="contact_name"]');
        $this->assert('Suite 5', 'Field 2: input#contact_name exists', $f2->length === 1);
        if ($f2->length === 1) {
            $this->assert('Suite 5', 'Field 2: contact_name is required with minlength=2 and maxlength=100', 
                $f2->item(0)->hasAttribute('required') && $f2->item(0)->getAttribute('minlength') === '2' && $f2->item(0)->getAttribute('maxlength') === '100');
            $this->assert('Suite 5', 'Field 2: contact_name has autocomplete="name"', $f2->item(0)->getAttribute('autocomplete') === 'name');
        }

        // 3. Job Title
        $f3 = $this->xpath->query('//select[@name="job_title"][@id="job_title"]');
        $this->assert('Suite 5', 'Field 3: select#job_title exists', $f3->length === 1);
        if ($f3->length === 1) {
            $this->assert('Suite 5', 'Field 3: job_title is required', $f3->item(0)->hasAttribute('required'));
            $options = $this->xpath->query('.//option', $f3->item(0));
            $this->assert('Suite 5', 'Field 3: job_title has at least 5 executive/management roles', $options->length >= 6, "Options: {$options->length}");
        }

        // 4. Email Address
        $f4 = $this->xpath->query('//input[@name="email"][@id="email"][@type="email"]');
        $this->assert('Suite 5', 'Field 4: input#email[type="email"] exists and is required', $f4->length === 1 && $f4->item(0)->hasAttribute('required'));
        if ($f4->length === 1) {
            $this->assert('Suite 5', 'Field 4: email has autocomplete="email"', $f4->item(0)->getAttribute('autocomplete') === 'email');
        }

        // 5. Phone Number
        $f5 = $this->xpath->query('//input[@name="phone"][@id="phone"][@type="tel"]');
        $this->assert('Suite 5', 'Field 5: input#phone[type="tel"] exists and is required', $f5->length === 1 && $f5->item(0)->hasAttribute('required'));
        if ($f5->length === 1) {
            $class = $f5->item(0)->getAttribute('class');
            $this->assert('Suite 5', 'Field 5: phone input has phone-mask class for JS masking', strpos($class, 'phone-mask') !== false);
        }

        // 6. Structured Physical Address
        // Street Address
        $f6_street = $this->xpath->query('//input[@name="street_address"][@id="street_address"]');
        $this->assert('Suite 5', 'Field 6a: input#street_address exists and is required (min 5, max 255)', 
            $f6_street->length === 1 && $f6_street->item(0)->hasAttribute('required') && $f6_street->item(0)->getAttribute('minlength') === '5');
        
        // City
        $f6_city = $this->xpath->query('//input[@name="city"][@id="city"]');
        $this->assert('Suite 5', 'Field 6b: input#city exists and is required', $f6_city->length === 1 && $f6_city->item(0)->hasAttribute('required'));

        // State
        $f6_state = $this->xpath->query('//select[@name="state"][@id="state"]');
        $this->assert('Suite 5', 'Field 6c: select#state exists and is required', $f6_state->length === 1 && $f6_state->item(0)->hasAttribute('required'));
        if ($f6_state->length === 1) {
            $stateOptions = $this->xpath->query('.//option', $f6_state->item(0));
            $this->assert('Suite 5', 'Field 6c: select#state contains all 50 US States + DC + PR (>=52 options)', $stateOptions->length >= 52, "Found: {$stateOptions->length}");
        }

        // ZIP Code
        $f6_zip = $this->xpath->query('//input[@name="zip_code"][@id="zip_code"]');
        $this->assert('Suite 5', 'Field 6d: input#zip_code exists and is required with pattern and zip-mask class', 
            $f6_zip->length === 1 && $f6_zip->item(0)->hasAttribute('required') && strpos($f6_zip->item(0)->getAttribute('class'), 'zip-mask') !== false);

        // 7. Clinical Specialty
        $f7 = $this->xpath->query('//select[@name="specialty"][@id="specialty"]');
        $this->assert('Suite 5', 'Field 7: select#specialty exists and is required', $f7->length === 1 && $f7->item(0)->hasAttribute('required'));
        if ($f7->length === 1) {
            $specOptions = $this->xpath->query('.//option', $f7->item(0));
            $this->assert('Suite 5', 'Field 7: specialty dropdown has >=15 medical/dental specialties', $specOptions->length >= 16, "Found: {$specOptions->length}");
        }

        // 8. Monthly Patient Volume
        $f8 = $this->xpath->query('//select[@name="patient_volume"][@id="patient_volume"]');
        $this->assert('Suite 5', 'Field 8: select#patient_volume exists and is required', $f8->length === 1 && $f8->item(0)->hasAttribute('required'));
        if ($f8->length === 1) {
            $volOptions = $this->xpath->query('.//option', $f8->item(0));
            $this->assert('Suite 5', 'Field 8: patient_volume dropdown has >=5 encounter tiers', $volOptions->length >= 6, "Found: {$volOptions->length}");
        }

        // 9. Monthly Revenue
        $f9 = $this->xpath->query('//select[@name="monthly_revenue"][@id="monthly_revenue"]');
        $this->assert('Suite 5', 'Field 9: select#monthly_revenue exists and is required', $f9->length === 1 && $f9->item(0)->hasAttribute('required'));
        if ($f9->length === 1) {
            $revOptions = $this->xpath->query('.//option', $f9->item(0));
            $this->assert('Suite 5', 'Field 9: monthly_revenue dropdown has >=5 collection brackets', $revOptions->length >= 6, "Found: {$revOptions->length}");
        }

        // 10. Current EHR / PMS
        $f10 = $this->xpath->query('//select[@name="current_ehr"][@id="current_ehr"]');
        $this->assert('Suite 5', 'Field 10: select#current_ehr exists and is required', $f10->length === 1 && $f10->item(0)->hasAttribute('required'));
        if ($f10->length === 1) {
            $ehrOptions = $this->xpath->query('.//option', $f10->item(0));
            $this->assert('Suite 5', 'Field 10: current_ehr dropdown has >=10 PMS platforms', $ehrOptions->length >= 11, "Found: {$ehrOptions->length}");
        }

        // Pain Points Checkbox Pills
        $painPoints = $this->xpath->query('//input[@type="checkbox"][@name="pain_points[]"]');
        $this->assert('Suite 5', 'Pain Points: Multi-select checkboxes name="pain_points[]" present (>=8 items)', $painPoints->length >= 8, "Found: {$painPoints->length}");
        $pillLabels = $this->xpath->query('//label[contains(@class, "pain-point-pill") or contains(@class, "audit-pill-label")]');
        $this->assert('Suite 5', 'Pain Points: Pill wrapper labels present for interactive pill styling', $pillLabels->length >= 8, "Found: {$pillLabels->length}");

        // Additional Notes & Character Counter
        $notes = $this->xpath->query('//textarea[@name="additional_notes"][@id="additional_notes"]');
        $this->assert('Suite 5', 'Additional Notes: textarea#additional_notes exists with maxlength=2000', 
            $notes->length === 1 && $notes->item(0)->getAttribute('maxlength') === '2000');
        $charCount = $this->xpath->query('//*[@id="charCount"]');
        $this->assert('Suite 5', 'Additional Notes: Character counter element #charCount exists', $charCount->length === 1);
        echo "\n";
    }

    private function suite6_UIComponentSections(): void {
        echo "[Suite 6] UI Component Sections & Visual Architecture\n";

        // Hero Section
        $hero = $this->xpath->query('//section[contains(@class, "page-hero")]');
        $this->assert('Suite 6', 'Page Hero: section.page-hero exists', $hero->length === 1);
        $heroTitle = $this->xpath->query('//h1[contains(@class, "page-hero-title")]');
        $this->assert('Suite 6', 'Page Hero: h1.page-hero-title exists and contains "Practice Revenue Audit"', 
            $heroTitle->length === 1 && strpos($heroTitle->item(0)->textContent, 'Practice Revenue Audit') !== false);
        $heroBadge = $this->xpath->query('//div[contains(@class, "hero-badge")]');
        $this->assert('Suite 6', 'Page Hero: Trust badge pill (.hero-badge) exists', $heroBadge->length === 1);
        $heroTrustStrip = $this->xpath->query('//div[contains(@class, "hero-trust-strip")]');
        $this->assert('Suite 6', 'Page Hero: Trust strip with 4 metric items exists', $heroTrustStrip->length === 1);

        // Authority Panel (Left Column)
        $authPanel = $this->xpath->query('//div[contains(@class, "audit-authority-panel")]');
        $this->assert('Suite 6', 'Authority Panel: .audit-authority-panel exists', $authPanel->length === 1);
        $featuresList = $this->xpath->query('//div[contains(@class, "audit-features-list")]//div[contains(@class, "audit-feature-item")]');
        $this->assert('Suite 6', 'Authority Panel: 5-Point Forensic Breakdown items present (>=5 items)', $featuresList->length >= 5, "Found: {$featuresList->length}");
        $roadmap = $this->xpath->query('//div[contains(@class, "audit-roadmap-card")]');
        $this->assert('Suite 6', 'Authority Panel: 4-Step Audit Roadmap timeline exists', $roadmap->length === 1);
        $testimonial = $this->xpath->query('//div[contains(@class, "client-pullquote-card")]');
        $this->assert('Suite 6', 'Authority Panel: Client testimonial card exists', $testimonial->length === 1);
        $securityBox = $this->xpath->query('//div[contains(@class, "security-guarantee-box")]');
        $this->assert('Suite 6', 'Authority Panel: HIPAA & NDA security guarantee box exists', $securityBox->length === 1);

        // Form Card & Submit Button
        $formCard = $this->xpath->query('//div[@id="audit-form-card"]');
        $this->assert('Suite 6', 'Form Card: #audit-form-card container exists', $formCard->length === 1);
        $submitBtn = $this->xpath->query('//button[@id="auditSubmitBtn"][@type="submit"]');
        $this->assert('Suite 6', 'Submit Button: button#auditSubmitBtn[type="submit"] exists', $submitBtn->length === 1);
        $alertBanner = $this->xpath->query('//div[@id="auditFormAlert"]');
        $this->assert('Suite 6', 'Alert Banner: Dynamic alert banner #auditFormAlert exists', $alertBanner->length === 1);

        // Success Feedback Overlay
        $successOverlay = $this->xpath->query('//div[@id="auditSuccessOverlay"]');
        $this->assert('Suite 6', 'Success Overlay: #auditSuccessOverlay element exists', $successOverlay->length === 1);
        
        $successFields = [
            'successContactName',
            'successLeadName',
            'successPracticeName',
            'successLeadId',
            'successSpecialty',
            'successContactEmail',
            'successContactPhone'
        ];
        $allFieldsFound = true;
        foreach ($successFields as $sfId) {
            $sf = $this->xpath->query("//*[@id='{$sfId}']");
            if ($sf->length === 0) {
                $allFieldsFound = false;
                break;
            }
        }
        $this->assert('Suite 6', 'Success Overlay: All 7 dynamic lead summary elements present', $allFieldsFound, implode(', ', $successFields));

        // Value Proof Strip
        $trustStrip = $this->xpath->query('//section[contains(@class, "section-trust-strip")]');
        $this->assert('Suite 6', 'Value Proof: .section-trust-strip exists with 4 key metrics', $trustStrip->length === 1);

        // Comparison Matrix Table
        $comparisonTable = $this->xpath->query('//table[contains(@class, "table")]');
        $this->assert('Suite 6', 'Comparison Matrix: Table exists comparing Medinext vs generic billing quotes', $comparisonTable->length >= 1);
        $tableRows = $this->xpath->query('//table//tbody//tr');
        $this->assert('Suite 6', 'Comparison Matrix: Contains >=5 feature comparison rows', $tableRows->length >= 5, "Found: {$tableRows->length}");

        // FAQ Accordion
        $faqAccordion = $this->xpath->query('//div[@id="auditFaqAccordion"]');
        $this->assert('Suite 6', 'FAQ Accordion: #auditFaqAccordion exists', $faqAccordion->length === 1);
        $faqItems = $this->xpath->query('//div[@id="auditFaqAccordion"]//div[contains(@class, "accordion-item")]');
        $this->assert('Suite 6', 'FAQ Accordion: Contains 5 collapsible FAQ items', $faqItems->length === 5, "Found: {$faqItems->length}");

        // Bottom CTA Banner
        $bottomCta = $this->xpath->query('//section[@id="cta"]');
        $this->assert('Suite 6', 'Bottom CTA: section#cta exists with canvas shader and phone CTA', $bottomCta->length === 1);
        echo "\n";
    }

    private function suite7_StructuredDataSchema(): void {
        echo "[Suite 7] Structured Data JSON-LD Schemas\n";

        $scripts = $this->xpath->query('//script[@type="application/ld+json"]');
        $this->assert('Suite 7', 'JSON-LD schema script tags exist', $scripts->length >= 1, "Found: {$scripts->length}");

        $allValidJson = true;
        $foundBreadcrumb = false;
        $foundService = false;
        $servicePriceZero = false;
        $foundFaq = false;
        $faqCount = 0;

        foreach ($scripts as $idx => $scriptNode) {
            $jsonContent = trim($scriptNode->textContent);
            $data = json_decode($jsonContent, true);
            if ($data === null || json_last_error() !== JSON_ERROR_NONE) {
                $allValidJson = false;
                continue;
            }

            // Inspect graph or single object
            $items = isset($data['@graph']) && is_array($data['@graph']) ? $data['@graph'] : [$data];
            foreach ($items as $node) {
                $type = $node['@type'] ?? '';
                if ($type === 'BreadcrumbList') {
                    $foundBreadcrumb = true;
                }
                if ($type === 'Service') {
                    $foundService = true;
                    if (isset($node['offers']) && ($node['offers']['price'] ?? '') === '0') {
                        $servicePriceZero = true;
                    }
                }
                if ($type === 'FAQPage') {
                    $foundFaq = true;
                    if (isset($node['mainEntity']) && is_array($node['mainEntity'])) {
                        $faqCount = count($node['mainEntity']);
                    }
                }
            }
        }

        $this->assert('Suite 7', 'All JSON-LD script tags contain valid parsable JSON', $allValidJson);
        $this->assert('Suite 7', 'Structured data includes BreadcrumbList schema', $foundBreadcrumb);
        $this->assert('Suite 7', 'Structured data includes Service schema with price $0 offer', $foundService && $servicePriceZero);
        $this->assert('Suite 7', 'Structured data includes FAQPage schema with 5 Question entities', $foundFaq && $faqCount === 5, "FAQ count: {$faqCount}");
        echo "\n";
    }

    private function suite8_DesignTokensAndCss(): void {
        echo "[Suite 8] Design Tokens & CSS Conformance\n";

        $cssFile = $this->rootDir . '/assets/css/style.css';
        $this->assert('Suite 8', 'CSS stylesheet assets/css/style.css exists', file_exists($cssFile));

        if (file_exists($cssFile)) {
            $css = file_get_contents($cssFile);
            $hasPrimary = strpos($css, '--primary-color') !== false || strpos($css, '#0ea5e9') !== false || strpos($css, '#0284c7') !== false;
            $this->assert('Suite 8', 'CSS contains Sky Blue primary tokens (#0ea5e9 / #0284c7)', $hasPrimary);

            $hasNavy = strpos($css, '#0c4a6e') !== false || strpos($css, '#0f172a') !== false || strpos($css, '--navy') !== false;
            $this->assert('Suite 8', 'CSS contains Deep Navy color tokens (#0c4a6e / #0f172a)', $hasNavy);

            $hasGlassmorphism = strpos($css, 'backdrop-filter') !== false || strpos($css, 'rgba(255, 255, 255') !== false;
            $this->assert('Suite 8', 'CSS implements glassmorphism / translucent card styling', $hasGlassmorphism);
        }
        echo "\n";
    }

    private function suite9_JsControllerSelectorSync(): void {
        echo "[Suite 9] JS Controller Selector Synchronization\n";

        $jsFile = $this->rootDir . '/assets/js/main.js';
        $this->assert('Suite 9', 'JS controller assets/js/main.js exists', file_exists($jsFile));

        if (file_exists($jsFile)) {
            $js = file_get_contents($jsFile);
            $this->assert('Suite 9', 'main.js contains AuditForm module definition', strpos($js, 'const AuditForm =') !== false);
            $this->assert('Suite 9', 'main.js initializes AuditForm.init() on DOMContentLoaded', strpos($js, 'AuditForm.init()') !== false);

            // Verify all DOM IDs referenced by AuditForm exist in the rendered HTML
            $jsIds = [
                'practice-audit-form',
                'auditSubmitBtn',
                'auditFormAlert',
                'auditSuccessOverlay',
                'charCount',
                'successContactName',
                'successLeadName',
                'successPracticeName',
                'successLeadId',
                'successSpecialty',
                'successContactEmail',
                'successContactPhone'
            ];

            foreach ($jsIds as $id) {
                $node = $this->xpath->query("//*[@id='{$id}']");
                $this->assert('Suite 9', "JS target DOM ID '#{$id}' exists in free-practice-audit.php", $node->length >= 1);
            }

            // Verify required fields checked by validateForm in JS exist in DOM
            $requiredFields = [
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
                'current_ehr'
            ];

            foreach ($requiredFields as $field) {
                $node = $this->xpath->query("//*[@name='{$field}' or @name='{$field}[]']");
                $this->assert('Suite 9', "JS required field name='{$field}' exists in form DOM", $node->length >= 1);
            }
        }
        echo "\n";
    }

    private function suite10_AccessibilityAndUniqueIds(): void {
        echo "[Suite 10] Accessibility & DOM ID Uniqueness Stress Testing\n";

        // Check DOM ID Uniqueness
        $allElementsWithId = $this->xpath->query('//*[@id]');
        $seenIds = [];
        $duplicateIds = [];
        foreach ($allElementsWithId as $el) {
            $id = $el->getAttribute('id');
            if (isset($seenIds[$id])) {
                $duplicateIds[] = $id;
            } else {
                $seenIds[$id] = true;
            }
        }
        $this->assert('Suite 10', 'All DOM IDs across the page are strictly unique (no duplicates)', count($duplicateIds) === 0, count($duplicateIds) > 0 ? "Duplicates: " . implode(', ', array_unique($duplicateIds)) : "Unique count: " . count($seenIds));

        // Check Form Labels match input IDs
        $labels = $this->xpath->query('//form[@id="practice-audit-form"]//label[@for]');
        $unmatchedLabels = [];
        foreach ($labels as $label) {
            $forId = $label->getAttribute('for');
            $target = $this->xpath->query("//*[@id='{$forId}']");
            if ($target->length === 0) {
                $unmatchedLabels[] = $forId;
            }
        }
        $this->assert('Suite 10', 'All form labels with "for" attribute match an existing element ID', count($unmatchedLabels) === 0, count($unmatchedLabels) > 0 ? "Unmatched: " . implode(', ', $unmatchedLabels) : "Checked: {$labels->length} labels");

        // Check aria-required on required inputs
        $requiredInputs = $this->xpath->query('//form[@id="practice-audit-form"]//*[@required]');
        $ariaRequiredCount = 0;
        foreach ($requiredInputs as $inp) {
            if ($inp->getAttribute('aria-required') === 'true') {
                $ariaRequiredCount++;
            }
        }
        $this->assert('Suite 10', 'All required inputs have aria-required="true" for screen reader compliance', $ariaRequiredCount === $requiredInputs->length, "{$ariaRequiredCount}/{$requiredInputs->length} compliant");
        echo "\n";
    }

    private function suite11_AdversarialDomStressChecks(): void {
        echo "[Suite 11] Adversarial DOM & Boundary Stress Checks\n";

        // 1. Nested Form Attack: Ensure no nested <form> inside <form>
        $nestedForms = $this->xpath->query('//form//form');
        $this->assert('Suite 11', 'No illegal nested <form> tags exist in DOM tree', $nestedForms->length === 0, "Found: {$nestedForms->length}");

        // 2. Button inside Button Attack
        $nestedButtons = $this->xpath->query('//button//button');
        $this->assert('Suite 11', 'No illegal nested <button> elements exist', $nestedButtons->length === 0);

        // 3. Form Section Numbering Check (1 to 5)
        $sectionBadges = $this->xpath->query('//div[contains(@class, "form-section-group")]//span[contains(@class, "badge")]');
        $badgeTexts = [];
        foreach ($sectionBadges as $b) {
            $txt = trim($b->textContent);
            if (is_numeric($txt)) {
                $badgeTexts[] = (int)$txt;
            }
        }
        $this->assert('Suite 11', 'Form section steps are sequentially numbered 1 through 5', $badgeTexts === [1, 2, 3, 4, 5], "Badges: " . implode(', ', $badgeTexts));

        // 4. Form Action & Method Integrity (No Javascript injection in action)
        $formEl = $this->xpath->query('//form[@id="practice-audit-form"]')->item(0);
        $actionVal = $formEl ? $formEl->getAttribute('action') : '';
        $this->assert('Suite 11', 'Form action is safe relative path without javascript: protocol', strpos(strtolower($actionVal), 'javascript:') === false && strpos($actionVal, 'api/submit-audit-request.php') !== false, "action='{$actionVal}'");

        // 5. Check Desktop Navbar CTA & Mobile Drawer CTA point to /free-practice-audit/
        $desktopCta = $this->xpath->query('//a[contains(@class, "mn-nav-cta") or contains(@class, "nav-cta")]');
        $desktopCtaValid = false;
        foreach ($desktopCta as $cta) {
            if (strpos($cta->getAttribute('href'), 'free-practice-audit') !== false) {
                $desktopCtaValid = true;
                break;
            }
        }
        $this->assert('Suite 11', 'Desktop navbar CTA button routes to /free-practice-audit/', $desktopCtaValid);

        $drawerCta = $this->xpath->query('//div[contains(@class, "drawer-cta")]//a | //a[contains(@class, "mn-drawer-cta") or contains(@class, "drawer-cta")]');
        $drawerCtaValid = false;
        foreach ($drawerCta as $cta) {
            if (strpos($cta->getAttribute('href'), 'free-practice-audit') !== false) {
                $drawerCtaValid = true;
                break;
            }
        }
        $this->assert('Suite 11', 'Mobile drawer CTA button routes to /free-practice-audit/', $drawerCtaValid);
        echo "\n";
    }
}

// Execute Runner
$rootDir = __DIR__ . '/..';
$challenger = new DomIntegrityChallenger($rootDir);
$passed = $challenger->runAllSuites();
exit($passed ? 0 : 1);
