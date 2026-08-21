<?php
/**
 * MEDINEXT SOLUTIONS - About Us Page
 * Professional Revenue Cycle Management for Dental & Medical Practices
 */

$pageTitle = 'About Us | MEDINEXT SOLUTIONS - Dental & Medical RCM Partner';
$pageDescription = 'Discover MEDINEXT SOLUTIONS: an end-to-end Revenue Cycle Management (RCM) partner for dental and medical practices across the United States. Revenue Built Right. Compliance Without Compromise.';
$pageKeywords = 'about MEDINEXT SOLUTIONS, medical billing company, dental billing services, revenue cycle management, RCM experts, healthcare billing partner, HIPAA compliant billing, AAPC certified coders';

require_once 'includes/header.php';
?>

<main id="main-content">

<!-- ============================================ -->
<!-- 1. PAGE HERO                                 -->
<!-- ============================================ -->
<section class="page-hero text-white position-relative py-5" style="background: linear-gradient(135deg, rgba(10, 38, 71, 0.92) 0%, rgba(0, 82, 204, 0.88) 60%, rgba(0, 201, 167, 0.82) 100%), url('<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-pixabay-263194.jpg') center/cover no-repeat; min-height: 480px;">
    <div class="container py-4 py-lg-5">
        <div class="page-hero-content text-center text-lg-start">
            <nav class="breadcrumb-nav mb-3" data-aos="fade-down" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/" class="text-white text-decoration-none"><i class="bi-house-fill me-1"></i> Home</a></li>
                    <li class="breadcrumb-item active text-white-50" aria-current="page">About Us</li>
                </ol>
            </nav>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white bg-opacity-20 text-white small" data-aos="fade-down" data-aos-delay="50">
                <i class="ph ph-shield-check text-warning"></i>
                <span class="fw-semibold">U.S. Healthcare Revenue Cycle Management Partner</span>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-hero-title text-white fw-bold display-5 mb-3" data-aos="fade-up">
                        Revenue Cycle Management That <span class="text-info">Works as Hard as Your Practice</span>
                    </h1>
                    <p class="page-hero-subtitle text-white-50 lead mb-4" style="color: #e2e8f0 !important; max-width: 780px;" data-aos="fade-up" data-aos-delay="100">
                        At Medinext Solutions, we believe healthcare providers should spend their time taking care of patients &mdash; not chasing insurance companies, correcting billing errors, or absorbing avoidable denials. We deliver full-spectrum RCM with clinical precision, transparent analytics, and measurable financial acceleration.
                    </p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3" data-aos="fade-up" data-aos-delay="150">
                        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-accent btn-lg fw-bold px-4 py-3 shadow">
                            <i class="ph ph-chart-line-up me-2"></i> Get Free Practice Audit
                        </a>
                        <a href="<?php echo $baseUrl; ?>/medical-billing-services/" class="btn btn-outline-light btn-lg fw-bold px-4 py-3">
                            <i class="ph ph-stethoscope me-2"></i> Our Medical Specialties
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-end" data-aos="fade-left" data-aos-delay="200">
                    <div class="p-4 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-25 text-start shadow-lg">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ph ph-check-circle fs-4"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold fs-6">AAPC &amp; AHIMA Certified</div>
                                <div class="text-white-50 small">100% Dedicated U.S. Teams</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-info text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ph ph-lock-key fs-4"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold fs-6">HIPAA &amp; HITECH Compliant</div>
                                <div class="text-white-50 small">256-Bit Encrypted Workflows</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ph ph-arrows-clockwise fs-4"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold fs-6">Zero Disruption Onboarding</div>
                                <div class="text-white-50 small">Plugs into your existing EHR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 2. STAT COUNTER BAR (4 METRICS)              -->
