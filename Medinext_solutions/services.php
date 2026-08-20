<?php
/**
 * MEDINEXT SOLUTIONS &mdash; Services Page (Redesigned with Animations)
 */
$pageTitle = 'Comprehensive Medical Billing Services | MEDINEXT SOLUTIONS';
$pageDescription = 'Expert medical billing services with 98% clean claim rate. AAPC-certified specialists in therapy, pain management, cardiology, oncology, dental, behavioral health, DME billing, RCM, and more.';
$pageKeywords = 'medical billing services, revenue cycle management, therapy billing, pain management billing, cardiovascular billing, AAPC certified, HIPAA compliant';
require_once 'includes/header.php';
?>

<main id="main-content">

<!-- &mdash;&mdash; Hero Section &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-hero">
  <div class="svc-hero-bg-canvas"></div>
  <!-- Floating orbs -->
  <div class="svc-orb svc-orb-1"></div>
  <div class="svc-orb svc-orb-2"></div>
  <div class="svc-orb svc-orb-3"></div>

  <div class="container svc-hero-inner">
    <nav aria-label="Breadcrumb" class="svc-breadcrumb">
      <ol><li><a href="index.php">Home</a></li><li aria-hidden="true">&gt;</li><li aria-current="page">Services</li></ol>
    </nav>
    <div class="svc-hero-badge" data-aos="fade-up">
      <span class="svc-badge-dot"></span> AAPC Certified &middot; HIPAA Compliant &middot; 10+ Years
    </div>
    <h1 class="svc-hero-title" data-aos="fade-up" data-aos-delay="80">
      Comprehensive<br><span class="svc-gradient-text">Medical Billing</span><br>Services
    </h1>
    <p class="svc-hero-sub" data-aos="fade-up" data-aos-delay="160">
      24+ specialized billing verticals. One trusted partner. Transform your practice revenue with a 98% clean claim rate and 30% average revenue increase.
    </p>
    <div class="svc-hero-ctas" data-aos="fade-up" data-aos-delay="240">
      <a href="free-practice-audit.php" class="svc-btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Get Free Practice Audit
      </a>
      <a href="tel:9088290133" class="svc-btn-outline">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15z"/></svg>
        908-829-0133
      </a>
    </div>

    <!-- Animated Stats Row -->
    <div class="svc-hero-stats" data-aos="fade-up" data-aos-delay="320">
      <div class="svc-stat">
        <span class="svc-stat-num" data-countup="98" data-suffix="%">0%</span>
        <span class="svc-stat-lbl">Clean Claim Rate</span>
      </div>
      <div class="svc-stat-divider"></div>
      <div class="svc-stat">
        <span class="svc-stat-num" data-countup="500" data-suffix="+">0</span>
        <span class="svc-stat-lbl">Providers Served</span>
      </div>
      <div class="svc-stat-divider"></div>
      <div class="svc-stat">
        <span class="svc-stat-num" data-countup="30" data-suffix="%">0%</span>
        <span class="svc-stat-lbl">Revenue Increase</span>
      </div>
      <div class="svc-stat-divider"></div>
      <div class="svc-stat">
        <span class="svc-stat-num" data-countup="15" data-suffix=" days">0</span>
        <span class="svc-stat-lbl">AR Turnaround</span>
      </div>
  </div>
</section>

