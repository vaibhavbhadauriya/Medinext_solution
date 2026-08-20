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
<!-- PAGE HERO                                    -->
<!-- ============================================ -->
<section class="page-hero">
    <div class="hero-mesh-gradient">
        <div class="mesh-orb mesh-orb-1"></div>
        <div class="mesh-orb mesh-orb-2"></div>
    </div>
    <div class="container">
        <div class="page-hero-content">
            <nav class="breadcrumb-nav" data-aos="fade-down">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/"><i class="bi-house-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active">About Us</li>
                </ol>
            </nav>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-white bg-opacity-20 text-white small" data-aos="fade-down" data-aos-delay="50">
                <i class="ph ph-shield-check text-warning"></i>
                <span>U.S. Healthcare Revenue Cycle Management</span>
            </div>
            <h1 class="page-hero-title" data-aos="fade-up">
                Revenue Cycle Management That <span class="gradient-text">Works as Hard as Your Practice</span>
            </h1>
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                At Medinext Solutions, we believe healthcare providers should spend their time taking care of patients &mdash; not chasing insurance companies, correcting billing issues, managing unpaid claims, or trying to understand where their revenue is getting stuck.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4" data-aos="fade-up" data-aos-delay="150">
                <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-accent btn-lg fw-bold">
                    <i class="ph ph-chart-line-up me-1"></i> Get Free Practice Audit
                </a>
                <a href="<?php echo $baseUrl; ?>/contact/" class="btn btn-outline-light btn-lg">
                    <i class="ph ph-chat-circle-dots me-1"></i> Speak With an RCM Specialist
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- MISSION STATEMENT PILL                       -->
<!-- ============================================ -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-10">
                <div class="p-3 rounded-4 bg-white shadow-sm border border-light d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                            <i class="ph ph-target fs-3"></i>
                        </div>
                        <div>
                            <span class="text-uppercase tracking-wider small fw-bold text-primary d-block">Our Single Goal</span>
                            <span class="fw-semibold text-dark fs-6">Help you capture the revenue you have earned &mdash; accurately, efficiently, and consistently.</span>
                        </div>
                    </div>
                    <a href="<?php echo $baseUrl; ?>/medical-billing-services/" class="btn btn-sm btn-outline-primary text-nowrap">Explore Capabilities &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- MORE THAN A BILLING COMPANY                   -->
