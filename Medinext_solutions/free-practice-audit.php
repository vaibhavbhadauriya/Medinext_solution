<?php
/**
 * MEDINEXT SOLUTIONS - Dedicated Practice Revenue Audit & Cost Assessment Intake Page
 * Route: /free-practice-audit/ -> free-practice-audit.php
 * Milestone: M1 Form Frontend UI & Routing
 */

$pageTitle = 'Free Practice Revenue Audit & Cost Assessment | MEDINEXT SOLUTIONS';
$pageDescription = 'Claim your complimentary practice revenue audit. Our AAPC-certified billing analysts evaluate claims, denials, and aging AR to pinpoint revenue leaks and optimize cash flow.';
$pageKeywords = 'free practice audit, medical billing audit, revenue cycle management audit, RCM audit, AR aging analysis, denial management review, practice cost assessment, medical coding audit, physician billing review';

require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- 1. PAGE HERO SECTION (Dark Hero Theme)       -->
<!-- ============================================ -->
<section class="page-hero" style="background: linear-gradient(135deg, rgba(10, 38, 71, 0.92) 0%, rgba(0, 82, 204, 0.88) 60%, rgba(0, 201, 167, 0.82) 100%), url('<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-negativespace-48604.jpg') center/cover no-repeat;">
    <div class="hero-mesh-gradient" style="opacity: 0.35;">
        <div class="mesh-orb mesh-orb-1"></div>
        <div class="mesh-orb mesh-orb-2"></div>
    </div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="page-hero-content">
            <!-- Breadcrumb Navigation -->
            <nav class="breadcrumb-nav" data-aos="fade-down" aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $baseUrl; ?>/"><i class="bi bi-house-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Free Practice Audit</li>
                </ol>
            </nav>

            <!-- Trust Badge Pill -->
            <div class="hero-badge" data-aos="fade-down" data-aos-delay="50">
                <span class="hero-badge-dot"></span>
                <span>AAPC-Certified Analysts &middot; 100% HIPAA Compliant &middot; Zero Obligation</span>
            </div>

            <!-- Main Headline -->
            <h1 class="page-hero-title" data-aos="fade-up" data-aos-delay="100">
                Practice Revenue Audit &amp; <span class="gradient-text">Cost Assessment</span>
            </h1>

            <!-- Subtitle -->
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="150">
                Stop losing up to 20% of your clinical collections to undetected claim denials, aging accounts receivable, and coding leakage. Get an executive forensic billing diagnosis from certified RCM architects with zero risk or obligation.
            </p>

            <!-- Quick Trust Metric Bar -->
            <div class="hero-trust-strip d-flex flex-wrap justify-content-center gap-3 gap-md-4 mt-4" data-aos="fade-up" data-aos-delay="200">
                <div class="hero-trust-item d-flex align-items-center gap-2 text-white-50 small">
                    <i class="ph ph-check-circle text-primary fs-5"></i>
                    <span class="text-white fw-semibold">98% Clean Claim Rate</span>
                </div>
                <div class="hero-trust-item d-flex align-items-center gap-2 text-white-50 small">
                    <i class="ph ph-shield-check text-primary fs-5"></i>
                    <span class="text-white fw-semibold">100% HIPAA &amp; NDA Secured</span>
                </div>
                <div class="hero-trust-item d-flex align-items-center gap-2 text-white-50 small">
                    <i class="ph ph-clock text-primary fs-5"></i>
                    <span class="text-white fw-semibold">48-Hour Executive Delivery</span>
                </div>
                <div class="hero-trust-item d-flex align-items-center gap-2 text-white-50 small">
                    <i class="ph ph-currency-dollar-simple text-primary fs-5"></i>
                    <span class="text-white fw-semibold">$0 Upfront Cost</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 2. MAIN INTAKE & FORENSIC AUTHORITY GRID     -->