<!-- &mdash;&mdash; Services Grid &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-grid-section" id="services-grid">
  <div class="container">
    <div class="svc-section-header" data-aos="fade-up">
      <span class="svc-eyebrow">Our Specialties</span>
      <h2 class="svc-section-title">24+ Specialized Billing <span class="svc-gradient-text">Verticals</span></h2>
      <p class="svc-section-sub">Every medical specialty has unique billing codes, common denials, and payer policies. Our dedicated teams handle each with precision.</p>
    </div>

    <?php
            $medicalServices = [
        ['icon'=>'bi-activity', 'color'=>'#0ea5e9', 'img'=>$baseUrl . '/assets/images/content/therapy-billing.jpg',
         'title'=>'Therapy Billing', 'tag'=>'ST &middot; PT &middot; OT',
         'desc'=>'Expert billing for Speech, Physical, and Occupational Therapy. 8-minute rule compliance, KX modifier, therapy cap management.',
         'url'=>'therapy-billing-services.php', 'kpi'=>'99% first-pass rate'],
        ['icon'=>'bi-bandaid', 'color'=>'#E55A2B', 'img'=>$baseUrl . '/assets/images/content/pain-management.webp',
         'title'=>'Pain Management', 'tag'=>'Interventional',
         'desc'=>'Complex prior authorizations, nerve block CPT codes (64400-64530), epidural injections. No bundling penalties.',
         'url'=>'pain-management-billing.php', 'kpi'=>'40% denial reduction'],
        ['icon'=>'bi-heart-pulse', 'color'=>'#EF4444', 'img'=>$baseUrl . '/assets/images/content/cardiovascular-billing.webp',
         'title'=>'Cardiovascular', 'tag'=>'Cardiology',
         'desc'=>'Echocardiography (93303-93352), cardiac catheterization, cardiology billing with zero unbundling penalties.',
         'url'=>'cardiovascular-billing-services.php', 'kpi'=>'0K+ recovered'],
        ['icon'=>'bi-capsule', 'color'=>'#10B981', 'img'=>$baseUrl . '/assets/images/content/oncology-hematology.jpg',
         'title'=>'Oncology-Hematology', 'tag'=>'Precision',
         'desc'=>'Chemo administration (96401-96549), J-code drug buy-and-bill, oncology-specific payer policies.',
         'url'=>'oncology-hematology-billing.php', 'kpi'=>'98% J-code accuracy'],
        ['icon'=>'ph-brain', 'color'=>'#F59E0B', 'img'=>$baseUrl . '/assets/images/content/behavioral-health-svc.webp',
         'title'=>'Behavioral Health', 'tag'=>'Mental Health',
         'desc'=>'Behavioral health carve-outs, telehealth regulations, psychiatric evaluation codes (90801-90899).',
         'url'=>'behavioral-health-billing.php', 'kpi'=>'Parity law experts'],
        ['icon'=>'ph-wheelchair', 'color'=>'#0ea5e9', 'img'=>$baseUrl . '/assets/images/content/dme-billing.webp',
         'title'=>'DME Billing', 'tag'=>'Equipment',
         'desc'=>'BrightTree proficiency, HCPCS Level II codes, documentation denial prevention for durable medical equipment.',
         'url'=>'dme-billing-services.php', 'kpi'=>'Zero doc denials target'],
        ['icon'=>'bi-graph-up-arrow', 'color'=>'#6366F1', 'img'=>$baseUrl . '/assets/images/content/neurology-billing.jpg',
         'title'=>'Neurology Billing', 'tag'=>'Neurosurgical',
         'desc'=>'EMG/EEG billing, neurosurgical procedure coding, rigorous diagnosis mapping for neurological specialties.',
         'url'=>'neurology-billing-services.php', 'kpi'=>'Complex CPT mastery'],
        ['icon'=>'bi-camera', 'color'=>'#0891B2', 'img'=>$baseUrl . '/assets/images/content/radiology-billing.jpg',
         'title'=>'Radiology Billing', 'tag'=>'Imaging',
         'desc'=>'Diagnostic imaging billing for X-ray, MRI, CT scans. Automated workflow for high-volume radiology centers.',
         'url'=>'radiology-billing-services.php', 'kpi'=>'15-day AR turnaround'],
        ['icon'=>'bi-droplet', 'color'=>'#0284c7', 'img'=>$baseUrl . '/assets/images/content/anesthesia-billing.webp',
         'title'=>'Anesthesia Billing', 'tag'=>'Time-Unit Logic',
         'desc'=>'Base units + time unit calculations, concurrency tracking, physical status modifiers (P1-P6), and ASA crosswalk compliance.',
         'url'=>'anesthesia-billing.php', 'kpi'=>'Zero concurrency errors'],
        ['icon'=>'bi-person-lines-fill', 'color'=>'#E11D48', 'img'=>$baseUrl . '/assets/images/content/dermatology-billing.avif',
         'title'=>'Dermatology Billing', 'tag'=>'Mohs & Biopsies',
         'desc'=>'Mohs micrographic surgery, complex lesion excision, pathology bundling rules, and cosmetic vs. medical billing demarcation.',
         'url'=>'dermatology-billing.php', 'kpi'=>'99.1% clean claims'],
        ['icon'=>'bi-lightning', 'color'=>'#F97316', 'img'=>$baseUrl . '/assets/images/content/emergency-medicine.jpeg',
         'title'=>'Emergency Medicine', 'tag'=>'High-Acuity E/M',
         'desc'=>'High-acuity Level 1-5 ED E/M coding (99281-99285), critical care time tracking (99291-99292), and trauma unbundling compliance.',
         'url'=>'emergency-medicine-billing.php', 'kpi'=>'14-day AR cycle'],
        ['icon'=>'bi-house-heart', 'color'=>'#10B981', 'img'=>$baseUrl . '/assets/images/content/family-medicine.jpg',
         'title'=>'Family Medicine', 'tag'=>'Primary Care',
         'desc'=>'Preventive medicine coding (99381-99397), chronic care management (CCM), transitional care (TCM), and AWV optimization.',
         'url'=>'family-medicine-billing.php', 'kpi'=>'28% revenue lift'],
        ['icon'=>'bi-scissors', 'color'=>'#8B5CF6', 'img'=>$baseUrl . '/assets/images/content/general-surgery.webp',
         'title'=>'General Surgery', 'tag'=>'Surgical Modifiers',
         'desc'=>'Global surgical package adherence, assistant surgeon modifiers (-80/-82), multiple procedure discounting (-51), and unbundled code resolution.',
         'url'=>'general-surgery-billing.php', 'kpi'=>'Zero bundling penalties'],
        ['icon'=>'bi-plus-circle', 'color'=>'#0ea5e9', 'img'=>$baseUrl . '/assets/images/content/internal-medicine.jpg',
         'title'=>'Internal Medicine', 'tag'=>'Complex Disease',
         'desc'=>'Multi-condition chronic disease management, advanced diagnostic coding, E/M complexity documentation, and MIPS compliance.',
         'url'=>'internal-medicine-billing.php', 'kpi'=>'99% first-pass rate'],
        ['icon'=>'bi-eye', 'color'=>'#06B6D4', 'img'=>$baseUrl . '/assets/images/content/Ophthalmology.jpeg',
         'title'=>'Ophthalmology Billing', 'tag'=>'Eye Care & Surgery',
         'desc'=>'Cataract surgery (66984), intravitreal injections (67028), diagnostic imaging (OCT, visual fields), and bilateral eye surgery coding.',
         'url'=>'ophthalmology-billing.php', 'kpi'=>'30% faster reimbursement'],
        ['icon'=>'bi-person-arms-up', 'color'=>'#3B82F6', 'img'=>$baseUrl . '/assets/images/content/orthopedic-billing.webp',
         'title'=>'Orthopedic Billing', 'tag'=>'Musculoskeletal',
         'desc'=>'Joint arthroplasty, fracture care global periods, arthroscopy, durable medical equipment (DME), and anatomical modifier accuracy.',
         'url'=>'orthopedic-billing.php', 'kpi'=>'0K+ recovered'],
        ['icon'=>'bi-house-add', 'color'=>'#14B8A6', 'img'=>$baseUrl . '/assets/images/content/home-health.avif',
         'title'=>'Home Health Billing', 'tag'=>'PDGM Model',
         'desc'=>'Patient-Driven Groupings Model (PDGM) compliance, Notice of Admission (NOA) submissions, OASIS accuracy, and episodic billing.',
         'url'=>'home-health-billing.php', 'kpi'=>'Zero NOA late penalties'],
        ['icon'=>'bi-shield-plus', 'color'=>'#EC4899', 'img'=>$baseUrl . '/assets/images/content/general-surgery.webp',
         'title'=>'Wound Care Billing', 'tag'=>'Debridement & CTPs',
         'desc'=>'Advanced skin substitutes (CTPs/15271-15278), surgical debridement (11042-11047), and hyperbaric oxygen therapy (HBOT) billing.',
         'url'=>'wound-care-billing.php', 'kpi'=>'100% CTP reimbursement'],
        ['icon'=>'bi-hospital', 'color'=>'#6366F1', 'img'=>$baseUrl . '/assets/images/content/hospital-billing.jpg',
         'title'=>'Hospital Billing', 'tag'=>'UB-04 & Facility',
         'desc'=>'Inpatient and outpatient facility billing, UB-04 claim scrubbing, DRG validation, APC optimization, and enterprise revenue cycle management.',
         'url'=>'hospital-billing.php', 'kpi'=>'Enterprise-grade RCM'],
        ['icon'=>'bi-person-arms-up', 'color'=>'#0EA5E9', 'img'=>$baseUrl . '/assets/images/content/occupational-therapy.webp',
         'title'=>'Occupational Therapy', 'tag'=>'OT Billing',
         'desc'=>'OT evaluation codes 97165-97167, therapeutic activities (97530), GO modifier expertise for occupational therapists.',
         'url'=>'occupational-therapy-billing.php', 'kpi'=>'100% modifier compliance'],
        ['icon'=>'bi-currency-dollar', 'color'=>'#059669', 'img'=>$baseUrl . '/assets/images/content/revenue-cycle-management.webp',
         'title'=>'Revenue Cycle Management', 'tag'=>'Full-Service RCM',
         'desc'=>'End-to-end RCM: eligibility verification, charge capture, claim submission, payment posting, AR follow-up.',
         'url'=>'revenue-cycle-management.php', 'kpi'=>'30% revenue increase'],
        ['icon'=>'bi-shield-exclamation', 'color'=>'#DC2626', 'img'=>$baseUrl . '/assets/images/content/denial-management.png',
         'title'=>'Denial Management', 'tag'=>'Appeals',
         'desc'=>'Aggressive denial tracking, root-cause analysis, comprehensive appeal filing. We pursue every dollar.',
         'url'=>'denial-management-services.php', 'kpi'=>'97% appeal success'],
        ['icon'=>'bi-clipboard2-check', 'color'=>'#7C3AED', 'img'=>$baseUrl . '/assets/images/content/prior-authorization.webp',
         'title'=>'Prior Authorization', 'tag'=>'Pre-Certs',
         'desc'=>'ePA portal integration with eviCore and AIM. CO-197 denial prevention for specialized treatments.',
         'url'=>'prior-authorization-services.php', 'kpi'=>'Zero CO-197 denials'],
        ['icon'=>'bi-code-slash', 'color'=>'#0ea5e9', 'img'=>$baseUrl . '/assets/images/content/medical-coding.png',
         'title'=>'Medical Coding', 'tag'=>'AAPC Certified',
         'desc'=>'AAPC-certified coders for ICD-10, CPT, and HCPCS coding. Zero-error documentation translation.',
         'url'=>'medical-coding-services.php', 'kpi'=>'98% coding accuracy'],
        ['icon'=>'bi-award', 'color'=>'#B45309', 'img'=>$baseUrl . '/assets/images/content/provider-credentialing.jpg',
         'title'=>'Provider Credentialing', 'tag'=>'Enrollment',
         'desc'=>'CAQH management, PECOS enrollment, NPI registration, and continuous payer contracting surveillance.',
         'url'=>'provider-credentialing-services.php', 'kpi'=>'30-day enrollment'],
      ];

      $dentalServices = [
        ['icon'=>'ph-tooth', 'color'=>'#8B5CF6', 'img'=>$baseUrl . '/assets/images/content/dental-billing.jpg',
         'title'=>'Dental Billing', 'tag'=>'Medical-Dental',
         'desc'=>'Medical-dental cross-coding for oral surgeries, trauma, TMD, and sleep apnea &mdash; maximizing reimbursements.',
         'url'=>'dental-billing-services.php', 'kpi'=>'Cross-code specialists'],
        ['icon'=>'bi-shield-check', 'color'=>'#3B82F6', 'img'=>$baseUrl . '/assets/images/content/dental-insurance.jpg',
         'title'=>'Dental Insurance Verification', 'tag'=>'Verification',
         'desc'=>'Comprehensive breakdown of benefits, eligibility verification, and prior authorizations for complex cases.',
         'url'=>'dental-insurance-verification.php', 'kpi'=>'100% verified'],
        ['icon'=>'bi-calendar-check', 'color'=>'#10B981', 'img'=>$baseUrl . '/assets/images/content/fee-schedule.jpeg',
         'title'=>'Fee Schedule Maintenance', 'tag'=>'Optimization',
         'desc'=>'Strategic fee schedule analysis, UCR comparisons, and PPO negotiations to maximize practice profitability.',
         'url'=>'fee-schedule-maintenance.php', 'kpi'=>'15% revenue lift'],
        ['icon'=>'bi-graph-up', 'color'=>'#F59E0B', 'img'=>$baseUrl . '/assets/images/content/ar-followup.png',
         'title'=>'AR Follow Up', 'tag'=>'Collections',
         'desc'=>'Aggressive 30-60-90+ day AR management, detailed aging reports, and rapid resolution of outstanding claims.',
         'url'=>'accounts-receivable-followup.php', 'kpi'=>'AR days < 25'],
        ['icon'=>'bi-x-octagon', 'color'=>'#EF4444', 'img'=>$baseUrl . '/assets/images/content/denial-management-dental.png',
         'title'=>'Payment & Denial Management', 'tag'=>'Reconciliation',
         'desc'=>'Accurate EFT/ERA posting, daily ledger reconciliation, and immediate appeal of denied dental claims.',
         'url'=>'dental-denial-management.php', 'kpi'=>'99% posted same-day'],
        ['icon'=>'bi-person-badge', 'color'=>'#0ea5e9', 'img'=>$baseUrl . '/assets/images/content/credentialing-dental.jpg',
         'title'=>'Dental Credentialing', 'tag'=>'Enrollment',
         'desc'=>'Streamlined PPO/Medicaid enrollment, re-credentialing, and CAQH profile maintenance for dentists.',
         'url'=>'dental-credentialing.php', 'kpi'=>'Hassle-free setup'],
      ];
      ?>
      
      <!-- Section Toggle Buttons -->
      <div class="svc-toggle-buttons text-center mb-5" data-aos="fade-up">
        <button class="uiverse-btn svc-tab-uiverse-btn active" id="btn-med-services" onclick="showServices('medical')">
          <div class="uiverse-content">
            <span class="uiverse-text">
              <span style="--i:1">M</span><span style="--i:2">e</span><span style="--i:3">d</span><span style="--i:4">i</span><span style="--i:5">c</span><span style="--i:6">a</span><span style="--i:7">l</span>
              <span>&nbsp;</span>
              <span style="--i:8">B</span><span style="--i:9">i</span><span style="--i:10">l</span><span style="--i:11">l</span><span style="--i:12">i</span><span style="--i:13">n</span><span style="--i:14">g</span>
              <span>&nbsp;</span>
              <span style="--i:15">S</span><span style="--i:16">e</span><span style="--i:17">r</span><span style="--i:18">v</span><span style="--i:19">i</span><span style="--i:20">c</span><span style="--i:21">e</span><span style="--i:22">s</span>
            </span>
          </div>
        </button>
        <button class="uiverse-btn svc-tab-uiverse-btn ms-3" id="btn-den-services" onclick="showServices('dental')">
          <div class="uiverse-content">
            <span class="uiverse-text">
              <span style="--i:1">D</span><span style="--i:2">e</span><span style="--i:3">n</span><span style="--i:4">t</span><span style="--i:5">a</span><span style="--i:6">l</span>
              <span>&nbsp;</span>
              <span style="--i:7">B</span><span style="--i:8">i</span><span style="--i:9">l</span><span style="--i:10">l</span><span style="--i:11">i</span><span style="--i:12">n</span><span style="--i:13">g</span>
              <span>&nbsp;</span>
              <span style="--i:14">S</span><span style="--i:15">e</span><span style="--i:16">r</span><span style="--i:17">v</span><span style="--i:18">i</span><span style="--i:19">c</span><span style="--i:20">e</span><span style="--i:21">s</span>
            </span>
          </div>
        </button>
      </div>

      <!-- MEDICAL GRID -->
      <div id="medical-services-grid" class="svc-card-grid">
      <?php foreach ($medicalServices as $i => $svc): ?>
      <article class="svc-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 60; ?>">
        <!-- Image -->
        <a href="<?php echo htmlspecialchars($svc['url']); ?>" class="svc-card-img-wrap">
          <img src="<?php echo htmlspecialchars($svc['img']); ?>" alt="<?php echo htmlspecialchars($svc['title']); ?>" loading="<?php echo $i < 6 ? 'eager' : 'lazy'; ?>" decoding="async">
          <div class="svc-card-img-overlay"></div>
          <span class="svc-card-tag" style="--tag-color:<?php echo htmlspecialchars($svc['color']); ?>">
            <?php echo htmlspecialchars($svc['tag']); ?>
          </span>
          <div class="svc-card-hover-icon" style="color:<?php echo htmlspecialchars($svc['color']); ?>">
            <i class="<?php echo strpos($svc['icon'], 'ph-') === 0 ? 'ph ' : 'bi '; ?><?php echo htmlspecialchars($svc['icon']); ?>"></i>
          </div>
        </a>

        <!-- Content -->
        <div class="svc-card-body">
          <div class="svc-card-icon-row">
            <span class="svc-card-icon" style="background:<?php echo htmlspecialchars($svc['color']); ?>20;color:<?php echo htmlspecialchars($svc['color']); ?>">
              <i class="<?php echo strpos($svc['icon'], 'ph-') === 0 ? 'ph ' : 'bi '; ?><?php echo htmlspecialchars($svc['icon']); ?>"></i>
            </span>
            <span class="svc-card-kpi"><?php echo htmlspecialchars($svc['kpi']); ?></span>
          </div>
          <h3 class="svc-card-title">
            <a href="<?php echo htmlspecialchars($svc['url']); ?>"><?php echo htmlspecialchars($svc['title']); ?></a>
          </h3>
          <p class="svc-card-desc"><?php echo htmlspecialchars($svc['desc']); ?></p>
          <a href="<?php echo htmlspecialchars($svc['url']); ?>" class="svc-card-link" style="color:<?php echo htmlspecialchars($svc['color']); ?>">
            Learn More
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
      </div>

      <!-- DENTAL GRID -->
      <div id="dental-services-grid" class="svc-card-grid" style="display: none;">
      <?php foreach ($dentalServices as $i => $svc): ?>
      <article class="svc-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 60; ?>">
        <!-- Image -->
        <a href="<?php echo htmlspecialchars($svc['url']); ?>" class="svc-card-img-wrap">
          <img src="<?php echo htmlspecialchars($svc['img']); ?>" alt="<?php echo htmlspecialchars($svc['title']); ?>" loading="lazy" decoding="async">
          <div class="svc-card-img-overlay"></div>
          <span class="svc-card-tag" style="--tag-color:<?php echo htmlspecialchars($svc['color']); ?>">
            <?php echo htmlspecialchars($svc['tag']); ?>
          </span>
          <div class="svc-card-hover-icon" style="color:<?php echo htmlspecialchars($svc['color']); ?>">
            <i class="<?php echo strpos($svc['icon'], 'ph-') === 0 ? 'ph ' : 'bi '; ?><?php echo htmlspecialchars($svc['icon']); ?>"></i>
          </div>
        </a>

        <!-- Content -->
        <div class="svc-card-body">
          <div class="svc-card-icon-row">
            <span class="svc-card-icon" style="background:<?php echo htmlspecialchars($svc['color']); ?>20;color:<?php echo htmlspecialchars($svc['color']); ?>">
              <i class="<?php echo strpos($svc['icon'], 'ph-') === 0 ? 'ph ' : 'bi '; ?><?php echo htmlspecialchars($svc['icon']); ?>"></i>
            </span>
            <span class="svc-card-kpi"><?php echo htmlspecialchars($svc['kpi']); ?></span>
          </div>
          <h3 class="svc-card-title">
            <a href="<?php echo htmlspecialchars($svc['url']); ?>"><?php echo htmlspecialchars($svc['title']); ?></a>
          </h3>
          <p class="svc-card-desc"><?php echo htmlspecialchars($svc['desc']); ?></p>
          <a href="<?php echo htmlspecialchars($svc['url']); ?>" class="svc-card-link" style="color:<?php echo htmlspecialchars($svc['color']); ?>">
            Learn More
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
      </div>

      <script>
      function showServices(type) {
          var medBtn = document.getElementById('btn-med-services');
          var denBtn = document.getElementById('btn-den-services');
          if (type === 'medical') {
              document.getElementById('medical-services-grid').style.display = 'grid';
              document.getElementById('dental-services-grid').style.display = 'none';
              if (medBtn) medBtn.classList.add('active');
              if (denBtn) denBtn.classList.remove('active');
          } else {
              document.getElementById('medical-services-grid').style.display = 'none';
              document.getElementById('dental-services-grid').style.display = 'grid';
              if (denBtn) denBtn.classList.add('active');
              if (medBtn) medBtn.classList.remove('active');
          }
      }
      </script>


  </div>