<!-- ============================================ -->
<section class="py-4 bg-white border-bottom shadow-sm position-relative" style="z-index: 2; margin-top: -30px; border-radius: 20px 20px 0 0;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                <div class="p-3 rounded-4 bg-light h-100 border border-light transition-all hover-shadow">
                    <div class="display-6 fw-bold text-primary mb-1">98.2%</div>
                    <div class="fw-bold text-dark fs-6 mb-1">Clean Claims Rate</div>
                    <div class="text-muted small">First-pass transmission accuracy</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="p-3 rounded-4 bg-light h-100 border border-light transition-all hover-shadow">
                    <div class="display-6 fw-bold text-primary mb-1">&lt;21 Days</div>
                    <div class="fw-bold text-dark fs-6 mb-1">Average Days in A/R</div>
                    <div class="text-muted small">Fast claim turnaround &amp; cash flow</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                <div class="p-3 rounded-4 bg-light h-100 border border-light transition-all hover-shadow">
                    <div class="display-6 fw-bold text-primary mb-1">$150M+</div>
                    <div class="fw-bold text-dark fs-6 mb-1">Practice Revenue Managed</div>
                    <div class="text-muted small">Annual collections optimized</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="p-3 rounded-4 bg-light h-100 border border-light transition-all hover-shadow">
                    <div class="display-6 fw-bold text-primary mb-1">500+</div>
                    <div class="fw-bold text-dark fs-6 mb-1">Healthcare Providers</div>
                    <div class="text-muted small">Practices &amp; clinics nationwide</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 3. ALTERNATING STORY SECTION 1               -->
<!-- "Who We Are & Our Healthcare Mission"       -->
<!-- Text Left, Image Right                       -->
<!-- ============================================ -->
<section class="section py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge mb-3">
                    <i class="ph ph-target"></i>
                    Our Healthcare Mission
                </span>
                <h2 class="section-title text-start mb-3">
                    Who We Are &amp; <span class="gradient-text">What Drives Us</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    We bridge the gap between clinical excellence and financial sustainability for healthcare practices.
                </p>
                <p class="text-muted mb-3">
                    Medinext Solutions was founded on a simple conviction: physicians and dental clinicians should be empowered to focus entirely on patient outcomes without the crushing overhead, complex payer rules, and cash flow instability caused by broken revenue cycles.
                </p>
                <p class="text-muted mb-4">
                    Unlike traditional billing clearinghouses that merely push electronic claims through an automated queue, we operate as a specialized RCM partner. We analyze the root causes of denials, optimize charge capture, appeal wrongful rejections with clinical documentation, and recover aged balances that other billers write off.
                </p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border">
                            <i class="ph ph-check-circle-fill text-primary fs-5"></i>
                            <span class="small fw-bold text-dark">Dedicated Practice Pods</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border">
                            <i class="ph ph-check-circle-fill text-primary fs-5"></i>
                            <span class="small fw-bold text-dark">Specialty-Specific Coders</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border">
                            <i class="ph ph-check-circle-fill text-primary fs-5"></i>
                            <span class="small fw-bold text-dark">Daily Claims Scrubbing</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border">
                            <i class="ph ph-check-circle-fill text-primary fs-5"></i>
                            <span class="small fw-bold text-dark">Real-Time BI Reporting</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <figure class="figure my-0 w-100 position-relative">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-thirdman-7659565.jpg"
                         alt="Medinext Solutions healthcare revenue cycle team reviewing clinical documentation with providers"
                         loading="lazy"
                         class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                         style="max-height: 440px;" />
                    <figcaption class="figure-caption text-muted text-center mt-2 small">
                        Collaborative revenue cycle management aligned directly with clinical workflows.
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 4. ALTERNATING STORY SECTION 2               -->
<!-- "The Triad Advantage"                        -->
<!-- Image Left, Text Right                       -->
<!-- ============================================ -->
<section class="section py-5 bg-light border-top border-bottom">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <figure class="figure my-0 w-100 position-relative">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-rdne-6129683.jpg"
                         alt="Certified medical billing specialist and clinical account manager analyzing real-time claims and patient telemetry data"
                         loading="lazy"
                         class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                         style="max-height: 440px;" />
                    <figcaption class="figure-caption text-muted text-center mt-2 small">
                        The Triad Advantage: Expert Human Oversight, Proven Process &amp; Predictive Analytics.
                    </figcaption>
                </figure>
            </div>
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <span class="section-badge mb-3">
                    <i class="ph ph-cube"></i>
                    The Triad Model
                </span>
                <h2 class="section-title text-start mb-3">
                    The Triad Advantage: <span class="gradient-text">People, Process &amp; Technology</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    Effective RCM requires more than automated billing software — it demands seasoned human expertise and rigorous execution.
                </p>
                <div class="d-flex flex-column gap-3 mt-4">
                    <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white shadow-sm border border-light">
                        <div class="text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #0369a1;">
                            <i class="ph ph-users fs-4"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">1. AAPC &amp; AHIMA Certified Coders</h3>
                            <p class="small text-muted mb-0">Our credentialed professionals perform line-by-line chart auditing, applying correct CPT, ICD-10, HCPCS, and CDT modifiers to eliminate under-coding and prevent audit triggers.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white shadow-sm border border-light">
                        <div class="text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #0369a1;">
                            <i class="ph ph-git-merge fs-4"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">2. Structured Rules-Based Workflow</h3>
                            <p class="small text-muted mb-0">Multi-tier scrubbing protocols intercept NCCI edits, frequency mismatches, and authorization gaps within 24 hours of encounter completion.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-white shadow-sm border border-light">
                        <div class="text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #0369a1;">
                            <i class="ph ph-headset fs-4"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">3. Dedicated Account Management</h3>
                            <p class="small text-muted mb-0">Every client is paired with a dedicated U.S.-based Account Manager who conducts monthly operational performance reviews and oversees ongoing payer contracting.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 5. ALTERNATING STORY SECTION 3               -->