<!-- ============================================ -->
<section class="section audit-main-section py-5" id="audit-form-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            
            <!-- LEFT COLUMN: Authority & Forensic Analysis Overview -->
            <div class="col-lg-5 col-xl-5" data-aos="fade-right">
                <div class="audit-authority-panel">
                    <span class="section-badge mb-3">
                        <i class="ph ph-microscope"></i>
                        Forensic Practice Diagnostics
                    </span>
                    <h2 class="h2 fw-bold text-dark mb-3">
                        Stop Guessing. <span class="gradient-text">Start Measuring.</span>
                    </h2>
                    <p class="text-muted mb-4">
                        Most healthcare administrators and practice owners do not have full visibility into their exact first-pass clean claim rate, payer-specific aging buckets, or revenue lost to "unspecified" downcoding. Our forensic audit delivers a complete diagnostic X-ray of your financial health.
                    </p>

                    <!-- Inline RCM Audit & Coding Lifecycle Workflow Graphic -->
                    <div class="card p-3 rounded-4 bg-white border shadow-sm mb-4">
                        <h3 class="h6 fw-bold text-dark mb-2 text-uppercase tracking-wider">
                            <i class="ph ph-tree-structure me-1 text-primary"></i> 6-Stage Forensic Audit Lifecycle
                        </h3>
                        <figure class="figure mb-0 w-100 text-center">
                            <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/medical-coding-process-steps.png"
                                 alt="6-Stage Medical Coding and Revenue Cycle Management Audit Workflow"
                                 loading="lazy"
                                 class="img-fluid rounded-3 w-100 object-fit-contain"
                                 style="max-height: 280px;" />
                            <figcaption class="figure-caption text-muted text-center mt-2 small">
                                AAPC-certified review covering charge capture, denial mapping, and reimbursement velocity
                            </figcaption>
                        </figure>
                    </div>

                    <!-- 5-Point Forensic Breakdown -->
                    <div class="audit-features-list mb-4">
                        <div class="audit-feature-item d-flex gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                            <div class="feature-icon-box text-danger fs-3 flex-shrink-0">
                                <i class="ph ph-chart-line-down"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Root-Cause Denial Mapping</h3>
                                <p class="small text-muted mb-0">We translate your CO-4, CO-16, and CO-97 remark codes to isolate exactly where your revenue is breaking down before claims age out.</p>
                            </div>
                        </div>

                        <div class="audit-feature-item d-flex gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                            <div class="feature-icon-box fs-3 flex-shrink-0" style="color: #b45309;">
                                <i class="ph ph-magnifying-glass"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">E/M Coding Bell Curve Analysis</h3>
                                <p class="small text-muted mb-0">We benchmark your physician coding distribution against national CMS specialty averages to identify systematic undercoding.</p>
                            </div>
                        </div>

                        <div class="audit-feature-item d-flex gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                            <div class="feature-icon-box fs-3 flex-shrink-0" style="color: #0284c7;">
                                <i class="ph ph-hourglass-high"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Aging A/R Recovery Velocity</h3>
                                <p class="small text-muted mb-0">We profile claims past 60, 90, and 120 days across commercial payers to recover stalled balances before timely filing lapses.</p>
                            </div>
                        </div>

                        <div class="audit-feature-item d-flex gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                            <div class="feature-icon-box text-success fs-3 flex-shrink-0">
                                <i class="ph ph-files"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Pre-Authorization &amp; Payer Leakage</h3>
                                <p class="small text-muted mb-0">We audit CO-197 authorizations, eligibility workflows, and contract allowable underpayments across top commercial carriers.</p>
                            </div>
                        </div>

                        <div class="audit-feature-item d-flex gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                            <div class="feature-icon-box fs-3 flex-shrink-0" style="color: #0369a1;">
                                <i class="ph ph-currency-dollar-simple"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">Payer Fee Schedule Audit</h3>
                                <p class="small text-muted mb-2">We compare your actual payer remittances against your contracted fee schedules to uncover silent underpayments.</p>
                                <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/fee%20schedule.jpeg"
                                     alt="Commercial and Medicare Fee Schedule Underpayment Analysis"
                                     loading="lazy"
                                     class="img-fluid rounded-3 border w-100 shadow-sm mt-1"
                                     style="max-height: 180px; object-fit: cover;" />
                            </div>
                        </div>
                    </div>

                    <!-- 4-Step Audit Roadmap -->
                    <div class="audit-roadmap-card p-4 rounded-4 bg-light border mb-4">
                        <h3 class="h6 fw-bold text-dark mb-3 text-uppercase tracking-wider">
                            <i class="ph ph-git-commit me-1" style="color: #0369a1;"></i> The 4-Step Audit Process
                        </h3>
                        <div class="roadmap-timeline">
                            <div class="roadmap-step d-flex gap-3 mb-2">
                                <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #0369a1; width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">1</span>
                                <span class="small text-dark fw-medium"><strong>Submit Intake:</strong> Complete the 2-minute practice profile form.</span>
                            </div>
                            <div class="roadmap-step d-flex gap-3 mb-2">
                                <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #0369a1; width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">2</span>
                                <span class="small text-dark fw-medium"><strong>Discovery Briefing:</strong> 15-minute consultation with an RCM Architect.</span>
                            </div>
                            <div class="roadmap-step d-flex gap-3 mb-2">
                                <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #0369a1; width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">3</span>
                                <span class="small text-dark fw-medium"><strong>Sample Analysis:</strong> Forensic review of ERA and aging reports within 48 hours.</span>
                            </div>
                            <div class="roadmap-step d-flex gap-3">
                                <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #0369a1; width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">4</span>
                                <span class="small text-dark fw-medium"><strong>Strategy Review:</strong> Executive recovery roadmap and actionable cash plan.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Client Testimonial Pullquote -->
                    <div class="client-pullquote-card p-4 rounded-4 bg-white border shadow-sm mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2" style="color: #b45309;">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <blockquote class="blockquote small text-muted mb-2 fst-italic">
                            "MEDINEXT SOLUTIONS's free practice audit revealed that our staff was systematically ignoring secondary crossover claims and failing to appeal CO-16 denials. That single audit uncovered $140,000 in recoverable revenue we were about to write off."
                        </blockquote>
                        <cite class="d-block small fw-bold text-dark">&mdash; Michael D., Practice Administrator, Advanced Therapeutics</cite>
                    </div>

                    <!-- HIPAA & NDA Security Box -->
                    <div class="security-guarantee-box p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 d-flex align-items-center gap-3">
                        <i class="ph ph-lock-key display-6 flex-shrink-0" style="color: #0369a1;"></i>
                        <div>
                            <h4 class="h6 fw-bold mb-1" style="color: #0369a1;">100% HIPAA &amp; NDA Protected</h4>
                            <p class="small text-muted mb-0">All data exchanges are encrypted with 256-bit SSL. Full Business Associate Agreements (BAA) and mutual NDAs executed prior to data review.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: High-Conversion Practice Audit Intake Card -->
            <div class="col-lg-7 col-xl-7" data-aos="fade-left">
                <div class="card audit-form-card shadow-lg border-0 rounded-4 overflow-hidden" id="audit-form-card">
                    
                    <!-- Card Top Header -->
                    <div class="audit-card-header p-4 p-md-5 text-white" style="background: var(--gradient-hero);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill small">
                                <i class="ph ph-sparkle text-warning me-1"></i> Complimentary Analysis
                            </span>
                            <span class="small text-white-50">
                                <i class="ph ph-shield-check me-1"></i> 256-Bit SSL Encrypted
                            </span>
                        </div>
                        <h2 class="h3 fw-bold mb-1 text-white">Request Your Practice Revenue Audit</h2>
                        <p class="small text-white-50 mb-0">Fill out your operational metrics below. Our senior RCM architects will conduct your forensic analysis.</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5 bg-white position-relative">
                        
                        <!-- PHP Non-JS Fallback Messages -->
                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <div class="alert alert-success d-flex align-items-center gap-3 mb-4" role="alert">
                                <i class="ph ph-check-circle fs-3 text-success"></i>
                                <div>
                                    <h4 class="alert-heading h6 fw-bold mb-1">Audit Request Successfully Submitted!</h4>
                                    <p class="small mb-0">Thank you! Your practice metrics have been securely transferred to our AAPC-certified review board. An RCM architect will contact you within 24 hours.</p>
                                </div>
                            </div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger d-flex align-items-center gap-3 mb-4" role="alert">
                                <i class="ph ph-warning-circle fs-3 text-danger"></i>
                                <div>
                                    <h4 class="alert-heading h6 fw-bold mb-1">Submission Incomplete</h4>
                                    <p class="small mb-0">Please verify all required fields highlighted below and resubmit.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Dynamic Alert Banner for AJAX / Validation Feedback -->
                        <div id="auditFormAlert" class="alert alert-danger d-none d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ph ph-warning-circle fs-4 text-danger flex-shrink-0"></i>
                            <span class="alert-message small mb-0"></span>
                        </div>

                        <!-- Main Practice Audit Form -->
                        <form id="practice-audit-form" action="api/submit-audit-request.php" method="POST" class="needs-validation" novalidate>
                            
                            <!-- Security CSRF & Anti-Bot Hidden Fields -->
                            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="hidden" name="form_timestamp" id="form_timestamp" value="<?php echo time(); ?>">
                            
                            <!-- Anti-Bot Honeypot Traps (Visually and ARIA hidden) -->
                            <div class="visually-hidden" aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px; opacity: 0; pointer-events: none;">
                                <label for="website_hp">Website URL (leave blank)</label>
                                <input type="text" name="website_hp" id="website_hp" tabindex="-1" autocomplete="off">
                                <label for="audit_form_hp">Honeypot (leave blank)</label>
                                <input type="text" name="audit_form_hp" id="audit_form_hp" tabindex="-1" autocomplete="off">
                            </div>

                            <!-- ============================================ -->
                            <!-- SECTION 1: Practice & POC Identity           -->
                            <!-- ============================================ -->
                            <div class="form-section-group mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge rounded-pill px-2 py-1 small text-white" style="background-color: #0369a1;">1</span>
                                    <h3 class="h6 fw-bold text-dark mb-0 text-uppercase tracking-wider">Practice &amp; Contact Profile</h3>
                                </div>
                                <div class="row g-3">
                                    <!-- Practice / Facility Name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="practice_name">
                                                Practice / Clinic / Facility Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-buildings text-muted"></i></span>
                                                <input type="text" class="form-control bg-light border-start-0" id="practice_name" name="practice_name" placeholder="e.g. Advanced Orthopedic &amp; Spine Institute" required minlength="2" maxlength="150" autocomplete="organization" aria-required="true">
                                            </div>
                                            <div class="invalid-feedback">Please enter your practice or facility name (at least 2 characters).</div>
                                        </div>
                                    </div>

                                    <!-- Primary Contact Full Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="contact_name">
                                                Primary Contact Full Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-user text-muted"></i></span>
                                                <input type="text" class="form-control bg-light border-start-0" id="contact_name" name="contact_name" placeholder="e.g. Dr. Sarah Jenkins, MD" required minlength="2" maxlength="100" autocomplete="name" aria-required="true">
                                            </div>
                                            <div class="invalid-feedback">Please enter your full name.</div>
                                        </div>
                                    </div>

                                    <!-- Job Title / Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="job_title">
                                                Job Title / Practice Role <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-identification-badge text-muted"></i></span>
                                                <select class="form-select bg-light border-start-0" id="job_title" name="job_title" required aria-required="true">
                                                    <option value="" selected disabled>Select your role...</option>
                                                    <option value="Practice Owner / Physician / Clinician">Practice Owner / Physician / Clinician</option>
                                                    <option value="Practice Administrator / CEO">Practice Administrator / CEO</option>
                                                    <option value="Practice Manager / Office Manager">Practice Manager / Office Manager</option>
                                                    <option value="Billing Manager / RCM Director">Billing Manager / RCM Director</option>
                                                    <option value="Chief Financial Officer (CFO) / Controller">Chief Financial Officer (CFO) / Controller</option>
                                                    <option value="Medical Director / Lead Clinician">Medical Director / Lead Clinician</option>
                                                    <option value="Executive Director / Managing Partner">Executive Director / Managing Partner</option>
                                                    <option value="Other Decision Maker">Other Decision Maker</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">Please select your role.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================ -->
                            <!-- SECTION 2: Direct Contact & Location         -->
                            <!-- ============================================ -->
                            <div class="form-section-group mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge rounded-pill px-2 py-1 small text-white" style="background-color: #0369a1;">2</span>
                                    <h3 class="h6 fw-bold text-dark mb-0 text-uppercase tracking-wider">Direct Contact &amp; Physical Location</h3>
                                </div>
                                <div class="row g-3">
                                    <!-- Work Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="email">
                                                Work Email Address <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-envelope-simple text-muted"></i></span>
                                                <input type="email" class="form-control bg-light border-start-0" id="email" name="email" placeholder="e.g. s.jenkins@practice.com" required autocomplete="email" aria-required="true">
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid work email address.</div>
                                        </div>
                                    </div>

                                    <!-- Direct Phone Number -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="phone">
                                                Direct Phone Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-phone text-muted"></i></span>
                                                <input type="tel" class="form-control bg-light border-start-0 phone-mask" id="phone" name="phone" placeholder="(555) 000-0000" required autocomplete="tel" aria-required="true" maxlength="14">
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                                        </div>
                                    </div>

                                    <!-- Physical Street Address -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="street_address">
                                                Practice Street Address <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-map-pin text-muted"></i></span>
                                                <input type="text" class="form-control bg-light border-start-0" id="street_address" name="street_address" placeholder="e.g. 1317 Edgewater Dr, Suite 3520" required minlength="5" maxlength="255" autocomplete="street-address" aria-required="true">
                                            </div>
                                            <div class="invalid-feedback">Please enter your physical street address.</div>
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="city">
                                                City <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control bg-light" id="city" name="city" placeholder="Orlando" required minlength="2" maxlength="100" autocomplete="address-level2" aria-required="true">
                                            <div class="invalid-feedback">Please enter your city.</div>
                                        </div>
                                    </div>

                                    <!-- State -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="state">
                                                State <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select bg-light" id="state" name="state" required autocomplete="address-level1" aria-required="true">
                                                <option value="" selected disabled>Select State...</option>
                                                <option value="AL">Alabama (AL)</option>
                                                <option value="AK">Alaska (AK)</option>
                                                <option value="AZ">Arizona (AZ)</option>
                                                <option value="AR">Arkansas (AR)</option>
                                                <option value="CA">California (CA)</option>
                                                <option value="CO">Colorado (CO)</option>
                                                <option value="CT">Connecticut (CT)</option>
                                                <option value="DE">Delaware (DE)</option>
                                                <option value="DC">District of Columbia (DC)</option>
                                                <option value="FL">Florida (FL)</option>
                                                <option value="GA">Georgia (GA)</option>
                                                <option value="HI">Hawaii (HI)</option>
                                                <option value="ID">Idaho (ID)</option>
                                                <option value="IL">Illinois (IL)</option>
                                                <option value="IN">Indiana (IN)</option>
                                                <option value="IA">Iowa (IA)</option>
                                                <option value="KS">Kansas (KS)</option>
                                                <option value="KY">Kentucky (KY)</option>
                                                <option value="LA">Louisiana (LA)</option>
                                                <option value="ME">Maine (ME)</option>
                                                <option value="MD">Maryland (MD)</option>
                                                <option value="MA">Massachusetts (MA)</option>
                                                <option value="MI">Michigan (MI)</option>
                                                <option value="MN">Minnesota (MN)</option>
                                                <option value="MS">Mississippi (MS)</option>
                                                <option value="MO">Missouri (MO)</option>
                                                <option value="MT">Montana (MT)</option>
                                                <option value="NE">Nebraska (NE)</option>
                                                <option value="NV">Nevada (NV)</option>
                                                <option value="NH">New Hampshire (NH)</option>
                                                <option value="NJ">New Jersey (NJ)</option>
                                                <option value="NM">New Mexico (NM)</option>
                                                <option value="NY">New York (NY)</option>
                                                <option value="NC">North Carolina (NC)</option>
                                                <option value="ND">North Dakota (ND)</option>
                                                <option value="OH">Ohio (OH)</option>
                                                <option value="OK">Oklahoma (OK)</option>
                                                <option value="OR">Oregon (OR)</option>
                                                <option value="PA">Pennsylvania (PA)</option>
                                                <option value="PR">Puerto Rico (PR)</option>
                                                <option value="RI">Rhode Island (RI)</option>
                                                <option value="SC">South Carolina (SC)</option>
                                                <option value="SD">South Dakota (SD)</option>
                                                <option value="TN">Tennessee (TN)</option>
                                                <option value="TX">Texas (TX)</option>
                                                <option value="UT">Utah (UT)</option>
                                                <option value="VT">Vermont (VT)</option>
                                                <option value="VA">Virginia (VA)</option>
                                                <option value="WA">Washington (WA)</option>
                                                <option value="WV">West Virginia (WV)</option>
                                                <option value="WI">Wisconsin (WI)</option>
                                                <option value="WY">Wyoming (WY)</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your state.</div>
                                        </div>
                                    </div>

                                    <!-- ZIP Code -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="zip_code">
                                                ZIP Code <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control bg-light zip-mask" id="zip_code" name="zip_code" placeholder="32804" required pattern="^\d{5}(-\d{4})?$" maxlength="10" autocomplete="postal-code" aria-required="true">
                                            <div class="invalid-feedback">5-digit ZIP.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================ -->
                            <!-- SECTION 3: Clinical & Financial Metrics      -->
                            <!-- ============================================ -->
                            <div class="form-section-group mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge rounded-pill px-2 py-1 small text-white" style="background-color: #0369a1;">3</span>
                                    <h3 class="h6 fw-bold text-dark mb-0 text-uppercase tracking-wider">Operational &amp; Financial Metrics</h3>
                                </div>
                                <div class="row g-3">
                                    <!-- Specialty Selection -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="specialty">
                                                Medical / Dental Specialty <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select bg-light" id="specialty" name="specialty" required aria-required="true">
                                                <option value="" selected disabled>Select primary specialty...</option>
                                                <option value="Therapy (Physical, Occupational, Speech - PT/OT/ST)">Therapy (PT / OT / ST)</option>
                                                <option value="Behavioral Health & Psychiatry / Substance Abuse">Behavioral Health &amp; Psychiatry</option>
                                                <option value="Pain Management & Interventional Spine">Pain Management &amp; Spine</option>
                                                <option value="Cardiology & Cardiovascular Services">Cardiology &amp; Cardiovascular</option>
                                                <option value="Oncology & Hematology">Oncology &amp; Hematology</option>
                                                <option value="Dental Billing & Oral Surgery">Dental Billing &amp; Oral Surgery</option>
                                                <option value="DME / HME (Durable Medical Equipment)">DME / HME Billing</option>
                                                <option value="Family Medicine & General Practice">Family Medicine / Primary Care</option>
                                                <option value="Internal Medicine & Multi-Specialty">Internal Medicine / Multi-Specialty</option>
                                                <option value="Neurology & Neurosurgery">Neurology &amp; Neurosurgery</option>
                                                <option value="Dermatology & Mohs Surgery">Dermatology &amp; Mohs Surgery</option>
                                                <option value="Orthopedic Surgery & Sports Medicine">Orthopedic Surgery &amp; Sports Med</option>
                                                <option value="Radiology & Diagnostic Imaging">Radiology &amp; Imaging</option>
                                                <option value="Anesthesia & CRNA Groups">Anesthesia &amp; CRNA Groups</option>
                                                <option value="Emergency Medicine & Urgent Care">Emergency Medicine &amp; Urgent Care</option>
                                                <option value="General Surgery & ASC (Ambulatory Surgery)">General Surgery &amp; ASC</option>
                                                <option value="Ophthalmology & Optometry">Ophthalmology &amp; Optometry</option>
                                                <option value="Wound Care & Hyperbaric Medicine">Wound Care &amp; Hyperbaric</option>
                                                <option value="Pathology & Clinical Laboratory">Pathology &amp; Laboratory</option>
                                                <option value="Other Medical Specialty">Other Medical Specialty</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your clinical specialty.</div>
                                        </div>
                                    </div>

                                    <!-- Monthly Patient Volume -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="patient_volume">
                                                Monthly Patient Visit Volume <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select bg-light" id="patient_volume" name="patient_volume" required aria-required="true">
                                                <option value="" selected disabled>Select monthly encounters...</option>
                                                <option value="Under 250 visits / month (Solo Provider / Startup)">Under 250 visits / month</option>
                                                <option value="250 - 500 visits / month (Small Practice)">250 &ndash; 500 visits / month</option>
                                                <option value="501 - 1,000 visits / month (Mid-Sized Practice)">501 &ndash; 1,000 visits / month</option>
                                                <option value="1,001 - 2,500 visits / month (Multi-Provider Group)">1,001 &ndash; 2,500 visits / month</option>
                                                <option value="2,501 - 5,000 visits / month (High-Volume Clinic / ASC)">2,501 &ndash; 5,000 visits / month</option>
                                                <option value="5,000+ visits / month (Enterprise / Health System)">5,000+ visits / month</option>
                                            </select>
                                            <div class="invalid-feedback">Please select monthly patient volume.</div>
                                        </div>
                                    </div>

                                    <!-- Monthly Collections Volume -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="monthly_revenue">
                                                Estimated Monthly Revenue / Collections <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select bg-light" id="monthly_revenue" name="monthly_revenue" required aria-required="true">
                                                <option value="" selected disabled>Select monthly collections...</option>
                                                <option value="Under $50,000 / month">Under $50,000 / month</option>
                                                <option value="$50,000 - $100,000 / month">$50,000 &ndash; $100,000 / month</option>
                                                <option value="$100,001 - $250,000 / month">$100,001 &ndash; $250,000 / month</option>
                                                <option value="$250,001 - $500,000 / month">$250,001 &ndash; $500,000 / month</option>
                                                <option value="$500,001 - $1,000,000 / month">$500,001 &ndash; $1,000,000 / month</option>
                                                <option value="$1,000,000+ / month">$1,000,000+ / month</option>
                                            </select>
                                            <div class="invalid-feedback">Please select estimated monthly revenue.</div>
                                        </div>
                                    </div>

                                    <!-- Current EHR / PMS Software -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark fw-semibold small" for="current_ehr">
                                                Current EHR / PMS Software <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select bg-light" id="current_ehr" name="current_ehr" required aria-required="true">
                                                <option value="" selected disabled>Select software platform...</option>
                                                <option value="Athenahealth (athenaOne / athenaCollector)">Athenahealth (athenaOne)</option>
                                                <option value="eClinicalWorks (eCW)">eClinicalWorks (eCW)</option>
                                                <option value="Epic Systems">Epic Systems</option>
                                                <option value="Cerner / Oracle Health">Cerner / Oracle Health</option>
                                                <option value="Kareo / Tebra">Kareo / Tebra</option>
                                                <option value="AdvancedMD">AdvancedMD</option>
                                                <option value="NextGen Healthcare">NextGen Healthcare</option>
                                                <option value="WebPT">WebPT</option>
                                                <option value="Dentrix / Eaglesoft / Open Dental">Dentrix / Eaglesoft / Open Dental</option>
                                                <option value="DrChrono">DrChrono</option>
                                                <option value="Modernizing Medicine (EMA / ModMed)">ModMed (EMA)</option>
                                                <option value="TherapyNotes / SimplePractice">TherapyNotes / SimplePractice</option>
                                                <option value="Greenway Health / Prime Suite">Greenway Health</option>
                                                <option value="Allscripts / Veradigm">Allscripts / Veradigm</option>
                                                <option value="Other / Proprietary System">Other / Custom PMS</option>
                                                <option value="None / Paper Records">None / Paper Records</option>
                                            </select>
                                            <div class="invalid-feedback">Please specify your current EHR/PMS.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================ -->
                            <!-- SECTION 4: Primary RCM Pain Points & Goals   -->
                            <!-- ============================================ -->
                            <div class="form-section-group mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill px-2 py-1 small text-white" style="background-color: #0369a1;">4</span>
                                        <h3 class="h6 fw-bold text-dark mb-0 text-uppercase tracking-wider">Primary RCM Pain Points &amp; Goals</h3>
                                    </div>
                                    <span class="small text-muted">(Select all that apply)</span>
                                </div>
                                <p class="small text-muted mb-3">Which challenges are impacting your practice cash flow most severely?</p>

                                <div class="pain-points-grid audit-pills-grid d-flex flex-wrap gap-2" role="group" aria-label="RCM Pain Points Selection">
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Chronic Claim Denials (CO-4, CO-16, CO-97)" class="audit-pill-checkbox" id="pp_denials">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-warning-circle me-1"></i> High Denial Rates</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="High Aging Accounts Receivable (>90 Days)" class="audit-pill-checkbox" id="pp_aging">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-hourglass-high me-1"></i> Aging A/R (>90 Days)</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="In-House Billing Staff Turnover & Burnout" class="audit-pill-checkbox" id="pp_burnout">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-users me-1"></i> In-House Staff Burnout</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Payer Credentialing & Re-Enrollment Delays" class="audit-pill-checkbox" id="pp_credentialing">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-certificate me-1"></i> Credentialing Lapses</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Prior Authorization Delays & Denials" class="audit-pill-checkbox" id="pp_prior_auth">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-shield-warning me-1"></i> Prior Auth Delays</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Clinical Undercoding & Missed Revenue Leaks" class="audit-pill-checkbox" id="pp_undercoding">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-chart-line-down me-1"></i> Undercoding / Leaks</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Payer Contract Fee Schedule Underpayments" class="audit-pill-checkbox" id="pp_fee_schedule">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-currency-dollar me-1"></i> Fee Underpayments</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="High In-House Billing Overhead Costs" class="audit-pill-checkbox" id="pp_cost">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-currency-dollar-simple me-1"></i> High Billing Cost</span>
                                    </label>
                                    <label class="pain-point-pill audit-pill-label">
                                        <input type="checkbox" name="pain_points[]" value="Lack of Real-Time RCM KPI Reporting" class="audit-pill-checkbox" id="pp_reporting">
                                        <span class="pill-content audit-pill-chip"><i class="ph ph-presentation-chart me-1"></i> Poor KPI Reporting</span>
                                    </label>
                                </div>
                            </div>

                            <!-- ============================================ -->
                            <!-- SECTION 5: Additional Notes & Requirements   -->
                            <!-- ============================================ -->
                            <div class="form-section-group mb-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-pill px-2 py-1 small text-white" style="background-color: #0369a1;">5</span>
                                    <label class="h6 fw-bold text-dark mb-0 text-uppercase tracking-wider" for="additional_notes">
                                        Specific Audit Goals / Practice Notes <span class="small text-muted fw-normal">(Optional)</span>
                                    </label>
                                </div>
                                <textarea class="form-control bg-light" id="additional_notes" name="additional_notes" rows="3" maxlength="2000" placeholder="Describe any current billing backlog, specific carrier disputes (e.g. BCBS, Medicare, UnitedHealthcare), or provider count considerations..."></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="small text-muted">Max 2,000 characters.</span>
                                    <span class="small text-muted"><span id="charCount">0</span> / 2000</span>
                                </div>
                            </div>

                            <!-- Submit CTA Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-accent btn-lg fw-bold py-3 shadow-lg rounded-3 d-flex align-items-center justify-content-center gap-2" id="auditSubmitBtn">
                                    <i class="ph ph-chart-line-up fs-5"></i>
                                    <span>Generate My Free Practice Audit</span>
                                </button>
                            </div>

                            <!-- Trust Guarantee Microcopy -->
                            <div class="text-center text-muted small">
                                <i class="ph ph-lock-key me-1 text-success"></i>
                                Strictly confidential. Protected by 256-bit encryption and HIPAA compliance protocols. Zero obligation.
                            </div>
                        </form>

                        <!-- ============================================ -->
                        <!-- Asynchronous Success Feedback Overlay        -->
                        <!-- ============================================ -->
                        <div id="auditSuccessOverlay" class="form-success-overlay text-center py-4" style="display: none;">
                            <div class="success-icon-wrapper mb-4">
                                <div class="success-checkmark-circle mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success text-white" style="width: 80px; height: 80px;">
                                    <i class="ph ph-check-bold display-4"></i>
                                </div>
                            </div>
                            <h3 class="form-success-title h3 fw-bold text-dark mb-2">Audit Request Confirmed!</h3>
                            <p class="form-success-text text-muted mb-4 mx-auto" style="max-width: 520px;">
                                Thank you, <span id="successLeadName" class="fw-bold text-dark"><strong id="successContactName">Doctor</strong></span>! Your practice metrics have been securely submitted to our AAPC-certified review board for <strong><span id="successPracticeName">your practice</span></strong>.
                            </p>
                            
                            <!-- Lead Summary Card -->
                            <div class="audit-confirmation-card bg-light border rounded-4 p-4 mb-4 text-start mx-auto shadow-sm" style="max-width: 540px;">
                                <h4 class="h6 fw-bold text-dark mb-3 text-uppercase tracking-wider">
                                    <i class="ph ph-clipboard-text text-primary me-2"></i>Assessment Overview
                                </h4>
                                <div class="row g-2 small text-muted mb-3">
                                    <div class="col-sm-6"><strong>Reference ID:</strong> <span id="successLeadId" class="text-dark fw-semibold">#AUD-PENDING</span></div>
                                    <div class="col-sm-6"><strong>Specialty:</strong> <span id="successSpecialty" class="text-dark fw-semibold">-</span></div>
                                    <div class="col-sm-6"><strong>Contact Email:</strong> <span id="successContactEmail" class="text-dark fw-semibold">-</span></div>
                                    <div class="col-sm-6"><strong>Phone:</strong> <span id="successContactPhone" class="text-dark fw-semibold">-</span></div>
                                </div>
                                <hr class="my-3">
                                <h5 class="h6 fw-bold text-dark mb-2 text-uppercase tracking-wider">
                                    <i class="ph ph-clock-countdown text-primary me-2"></i>What Happens Next:
                                </h5>
                                <ul class="list-unstyled mb-0 small text-muted">
                                    <li class="mb-2 d-flex gap-2">
                                        <i class="ph ph-phone-call text-primary fs-5 mt-1 flex-shrink-0"></i>
                                        <span>An RCM Specialist will contact you within <strong>24 business hours</strong> to verify sample ERA parameters.</span>
                                    </li>
                                    <li class="mb-2 d-flex gap-2">
                                        <i class="ph ph-file-text text-primary fs-5 mt-1 flex-shrink-0"></i>
                                        <span>We assemble your <strong>5-Point Executive Audit Summary</strong> within 48 to 72 hours.</span>
                                    </li>
                                    <li class="d-flex gap-2">
                                        <i class="ph ph-calendar-check text-primary fs-5 mt-1 flex-shrink-0"></i>
                                        <span>We deliver a 1-on-1 strategy briefing outlining actionable revenue recovery steps with zero obligation.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="d-flex flex-wrap justify-content-center gap-3">
                                <a href="<?php echo $baseUrl; ?>/" class="btn btn-outline-primary px-4 py-2">
                                    <i class="ph ph-house me-1"></i> Back to Homepage
                                </a>
                                <a href="<?php echo $baseUrl; ?>/medical-billing-services/" class="btn btn-primary px-4 py-2">
                                    <i class="ph ph-grid-four me-1"></i> Explore Billing Services
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 3. VALUE PROOF & PERFORMANCE METRICS STRIP   -->
<!-- ============================================ -->
<section class="section-trust-strip py-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="50">
                <div class="trust-metric-box">
                    <div class="display-5 fw-bold mb-1" style="color: #0369a1;">98%</div>
                    <h3 class="h6 fw-bold text-dark mb-0">Clean Claim Rate</h3>
                    <p class="small text-muted mb-0">First-pass ICD-10 accuracy</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="trust-metric-box">
                    <div class="display-5 fw-bold mb-1" style="color: #0369a1;">500+</div>
                    <h3 class="h6 fw-bold text-dark mb-0">Providers Served</h3>
                    <p class="small text-muted mb-0">Across 24+ medical specialties</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
                <div class="trust-metric-box">
                    <div class="display-5 fw-bold mb-1" style="color: #0369a1;">&lt; 25</div>
                    <h3 class="h6 fw-bold text-dark mb-0">Days in A/R</h3>
                    <p class="small text-muted mb-0">Industry benchmark average</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="trust-metric-box">
                    <div class="display-5 fw-bold mb-1" style="color: #0369a1;">100%</div>
                    <h3 class="h6 fw-bold text-dark mb-0">HIPAA Compliant</h3>
                    <p class="small text-muted mb-0">End-to-end data encryption</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 4. SIDE-BY-SIDE AUDIT COMPARISON MATRIX      -->