</section>

<!-- &mdash;&mdash; Process Section &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-process" data-aos="fade-up">
  <div class="container">
    <div class="svc-section-header" data-aos="fade-up">
      <span class="svc-eyebrow">How It Works</span>
      <h2 class="svc-section-title">Our End-to-End <span class="svc-gradient-text">Billing Process</span></h2>
    </div>
    <div class="svc-process-steps">
      <?php
      $steps = [
        ['num'=>'01','icon'=>'bi-person-check','title'=>'Eligibility Check','desc'=>'We verify patient demographics and insurance coverage before every encounter.'],
        ['num'=>'02','icon'=>'bi-code-slash','title'=>'Certified Coding','desc'=>'AAPC coders translate documentation into accurate ICD-10, CPT & HCPCS codes.'],
        ['num'=>'03','icon'=>'bi-search','title'=>'Claim Scrubbing','desc'=>'Our proprietary system catches 98% of errors before claims reach the payer.'],
        ['num'=>'04','icon'=>'bi-send','title'=>'Electronic Submission','desc'=>'Clean claims submitted instantly to all major payers via EDI 837.'],
        ['num'=>'05','icon'=>'bi-cash-coin','title'=>'Payment Posting','desc'=>'ERA/EOB payments posted and reconciled within 24 hours of receipt.'],
        ['num'=>'06','icon'=>'bi-graph-up-arrow','title'=>'AR Follow-Up','desc'=>'Unpaid claims aggressively worked until every dollar is recovered.'],
      ];
      foreach ($steps as $step):
      ?>
      <div class="svc-step" data-aos="fade-up" data-aos-delay="<?php echo array_search($step, $steps) * 60; ?>">
        <div class="svc-step-num"><?php echo $step['num']; ?></div>
        <div class="svc-step-icon"><i class="bi <?php echo $step['icon']; ?>"></i></div>
        <h3 class="svc-step-title"><?php echo $step['title']; ?></h3>
        <p class="svc-step-desc"><?php echo $step['desc']; ?></p>
      </div>
      <?php endforeach; ?>
  </div>
