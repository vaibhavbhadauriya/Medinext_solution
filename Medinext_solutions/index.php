<?php
/**
 * MEDINEXT SOLUTIONS - Home Page
 * Premium Medical Billing Website
 */

$pageTitle = 'MEDINEXT SOLUTIONS | Expert RCM & Medical Billing Services';
$pageDescription = 'Transform your practice with MEDINEXT SOLUTIONS. We provide expert Revenue Cycle Management (RCM) and specialized healthcare billing services to maximize claim acceptance and optimize cash flow.';
$pageKeywords = 'revenue cycle management, RCM, healthcare billing, medical billing services, claim denial reduction, specialized medical coding, practice revenue optimization';

require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- HERO SECTION WITH VIDEO BACKGROUND -->
<!-- ============================================ -->
<section class="hero-section-video" id="hero">
    <!-- Video Background -->
    <div class="hero-video-wrapper">
        <video class="hero-bg-video" autoplay muted loop playsinline preload="metadata">
            <source src="<?php echo $baseUrl; ?>/assets/videos/hero.mp4" type="video/mp4">
        </video>
        <div class="hero-video-white-overlay"></div>
    </div>

    <!-- Content Grid (Anchored to Bottom of Hero) -->
    <div class="container hero-video-content-container">
        <div class="row align-items-end gy-4">
            <!-- Left Column: Typing Headline & Action Button -->
            <div class="col-lg-7 col-md-12">
                <h1 class="hero-v2-title">
                    <span id="hero-typewriter-target"></span>
                </h1>
                <noscript>
                    <h1 class="hero-v2-title">
                        We <span class="text-blue-highlight">bill</span>, you <span class="text-blue-highlight">heal</span><br>
                        <span class="hero-title-subline">that's the deal</span>
                    </h1>
                </noscript>
                <div class="hero-v2-cta-wrap hero-fade-in-element">
                    <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn-blue-split">
                        <span class="btn-blue-text">Get Started</span>
                        <span class="btn-blue-arrow"><i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Italicized Strategic Value Proposition -->
            <div class="col-lg-5 col-md-12">
                <div class="hero-v2-right-content hero-fade-in-element">
                    <p class="hero-v2-italic-desc">
                        We transform your healthcare revenue cycle into predictable, maximized cash flow. From 99% clean claim submissions to aggressive denial recovery, we accelerate your practice revenue every step of the way.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- EHR & PMS INTEGRATIONS SHOWCASE SECTION -->
<!-- ============================================ -->
<section class="section ehr-showcase-section" id="ehr-integrations">
    <div class="container">
        <!-- Section Header -->
        <div class="ehr-showcase-header" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-plugs-connected"></i>
                Seamless Integrations
            </span>
            <h2 class="section-title">
                We Work With <span class="gradient-text">Every Major EHR & PMS</span>
            </h2>
            <p class="section-subtitle">
                Our expert team integrates seamlessly with 80+ leading Electronic Health Records and Practice Management Systems across the healthcare industry.
            </p>

            <!-- Tech Toggle -->
            <div class="ehr-tech-toggle mt-4">
                <button class="btn btn-outline-primary active" id="btn-show-medical" onclick="document.getElementById('medical-ehr-wrap').style.display='block'; document.getElementById('dental-ehr-wrap').style.display='none'; this.classList.add('active'); document.getElementById('btn-show-dental').classList.remove('active'); if(window.animateEhrCounters) animateEhrCounters('medical-ehr-wrap');">Medical Systems</button>
                <button class="btn btn-outline-primary" id="btn-show-dental" onclick="document.getElementById('dental-ehr-wrap').style.display='block'; document.getElementById('medical-ehr-wrap').style.display='none'; this.classList.add('active'); document.getElementById('btn-show-medical').classList.remove('active'); if(window.animateEhrCounters) animateEhrCounters('dental-ehr-wrap');">Dental Systems</button>
            </div>
        </div>
    </div>

    <?php
    // Scan ehr_logos directory and categorize
    $logoDir = __DIR__ . '/assets/images/ehr_logos/';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $medicalLogos = [];
    $dentalLogos = [];

    if (is_dir($logoDir)) {
        $files = scandir($logoDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExtensions)) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                
                $logoData = [
                    'file' => $file,
                    'name' => ucwords(str_replace(['_', '-', '.'], ' ', $filename))
                ];

                if (preg_match('/^\d+$/', $filename)) {
                    $medicalLogos[] = $logoData;
                } else {
                    $dentalLogos[] = $logoData;
                }
            }
        }
    }
    shuffle($medicalLogos);
    shuffle($dentalLogos);
    ?>

    <!-- MEDICAL EHR WRAPPER -->
    <div id="medical-ehr-wrap">
        <div class="ehr-marquee-wrapper ehr-medical-section pt-2">
            <?php
            $rowSize = ceil(count($medicalLogos) / 3); // 3 rows for medical (55 logos)
            $rows = array_chunk($medicalLogos, $rowSize);

            foreach ($rows as $rowIndex => $rowLogos):
                if(empty($rowLogos)) continue;
                $direction = ($rowIndex % 2 === 0) ? 'left' : 'right';
                $speed = 30 + ($rowIndex * 5);
            ?>
            <div class="ehr-marquee-row" data-direction="<?php echo $direction; ?>" style="--marquee-duration: <?php echo $speed; ?>s;">
                <div class="ehr-marquee-track">
                    <?php
                    for ($repeat = 0; $repeat < 2; $repeat++):
                        foreach ($rowLogos as $logo):
                    ?>
                    <div class="ehr-logo-card">
                        <img src="<?php echo $baseUrl; ?>/assets/images/ehr_logos/<?php echo htmlspecialchars($logo['file']); ?>"
                             alt="<?php echo htmlspecialchars($logo['name']); ?> Medical EHR"
                             loading="lazy" title="Medical EHR">
                    </div>
                    <?php endforeach; endfor; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="container mt-4">
            <div class="ehr-stats-bar">
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-laptop"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="80" data-suffix="+">80+</span>
                        <span class="ehr-stat-title">EHR Systems</span>
                        <span class="ehr-stat-desc">Pre-integrated &amp; ready</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="100" data-suffix="%">100%</span>
                        <span class="ehr-stat-title">HIPAA Compliant</span>
                        <span class="ehr-stat-desc">Bank-grade data safety</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-patch-check"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="99.4" data-decimals="1" data-suffix="%">99.4%</span>
                        <span class="ehr-stat-title">Clean Claims</span>
                        <span class="ehr-stat-desc">First-pass accuracy rate</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="24" data-prefix="&lt; " data-suffix="h">&lt; 24h</span>
                        <span class="ehr-stat-title">Turnaround</span>
                        <span class="ehr-stat-desc">Fast charge entry &amp; audit</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number">0-Day</span>
                        <span class="ehr-stat-title">Software Migration</span>
                        <span class="ehr-stat-desc">We work in your software</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DENTAL EHR WRAPPER -->
    <div id="dental-ehr-wrap" style="display: none;">
        <div class="ehr-marquee-wrapper ehr-dental-section pt-2">
            <?php
            $rowSize = ceil(count($dentalLogos) / 2); // 2 rows for dental (34 logos)
            $rows = array_chunk($dentalLogos, $rowSize);

            foreach ($rows as $rowIndex => $rowLogos):
                if(empty($rowLogos)) continue;
                $direction = ($rowIndex % 2 === 0) ? 'left' : 'right';
                $speed = 30 + ($rowIndex * 5);
            ?>
            <div class="ehr-marquee-row" data-direction="<?php echo $direction; ?>" style="--marquee-duration: <?php echo $speed; ?>s;">
                <div class="ehr-marquee-track">
                    <?php
                    for ($repeat = 0; $repeat < 2; $repeat++):
                        foreach ($rowLogos as $logo):
                    ?>
                    <div class="ehr-logo-card">
                        <img src="<?php echo $baseUrl; ?>/assets/images/ehr_logos/<?php echo htmlspecialchars($logo['file']); ?>"
                             alt="<?php echo htmlspecialchars($logo['name']); ?> Dental PMS"
                             loading="lazy" title="<?php echo htmlspecialchars($logo['name']); ?>">
                    </div>
                    <?php endforeach; endfor; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="container mt-4">
            <div class="ehr-stats-bar">
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="ph ph-tooth"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="<?php echo count($dentalLogos); ?>" data-suffix="+"><?php echo count($dentalLogos); ?>+</span>
                        <span class="ehr-stat-title">Dental PMS</span>
                        <span class="ehr-stat-desc">Dentrix, Eaglesoft &amp; more</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="100" data-suffix="%">100%</span>
                        <span class="ehr-stat-title">HIPAA Compliant</span>
                        <span class="ehr-stat-desc">Encrypted direct gateway</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-lightning-charge"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number">Same-Day</span>
                        <span class="ehr-stat-title">Insurance Verification</span>
                        <span class="ehr-stat-desc">Real-time benefit breakdown</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-graph-down-arrow"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="25" data-prefix="&lt; " data-suffix=" Days">&lt; 25 Days</span>
                        <span class="ehr-stat-title">A/R Aging</span>
                        <span class="ehr-stat-desc">Accelerated collections</span>
                    </div>
                </div>
                <div class="ehr-stat-divider"></div>
                <div class="ehr-stat-col">
                    <div class="ehr-stat-icon"><i class="bi bi-layers"></i></div>
                    <div class="ehr-stat-content">
                        <span class="ehr-stat-number" data-countup="100" data-suffix="%">100%</span>
                        <span class="ehr-stat-title">Cross-Coding</span>
                        <span class="ehr-stat-desc">Medical + Dental billing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- SERVICES SECTION -->
<!-- ============================================ -->
<section class="section services-section" id="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <p class="svc-eyebrow">What We Do</p>
            <h2 class="svc-heading">Revenue cycle management<br>built for modern healthcare</h2>
        </div>

        <!-- Flagship Services (2 large cards) -->
        <div class="row g-4 mb-4" data-aos="fade-up">
            <div class="col-lg-6">
                <a href="<?php echo $baseUrl; ?>/revenue-cycle-management/" class="svc-feature-card">
                    <div class="svc-feature-img">
                        <img src="<?php echo $baseUrl; ?>/assets/images/content/revenue-cycle-management.webp" alt="Revenue Cycle Management" loading="lazy">
                    </div>
                    <div class="svc-feature-content">
                        <h3>Revenue Cycle Management</h3>
                        <p>End-to-end claims management from eligibility verification through remittance posting and A/R follow-up. We handle the entire billing lifecycle so your team can focus on patient care.</p>
                        <span class="svc-feature-link">Learn more <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a href="<?php echo $baseUrl; ?>/denial-management-services/" class="svc-feature-card">
                    <div class="svc-feature-img">
                        <img src="<?php echo $baseUrl; ?>/assets/images/content/denial-management.png" alt="Denial Management" loading="lazy">
                    </div>
                    <div class="svc-feature-content">
                        <h3>Denial Management</h3>
                        <p>Root-cause analysis, rapid appeal submission, and persistent payer follow-up to recover revenue from denied and underpaid claims before they hit your bottom line.</p>
                        <span class="svc-feature-link">Learn more <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Compact Services (4 smaller cards) -->
        <div class="row g-4" data-aos="fade-up">
            <div class="col-lg-3 col-md-6">
                <a href="<?php echo $baseUrl; ?>/prior-authorization-services/" class="svc-compact-card">
                    <div class="svc-compact-icon"><i class="bi bi-clipboard2-check"></i></div>
                    <h4>Prior Authorization</h4>
                    <p>Automated ePA workflows that eliminate treatment delays and CO-197 denials.</p>
                    <span class="svc-compact-link">Learn more <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?php echo $baseUrl; ?>/therapy-billing-services/" class="svc-compact-card">
                    <div class="svc-compact-icon"><i class="bi bi-activity"></i></div>
                    <h4>Therapy Billing</h4>
                    <p>PT, OT, and Speech billing with Medicare 8-minute rule compliance built in.</p>
                    <span class="svc-compact-link">Learn more <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?php echo $baseUrl; ?>/dental-billing-services/" class="svc-compact-card">
                    <div class="svc-compact-icon"><i class="bi bi-file-earmark-medical"></i></div>
                    <h4>Dental Billing</h4>
                    <p>Claims processing, insurance verification, and aging recovery for practices and DSOs.</p>
                    <span class="svc-compact-link">Learn more <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="<?php echo $baseUrl; ?>/medical-coding-services/" class="svc-compact-card">
                    <div class="svc-compact-icon"><i class="bi bi-code-slash"></i></div>
                    <h4>Medical Coding</h4>
                    <p>AAPC-certified coders delivering accurate ICD-10, CPT, and HCPCS coding with audit-proof documentation.</p>
                    <span class="svc-compact-link">Learn more <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
        </div>

        <!-- Simple text CTA -->
        <div class="svc-footer-line" data-aos="fade-up">
            <p>We serve <strong>27+ medical and dental specialties</strong> across the United States. <a href="<?php echo $baseUrl; ?>/services/">View all services <i class="bi bi-arrow-right"></i></a></p>
        </div>
    </div>
</section>