<!-- ============================================ -->
<section class="section py-5">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-scales"></i>
                The Medinext Advantage
            </span>
            <h2 class="section-title">
                What Sets Our <span class="gradient-text">Forensic Audit Apart</span>
            </h2>
            <p class="section-subtitle">
                Compare our deep-dive clinical and financial diagnostic analysis against generic billing company quotes.
            </p>
        </div>

        <div class="table-responsive shadow-sm rounded-4 border bg-white" data-aos="fade-up">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="py-3 px-4 text-dark fw-bold" style="width: 40%;">Diagnostic Assessment Feature</th>
                        <th scope="col" class="py-3 px-4 fw-bold text-center" style="width: 30%; color: #0369a1;">MEDINEXT SOLUTIONS Free Audit</th>
                        <th scope="col" class="py-3 px-4 fw-bold text-center" style="width: 30%; color: #475569;">Generic Billing Inquiries</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="ph ph-chart-line-down me-2" style="color: #0369a1;"></i> E/M Bell Curve Specialty Benchmarking
                        </td>
                        <td class="py-3 px-4 text-center fw-bold" style="color: #15803d;"><i class="ph ph-check-circle fs-5 me-1"></i> Included (Full CMS Match)</td>
                        <td class="py-3 px-4 text-center" style="color: #475569;"><i class="ph ph-x-circle fs-5 me-1" style="color: #dc2626;"></i> Not Provided</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="ph ph-file-search me-2" style="color: #0369a1;"></i> Denial Code Translation (CO-4, CO-16, CO-97)
                        </td>
                        <td class="py-3 px-4 text-center fw-bold" style="color: #15803d;"><i class="ph ph-check-circle fs-5 me-1"></i> Root-Cause Mapped</td>
                        <td class="py-3 px-4 text-center" style="color: #475569;"><i class="ph ph-x-circle fs-5 me-1" style="color: #dc2626;"></i> High-Level Guesswork</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="ph ph-hourglass-medium me-2" style="color: #0369a1;"></i> Aging A/R Recovery Dollar Modeling
                        </td>
                        <td class="py-3 px-4 text-center fw-bold" style="color: #15803d;"><i class="ph ph-check-circle fs-5 me-1"></i> Dollar-by-Dollar Projection</td>
                        <td class="py-3 px-4 text-center" style="color: #475569;"><i class="ph ph-x-circle fs-5 me-1" style="color: #dc2626;"></i> Generic Estimates</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="ph ph-currency-dollar-simple me-2" style="color: #0369a1;"></i> Payer Fee Schedule Underpayment Check
                        </td>
                        <td class="py-3 px-4 text-center fw-bold" style="color: #15803d;"><i class="ph ph-check-circle fs-5 me-1"></i> Contract Rate Verification</td>
                        <td class="py-3 px-4 text-center" style="color: #475569;"><i class="ph ph-x-circle fs-5 me-1" style="color: #dc2626;"></i> Ignored</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="ph ph-certificate me-2" style="color: #0369a1;"></i> Conducted by AAPC-Certified Auditors
                        </td>
                        <td class="py-3 px-4 text-center fw-bold" style="color: #15803d;"><i class="ph ph-check-circle fs-5 me-1"></i> Certified CPB / CPC Experts</td>
                        <td class="py-3 px-4 text-center" style="color: #475569;"><i class="ph ph-x-circle fs-5 me-1" style="color: #dc2626;"></i> Sales Representatives</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 5. FREQUENTLY ASKED QUESTIONS                -->