</section>

<!-- &mdash;&mdash; Trust Badges &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-trust" data-aos="fade-up">
  <div class="container">
    <div class="svc-trust-grid">
      <div class="svc-trust-item">
        <i class="bi bi-shield-check"></i>
        <h4>100% HIPAA</h4>
        <p>AES-256 encryption, VPN-secured access, fully compliant</p>
      </div>
      <div class="svc-trust-item">
        <i class="bi bi-award"></i>
        <h4>AAPC Certified</h4>
        <p>All coders hold active AAPC certifications</p>
      </div>
      <div class="svc-trust-item">
        <i class="bi bi-people"></i>
        <h4>500+ Providers</h4>
        <p>Trusted by practices in all 50 states</p>
      </div>
      <div class="svc-trust-item">
        <i class="bi bi-headset"></i>
        <h4>24/7 Support</h4>
        <p>Dedicated account managers always available</p>
      </div>
  </div>
</section>

<!-- &mdash;&mdash; FAQ &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-faq" id="faq" data-aos="fade-up">
  <div class="container">
    <div class="svc-section-header" data-aos="fade-up">
      <span class="svc-eyebrow">FAQ</span>
      <h2 class="svc-section-title">Frequently Asked <span class="svc-gradient-text">Questions</span></h2>
    </div>
    <div class="svc-faq-grid">
      <?php
      $faqs = [
        ['q'=>'What does a 98% clean claim rate mean for my practice?',
         'a'=>'98 out of every 100 claims are accepted on first submission &mdash; meaning faster payment, less rework, and steady reliable cash flow with minimal denial management overhead.'],
        ['q'=>'Are your medical coders AAPC certified?',
         'a'=>'Yes. All our coders hold active certifications from the American Academy of Professional Coders (AAPC), ensuring precision ICD-10, CPT, and HCPCS coding with strict compliance.'],
        ['q'=>'How do you handle difficult claim denials?',
         'a'=>'Our Denial Management team investigates every rejection, identifies root causes, rectifies errors, and files comprehensive appeals &mdash; tracked until full resolution.'],
        ['q'=>'Do you provide prior authorization support?',
         'a'=>'Yes. We manage the entire prior auth lifecycle &mdash; securing pre-approvals for complex treatments, advanced imaging, and specialized therapies through ePA portal integrations.'],
        ['q'=>'Is my patient data secure and HIPAA compliant?',
         'a'=>'Absolutely. We use AES-256 encryption, secure VPNs, strict physical and digital access controls to keep all Protected Health Information (PHI) fully protected.'],
        ['q'=>'How long does it take to see revenue improvements?',
         'a'=>'Most practices see measurable revenue improvements within the first 30-60 days. Full optimization of your revenue cycle typically occurs within 90 days of onboarding.'],
      ];
      foreach ($faqs as $faq):
      ?>
      <div class="svc-faq-item" data-aos="fade-up">
        <button class="svc-faq-q" aria-expanded="false">
          <?php echo htmlspecialchars($faq['q']); ?>
          <svg class="svc-faq-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="svc-faq-a">
          <p><?php echo htmlspecialchars($faq['a']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
  </div>
</section>

<!-- &mdash;&mdash; CTA Banner &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<section class="svc-cta-banner" data-aos="fade-up">
  <div class="svc-cta-particles"></div>
  <div class="container">
    <div class="svc-cta-inner">
      <span class="svc-eyebrow" style="color:rgba(255,255,255,.7)">Ready to transform your practice?</span>
      <h2 class="svc-cta-title">Stop Leaving Revenue <span class="svc-gradient-text">on the Table</span></h2>
      <p class="svc-cta-sub">Partner with MEDINEXT SOLUTIONS and experience an average 30% revenue increase. Get expert RCM support tailored to your specialty at no upfront cost.</p>
      <div class="svc-cta-btns">
        <a href="free-practice-audit.php" class="svc-btn-primary">Get Your Free Practice Audit</a>
        <a href="contact.php" class="svc-btn-outline-light">Schedule Consultation</a>
      </div>
  </div>
</section>

</main>

<!-- &mdash;&mdash; Services Page CSS &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<style>
/* &mdash;&mdash; CRITICAL: Dark text overrides for white-background sections &mdash;&mdash; */
/* Site uses dark theme (white text globally) but service card/process/faq
   sections use white backgrounds. Must force dark text on those sections. */
.svc-grid-section,
.svc-grid-section *,
.svc-process,
.svc-process *,
.svc-faq,
.svc-faq * {
  color: #0c4a6e;
}
.svc-section-sub,
.svc-card-desc,
.svc-step-desc,
.svc-faq-a p {
  color: #6B7280 !important;
}
.svc-section-title,
.svc-card-title,
.svc-card-title a,
.svc-step-title,
.svc-faq-q {
  color: #0c4a6e !important;
}
.svc-card-kpi { color: #0ea5e9 !important; }
.svc-card:hover .svc-card-title a { color: #0ea5e9 !important; }
.svc-faq-q:hover,
.svc-faq-q[aria-expanded="true"] { color: #0ea5e9 !important; }

/* &mdash;&mdash; Root Variables &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-hero,.svc-grid-section,.svc-process,.svc-trust,.svc-faq,.svc-cta-banner{
  --svc-primary:#0ea5e9;--svc-dark:#0284c7;--svc-accent:#38bdf8;
  --svc-dark-bg:#0c4a6e;--svc-gray:#6B7280;--svc-border:#e5e7eb;
  --svc-white:#fff;--svc-transition:.3s ease;
}
.svc-gradient-text{
  background:linear-gradient(135deg,#0ea5e9,#38bdf8);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

/* &mdash;&mdash; Hero &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-hero{
  position:relative;min-height:90vh;display:flex;align-items:center;
  background:linear-gradient(135deg, #082f49 0%, #0c4a6e 50%, #0ea5e9 100%);
  overflow:hidden;padding:7rem 0 4rem;
}
.svc-hero-bg-canvas{
  position:absolute;inset:0;
  background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(255,107,53,.15) 0%,transparent 70%);
  animation:svc-pulse 6s ease-in-out infinite alternate;
}
@keyframes svc-pulse{0%{opacity:.6}100%{opacity:1}}
.svc-orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;animation:svc-float 8s ease-in-out infinite alternate;}
.svc-orb-1{width:600px;height:600px;background:#0ea5e9;top:-200px;right:-100px;animation-delay:0s;}
.svc-orb-2{width:400px;height:400px;background:#38bdf8;bottom:-100px;left:-100px;animation-delay:-3s;}
.svc-orb-3{width:300px;height:300px;background:#fff;top:40%;left:40%;animation-delay:-6s;opacity:.05;}
@keyframes svc-float{0%{transform:translateY(0)}100%{transform:translateY(-40px)}}

.svc-hero-inner{position:relative;z-index:2;text-align:center;}
.svc-breadcrumb ol{display:flex;align-items:center;justify-content:center;gap:.5rem;list-style:none;margin:0 0 1.5rem;padding:0;font-size:.8rem;color:rgba(255,255,255,.55);}
.svc-breadcrumb a{color:rgba(255,255,255,.7);text-decoration:none;} .svc-breadcrumb a:hover{color:#fff;}
.svc-hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.85);padding:.45rem 1.1rem;border-radius:50px;font-size:.8rem;font-weight:600;letter-spacing:.05em;margin-bottom:1.5rem;backdrop-filter:blur(8px);}
.svc-badge-dot{width:8px;height:8px;background:#10B981;border-radius:50%;animation:svc-blink 2s ease-in-out infinite;}
@keyframes svc-blink{0%,100%{opacity:1}50%{opacity:.3}}
.svc-hero-title{font-size:clamp(2.5rem,6vw,4.5rem);font-weight:300;color:#fff;line-height:1.1;margin-bottom:1.4rem;letter-spacing:-.02em;}
.svc-hero-title span{font-weight:700;}
.svc-hero-sub{font-size:1.1rem;color:rgba(255,255,255,.75);max-width:600px;margin:0 auto 2rem;line-height:1.75;}
.svc-hero-ctas{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:3.5rem;}
.svc-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:#38bdf8;color:#fff;padding:.85rem 2rem;border-radius:8px;font-weight:700;text-decoration:none;transition:all var(--svc-transition);}
.svc-btn-primary:hover{background:#E55A2B;transform:translateY(-3px);box-shadow:0 12px 32px rgba(255,107,53,.4);color:#fff;}
.svc-btn-outline{display:inline-flex;align-items:center;gap:.5rem;border:2px solid rgba(255,255,255,.4);color:#fff;padding:.85rem 2rem;border-radius:8px;font-weight:700;text-decoration:none;transition:all var(--svc-transition);background:rgba(255,255,255,.08);backdrop-filter:blur(8px);}
.svc-btn-outline:hover{background:rgba(255,255,255,.2);border-color:#fff;color:#fff;}

/* Stats row */
.svc-hero-stats{display:flex;align-items:center;justify-content:center;gap:2rem;flex-wrap:wrap;padding:1.8rem 2.5rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:16px;backdrop-filter:blur(12px);}
.svc-stat{text-align:center;}
.svc-stat-num{display:block;font-size:2rem;font-weight:800;color:#fff;line-height:1;}
.svc-stat-lbl{display:block;font-size:.75rem;color:rgba(255,255,255,.6);margin-top:.25rem;letter-spacing:.05em;}
.svc-stat-divider{width:1px;height:40px;background:rgba(255,255,255,.2);}
@media(max-width:600px){.svc-stat-divider{display:none}}

/* &mdash;&mdash; Grid Section &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-grid-section{background:#f8f9fa;padding:5rem 0;}
.svc-section-header{text-align:center;margin-bottom:3.5rem;}
.svc-eyebrow{font-size:.75rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--svc-primary);display:block;margin-bottom:.75rem;}
.svc-section-title{font-size:clamp(1.8rem,4vw,2.8rem);font-weight:700;color:#0c4a6e;margin-bottom:.75rem;}
.svc-section-sub{color:var(--svc-gray);max-width:580px;margin:0 auto;font-size:1rem;line-height:1.7;}

.svc-card-grid, .svc-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1.8rem;}
@media(max-width:992px){.svc-card-grid, .svc-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media(max-width:600px){.svc-card-grid, .svc-grid{grid-template-columns:minmax(0,1fr);}}

.svc-card{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.06);transition:transform var(--svc-transition),box-shadow var(--svc-transition);}
.svc-card:hover{transform:translateY(-8px);box-shadow:0 20px 60px rgba(0,82,204,.15);}

.svc-card-img-wrap{display:block;position:relative;overflow:hidden;aspect-ratio:16/9;}
.svc-card-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease;}
.svc-card:hover .svc-card-img-wrap img{transform:scale(1.06);}
.svc-card-img-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,.5) 100%);}
.svc-card-tag{position:absolute;top:.75rem;left:.75rem;background:var(--tag-color,#0ea5e9);color:#fff;font-size:.65rem;font-weight:700;letter-spacing:.08em;padding:.2rem .6rem;border-radius:4px;text-transform:uppercase;}
.svc-card-hover-icon{position:absolute;bottom:.75rem;right:.75rem;width:40px;height:40px;background:rgba(255,255,255,.95);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;transform:scale(0) rotate(-45deg);transition:transform .35s cubic-bezier(.34,1.56,.64,1);}
.svc-card:hover .svc-card-hover-icon{transform:scale(1) rotate(0);}

.svc-card-body{padding:1.4rem;}
.svc-card-icon-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:.9rem;}
.svc-card-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.svc-card-kpi{font-size:.7rem;font-weight:700;color:var(--svc-primary);background:#EFF6FF;padding:.2rem .65rem;border-radius:50px;text-align:right;}
.svc-card-title{font-size:1.05rem;font-weight:700;color:#0c4a6e;margin-bottom:.5rem;}
.svc-card-title a{color:inherit;text-decoration:none;transition:color var(--svc-transition);}
.svc-card-title a:hover{color:var(--svc-primary);}
.svc-card-desc{font-size:.85rem;color:var(--svc-gray);line-height:1.65;margin-bottom:1rem;}
.svc-card-link{display:inline-flex;align-items:center;gap:.4rem;font-size:.85rem;font-weight:700;text-decoration:none;transition:gap var(--svc-transition);}
.svc-card-link:hover{gap:.7rem;}

/* &mdash;&mdash; Process Section &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-process{background:#fff;padding:5rem 0;}
.svc-process-steps{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:2rem;margin-top:3rem;}
@media(max-width:768px){.svc-process-steps{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media(max-width:500px){.svc-process-steps{grid-template-columns:minmax(0,1fr);}}

.svc-step{text-align:center;padding:2rem 1.5rem;border:1px solid var(--svc-border);border-radius:16px;position:relative;transition:all var(--svc-transition);}
.svc-step:hover{border-color:var(--svc-primary);box-shadow:0 8px 32px rgba(0,82,204,.1);transform:translateY(-4px);}
.svc-step-num{position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:var(--svc-primary);color:#fff;font-size:.7rem;font-weight:800;padding:.15rem .6rem;border-radius:0 0 8px 8px;letter-spacing:.05em;}
.svc-step-icon{width:64px;height:64px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:var(--svc-primary);margin:1.5rem auto 1rem;}
.svc-step-title{font-size:1rem;font-weight:700;color:#0c4a6e;margin-bottom:.5rem;}
.svc-step-desc{font-size:.85rem;color:var(--svc-gray);line-height:1.65;}

/* &mdash;&mdash; Trust &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-trust{background:linear-gradient(135deg,#0c4a6e 0%,#0ea5e9 100%);padding:4rem 0;}
.svc-trust-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:2rem;}
@media(max-width:768px){.svc-trust-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
.svc-trust-item{text-align:center;color:#fff;}
.svc-trust-item i{font-size:2.5rem;color:#10B981;display:block;margin-bottom:1rem;}
.svc-trust-item h4{font-size:1.1rem;font-weight:700;margin-bottom:.4rem;}
.svc-trust-item p{font-size:.85rem;color:rgba(255,255,255,.65);margin:0;}

/* &mdash;&mdash; FAQ &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-faq{background:#f8f9fa;padding:5rem 0;}
.svc-faq-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1.2rem;margin-top:3rem;}
@media(max-width:768px){.svc-faq-grid{grid-template-columns:minmax(0,1fr);}}

.svc-faq-item{background:#fff;border-radius:12px;border:1px solid var(--svc-border);overflow:hidden;}
.svc-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1.2rem 1.4rem;background:none;border:none;font-size:.95rem;font-weight:600;color:#0c4a6e;cursor:pointer;text-align:left;transition:color var(--svc-transition);}
.svc-faq-q:hover{color:var(--svc-primary);}
.svc-faq-q[aria-expanded="true"]{color:var(--svc-primary);}
.svc-faq-chevron{flex-shrink:0;transition:transform .3s;}
.svc-faq-q[aria-expanded="true"] .svc-faq-chevron{transform:rotate(180deg);}
.svc-faq-a{max-height:0;overflow:hidden;transition:max-height .35s ease;}
.svc-faq-a.open{max-height:300px;}
.svc-faq-a p{padding:.2rem 1.4rem 1.2rem;font-size:.88rem;color:var(--svc-gray);line-height:1.7;margin:0;}

/* &mdash;&mdash; CTA Banner &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; */
.svc-cta-banner{position:relative;background:linear-gradient(135deg,#0c4a6e 0%,#0284c7 60%,#0ea5e9 100%);padding:5rem 0;overflow:hidden;text-align:center;}
.svc-cta-inner{position:relative;z-index:2;}
.svc-cta-title{font-size:clamp(2rem,4vw,3rem);font-weight:700;color:#fff;margin:.75rem auto 1rem;max-width:600px;}
.svc-cta-sub{color:rgba(255,255,255,.75);max-width:550px;margin:0 auto 2rem;font-size:1rem;line-height:1.7;}
.svc-cta-btns{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;}
.svc-btn-outline-light{display:inline-flex;align-items:center;gap:.5rem;border:2px solid rgba(255,255,255,.4);color:#fff;padding:.85rem 2rem;border-radius:8px;font-weight:700;text-decoration:none;transition:all var(--svc-transition);background:rgba(255,255,255,.08);}
.svc-btn-outline-light:hover{background:rgba(255,255,255,.2);color:#fff;border-color:#fff;}
</style>

<!-- &mdash;&mdash; Services Page JS: FAQ accordion + CountUp &mdash;&mdash;&mdash; -->
<script>
(function(){
  /* FAQ Accordion */
  document.querySelectorAll('.svc-faq-q').forEach(function(btn){
    btn.addEventListener('click',function(){
      var isOpen = btn.getAttribute('aria-expanded') === 'true';
      /* Close all */
      document.querySelectorAll('.svc-faq-q').forEach(function(b){
        b.setAttribute('aria-expanded','false');
        var a = b.nextElementSibling;
        if(a) a.classList.remove('open');
      });
      /* Open clicked if was closed */
      if(!isOpen){
        btn.setAttribute('aria-expanded','true');
        var ans = btn.nextElementSibling;
        if(ans) ans.classList.add('open');
      }
    });
  });

  /* CountUp for stats */
  function countUp(el){
    var target = parseFloat(el.getAttribute('data-countup'));
    var suffix = el.getAttribute('data-suffix') || '';
    var duration = 1800;
    var start = performance.now();
    function step(now){
      var elapsed = now - start;
      var progress = Math.min(elapsed / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(target * eased) + suffix;
      if(progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if('IntersectionObserver' in window){
    var obs = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ countUp(e.target); obs.unobserve(e.target); }
      });
    },{threshold:.5});
    document.querySelectorAll('[data-countup]').forEach(function(el){ obs.observe(el); });
  }
})();
</script>

<!-- &mdash;&mdash; Structured Data &mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash; -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {"@type":"BreadcrumbList","@id":"https://medinextsolutions.com/medical-billing-services/#breadcrumb","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://medinextsolutions.com/"},{"@type":"ListItem","position":2,"name":"Medical Billing Services","item":"https://medinextsolutions.com/medical-billing-services/"}]},
    {"@type":"MedicalWebPage","@id":"https://medinextsolutions.com/medical-billing-services/#webpage","url":"https://medinextsolutions.com/medical-billing-services/","name":"Comprehensive Medical Billing Services | MEDINEXT SOLUTIONS","description":"Expert medical billing services with 98% clean claim rate across 15 healthcare specialties."}
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