<!-- "Enterprise Software & EHR Ecosystem"        -->
<!-- Text Left, Image Right                       -->
<!-- ============================================ -->
<section class="section py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge mb-3">
                    <i class="ph ph-cpu"></i>
                    Zero-Disruption Onboarding
                </span>
                <h2 class="section-title text-start mb-3">
                    Enterprise Software &amp; <span class="gradient-text">EHR Ecosystem Integration</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    We adapt to your software stack &mdash; you never have to migrate databases or retrain your clinic staff.
                </p>
                <p class="text-muted mb-3">
                    Our technical architects integrate directly into your existing Electronic Health Record (EHR), Practice Management Software (PMS), and clearinghouse channels. Whether your practice operates on a modern cloud platform or an established server environment, our team connects via secure API and remote gateway access.
                </p>
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark mb-2">Supported Medical EHR &amp; Billing Platforms:</h3>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Epic</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Cerner</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">eClinicalWorks</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">athenahealth</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Tebra / Kareo</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">AdvancedMD</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">NextGen</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">DrChrono</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">ModMed</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">CareCloud</span>
                    </div>
                    <h3 class="h6 fw-bold text-dark mb-2">Supported Dental PMS &amp; Clearinghouse Systems:</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Open Dental</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Dentrix</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Eaglesoft</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Curve Dental</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">Vyne Dental</span>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">DentalXChange</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <figure class="figure my-0 w-100 position-relative">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-tima-miroshnichenko-8376217.jpg"
                         alt="Healthcare physician reviewing digital diagnostic records and EHR software interface on tablet"
                         loading="lazy"
                         class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                         style="max-height: 440px;" />
                    <figcaption class="figure-caption text-muted text-center mt-2 small">
                        Real-time EHR chart synchronization and electronic claim scrubbing workflows.
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 6. ALTERNATING STORY SECTION 4               -->
<!-- "HIPAA Security, Compliance & Data Governance"-->
<!-- Image Left, Text Right                       -->
<!-- ============================================ -->
<section class="section py-5 bg-light border-top border-bottom">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <figure class="figure my-0 w-100 position-relative">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-stephentcandrews-9408868.jpg"
                         alt="Healthcare diagnostic monitoring and secure clinical data telemetry system ensuring HIPAA compliance"
                         loading="lazy"
                         class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                         style="max-height: 440px;" />
                    <figcaption class="figure-caption text-muted text-center mt-2 small">
                        Enterprise-grade data encryption, HIPAA compliance and rigorous clinical data governance.
                    </figcaption>
                </figure>
            </div>
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <span class="section-badge mb-3">
                    <i class="ph ph-shield-check"></i>
                    Compliance &amp; Governance
                </span>
                <h2 class="section-title text-start mb-3">
                    HIPAA Security, Compliance &amp; <span class="gradient-text">Data Governance</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    Protecting Protected Health Information (PHI) is fundamental to every aspect of our operations.
                </p>
                <p class="text-muted mb-3">
                    Medinext Solutions operates under strict compliance with HIPAA, HITECH, and Omnibus regulatory frameworks. We maintain comprehensive Business Associate Agreements (BAAs), SOC 2 Type II aligned data centers, and multi-factor authentication across all operational access points.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-white border shadow-sm d-flex align-items-start gap-3">
                            <i class="ph ph-lock-key text-primary fs-4 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">End-to-End 256-Bit AES Encryption</h3>
                                <p class="small text-muted mb-0">All patient identifiers, financial records, and EDI transactions are encrypted in transit (TLS 1.3) and at rest (AES-256).</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-white border shadow-sm d-flex align-items-start gap-3">
                            <i class="ph ph-identification-badge text-primary fs-4 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Role-Based Access Control (RBAC)</h3>
                                <p class="small text-muted mb-0">Strict least-privilege permissions ensure only assigned billing pod members have access to authorized practice data.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-white border shadow-sm d-flex align-items-start gap-3">
                            <i class="ph ph-file-text text-primary fs-4 mt-1"></i>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Continuous Compliance &amp; Auditing</h3>
                                <p class="small text-muted mb-0">Routine third-party penetration testing, automated log audits, and mandatory annual HIPAA recertification for all team members.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 7. LEADERSHIP & EXPERT TEAM SHOWCASE         -->