<!-- ============================================ -->
<!-- NATIONWIDE US COVERAGE MAP SECTION -->
<!-- ============================================ -->
<section class="section nationwide-map-section" id="nationwide-coverage">
    <div class="container">
        <!-- Section Header (Cleaned & Centered) -->
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">
                Delivering RCM Excellence in <span class="gradient-text">All 50 States</span>
            </h2>
            <p class="section-subtitle">
                From solo private practices to large multi-specialty health systems, MEDINEXT SOLUTIONS provides state-compliant medical coding, local payer credentialing, and 100% HIPAA-compliant billing coast to coast.
            </p>
        </div>

        <!-- Centered Interactive Map Container -->
        <div class="row justify-content-center mt-3">
            <div class="col-xl-10 col-lg-11 col-12" data-aos="zoom-in" data-aos-delay="100">
                <div class="us-map-free-wrapper position-relative">
                    <!-- Map Top Legend -->
                    <div class="us-map-top-bar justify-content-center">
                        <div class="map-legend-item">
                            <span class="legend-dot hq-dot"></span>
                            <span>National HQ (Orlando, FL)</span>
                        </div>
                        <div class="map-legend-item">
                            <span class="legend-dot active-dot"></span>
                            <span>Active RCM &amp; Billing Coverage</span>
                        </div>
                        <div class="map-legend-item d-none d-sm-flex">
                            <span class="legend-dot hub-dot"></span>
                            <span>Regional Hubs</span>
                        </div>
                    </div>

                    <!-- SVG Map with Floating Dynamic Mega-Menu Style Popup -->
                    <div class="us-svg-responsive-container position-relative">
                        <!-- Floating State Hover Tooltip (Mega-Menu Style Animation) -->
                        <div class="us-state-hover-popup" id="us-state-hover-popup" aria-hidden="true">
                            <div class="us-popup-content">
                                <div class="us-popup-state-name" id="popup-state-name">New York</div>
                                <div class="us-popup-providers" id="popup-providers">
                                    <i class="bi bi-hospital"></i>
                                    <span id="popup-providers-text">58+ Active Practices</span>
                                </div>
                            </div>
                            <div class="us-popup-arrow"></div>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 959 593" class="us-nation-svg" id="us-interactive-map" aria-label="Interactive Map of United States Nationwide Medical Billing Coverage">
  <defs>
    <!-- Filter for state hover glow -->
    <filter id="state-glow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="3" result="blur" />
      <feComposite in="SourceGraphic" in2="blur" operator="over" />
    </filter>
    <linearGradient id="hq-pulse-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#38bdf8" />
      <stop offset="100%" stop-color="#0284c7" />
    </linearGradient>
  </defs>
  
  <!-- State Boundaries & Shapes -->
  <g class="us-states-group">
    <path id="state-al" class="us-state-path" data-code="AL" data-name="Alabama" data-region="Southeast" data-providers="18+" d="m 643,467.4 .4,-7.3 -.9,-1.2 -1.7,-.7 -2.5,-2.8 .5,-2.9 48.8,-5.1 -.7,-2.2 -1.5,-1.5 -.5,-1.4 .6,-6.3 -2.4,-5.7 .5,-2.6 .3,-3.7 2.2,-3.8 -.2,-1.1 -1.7,-1 v -3.2 l -1.8,-1.9 -2.9,-6.1 -12.9,-45.8 -45.7,4 1.3,2 -1.3,67 4.4,33.2 .9,-.5 1.3,.1 .6,.4 .8,-.1 2,-3.8 v -2.3 l 1.1,-1.1 1.4,.5 3.4,6.4 v .9 l -3.3,2.2 3.5,-.4 4.9,-1.6 z"><title>Alabama (AL)</title></path><path id="state-ak" class="us-state-path" data-code="AK" data-name="Alaska" data-region="Pacific" data-providers="6+" d="m 15.8,572 h 2.4 l .7,.7 -1,1.2 -1.9,.2 -2.5,1.3 -3.7,-.1 2.2,-.9 .3,-1.1 2.5,-.3 z m 8.3,-1.7 1.3,.5 h .9 l .5,1.2 .3,-.6 .9,.2 1.1,1.5 0,.5 -4.2,1.9 -2.4,-.1 -1,-.5 -1.1,.7 -2,0 -1.1,-1.4 4.7,-.5 z m 5.4,-.1 1,.1 .7,.7 v 1 l -1.3,.1 -.9,-1.1 z m 2.5,.3 1.3,-.1 -.1,1 -1.1,.6 z m .3,2.2 3.4,-.1 .2,1.1 -1.3,.1 -.3,-.5 -.8,.6 -.4,-.6 -.9,-.2 z m 166.3,7.6 2.1,.1 -1,1.9 -1.1,-.1 -.4,-.8 .5,-1.3 m -1.1,-2.9 .6,-1.3 -.2,-2.3 2.4,-.5 4.5,4.4 1.3,3.4 1.9,1.6 .3,5.1 -1.4,0 -1.3,-2.3 -3.1,-2.4 h -.6 l 1.1,2.8 1.7,.2 .2,2.1 -.9,.1 -4.1,-4.4 -.1,-.9 1.9,-1 0,-1 -.5,-.8 -1.6,-.6 -1.7,-1.3 1.4,.1 .5,-.4 -.6,-.9 -.6,.5 z m -3.6,-9.1 1.3,.1 2.4,2.5 -.2,.8 -.8,-.1 -.1,1.8 .5,.5 0,1.5 -.8,.3 -.4,1.2 -.8,-.4 -.4,-2.2 1.1,-1.4 -2.1,-2.2 .1,-1.2 z m 1.5,-1.5 1.9,.2 2.5,.1 3.4,3.2 -.2,.5 -1.1,.6 -1.1,-.2 -.1,-.7 -1.2,-1.6 -.3,.7 1,1.3 -.2,1.2 -.8,-.1 -1.3,.2 -.1,-1.7 -2.6,-2.8 z m -12.7,-8.9 .9,-.4 h 1.6 l .7,-.5 4.1,2.2 .1,1.5 -.5,.5 h -.8 l -1.4,-.7 1.1,1.3 1.8,0 .5,2 -.9,0 -2.2,-1.5 -1.1,-.2 .6,1.3 .1,.9 .8,-.6 1.7,1.2 1.3,-.1 -.2,.8 1.9,4.3 0,3.4 .4,2.1 -.8,.3 -1.2,-2 -.5,-1.5 -1.6,-1.6 -.2,-2.7 -.6,-1.7 h -.7 l .3,1.1 0,.5 -1.4,1 .1,-3.3 -1.6,-1.6 -1.3,-2.3 -1.2,-1.2 z m 7.2,-2.3 1.1,1.8 2.4,-.1 1,2.1 -.6,.6 2,3.2 v 1.3 l -1.2,.8 v .7 l -2,1.9 -.5,-1.4 -.1,-1.3 .6,-.7 v -1.1 l -1.5,-1.9 -.5,-3.7 -.9,-1.5 z m -56.7,-18.3 -4,4.1 v 1.6 l 2.1,-.8 .8,-1.9 2.2,-2.4 z m -31.6,16.6 0,.6 1.8,1.2 .2,-1.4 .6,.9 3.5,.1 .7,-.6 .2,-1.8 -.5,-.7 -1.4,0 0,-.8 .4,-.6 v -.4 l -1.5,-.3 -3.3,3.6 z m -8.1,6.2 1.5,5.8 h 2.1 l 2.4,-2.5 .3,1.2 6.3,-4 .7,-1 -1,-1.1 v -.7 l .5,-1.3 -.9,-.1 -2,1 0,-1.2 -2.7,-.6 -2.4,.3 -.2,3.4 -.8,-2 -1.5,-.1 -1,.6 z m -2.2,8.2 .1,-.7 2.1,-1.3 .6,.3 1.3,.2 1.3,1.2 -2.2,-.2 -.4,-.6 -1,.6 z m -5.2,3.3 -1.1,.8 1.5,1.4 .8,-.7 -.1,-1.3 z m -6.3,-7.9 1.4,.1 .4,.6 -1.8,.1 z m -13.9,11.9 v .5 l .7,.1 -.1,-.6 z m -.4,-3.2 -1,1 v .5 l .7,1.1 1,-1 -.7,-.1 z m -2,-.8 -.3,1 -1.3,.1 -.4,.2 0,1.3 -.5,.9 .6,0 .7,-.9 .8,-.1 .9,-1 .2,-1.3 z m -4.4,-2 -.2,1.8 1.4,.8 1.2,-.6 0,-1 1.7,-.3 -.1,-.6 -.9,-.2 -.7,.6 -.9,-.5 z m -4.9,-.1 1,.7 -.3,1.2 -1.4,-1.1 z m -4.2,1.3 1.4,.1 -.7,.8 z m -3.5,3 1.8,1.1 -1.7,.1 z m -25.4,-31.2 1.2,.6 -.8,.6 z m -.7,-6.3 .4,1.2 .8,-1.2 z m 24.3,-19.3 1.5,-.1 .9,.4 1.1,-.5 1.3,-.1 1.6,.8 .8,1.9 -.1,.9 -1.2,2 -2.4,-.2 -2.1,-1.8 -1,-.4 -1.1,-2 z m -21.1,-14.4 .1,1.9 2,2 v .5 l -.8,-.2 -1.7,-.8 -.3,-1.1 -.3,-1.6 z m 18.3,-23.3 v 1.2 l 1.9,1.8 h 2.3 l .6,1.1 v 1.6 l 2.1,1.9 1.8,1.2 -.1,.7 -.7,1.1 -1.4,-1.2 -2.1,.1 -.8,-.8 -.9,-2.1 -1.5,-2.2 -2.6,-.1 -1,-.7 1,-2.1 z m 16.8,-4.5 1,0 .1,1.1 h -1 z m 16.2,19.7 .9,.1 0,1.2 -1.7,-.5 z m 127.8,77.7 -1.2,.4 -.1,1.1 h 1.2 z m -157.6,-4.5 -1.3,-.4 -4.1,.6 -2.8,1.4 -.1,1.9 1.9,.7 1.5,-.9 1.7,-.1 4.7,1.4 .1,-1.3 -1.6,-1.1 z m 2.1,2.3 -.4,-1.4 1.2,.2 .1,1.4 1.8,0 .4,-2.5 .3,2.4 2.5,-.1 3.2,-3.3 .8,.1 -.7,1.3 1.4,.9 4.2,-.2 2.6,-1.2 1.4,-.1 .3,1.5 .6,-.5 .4,-1.4 5.9,.2 1.9,-1.6 -1.3,-1.1 .6,-1.2 2.6,.2 -.2,-1.2 2.5,.2 .7,-1.1 1.1,.2 4.6,-1.9 .2,-1.7 5.6,-2.4 2,-1.9 1.2,-.6 1.3,.8 2.3,-.9 1.1,-1.9 .5,-1.3 1.7,-.9 1.5,-.7 .4,-1.4 -1.1,-1.7 -2.2,-.2 -.2,-1.3 .8,-1.6 1.4,-.2 1.3,-1.5 1.9,-.1 3.4,-3.2 .4,-1.4 1.5,-2.3 3.8,-4.1 2.5,-.9 1.9,-.9 2.1,.8 1.4,2.6 -1.5,0 -1.4,-1.5 -3,2 -1.7,.1 -.2,3.1 -3.1,4.9 .6,2 2.3,0 -.6,1 -1.4,.1 -2.4,1.8 0,.9 1.9,1 3.4,-.6 1.4,-1.7 1.4,.1 3,-1.7 .5,-2.3 1.6,-.1 6.3,.8 1,-1.1 1,-4.5 -1.6,1.1 .6,-2.2 -1.6,-1.4 .8,-1.5 .1,1.5 3.4,0 .7,-1 1.6,-.1 -.3,1.7 1.9,.1 -1.9,1.3 4.1,1.1 -3.5,.4 -1.3,1.2 .9,1.4 4.6,-1.7 2.3,1.7 .7,-.9 .6,1.4 4,2.3 h 2.9 l 3.9,-.5 4.3,1.1 2,1.9 4.5,.4 1.8,-1.5 .8,2.4 -1.8,.7 1.2,1.2 7.4,3.8 1.4,2.5 5.4,4.1 3.3,-2 -.6,-2.2 -3.5,-2 3.1,1.2 .5,-.7 .9,1.3 0,2.7 2.1,-.6 2.1,1.8 -2.5,-9.8 1.2,1.3 1.4,6 2.2,2.5 2.4,-.4 1.8,3.5 h .9 l .6,5.6 3.4,.5 1.6,2.2 1.8,1.1 .4,2.8 -1.8,2.6 2.9,1.6 1.2,-2.4 -.2,3.1 -.8,.9 1.4,1.7 .7,-2.4 -.2,-1.2 .8,.2 .6,2.3 -1,1.4 .6,2.6 .5,.4 .3,-1.6 .7,.6 -.3,2 1.2,.2 -.4,.9 1.7,-.1 0,-1 h -1 l .1,-1.7 -.8,-.6 1.7,-.3 .5,-.8 0,-1.6 .5,1.3 -.6,1.8 1.2,3.9 1.8,.1 2.2,-4.2 .1,-1.9 -1.3,-4 -.1,-1.2 .5,-1.2 -.7,-.7 -1.7,.1 -2.5,-2 -1.7,0 -2,-1.4 -1.5,0 -.5,-1.6 -1.4,-.3 -.2,-1.5 -1,-.5 .1,-1.7 -5.1,-7.4 -1.8,-1.5 v -1.2 l -4.3,-3.5 -.7,-1.1 -1.6,-2 -1.9,-.6 0,-2.2 -1.2,-1.3 -1.7,-.7 -2.1,1.3 -1.6,2.1 -.4,2.4 -1.5,.1 -2.5,2.7 -.8,-.3 v -2.5 l -2.4,-2.2 -2.3,-2 -.5,-2 -2.5,-1.3 .2,-2.2 -2.8,-.1 -.7,1.1 -1.2,0 -.7,-.7 -1.2,.8 -1.8,-1.2 0,-85.8 -6.9,-4.1 -1.8,-.5 -2.2,1.1 -2.2,.1 -2.3,-1.6 -4.3,-.6 -5.8,-3.6 -5.7,-.4 -2,.5 -.2,-1.8 -1.8,-.7 1.1,-1 -.2,-.9 -3.2,-1.1 h -2.4 l -.4,.4 -.9,-.6 .1,-2.6 -.8,-.9 -2.5,2.9 -.8,-.1 v -.8 l 1.7,-.8 v -.8 l -1.9,-2.4 -1.1,-.1 -4.5,3.1 h -3.9 l .4,-.9 -1.8,-.1 -5.2,3.4 -1.8,0 -.6,-.8 -2.7,1.5 -3.6,3.7 -2.8,2.7 -1.5,1.2 -2.6,.1 -2.2,-.4 -2.3,-1.3 v 0 l -2.8,3.9 -.1,2.4 2.6,2.4 2.1,4.5 .2,5.3 2.9,2 3.4,.4 .7,.8 -1.5,2.3 .7,2.7 -1.7,-2.6 v -2.4 l -1.5,-.3 .1,1.2 .7,2.1 2.9,3.7 h -1.4 l -2.2,1.1 -6.2,-2.5 -.1,-2 1.4,-1.3 0,-1.4 -2.1,-.5 -2.3,.2 -4.8,.2 1.5,2.3 -1.9,-1.8 -8.4,1.2 -.8,1.5 4.9,4.7 -.8,1.4 -.3,2 -.7,.8 -.1,1.9 4.4,3.6 4.1,.2 4.6,1.9 h 2 l .8,-.6 3.8,.1 .1,-.8 1.2,1.1 .1,2 -2.5,-.1 .1,3.3 .5,3.2 -2.9,2.7 -1.9,-.1 -2,-.8 -1,.1 -3.1,2.1 -1.7,.2 -1.4,-2.8 -3.1,0 -2.2,2 -.5,1.8 -3.3,1.8 -5.3,4.3 -.3,3.1 .7,2.2 1,1.2 1,-.4 .9,1 -.8,.6 -1.5,.9 1.1,1.5 -2.6,1.1 .8,2.2 1.7,2.3 .8,4.1 4,1.5 2.6,-.8 1.7,-1.1 .5,2.1 .3,4.4 -1.9,1.4 0,4.4 -.6,.9 h -1.7 l 1.7,1.2 2.1,-.1 .4,-1 4.6,-.6 2,2.6 1.3,-.7 1.3,5.1 1,.5 1,-.7 .1,-2.4 .9,-1 .7,1.1 .2,1.6 1.6,.4 4.7,-1.2 .2,1.2 -2,1.1 -1.6,1.7 -2.8,7 -4.3,2 -1.4,1.5 -.3,1.4 -1,-.6 -9.3,3.3 -1.8,4.1 -1.3,-.4 .5,-1.1 -1.5,-1.4 -3.5,-.2 -5.3,3.2 -2.2,1.3 -2.3,0 -.5,2.4 z"><title>Alaska (AK)</title></path><path id="state-az" class="us-state-path" data-code="AZ" data-name="Arizona" data-region="Southwest" data-providers="24+" d="m 139.6,387.6 3,-2.2 .8,-2.4 -1,-1.6 -1.8,-.2 -1.1,-1.6 1.1,-6.9 1.6,-.3 2.4,-3.2 1.6,-7 2.4,-3.6 4.8,-1.7 1.3,-1.3 -.4,-1.9 -2.3,-2.5 -1.2,-5.8 -1.4,-1.8 -1.3,-3.4 .9,-2.1 1.4,-3 .5,-2.9 -.5,-4.9 1,-13.6 3.5,-.6 3.7,1.4 1.2,2.7 h 2 l 2.4,-2.9 3.4,-17.5 46.2,8.2 40,6 -17.4,124.1 -37.3,-5.4 -64.2,-37.5 .5,-2.9 2,-1.8 z"><title>Arizona (AZ)</title></path><path id="state-ar" class="us-state-path" data-code="AR" data-name="Arkansas" data-region="Southeast" data-providers="12+" d="m 584.2,367 .9,-2.2 1.2,.5 .7,-1 -.8,-.7 .3,-1.5 -1.1,-.9 .6,-1 -.1,-1.5 -1.1,-.1 .8,-.8 1.3,.8 .3,-1.4 -.4,-1.1 .1,-.7 2,.6 -.4,-1.5 1.6,-1.3 -.5,-.9 -1.1,.1 -.6,-.9 .9,-.9 1.6,-.2 .5,-.8 1.4,-.2 -.1,-.8 -.9,-.9 v -.5 h 1.5 l .4,-.7 -1.4,-1 -.1,-.6 -11.2,.8 2.8,-5.1 1.7,-1.5 v -2.2 l -1.6,-2.5 -39.8,2 -39.1,.7 4.1,24.4 -.7,39 2.6,2.3 2.8,-1.3 3.2,.8 .2,11.9 52.3,-1.3 1.2,-1.5 .5,-3 -1.5,-2.3 -.5,-2.2 .9,-.7 v -.8 l -1.7,-1.1 -.1,-.7 1.6,-.9 -1.2,-1.1 1.7,-7.1 3.4,-1.6 v -.8 l -1.1,-1.4 2.9,-5.4 h 1.9 l 1.5,-1.2 -.3,-5.2 3.1,-4.5 1.8,-.6 -.5,-3.1 z"><title>Arkansas (AR)</title></path><path id="state-ca" class="us-state-path" data-code="CA" data-name="California" data-region="West Coast" data-providers="65+" d="m 69.4,365.6 3.4,5.2 -1.4,.1 -1.8,-1.9 z m 1.9,-9.8 1.8,4.1 2.6,1 .7,-.6 -1.3,-2.5 -2.6,-2.4 z m -19.9,-19 v 2.4 l 2,1.2 4.4,-.2 1,-1 -3.1,-.2 z m -5.9,.1 3.3,.5 1.4,2.2 h -3.8 z m 47.9,45.5 -1,-3 .2,-3 -.4,-7.9 -1.8,-4.8 -1.2,-1.4 -.6,-1.5 -7,-8.6 -3.6,.1 -2,-1.9 1.1,-1.8 -.7,-3.7 -2.2,-1.2 -3.9,-.6 -2.8,-1.3 -1.5,-1.9 -4.5,-6.6 -2.7,-2.2 -3.7,-.5 -3.1,-2.3 -4.7,-1.5 -2.8,-.3 -2.5,-2.5 .2,-2.8 .8,-4.8 1.8,-5.1 -1.4,-1.6 -4,-9.4 -2.7,-3.7 -.4,-3 -1.6,-2.3 .2,-2.5 -2,-5 -2.9,-2.7 .6,-7.1 2.4,-.8 1.8,-3.1 -.4,-3.2 -1,-.9 h -2.5 l -2.5,-3.3 -1.5,-3.5 v -7.5 l 1.2,-4.2 .2,-2.1 2.5,.2 -.1,1.6 -.8,.7 v 2.5 l 3.7,3.2 v -4.7 l -1.4,-3.4 .5,-1.1 -1,-1.7 2.8,-1.5 -1.9,-3 -1.4,.5 -1.5,3.8 .5,1.3 -.8,1 -.9,-.1 -5.4,-6.1 .7,-5.6 -1.1,-3.9 -6.5,-12.8 .8,-10.7 2.3,-3.6 .2,-6.4 -5.5,-11.1 .3,-5.2 6.9,-7.5 1.7,-2.4 -.1,-1.4 4,-9.2 .1,-8.4 .9,-2.5 66.1,18.6 -16.4,63.1 1.1,3.5 70.4,105 -.9,2.1 1.3,3.4 1.4,1.8 1.2,5.8 2.3,2.5 .4,1.9 -1.3,1.3 -4.8,1.7 -2.4,3.6 -1.6,7 -2.4,3.2 -1.6,.3 -1.1,6.9 1.1,1.6 1.8,.2 1,1.6 -.8,2.4 -3,2.2 -2.2,-.1 z"><title>California (CA)</title></path><path id="state-co" class="us-state-path" data-code="CO" data-name="Colorado" data-region="Mountain" data-providers="20+" d="m 374.6,323.3 -16.5,-1 -51.7,-4.8 -52.6,-6.5 11.5,-88.3 44.9,5.7 37.5,3.4 33.1,2.4 -1.4,22.1 z"><title>Colorado (CO)</title></path><path id="state-ct" class="us-state-path" data-code="CT" data-name="Connecticut" data-region="Northeast" data-providers="15+" d="m 873.5,178.9 .4,-1.1 -3.2,-12.3 -.1,-.3 -14.9,3.4 v .7 l -.9,.3 -.5,-.7 -10.5,2.4 2.8,16.3 1.8,1.5 -3.5,3.4 1.7,2.2 5.4,-4.5 1.7,-1.3 h .8 l 2.4,-3.1 1.4,.1 2.9,-1.1 h 2.1 l 5.3,-2.7 2.8,-.9 1,-1 1.5,.5 z"><title>Connecticut (CT)</title></path><path id="state-de" class="us-state-path" data-code="DE" data-name="Delaware" data-region="Mid-Atlantic" data-providers="8+" d="m 822.2,226.6 -1.6,.3 -1.5,1.1 -1.2,2.1 7.6,27.1 10.9,-2.3 -2.2,-7.6 -1.1,.5 -3.3,-2.6 -.5,-1.7 -1.8,-1 -.2,-3.7 -2.1,-2.2 -1.1,-.8 -1.2,-1.1 -.4,-3.2 .3,-2.1 1,-2.2 z"><title>Delaware (DE)</title></path><path id="state-fl" class="us-state-path" data-code="FL" data-name="Florida" data-region="Southeast Hub" data-providers="52+" d="m 751.7,445.1 -4,-.7 -1.7,-.9 -2.2,1.4 v 2.5 l 1.4,2.1 -.5,4.3 -2.1,.6 -1,-1.1 -.6,-3.2 -50.1,3.3 -3.3,-6 -48.8,5.1 -.5,2.9 2.5,2.8 1.7,.7 .9,1.2 -.4,7.3 -1.1,.6 .5,.4 1,-.3 .7,-.8 10.5,-2.7 9.2,-.5 8.1,1.9 8.5,5 2.4,.8 2.2,2 -.1,2.7 h 2.4 l 1.9,-1 2.5,.1 2,-.8 2.9,-2 3.1,-2.9 1.1,-.4 .6,.5 h 1.4 l .5,-.8 -.5,-1.2 -.6,-.6 .2,-.8 2,-1.1 5,-.4 .8,1 1,.1 2.3,1 3,1.8 1.2,1.7 1.1,1.2 2.8,1.4 v 2.4 l 2.8,1.9 1,.1 1.6,1.4 .7,1.6 1,.2 .8,2.1 .7,.6 1,-1.1 2.9,.1 .5,1.4 1.1,.9 v 1.3 l 2.9,2.2 .2,9.6 -1.8,5.8 1,1.2 -.2,3.4 -.8,1.4 .7,1.2 2.3,2.3 .3,1.5 .8,1 -.4,-1.9 1.3,-.6 .8,-3.6 -3,-1.2 .1,-.6 2.6,-.4 .9,2.6 1.1,.6 .1,-2 1.1,.3 .6,.8 -.1,.7 -2.9,4.2 -.2,1.1 -1.7,1.9 v 1.1 l 3.7,3.8 5.3,7.9 1.8,2.1 v 1.8 l 2.8,4.6 2.3,.6 .7,-1.2 -2.1,.3 -3,-4.5 .2,-1.4 1.5,-.8 v -1.5 l -.6,-1.3 .9,-.9 .4,.9 .7,.5 v 4 l -1.2,-.6 -.8,.9 1.4,1.6 1,2.6 1.2,-.6 2.3,1.2 2.1,2.2 1.6,5.1 3.1,4.8 .8,-1.3 2.8,-.5 3.2,1.3 .3,1.7 3.3,3.8 .1,1.1 2.2,2.7 -.7,.5 v 2.7 l 2.7,1.4 h 1.5 l 2.7,-1.8 1.5,.3 1.1,.4 2.3,-1.7 .2,-.7 1.2,.3 2.4,-1.7 1.3,-2.3 -.7,-3.2 -.2,-1.3 1.1,-4 .6,-.2 .6,1.6 .8,-1.8 -.8,-7.2 -.4,-10.5 -1,-6.8 -.7,-1.7 -6.6,-11.1 -5.2,-9.1 -2.2,-3.3 -1.3,-3.6 -.2,-3.4 .9,-.3 v -.9 l -1.1,-2.2 -4,-4 -7.6,-9.7 -5.7,-10.4 -4.3,-10.7 -.6,-3.7 -1.2,-1 -.5,-3.8 z m 9.2,134.5 1.7,-.1 -.7,-1 z m 7.3,-1.1 v -.7 l 1.6,-.2 3.7,-3.3 1.5,-.6 2.4,-.9 .3,1.3 1.7,.8 -2.6,1.2 h -2.4 l -3.9,2.5 z m 17.2,-7.6 -3,1.4 -1,1.3 1.1,.1 z m 3.8,-2.9 -1.1,.3 -1.4,2 1.1,-.2 1.5,-1.6 z m 8.3,-15.7 -1.7,5.6 -.8,1 -1,2.6 -1.2,1.6 -.7,1.7 -1.9,2.2 v .9 l 2.7,-2.8 2.4,-3.5 .6,-2 2.1,-4.9 z"><title>Florida (FL)</title></path><path id="state-ga" class="us-state-path" data-code="GA" data-name="Georgia" data-region="Southeast" data-providers="30+" d="m 761.8,414.1 v 1.4 l -4.2,6.2 -1.2,.2 1.5,.5 v 2 l -.9,1.1 -.6,6 -2.3,6.2 .5,2 .7,5.1 -3.6,.3 -4,-.7 -1.7,-.9 -2.2,1.4 v 2.5 l 1.4,2.1 -.5,4.3 -2.1,.6 -1,-1.1 -.6,-3.2 -50.1,3.3 -3.3,-6 -.7,-2.2 -1.5,-1.5 -.5,-1.4 .6,-6.3 -2.4,-5.7 .5,-2.6 .3,-3.7 2.2,-3.8 -.2,-1.1 -1.7,-1 v -3.2 l -1.8,-1.9 -2.9,-6.1 -12.9,-45.8 22.9,-2.9 21.4,-3 -.1,1.9 -1.9,1 -1.4,3.2 .2,1.3 6.1,3.8 2.6,-.3 3.1,4 .4,1.7 4.2,5.1 2.6,1.7 1.4,.2 2.2,1.6 1.1,2.2 2,1.6 1.8,.5 2.7,2.7 .1,1.4 2.6,2.8 5,2.3 3.6,6.7 .3,2.7 3.9,2.1 2.5,4.8 .8,3.1 4.2,.4 z"><title>Georgia (GA)</title></path><path id="state-hi" class="us-state-path" data-code="HI" data-name="Hawaii" data-region="Pacific" data-providers="7+" d="m 317,553.7 -.2,3.2 1.7,1.9 .1,1.2 -4.8,4.5 -.1,1.2 1.9,3.2 1.7,4.2 v 2.6 l -.5,1.2 .1,3.4 4.1,2.1 1.1,1.1 1.2,-1.1 2.1,-3.6 4.5,-2.9 3.3,-.5 2.5,-1 1.7,-1.2 3.2,-3.5 -2.8,-1.1 -1.4,-1.4 .1,-1.7 -.5,-.6 h -2 l .2,-2.5 -.7,-1.2 -2.6,-2.3 -4.5,-1.9 -2.8,-.2 -3.3,-2.7 -1.2,-.6 z m -15.3,-17 -1.1,1.5 -.1,1.7 2.7,2.4 1.9,.5 .6,1 .4,3 3.6,.2 5.3,-2.6 -.1,-2.5 -1.4,-.5 -3.5,-2.6 -1.8,-.3 -2.9,1.3 -1.5,-2.7 z m -1.5,11.5 .9,-1.4 2.5,-.3 .6,1.8 z m -7,-8.7 1.7,4 3.1,-.6 .3,-2 -1.4,-1.5 z m -4.1,-6.7 -1.1,2.4 h 5 l 4.8,1.6 2.5,-1.6 .2,-1.5 -4.8,.2 z m -16,-10.6 -1.9,2.1 -2.9,.6 .8,2.2 2.2,2.8 .1,1 2.1,-.3 2.3,.1 1.7,1.2 3.5,-.8 v -.7 l -1,-.8 -.5,-2.1 -.8,-.3 -.5,1 -1.2,-1.3 .2,-1.4 -1.8,-3.3 -1.1,-.7 z m -31.8,-12.4 -4.2,2.9 .2,2.3 2.4,1.2 1.9,1.3 2.7,.4 2.6,-2.2 -.2,-1.9 .8,-1.7 v -1.4 l -1,-.9 z m -10.8,4.8 -.3,1.2 -1.9,.9 -.6,1.8 1,.8 1.1,-1.5 1.9,-.6 .4,-2.6 z"><title>Hawaii (HI)</title></path><path id="state-id" class="us-state-path" data-code="ID" data-name="Idaho" data-region="Northwest" data-providers="9+" d="m 165.3,183.1 -24.4,-5.4 8.5,-37.3 2.9,-5.8 .4,-2.1 .8,-.9 -.9,-2 -2.9,-1.2 .2,-4.2 4,-5.8 2.5,-.8 1.6,-2.3 -.1,-1.6 1.8,-1.6 3.2,-5.5 4.2,-4.8 -.5,-3.2 -3.5,-3.1 -1.6,-3.6 1.1,-4.3 -.7,-4 12.7,-56.1 14.2,3 -4.8,22 3.7,7.4 -1.6,4.8 3.6,4.8 1.9,.7 3.9,8.3 v 2.1 l 2.3,3 h .9 l 1.4,2.1 h 3.2 v 1.6 l -7.1,17 -.5,4.1 1.4,.5 1.6,2.6 2.8,-1.4 3.6,-2.4 1.9,1.9 .5,2.5 -.5,3.2 2.5,9.7 2.6,3.5 2.3,1.4 .4,3 v 4.1 l 2.3,2.3 1.6,-2.3 6.9,1.6 2.1,-1.2 9,1.7 2.8,-3.3 1.8,-.6 1.2,1.8 1.6,4.1 .9,.1 -8.5,54.8 -47.9,-8.2 z"><title>Idaho (ID)</title></path><path id="state-il" class="us-state-path" data-code="IL" data-name="Illinois" data-region="Midwest Hub" data-providers="38+" d="m 623.5,265.9 -1,5.2 v 2 l 2.4,3.5 v .7 l -.3,.9 .9,1.9 -.3,2.4 -1.6,1.8 -1.3,4.2 -3.8,5.3 -.1,7 h -1 l .9,1.9 v .9 l -2.2,2.7 .1,1.1 1.5,2.2 -.1,.9 -3.7,.6 -.6,1.2 -1.2,-.6 -1,.5 -.4,3.3 1.7,1.8 -.4,2.4 -1.5,.3 -6.9,-3 -4,3.7 .3,1.8 h -2.8 l -1.4,-1.5 -1.8,-3.8 v -1.9 l .8,-.6 .1,-1.3 -1.7,-1.9 -.9,-2.5 -2.7,-4.1 -4.8,-1.3 -7.4,-7.1 -.4,-2.4 2.8,-7.6 -.4,-1.9 1.2,-1.1 v -1.3 l -2.8,-1.5 -3,-.7 -3.4,1.2 -1.3,-2.3 .6,-1.9 -.7,-2.4 -8.6,-8.4 -2.2,-1.5 -2.5,-5.9 -1.2,-5.4 1.4,-3.7 .7,-.7 .1,-2.3 -.7,-.9 1,-1.5 1.8,-.6 .9,-.3 1,-1.2 v -2.4 l 1.7,-2.4 .5,-.5 .1,-3.5 -.9,-1.4 -1,-.3 -1.1,-1.6 1,-4 3,-.8 h 2.4 l 4.2,-1.8 1.7,-2.2 .1,-2.4 1.1,-1.3 1.3,-3.2 -.1,-2.6 -2.8,-3.5 h -1.2 l -.9,-1.1 .2,-1.6 -1.7,-1.7 -2.5,-1.3 .5,-.6 45.9,-2.8 .1,4.6 3.4,4.6 1.2,4.1 1.6,3.2 z"><title>Illinois (IL)</title></path><path id="state-in" class="us-state-path" data-code="IN" data-name="Indiana" data-region="Midwest" data-providers="22+" d="m 629.2,214.8 -5.1,2.3 -4.7,-1.4 4.1,50.2 -1,5.2 v 2 l 2.4,3.5 v .7 l -.3,.9 .9,1.9 -.3,2.4 -1.6,1.8 -1.3,4.2 -3.8,5.3 -.1,7 h -1 l .9,1.9 1.1,.8 .6,-1 -.7,-1.7 4.6,-.5 .2,1.2 1.1,.2 .4,-.9 -.6,-1.3 .3,-.8 1.3,.8 1.7,-.4 1.7,.6 3.4,2.1 1.8,-2.8 3.5,-2.2 3,3.3 1.6,-2.1 .3,-2.7 3.8,-2.3 .2,1.3 1.9,1.2 3,-.2 1.2,-.7 .1,-3.4 2.5,-3.7 4.6,-4.4 -.1,-1.7 1.2,-3.8 2.2,1 6.7,-4.5 -.4,-1.7 -1.5,-2.1 1,-1.9 -6.6,-57.2 -.1,-1.4 -32.4,3.4 z"><title>Indiana (IN)</title></path><path id="state-ia" class="us-state-path" data-code="IA" data-name="Iowa" data-region="Midwest" data-providers="14+" d="m 556.9,183 2.1,1.6 .6,1.1 -1.6,3.3 -.1,2.5 2,5.5 2.7,1.5 3.3,.7 1.3,2.8 -.5,.6 2.5,1.3 1.7,1.7 -.2,1.6 .9,1.1 h 1.2 l 2.8,3.5 .1,2.6 -1.3,3.2 -1.1,1.3 -.1,2.4 -1.7,2.2 -4.2,1.8 h -2.4 l -3,.8 -1,4 1.1,1.6 1,.3 .9,1.4 -.1,3.5 -.5,.5 -1.7,2.4 v 2.4 l -1,1.2 -.9,.3 -1.8,.6 -1,1.5 .7,.9 -.1,2.3 -.7,.7 -1.5,-.8 -1.1,-1.1 -.6,-1.6 -1.7,-1.3 -14.3,.8 -27.2,1.2 -25.9,-.1 -1.8,-4.4 .7,-2.2 -.8,-3.3 .2,-2.9 -1.3,-.7 -.4,-6.1 -2.8,-5 -.2,-3.7 -2.2,-4.3 -1.3,-3.7 v -1.4 l -.6,-1.7 v -2.3 l -.5,-.9 -.7,-1.7 -.3,-1.3 -1.3,-1.2 1,-4.3 1.7,-5.1 -.7,-2 -1.3,-.4 -.4,-1.6 1,-.5 .1,-1.1 -1.3,-1.5 .1,-1.6 2.2,.1 h 28.2 l 36.3,-.9 18.6,-.7 z"><title>Iowa (IA)</title></path><path id="state-ks" class="us-state-path" data-code="KS" data-name="Kansas" data-region="Midwest" data-providers="11+" d="m 459.1,259.5 -43.7,-1.2 -36,-2 -4.8,67 67.7,2.9 62,.1 -.5,-48.1 -3.2,-.7 -2.6,-4.7 -2.5,-2.5 .5,-2.3 2.7,-2.6 .1,-1.2 -1.5,-2.1 -.9,1 -2,-.6 -2.9,-3 z"><title>Kansas (KS)</title></path><path id="state-ky" class="us-state-path" data-code="KY" data-name="Kentucky" data-region="Southeast" data-providers="16+" d="m 692.1,322.5 -20.5,1.4 -5.2,.8 -17.4,1 -2.6,.8 -22.6,2 -.7,-.6 h -3.7 l 1.2,3.2 -.6,.9 -23.3,1.5 1,-2.7 1.4,.9 .7,-.4 1.2,-4.1 -1,-1 1,-2 .2,-.9 -1.3,-.8 -.3,-1.8 4,-3.7 6.9,3 1.5,-.3 .4,-2.4 -1.7,-1.8 .4,-3.3 1,-.5 1.2,.6 .6,-1.2 3.7,-.6 .1,-.9 -1.5,-2.2 -.1,-1.1 2.2,-2.7 0,-.9 1.1,.8 .6,-1 -.7,-1.7 4.6,-.5 .2,1.2 1.1,.2 .4,-.9 -.6,-1.3 .3,-.8 1.3,.8 1.7,-.4 1.7,.6 3.4,2.1 1.8,-2.8 3.5,-2.2 3,3.3 1.6,-2.1 .3,-2.7 3.8,-2.3 .2,1.3 1.9,1.2 3,-.2 1.2,-.7 .1,-3.4 2.5,-3.7 4.6,-4.4 -.1,-1.7 1.2,-3.8 2.2,1 6.7,-4.5 -.4,-1.7 -1.5,-2.1 1,-1.9 1.3,.5 2.2,.1 1.9,-.8 2.9,1.2 2.2,3.4 v 1 l 4.1,.7 2.3,-.2 1.9,2.1 2.2,.2 v -1 l 1.9,-.8 3,.8 1.2,.8 1.3,-.7 h .9 l .6,-1.7 3.4,-1.8 .5,.8 .8,2.9 3.5,1.4 1.2,2.1 -.1,1.1 .6,1 -.6,3.6 1.9,1.6 .8,1.1 1,.6 -.1,.9 4.4,5.6 h 1.4 l 1.5,1.8 1.2,.3 1.4,-.1 -4.9,6.6 -2.9,1 -3,3 -.4,2.2 -2.1,1.3 -.1,1.7 -1.4,1.4 -1.8,.5 -.5,1.9 -1,.4 -6.9,4.2 z m -98,11.3 -.7,-.7 .2,-1 h 1.1 l .7,.7 -.3,1 z"><title>Kentucky (KY)</title></path><path id="state-la" class="us-state-path" data-code="LA" data-name="Louisiana" data-region="Southeast" data-providers="19+" d="m 602.5,472.8 -1.2,-1.8 .3,-1.3 -4.8,-6.8 .9,-4.6 1,-1.4 .1,-1.4 -36,2 1.7,-11.9 2.4,-4.8 6,-8.4 -1.8,-2.5 h 2 v -3.3 l -2.4,-2.5 .5,-1.7 -1.2,-1 -1.6,-7.1 .6,-1.4 -52.3,1.3 .5,19.9 .7,3.4 2.6,2.8 .7,5.4 3.8,4.6 .8,4.3 h 1 l -.1,7.3 -3.3,6.4 1.3,2.3 -1.3,1.5 .7,3 -.1,4.3 -2.2,3.5 -.1,.8 -1.7,1.2 1,1.8 1.2,1.1 1.6,-1.3 5.3,-.9 6.1,-.1 9.6,3.8 8,1 1.5,-1.4 1.8,-.2 4.8,2.2 1.6,-.4 1.1,-1.5 -4.2,-1.8 -2.2,1 -1.1,-.2 -1.4,-2 3.3,-2.2 1.6,-.1 v 1.7 l 1.5,-.1 3.4,-.3 .4,2.3 1.1,.4 .6,1.9 4.8,1 1.7,1.6 v .7 h -1.2 l -1.5,1.7 1.7,1.2 5.4,1 2.7,2.8 4.4,-1 -3.7,.2 -.1,-.6 2.8,-.7 .2,-1.8 1.2,-.3 v -1.4 l 1.1,.1 v 1.6 l 2.5,.1 .8,-1.9 .9,.3 .2,2.5 1.2,.2 -1.8,2 2.6,-.9 2,-1.1 2.9,-3.3 h -.7 l -1.3,1.2 -.4,-.1 -.5,-.8 .9,-1.2 v -2.3 l 1.1,-.8 .7,.7 1,-.8 1,-.1 .6,1.3 -.6,1.9 h 2.4 l 5.1,1.7 .5,1.3 1.6,1.4 2.8,.1 1.3,.7 1.8,-1 .9,-1.7 v -1.7 h -1.4 l -1.2,-1.4 -1.1,-1.1 -3.2,-.9 -2.6,.2 -4.2,-2.4 v -2.3 l 1.3,-1 2.4,.6 -3.1,-1.6 .2,-.8 h 3.6 l 2.6,-3.5 -2.6,-1.8 .8,-1.5 -1.2,-.8 h -.8 l -2,2.1 v 2.1 l -.6,.7 -1.1,-.1 -1.6,-1.4 h -1.3 v -1.5 l .6,-.7 .8,.7 1.7,-1.6 .7,-1.6 .8,-.3 z m -10.3,-2.7 1.9,1 .8,1.1 2.5,.1 1.5,.8 .2,1.4 -.4,.6 -.9,-1.5 -1.4,1.2 -.9,1.4 -2.8,.8 -1.6,.1 -3.7,-1 .1,-1.7 2,-2 1.1,-2.4 z m -4.7,1.2 v 1.1 l -1.8,2 h -1.2 v -2.2 l 1.6,-1.5 z"><title>Louisiana (LA)</title></path><path id="state-me" class="us-state-path" data-code="ME" data-name="Maine" data-region="Northeast" data-providers="8+" d="m 875,128.7 .6,4 3.2,2 .8,2.2 2.3,1.4 1.4,-.3 1,-3 -.8,-2.9 1.6,-.9 .5,-2.8 -.6,-1.3 3.3,-1.9 -2.2,-2.3 .9,-2.4 1.4,-2.2 .5,3.2 1.6,-2 1.3,.9 1.2,-.8 v -1.7 l 3.2,-1.3 .3,-2.9 2.5,-.2 2.7,-3.7 v -.7 l -.9,-.5 -.1,-3.3 .6,-1.1 .2,1.6 1,-.5 -.2,-3.2 -.9,.3 -.1,1.2 -1.2,-1.4 .9,-1.4 .6,.1 1.1,-.4 .5,2.8 2,-.3 2.9,.7 v -1 l -1.1,-1.2 1.3,.1 .1,-2.3 .6,.8 .3,1.9 2.1,1.5 .2,-1 .9,-.2 -.3,-.8 .8,-.6 -.1,-1.6 -1.6,-.2 -2,.7 1.4,-1.6 .7,-.8 1.3,-.2 .4,1.3 1.7,1.6 .4,-2.1 2.3,-1.2 -.9,-1.3 .1,-1.7 1.1,.5 h .7 l 1.7,-1.4 .4,-2.3 2.2,.3 .1,-.7 .2,-1.6 .5,1.4 1.5,-1 2.3,-4.1 -.1,-2.2 -1.4,-2 -3,-3.2 h -1.9 l -.8,2.2 -2.9,-3 .3,-.8 v -1.5 l -1.6,-4.5 -.8,-.2 -.7,.4 h -4.8 l -.3,-3.6 -8.1,-26 -7.3,-3.7 -2.9,-.1 -6.7,6.6 -2.7,-1 -1,-3.9 h -2.7 l -6.9,19.5 .7,6.2 -1.7,2.4 -.4,4.6 1.3,3.7 .8,.2 v 1.6 l -1.6,4.5 -1.5,1.4 -1.3,2.2 -.4,7.8 -2.4,-1 -1.5,.4 z m 34.6,-24.7 -1,.8 v 1.3 l .7,-.8 .9,.8 .4,-.5 1.1,.2 -1,-.8 .4,-.8 z m -1.7,2.6 -1,1.1 .5,.4 -.1,1 h 1.1 v -1.8 z m -3,-1.6 .9,1.3 1,.5 .3,-1 v -1.8 l -1.3,-.7 -.4,1.2 z m -1,5 -1.7,-1.7 1.6,-2.4 .8,.3 .2,1.1 1,.8 v 1.1 l -1,1 z"><title>Maine (ME)</title></path><path id="state-md" class="us-state-path" data-code="MD" data-name="Maryland" data-region="Mid-Atlantic" data-providers="21+" d="m 822.9,269.3 0,-1.7 h -.8 l 0,1.8 z m 11.8,-3.9 1.2,-2.2 .1,-2.5 -.6,-.6 -.7,.9 -.2,2.1 -.8,1.4 -.3,1.1 -4.6,1.6 -.7,.8 -1.3,.2 -.4,.9 -1.3,.6 -.3,-2.5 .4,-.7 -.8,-.5 .2,-1.5 -1.6,1 v -2 l 1.2,-.3 -1.9,-.4 -.7,-.8 .4,-1.3 -.8,-.6 -.7,1.6 .5,.8 -.7,.6 -1.1,.5 -2,-1 -.2,-1.2 -1,-1.1 -1.4,-1.7 1.5,-.8 -1,-.6 v -.9 l .6,-1 1.7,-.3 -1.4,-.6 -.1,-.7 -1.3,-.1 -.4,1.1 -.6,.3 .1,-3.4 1,-1 .8,.7 .1,-1.6 -1,-.9 -.9,1.1 -1,1.4 -.6,-1 .2,-2.4 .9,-1 .9,.9 1.2,-.7 -.4,-1.7 -1,1 -.9,-2.1 -.2,-1.7 1.1,-2.4 1.1,-1.4 1.4,-.2 -.5,-.8 .5,-.6 -.3,-.7 .2,-2.1 -1.5,.4 -.8,1.1 1,1.3 -2.6,3.6 -.9,-.4 -.7,.9 -.6,2.2 -1.8,.5 1.3,.6 1.3,1.3 -.2,.7 .9,1.2 -1.1,1 .5,.3 -.5,1.3 v 2.1 l -.5,1.3 .9,1.1 .7,3.4 1.3,1.4 1.6,1.4 .4,2.8 1.6,2 .4,1.4 v 1 h -.7 l -1.5,-1.2 -.4,.2 -1.2,-.2 -1.7,-1.4 -1.4,-.3 -1,.5 -1.2,-.3 -.4,.2 -1.7,-.8 -1,-1 -1,-1.3 -.6,-.2 -.8,.7 -1.6,1.3 -1.1,-.8 -.4,-2.3 .8,-2.1 -.3,-.5 .3,-.4 -.7,-1 1,-.1 1,-.9 .4,-1.8 1.7,-2.6 -2.6,-1.8 -1,1.7 -.6,-.6 h -1 l -.6,-.1 -.4,-.4 .1,-.5 -1.7,-.6 -.8,.3 -1.2,-.1 -.7,-.7 -.5,-.2 -.2,-.7 .6,-.8 v -.9 l -1.2,-.2 -1,-.9 -.9,.1 -1.6,-.3 -.9,-.4 .2,-1.6 -1,-.5 -.2,-.7 h -.7 l -.8,-1.2 .2,-1 -2.6,.4 -2.2,-1.6 -1.4,.3 -.9,1.4 h -1.3 l -1.7,2.9 -3.3,.4 -1.9,-1 -2.6,3.8 -2.2,-.3 -3.1,3.9 -.9,1.6 -1.8,1.6 -1.7,-11.4 60.5,-11.8 7.6,27.1 10.9,-2.3 0,5.3 -.1,3.1 -1,1.8 z m -13.4,-1.8 -1.3,.9 .8,1.8 1.7,.8 -.4,-1.6 z"><title>Maryland (MD)</title></path><path id="state-ma" class="us-state-path" data-code="MA" data-name="Massachusetts" data-region="Northeast" data-providers="25+" d="m 899.9,174.2 h 3.4 l .9,-.6 .1,-1.3 -1.9,-1.8 .4,1 -1.5,1.5 h -2.3 l .1,.8 z m -9,1.8 -1.2,-.6 1,-.8 .6,-2.1 1.2,-1 .8,-.2 .6,.9 1.1,.2 .6,-.6 .5,1.9 -1.3,.3 -2.8,.7 z m -34.9,-23.4 18.4,-3.8 1,-1.5 .3,-1.7 1.9,-.6 .5,-1.1 1.7,-1.1 1.3,.3 1.7,3.3 1,.4 1.1,-1.3 .8,1.3 v 1.1 l -3,2.4 .2,.8 -.9,1 .4,.8 -1.3,.3 .9,1.2 -.8,.7 .6,1 .9,-.2 .3,-.8 1.1,.6 h 1.8 l 2.5,2.6 .2,2.6 1.8,.1 .8,1.1 .6,2 1,.7 h 1.9 l 1.9,-.1 .8,-.9 1.6,-1.2 1.1,-.3 -1.2,-2.1 -.3,.9 -1.5,-3.6 h -.8 l -.4,.9 -1.2,-1 1.3,-1.1 1.8,.4 2.3,2.1 1.3,2.7 1.2,3.3 -1,2.8 v -1.8 l -.7,-1 -3.5,2.3 -.9,-.3 -1.6,1 -.1,1.2 -2.2,1.2 -2,2.1 -2,1.9 h -1.2 l 3.3,-3.3 .5,-1.9 -.5,-.6 -.3,-1.3 -.9,-.1 -.1,1.3 -1,1.2 h -1.2 l -.3,1.1 .4,1.2 -1.2,1.1 -1.1,-.2 -.4,1 -1.4,-3 -1.3,-1.1 -2.6,-1.3 -.6,-2.2 h -.8 l -.7,-2.6 -6.5,2 -.1,-.3 -14.9,3.4 v .7 l -.9,.3 -.5,-.7 -10.5,2.4 -.7,-1 .5,-15 z"><title>Massachusetts (MA)</title></path><path id="state-mi" class="us-state-path" data-code="MI" data-name="Michigan" data-region="Midwest" data-providers="28+" d="m 663.3,209.8 .1,1.4 21.4,-3.5 .5,-1.2 3.9,-5.9 v -4.3 l .8,-2.1 2.2,-.8 2,-7.8 1,-.5 1,.6 -.2,.6 -1.1,.8 .3,.9 .8,.4 1.9,-1.4 .4,-9.8 -1.6,-2.3 -1.2,-3.7 v -2.5 l -2.3,-4.4 v -1.8 l -1.2,-3.3 -2.3,-3 -2.9,-1 -4.8,3 -2.5,4.6 -.2,.9 -3,3.5 -1.5,-.2 -2.9,-2.8 -.1,-3.4 1.5,-1.9 2,-.2 1.2,-1.7 .2,-4 .8,-.8 1.1,-.1 .9,-1.7 -.2,-9.6 -.3,-1.3 -1.2,-1.2 -1.7,-1 -.1,-1.8 .7,-.6 1.8,.8 -.3,-1.7 -1.9,-2.7 -.7,-1.6 -1.1,-1.1 h -2.2 l -8.1,-2.9 -1.4,-1.7 -3.1,-.3 -1.2,.3 -4.4,-2.3 h -1.4 l .5,1 -2.7,-.1 .1,.6 .6,.6 -2.5,2.1 .1,1.8 1.5,2.3 1.5,.2 v .6 l -1.5,.5 -2.1,-.1 -2.8,2.5 .1,2.5 .4,5.8 -2.2,3.4 .8,-4.5 -.8,-.6 -.9,5.3 -1,-2.3 .5,-2.3 -.5,-1 .6,-1.3 -.6,-1.1 1,-1 v -1.2 l -1.3,.6 -1.3,3.1 -.7,.7 -1.3,2.4 -1.7,-.2 -.1,1.2 h -1.6 l .2,1.5 .2,2 -3,1.2 .1,1.3 1,1.7 -.1,5.2 -1.3,4.4 -1.7,2.5 1.2,1.4 .8,3.5 -1,2.5 -.2,2.1 1.7,3.4 2.5,4.9 1.2,1.9 1.6,6.9 -.1,8.8 -.9,3.9 -2,3.2 -.9,3.7 -2,3 -1.2,1 z m -95.8,-96.8 3,3.8 17,3.8 1.4,1 4,.8 .7,.5 2.8,-.2 4.9,.8 1.4,1.5 -1,1 .8,.8 3.8,.7 1.2,1.2 .1,4.4 -1.3,2.8 2,.1 1,-.8 .9,.8 -1.1,3.1 1,1.6 1.2,.3 .8,-1.8 2.9,-4.6 1.6,-6 2.3,-2 -.5,-1.6 .5,-.9 1,1.6 -.3,2.2 2.9,-2.2 .2,-2.3 2.1,.6 .8,-1.6 .7,.6 -.7,1.5 -1,.5 -1,2 1.4,1.8 1.1,-.5 -.5,-.7 1,-1.5 1.9,-1.7 h .8 l .2,-2.6 2,-1.8 7.9,-.5 1.9,-3.1 3.8,-.3 3.8,1.2 4.2,2.7 .7,-.2 -.2,-3.5 .7,-.2 4.5,1.1 1.5,-.2 2.9,-.7 1.7,.4 1.8,.1 v -1.1 l -.7,-.9 -1.5,-.2 -1.1,-.8 .5,-1.4 -.8,-.3 -2.6,.1 -.1,-1 1.1,-.8 .6,.8 .5,-1.8 -.7,-.7 .7,-.2 -1.4,-1.3 .3,-1.3 .1,-1.9 h -1.3 l -1.5,1 -1.9,.1 -.5,1.8 -1.9,.2 -.3,-1.2 -2.2,.1 -1,1.2 -.7,-.1 -.2,-.8 -2.6,.4 -.1,-4.8 1,-2 -.7,-.1 -1.8,1.1 h -2.2 l -3.8,2.7 -6.2,.3 -4.1,.8 -1.9,1.5 -1.4,1.3 -2.5,1.7 -.3,.8 -.6,-1.7 -1.3,-.6 v .6 l .7,.7 v 1.3 l -1.5,-.6 h -.6 l -.3,1.2 -2,-1.9 -1.3,-.2 -1.3,1.5 -3.2,-.1 -.5,-1.4 -2,-1.9 -1.3,-1.6 v -.7 l -1.1,-1.4 -2.6,-1.2 -3.3,-.1 -1.1,-.9 h -1.4 l -.7,.4 -2.2,2.2 -.7,1.1 -1,-.7 .2,-1 .8,-2.1 3.2,-5 .8,-.2 1.7,-1.9 .7,-1.6 3,-.6 .8,-.6 -.1,-1 -.5,-.5 -4.5,.2 -2,.5 -2.6,1.2 -1.2,1.2 -1.7,2.2 -1.8,1 -3.3,3.4 -.4,1.6 -7.4,4.6 -4,.5 -1.8,.4 -2.3,3 -1.8,.7 -4.4,2.3 z m 100.7,3.8 3.8,.1 .6,-.5 -.2,-2 -1.7,-1.8 -1.9,.1 -.1,.5 1.1,.4 -1.6,.8 -.3,1 -.6,-.6 -.4,.8 z m -75.1,-41.9 -2.3,.2 -2.7,1.9 -7.1,5.3 .8,1 1.8,.3 2.8,-2 -1.1,-.5 2.3,-1.6 h 1 l 3,-1.9 -.1,-.9 z m 41.1,62.8 v 1 l 2.1,1.6 -.2,-2.4 z m -.7,2.8 1.1,.1 v .9 h -1 z m 21.4,-21.3 v .9 l .8,-.2 v -.5 z m 4.7,3.1 -.1,-1.1 -1.6,-.2 -.6,-.4 h -.9 l -.4,.3 .9,.4 1.1,1.1 z m -18,1.2 -.1,1.1 -.3,.7 .2,2.2 .4,.3 .7,.1 .5,-.9 .1,-1.6 -.3,-.6 -.1,-1.1 z"><title>Michigan (MI)</title></path><path id="state-mn" class="us-state-path" data-code="MN" data-name="Minnesota" data-region="Midwest" data-providers="19+" d="m 464.7,68.6 -1.1,2.8 .8,1.4 -.3,5.1 -.5,1.1 2.7,9.1 1.3,2.5 .7,14 1,2.7 -.4,5.8 2.9,7.4 .3,5.8 -.1,2.1 -.1,2.2 -.9,2 -3.1,1.9 -.3,1.2 1.7,2.5 .4,1.8 2.6,.6 1.5,1.9 -.2,39.5 h 28.2 l 36.3,-.9 18.6,-.7 -1.1,-4.5 -.2,-3 -2.2,-3 -2.8,-.7 -5.2,-3.6 -.6,-3.3 -6.3,-3.1 -.2,-1.3 h -3.3 l -2.2,-2.6 -2,-1.3 .7,-5.1 -.9,-1.6 .5,-5.4 1,-1.8 -.3,-2.7 -1.2,-1.3 -1.8,-.3 v -1.7 l 2.8,-5.8 5.9,-3.9 -.4,-13 .9,.4 .6,-.5 .1,-1.1 .9,-.6 1.4,1.2 .7,-.1 v 0 l -1.2,-2.2 4.3,-3.1 3.1,-3.7 1.6,-.8 4.7,-5.9 6.3,-5.8 3.9,-2.1 6.3,-2.7 7.6,-4.5 -.6,-.4 -3.7,.7 -2.8,.1 -1,-1.6 -1.4,-.9 -9.8,1.2 -1,-2.8 -1.6,-.1 -1.7,.8 -3.7,3.1 h -4.1 l -2.1,-1 -.3,-1.7 -3.9,-.8 -.6,-1.6 -.7,-1.3 -1,.9 -2.6,.1 -9.9,-5.5 h -2.9 l -.8,-.7 -3.1,1.3 -.8,1.3 -3.3,.8 -1.3,-.2 v -1.7 l -.7,-.9 h -5.9 l -.4,-1.4 h -2.6 l -1.1,.4 -2.4,-1.7 .3,-1.4 -.6,-2.4 -.7,-1.1 -.2,-3 -1,-3.1 -2.1,-1.6 h -2.9 l .1,8 -30.9,-.4 z"><title>Minnesota (MN)</title></path><path id="state-ms" class="us-state-path" data-code="MS" data-name="Mississippi" data-region="Southeast" data-providers="10+" d="m 623.8,468.6 -5,.1 -2.4,-1.5 -7.9,2.5 -.9,-.7 -.5,.2 -.1,1.6 -.6,.1 -2.6,2.7 -.7,-.1 -.6,-.7 -1.2,-1.8 .3,-1.3 -4.8,-6.8 .9,-4.6 1,-1.4 .1,-1.4 -36,2 1.7,-11.9 2.4,-4.8 6,-8.4 -1.8,-2.5 h 2 v -3.3 l -2.4,-2.5 .5,-1.7 -1.2,-1 -1.6,-7.1 .6,-1.4 1.2,-1.5 .5,-3 -1.5,-2.3 -.5,-2.2 .9,-.7 v -.8 l -1.7,-1.1 -.1,-.7 1.6,-.9 -1.2,-1.1 1.7,-7.1 3.4,-1.6 v -.8 l -1.1,-1.4 2.9,-5.4 h 1.9 l 1.5,-1.2 -.3,-5.2 3.1,-4.5 1.8,-.6 -.5,-3.1 38.3,-2.6 1.3,2 -1.3,67 4.4,33.2 z"><title>Mississippi (MS)</title></path><path id="state-mo" class="us-state-path" data-code="MO" data-name="Missouri" data-region="Midwest" data-providers="23+" d="m 555.3,248.9 -1.1,-1.1 -.6,-1.6 -1.7,-1.3 -14.3,.8 -27.2,1.2 -25.9,-.1 1.3,1.3 -.3,1.4 2.1,3.7 3.9,6.3 2.9,3 2,.6 .9,-1 1.5,2.1 -.1,1.2 -2.7,2.6 -.5,2.3 2.5,2.5 2.6,4.7 3.2,.7 .5,48.1 .2,10.8 39.1,-.7 39.8,-2 1.6,2.5 v 2.2 l -1.7,1.5 -2.8,5.1 11.2,-.8 1,-2 1.2,-.5 v -.7 l -1.2,-1.1 -.6,-1 1.7,.2 .8,-.7 -1.4,-1.5 1.4,-.5 .1,-1 -.6,-1 v -1.3 l -.7,-.7 .2,-1 h 1.1 l .7,.7 -.3,1 .8,.7 .8,-1 1,-2.7 1.4,.9 .7,-.4 1.2,-4.1 -1,-1 1,-2 .2,-.9 -1.3,-.8 h -2.8 l -1.4,-1.5 -1.8,-3.8 v -1.9 l .8,-.6 .1,-1.3 -1.7,-1.9 -.9,-2.5 -2.7,-4.1 -4.8,-1.3 -7.4,-7.1 -.4,-2.4 2.8,-7.6 -.4,-1.9 1.2,-1.1 v -1.3 l -2.8,-1.5 -3,-.7 -3.4,1.2 -1.3,-2.3 .6,-1.9 -.7,-2.4 -8.6,-8.4 -2.2,-1.5 -2.5,-5.9 -1.2,-5.4 1.4,-3.7 z"><title>Missouri (MO)</title></path><path id="state-mt" class="us-state-path" data-code="MT" data-name="Montana" data-region="Northwest" data-providers="8+" d="m 247,130.5 57.3,7.9 51,5.3 2,-20.7 5.2,-66.7 -53.5,-5.6 -54.3,-7.7 -65.9,-12.5 -4.8,22 3.7,7.4 -1.6,4.8 3.6,4.8 1.9,.7 3.9,8.3 v 2.1 l 2.3,3 h .9 l 1.4,2.1 h 3.2 v 1.6 l -7.1,17 -.5,4.1 1.4,.5 1.6,2.6 2.8,-1.4 3.6,-2.4 1.9,1.9 .5,2.5 -.5,3.2 2.5,9.7 2.6,3.5 2.3,1.4 .4,3 v 4.1 l 2.3,2.3 1.6,-2.3 6.9,1.6 2.1,-1.2 9,1.7 2.8,-3.3 1.8,-.6 1.2,1.8 1.6,4.1 .9,.1 z"><title>Montana (MT)</title></path><path id="state-ne" class="us-state-path" data-code="NE" data-name="Nebraska" data-region="Midwest" data-providers="12+" d="m 402.5,191.1 38,1.6 3.4,3.2 1.7,.2 2.1,2 1.8,-.1 1.8,-2 1.5,.6 1,-.7 .7,.5 .9,-.4 .7,.4 .9,-.4 1,.5 1.4,-.6 2,.6 .6,1.1 6.1,2.2 1.2,1.3 .9,2.6 1.8,.7 1.5,-.2 .5,.9 v 2.3 l .6,1.7 v 1.4 l 1.3,3.7 2.2,4.3 .2,3.7 2.8,5 .4,6.1 1.3,.7 -.2,2.9 .8,3.3 -.7,2.2 1.8,4.4 1.3,1.3 -.3,1.4 2.1,3.7 3.9,6.3 h -32.4 l -43.7,-1.2 -36,-2 1.4,-22.1 -33.1,-2.4 3.7,-44.2 z"><title>Nebraska (NE)</title></path><path id="state-nv" class="us-state-path" data-code="NV" data-name="Nevada" data-region="West" data-providers="15+" d="m 167.6,296.8 -3.4,17.5 -2.4,2.9 h -2 l -1.2,-2.7 -3.7,-1.4 -3.5,.6 -1,13.6 .5,4.9 -.5,2.9 -1.4,3 -70.4,-105 -1.1,-3.5 16.4,-63.1 47,11.2 24.4,5.4 23.3,4.7 z"><title>Nevada (NV)</title></path><path id="state-nh" class="us-state-path" data-code="NH" data-name="New Hampshire" data-region="Northeast" data-providers="9+" d="m 862.6,93.6 -1.3,.1 -1,-1.1 -1.9,1.4 -.5,6.1 1.2,2.3 -1.1,3.5 2.1,2.8 -.4,1.7 .1,1.3 -1.1,2.1 -1.4,.4 -.6,1.3 -2.1,1 -.7,1.5 1.4,3.4 -.5,2.5 .5,1.5 -1,1.9 .4,1.9 -1.3,1.9 .2,2.2 -.7,1.1 .7,4.5 .7,1.5 -.5,2.6 .9,1.8 -.2,2.5 -.5,1.3 -.1,1.4 2.1,2.6 18.4,-3.8 1,-1.5 .3,-1.7 1.9,-.6 .5,-1.1 1.7,-1.1 1.3,.3 .8,-4.8 -2.3,-1.4 -.8,-2.2 -3.2,-2 -.6,-4 -11.9,-36.8 z"><title>New Hampshire (NH)</title></path><path id="state-nj" class="us-state-path" data-code="NJ" data-name="New Jersey" data-region="HQ / Northeast" data-providers="70+" d="m 842.5,195.4 -14.6,-4.9 -1.8,2.5 .1,2.2 -3,5.4 1.5,1.8 -.7,2 -1,1 .5,3.6 2.7,.9 1,2.8 2.1,1.1 4.2,3.2 -3.3,2.6 -1.6,2.3 -1.8,3 -1.6,.6 -1.4,1.7 -1,2.2 -.3,2.1 .8,.9 .4,2.3 1.2,.6 2.4,1.5 1.8,.8 1.6,.8 .1,1.1 .8,.1 1.1,-1.2 .8,.4 2.1,.2 -.2,2.9 .2,2.5 1.8,-.7 1.5,-3.9 1.6,-4.8 2.9,-2.8 .6,-3.5 -.6,-1.2 1.7,-2.9 v -1.2 l -.7,-1.1 1.2,-2.7 -.3,-3.6 -.6,-8.2 -1.2,-1.4 v 1.4 l .5,.6 h -1.1 l -.6,-.4 -1.3,-.2 -.9,.6 -1.2,-1.6 .7,-1.7 v -1 l 1.7,-.7 .8,-2.1 z"><title>New Jersey (NJ)</title></path><path id="state-nm" class="us-state-path" data-code="NM" data-name="New Mexico" data-region="Southwest" data-providers="11+" d="m 357.5,332.9 h -.8 l -7.9,99.3 -31.8,-2.6 -34.4,-3.6 -.3,3 2,2.2 -30.8,-4.1 -1.4,10.2 -15.7,-2.2 17.4,-124.1 52.6,6.5 51.7,4.8 z"><title>New Mexico (NM)</title></path><path id="state-ny" class="us-state-path" data-code="NY" data-name="New York" data-region="Northeast" data-providers="58+" d="m 872.9,181.6 -1.3,.1 -.5,1 z m -30.6,22.7 .7,.6 1.3,-.3 1.1,.3 .9,-1.3 h 1.9 l 2.4,-.9 5.1,-2.1 -.5,-.5 -1.9,.8 -2,.9 .2,-.8 2.6,-1.1 .8,-1 1.2,.1 4.1,-2.3 v .7 l -4.2,3 4.5,-2.8 1.7,-2.2 1.5,-.1 4.5,-3.1 3.2,-3.1 3,-2.3 1,-1.2 -1.7,-.1 -1,1.2 -.2,.7 -.9,.7 -.8,-1.1 -1.7,1 -.1,.9 -.9,-.2 .5,-.9 -1.2,-.7 -.6,.9 .9,.3 .2,.5 -.3,.5 -1.4,2.6 h -1.9 l .9,-1.8 .9,-.6 .3,-1.7 1.4,-1.6 .9,-.8 1.5,-.7 -1.2,-.2 -.7,.9 h -.7 l -1.1,.8 -.2,1 -2.2,2.1 -.4,.9 -1.4,.9 -7.7,1.9 .2,.9 -.9,.7 -2,.3 -1,-.6 -.2,1.1 -1.1,-.4 .1,1 -1.2,-.1 -1.2,.5 -.2,1.1 h -1 l .2,1 h -.7 l .2,1 -1.8,.4 -1.5,2.3 z m -.8,-.4 -1.6,.4 v 1 l -.7,1.6 .6,.7 2.4,-2.3 -.1,-.9 z m -10.1,-95.2 -.6,1.9 1.4,.9 -.4,1.5 .5,3.2 2.2,2.3 -.4,2.2 .6,2 -.4,1 -.3,3.8 3.1,6.7 -.8,1.8 .9,2.2 .9,-1.6 1.9,1.5 3,14.2 -.5,2 1.1,1 -.5,15 .7,1 2.8,16.3 1.8,1.5 -3.5,3.4 1.7,2.2 -1.3,3.3 -1.5,1.7 -1.5,2.3 -.2,-.7 .4,-5.9 -14.6,-4.9 -1.6,-1.1 -1.9,.3 -3,-2.2 -3,-5.8 h -2 l -.4,-1.5 -1.7,-1.1 -70.5,13.9 -.8,-6 4.3,-3.9 .6,-1.7 3.9,-2.5 .6,-2.4 2.3,-2 .8,-1.1 -1.7,-3.3 -1.7,-.5 -1.8,-3 -.2,-3.2 7.6,-3.9 8.2,-1.6 h 4.4 l 3.2,1.6 .9,-.1 1.8,-1.6 3.4,-.7 h 3 l 2.6,-1.3 2.5,-2.6 2.4,-3.1 1.9,-.4 1.1,-.5 .4,-3.2 -1.4,-2.7 -1.2,-.7 2,-1.3 -.1,-1.8 h -1.5 l -2.3,-1.4 -.1,-3.1 6.2,-6.1 .7,-2.4 3.7,-6.3 5.9,-6.4 2.1,-1.7 2.5,.1 20.6,-5.2 z"><title>New York (NY)</title></path><path id="state-nc" class="us-state-path" data-code="NC" data-name="North Carolina" data-region="Southeast" data-providers="32+" d="m 829,300.1 -29.1,6.1 -39.4,7.3 -29.4,3.5 v 5.2 l -1.5,-.1 -1.4,1.2 -2.4,5.2 -2.6,-1.1 -3.5,2.5 -.7,2.1 -1.5,1.2 -.8,-.8 -.1,-1.5 -.8,-.2 -4,3.3 -.6,3.4 -4.7,2.4 -.5,1.2 -3.2,2.6 -3.6,.5 -4.6,3 -.8,4.1 -1.3,.9 -1.5,-.1 -1.4,1.3 -.1,4.9 21.4,-3 4.4,-1.9 1.3,-.1 7.3,-4.3 23.2,-2.2 .4,.5 -.2,1.4 .7,.3 1.2,-1.5 3.3,3 .1,2.6 19.7,-2.8 24.5,17.1 4,-2.2 3,-.7 h 1.7 l 1.1,1.1 .8,-2 .6,-5 1.7,-3.9 5.4,-6.1 4.1,-3.5 5.4,-2.3 2.5,-.4 1.3,.4 .7,1.1 3.3,-6.6 3.3,-5.3 -.7,-.3 -4.4,6.8 -.5,-.8 2,-2.2 -.4,-1.5 -2,-.5 1,1.3 -1.2,.1 -1.2,-1.8 -1.2,2 -1.6,.2 1,-2.7 .7,-1.7 -.2,-2.9 -2.2,-.1 .9,-.9 1.1,.3 2.7,.1 .8,-.5 h 2.3 l 2,-1.9 .2,-3.2 1.3,-1.4 1.2,-.2 1.3,-1 -.5,-3.7 -2.2,-3.8 -2.7,-.2 -.9,1.6 -.5,-1 -2.7,.2 -1.2,.4 -1.9,1.2 -.3,-.4 h -.9 l -1.8,1.2 -2.6,.5 v -1.3 l .8,-1 1,.7 h 1 l 1.7,-2.1 3.7,-1.7 2,-2.2 h 2.4 l .8,1.3 1.7,.8 -.5,-1.5 -.3,-1.6 -2.8,-3.1 -.3,-1.4 -.4,1 -.9,-1.3 z m 7,31 2.7,-2.5 4.6,-3.3 v -3.7 l -.4,-3.1 -1.7,-4.2 1.5,1.4 1,3.2 .4,7.6 -1.7,.4 -3.1,2.4 -3.2,3.2 z m 1.9,-19.3 -.9,-.2 v 1 l 2.5,2.2 -.2,-1.4 z m 2.9,2.1 -1.4,-2.8 -2.2,-3.4 -2.4,-3 -2.2,-4.3 -.8,-.7 2.2,4.3 .3,1.3 3.4,5.5 1.8,2.1 z"><title>North Carolina (NC)</title></path><path id="state-nd" class="us-state-path" data-code="ND" data-name="North Dakota" data-region="Midwest" data-providers="6+" d="m 464.7,68.6 -1.1,2.8 .8,1.4 -.3,5.1 -.5,1.1 2.7,9.1 1.3,2.5 .7,14 1,2.7 -.4,5.8 2.9,7.4 .3,5.8 -.1,2.1 -29.5,-.4 -46,-2.1 -39.2,-2.9 5.2,-66.7 44.5,3.4 55.3,1.6 z"><title>North Dakota (ND)</title></path><path id="state-oh" class="us-state-path" data-code="OH" data-name="Ohio" data-region="Midwest" data-providers="35+" d="m 685.7,208.8 1.9,-.4 3,1.3 2.1,.6 .7,.9 h 1 l 1,-1.5 1.3,.8 h 1.5 l -.1,1 -3.1,.5 -2,1.1 1.9,.8 1.6,-1.5 2.4,-.4 2.2,1.5 1.5,-.1 2.5,-1.7 3.6,-2.1 5.2,-.3 4.9,-5.9 3.8,-3.1 9.3,-5.1 4.9,29.9 -2.2,1.2 1.4,2.1 -.1,2.2 .6,2 -1.1,3.4 -.1,5.4 -1,3.6 .5,1.1 -.4,2.2 -1.1,.5 -2,3.3 -1.8,2 h -.6 l -1.8,1.7 -1.3,-1.2 -1.5,1.8 -.3,1.2 h -1.3 l -1.3,2.2 .1,2.1 -1,.5 1.4,1.1 v 1.9 l -1,.2 -.7,.8 -1,.5 -.6,-2.1 -1.6,-.5 -1,2.3 -.3,2.2 -1.1,1.3 1.3,3.6 -1.5,.8 -.4,3.5 h -1.5 l -3.2,1.4 -1.2,-2.1 -3.5,-1.4 -.8,-2.9 -.5,-.8 -3.4,1.8 -.6,1.7 h -.9 l -1.3,.7 -1.2,-.8 -3,-.8 -1.9,.8 v 1 l -2.2,-.2 -1.9,-2.1 -2.3,.2 -4.1,-.7 v -1 l -2.2,-3.4 -2.9,-1.2 -1.9,.8 -2.2,-.1 -1.3,-.5 -6.6,-57.2 21.4,-3.5 z"><title>Ohio (OH)</title></path><path id="state-ok" class="us-state-path" data-code="OK" data-name="Oklahoma" data-region="South Central" data-providers="14+" d="m 501.5,398.6 -4.6,-3.8 -2.2,-.9 -.5,1.6 -5.1,.3 -.6,-1.5 -5,2.5 -1.6,-.7 -3.7,.3 -.6,1.7 -3.6,.9 -1.3,-1.2 -1.2,.1 -2,-1.8 -2.1,.7 -2,-.5 -1.8,-2 -2.5,4.2 -1.2,.8 -1,-1.8 .3,-2 -1.2,-.7 -2.3,2.5 -1.7,-1.2 -.1,-1.5 -1.3,.5 -2.6,-1.7 -3,2.6 -2.3,-1.1 .7,-2.1 -2.3,.1 -1.9,-3 -3.5,-1.1 -2,2.3 -2.3,-2.2 -1.4,.4 -2,.1 -3.5,-1.9 -2.3,.1 -1.2,-.7 -.5,-2.9 -2.3,-1.7 -1.1,1.5 -1.4,-1 -1.2,-.4 -1.1,1 -1.5,-.3 -2.5,-3 -2.7,-1.3 1.4,-42.7 -52.6,-3.2 .6,-10.6 16.5,1 67.7,2.9 62,.1 .2,10.8 4.1,24.4 -.7,39 z"><title>Oklahoma (OK)</title></path><path id="state-or" class="us-state-path" data-code="OR" data-name="Oregon" data-region="Northwest" data-providers="18+" d="m 93.9,166.5 47,11.2 8.5,-37.3 2.9,-5.8 .4,-2.1 .8,-.9 -.9,-2 -2.9,-1.2 .2,-4.2 4,-5.8 2.5,-.8 1.6,-2.3 -.1,-1.6 1.8,-1.6 3.2,-5.5 4.2,-4.8 -.5,-3.2 -3.5,-3.1 -1.6,-3.6 -30.3,-7.3 -2.8,1 -5.4,-.9 -1.8,-.9 -1.5,1.2 -3.3,-.4 -4.5,.5 -.9,.7 -4.2,-.4 -.8,-1.6 -1.2,-.2 -4.4,1.3 -1.6,-1.1 -2.2,.8 -.2,-1.8 -2.3,-1.2 -1.5,-.2 -1,-1.1 -3,.3 -1.2,-.8 h -1.2 l -1.2,.9 -5.5,.7 -6.6,-4.2 1.1,-5.6 -.4,-4.1 -3.2,-3.7 -3.7,.1 -.4,-1.1 .4,-1.2 -.7,-.8 -1,.1 -1.1,1.3 -1.5,-.2 -.5,-1.1 -1,-.1 -.7,.6 -2,-1.9 v 4.3 l -1.3,1.3 -1.1,3.5 -.1,2.3 -4.5,12.3 -13.2,31.3 -3.2,4.6 -1.6,-.1 .1,2.1 -5.2,7.1 -.3,3.3 1,1.3 .1,2.4 -1.2,1.1 -1.2,3 .1,5.7 1.2,2.9 z"><title>Oregon (OR)</title></path><path id="state-pa" class="us-state-path" data-code="PA" data-name="Pennsylvania" data-region="Northeast" data-providers="42+" d="m 826.3,189.4 -1.9,.3 -3,-2.2 -3,-5.8 h -2 l -.4,-1.5 -1.7,-1.1 -70.5,13.9 -.8,-6 -4.2,3.4 -.9,.1 -2.7,3 -3.3,1.7 4.9,29.9 3.2,19.7 17.4,-2.9 60.5,-11.8 1.2,-2.1 1.5,-1.1 1.6,-.3 1.6,.6 1.4,-1.7 1.6,-.6 1.8,-3 1.6,-2.3 3.3,-2.6 -4.2,-3.2 -2.1,-1.1 -1,-2.8 -2.7,-.9 -.5,-3.6 1,-1 .7,-2 -1.5,-1.8 3,-5.4 -.1,-2.2 1.8,-2.5 z"><title>Pennsylvania (PA)</title></path><path id="state-ri" class="us-state-path" data-code="RI" data-name="Rhode Island" data-region="Northeast" data-providers="7+" d="m 883.2,170.7 -1.3,-1.1 -2.6,-1.3 -.6,-2.2 h -.8 l -.7,-2.6 -6.5,2 3.2,12.3 -.4,1.1 .4,1.8 5.6,-3.6 .1,-3 -.8,-.8 .4,-.6 -.1,-1.3 -.9,-.7 1.2,-.4 -.9,-1.6 1.8,.7 .3,1.4 .7,1.2 -1.4,-.8 1.1,1.7 -.3,1.2 -.6,-1.1 v 2.5 l .6,-.9 .4,.9 1.3,-1.5 -.2,-2.5 1.4,3.1 1,-.9 z m -4.7,12.2 h .9 l .5,-.6 -.8,-1.3 -.7,.7 z"><title>Rhode Island (RI)</title></path><path id="state-sc" class="us-state-path" data-code="SC" data-name="South Carolina" data-region="Southeast" data-providers="20+" d="m 772.3,350.2 -19.7,2.8 -.1,-2.6 -3.3,-3 -1.2,1.5 -.7,-.3 .2,-1.4 -.4,-.5 -23.2,2.2 -7.3,4.3 -1.3,.1 -4.4,1.9 -.1,1.9 -1.9,1 -1.4,3.2 .2,1.3 6.1,3.8 2.6,-.3 3.1,4 .4,1.7 4.2,5.1 2.6,1.7 1.4,.2 2.2,1.6 1.1,2.2 2,1.6 1.8,.5 2.7,2.7 .1,1.4 2.6,2.8 5,2.3 3.6,6.7 .3,2.7 3.9,2.1 2.5,4.8 .8,3.1 4.2,.4 .8,-1.5 h .6 l 1.8,-1.5 .5,-2 3.2,-2.1 .3,-2.4 -1.2,-.9 .8,-.7 .8,.4 1.3,-.4 1.8,-2.1 3.8,-1.8 1.6,-2.4 .1,-.7 4.8,-4.4 -.1,-.5 -.9,-.8 1.1,-1.5 h .8 l .4,.5 .7,-.8 h 1.3 l .6,-1.5 2.3,-2.1 -.3,-5.4 .8,-2.3 3.6,-6.2 2.4,-2.2 2.2,-1.1 z"><title>South Carolina (SC)</title></path><path id="state-sd" class="us-state-path" data-code="SD" data-name="South Dakota" data-region="Midwest" data-providers="7+" d="m 396.5,125.9 46,2.1 29.5,.4 -.1,2.2 -.9,2 -3.1,1.9 -.3,1.2 1.7,2.5 .4,1.8 2.6,.6 1.5,1.9 -.2,39.5 -2.2,-.1 -.1,1.6 1.3,1.5 -.1,1.1 -1,.5 .4,1.6 1.3,.4 .7,2 -1.7,5.1 -1,4.3 1.3,1.2 .3,1.3 .7,1.7 -1.5,.2 -1.8,-.7 -.9,-2.6 -1.2,-1.3 -6.1,-2.2 -.6,-1.1 -2,-.6 -1.4,.6 -1,-.5 -.9,.4 -.7,-.4 -.9,.4 -.7,-.5 -1,.7 -1.5,-.6 -1.8,2 -1.8,.1 -2.1,-2 -1.7,-.2 -3.4,-3.2 -38,-1.6 -51.1,-3.5 3.9,-43.9 2,-20.7 z"><title>South Dakota (SD)</title></path><path id="state-tn" class="us-state-path" data-code="TN" data-name="Tennessee" data-region="Southeast" data-providers="26+" d="m 620.9,365.1 45.7,-4 22.9,-2.9 .1,-4.9 1.4,-1.3 1.5,.1 1.3,-.9 .8,-4.1 4.6,-3 3.6,-.5 3.2,-2.6 .5,-1.2 4.7,-2.4 .6,-3.4 4,-3.3 .8,.2 .1,1.5 .8,.8 1.5,-1.2 .7,-2.1 3.5,-2.5 2.6,1.1 2.4,-5.2 1.4,-1.2 1.5,.1 0,-5.2 .3,-.7 -4.6,.5 -.2,1 -28.9,3.3 -5.6,1.4 -20.5,1.4 -5.2,.8 -17.4,1 -2.6,.8 -22.6,2 -.7,-.6 h -3.7 l 1.2,3.2 -.6,.9 -23.3,1.5 -.8,1 -.8,-.7 h -1 v 1.3 l .6,1 -.1,1 -1.4,.5 1.4,1.5 -.8,.7 -1.7,-.2 .6,1 1.2,1.1 v .7 l -1.2,.5 -1,2 .1,.6 1.4,1 -.4,.7 h -1.5 v .5 l .9,.9 .1,.8 -1.4,.2 -.5,.8 -1.6,.2 -.9,.9 .6,.9 1.1,-.1 .5,.9 -1.6,1.3 .4,1.5 -2,-.6 -.1,.7 .4,1.1 -.3,1.4 -1.3,-.8 -.8,.8 1.1,.1 .1,1.5 -.6,1 1.1,.9 -.3,1.5 .8,.7 -.7,1 -1.2,-.5 -.9,2.2 -1.6,.7 z"><title>Tennessee (TN)</title></path><path id="state-tx" class="us-state-path" data-code="TX" data-name="Texas" data-region="South Central Hub" data-providers="60+" d="m 282.3,429 .3,-3 34.4,3.6 31.8,2.6 7.9,-99.3 .8,0 52.6,3.2 -1.4,42.7 2.7,1.3 2.5,3 1.5,.3 1.1,-1 1.2,.4 1.4,1 1.1,-1.5 2.3,1.7 .5,2.9 1.2,.7 2.3,-.1 3.5,1.9 2,-.1 1.4,-.4 2.3,2.2 2,-2.3 3.5,1.1 1.9,3 2.3,-.1 -.7,2.1 2.3,1.1 3,-2.6 2.6,1.7 1.3,-.5 .1,1.5 1.7,1.2 2.3,-2.5 1.2,.7 -.3,2 1,1.8 1.2,-.8 2.5,-4.2 1.8,2 2,.5 2.1,-.7 2,1.8 1.2,-.1 1.3,1.2 3.6,-.9 .6,-1.7 3.7,-.3 1.6,.7 5,-2.5 .6,1.5 5.1,-.3 .5,-1.6 2.2,.9 4.6,3.8 6.4,1.9 2.6,2.3 2.8,-1.3 3.2,.8 .2,11.9 .5,19.9 .7,3.4 2.6,2.8 .7,5.4 3.8,4.6 .8,4.3 h 1 l -.1,7.3 -3.3,6.4 1.3,2.3 -1.3,1.5 .7,3 -.1,4.3 -2.2,3.5 -.1,.8 -1.7,1.2 1,1.8 1.2,1.1 -3.5,.3 -8.4,3.9 -3.5,1.4 -1.8,1.8 -.7,-.5 2.1,-2.3 1.8,-.7 .5,-.9 -2.9,-.1 -.7,-.8 .8,-2 -.9,-1.8 h -.6 l -2.4,1.3 -1.9,2.6 .3,1.7 3.3,3.4 1.3,.3 v .8 l -2.3,1.6 -4.9,4 -4,3.9 -3.2,1.4 -5,3 -3.7,2 -4.5,1.9 -4.1,2.5 3.2,-3 v -1.1 l .6,-.8 -.2,-1.8 -1.5,-.1 -1.1,1.5 -2.6,1.3 -1.8,-1.2 -.3,-1.7 h -1.5 l .8,2.2 1.4,.7 1.2,.9 1.8,1.6 -.7,.8 -3.9,1.7 -1.7,.1 -1.2,-1.2 -.5,2.1 .5,1.1 -2.7,2 -1.5,.2 -.8,.7 -.4,1.7 -1.8,3.3 -1.6,.7 -1.6,-.6 -1.8,1.1 .3,1.4 1.3,.8 1,.8 -1.8,3.5 -.3,2.8 -1,1.7 -1.4,1 -2.9,.4 1.8,.6 1.9,-.6 -.4,3.2 -1.1,-.1 .2,1.2 .3,1.4 -1.3,.9 v 3.1 l 1.6,1.4 .6,3.1 -.4,2.2 -1,.4 .4,1.5 1.1,.4 .8,1.7 v 2.6 l 1.1,2.1 2.2,2.6 -.1,.7 -2.2,-.2 -1.6,1.4 .2,1.4 -.9,-.3 -1.4,-.2 -3.4,-3.7 -2.3,-.6 h -7.1 l -2.8,-.8 -3.6,-3 -1.7,-1 -2.1,.1 -3.2,-2.6 -5.4,-1.6 v -1.3 l -1.4,-1.8 -.9,-4.7 -1.1,-1.7 -1.7,-1.4 v -1.6 l -1.4,-.6 .6,-2.6 -.3,-2.2 -1.3,-1.4 .7,-3 -.8,-3.2 -1.7,-1.4 h -1.1 l -4,-3.5 .1,-1.9 -.8,-1.7 -.8,-.2 -.9,-2.4 -2,-1.6 -2.9,-2.5 -.2,-2.1 -1,-.7 .2,-1.6 .5,-.7 -1.4,-1.5 .1,-.7 -2,-2.2 .1,-2.1 -2.7,-4.9 -.1,-1.7 -1.8,-3.1 -5.1,-4.8 v -1.1 l -3.3,-1.7 -.1,-1.8 -1.2,-.4 v -.7 l -.8,-.2 -2.1,-2.8 h -.8 l -.7,-.6 -1.3,1.1 h -2.2 l -2.6,-1.1 h -4.6 l -4.2,-2.1 -1.3,1.9 -2.2,-.6 -3.3,1.2 -1.7,2.8 -2,3.2 -1.1,4.4 -1.4,1.2 -1.1,.1 -.9,1.6 -1.3,.6 -.1,1.8 -2.9,.1 -1.8,-1.5 h -1 l -2,-2.9 -3.6,-.5 -1.7,-2.3 -1.3,-.2 -2.1,-.8 -3.4,-3.4 .2,-.8 -1.6,-1.2 -1,-.1 -3.4,-3.1 -.1,-2 -2.3,-4 .2,-1.6 -.7,-1.3 .8,-1.5 -.1,-2.4 -2.6,-4.1 -.6,-4.2 -1.6,-1.6 v -1 l -1.2,-.2 -.7,-1.1 -2.4,-1.7 -.9,-.1 -1.9,-1.6 v -1.1 l -2.9,-1.8 -.6,-2.1 -2.6,-2.3 -3.2,-4.4 -3,-1.3 -2.1,-1.8 .2,-1.2 -1.3,-1.4 -1.7,-3.7 -2.4,-1 z m 174.9,138.3 .8,.1 -.6,-4.8 -3.5,-12.3 -.2,-8.1 4.9,-10.5 6.1,-8.2 7.2,-5.1 v -.7 h -.8 l -2.6,1 -3.6,2.3 -.7,1.5 -8.2,11.6 -2.8,7.9 v 8.8 l 3.6,12 z"><title>Texas (TX)</title></path><path id="state-ut" class="us-state-path" data-code="UT" data-name="Utah" data-region="Mountain" data-providers="16+" d="m 233.2,217.9 3.3,-21.9 -47.9,-8.2 -21,109 46.2,8.2 40,6 11.5,-88.3 z"><title>Utah (UT)</title></path><path id="state-vt" class="us-state-path" data-code="VT" data-name="Vermont" data-region="Northeast" data-providers="6+" d="m 859.1,102.4 -1.1,3.5 2.1,2.8 -.4,1.7 .1,1.3 -1.1,2.1 -1.4,.4 -.6,1.3 -2.1,1 -.7,1.5 1.4,3.4 -.5,2.5 .5,1.5 -1,1.9 .4,1.9 -1.3,1.9 .2,2.2 -.7,1.1 .7,4.5 .7,1.5 -.5,2.6 .9,1.8 -.2,2.5 -.5,1.3 -.1,1.4 2.1,2.6 -12.4,2.7 -1.1,-1 .5,-2 -3,-14.2 -1.9,-1.5 -.9,1.6 -.9,-2.2 .8,-1.8 -3.1,-6.7 .3,-3.8 .4,-1 -.6,-2 .4,-2.2 -2.2,-2.3 -.5,-3.2 .4,-1.5 -1.4,-.9 .6,-1.9 -.8,-1.7 27.3,-6.9 z"><title>Vermont (VT)</title></path><path id="state-va" class="us-state-path" data-code="VA" data-name="Virginia" data-region="Mid-Atlantic" data-providers="29+" d="m 834.7,265.4 -1.1,2.8 .5,1.1 .4,-1.1 .8,-3.1 z m -34.6,-7 -.7,-1 1,-.1 1,-.9 .4,-1.8 -.2,-.5 .1,-.5 -.3,-.7 -.6,-.5 -.4,-.1 -.5,-.4 -.6,-.6 h -1 l -.6,-.1 -.4,-.4 .1,-.5 -1.7,-.6 -.8,.3 -1.2,-.1 -.7,-.7 -.5,-.2 -.2,-.7 .6,-.8 v -.9 l -1.2,-.2 -1,-.9 -.9,.1 -1.6,-.3 -.4,.7 -.4,1.6 -.5,2.3 -10,-5.2 -.2,.9 .9,1.6 -.8,2.3 .1,2.9 -1.2,.8 -.5,2.1 -.9,.8 -1.4,1.8 -.9,.8 -1,2.5 -2.4,-1.1 -2.3,8.5 -1.3,1.6 -2.8,-.5 -1.3,-1.9 -2.3,-.7 -.1,4.7 -1.4,1.7 .4,1.5 -2.1,2.2 .4,1.9 -3.7,6.3 -1,3.3 1.5,1.2 -1.5,1.9 .1,1.4 -2.3,2 -.7,-1.1 -4.3,3.1 -1.5,-1 -.6,1.4 .8,.5 -.5,.9 -5.5,2.4 -3,-1.8 -.8,1.7 -1.9,1.8 -2.3,.1 -4.4,-2.3 -.1,-1.5 -1.5,-.7 .8,-1.2 -.7,-.6 -4.9,6.6 -2.9,1 -3,3 -.4,2.2 -2.1,1.3 -.1,1.7 -1.4,1.4 -1.8,.5 -.5,1.9 -1,.4 -6.9,4.2 28.9,-3.3 .2,-1 4.6,-.5 -.3,.7 29.4,-3.5 39.4,-7.3 29.1,-6.1 -.6,-1.2 .4,-.1 .9,.9 -.1,-1.4 -.3,-1.9 1.6,1.2 .9,2.1 v -1.3 l -3.4,-5.5 v -1.2 l -.7,-.8 -1.3,.7 .5,1.4 h -.8 l -.4,-1 -.6,.9 -.9,-1.1 -2.1,-.1 -.2,.7 1.5,2.1 -1.4,-.7 -.5,-1 -.4,.8 -.8,.1 -1.5,1.7 .3,-1.6 v -1.4 l -1.5,-.7 -1.8,-.5 -.2,-1.7 -.6,-1.3 -.6,1.1 -1.7,-1 -2,.3 .2,-.9 1.5,-.2 .9,.5 1.7,-.8 .9,.4 .5,1 v .7 l 1.9,.4 .3,.9 .9,.4 .9,1.2 1.4,-1.6 h .6 l -.1,-2.1 -1.3,1 -.6,-.9 1.5,-.2 -1.2,-.9 -1.2,.6 -.1,-1.7 -1.7,.2 -2.2,-1.1 -1.8,-2.2 3.6,2.2 .9,.3 1.7,-.8 -1.7,-.9 .6,-.6 -1,-.5 .8,-.2 -.3,-.9 1.1,.9 .4,-.8 .4,1.3 1.2,.8 .6,-.5 -.5,-.6 -.1,-2.5 -1.1,-.1 -1.6,-.8 .9,-1.1 -2,-.1 -.4,-.5 -1.4,.6 -1.4,-.8 -.5,-1.2 -2.1,-1.2 -2.1,-1.8 -2.2,-1.9 3,1.3 .9,1.2 2.1,.7 2.3,2.5 .2,-1.7 .6,1.3 2.3,.5 v -4 l -.8,-1.1 1.1,.4 .1,-1.6 -3.1,-1.4 -1.6,-.2 -1.3,-.2 .3,-1.2 -1.5,-.3 -.1,-.6 h -1.8 l -.2,.8 -.7,-1 h -2.7 l -1,-.4 -.2,-1 -1.2,-.6 -.4,-1.5 -.6,-.4 -.7,1.1 -.9,.2 -.9,.7 h -1.5 l -.9,-1.3 .4,-3.1 .5,-2.4 .6,.5 z m 21.9,11.6 .9,-.1 0,-.6 -.8,.1 z m 7.5,14.2 -1,2.7 1.2,-1.3 z m -1.8,-15.3 .7,.3 -.2,1.9 -.5,-.5 -1.3,1 1,.4 -1.8,4.4 .1,8.1 1.9,3.1 .5,-1.5 .4,-2.7 -.3,-2.3 .7,-.9 -.2,-1.4 1.2,-.6 -.6,-.5 .5,-.7 .8,1.1 -.2,1.1 -.4,3.9 1.1,-2.2 .4,-3.1 .1,-3 -.3,-2 .6,-2.3 1.1,-1.8 .1,-2.2 .3,-.9 -4.6,1.6 -.7,.8 z"><title>Virginia (VA)</title></path><path id="state-wa" class="us-state-path" data-code="WA" data-name="Washington" data-region="Northwest" data-providers="31+" d="m 161.9,83.6 .7,4 -1.1,4.3 -30.3,-7.3 -2.8,1 -5.4,-.9 -1.8,-.9 -1.5,1.2 -3.3,-.4 -4.5,.5 -.9,.7 -4.2,-.4 -.8,-1.6 -1.2,-.2 -4.4,1.3 -1.6,-1.1 -2.2,.8 -.2,-1.8 -2.3,-1.2 -1.5,-.2 -1,-1.1 -3,.3 -1.2,-.8 h -1.2 l -1.2,.9 -5.5,.7 -6.6,-4.2 1.1,-5.6 -.4,-4.1 -3.2,-3.7 -3.7,.1 -.4,-1.1 .4,-1.2 -.7,-.8 -1,.1 -2.1,-1.5 -1.2,.4 -2,-.1 -.7,-1.5 -1.6,-.3 2.5,-7.5 -.7,6 .5,.5 v -2 l .8,-.2 1.1,2.3 -.5,-2.2 1.2,-4.2 1.8,.4 -1.1,-2 -1,.3 -1.5,-.4 .2,-4.2 .2,1.5 .9,.5 .6,-1.6 h 3.2 l -2.2,-1.2 -1.7,-1.9 -1.4,1.6 1.2,-3.1 -.3,-4.6 -.2,-3.6 .9,-6.1 -.5,-2 -1.4,-2.1 .1,-4 .4,-2.7 2,-2.3 -.7,-1.4 .2,-.6 .9,.1 7.8,7.6 4.7,1.9 5.1,2.5 3.2,-.1 .2,3 1,-1.6 h .7 l .6,2.7 .5,-2.6 1.4,-.2 .5,.7 -1.1,.6 .1,1.6 .7,-1.5 h 1.1 l -.4,2.6 -1.1,-.8 .4,1.4 -.1,1.5 -.8,.7 -2.5,2.9 1.2,-3.4 -1.6,.4 -.4,2.1 -3.8,2.8 -.4,1 -2.1,2.2 -.1,1 h 2.2 l 2.4,-.2 .5,-.9 -3.9,.5 v -.6 l 2.6,-2.8 1.8,-.8 1.9,-.2 1,-1.6 3,-2.3 v -1.4 h 1.1 l .1,4 h -1.5 l -.6,.8 -1.1,-.9 .3,1.1 v 1.7 l -.7,.7 -.3,-1.6 -.8,.8 .7,.6 -.9,1.1 h 1.3 l .7,-.5 .1,2 -1,1.9 -.9,1 -.1,1.8 -1,-.2 -.2,-1.4 .9,-1.1 -.8,-.5 -.8,.7 -.7,2.2 -.8,.9 -.1,-2 .8,-1.1 -.2,-1.1 -1.2,1.2 .1,2.2 -.6,.4 -2.1,-.4 -1.3,1.2 2.2,-.6 -.2,2.2 1,-1.8 .4,1.4 .5,-1 .7,1.8 h .7 l .7,-.8 .6,-.1 2,-1.9 .2,-1.2 .8,.6 .3,.9 .7,-.3 .1,-1.2 h 1.3 l .2,-2.9 -.1,-2.7 .9,.3 -.7,-2.1 1.4,-.8 .2,-2.4 2.3,-2.2 1,.1 .3,-1.4 -1.2,-1.4 -.1,-3.5 -.8,.9 .7,2.9 -.6,.1 -.6,-1.9 -.6,-.5 .3,-2.3 1.8,-.1 .3,.7 .3,-1.6 -1.6,-1.7 -.6,-1.6 -.2,2 .9,1.1 -.7,.4 -1,-.8 -1.8,1.3 1.5,.5 .2,2.4 -.3,1.8 .9,-1.3 1.4,2.3 -.4,1.9 h -1.5 v -1.2 l -1.5,-1.2 .5,-3 -1.9,-2.6 2.7,-3 .6,-4.1 h .9 l 1.4,3.2 v -2.6 l 1.2,.3 v -3.3 l -.9,-.8 -1.2,2.5 -1,-3 1.3,-.1 -1.5,-4.9 1.9,-.6 25.4,7.5 31.7,8 23.6,5.5 z m -78.7,-39.4 h .5 l .1,.8 -.5,.3 .1,.6 -.7,.4 -.2,-.9 .5,-.4 z m 5,-4.3 -1.2,1.9 -.1,.8 .4,.2 .5,-.6 1.1,.1 z m -.4,-21.6 .5,.6 1.3,-.3 .2,-1 1.2,-1.8 -1,-.4 -.7,1.6 -.1,-1.6 -1.1,.2 -.7,1.4 z m 3.2,-5.5 .7,1.5 -.9,.2 -.8,.4 -.2,-2.4 z m -2.7,-1.6 -1.1,-.2 .5,1.4 z m -1,2.5 .8,.4 -.4,1.1 1.7,-.5 -.2,-2.2 -.9,-.2 z m -2.7,-.4 .3,2.7 1.6,1.3 .6,-1.9 -1.1,-2.2 z m 1.9,-1.1 -1.1,-1 -.9,.1 1.8,1.5 z m 3.2,-7 h -1.2 v .8 l 1.2,.6 z m -.9,32.5 .4,-2.7 h -1.1 l -.2,1.9 z"><title>Washington (WA)</title></path><path id="state-wv" class="us-state-path" data-code="WV" data-name="West Virginia" data-region="Mid-Atlantic" data-providers="10+" d="m 723.4,297.5 -.8,1.2 1.5,.7 .1,1.5 4.4,2.3 2.3,-.1 1.9,-1.8 .8,-1.7 3,1.8 5.5,-2.4 .5,-.9 -.8,-.5 .6,-1.4 1.5,1 4.3,-3.1 .7,1.1 2.3,-2 -.1,-1.4 1.5,-1.9 -1.5,-1.2 1,-3.3 3.7,-6.3 -.4,-1.9 2.1,-2.2 -.4,-1.5 1.4,-1.7 .1,-4.7 2.3,.7 1.3,1.9 2.8,.5 1.3,-1.6 2.3,-8.5 2.4,1.1 1,-2.5 .9,-.8 1.4,-1.8 .9,-.8 .5,-2.1 1.2,-.8 -.1,-2.9 .8,-2.3 -.9,-1.6 .2,-.9 10,5.2 .5,-2.3 .4,-1.6 .4,-.7 -.9,-.4 .2,-1.6 -1,-.5 -.2,-.7 h -.7 l -.8,-1.2 .2,-1 -2.6,.4 -2.2,-1.6 -1.4,.3 -.9,1.4 h -1.3 l -1.7,2.9 -3.3,.4 -1.9,-1 -2.6,3.8 -2.2,-.3 -3.1,3.9 -.9,1.6 -1.8,1.6 -1.7,-11.4 -17.4,2.9 -3.2,-19.7 -2.2,1.2 1.4,2.1 -.1,2.2 .6,2 -1.1,3.4 -.1,5.4 -1,3.6 .5,1.1 -.4,2.2 -1.1,.5 -2,3.3 -1.8,2 h -.6 l -1.8,1.7 -1.3,-1.2 -1.5,1.8 -.3,1.2 h -1.3 l -1.3,2.2 .1,2.1 -1,.5 1.4,1.1 v 1.9 l -1,.2 -.7,.8 -1,.5 -.6,-2.1 -1.6,-.5 -1,2.3 -.3,2.2 -1.1,1.3 1.3,3.6 -1.5,.8 -.4,3.5 h -1.5 l -3.2,1.4 -.1,1.1 .6,1 -.6,3.6 1.9,1.6 .8,1.1 1,.6 -.1,.9 4.4,5.6 h 1.4 l 1.5,1.8 1.2,.3 1.4,-.1 z"><title>West Virginia (WV)</title></path><path id="state-wi" class="us-state-path" data-code="WI" data-name="Wisconsin" data-region="Midwest" data-providers="20+" d="m 611,144 -2.9,.8 .2,2.3 -2.4,3.4 -.2,3.1 .6,.7 .8,-.7 .5,-1.6 2,-1.1 1.6,-4.2 3.5,-1.1 .8,-3.3 .7,-.9 .4,-2.1 1.8,-1.1 v -1.5 l 1,-.9 1.4,.1 v 2 l -1,.1 .5,1.2 -.7,2.2 -.6,.1 -1.2,4.5 -.7,.5 -2.8,7.2 -.3,4.2 .6,2 .1,1.3 -2.4,1.9 .3,1.9 -.9,3.1 .3,1.6 .4,3.7 -1.1,4.1 -1.5,5 1,1.5 -.3,.3 .8,1.7 -.5,1.1 1.1,.9 v 2.7 l 1.3,1.5 -.4,3 .3,4 -45.9,2.8 -1.3,-2.8 -3.3,-.7 -2.7,-1.5 -2,-5.5 .1,-2.5 1.6,-3.3 -.6,-1.1 -2.1,-1.6 -.2,-2.6 -1.1,-4.5 -.2,-3 -2.2,-3 -2.8,-.7 -5.2,-3.6 -.6,-3.3 -6.3,-3.1 -.2,-1.3 h -3.3 l -2.2,-2.6 -2,-1.3 .7,-5.1 -.9,-1.6 .5,-5.4 1,-1.8 -.3,-2.7 -1.2,-1.3 -1.8,-.3 v -1.7 l 2.8,-5.8 5.9,-3.9 -.4,-13 .9,.4 .6,-.5 .1,-1.1 .9,-.6 1.4,1.2 .7,-.1 h 2.6 l 6.8,-2.6 .3,-1 h 1.2 l .7,-1.2 .4,.8 1.8,-.9 1.8,-1.7 .3,.5 1,-1 2.2,1.6 -.8,1.6 -1.2,1.4 .5,1.5 -1.4,1.6 .4,.9 2.3,-1.1 v -1.4 l 3.3,1.9 1.9,.7 1.9,.7 3,3.8 17,3.8 1.4,1 4,.8 .7,.5 2.8,-.2 4.9,.8 1.4,1.5 -1,1 .8,.8 3.8,.7 1.2,1.2 .1,4.4 -1.3,2.8 2,.1 1,-.8 .9,.8 -1.1,3.1 1,1.6 1.2,.3 z m -49.5,-37.3 -.5,.1 -1.5,1.6 .2,.5 1.5,-.6 v -.6 l .9,-.3 z m 1.6,-1.1 -1,.3 -.2,.7 .9,-.1 z m -1.3,-1.6 -.2,.9 h 1.7 l .6,-.4 .1,-1 z m 2.8,-3 -.3,1.9 1.2,-.5 .1,-1.4 z m 58.3,31.9 -2,.3 -.4,1.3 1.3,1.7 z"><title>Wisconsin (WI)</title></path><path id="state-wy" class="us-state-path" data-code="WY" data-name="Wyoming" data-region="Mountain" data-providers="5+" d="m 355.3,143.7 -51,-5.3 -57.3,-7.9 -2,10.7 -8.5,54.8 -3.3,21.9 32.1,4.8 44.9,5.7 37.5,3.4 3.7,-44.2 z"><title>Wyoming (WY)</title></path><path id="state-dc" class="us-state-path" data-code="DC" data-name="District of Columbia" data-region="Mid-Atlantic" data-providers="10+" d="m 803.5,252 -2.6,-1.8 -1,1.7 .5,.4 .4,.1 .6,.5 .3,.7 -.1,.5 .2,.5 z"><title>District of Columbia (DC)</title></path>
  </g>
  
  <!-- State 2-Letter Abbreviation Labels -->
  <g class="us-labels-group" pointer-events="none">
    <text x="654" y="415" class="us-state-label state-label-al" data-code="AL" font-size="9.5" text-anchor="middle" dominant-baseline="central">AL</text><text x="120" y="500" class="us-state-label state-label-ak" data-code="AK" font-size="9.5" text-anchor="middle" dominant-baseline="central">AK</text><text x="195" y="365" class="us-state-label state-label-az" data-code="AZ" font-size="9.5" text-anchor="middle" dominant-baseline="central">AZ</text><text x="548" y="374" class="us-state-label state-label-ar" data-code="AR" font-size="9.5" text-anchor="middle" dominant-baseline="central">AR</text><text x="95" y="275" class="us-state-label state-label-ca" data-code="CA" font-size="9.5" text-anchor="middle" dominant-baseline="central">CA</text><text x="317" y="273" class="us-state-label state-label-co" data-code="CO" font-size="9.5" text-anchor="middle" dominant-baseline="central">CO</text><text x="858" y="178" class="us-state-label state-label-ct" data-code="CT" font-size="8" text-anchor="middle" dominant-baseline="central">CT</text><text x="828" y="240" class="us-state-label state-label-de" data-code="DE" font-size="8" text-anchor="middle" dominant-baseline="central">DE</text><text x="745" y="490" class="us-state-label state-label-fl" data-code="FL" font-size="9.5" text-anchor="middle" dominant-baseline="central">FL</text><text x="714" y="405" class="us-state-label state-label-ga" data-code="GA" font-size="9.5" text-anchor="middle" dominant-baseline="central">GA</text><text x="290" y="548" class="us-state-label state-label-hi" data-code="HI" font-size="9.5" text-anchor="middle" dominant-baseline="central">HI</text><text x="185" y="140" class="us-state-label state-label-id" data-code="ID" font-size="9.5" text-anchor="middle" dominant-baseline="central">ID</text><text x="590" y="260" class="us-state-label state-label-il" data-code="IL" font-size="9.5" text-anchor="middle" dominant-baseline="central">IL</text><text x="644" y="256" class="us-state-label state-label-in" data-code="IN" font-size="9.5" text-anchor="middle" dominant-baseline="central">IN</text><text x="523" y="215" class="us-state-label state-label-ia" data-code="IA" font-size="9.5" text-anchor="middle" dominant-baseline="central">IA</text><text x="439" y="291" class="us-state-label state-label-ks" data-code="KS" font-size="9.5" text-anchor="middle" dominant-baseline="central">KS</text><text x="665" y="301" class="us-state-label state-label-ky" data-code="KY" font-size="9.5" text-anchor="middle" dominant-baseline="central">KY</text><text x="566" y="456" class="us-state-label state-label-la" data-code="LA" font-size="9.5" text-anchor="middle" dominant-baseline="central">LA</text><text x="895" y="88" class="us-state-label state-label-me" data-code="ME" font-size="9.5" text-anchor="middle" dominant-baseline="central">ME</text><text x="796" y="245" class="us-state-label state-label-md" data-code="MD" font-size="8" text-anchor="middle" dominant-baseline="central">MD</text><text x="872" y="156" class="us-state-label state-label-ma" data-code="MA" font-size="8" text-anchor="middle" dominant-baseline="central">MA</text><text x="660" y="190" class="us-state-label state-label-mi" data-code="MI" font-size="9.5" text-anchor="middle" dominant-baseline="central">MI</text><text x="520" y="118" class="us-state-label state-label-mn" data-code="MN" font-size="9.5" text-anchor="middle" dominant-baseline="central">MN</text><text x="594" y="419" class="us-state-label state-label-ms" data-code="MS" font-size="9.5" text-anchor="middle" dominant-baseline="central">MS</text><text x="543" y="295" class="us-state-label state-label-mo" data-code="MO" font-size="9.5" text-anchor="middle" dominant-baseline="central">MO</text><text x="273" y="87" class="us-state-label state-label-mt" data-code="MT" font-size="9.5" text-anchor="middle" dominant-baseline="central">MT</text><text x="420" y="224" class="us-state-label state-label-ne" data-code="NE" font-size="9.5" text-anchor="middle" dominant-baseline="central">NE</text><text x="133" y="235" class="us-state-label state-label-nv" data-code="NV" font-size="9.5" text-anchor="middle" dominant-baseline="central">NV</text><text x="865" y="122" class="us-state-label state-label-nh" data-code="NH" font-size="8" text-anchor="middle" dominant-baseline="central">NH</text><text x="834" y="216" class="us-state-label state-label-nj" data-code="NJ" font-size="8" text-anchor="middle" dominant-baseline="central">NJ</text><text x="297" y="374" class="us-state-label state-label-nm" data-code="NM" font-size="9.5" text-anchor="middle" dominant-baseline="central">NM</text><text x="805" y="155" class="us-state-label state-label-ny" data-code="NY" font-size="9.5" text-anchor="middle" dominant-baseline="central">NY</text><text x="765" y="335" class="us-state-label state-label-nc" data-code="NC" font-size="9.5" text-anchor="middle" dominant-baseline="central">NC</text><text x="415" y="92" class="us-state-label state-label-nd" data-code="ND" font-size="9.5" text-anchor="middle" dominant-baseline="central">ND</text><text x="700" y="237" class="us-state-label state-label-oh" data-code="OH" font-size="9.5" text-anchor="middle" dominant-baseline="central">OH</text><text x="433" y="361" class="us-state-label state-label-ok" data-code="OK" font-size="9.5" text-anchor="middle" dominant-baseline="central">OK</text><text x="97" y="118" class="us-state-label state-label-or" data-code="OR" font-size="9.5" text-anchor="middle" dominant-baseline="central">OR</text><text x="782" y="210" class="us-state-label state-label-pa" data-code="PA" font-size="9.5" text-anchor="middle" dominant-baseline="central">PA</text><text x="878" y="172" class="us-state-label state-label-ri" data-code="RI" font-size="8" text-anchor="middle" dominant-baseline="central">RI</text><text x="752" y="380" class="us-state-label state-label-sc" data-code="SC" font-size="9.5" text-anchor="middle" dominant-baseline="central">SC</text><text x="413" y="164" class="us-state-label state-label-sd" data-code="SD" font-size="9.5" text-anchor="middle" dominant-baseline="central">SD</text><text x="657" y="342" class="us-state-label state-label-tn" data-code="TN" font-size="9.5" text-anchor="middle" dominant-baseline="central">TN</text><text x="410" y="445" class="us-state-label state-label-tx" data-code="TX" font-size="9.5" text-anchor="middle" dominant-baseline="central">TX</text><text x="216" y="249" class="us-state-label state-label-ut" data-code="UT" font-size="9.5" text-anchor="middle" dominant-baseline="central">UT</text><text x="842" y="126" class="us-state-label state-label-vt" data-code="VT" font-size="8" text-anchor="middle" dominant-baseline="central">VT</text><text x="768" y="283" class="us-state-label state-label-va" data-code="VA" font-size="9.5" text-anchor="middle" dominant-baseline="central">VA</text><text x="116" y="50" class="us-state-label state-label-wa" data-code="WA" font-size="9.5" text-anchor="middle" dominant-baseline="central">WA</text><text x="748" y="264" class="us-state-label state-label-wv" data-code="WV" font-size="9.5" text-anchor="middle" dominant-baseline="central">WV</text><text x="575" y="152" class="us-state-label state-label-wi" data-code="WI" font-size="9.5" text-anchor="middle" dominant-baseline="central">WI</text><text x="294" y="181" class="us-state-label state-label-wy" data-code="WY" font-size="9.5" text-anchor="middle" dominant-baseline="central">WY</text><text x="802" y="252" class="us-state-label state-label-dc" data-code="DC" font-size="8" text-anchor="middle" dominant-baseline="central">DC</text>
  </g>
  
  <!-- Key Hub Pulsing Radar Markers -->
  <!-- HQ: Orlando, FL -->
  <g class="us-map-hub hub-hq" transform="translate(834, 216)" data-bs-toggle="tooltip" title="Medinext National Operations HQ - Orlando, FL">
    <circle class="hub-radar-ring" r="14"></circle>
    <circle class="hub-radar-ring-2" r="8"></circle>
    <circle class="hub-center-dot" r="4.5"></circle>
  </g>
  
  <!-- Hub: Chicago, IL -->
  <g class="us-map-hub hub-midwest" transform="translate(595, 235)">
    <circle class="hub-radar-ring" r="12"></circle>
    <circle class="hub-center-dot" r="3.5"></circle>
  </g>
  
  <!-- Hub: Dallas / Houston, TX -->
  <g class="us-map-hub hub-south" transform="translate(425, 435)">
    <circle class="hub-radar-ring" r="12"></circle>
    <circle class="hub-center-dot" r="3.5"></circle>
  </g>
  
  <!-- Hub: Los Angeles, CA -->
  <g class="us-map-hub hub-west" transform="translate(90, 310)">
    <circle class="hub-radar-ring" r="12"></circle>
    <circle class="hub-center-dot" r="3.5"></circle>
  </g>
  
  <!-- Hub: Miami / Orlando, FL -->
  <g class="us-map-hub hub-southeast" transform="translate(760, 520)">
    <circle class="hub-radar-ring" r="12"></circle>
    <circle class="hub-center-dot" r="3.5"></circle>
  </g>
