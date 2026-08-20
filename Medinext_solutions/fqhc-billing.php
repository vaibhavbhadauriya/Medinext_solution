<?php
/**
 * MEDINEXT SOLUTIONS - FQHCs & Community Health Centers Billing
 */

$pageTitle = 'FQHCs & Community Health Centers Billing | MEDINEXT SOLUTIONS';
$pageDescription = 'Specialized billing and coding for FQHCs.';
$pageKeywords = 'fqhc billing, medical billing, RCM, Medinext';

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
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">FQHC Billing</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">FQHC Billing Services</h1>
            <p class="lead mb-4">Master the PPS rate methodology, automate complex MCO wrap-around payments, and guarantee flawless encounter data to survive HRSA and state Medicaid audits.</p>
            <div class="hero-cta">
                <a href="free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary me-3 mb-2">Get Free Practice Audit</a>
                <a href="tel:9088290133" class="btn btn-outline-light btn-lg mb-2"><i class="ph ph-phone"></i> 908-829-0133</a>
            </div>
        </div>
    </header>

    <!-- Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="service-content">
                        <h2 class="h3 fw-bold text-primary mb-3">Navigating the FQHC PPS Maze</h2>
                        <p>Federally Qualified Health Centers (FQHCs) operate under entirely different reimbursement rules than standard physician practices. Billing under the Prospective Payment System (PPS) requires specialized G-codes, specific modifiers, and exact revenue code mapping. A single configuration error in your EHR or clearinghouse can result in an entire month of Medicare and Medicaid encounters being rejected.</p>
                        <p>MEDINEXT SOLUTIONS provides specialized <a href="revenue-cycle-management/">revenue cycle management</a> engineered exclusively for FQHCs and Community Health Centers. Our certified coders understand the nuances of the FQHC PPS methodology (G0466-G0470). We ensure every qualifying visit is accurately captured, modifiers are correctly applied to prevent bundling, and your cost reports are supported by flawless encounter data. We maintain a <strong>98% clean claim rate</strong>, ensuring your grant-funded center remains financially viable.</p>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Mastering Wrap-Around Payments</h2>
                        <p>When an FQHC provides services to a patient covered by a Medicaid Managed Care Organization (MCO) or a Medicare Advantage (MA) plan, the payment from the health plan is often lower than the center's established PPS encounter rate. FQHCs are legally entitled to a supplemental "wrap-around" payment from the state Medicaid agency or Medicare to make up this difference.</p>
                        <ul>
                            <li><strong>Automated Wrap Capture</strong>: Capturing these wrap payments manually is a logistical nightmare that results in massive revenue leakage. We implement automated secondary billing workflows. The moment the MCO remits the primary payment, our system generates the wrap-around claim to the state or MAC, ensuring you receive 100% of your entitled PPS rate without administrative delay.</li>
                            <li><strong>MA Plan Wrap-Arounds</strong>: Medicare Advantage wrap-around billing requires specific TOB (Type of Bill) 771 for FQHCs, alongside complex condition codes. We manage this process end-to-end, preventing MAC rejections.</li>
                        </ul>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Encounter Data and HRSA Compliance</h2>
                        <p>FQHCs are heavily audited not just by payers, but by the Health Resources and Services Administration (HRSA). Your grant funding depends on the accuracy of your UDS (Uniform Data System) reporting.</p>
                        <p>We perform rigorous encounter data validation before claims are submitted. We ensure that non-qualifying visits (e.g., blood draws, brief nursing visits) are not erroneously billed as PPS encounters, protecting you from crippling recoupments during federal audits. Conversely, if a patient has both a medical and a mental health encounter on the same day, we ensure both are billed correctly using the required modifiers (e.g., Modifier 59) to capture dual reimbursement where state and federal rules allow.</p>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Sliding Fee Scale and Patient Balances</h2>
                        <p>Managing the sliding fee discount program is a critical compliance requirement for FQHCs. We integrate your sliding fee schedules directly into our billing workflows. When assessing patient responsibility, we ensure the balance accurately reflects the patient's approved nominal fee or discounted percentage, preventing compliance violations and improving patient collections.</p>
                    </article>

                    <!-- FAQ Section -->
                    <section class="mt-5 pt-4 border-top" id="faq">
                        <h2 class="h3 fw-bold mb-4">Frequently Asked Questions</h2>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        What is the FQHC Prospective Payment System (PPS)?
                                    </button>
                                </h3>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Under PPS, Medicare (and typically Medicaid) pays FQHCs a national encounter-based rate for qualifying visits, regardless of the specific services provided. You must bill using specific G-codes (e.g., G0466 for a new patient) along with the qualifying CPT codes, and payment is based on the G-code.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What is a wrap-around (supplemental) payment?
                                    </button>
                                </h3>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        If a Medicaid MCO or Medicare Advantage plan pays the FQHC less than their established PPS encounter rate, the FQHC must bill the state Medicaid agency or Medicare MAC for a supplemental "wrap-around" payment to cover the difference.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can an FQHC bill for multiple encounters on the same day?
                                    </button>
                                </h3>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        For Medicare, you generally cannot bill multiple medical encounters for the same patient on the same day unless the patient suffers an illness or injury subsequent to the first visit. However, you can typically bill a medical encounter and a mental health encounter on the same day. Medicaid rules vary by state.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        What constitutes a "qualifying visit" for PPS billing?
                                    </button>
                                </h3>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        A qualifying visit must be a face-to-face encounter between the patient and a physician, NP, PA, CNM, CP, or CSW for the provision of medical or mental health services. Incidental services, such as a nurse administering a vaccine or drawing blood, do not qualify for a PPS encounter payment.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        How are care management services (CCM/TCM) billed in an FQHC?
                                    </button>
                                </h3>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        FQHCs bill for Chronic Care Management (CCM) and general care management using a specific code, G0511. They bill for Psychiatric Collaborative Care Model (CoCM) services using G0512. These are paid separately from the PPS encounter rate.
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
                                <p class="small mb-4">Speak with an FQHC billing expert today. Available 24/7 for support.</p>
                                <a href="tel:9088290133" class="btn btn-light text-primary w-100 mb-2 py-2 fw-bold"><i class="ph ph-phone-call"></i> Call 908-829-0133</a>
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
                    <h3 class="h5 fw-bold mb-1">Wrap Payments</h3>
                    <p class="text-muted small mb-0">Automated Capture</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-shield-check text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">HRSA/UDS</h3>
                    <p class="text-muted small mb-0">Fully Compliant</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-certificate text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">FQHC Experts</h3>
                    <p class="text-muted small mb-0">Certified Coders</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-5 border-bottom">
        <div class="container text-center py-4">
            <i class="ph ph-quotes text-primary display-3 opacity-25 mb-3"></i>
            <blockquote class="blockquote fs-4 fw-medium mb-4 mx-auto" style="max-width: 800px;">
                "We were losing over $40,000 a month in missed wrap-around payments from Medicaid MCOs. MEDINEXT SOLUTIONS automated the entire secondary billing process and cleaned up our encounter data. The ROI for our Community Health Center was immediate."
            </blockquote>
            <cite class="d-block text-muted fw-bold">- Maria Gonzalez, Executive Director, FQHC</cite>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-5 text-center text-white" style="background: var(--bs-dark);">
        <div class="container py-4">
            <h2 class="display-5 fw-bold mb-3">Stop Leaving Money on the Table</h2>
            <p class="lead mb-4 mx-auto" style="max-width: 700px;">Partner with MEDINEXT SOLUTIONS and ensure you receive every dollar of your PPS rate. Get expert RCM support tailored to your FQHC.</p>
            <a href="free-practice-audit/" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg">Get Your Free Clinic Audit</a>
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
      "@id": "https://medinextsolutions.com/fqhc-billing/#breadcrumb",
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
          "name": "FQHC Billing",
          "item": "https://medinextsolutions.com/fqhc-billing/"
        }
      ]
    },
    {
      "@type": "MedicalWebPage",
      "@id": "https://medinextsolutions.com/fqhc-billing/#webpage",
      "url": "https://medinextsolutions.com/fqhc-billing/",
      "name": "FQHC Billing Services | MEDINEXT SOLUTIONS",
      "specialty": "https://schema.org/MedicalBusiness",
      "about": [
        {"@type": "MedicalSpecialty", "name": "FQHC"},
        {"@type": "MedicalSpecialty", "name": "Medical Billing and Coding"}
      ]
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/fqhc-billing/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is the FQHC Prospective Payment System (PPS)?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Under PPS, Medicare pays FQHCs a national encounter-based rate for qualifying visits, using specific G-codes (e.g., G0466) along with qualifying CPT codes."
          }
        },
        {
          "@type": "Question",
          "name": "What is a wrap-around (supplemental) payment?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "If a Medicaid MCO or MA plan pays the FQHC less than their established PPS rate, the FQHC must bill the state or MAC for a supplemental 'wrap-around' payment to cover the difference."
          }
        },
        {
          "@type": "Question",
          "name": "Can an FQHC bill for multiple encounters on the same day?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "For Medicare, you generally cannot bill multiple medical encounters for the same patient on the same day unless due to a subsequent illness/injury. You can typically bill a medical and a mental health encounter on the same day."
          }
        },
        {
          "@type": "Question",
          "name": "What constitutes a 'qualifying visit' for PPS billing?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "A qualifying visit must be a face-to-face encounter between the patient and a physician, NP, PA, CNM, CP, or CSW for medical or mental health services. Incidental services (e.g., blood draws) do not qualify."
          }
        },
        {
          "@type": "Question",
          "name": "How are care management services (CCM/TCM) billed in an FQHC?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "FQHCs bill for CCM and general care management using G0511, and Psychiatric Collaborative Care Model (CoCM) services using G0512. These are paid separately from the PPS rate."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