<!-- ============================================ -->
<section class="section py-5 bg-light" id="faq">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
            <span class="section-badge">
                <i class="ph ph-question"></i>
                Audit FAQs
            </span>
            <h2 class="section-title">
                Frequently Asked <span class="gradient-text">Questions</span>
            </h2>
            <p class="section-subtitle">
                Everything you need to know about our complimentary, zero-obligation practice revenue audit.
            </p>
        </div>

        <div class="row justify-content-center" data-aos="fade-up">
            <div class="col-lg-9">
                <div class="accordion custom-accordion" id="auditFaqAccordion">
                    
                    <!-- FAQ 1 -->
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden bg-white shadow-sm">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Is the practice revenue audit truly free with zero obligation?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#auditFaqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, 100%. MEDINEXT SOLUTIONS provides this diagnostic revenue assessment at zero cost and without any contractual commitment. We believe in demonstrating our forensic RCM capabilities and clean claim accuracy upfront before discussing an ongoing partnership.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden bg-white shadow-sm">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What reports or data do I need to supply for the audit?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#auditFaqAccordion">
                            <div class="accordion-body text-muted">
                                We only need standard practice management summary reports, such as a 90-day aging summary by payer and a small sample of recent Electronic Remittance Advice (ERA) denial batches. You can provide exported PDFs or grant secure read-only portal access.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden bg-white shadow-sm">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How is patient privacy and HIPAA compliance safeguarded?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#auditFaqAccordion">
                            <div class="accordion-body text-muted">
                                Patient privacy is paramount. All preliminary reviews are conducted under full HIPAA protocols, covered by our formal Business Associate Agreement (BAA) and a mutual Non-Disclosure Agreement (NDA). All transmission files are encrypted using bank-grade 256-bit TLS/SSL protocols.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden bg-white shadow-sm">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How long does it take to construct the executive audit report?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#auditFaqAccordion">
                            <div class="accordion-body text-muted">
                                Once sample metrics are received, our AAPC-certified analysts require only 48 to 72 hours to complete the forensic analysis and prepare your executive presentation deck.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden bg-white shadow-sm">
                        <h3 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                What clinical specialties do you support?
                            </button>
                        </h3>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#auditFaqAccordion">
                            <div class="accordion-body text-muted">
                                We support over 24 clinical specialties, including Therapy (PT/OT/ST), Behavioral Health &amp; Psychiatry, Pain Management, Cardiology, Oncology &amp; Hematology, Dental Billing, DME/HME, Family Practice, General Surgery &amp; ASCs, Dermatology, and Orthopedic Care.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 6. BOTTOM CTA BANNER SECTION                 -->