<!-- Visual cards with certified RCM architects   -->
<!-- ============================================ -->
<section class="section py-5 bg-white">
    <div class="container py-lg-4">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-badge mb-2">
                <i class="ph ph-users-three"></i>
                Certified RCM Architects
            </span>
            <h2 class="section-title">
                Leadership &amp; <span class="gradient-text">Expert RCM Team</span>
            </h2>
            <p class="section-subtitle mx-auto text-muted" style="max-width: 750px;">
                Guided by seasoned medical billing executives, certified coding auditors, and healthcare informatics leaders dedicated to maximizing practice profitability.
            </p>
        </div>

        <div class="row g-4">
            <!-- Leader 1 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-all text-center">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-gustavo-fring-4173251.jpg"
                         alt="Dr. Marcus Vance, MD, CPC - Chief Medical & Clinical Compliance Officer at Medinext Solutions"
                         loading="lazy"
                         class="card-img-top object-fit-cover"
                         style="height: 260px; width: 100%;" />
                    <div class="card-body p-4 text-start">
                        <div class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 mb-2 small">MD, CPC, CPMA</div>
                        <h3 class="h5 fw-bold text-dark mb-1">Dr. Marcus Vance</h3>
                        <p class="text-primary small fw-semibold mb-2">Chief Medical &amp; Clinical Officer</p>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            20+ years guiding clinical compliance, Medicare LCD guidelines, and physician chart documentation integrity.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Leader 2 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-all text-center">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-tima-miroshnichenko-5452204.jpg"
                         alt="Sarah Jenkins, RHIA, CPB - VP of Revenue Cycle Operations at Medinext Solutions"
                         loading="lazy"
                         class="card-img-top object-fit-cover"
                         style="height: 260px; width: 100%;" />
                    <div class="card-body p-4 text-start">
                        <div class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 mb-2 small">RHIA, CPB, CPC</div>
                        <h3 class="h5 fw-bold text-dark mb-1">Sarah Jenkins</h3>
                        <p class="text-primary small fw-semibold mb-2">VP of Revenue Operations</p>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            Specializes in large physician group billing operations, denial prevention architectures, and clearinghouse integrations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Leader 3 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-all text-center">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-gustavo-fring-4173244.jpg"
                         alt="David Chen, MBA, CMPE - Director of Practice Optimization at Medinext Solutions"
                         loading="lazy"
                         class="card-img-top object-fit-cover"
                         style="height: 260px; width: 100%;" />
                    <div class="card-body p-4 text-start">
                        <div class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 mb-2 small">MBA, CMPE</div>
                        <h3 class="h5 fw-bold text-dark mb-1">David Chen</h3>
                        <p class="text-primary small fw-semibold mb-2">Director of Practice Optimization</p>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            Expert in payer fee schedule negotiations, clinic overhead reduction, and multi-state provider credentialing.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Leader 4 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-all text-center">
                    <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-thirdman-7659868.jpg"
                         alt="Elena Rostova, CPC, CPCO - Lead Medical Coding Auditor at Medinext Solutions"
                         loading="lazy"
                         class="card-img-top object-fit-cover"
                         style="height: 260px; width: 100%;" />
                    <div class="card-body p-4 text-start">
                        <div class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 mb-2 small">CPC, CPCO, CRC</div>
                        <h3 class="h5 fw-bold text-dark mb-1">Elena Rostova</h3>
                        <p class="text-primary small fw-semibold mb-2">Lead Coding Auditor</p>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            AAPC fellow with deep mastery in surgical modifier utilization, complex infusion coding, and clinical appeal packages.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 8. FULL-SPECTRUM CAPABILITIES GRID           -->
