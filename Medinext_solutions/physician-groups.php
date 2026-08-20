<?php
/**
 * MEDINEXT SOLUTIONS - Physician Groups Billing
 */

$pageTitle = 'Physician Groups Billing | MEDINEXT SOLUTIONS';
$pageDescription = 'Expert RCM and billing solutions for physician groups.';
$pageKeywords = 'physician groups, medical billing, RCM, Medinext';

require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- PAGE HERO -->
<!-- ============================================ -->
<main id="main-content">
    <!-- Hero Section -->
    <header class="page-hero text-white py-5" style="background: linear-gradient(135deg, #0056D2, #00C9A7);">
        <div class="container mt-5 pt-5 pb-4">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="services.php" class="text-white text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Physician Groups Billing</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Physician Group Billing Services</h1>
            <p class="lead mb-4">Master multi-provider credentialing, optimize Group NPI structures, and navigate the complex compliance landscape of Incident-To billing to protect your practice's revenue.</p>
            <div class="hero-cta">
                <a href="free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary me-3 mb-2">Get Free Practice Audit</a>
                <a href="tel:8627992199" class="btn btn-outline-light btn-lg mb-2"><i class="ph ph-phone"></i> 862-799-2199</a>
            </div>
        </div>
    </header>

    <!-- Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="service-content">
                        <h2 class="h3 fw-bold text-primary mb-3">Enterprise RCM for Multi-Provider Practices</h2>
                        <p>Managing the revenue cycle for a multi-provider physician group is exponentially more complex than billing for a solo practitioner. Credentialing bottlenecks, incorrect NPI mapping on the CMS-1500, and non-compliant Advanced Practice Provider (APP) utilization can result in massive revenue leakage and severe OIG audit risk.</p>
                        <p>MEDINEXT SOLUTIONS delivers enterprise-grade <a href="revenue-cycle-management/">revenue cycle management</a> specifically designed for growing medical groups. We standardize your charge capture workflows across all locations and specialties, ensuring that every provider is properly linked to your Group NPI (Type 2). We maintain a <strong>98% clean claim rate</strong>, ensuring your practice scales without sacrificing cash flow.</p>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Group NPI and Provider Mapping</h2>
                        <p>When a physician joins a group practice, they cannot simply bill under their individual Type 1 NPI. Claims must be submitted with the group's Type 2 NPI as the billing provider, and the individual physician's Type 1 NPI as the rendering provider.</p>
                        <ul>
                            <li><strong>PECOS and CAQH Management</strong>: We manage the complex process of linking new physicians to your group's Medicare and Medicaid enrollments via PECOS. We ensure their CAQH profiles are updated with your group's TIN and locations, preventing out-of-network denials.</li>
                            <li><strong>Locum Tenens Billing (Modifier Q6)</strong>: If a physician is temporarily absent and you bring in a substitute, you can bill for their services under the regular physician's NPI using modifier Q6 (for up to 60 continuous days). We track these timelines rigidly to ensure compliance.</li>
                        </ul>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Mastering "Incident-To" Billing</h2>
                        <p>Utilizing Nurse Practitioners (NPs) and Physician Assistants (PAs) is vital for practice profitability. Generally, Medicare pays APPs at 85% of the physician fee schedule. However, under "incident-to" rules, you can bill APP services under the supervising physician's NPI and receive 100% reimbursement—if strict criteria are met.</p>
                        <p>Incident-to billing is one of the highest OIG audit targets. We audit your documentation to ensure compliance with the "direct supervision" requirement (the physician must be present in the office suite and immediately available) and verify that the APP is treating an established problem under an established plan of care. If a new problem is addressed, it must be billed under the APP's own NPI at 85%.</p>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Split/Shared Visits in the Facility Setting</h2>
                        <p>For physician groups rounding in hospitals, the rules for split/shared E/M visits between a physician and an APP are complex. CMS now requires that the provider who performs the "substantive portion" of the visit (either the history, exam, MDM, or more than half of the total time) is the one who bills for the service. We provide targeted training to your providers to ensure they clearly document who performed the substantive portion, appending <strong>Modifier FS</strong> to the claim as required by Medicare.</p>
                    </article>

                    <!-- FAQ Section -->
                    <section class="mt-5 pt-4 border-top" id="faq">
                        <h2 class="h3 fw-bold mb-4">Frequently Asked Questions</h2>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        What is the difference between a Type 1 and Type 2 NPI?
                                    </button>
                                </h3>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        A Type 1 NPI is issued to an individual healthcare provider (like a physician or NP) and stays with them throughout their career. A Type 2 NPI is issued to an organization or group practice. In a group setting, claims are billed under the Type 2 NPI, with the specific doctor's Type 1 NPI listed as the rendering provider.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What are the requirements for "incident-to" billing?
                                    </button>
                                </h3>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        To bill an APP's service incident-to a physician (receiving 100% payment), the patient must be established, the physician must have created the original plan of care, the APP must be following that plan, and a physician from the group must be physically present in the office suite during the service (direct supervision).
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can I bill incident-to for a new patient?
                                    </button>
                                </h3>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        No. If an APP evaluates a new patient, or evaluates an established patient for a completely new medical problem, you cannot bill incident-to. The service must be billed under the APP's own NPI, which Medicare will reimburse at 85% of the physician fee schedule.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        How does Locum Tenens (Modifier Q6) work?
                                    </button>
                                </h3>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        If a regular group physician is absent (e.g., vacation, illness), you can hire a locum tenens (substitute) physician. You bill the substitute's services using the regular physician's NPI, appending modifier Q6 to the CPT codes. This is limited to a maximum of 60 continuous days per locum physician.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        What is the FS modifier used for?
                                    </button>
                                </h3>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Modifier FS is appended to E/M codes to indicate a split or shared visit provided by both a physician and a non-physician practitioner (NPP) in a facility setting (like a hospital). It must be billed under the NPI of the provider who performed the substantive portion of the visit.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="sticky-top" style="top: 100px;">
                        <div class="card shadow-sm border-0 mb-4 bg-light">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold card-title mb-3">Related Billing Services</h3>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="medical-billing-services/" class="text-decoration-none d-flex align-items-center"><i class="ph ph-caret-right text-primary me-2"></i> All Billing Services</a></li>
                                    <li class="mb-2"><a href="provider-credentialing-services/" class="text-decoration-none d-flex align-items-center"><i class="ph ph-caret-right text-primary me-2"></i> Credentialing</a></li>
                                    <li class="mb-2"><a href="denial-management-services/" class="text-decoration-none d-flex align-items-center"><i class="ph ph-caret-right text-primary me-2"></i> Denial Management</a></li>
                                    <li class="mb-2"><a href="revenue-cycle-management/" class="text-decoration-none d-flex align-items-center"><i class="ph ph-caret-right text-primary me-2"></i> Revenue Cycle Management</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card shadow border-0 text-white" style="background: var(--bs-primary);">
                            <div class="card-body p-4 text-center">
                                <h3 class="h5 fw-bold mb-3">Need Immediate Help?</h3>
                                <p class="small mb-4">Speak with a group billing expert today. Available 24/7 for support.</p>
                                <a href="tel:8627992199" class="btn btn-light text-primary w-100 mb-2 py-2 fw-bold"><i class="ph ph-phone-call"></i> Call 862-799-2199</a>
                                <a href="contact/" class="btn btn-outline-light w-100 py-2">Contact Us</a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="trust-badges py-5 bg-light border-top border-bottom">
        <div class="container">
            <div class="row g-4 text-center justify-content-center">
                <div class="col-6 col-md-3">
                    <i class="ph ph-check-circle text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">98% Accuracy</h3>
                    <p class="text-muted small mb-0">Clean Claim Rate</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-users text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">Multi-Provider</h3>
                    <p class="text-muted small mb-0">Scalable Solutions</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-shield-check text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">OIG Compliant</h3>
                    <p class="text-muted small mb-0">Audit Defense</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-certificate text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">Credentialing</h3>
                    <p class="text-muted small mb-0">PECOS Experts</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-5 border-bottom">
        <div class="container text-center py-4">
            <i class="ph ph-quotes text-primary display-3 opacity-25 mb-3"></i>
            <blockquote class="blockquote fs-4 fw-medium mb-4 mx-auto" style="max-width: 800px;">
                "As we expanded from 3 to 15 providers, our billing fell apart. Denials for incorrect NPIs and credentialing delays cost us a fortune. MEDINEXT SOLUTIONS took over our entire RCM and credentialing process, built a compliant incident-to workflow, and stabilized our cash flow completely."
            </blockquote>
            <cite class="d-block text-muted fw-bold">- Dr. William Peterson, Managing Partner</cite>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-5 text-center text-white" style="background: var(--bs-dark);">
        <div class="container py-4">
            <h2 class="display-5 fw-bold mb-3">Stop Leaving Money on the Table</h2>
            <p class="lead mb-4 mx-auto" style="max-width: 700px;">Partner with MEDINEXT SOLUTIONS and seamlessly scale your physician group. Get expert RCM support tailored to multi-provider practices.</p>
            <a href="free-practice-audit/" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg">Get Your Free Group Audit</a>
        </div>
    </section>
