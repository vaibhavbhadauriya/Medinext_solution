<?php
/**
 * MEDINEXT SOLUTIONS - Programmatic Local SEO & Locations Hub
 * Dynamic routing controller for:
 * 1. /locations/                      -> All 50 States Directory & National Overview
 * 2. /locations/{state}/              -> State Hub Page with City Directory & State Health Stats
 * 3. /locations/{state}/{city}/       -> Hyper-Targeted City Landing Page for Healthcare Practices
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/location-helper.php';

$baseUrl = getBaseUrl();

// Detect State and City from GET or URL parameters
$rawState = isset($_GET['state']) ? trim(strtolower((string)$_GET['state'])) : null;
$rawCity  = isset($_GET['city']) ? trim(strtolower((string)$_GET['city'])) : null;

// Sanitize slugs (letters, numbers, hyphens only)
$stateSlug = $rawState !== null ? preg_replace('/[^a-zA-Z0-9-]/', '', $rawState) : null;
$citySlug  = $rawCity !== null ? preg_replace('/[^a-zA-Z0-9-]/', '', $rawCity) : null;

// Determine View Mode
$viewMode = 'national';
$currentState = null;
$currentCity = null;
$macData = null;

if ($stateSlug && $citySlug) {
    $currentCity = getCityBySlug($stateSlug, $citySlug);
    if ($currentCity) {
        $currentState = getStateBySlug($stateSlug);
        $macData = getMacJurisdiction($currentCity['state_id'] ?? $currentCity['state_slug'] ?? $stateSlug);
        $viewMode = 'city';
    } else {
        // City not found -> send 404
        header("HTTP/1.0 404 Not Found");
        include __DIR__ . '/404.php';
        exit();
    }
} elseif ($stateSlug) {
    $currentState = getStateBySlug($stateSlug);
    if ($currentState) {
        $macData = getMacJurisdiction($currentState['id'] ?? $currentState['slug'] ?? $stateSlug);
        $viewMode = 'state';
    } else {
        // State not found -> send 404
        header("HTTP/1.0 404 Not Found");
        include __DIR__ . '/404.php';
        exit();
    }
}

// -------------------------------------------------------------
// Dynamic SEO Metadata Configuration
// -------------------------------------------------------------
$specialties = getLocationSpecialties();

if ($viewMode === 'city') {
    $cityName  = $currentCity['city'];
    $stateName = $currentCity['state_name'];
    $stateId   = $currentCity['state_id'];
    $county    = $currentCity['county_name'];

    $pageTitle = "Medical Billing Services in {$cityName}, {$stateId} | MEDINEXT SOLUTIONS";
    $pageDescription = "Top-rated medical billing & RCM services in {$cityName}, {$stateName} ({$county} County). AAPC-certified coders, 98% clean claim rate, and 24/7 dedicated support.";
    $pageKeywords = "medical billing {$cityName} {$stateId}, medical billing company {$cityName}, medical coding {$cityName} {$stateName}, RCM services {$cityName}, denial management {$cityName}, healthcare billing {$county} County";
    $canonicalUrl = "https://medinextsolutions.com/locations/{$stateSlug}/{$citySlug}/";
    $geoRegion = "US-{$stateId}";
    $geoPlacename = "{$cityName}, {$stateName}";

    $nearbyCities = getNearbyCities($currentCity, 12);
    $faqs = generateLocationFAQs($currentCity);

} elseif ($viewMode === 'state') {
    $stateName = $currentState['name'];
    $stateId   = $currentState['id'];
    $cityCount = $currentState['city_count'];

    $pageTitle = "Medical Billing & RCM Services in {$stateName} ({$stateId}) | MEDINEXT SOLUTIONS";
    $pageDescription = "Outsourced medical billing, coding, and RCM services for healthcare practices across {$stateName}. Serving {$cityCount}+ cities with a 98% clean claim rate.";
    $pageKeywords = "medical billing {$stateName}, {$stateName} medical billing company, medical coding services {$stateName}, RCM {$stateName}, physician billing {$stateName}, healthcare RCM {$stateId}";
    $canonicalUrl = "https://medinextsolutions.com/locations/{$stateSlug}/";
    $geoRegion = "US-{$stateId}";
    $geoPlacename = $stateName;

    $stateCities = getCitiesByState($stateSlug, 80, 0, 'population DESC');

} else {
    // National Directory
    $pageTitle = "Medical Billing & RCM Services by US State & City | MEDINEXT SOLUTIONS";
    $pageDescription = "Explore MEDINEXT SOLUTIONS medical billing and revenue cycle management services across all 50 US states and major metropolitan areas. 98% clean claim rate nationwide.";
    $pageKeywords = "medical billing nationwide, US medical billing company, medical coding services US, state medical billing directory, local RCM services, healthcare billing all states";
    $canonicalUrl = "https://medinextsolutions.com/locations/";
    $geoRegion = "US";
    $geoPlacename = "United States";

    $allStates = getAllStates();
    $topMetros = getTopMetroCities(24);
}

// Include Main Header
require_once __DIR__ . '/includes/header.php';
?>

<main id="main-content" class="locations-page">

<?php if ($viewMode === 'city'): ?>
    <!-- ============================================================ -->
    <!-- CITY LANDING PAGE VIEW -->
    <!-- ============================================================ -->

    <!-- City Hero -->
    <header class="page-hero text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0A2647 0%, #0052CC 60%, #00C9A7 100%);">
        <div class="container mt-5 pt-4 pb-4">
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/locations/" class="text-white-50 text-decoration-none">Locations</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($stateSlug); ?>/" class="text-white-50 text-decoration-none"><?php echo htmlspecialchars($stateName); ?></a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page"><?php echo htmlspecialchars($cityName); ?></li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white bg-opacity-20 text-white small">
                        <i class="ph ph-map-pin-fill text-warning"></i>
                        <span>Serving <?php echo htmlspecialchars($cityName); ?>, <?php echo htmlspecialchars($stateId); ?> &amp; <?php echo htmlspecialchars($county); ?> County</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">
                        Medical Billing &amp; RCM Services in <span class="text-warning"><?php echo htmlspecialchars($cityName); ?>, <?php echo htmlspecialchars($stateId); ?></span>
                    </h1>
                    <p class="lead mb-4 text-white-90" style="font-size: 1.15rem; line-height: 1.6;">
                        Empowering healthcare practices, specialty clinics, and hospital systems in <strong><?php echo htmlspecialchars($cityName); ?></strong> with end-to-end revenue cycle management. AAPC-certified coding, automated claim scrubbing, and aggressive denial recovery to maximize your practice revenue by up to <strong>30%</strong>.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary shadow-sm">
                            <i class="ph ph-chart-line-up me-1"></i> Get Free Practice Audit
                        </a>
                        <a href="tel:8627992199" class="btn btn-outline-light btn-lg">
                            <i class="ph ph-phone me-1"></i> Speak with a Specialist
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="card border-0 shadow-lg rounded-4 bg-white text-dark p-4">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="ph ph-shield-check me-1 text-success"></i> <?php echo htmlspecialchars($cityName); ?> Practice Snapshot
                        </h5>
                        <ul class="list-unstyled mb-4 small text-muted">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Clean Claim Rate:</span>
                                <strong class="text-success">98.2% First-Pass</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Average A/R Days:</span>
                                <strong class="text-primary">&lt; 21 Days</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>County:</span>
                                <strong><?php echo htmlspecialchars($county); ?> County</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>HIPAA Compliance:</span>
                                <strong class="text-success">100% Guaranteed</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span>Support:</span>
                                <strong class="text-primary">24/7 Dedicated Team</strong>
                            </li>
                        </ul>
                        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-primary w-100 fw-semibold">
                            Request Custom Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Value Proposition Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 p-4 bg-white">
                        <div class="text-primary fs-1 mb-3"><i class="bi bi-graph-up-arrow"></i></div>
                        <h3 class="h5 fw-bold text-dark mb-2">Eliminate Claim Denials in <?php echo htmlspecialchars($cityName); ?></h3>
                        <p class="text-muted small mb-0">Our certified billers scrub every claim against <?php echo htmlspecialchars($stateName); ?> commercial and Medicare MAC guidelines before submission, dropping your denial rates below 2%.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 p-4 bg-white">
                        <div class="text-success fs-1 mb-3"><i class="bi bi-clock-history"></i></div>
                        <h3 class="h5 fw-bold text-dark mb-2">Fast Cash Flow &amp; Aging AR Recovery</h3>
                        <p class="text-muted small mb-0">We aggressively pursue 30-60-90+ day aging accounts from <?php echo htmlspecialchars($stateName); ?> payers, converting locked revenues into immediate cash flow for your clinic.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 p-4 bg-white">
                        <div class="text-info fs-1 mb-3"><i class="bi bi-cpu"></i></div>
                        <h3 class="h5 fw-bold text-dark mb-2">Direct EHR / PM Software Integration</h3>
                        <p class="text-muted small mb-0">No need to change your existing software. We connect directly into Epic, eClinicalWorks, AdvancedMD, Athena, Kareo, Dentrix, and 40+ systems used in <?php echo htmlspecialchars($cityName); ?>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- In-Depth Local RCM Content -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="service-content">
                        <h2 class="h3 fw-bold text-primary mb-3">
                            Tailored Revenue Cycle Management for <?php echo htmlspecialchars($cityName); ?>, <?php echo htmlspecialchars($stateId); ?> Healthcare Providers
                        </h2>
                        <p>
                            Operating a medical or dental practice in <strong><?php echo htmlspecialchars($cityName); ?>, <?php echo htmlspecialchars($stateName); ?></strong> requires balancing exceptional patient care with complex healthcare billing regulations. Medical practices throughout <strong><?php echo htmlspecialchars($county); ?> County</strong> frequently encounter mounting administrative burdens, from changing modifier rules to delayed payer authorizations.
                        </p>
                        <p>
                            At <strong>MEDINEXT SOLUTIONS</strong>, we serve as your practice's dedicated, AAPC-certified billing division. We handle patient eligibility verification, complex medical coding, electronic claim transmission, payment posting, and rigorous denial management so you can focus entirely on patient wellness.
                        </p>

                        <div class="card border-0 bg-primary bg-opacity-10 rounded-3 p-4 my-4">
                            <h3 class="h5 fw-bold text-primary mb-2">
                                <i class="ph ph-check-circle-fill text-success me-2"></i> Why Choose MEDINEXT in <?php echo htmlspecialchars($cityName); ?>?
                            </h3>
                            <ul class="mb-0 small text-muted row g-2">
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> Dedicated Account Manager</li>
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> 98% First-Pass Clean Claims</li>
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> Full HIPAA &amp; HITECH Compliance</li>
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> 24-48 Hour Denial Appeals</li>
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> Zero Software Migration Required</li>
                                <li class="col-md-6"><i class="bi bi-check2 text-primary me-1"></i> Free Baseline Practice Audit</li>
                            </ul>
                        </div>

                        <!-- Specialties Grid for City -->
                        <h2 class="h3 fw-bold text-primary mt-5 mb-4">
                            Medical Specialties We Support in <?php echo htmlspecialchars($cityName); ?>
                        </h2>
                        <div class="row g-3 mb-5">
                            <?php foreach (array_slice($specialties, 0, 8) as $spec): ?>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm bg-light p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="<?php echo $spec['icon']; ?> text-primary fs-4 me-2"></i>
                                            <h4 class="h6 fw-bold mb-0 text-dark"><?php echo htmlspecialchars($spec['name']); ?></h4>
                                        </div>
                                        <p class="small text-muted mb-2"><?php echo htmlspecialchars($spec['desc']); ?></p>
                                        <a href="<?php echo $baseUrl; ?>/<?php echo $spec['slug']; ?>/" class="small fw-semibold text-primary text-decoration-none">
                                            Learn more <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Regional Medicare Administrative Contractor (MAC) & State Payer Compliance Section -->
                        <?php if (!empty($macData)): ?>
                        <div class="card border-0 shadow-sm rounded-4 p-4 my-5 bg-white border-start border-4 border-primary">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                                <div>
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill bg-primary bg-opacity-10 text-primary small fw-bold">
                                        <i class="ph ph-shield-check-fill text-primary"></i>
                                        <span>Regional MAC &amp; State Payer Compliance Hub</span>
                                    </div>
                                    <h3 class="h4 fw-bold text-dark mb-1">
                                        <?php echo htmlspecialchars($stateName); ?> Medicare &amp; Medicaid Compliance (<?php echo htmlspecialchars($macData['code']); ?>)
                                    </h3>
                                    <p class="text-muted small mb-0">
                                        Authoritative Part A/B Medicare contractor guidelines &amp; state payer compliance parameters for healthcare providers in <?php echo htmlspecialchars($cityName); ?>, <?php echo htmlspecialchars($stateId); ?>.
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i> 98.2% Clean Claim Target
                                    </span>
                                </div>
                            </div>

                            <!-- MAC Contractor & Provider Portal Details -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-light h-100 border">
                                        <div class="small text-muted text-uppercase fw-bold mb-1">Medicare Administrative Contractor (MAC)</div>
                                        <div class="h6 fw-bold text-primary mb-1">
                                            <?php echo htmlspecialchars($macData['jurisdiction_name']); ?> &bull; <?php echo htmlspecialchars($macData['contractor']); ?>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <i class="ph ph-buildings me-1"></i> Headquarters: <?php echo htmlspecialchars($macData['headquarters']); ?>
                                        </div>
                                        <div class="small">
                                            <span class="text-muted">Official Portal:</span>
                                            <a href="<?php echo htmlspecialchars($macData['portal_url']); ?>" target="_blank" rel="noopener noreferrer" class="fw-semibold text-primary text-decoration-none">
                                                <?php echo htmlspecialchars($macData['portal_name']); ?> <i class="bi bi-box-arrow-up-right small ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-light h-100 border">
                                        <div class="small text-muted text-uppercase fw-bold mb-1">State Medicaid &amp; Secondary Payer</div>
                                        <div class="h6 fw-bold text-dark mb-1">
                                            <?php echo htmlspecialchars($macData['medicaid_program']); ?>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <i class="ph ph-bank me-1"></i> Agency: <?php echo htmlspecialchars($macData['medicaid_agency']); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <span>Timely Filing:</span>
                                            <strong class="text-dark"><?php echo htmlspecialchars($macData['medicaid_timely_filing']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Key Filing & Appeals Benchmarks -->
                            <div class="row g-3 mb-4 text-center">
                                <div class="col-sm-4">
                                    <div class="p-3 rounded-3 bg-white border shadow-none">
                                        <div class="small text-muted fw-semibold mb-1">Medicare Timely Filing</div>
                                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($macData['medicare_timely_filing']); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 rounded-3 bg-white border shadow-none">
                                        <div class="small text-muted fw-semibold mb-1">Redetermination Appeals</div>
                                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($macData['appeals_deadline']); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 rounded-3 bg-white border shadow-none">
                                        <div class="small text-muted fw-semibold mb-1">First-Pass Target</div>
                                        <div class="fw-bold text-success">98.2% MAC Scrubber Rate</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Key Local Coverage Determinations (LCDs) -->
                            <?php if (!empty($macData['key_lcds'])): ?>
                                <div class="mb-4">
                                    <h4 class="h6 fw-bold text-dark mb-2">
                                        <i class="ph ph-file-text-fill text-primary me-1"></i> Key Local Coverage Determinations (LCDs) Enforced in <?php echo htmlspecialchars($stateId); ?>:
                                    </h4>
                                    <div class="row g-2">
                                        <?php foreach ($macData['key_lcds'] as $lcd): ?>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-2 p-2 rounded bg-light border-0 small">
                                                    <span class="badge bg-primary bg-opacity-20 text-primary font-monospace"><?php echo htmlspecialchars($lcd['id']); ?></span>
                                                    <span class="text-dark fw-medium"><?php echo htmlspecialchars($lcd['name']); ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Regional Billing Nuances -->
                            <?php if (!empty($macData['billing_nuances'])): ?>
                                <div class="p-3 rounded-3 bg-light bg-opacity-75 border-0">
                                    <h4 class="h6 fw-bold text-dark mb-2">
                                        <i class="ph ph-info-fill text-primary me-1"></i> Regional <?php echo htmlspecialchars($stateName); ?> Billing Nuances &amp; Audit Safeguards:
                                    </h4>
                                    <ul class="list-unstyled mb-0 small text-muted">
                                        <?php foreach ($macData['billing_nuances'] as $nuance): ?>
                                            <li class="d-flex align-items-start gap-2 mb-1">
                                                <i class="bi bi-arrow-right-circle-fill text-primary mt-1 flex-shrink-0"></i>
                                                <span><?php echo htmlspecialchars($nuance); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Local FAQs -->
                        <h2 class="h3 fw-bold text-primary mt-5 mb-4">
                            Frequently Asked Questions &bull; <?php echo htmlspecialchars($cityName); ?> Billing
                        </h2>
                        <div class="accordion shadow-sm rounded-3 mb-5" id="cityFaqAccordion">
                            <?php foreach ($faqs as $idx => $faq): ?>
                                <div class="accordion-item border-0 border-bottom">
                                    <h3 class="accordion-header" id="faqHeading<?php echo $idx; ?>">
                                        <button class="accordion-button <?php echo $idx === 0 ? '' : 'collapsed'; ?> fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse<?php echo $idx; ?>" aria-expanded="<?php echo $idx === 0 ? 'true' : 'false'; ?>" aria-controls="faqCollapse<?php echo $idx; ?>">
                                            <?php echo htmlspecialchars($faq['q']); ?>
                                        </button>
                                    </h3>
                                    <div id="faqCollapse<?php echo $idx; ?>" class="accordion-collapse collapse <?php echo $idx === 0 ? 'show' : ''; ?>" aria-labelledby="faqHeading<?php echo $idx; ?>" data-bs-parent="#cityFaqAccordion">
                                        <div class="accordion-body text-muted small">
                                            <?php echo htmlspecialchars($faq['a']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Zip Codes Covered -->
                        <?php if (!empty($currentCity['zips'])): ?>
                            <div class="card border-0 bg-light p-4 mb-4">
                                <h3 class="h6 fw-bold text-primary mb-2">
                                    <i class="ph ph-map-pin me-1"></i> Postal &amp; Zip Codes Served in <?php echo htmlspecialchars($cityName); ?>:
                                </h3>
                                <p class="small text-muted mb-0">
                                    <?php 
                                    $zipList = array_filter(explode(' ', trim($currentCity['zips'])));
                                    echo htmlspecialchars(implode(', ', array_slice($zipList, 0, 30)));
                                    if (count($zipList) > 30) echo ', and surrounding postal areas.';
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px; z-index: 10;">
                        <!-- Lead Generation Audit Card -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h4 class="h5 fw-bold text-primary mb-2">Claim Your Free Audit</h4>
                            <p class="small text-muted mb-3">Discover hidden revenue leaks and coding gaps in your <?php echo htmlspecialchars($cityName); ?> practice.</p>
                            <form action="<?php echo $baseUrl; ?>/free-practice-audit/" method="GET">
                                <input type="hidden" name="ref_city" value="<?php echo htmlspecialchars($cityName); ?>">
                                <input type="hidden" name="ref_state" value="<?php echo htmlspecialchars($stateId); ?>">
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-sm" name="practice_name" placeholder="Practice or Clinic Name" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control form-control-sm" name="email" placeholder="Doctor / Office Email" required>
                                </div>
                                <div class="mb-3">
                                    <input type="tel" class="form-control form-control-sm" name="phone" placeholder="Contact Phone Number" required>
                                </div>
                                <button type="submit" class="btn btn-accent w-100 fw-bold">
                                    Start Free Audit <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Nearby Cities in Same State -->
                        <?php if (!empty($nearbyCities)): ?>
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                                <h4 class="h6 fw-bold text-primary mb-3">
                                    <i class="ph ph-buildings me-1"></i> Other Cities in <?php echo htmlspecialchars($stateName); ?>
                                </h4>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($nearbyCities as $near): ?>
                                        <a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($near['state_slug']); ?>/<?php echo htmlspecialchars($near['city_slug']); ?>/" class="btn btn-sm btn-outline-secondary text-truncate" style="max-width: 140px;" title="<?php echo htmlspecialchars($near['city']); ?>, <?php echo htmlspecialchars($near['state_id']); ?>">
                                            <?php echo htmlspecialchars($near['city']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($stateSlug); ?>/" class="small fw-bold text-primary text-decoration-none">
                                        View All <?php echo htmlspecialchars($stateName); ?> Locations &rarr;
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Local JSON-LD Schemas -->
    <script type="application/ld+json">
    <?php
    $knowsAboutList = !empty($macData['knows_about']) ? $macData['knows_about'] : [
        'Revenue Cycle Management (RCM)',
        'Medical Billing Services',
        'AAPC-Certified Medical Coding',
        'Medicare & Medicaid Claim Adjudication',
        'Denial Management & Appeals'
    ];

    $citySchemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => ['MedicalBusiness', 'ProfessionalService'],
                '@id' => $canonicalUrl . '#medicalbusiness',
                'name' => "MEDINEXT SOLUTIONS - {$cityName} Medical Billing Services",
                'description' => "AAPC-certified medical billing and RCM services for clinics and healthcare providers in {$cityName}, {$stateName} under " . (!empty($macData['contractor_short']) ? "{$macData['contractor_short']} ({$macData['code']})" : "Medicare MAC") . " guidelines.",
                'url' => $canonicalUrl,
                'telephone' => '+1-862-799-2199',
                'email' => 'info@medinextsolutions.com',
                'priceRange' => '$$',
                'areaServed' => [
                    [
                        '@type' => 'City',
                        'name' => $cityName
                    ],
                    [
                        '@type' => 'AdministrativeArea',
                        'name' => "{$county} County"
                    ],
                    [
                        '@type' => 'State',
                        'name' => $stateName
                    ]
                ],
                'knowsAbout' => $knowsAboutList,
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $cityName,
                    'addressRegion' => $stateId,
                    'addressCountry' => 'US'
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => (float)$currentCity['lat'],
                    'longitude' => (float)$currentCity['lng']
                ]
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $canonicalUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => 'https://medinextsolutions.com/'
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Locations',
                        'item' => 'https://medinextsolutions.com/locations/'
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $stateName,
                        'item' => "https://medinextsolutions.com/locations/{$stateSlug}/"
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 4,
                        'name' => $cityName,
                        'item' => $canonicalUrl
                    ]
                ]
            ],
            [
                '@type' => 'FAQPage',
                '@id' => $canonicalUrl . '#faq',
                'mainEntity' => array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['q'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['a']
                        ]
                    ];
                }, $faqs)
            ]
        ]
    ];

    echo json_encode($citySchemaGraph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>


<?php elseif ($viewMode === 'state'): ?>
    <!-- ============================================================ -->
    <!-- STATE HUB VIEW -->
    <!-- ============================================================ -->

    <!-- State Hero -->
    <header class="page-hero text-white py-5 position-relative" style="background: linear-gradient(135deg, #0A2647 0%, #0052CC 70%, #00C9A7 100%);">
        <div class="container mt-5 pt-4 pb-4">
            <nav aria-label="Breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/locations/" class="text-white-50 text-decoration-none">Locations</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page"><?php echo htmlspecialchars($stateName); ?></li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white bg-opacity-20 text-white small">
                        <i class="ph ph-flag-fill text-warning"></i>
                        <span>Statewide Healthcare RCM Coverage &bull; <?php echo number_format((int)$cityCount); ?> Cities &amp; Towns</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">
                        Medical Billing &amp; RCM Services in <span class="text-warning"><?php echo htmlspecialchars($stateName); ?></span>
                    </h1>
                    <p class="lead mb-4 text-white-90" style="font-size: 1.15rem; line-height: 1.6;">
                        Delivering tailored medical coding, billing, and credentialing services across <?php echo htmlspecialchars($stateName); ?>. Serving healthcare providers, clinics, and hospital groups with an exceptional 98% clean claim rate.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary">
                            Get Free Practice Audit
                        </a>
                        <a href="#state-cities" class="btn btn-outline-light btn-lg">
                            Browse <?php echo htmlspecialchars($stateName); ?> Cities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Regional Medicare Administrative Contractor (MAC) & Statewide Compliance Section -->
    <?php if (!empty($macData)): ?>
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white border-start border-4 border-primary">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill bg-primary bg-opacity-10 text-primary small fw-bold">
                            <i class="ph ph-shield-check-fill text-primary"></i>
                            <span>Statewide Regulatory &amp; MAC Compliance Hub</span>
                        </div>
                        <h2 class="h3 fw-bold text-dark mb-1">
                            <?php echo htmlspecialchars($stateName); ?> Medicare MAC Jurisdiction &amp; Payer Regulations (<?php echo htmlspecialchars($macData['code']); ?>)
                        </h2>
                        <p class="text-muted small mb-0">
                            Comprehensive Part A/B Medicare contractor policies, LCD standards, and state Medicaid filing rules for <?php echo htmlspecialchars($stateName); ?> medical practices.
                        </p>
                    </div>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
                            <i class="bi bi-shield-lock-fill me-1"></i> 98.2% First-Pass Clean Claim Rate
                        </span>
                    </div>
                </div>

                <!-- MAC Operator & Portal Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="p-4 rounded-3 bg-light h-100 border">
                            <div class="small text-muted text-uppercase fw-bold mb-1">Medicare Administrative Contractor (MAC)</div>
                            <h3 class="h5 fw-bold text-primary mb-2">
                                <?php echo htmlspecialchars($macData['jurisdiction_name']); ?>
                            </h3>
                            <p class="small text-muted mb-2">
                                <strong>Contractor:</strong> <?php echo htmlspecialchars($macData['contractor']); ?> (HQ: <?php echo htmlspecialchars($macData['headquarters']); ?>)
                            </p>
                            <div class="small">
                                <span class="text-muted">Electronic Portal:</span>
                                <a href="<?php echo htmlspecialchars($macData['portal_url']); ?>" target="_blank" rel="noopener noreferrer" class="fw-semibold text-primary text-decoration-none">
                                    <?php echo htmlspecialchars($macData['portal_name']); ?> <i class="bi bi-box-arrow-up-right small ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-4 rounded-3 bg-light h-100 border">
                            <div class="small text-muted text-uppercase fw-bold mb-1">State Medicaid &amp; Public Health Agency</div>
                            <h3 class="h5 fw-bold text-dark mb-2">
                                <?php echo htmlspecialchars($macData['medicaid_program']); ?>
                            </h3>
                            <p class="small text-muted mb-2">
                                <strong>Governing Agency:</strong> <?php echo htmlspecialchars($macData['medicaid_agency']); ?>
                            </p>
                            <div class="small text-muted">
                                <span>Medicaid Timely Filing:</span>
                                <strong class="text-dark"><?php echo htmlspecialchars($macData['medicaid_timely_filing']); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timely Filing, Appeals, and Compliance Metrics -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-muted fw-semibold mb-1">Medicare Timely Filing Window</div>
                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($macData['medicare_timely_filing']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-muted fw-semibold mb-1">First-Level Appeals (Redetermination)</div>
                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($macData['appeals_deadline']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-muted fw-semibold mb-1">First-Pass Clean Claim Target</div>
                            <div class="fw-bold text-success">98.2% Scrubber Rate</div>
                        </div>
                    </div>
                </div>

                <!-- LCD List -->
                <?php if (!empty($macData['key_lcds'])): ?>
                    <div class="mb-4">
                        <h3 class="h6 fw-bold text-dark mb-3">
                            <i class="ph ph-file-text-fill text-primary me-1"></i> Active Local Coverage Determinations (LCDs) in <?php echo htmlspecialchars($stateName); ?> (<?php echo htmlspecialchars($macData['code']); ?>):
                        </h3>
                        <div class="row g-3">
                            <?php foreach ($macData['key_lcds'] as $lcd): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-2 p-3 rounded bg-light border small h-100">
                                        <span class="badge bg-primary text-white font-monospace"><?php echo htmlspecialchars($lcd['id']); ?></span>
                                        <span class="text-dark fw-medium"><?php echo htmlspecialchars($lcd['name']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Regional Billing Nuances -->
                <?php if (!empty($macData['billing_nuances'])): ?>
                    <div class="p-3 rounded-3 bg-light border">
                        <h3 class="h6 fw-bold text-dark mb-2">
                            <i class="ph ph-info-fill text-primary me-1"></i> Regional Billing Nuances &amp; Compliance Safeguards:
                        </h3>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <?php foreach ($macData['billing_nuances'] as $nuance): ?>
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <i class="bi bi-arrow-right-circle-fill text-primary mt-1 flex-shrink-0"></i>
                                    <span><?php echo htmlspecialchars($nuance); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- State Cities Directory Grid -->
    <section class="py-5" id="state-cities">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold text-primary">Healthcare Locations &amp; Cities in <?php echo htmlspecialchars($stateName); ?></h2>
                <p class="text-muted">Select your city below for specialized local medical billing solutions and regional payer expertise.</p>
            </div>

            <div class="row g-3">
                <?php foreach ($stateCities as $city): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($stateSlug); ?>/<?php echo htmlspecialchars($city['city_slug']); ?>/" class="card h-100 border-0 shadow-sm p-3 text-decoration-none text-dark bg-white hover-shadow transition-all">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="h6 fw-bold text-primary mb-1"><?php echo htmlspecialchars($city['city']); ?></h3>
                                    <p class="small text-muted mb-0"><?php echo htmlspecialchars($city['county_name']); ?> County</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-muted border small">
                                        Pop: <?php echo number_format((int)$city['population']); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Schema for State -->
    <script type="application/ld+json">
    <?php
    $stateKnowsAbout = !empty($macData['knows_about']) ? $macData['knows_about'] : [
        'Revenue Cycle Management (RCM)',
        'Statewide Medical Billing Services',
        'AAPC-Certified Medical Coding',
        'Medicare MAC Compliance',
        'Medicaid Reimbursement Optimization'
    ];

    $stateSchemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => ['MedicalBusiness', 'ProfessionalService'],
                '@id' => $canonicalUrl . '#medicalbusiness',
                'name' => "MEDINEXT SOLUTIONS - {$stateName} Medical Billing & RCM Services",
                'description' => "Statewide medical billing, AAPC-certified coding, and Medicare MAC " . (!empty($macData['code']) ? $macData['code'] : 'compliance') . " guidelines for healthcare practices across {$stateName}.",
                'url' => $canonicalUrl,
                'telephone' => '+1-862-799-2199',
                'email' => 'info@medinextsolutions.com',
                'priceRange' => '$$',
                'areaServed' => [
                    '@type' => 'State',
                    'name' => $stateName
                ],
                'knowsAbout' => $stateKnowsAbout,
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressRegion' => $stateId,
                    'addressCountry' => 'US'
                ]
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $canonicalUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => 'https://medinextsolutions.com/'
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Locations',
                        'item' => 'https://medinextsolutions.com/locations/'
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $stateName,
                        'item' => $canonicalUrl
                    ]
                ]
            ]
        ]
    ];

    echo json_encode($stateSchemaGraph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>


<?php else: ?>
    <!-- ============================================================ -->
    <!-- NATIONAL DIRECTORY VIEW -->
    <!-- ============================================================ -->

    <!-- Directory Hero -->
    <header class="page-hero text-white py-5 position-relative" style="background: linear-gradient(135deg, #0A2647 0%, #0052CC 70%, #00C9A7 100%);">
        <div class="container mt-5 pt-4 pb-4">
            <nav aria-label="Breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Locations</li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white bg-opacity-20 text-white small">
                        <i class="ph ph-globe-hemisphere-west-fill text-warning"></i>
                        <span>United States Nationwide RCM Directory</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">
                        Medical Billing &amp; RCM Across <span class="text-warning">All 50 US States</span>
                    </h1>
                    <p class="lead mb-4 text-white-90" style="font-size: 1.15rem; line-height: 1.6;">
                        MEDINEXT SOLUTIONS delivers nationwide medical billing, revenue cycle management, and provider credentialing services across 31,000+ US cities, helping healthcare practices eliminate denials and accelerate cash flow.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary">
                            Get Free Practice Audit
                        </a>
                        <a href="#states-grid" class="btn btn-outline-light btn-lg">
                            Browse All 50 States
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Top US Healthcare Metro Hubs -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase small">Metropolitan Coverage</span>
                <h2 class="h2 fw-bold text-dark mt-2">Top US Healthcare Metro Hubs</h2>
                <p class="text-muted">Explore high-volume medical billing services in major US healthcare markets.</p>
            </div>

            <div class="row g-3">
                <?php foreach ($topMetros as $metro): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($metro['state_slug']); ?>/<?php echo htmlspecialchars($metro['city_slug']); ?>/" class="card h-100 border-0 shadow-sm p-3 text-decoration-none text-dark bg-white hover-shadow transition-all">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="h6 fw-bold text-primary mb-1"><?php echo htmlspecialchars($metro['city']); ?>, <?php echo htmlspecialchars($metro['state_id']); ?></h3>
                                    <p class="small text-muted mb-0"><?php echo htmlspecialchars($metro['county_name']); ?> County</p>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- All 50 States Grid -->
    <section class="py-5" id="states-grid">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase small">Nationwide Directory</span>
                <h2 class="h2 fw-bold text-dark mt-2">Browse Medical Billing by State</h2>
                <p class="text-muted">Select any US state below to view local city locations, regional payer details, and healthcare RCM coverage.</p>
            </div>

            <div class="row g-3">
                <?php foreach ($allStates as $st): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <a href="<?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($st['slug']); ?>/" class="card h-100 border-0 shadow-sm p-3 text-decoration-none text-dark bg-white hover-shadow transition-all border-start border-primary border-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1"><?php echo htmlspecialchars($st['name']); ?> (<?php echo htmlspecialchars($st['id']); ?>)</h3>
                                    <p class="small text-muted mb-0"><?php echo number_format((int)$st['city_count']); ?> Cities &amp; Towns</p>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary small">View</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Schema for National Directory -->
    <script type="application/ld+json">
    <?php
    $nationalSchemaGraph = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        '@id' => $canonicalUrl . '#breadcrumb',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => 'https://medinextsolutions.com/'
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Locations',
                'item' => $canonicalUrl
            ]
        ]
    ];
    echo json_encode($nationalSchemaGraph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>

<?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