</svg>
                    </div>

                    <div class="us-map-hint text-center">
                        <i class="bi bi-cursor-fill"></i> Hover or tap any state to view local provider network
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- TESTIMONIALS SECTION (Matching Reference Design) -->
<!-- ============================================ -->
<section class="section testimonials-section" id="testimonials">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center" data-aos="fade-up">
            <p class="tm-eyebrow">What Our Clients Say</p>
            <h2 class="tm-heading">Healthcare Leaders Trust Us<br>With Their <span class="gradient-text">Revenue Cycle</span></h2>
        </div>

        <!-- Swiper Testimonial Showcase -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10 col-xl-9" data-aos="zoom-in" data-aos-delay="100">
                <div class="swiper swiper-testimonials-showcase">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide">
                            <div class="tm-showcase-wrapper">
                                <div class="tm-bg-circle-1"></div>
                                <div class="tm-bg-circle-2"></div>
                                
                                <!-- Top-Right Speech Bubble (Cyan/Blue Theme) -->
                                <div class="tm-quote-bubble tm-bubble-top-right">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true">
                                        <defs>
                                            <linearGradient id="tm-bubble-grad-1" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#0ea5e9" />
                                                <stop offset="100%" stop-color="#0284c7" />
                                            </linearGradient>
                                        </defs>
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <!-- Bottom-Left Speech Bubble (Cyan/Blue Theme) -->
                                <div class="tm-quote-bubble tm-bubble-bottom-left">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true" style="transform: rotate(180deg);">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <!-- Main White Box -->
                                <div class="tm-showcase-box">
                                    <h3 class="tm-showcase-title">Testimonial</h3>
                                    <p class="tm-showcase-quote">
                                        &ldquo;MEDINEXT SOLUTIONS transformed our billing process completely. Our claim acceptance rate jumped from 82% to 98% within three months. Their team handles complex surgical bundling with the kind of precision we couldn&rsquo;t find anywhere else.&rdquo;
                                    </p>
                                    <div class="tm-showcase-stars" aria-label="5 out of 5 stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <p class="tm-showcase-author">Dr. Rachel Thompson &mdash; Managing Partner, Orthopedic Surgery</p>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide">
                            <div class="tm-showcase-wrapper">
                                <div class="tm-bg-circle-1"></div>
                                <div class="tm-bg-circle-2"></div>
                                
                                <div class="tm-quote-bubble tm-bubble-top-right">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-quote-bubble tm-bubble-bottom-left">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true" style="transform: rotate(180deg);">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-showcase-box">
                                    <h3 class="tm-showcase-title">Testimonial</h3>
                                    <p class="tm-showcase-quote">
                                        &ldquo;Their cardiovascular billing specialists recovered over $200,000 in previously uncollected claims. The mastery of catheterization coding, angiography mapping, and modifier logic is simply unmatched.&rdquo;
                                    </p>
                                    <div class="tm-showcase-stars" aria-label="5 out of 5 stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <p class="tm-showcase-author">Dr. James Williams &mdash; Chief Medical Officer, Heart &amp; Vascular Center</p>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide">
                            <div class="tm-showcase-wrapper">
                                <div class="tm-bg-circle-1"></div>
                                <div class="tm-bg-circle-2"></div>
                                
                                <div class="tm-quote-bubble tm-bubble-top-right">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-quote-bubble tm-bubble-bottom-left">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true" style="transform: rotate(180deg);">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-showcase-box">
                                    <h3 class="tm-showcase-title">Testimonial</h3>
                                    <p class="tm-showcase-quote">
                                        &ldquo;Switching to MEDINEXT was the best decision for our multi-location dental practice. Insurance eligibility turnaround dropped from days to minutes with zero billing backlog and instant aging recovery.&rdquo;
                                    </p>
                                    <div class="tm-showcase-stars" aria-label="5 out of 5 stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <p class="tm-showcase-author">Dr. Amanda Patel, DDS &mdash; Founder, Family Dental Associates</p>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="swiper-slide">
                            <div class="tm-showcase-wrapper">
                                <div class="tm-bg-circle-1"></div>
                                <div class="tm-bg-circle-2"></div>
                                
                                <div class="tm-quote-bubble tm-bubble-top-right">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-quote-bubble tm-bubble-bottom-left">
                                    <svg viewBox="0 0 100 100" class="tm-bubble-svg" aria-hidden="true" style="transform: rotate(180deg);">
                                        <path d="M 50 8 C 26.8 8 8 26.8 8 50 C 8 73.2 26.8 92 50 92 C 59.5 92 68.3 88.8 75.4 83.5 C 78 93 72 98 67 100 C 84 98 94 84 92 68 C 92 68 92 68 92 50 C 92 26.8 73.2 8 50 8 Z" fill="url(#tm-bubble-grad-1)" />
                                    </svg>
                                </div>

                                <div class="tm-showcase-box">
                                    <h3 class="tm-showcase-title">Testimonial</h3>
                                    <p class="tm-showcase-quote">
                                        &ldquo;The 15-day average AR turnaround and 99.4% clean claim accuracy have transformed our practice cash flow. Having dedicated AAPC-certified specialists assigned to our accounts makes all the difference.&rdquo;
                                    </p>
                                    <div class="tm-showcase-stars" aria-label="5 out of 5 stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <p class="tm-showcase-author">Dr. Michael Chang, MD &mdash; Director, Multi-Specialty Health Network</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination Bullets & Navigation -->
                    <div class="tm-swiper-controls mt-4">
                        <button class="tm-swiper-btn tm-swiper-prev" aria-label="Previous Testimonial">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="swiper-pagination tm-swiper-pagination"></div>
                        <button class="tm-swiper-btn tm-swiper-next" aria-label="Next Testimonial">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tm-footer-line text-center mt-5" data-aos="fade-up">
            <p>Trusted by <strong>500+ healthcare practices</strong> across the United States</p>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- BLOG / NEWS & INSIGHTS CAROUSEL SECTION -->