<!-- ============================================ -->
<section class="section py-5 bg-light border-top border-bottom">
    <div class="container py-lg-4">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-badge mb-2">
                <i class="ph ph-list-checks"></i>
                Full-Spectrum Capabilities
            </span>
            <h2 class="section-title">
                Comprehensive <span class="gradient-text">Revenue Cycle Solutions</span>
            </h2>
            <p class="section-subtitle mx-auto text-muted" style="max-width: 750px;">
                End-to-end RCM execution addressing every critical touchpoint of the healthcare reimbursement lifecycle.
            </p>
        </div>

        <div class="row g-3 g-lg-4">
            <?php
            $capabilities = [
                ['icon' => 'ph ph-user-plus', 'title' => 'Patient Registration & Demographics', 'desc' => 'Accurate patient onboarding, insurance intake, and demographic data validation before service.'],
                ['icon' => 'ph ph-shield-check', 'title' => 'Eligibility & Benefits Verification', 'desc' => 'Real-Time Eligibility (RTE) scrubs confirming active coverage tiers, deductibles, and co-pays.'],
                ['icon' => 'ph ph-clipboard-text', 'title' => 'Patient Insurance Review', 'desc' => 'Evaluating primary, secondary, and tertiary payer hierarchies to avoid coordination of benefits delays.'],
                ['icon' => 'ph ph-file-plus', 'title' => 'Plan Creation & Attachment', 'desc' => 'Structuring fee schedules, authorization tracking, and payer plan mapping in your billing software.'],
                ['icon' => 'ph ph-code', 'title' => 'Coding & Charge Entry Support', 'desc' => 'AAPC & AHIMA compliant translation of clinical notes into exact ICD-10, CPT, and HCPCS Level II codes.'],
                ['icon' => 'ph ph-paper-plane-tilt', 'title' => 'Claim Preparation & Submission', 'desc' => 'Pre-transmission NCCI scrubbing and electronic ANSI 837 transmission to major clearinghouses.'],
                ['icon' => 'ph ph-clock-countdown', 'title' => 'Claim Status Follow-Up', 'desc' => 'Proactive clearinghouse tracking to intercept rejections before they turn into unpaid aging claims.'],
                ['icon' => 'ph ph-currency-dollar', 'title' => 'Payment & ERA Posting', 'desc' => 'Meticulous reconciliation of 835 ERAs, manual EOBs, co-pays, and line-item balance adjustments.'],
                ['icon' => 'ph ph-shield-warning', 'title' => 'Denial Management & Appeals', 'desc' => 'Root-cause denial analysis, modifier corrections, and evidence-backed clinical appeal packages.'],
                ['icon' => 'ph ph-arrows-counter-clockwise', 'title' => 'Claim Correction & Resubmission', 'desc' => 'Rapid turnaround on corrected claims (Frequency Type 7) within strict timely filing limits.'],
                ['icon' => 'ph ph-chart-line-up', 'title' => 'A/R Follow-Up & Aging Recovery', 'desc' => 'Persistent pursuit of 30-60-90+ day aging buckets to recover trapped practice revenue.'],
                ['icon' => 'ph ph-identification-badge', 'title' => 'Provider Credentialing & Enrollment', 'desc' => 'Complete CAQH profile maintenance, commercial payer contracting, and Medicare/Medicaid re-attestation.'],
                ['icon' => 'ph ph-presentation-chart', 'title' => 'Financial Reporting & Analytics', 'desc' => 'Specialty-specific monthly KPIs, clean claim percentages, payer velocity, and revenue forecasting.'],
                ['icon' => 'ph ph-monitor', 'title' => 'Customized Live Dashboards', 'desc' => 'Interactive web dashboards giving your executive team complete operational visibility 24/7.']
            ];

            foreach ($capabilities as $idx => $cap):
            ?>
                <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="<?php echo min(300, ($idx % 4) * 50); ?>">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white hover-shadow transition-all">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="<?php echo $cap['icon']; ?> fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0 text-dark"><?php echo $cap['title']; ?></h3>
                        </div>
                        <p class="small text-muted mb-0" style="line-height: 1.5;"><?php echo $cap['desc']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 9. CORE VALUES & WHY PRACTICES CHOOSE US     -->