<!-- ============================================ -->
<section class="section cta-section position-relative overflow-hidden" id="cta">
    <canvas id="cta-color-panels-canvas" class="cta-shader-bg"></canvas>
    <div class="cta-overlay-layer"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="cta-wrapper text-center py-5" data-aos="fade-up">
            <div class="cta-content max-w-700 mx-auto">
                <h2 class="cta-title text-white display-5 fw-bold mb-3">
                    Ready to Recover Your <span class="gradient-text">Lost Practice Cash Flow?</span>
                </h2>
                <p class="cta-text text-white-50 fs-5 mb-4">
                    Join hundreds of providers nationwide who have eliminated billing backlogs, reduced denials by 40%, and accelerated monthly collections with MEDINEXT SOLUTIONS.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#audit-form-card" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg cta-btn">
                        <i class="ph ph-arrow-up me-1"></i> Complete Your Audit Intake
                    </a>
                    <a href="tel:8627992199" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="ph ph-phone me-1"></i> Call 862-799-2199
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- 7. STRUCTURED DATA JSON-LD SCHEMAS          -->
<!-- ============================================ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "https://medinextsolutions.com/free-practice-audit/#breadcrumb",
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
          "name": "Free Practice Audit",
          "item": "https://medinextsolutions.com/free-practice-audit/"
        }
      ]
    },
    {
      "@type": "Service",
      "@id": "https://medinextsolutions.com/free-practice-audit/#service",
      "name": "Free Medical Practice Revenue Audit & Cost Assessment",
      "provider": {
        "@type": "MedicalOrganization",
        "name": "MEDINEXT SOLUTIONS",
        "url": "https://medinextsolutions.com/"
      },
      "serviceType": "Revenue Cycle Management Audit",
      "description": "Comprehensive forensic medical billing audit assessing claim denial patterns, aging accounts receivable, coding bell curve benchmarks, and practice cost optimization.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD",
        "availability": "https://schema.org/InStock"
      }
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/free-practice-audit/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Is the practice revenue audit truly free with zero obligation?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, 100%. MEDINEXT SOLUTIONS provides this diagnostic revenue assessment at zero cost and without any contractual commitment."
          }
        },
        {
          "@type": "Question",
          "name": "What reports or data do I need to supply for the audit?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We only need standard practice management summary reports, such as a 90-day aging summary by payer and a small sample of recent Electronic Remittance Advice (ERA) denial batches."
          }
        },
        {
          "@type": "Question",
          "name": "How is patient privacy and HIPAA compliance safeguarded?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "All preliminary reviews are conducted under full HIPAA protocols, covered by our formal Business Associate Agreement (BAA) and a mutual Non-Disclosure Agreement (NDA)."
          }
        },
        {
          "@type": "Question",
          "name": "How long does it take to construct the executive audit report?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Once sample metrics are received, our AAPC-certified analysts require only 48 to 72 hours to complete the forensic analysis and prepare your executive presentation deck."
          }
        },
        {
          "@type": "Question",
          "name": "What clinical specialties do you support?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We support over 24 clinical specialties, including Therapy (PT/OT/ST), Behavioral Health, Pain Management, Cardiology, Oncology, Dental Billing, DME/HME, Family Practice, General Surgery & ASCs, Dermatology, and Orthopedic Care."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