<!-- ============================================ -->
<section class="section home-blog-insights-section" id="blog-insights">
    <div class="container">
        <!-- Section Header Row: Title on Left, View More on Right -->
        <div class="home-blog-header" data-aos="fade-up">
            <h2 class="home-blog-title">Dental &amp; Medical RCM News &amp; Insights</h2>
            <a href="<?php echo $baseUrl; ?>/blog/" class="btn-view-more-articles">
                VIEW MORE ARTICLES
            </a>
        </div>

        <!-- Swiper Carousel Container -->
        <div class="swiper swiper-blog-insights mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/dental-billing-services/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/dental-billing.jpg" alt="Dental Billing Best Practices for Faster Insurance Payments" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">Dental Billing Best Practices for Faster Insurance Payments</h3>
                            <p class="home-blog-card-desc">Even small dental billing errors can quickly turn into lost revenue, delayed payments, and unhappy patients. Learn key protocols for faster reimbursement.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Jan 21, 2026</span>
                                <span class="home-blog-category">Dental Billing &amp; Coding</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/dermatology-billing/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/dermatology-billing.avif" alt="Dermatology Billing: How to Avoid Revenue Loss" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">Dermatology Billing: How to Avoid Revenue Loss and Stay Audit-Ready</h3>
                            <p class="home-blog-card-desc">Optimize your dermatology medical billing with expert tips on Mohs coding, complex lesion excision, pathology bundling, and common denial triggers.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Jun 11, 2025</span>
                                <span class="home-blog-category">Specialty Billing</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/dental-insurance-verification/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/dental-insurance.jpg" alt="Your Dental Insurance Verification Guide & Checklist" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">Your Dental Insurance Verification Guide &amp; Checklist</h3>
                            <p class="home-blog-card-desc">Read our breakdown of everything you need to know about dental insurance verification &mdash; including breakdown of benefits, avoiding same-day write-offs, and pre-authorizations.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Jun 4, 2025</span>
                                <span class="home-blog-category">Dental Insurance Verification</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/blog/revenue-cycle-management-guide/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/blog-rcm-guide.jpg" alt="An End-to-End Approach to Revenue Cycle Management" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">An End-to-End Approach to Healthcare Revenue Cycle Management</h3>
                            <p class="home-blog-card-desc">Healthcare and dental providers rely on comprehensive Revenue Cycle Management to safeguard cash flow, reduce denials, and accelerate insurance collections.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">May 8, 2025</span>
                                <span class="home-blog-category">Medical RCM</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 5 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/blog/claim-denial-reasons-and-fixes/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/blog-denials.jpg" alt="Top 10 Claim Denial Reasons & How to Fix Them Fast" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">Top 10 Claim Denial Reasons &amp; How to Fix Them Fast</h3>
                            <p class="home-blog-card-desc">Stop losing revenue to preventable denials. Our billing experts break down the most common CO and PR denial codes with proven appeal strategies.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Jan 18, 2025</span>
                                <span class="home-blog-category">Denials &amp; Appeals</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 6 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/blog/medical-billing-outsourcing-guide/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/blog-outsourcing.jpg" alt="The Complete Guide to Medical Billing Outsourcing" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">The Complete Guide to Medical Billing Outsourcing in 2025</h3>
                            <p class="home-blog-card-desc">Everything you need to know before outsourcing your medical billing &mdash; cost analysis, vetting checklist, red flags, and transition timelines.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Feb 14, 2025</span>
                                <span class="home-blog-category">Outsourcing Guide</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 7 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/blog/behavioral-health-billing-guide/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/blog-behavioral.jpg" alt="Behavioral Health Billing: Mental Health Claims" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">Behavioral Health Billing: Complete Guide to Mental Health Claims</h3>
                            <p class="home-blog-card-desc">CPT codes, parity laws, telehealth billing rules, and denial prevention strategies for behavioral health and mental health practices.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Jan 30, 2025</span>
                                <span class="home-blog-category">Behavioral Health</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Slide 8 -->
                <div class="swiper-slide">
                    <a href="<?php echo $baseUrl; ?>/blog/inhouse-vs-outsourced-billing/" class="home-blog-card">
                        <div class="home-blog-card-thumb">
                            <img src="<?php echo $baseUrl; ?>/assets/images/content/blog-inhouse-vs-outsourced.jpg" alt="In-House vs Outsourced Medical Billing" loading="lazy">
                        </div>
                        <div class="home-blog-card-body">
                            <h3 class="home-blog-card-title">In-House vs Outsourced Medical Billing: True Cost Comparison</h3>
                            <p class="home-blog-card-desc">Side-by-side financial analysis of keeping billing internal vs. outsourcing: salary, software, training, denial rates, and net revenue impact.</p>
                            <div class="home-blog-card-meta">
                                <span class="home-blog-date">Dec 22, 2024</span>
                                <span class="home-blog-category">Cost Analysis</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Custom Scrollbar & Nav Track matching screenshot -->
            <div class="home-blog-slider-footer mt-4">
                <button class="blog-swiper-arrow blog-swiper-prev" aria-label="Previous articles">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <div class="blog-swiper-scrollbar-track">
                    <div class="swiper-scrollbar blog-swiper-scrollbar"></div>
                </div>
                <button class="blog-swiper-arrow blog-swiper-next" aria-label="Next articles">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- CTA BANNER SECTION -->