<!-- ============================================ -->
<section class="section py-5 bg-white">
    <div class="container py-lg-4">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-badge mb-2">
                <i class="ph ph-star-fill"></i>
                The Medinext Advantage
            </span>
            <h2 class="section-title">
                Why Healthcare Practices <span class="gradient-text">Choose Medinext</span>
            </h2>
            <p class="section-subtitle mx-auto text-muted" style="max-width: 750px;">
                Built on accountability, clinical precision, and long-term partnership with medical and dental providers.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">End-to-End RCM Accountability</h3>
                    </div>
                    <p class="small text-muted mb-0">Full-cycle coverage from patient intake through claims, payments, denial resolution, A/R recovery, and credentialing.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">Specialized Medical &amp; Dental Teams</h3>
                    </div>
                    <p class="small text-muted mb-0">Specialized RCM tailored for both medical and dental environments without forcing either into a generic billing mold.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">Direct Technology Integration</h3>
                    </div>
                    <p class="small text-muted mb-0">Seamless connectivity across major medical EHRs, billing platforms, and dental practice management systems.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">Compliance-Focused Operations</h3>
                    </div>
                    <p class="small text-muted mb-0">HIPAA-conscious workflows, encrypted systems, strict access controls, and full BAA compliance support.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">Transparent Live Reporting</h3>
                    </div>
                    <p class="small text-muted mb-0">Clear reporting and dashboard visibility into real-time revenue-cycle activity and financial trends.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="350">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border border-light hover-shadow transition-all">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-check-circle text-primary fs-3"></i>
                        <h3 class="h6 fw-bold mb-0 text-dark">Scalable Practice Extension</h3>
                    </div>
                    <p class="small text-muted mb-0">Flexible support designed around your practice's specific operational needs without adding overhead.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 10. HIGH-IMPACT BOTTOM CTA BANNER            -->
<!-- ============================================ -->
<section class="section cta-section text-white py-5 position-relative" style="background: linear-gradient(135deg, rgba(10, 38, 71, 0.94) 0%, rgba(0, 82, 204, 0.90) 60%, rgba(0, 201, 167, 0.86) 100%), url('<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-thirdman-7659868.jpg') center/cover no-repeat;">
    <div class="container py-4 text-center position-relative" style="z-index: 2;">
        <div class="cta-content max-w-750 mx-auto" data-aos="fade-up">
            <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill mb-3 text-uppercase small tracking-wider">
                <i class="ph ph-sparkle me-1"></i> Partner with Medinext Solutions
            </span>
            <h2 class="cta-title text-white fw-bold display-5 mb-3">
                Revenue Built Right. <span class="text-info">Compliance Without Compromise.</span>
            </h2>
            <p class="cta-text text-white-50 lead mx-auto mb-4" style="color: #e2e8f0 !important; max-width: 720px;">
                Eliminate billing backlogs, lower denial rates, and capture every dollar your clinical providers earn. Request your comprehensive, confidential Practice Revenue Audit today.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-accent btn-lg fw-bold px-4 py-3 shadow">
                    <i class="ph ph-chart-line-up me-2"></i> Get Your Free Practice Audit
                </a>
                <a href="<?php echo $baseUrl; ?>/medical-billing-services/" class="btn btn-outline-light btn-lg fw-bold px-4 py-3">
                    <i class="ph ph-phone-call me-2"></i> View Specialty Services
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- STRUCTURED DATA (JSON-LD)                    -->
<!-- ============================================ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "AboutPage",
      "@id": "https://medinextsolutions.com/about/#aboutpage",
      "url": "https://medinextsolutions.com/about/",
      "name": "About MEDINEXT SOLUTIONS",
      "description": "End-to-end Revenue Cycle Management (RCM) services for dental and medical practices across the United States.",
      "mainEntity": {
        "@id": "https://medinextsolutions.com/#organization"
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": "https://medinextsolutions.com/about/#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://medinextsolutions.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "About Us",
          "item": "https://medinextsolutions.com/about/"
        }
      ]
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