</main>

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "https://medinextsolutions.com/physician-groups/#breadcrumb",
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
          "name": "Medical Billing Services",
          "item": "https://medinextsolutions.com/medical-billing-services/"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Physician Groups Billing",
          "item": "https://medinextsolutions.com/physician-groups/"
        }
      ]
    },
    {
      "@type": "MedicalWebPage",
      "@id": "https://medinextsolutions.com/physician-groups/#webpage",
      "url": "https://medinextsolutions.com/physician-groups/",
      "name": "Physician Group Billing Services | MEDINEXT SOLUTIONS",
      "specialty": "https://schema.org/MedicalBusiness",
      "about": [
        {"@type": "MedicalSpecialty", "name": "Physician Group"},
        {"@type": "MedicalSpecialty", "name": "Medical Billing and Coding"}
      ]
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/physician-groups/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is the difference between a Type 1 and Type 2 NPI?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "A Type 1 NPI is for an individual provider. A Type 2 NPI is for an organization or group practice. Group claims are billed under the Type 2 NPI, with the specific doctor's Type 1 NPI listed as the rendering provider."
          }
        },
        {
          "@type": "Question",
          "name": "What are the requirements for incident-to billing?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The patient must be established, the physician must have created the care plan, the APP must follow it, and a physician from the group must be physically present in the office suite during the service."
          }
        },
        {
          "@type": "Question",
          "name": "Can I bill incident-to for a new patient?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. New patients or new medical problems must be billed under the APP's own NPI, which Medicare will reimburse at 85% of the physician fee schedule."
          }
        },
        {
          "@type": "Question",
          "name": "How does Locum Tenens (Modifier Q6) work?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You bill a substitute physician's services using the absent regular physician's NPI, appending modifier Q6. This is limited to a maximum of 60 continuous days per locum."
          }
        },
        {
          "@type": "Question",
          "name": "What is the FS modifier used for?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Modifier FS indicates a split/shared E/M visit provided by both a physician and an NPP in a facility setting. It must be billed under the NPI of the provider who performed the substantive portion."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