<!-- ============================================ -->
<section class="section cta-section" id="cta">
    <canvas id="cta-color-panels-canvas" class="cta-shader-bg"></canvas>
    <div class="cta-overlay-layer"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="cta-wrapper" data-aos="fade-up">
            <div class="cta-content">
                <h2 class="cta-title">
                    Ready to <span class="cta-highlight">Maximize Your Practice Revenue?</span>
                </h2>
                <p class="cta-text">
                    Let our expert billing team handle the paperwork while you focus on patient care.
                </p>
                <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg cta-btn">
                    Schedule a Free Consultation
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SCHEMA 3 ? FAQPage -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://medinextsolutions.com/medical-billing-services/#faq",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How much does outsourced medical billing cost?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Outsourced medical billing with MEDINEXT SOLUTIONS typically costs a small percentage of your net collections, rather than a flat exorbitant fee. Because we only get paid when your practice gets paid, we are incentivized to secure maximum reimbursements. By leveraging our 98% clean claim rate and avoiding long-term contracts, our clients historically experience a 30% average revenue increase, making our full-service RCM an extremely profitable investment. Discover how affordable expert medical billing can be by requesting your free practice audit today."
      }
    },
    {
      "@type": "Question",
      "name": "What is your clean claim acceptance rate?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "MEDINEXT SOLUTIONS operates with an industry-leading 98% clean claim acceptance rate on the first pass. We achieve this exceptional accuracy by relying solely on AAPC-certified coding specialists and implementing robust, multi-tiered claim scrubbing workflows. By proactively resolving complex CPT mapping and modifier errors before submissions go out, we drastically limit rejections, ensuring a steady, uninterrupted cash flow with an average 15-day AR turnaround. Speak with our experts to learn how we can optimize your clean claim metrics."
      }
    },
    {
      "@type": "Question",
      "name": "How long does the onboarding process take?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "At MEDINEXT SOLUTIONS, the onboarding process is comprehensive yet highly streamlined, generally taking between two to four weeks. During this period, our dedicated onboarding specialists work directly with your team to integrate securely with your existing EMR/EHR, analyze historical AR data, and establish a 100% HIPAA-compliant data exchange protocol. We ensure zero disruption to your daily clinical schedule or cash flow during the transition. Schedule a free consultation to map out a seamless transition timeline for your practice."
      }
    },
    {
      "@type": "Question",
      "name": "Do you work with my EHR/EMR system?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, absolutely. MEDINEXT SOLUTIONS is EMR-agnostic, meaning our highly trained medical billing staff integrates directly into whatever Electronic Health Record system you currently rely on. Whether your practice utilizes Epic, eClinicalWorks, AdvancedMD, BrightTree, or a niche specialty platform, we adapt to your clinical workflow seamlessly. This prevents you from enduring costly software migrations and allows your staff to focus fully on patient care while our AAPC-certified coders optimize the backend. Contact us to verify your specific software integration."
      }
    },
    {
      "@type": "Question",
      "name": "Are your services HIPAA compliant?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Data security and patient privacy are our top priorities. MEDINEXT SOLUTIONS is strictly 100% HIPAA compliant across all operational levels. We utilize end-to-end encryption for all patient data exchanges and maintain secure, restricted-access infrastructure protocols. Furthermore, our entire staff of AAPC-certified professionals partakes in rigorous, recurring compliance training regarding the specialized handling of ePHI. We are fully prepared to execute a comprehensive Business Associate Agreement (BAA) with your facility. Reach out today for our full security prospectus."
      }
    },
    {
      "@type": "Question",
      "name": "What medical specialties do you support?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "With 10+ years of Revenue Cycle Management experience and over 500 providers served nationwide, we support a diverse array of medical specialties. Our deep expertise covers Therapy (ST &middot; PT &middot; OT), Cardiovascular, Pain Management, Oncology-Hematology, Dental Billing crossover, Behavioral Health, DME Billing on BrightTree, Neurology, and Radiology. Every specialty presents unique coding hurdles&mdash;our AAPC-certified professionals meticulously navigate these distinct guidelines to safeguard a 98% clean claim rate regardless of your field. Let us customize a billing strategy for your exact specialty."
      }
    },
    {
      "@type": "Question",
      "name": "How do you handle denied claims?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Denials cripple practice cash flow, which is why we offer aggressive Denial Management. When a rare denial occurs, our specialized accounts receivable team initiates an immediate root-cause investigation. We rapidly rectify coding errors, attach required clinical documentation, and submit robust appeals directly to payers. By prioritizing fast resolution workflows, we ensure our clients maintain a 15-day average AR turnaround time and successfully capture revenue that would otherwise be permanently lost. Stop writing off denials and contact us today."
      }
    },
    {
      "@type": "Question",
      "name": "What is revenue cycle management?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Revenue Cycle Management (RCM) encompasses the entire life cycle of a patient account, from initial appointment scheduling and insurance verification to precise coding, claim submission, payment posting, and aggressive denial management. By outsourcing your complete RCM process to MEDINEXT SOLUTIONS, we handle the exhausting administrative burden, drastically reducing overhead while fueling a 30% average revenue increase. We ensure 24/7 dedicated support and absolute financial transparency. Maximize your profitability by starting your free practice revenue audit right now."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need to sign a long-term contract?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No! MEDINEXT SOLUTIONS confidently operates without any restrictive long-term contracts. We strongly believe that our exceptional performance should retain our clients, not a binding legal document. Because our AAPC-certified coders consistently deliver a 98% clean claim rate and a 30% average revenue increase, we retain over 500 providers nationwide based purely on merit. You can cancel at any time if you are unsatisfied with our results. Experience risk-free medical billing outsourcing by speaking with our specialists today."
      }
    },
    {
      "@type": "Question",
      "name": "How is outsourced billing better than in-house billing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Outsourced billing eliminates the tremendous overhead required to hire, train, and maintain an in-house billing department, including expensive software licenses and employee benefits. By partnering with MEDINEXT SOLUTIONS, you gain an entire infrastructure of 10+ year experienced, AAPC-certified professionals dedicated to maximizing your payouts. Our aggressive denial management and 98% clean claim rate guarantee a 15-day AR turnaround, something in-house teams consistently struggle to achieve. Reclaim your time and boost profitability by outsourcing your revenue cycle management."
      }
    },
    {
      "@type": "Question",
      "name": "What reports will I receive and how often?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Financial transparency is critical to a healthy practice. MEDINEXT SOLUTIONS provides comprehensive, detailed reporting packages delivered on a monthly, and upon request, weekly basis. These bespoke analytics encompass key performance indicators such as total claims submitted, outstanding AR aging, clear denial reason breakdowns, and net collections. This granular visibility helps 500+ providers track their 30% average revenue increase and make educated, data-driven decisions regarding practice expansion. Get in touch to view sample reports tailored to your clinical specialty."
      }
    },
    {
      "@type": "Question",
      "name": "How do you ensure coding accuracy?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Coding accuracy directly links to your bottom line, which is why MEDINEXT SOLUTIONS strictly requires all coders to be AAPC-certified. Our experienced team relentlessly stays updated on ever-changing ICD-10, CPT, and HCPCS Level II compliance regulations. Before any claim is ever transmitted, it is meticulously validated through high-level scrubbing software and an internal quality assurance team. This dedicated precision is what cements our 98% first-pass clean claim acceptance rate across all our clients. Ensure coding compliance by partnering with us."
      }
    },
    {
      "@type": "Question",
      "name": "Can you help with provider credentialing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Absolutely. Provider Credentialing and insurance enrollment are notoriously complex processes that can halt cash flow if handled poorly. MEDINEXT SOLUTIONS fully manages CAQH profiles, PECOS updates, and multi-payer network contracts to secure seamless in-network status across Medicare, Medicaid, and commercial networks. We navigate the bureaucratic red tape so you can quickly onboard new clinicians without costly payment delays or administrative errors. Accelerate your practice expansion by letting our credentialing experts manage your enrollments."
      }
    },
    {
      "@type": "Question",
      "name": "What is your average turnaround time for claims?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Our highly optimized revenue cycle workflows ensure lightning-fast cash flow. Thanks to our meticulously scrubbed coding process and 98% first-pass clean claim acceptance rate, MEDINEXT SOLUTIONS consistently maintains a 15-day average Accounts Receivable (AR) turnaround time. Clean claims are accurately submitted within 24 to 48 hours of your patient encounters. This exceptional speed eliminates cash flow bottlenecks and enables the 30% average revenue increase our providers depend on. Streamline your AR today by consulting with our team."
      }
    },
    {
      "@type": "Question",
      "name": "How do I get started with MEDINEXT SOLUTIONS?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Starting with MEDINEXT SOLUTIONS is immediate, risk-free, and incredibly straightforward. You begin by requesting a complimentary, comprehensive practice revenue audit. With 10+ years of RCM analytics, our experts evaluate your current AR aging and coding practices to pinpoint exact areas of revenue leakage. Since we require zero long-term contracts and provide 24/7 dedicated support, transitioning is painless. Call us directly at +1-862-799-2199 or email info@medinextsolutions.com to schedule your free audit and maximize collections."
      }
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>