<!-- ============================================ -->
<section class="section about-story">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); background: #000;">
                    <video autoplay muted loop playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        <source src="<?php echo $baseUrl; ?>/assets/videos/about-bg.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">
                    <i class="ph ph-arrows-clockwise"></i>
                    Connected Workflow
                </span>
                <h2 class="section-title text-start mb-3">
                    More Than a <span class="gradient-text">Billing Company</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    Revenue Cycle Management is not just about submitting claims.
                </p>
                <p class="text-muted mb-3">
                    A healthy revenue cycle starts before a claim ever reaches the payer. Patient information, insurance eligibility, benefits, documentation, coding, charge entry, claim preparation, and timely submission all play a role in whether a provider gets paid correctly and on time.
                </p>
                <p class="text-muted mb-3">
                    Once a claim is submitted, the work continues. Claims need to be monitored. Payments need to be posted accurately. Denials need to be investigated. Unpaid claims need to be followed up. Aging A/R needs consistent attention. Recurring problems need to be identified and addressed.
                </p>
                <p class="fw-bold text-primary mb-0">
                    That is where Medinext Solutions comes in. We manage the revenue cycle as a connected process rather than treating each billing task as an isolated activity.
                </p>
            </div>
        </div>

        <!-- Lifecycle Flowchart -->
        <div class="mt-5 pt-4" data-aos="fade-up">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h3 class="h6 fw-bold text-uppercase tracking-wider text-muted text-center mb-4">
                    The Medinext Connected Revenue Lifecycle
                </h3>
                <div class="row g-3 text-center align-items-center justify-content-center">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-identification-card text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">Accurate Info</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Demographics &amp; RTE</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-check-square-offset text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">Clean Claims</span>
                            <span class="text-muted" style="font-size: 0.75rem;">98% First-Pass Rate</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-paper-plane-tilt text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">Timely Filing</span>
                            <span class="text-muted" style="font-size: 0.75rem;">ANSI 837 Scrubbing</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-clock-countdown text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">A/R Follow-Up</span>
                            <span class="text-muted" style="font-size: 0.75rem;">&lt; 21 Day Turnaround</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-shield-warning text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">Denial Resolution</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Multi-Tier Appeals</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <i class="ph ph-chart-bar text-primary fs-3 mb-2 d-block"></i>
                            <span class="fw-bold small d-block text-dark">Clear Visibility</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Real-Time Reporting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- BUILT AROUND YOUR PRACTICE                    -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <span class="section-badge">
                    <i class="ph ph-sliders-horizontal"></i>
                    Tailored Approach
                </span>
                <h2 class="section-title text-start mb-3">
                    Built Around <span class="gradient-text">Your Practice</span>
                </h2>
                <p class="lead fw-semibold text-dark mb-3">
                    No two practices operate exactly the same way.
                </p>
                <p class="text-muted mb-3">
                    Your specialty, payer mix, patient volume, internal staffing, billing processes, and technology environment all affect how your revenue cycle should be managed. We therefore do not believe in a rigid, one-size-fits-all approach.
                </p>
                <p class="text-muted mb-4">
                    Medinext Solutions works alongside your existing team and adapts our workflows to your practice. Whether you need support with a specific part of the revenue cycle or comprehensive RCM management, our objective is to fit into your operation and make it more efficient &mdash; not create another system for your staff to manage.
                </p>
                <div class="p-3 rounded-3 bg-white border-start border-primary border-4 shadow-sm">
                    <p class="mb-0 fw-semibold text-dark">
                        We work as an <span class="text-primary">extension of your team</span>, providing the people, processes, follow-up, and reporting needed to keep your revenue cycle moving forward smoothly.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="text-primary fs-2 mb-3"><i class="ph ph-stethoscope"></i></div>
                            <h4 class="h6 fw-bold text-dark mb-2">Medical Specialties</h4>
                            <p class="small text-muted mb-0">Cardiology, Therapy, Behavioral Health, DME, Pain Mgmt, Oncology, Orthopedics, Surgery, and more.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="text-primary fs-2 mb-3"><i class="ph ph-tooth"></i></div>
                            <h4 class="h6 fw-bold text-dark mb-2">Dental &amp; Orthodontics</h4>
                            <p class="small text-muted mb-0">Medical-dental cross coding, CDT claim processing, eligibility, fee schedule maintenance, and DSOs.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="text-primary fs-2 mb-3"><i class="ph ph-users-three"></i></div>
                            <h4 class="h6 fw-bold text-dark mb-2">Solo &amp; Group Clinics</h4>
                            <p class="small text-muted mb-0">Customized capacity scaling from independent private practices to multi-provider health networks.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="text-primary fs-2 mb-3"><i class="ph ph-buildings"></i></div>
                            <h4 class="h6 fw-bold text-dark mb-2">Hospitals &amp; FQHCs</h4>
                            <p class="small text-muted mb-0">Enterprise-grade RCM, sliding fee scale optimization, PPS billing, and inpatient/outpatient support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- COMPREHENSIVE REVENUE CYCLE SUPPORT          -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-list-checks"></i>
                Full-Spectrum Capabilities
            </span>
            <h2 class="section-title">
                Comprehensive <span class="gradient-text">Revenue Cycle Support</span>
            </h2>
            <p class="section-subtitle mx-auto" style="max-width: 750px;">
                Our RCM capabilities span the complete financial workflow, eliminating administrative fragmentation and creating accountability at every stage.
            </p>
        </div>

        <div class="row g-3 g-lg-4 mt-2">
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
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="<?php echo $cap['icon']; ?> fs-5"></i>
                            </div>
                            <h4 class="h6 fw-bold mb-0 text-dark"><?php echo $cap['title']; ?></h4>
                        </div>
                        <p class="small text-muted mb-0" style="line-height: 1.5;"><?php echo $cap['desc']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- TECHNOLOGY THAT FITS YOUR WORKFLOW           -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-cpu"></i>
                Seamless Integration
            </span>
            <h2 class="section-title">
                Technology That <span class="gradient-text">Fits Your Workflow</span>
            </h2>
            <p class="section-subtitle mx-auto" style="max-width: 750px;">
                Technology should make RCM easier, not create another obstacle. Our team has experience working across major medical EHR, billing, and dental practice management systems.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <!-- Medical Platforms -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-hospital text-primary fs-3"></i>
                        <h3 class="h5 fw-bold text-dark mb-0">Medical EHR &amp; Billing Systems</h3>
                    </div>
                    <p class="small text-muted mb-3">
                        Our certified billers plug directly into your existing medical software without disrupting your clinic's daily appointments:
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $medPlatforms = ['eClinicalWorks', 'Tebra / Kareo', 'athenahealth', 'DrChrono', 'AdvancedMD', 'Office Ally', 'PracticeSuite', 'NextGen', 'CareCloud', 'Medisoft', 'OpenEMR', 'Epic', 'Cerner', 'ModMed'];
                        foreach ($medPlatforms as $plat):
                        ?>
                            <span class="badge bg-light text-dark border px-3 py-2 fw-semibold small"><?php echo $plat; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Dental Platforms -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ph ph-tooth text-primary fs-3"></i>
                        <h3 class="h5 fw-bold text-dark mb-0">Dental PMS &amp; Clearinghouse Systems</h3>
                    </div>
                    <p class="small text-muted mb-3">
                        Full workflow support across leading dental practice management and electronic claim software:
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $dentalPlatforms = ['Open Dental', 'Dentrix', 'Eaglesoft', 'Curve Dental / Curve Hero', 'Oryx', 'Vyne Dental', 'DentalXChange', 'Carestream Dental', 'CS SoftDent'];
                        foreach ($dentalPlatforms as $dPlat):
                        ?>
                            <span class="badge bg-light text-dark border px-3 py-2 fw-semibold small"><?php echo $dPlat; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-4 rounded-4 bg-white shadow-sm border text-center" data-aos="fade-up">
            <p class="mb-0 text-muted small">
                <strong class="text-dark">Cloud-Based, Server-Based, or Custom Setup?</strong> Our team focuses on building workflows that work within your existing environment so your practice never has to migrate data or retrain clinical staff.
            </p>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PEOPLE, PROCESS & TECHNOLOGY                 -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-cube"></i>
                Our Triad Model
            </span>
            <h2 class="section-title">
                People, Process &amp; <span class="gradient-text">Technology</span>
            </h2>
            <p class="section-subtitle mx-auto" style="max-width: 750px;">
                Effective RCM requires more than software. It requires people who understand the revenue cycle, processes that create accountability, and technology that provides visibility.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <!-- Pillar 1: People -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                    <div class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="ph ph-users fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-3">1. Dedicated People</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Our RCM professionals handle the day-to-day work that requires attention, follow-up, investigation, and skilled decision-making. No automated bot can replace an experienced coder advocating for your practice.
                    </p>
                </div>
            </div>

            <!-- Pillar 2: Process -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                    <div class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="ph ph-git-merge fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-3">2. Structured Process</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Our standardized standard operating procedures (SOPs) create consistency across the entire revenue cycle &mdash; ensuring clean claims, rapid denial turnarounds, and zero unaddressed aging accounts.
                    </p>
                </div>
            </div>

            <!-- Pillar 3: Technology -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                    <div class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="ph ph-chart-line-up fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-3">3. Actionable Tech</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Technology helps organize information, monitor live claim activity, and provide transparency. The result is a connected approach designed to identify issues early, take action, and keep revenue moving.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- COMPLIANCE & TRANSPARENT REPORTING           -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- Compliance -->
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge">
                    <i class="ph ph-shield-check"></i>
                    Security &amp; Standards
                </span>
                <h2 class="section-title text-start mb-3">
                    Compliance <span class="gradient-text">Without Compromise</span>
                </h2>
                <p class="text-muted mb-3">
                    Healthcare revenue cycle management involves sensitive patient and financial information. Protecting that information is fundamental to how an RCM partner should operate.
                </p>
                <p class="text-muted mb-3">
                    Medinext Solutions is a U.S.-registered healthcare RCM organization operating with HIPAA-focused processes, secure tools, access controls, and compliance-oriented workflows.
                </p>
                <ul class="list-unstyled small text-dark mb-4">
                    <li class="d-flex align-items-center mb-2"><i class="bi bi-check2-circle text-primary fs-5 me-2"></i> Comprehensive Business Associate Agreement (BAA) Support</li>
                    <li class="d-flex align-items-center mb-2"><i class="bi bi-check2-circle text-primary fs-5 me-2"></i> Role-Based Access Controls (RBAC) &amp; Encrypted Data Transfer</li>
                    <li class="d-flex align-items-center mb-2"><i class="bi bi-check2-circle text-primary fs-5 me-2"></i> Continuous Compliance Auditing &amp; Staff Training</li>
                </ul>
                <div class="p-3 bg-white rounded-3 border-start border-primary border-4 shadow-sm">
                    <p class="mb-0 small fw-bold text-dark">
                        "Compliance should not be an additional feature of RCM. It should be built into the process from the beginning."
                    </p>
                </div>
            </div>

            <!-- Reporting -->
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">
                    <i class="ph ph-chart-polar"></i>
                    Absolute Transparency
                </span>
                <h2 class="section-title text-start mb-3">
                    Visibility <span class="gradient-text">You Can Act On</span>
                </h2>
                <p class="text-muted mb-3">
                    You should not have to chase your billing company to find out what is happening with your revenue. Medinext Solutions provides reporting and dashboard visibility designed to help practices understand performance.
                </p>
                <div class="row g-2 mb-3">
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Claims Submitted &amp; Paid</div></div>
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Outstanding Aging A/R</div></div>
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Denial Root Causes</div></div>
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Net Collections Rate</div></div>
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Payer Turnaround Times</div></div>
                    <div class="col-6"><div class="p-2 rounded bg-white border small text-dark"><i class="ph ph-check text-success me-1"></i> Operational Trends</div></div>
                </div>
                <p class="small text-muted mb-0">
                    Because reporting is not valuable simply because it contains numbers &mdash; it is valuable when those numbers help you understand what is happening and what needs to happen next.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- FOCUSED ON REVENUE & TRUE PARTNER            -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    <div class="text-primary fs-1 mb-3"><i class="ph ph-coin-vertical"></i></div>
                    <h3 class="h4 fw-bold text-dark mb-3">Focused on the Revenue That Matters</h3>
                    <p class="text-muted mb-3">
                        Every unpaid claim represents more than a line on a report. It represents services already provided, work already performed, and revenue the practice has a right to collect.
                    </p>
                    <p class="text-muted mb-0">
                        That is why our focus extends beyond simple claim submission. We look at the entire revenue cycle to identify where revenue is being delayed, denied, underpaid, or left sitting in A/R &mdash; addressing issues at both the front and back end so practices build stronger, lasting processes.
                    </p>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    <div class="text-primary fs-1 mb-3"><i class="ph ph-handshake"></i></div>
                    <h3 class="h4 fw-bold text-dark mb-3">A Partner, Not Just a Vendor</h3>
                    <p class="text-muted mb-3">
                        The best RCM relationship should feel like an extension of your organization. Your internal team should have full visibility into what we are doing, understand performance, and know where attention is required.
                    </p>
                    <p class="text-muted mb-0">
                        At Medinext Solutions, we emphasize communication, accountability, transparent reporting, and consistent execution. We take responsibility for the functions assigned to us while working collaboratively with your staff.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- WHY MEDINEXT SOLUTIONS SUMMARY               -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-star-fill"></i>
                The Medinext Advantage
            </span>
            <h2 class="section-title">
                Why Healthcare Practices <span class="gradient-text">Choose Medinext</span>
            </h2>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> End-to-End RCM</h4>
                    <p class="small text-muted mb-0">Full-cycle coverage from patient intake through claims, payments, denial resolution, A/R recovery, and credentialing.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> Dental &amp; Medical Expertise</h4>
                    <p class="small text-muted mb-0">Specialized RCM tailored for both medical and dental environments without forcing either into a generic billing mold.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> Direct Technology Integration</h4>
                    <p class="small text-muted mb-0">Seamless connectivity across major medical EHRs, billing platforms, and dental practice management systems.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> Compliance-Focused Operations</h4>
                    <p class="small text-muted mb-0">HIPAA-conscious workflows, encrypted systems, strict access controls, and full BAA support.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> Transparent Live Reporting</h4>
                    <p class="small text-muted mb-0">Clear reporting and dashboard visibility into real-time revenue-cycle activity and financial trends.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="350">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="h6 fw-bold text-primary mb-2"><i class="ph ph-check-circle me-1"></i> Scalable &amp; Dedicated Extension</h4>
                    <p class="small text-muted mb-0">Flexible support designed around your practice's specific operational needs without adding overhead.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- OUR COMMITMENT & FINAL CTA                   -->
<!-- ============================================ -->
<section class="section cta-section">
    <canvas class="shader-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none; opacity: 1;"></canvas>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="cta-wrapper" data-aos="fade-up">
            <div class="glass-bend-layer"></div>
            <div class="glass-face-layer"></div>
            <div class="glass-edge-layer"></div>
            <div class="glass-content-layer cta-content text-center">
                <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill mb-3 text-uppercase small tracking-wider">
                    Our Commitment
                </span>
                <h2 class="cta-title">
                    Revenue Built Right. <span class="gradient-text">Compliance Without Compromise.</span>
                </h2>
                <p class="cta-text mx-auto" style="max-width: 750px;">
                    Make the revenue cycle easier to manage and harder for revenue to fall through the cracks. Because your practice should not have to choose between excellent patient care and a well-managed revenue cycle. You should have both.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                    <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="btn btn-accent btn-lg fw-bold">
                        <i class="ph ph-chart-line-up me-1"></i> Get Your Free Practice Audit
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact/" class="btn btn-outline-light btn-lg">
                        <i class="ph ph-calendar-check me-1"></i> Contact Us Today
                    </a>
                </div>
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
