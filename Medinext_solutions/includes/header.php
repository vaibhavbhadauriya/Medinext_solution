<?php

/**
 * MEDINEXT SOLUTIONS - Header Include
 * Shared header with navbar for all pages
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

$currentPage = getCurrentPage();
$csrfToken = generateCSRFToken();
// Dynamic SEO Meta Tags Logic
$seoData = require __DIR__ . '/meta_config.php';

// Detect base URL automatically (localhost subdirectory vs live domain)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
           (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$scheme = $isHttps ? 'https' : 'http';

// Detect if we are running in the XAMPP subdirectory (locally or via tunnel)
$isSubdir = strpos($_SERVER['REQUEST_URI'] ?? '', '/Medinext_solution/Medinext_solutions') === 0;

if ($isSubdir) {
    $baseUrl = $scheme . '://' . $host . '/Medinext_solution/Medinext_solutions';
    $basePath = '/Medinext_solution/Medinext_solutions';
} else {
    $baseUrl = $scheme . '://' . $host;
    $basePath = '';
}

// Normalize request URI for exact mapping
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Strip basePath if present
if ($basePath !== '' && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Redirect logic to enforce clean URLs and eliminate duplicate content for SEO
$needsRedirect = false;
$cleanUri = $requestUri;

if ($requestUri === '' || $requestUri === '//' || $requestUri === '///') {
    $cleanUri = '/';
    if ($requestUri !== '/') $needsRedirect = true;
} elseif (preg_match('/\.php$/i', $requestUri)) {
    // If it ends in .php (but is not /, which is handled above), map it to a trailing slash clean URL
    $needsRedirect = true;
    
    // Explicit mappings for SEO consistency
    if (strpos($requestUri, '//medical-billing-services/') !== false) {
        $cleanUri = '/medical-billing-services/';
    } elseif (strpos($requestUri, '//about/') !== false) {
        $cleanUri = '/about/';
    } elseif (strpos($requestUri, '//contact/') !== false) {
        $cleanUri = '/contact/';
    } elseif (strpos($requestUri, '//blog/') !== false) {
        $cleanUri = '/blog/';
    } else {
        // Generic mapping: /something.php -> /something/
        $cleanUri = preg_replace('/\.php$/i', '/', $requestUri);
    }
} else {
    // Force trailing slash for clean URLs (if not just '/')
    if ($cleanUri !== '/' && rtrim($cleanUri, '/') === '') {
        $cleanUri = '/';
    } else if ($cleanUri !== '/' && substr($cleanUri, -1) !== '/') {
        $cleanUri .= '/';
        // Only redirect if it doesn't have an extension (don't redirect .css, .js, .png, etc.)
        $ext = pathinfo($cleanUri, PATHINFO_EXTENSION);
        if (empty($ext) || rtrim($ext, '/') === '') {
            // $needsRedirect = true; // Optional: enforce trailing slash redirect strictly across the site too
        }
    }
    
    // Business logic mappings for clean URLs already
    if ($cleanUri === '/services/') $cleanUri = '/medical-billing-services/';
}

// Perform the 301 Permanent Redirect to instantly fix the GSC crawler SEO issue
if ($needsRedirect && !headers_sent()) {
    $redirectTarget = $baseUrl . $cleanUri;
    // Preserve query strings
    if (!empty($_SERVER['QUERY_STRING'])) {
        $redirectTarget .= '?' . $_SERVER['QUERY_STRING'];
    }
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $redirectTarget);
    exit();
}

$requestUri = $cleanUri;

// Fetch specific meta, cascade to Homepage if missing
$meta = $seoData[$requestUri] ?? ($seoData[rtrim($requestUri, '/')] ?? $seoData['/']);

$pageTitle = $pageTitle ?? $meta['title'];
$pageDescription = $pageDescription ?? $meta['desc'];
$pageKeywords = $pageKeywords ?? $meta['kws'];
$pageRobots = $pageRobots ?? $meta['robots'];
$pageType = $pageType ?? $meta['type'];

$canonicalUrl = $canonicalUrl ?? ("https://medinextsolutions.com" . ($requestUri === '/' ? '/' : rtrim($requestUri, '/') . '/'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-573N34G5');</script>
<!-- End Google Tag Manager -->


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="<?php echo $baseUrl; ?>/">

    <!-- SEO Meta Tags -->
    <title><?php echo sanitizeInput($pageTitle); ?></title>
    <meta name="description" content="<?php echo sanitizeInput($pageDescription); ?>">
    <meta name="keywords" content="<?php echo sanitizeInput($pageKeywords); ?>">
    <meta name="robots" content="<?php echo sanitizeInput($pageRobots); ?>">
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">
    <meta name="author" content="MEDINEXT SOLUTIONS">
    <meta name="theme-color" content="#0052CC">
    <!-- Search Engine Verification – uncomment and replace placeholder values with real codes -->

    <!-- Geo-Targeting -->
    <meta name="geo.region" content="<?php echo sanitizeInput($geoRegion ?? 'US-FL'); ?>">
    <meta name="geo.placename" content="<?php echo sanitizeInput($geoPlacename ?? 'Orlando'); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo sanitizeInput($pageType); ?>">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:title" content="<?php echo sanitizeInput($pageTitle); ?>">
    <meta property="og:description" content="<?php echo sanitizeInput($pageDescription); ?>">
    <meta property="og:image" content="https://medinextsolutions.com/assets/images/og-image.jpg">
    <meta property="og:site_name" content="MEDINEXT SOLUTIONS">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@MedinextSolutions">
    <meta name="twitter:title" content="<?php echo sanitizeInput($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo sanitizeInput($pageDescription); ?>">
    <meta name="twitter:image" content="https://medinextsolutions.com/assets/images/og-image.jpg">

    <!-- Favicons -->
    <link rel="icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">


    <!-- ============================================================ -->
    <!-- SCHEMA 5: BreadcrumbList (Homepage) -->
    <!-- ============================================================ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "@id": "<?php echo $canonicalUrl; ?>#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://medinextsolutions.com/"
        }
      ]
    }
    </script>

    <!-- Include Organization Schema with Optional Reviews -->
    <?php require_once __DIR__ . '/schema-organization.php'; ?>
    
    <!-- Dynamic Service and Breadcrumb Schema -->
    <?php require_once __DIR__ . '/schema-dynamic.php'; ?>

    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <link rel="author" href="humans.txt">



    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Google Fonts (reduced to essential weights only) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS - Animate On Scroll -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Swiper.js CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Phosphor Icons (loaded async - not render blocking) -->
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

    <!-- Custom Stylesheets -->
    <link href="assets/css/style.css?v=3.5" rel="stylesheet">
    <link href="assets/css/animations.css?v=2.1" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' stop-color='%230A2647'/><stop offset='100%25' stop-color='%232C74B3'/></linearGradient></defs><circle cx='50' cy='50' r='45' fill='url(%23g)'/><text x='50' y='65' font-size='45' font-weight='bold' text-anchor='middle' fill='white'>M</text></svg>">
    <?php include __DIR__ . '/seo-head-common.php'; ?>
</head>
<?php
$bodyClass = '';
$darkHeroPages = ['services', 'about', 'blog', 'contact', 'free-practice-audit'];
if (in_array(basename($currentPage), $darkHeroPages)) {
    $bodyClass = 'dark-hero';
}
?>
<body class="<?php echo $bodyClass; ?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-573N34G5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


    <!-- Skip Navigation Link (accessibility + SEO) -->
    <a href="#main-content" class="ph-skip-nav">Skip to main content</a>
    <!-- ============================================ -->
    <!-- Page Loader -->
    <!-- ============================================ -->
    <div id="page-loader" class="page-loader">
        <div class="cube-loader-container">
            <div class="cube-scene">
                <div class="cube-spinning">
                    <div class="cube-core"></div>
                    <div class="side-wrapper front"><div class="face"></div></div>
                    <div class="side-wrapper back"><div class="face"></div></div>
                    <div class="side-wrapper right"><div class="face"></div></div>
                    <div class="side-wrapper left"><div class="face"></div></div>
                    <div class="side-wrapper top"><div class="face"></div></div>
                    <div class="side-wrapper bottom"><div class="face"></div></div>
                </div>
                <div class="floor-shadow"></div>
            </div>
            <div class="loader-text-group">
                <h3 class="loader-heading">Loading</h3>
                <p class="loader-desc">Preparing your experience, please wait?</p>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- Scroll Progress Bar -->
    <!-- ============================================ -->
    <div id="scroll-progress" class="scroll-progress"></div>

    <!-- ============================================ -->
    <!-- Navigation -->
    <!-- ============================================ -->

<nav class="navbar navbar-expand-lg fixed-top prisma-nav" id="mainNav">
        <div class="container">
                <a class="navbar-brand prisma-brand me-4" href="<?php echo $baseUrl; ?>/">
                    <div class="brand-logo-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/logo.png?v=8" alt="MEDINEXT SOLUTIONS Logo" width="200" height="80">
                    </div>
                </a>
            <!-- Mobile Toggle -->
            <button class="navbar-toggler prisma-toggler" type="button" id="navToggler" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActivePage('index'); ?>" href="<?php echo $baseUrl; ?>/">Home</a>
                    </li>
                    
                    <!-- Mega Menu: Services -->
                    <li class="nav-item dropdown mega-dropdown mega-3col-dropdown">
                        <a class="nav-link dropdown-toggle <?php echo isActivePage('services'); ?>" href="<?php echo $baseUrl; ?>/medical-billing-services/" id="servicesDropdown" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static">
                            Services
                        </a>
                        <div class="dropdown-menu mega-menu mega-menu-fullwidth" aria-labelledby="servicesDropdown">
                            <!-- Top Tabs -->
                            <div class="mega-top-tabs">
                                <a href="#" class="mega-top-tab active" id="mega-tab-medical">Medical RCM</a>
                                <a href="#" class="mega-top-tab" id="mega-tab-dental">Dental RCM</a>
                            </div>

                            <!-- ===== MEDICAL TAB ===== -->
                            <div class="mega-content-wrapper" id="mega-content-medical">
                                <!-- Left Nav: Category Items -->
                                <div class="mn-left-nav">
                                    <div class="mn-nav-item active" data-panel="mn-panel-services">
                                        <div class="mn-nav-icon"><i class="bi bi-currency-exchange"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">RCM Services</span>
                                            <span class="mn-nav-desc">End-to-end revenue cycle solutions for your practice</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                    <div class="mn-nav-item" data-panel="mn-panel-specialties">
                                        <div class="mn-nav-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">Billing Specialties</span>
                                            <span class="mn-nav-desc">Expert billing across 20+ medical specialties</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                    <div class="mn-nav-item" data-panel="mn-panel-who">
                                        <div class="mn-nav-icon"><i class="bi bi-people"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">Who We Help</span>
                                            <span class="mn-nav-desc">Healthcare providers we serve nationwide</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                </div>

                                <!-- Right Panels -->
                                <div class="mn-right-panels">
                                    <!-- Panel: RCM Services -->
                                    <div class="mn-panel active" id="mn-panel-services">
                                        <a href="<?php echo $baseUrl; ?>/revenue-cycle-management/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-currency-dollar"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Revenue Cycle Management</span>
                                                <span class="mn-panel-desc">Comprehensive RCM, fully handled by experts</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/denial-management-services/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-shield-exclamation"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Denial Management</span>
                                                <span class="mn-panel-desc">Root cause analysis and aggressive appeal strategies</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/prior-authorization-services/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-clipboard2-check"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Prior Authorization</span>
                                                <span class="mn-panel-desc">Faster approvals, fewer delays in patient care</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/medical-coding-services/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-code-slash"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Medical Coding</span>
                                                <span class="mn-panel-desc">Certified coders ensuring accuracy and compliance</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/provider-credentialing-services/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-person-check"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Provider Credentialing</span>
                                                <span class="mn-panel-desc">Enrollment and credentialing with all major payers</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/healthcare-operations/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-gear"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Healthcare Operations</span>
                                                <span class="mn-panel-desc">Streamline your practice workflow end-to-end</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                    </div>

                                    <!-- Panel: Billing Specialties -->
                                    <div class="mn-panel" id="mn-panel-specialties">
                                        <div class="mn-specialties-grid">
                                            <a href="<?php echo $baseUrl; ?>/therapy-billing-services/" class="mn-spec-link"><i class="bi bi-activity"></i> Therapy (PT/OT/ST)</a>
                                            <a href="<?php echo $baseUrl; ?>/pain-management-billing/" class="mn-spec-link"><i class="bi bi-bandaid"></i> Pain Management</a>
                                            <a href="<?php echo $baseUrl; ?>/cardiovascular-billing-services/" class="mn-spec-link"><i class="bi bi-heart-pulse"></i> Cardiology</a>
                                            <a href="<?php echo $baseUrl; ?>/oncology-hematology-billing/" class="mn-spec-link"><i class="bi bi-capsule"></i> Oncology</a>
                                            <a href="<?php echo $baseUrl; ?>/behavioral-health-billing/" class="mn-spec-link"><i class="bi bi-person-bounding-box"></i> Behavioral Health</a>
                                            <a href="<?php echo $baseUrl; ?>/dme-billing-services/" class="mn-spec-link"><i class="bi bi-wheelchair"></i> DME Billing</a>
                                            <a href="<?php echo $baseUrl; ?>/neurology-billing-services/" class="mn-spec-link"><i class="bi bi-graph-up-arrow"></i> Neurology</a>
                                            <a href="<?php echo $baseUrl; ?>/radiology-billing-services/" class="mn-spec-link"><i class="bi bi-camera"></i> Radiology</a>
                                            <a href="<?php echo $baseUrl; ?>/anesthesia-billing/" class="mn-spec-link"><i class="bi bi-droplet"></i> Anesthesia</a>
                                            <a href="<?php echo $baseUrl; ?>/dermatology-billing/" class="mn-spec-link"><i class="bi bi-person-lines-fill"></i> Dermatology</a>
                                            <a href="<?php echo $baseUrl; ?>/emergency-medicine-billing/" class="mn-spec-link"><i class="bi bi-lightning"></i> Emergency Medicine</a>
                                            <a href="<?php echo $baseUrl; ?>/family-medicine-billing/" class="mn-spec-link"><i class="bi bi-house-heart"></i> Family Medicine</a>
                                            <a href="<?php echo $baseUrl; ?>/general-surgery-billing/" class="mn-spec-link"><i class="bi bi-scissors"></i> General Surgery</a>
                                            <a href="<?php echo $baseUrl; ?>/internal-medicine-billing/" class="mn-spec-link"><i class="bi bi-plus-circle"></i> Internal Medicine</a>
                                            <a href="<?php echo $baseUrl; ?>/ophthalmology-billing/" class="mn-spec-link"><i class="bi bi-eye"></i> Ophthalmology</a>
                                            <a href="<?php echo $baseUrl; ?>/orthopedic-billing/" class="mn-spec-link"><i class="bi bi-person-arms-up"></i> Orthopedic</a>
                                            <a href="<?php echo $baseUrl; ?>/pathology-billing/" class="mn-spec-link"><i class="bi bi-virus"></i> Pathology</a>
                                            <a href="<?php echo $baseUrl; ?>/radiation-oncology-billing/" class="mn-spec-link"><i class="bi bi-radioactive"></i> Radiation Oncology</a>
                                            <a href="<?php echo $baseUrl; ?>/wound-care-billing/" class="mn-spec-link"><i class="bi bi-bandaid"></i> Wound Care</a>
                                            <a href="<?php echo $baseUrl; ?>/home-health-billing/" class="mn-spec-link"><i class="bi bi-house-add"></i> Home Health</a>
                                        </div>
                                    </div>

                                    <!-- Panel: Who We Help -->
                                    <div class="mn-panel" id="mn-panel-who">
                                        <a href="<?php echo $baseUrl; ?>/physician-groups/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-people"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Physician Groups</span>
                                                <span class="mn-panel-desc">Solo practitioners to large multi-specialty groups</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/hospitals-health-systems/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-hospital"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Hospitals &amp; Health Systems</span>
                                                <span class="mn-panel-desc">Enterprise-grade RCM for inpatient &amp; outpatient</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/fqhc-billing/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-file-medical"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">FQHCs &amp; Community Health</span>
                                                <span class="mn-panel-desc">PPS rate optimization and sliding fee schedules</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/ambulatory-surgery-centers/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-building"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Ambulatory Surgery Centers</span>
                                                <span class="mn-panel-desc">ASC-specific coding and facility billing</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/correctional-billing/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-building-lock"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Correctional Healthcare</span>
                                                <span class="mn-panel-desc">Specialized billing for correctional facilities</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== DENTAL TAB ===== -->
                            <div class="mega-content-wrapper" id="mega-content-dental" style="display:none;">
                                <!-- Left Nav -->
                                <div class="mn-left-nav">
                                    <div class="mn-nav-item active" data-panel="mn-panel-dental-billing">
                                        <div class="mn-nav-icon"><i class="bi bi-file-earmark-medical"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">Dental Billing</span>
                                            <span class="mn-nav-desc">Complete dental revenue cycle solutions</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                    <div class="mn-nav-item" data-panel="mn-panel-dental-ops">
                                        <div class="mn-nav-icon"><i class="bi bi-gear"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">Dental Operations</span>
                                            <span class="mn-nav-desc">Back-office operations for dental practices</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                    <div class="mn-nav-item" data-panel="mn-panel-dental-who">
                                        <div class="mn-nav-icon"><i class="bi bi-people"></i></div>
                                        <div class="mn-nav-text">
                                            <span class="mn-nav-title">Who We Help</span>
                                            <span class="mn-nav-desc">Dental providers we serve nationwide</span>
                                        </div>
                                        <i class="bi bi-chevron-right mn-nav-arrow"></i>
                                    </div>
                                </div>
                                <!-- Right Panels -->
                                <div class="mn-right-panels">
                                    <div class="mn-panel active" id="mn-panel-dental-billing">
                                        <a href="<?php echo $baseUrl; ?>/dental-billing-services/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-file-earmark-medical"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Dental Billing Services</span><span class="mn-panel-desc">Full-service dental billing and collections</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/dental-insurance-verification/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-shield-check"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Insurance Verification</span><span class="mn-panel-desc">Eligibility checks before every appointment</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/fee-schedule-maintenance/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-calendar-check"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Fee Schedule Maintenance</span><span class="mn-panel-desc">Keep your fee schedules optimized and current</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/accounts-receivable-followup/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-graph-up"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">AR Follow-Up</span><span class="mn-panel-desc">Aggressive follow-up to reduce days in A/R</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                    </div>
                                    <div class="mn-panel" id="mn-panel-dental-ops">
                                        <a href="<?php echo $baseUrl; ?>/dental-denial-management/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-x-octagon"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Payment &amp; Denial Management</span><span class="mn-panel-desc">Track, appeal, and recover denied claims</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/dental-credentialing/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-person-badge"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Dental Credentialing</span><span class="mn-panel-desc">Payer enrollment for dental providers</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/accounts-payable-outsourcing/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-cash-coin"></i></div>
                                            <div class="mn-panel-text"><span class="mn-panel-title">Accounts Payable Outsourcing</span><span class="mn-panel-desc">Outsource AP processing for operational efficiency</span></div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                    </div>
                                    <div class="mn-panel" id="mn-panel-dental-who">
                                        <a href="<?php echo $baseUrl; ?>/group-dental-practices/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-building"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Group Dental Practices &amp; DSOs</span>
                                                <span class="mn-panel-desc">Scalable billing for multi-location dental groups and DSOs</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>/private-dental-practices/" class="mn-panel-link">
                                            <div class="mn-panel-icon"><i class="bi bi-house-heart"></i></div>
                                            <div class="mn-panel-text">
                                                <span class="mn-panel-title">Private Dental Practices</span>
                                                <span class="mn-panel-desc">Personalized RCM solutions for independent dental offices</span>
                                            </div>
                                            <i class="bi bi-chevron-right mn-panel-arrow"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>


                    <!-- About Link -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActivePage('about'); ?>" href="<?php echo $baseUrl; ?>/about/">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo isActivePage('locations'); ?>" href="<?php echo $baseUrl; ?>/locations/">Locations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActivePage('blog'); ?>" href="<?php echo $baseUrl; ?>/blog/">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActivePage('contact'); ?>" href="<?php echo $baseUrl; ?>/contact/">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn nav-cta uiverse-btn" href="<?php echo $baseUrl; ?>/free-practice-audit/">
                            <div class="uiverse-content">
                                <span class="uiverse-text">
                                    <span style="--i:1">G</span><span style="--i:2">e</span><span style="--i:3">t</span>
                                    <span>&nbsp;</span>
                                    <span style="--i:4">F</span><span style="--i:5">r</span><span style="--i:6">e</span><span style="--i:7">e</span>
                                    <span>&nbsp;</span>
                                    <span style="--i:8">C</span><span style="--i:9">o</span><span style="--i:10">n</span><span style="--i:11">s</span><span style="--i:12">u</span><span style="--i:13">l</span><span style="--i:14">t</span><span style="--i:15">a</span><span style="--i:16">t</span><span style="--i:17">i</span><span style="--i:18">o</span><span style="--i:19">n</span>
                                </span>
                                <svg class="uiverse-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Drawer Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <div class="mobile-drawer" id="mobileDrawer">
            <div class="drawer-header p-4 border-bottom d-flex justify-content-between align-items-center">
                <div class="drawer-brand">
                    <img src="<?php echo $baseUrl; ?>/assets/images/logo.png?v=7" alt="MEDINEXT SOLUTIONS Logo" style="height: 60px; width: auto; object-fit: contain;">
                </div>
                <button type="button" class="btn-close drawer-close" aria-label="Close"></button>
            </div>
        <div class="drawer-body">
            <ul class="drawer-nav">
                <li><a href="<?php echo $baseUrl; ?>/" class="<?php echo isActivePage('index'); ?>"><i class="bi bi-house-fill"></i> Home</a></li>
                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/" class="<?php echo isActivePage('services'); ?>"><i class="ph ph-grid-four"></i> Services</a></li>
                <li><a href="<?php echo $baseUrl; ?>/locations/" class="<?php echo isActivePage('locations'); ?>"><i class="ph ph-map-pin"></i> Locations</a></li>
                <li><a href="<?php echo $baseUrl; ?>/about/" class="<?php echo isActivePage('about'); ?>"><i class="ph ph-info"></i> About</a></li>
                <li><a href="<?php echo $baseUrl; ?>/#why-us"><i class="bi bi-star-fill"></i> Why Us</a></li>
                <li><a href="<?php echo $baseUrl; ?>/blog/" class="<?php echo isActivePage('blog'); ?>"><i class="ph ph-article"></i> Blog</a></li>
                <li><a href="<?php echo $baseUrl; ?>/contact/" class="<?php echo isActivePage('contact'); ?>"><i class="bi bi-envelope"></i> Contact</a></li>
            </ul>
            <div class="drawer-cta">
                <a class="btn btn-accent w-100" href="<?php echo $baseUrl; ?>/free-practice-audit/">
                    <i class="ph ph-calendar-check"></i>
                    Get Free Consultation
                </a>
            </div>
            <div class="drawer-contact">
                <p><i class="bi bi-telephone"></i> 862-799-2199</p>
                <p><i class="ph ph-envelope-simple"></i> info@medinextsolutions.com</p>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- Animated Mesh Background -->
    <!-- ============================================ -->
    <div class="mesh-bg">
        <div class="mesh-blob mesh-blob-1"></div>
        <div class="mesh-blob mesh-blob-2"></div>
        <div class="mesh-blob mesh-blob-3"></div>
        <div class="mesh-blob mesh-blob-4"></div>
    </div>

    <!-- Main Content Wrapper -->
    <main id="main-content">




