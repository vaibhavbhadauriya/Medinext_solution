<?php
/**
 * MEDINEXT SOLUTIONS - Radiation Oncology Billing
 */

$pageTitle = 'Radiation Oncology Billing | MEDINEXT SOLUTIONS';
$pageDescription = 'Radiation oncology billing and coding.';
$pageKeywords = 'radiation oncology billing, medical billing, RCM, Medinext';

require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- PAGE HERO -->
<!-- ============================================ -->
<main id="main-content">
    <!-- Hero Section -->
    <header class="page-hero text-white py-5" style="background: linear-gradient(135deg, rgba(10, 38, 71, 0.92) 0%, rgba(0, 82, 204, 0.88) 60%, rgba(0, 201, 167, 0.82) 100%), url('<?php echo $baseUrl; ?>/assets/images/decorative%20images/Oncology.jpg') center/cover no-repeat;">
        <div class="container mt-5 pt-5 pb-4">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="services.php" class="text-white text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Radiation Oncology Billing</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Radiation Oncology Billing Services</h1>
            <p class="lead mb-4">Survive the massive 2026 CPT overhaul. Navigate the deletion of legacy IMRT codes, master the new complexity-based delivery tiers, and prevent devastating NCCI bundling denials.</p>
            <div class="hero-cta">
                <a href="free-practice-audit/" class="btn btn-light btn-lg fw-bold text-dark me-3 mb-2">Get Free Practice Audit</a>
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
                        <h2 class="h3 fw-bold text-primary mb-3">The Most Complex Restructuring in Decades</h2>
                        <p>Radiation Oncology billing is currently undergoing a massive structural transformation. The 2026 CPT updates have completely rewritten the rules for treatment delivery and image guidance, bundling previously distinct services into comprehensive, complexity-based tiers. Freestanding cancer centers and hospital-based radiation oncology departments using outdated coding templates will face immediate, catastrophic cash flow interruptions.</p>
                        <p>MEDINEXT SOLUTIONS delivers specialized <a href="revenue-cycle-management/">revenue cycle management</a> engineered exclusively for Radiation Oncology. Our RO-certified coding team operates within your oncology-specific EHR (ARIA or MOSAIQ), ensuring every simulation, physics calculation, and daily treatment is captured correctly under the radically altered 2026 guidelines. We maintain a <strong>98% clean claim rate</strong>, shielding your practice from RO Model compliance audits.</p>

                        <figure class="figure my-4 w-100">
                            <img src="<?php echo $baseUrl; ?>/assets/images/decorative%20images/pexels-jw-medicare-pvt-ltd-2157283432-34642915.jpg" alt="Radiation oncology linear accelerator treatment planning and dosimetry delivery suite" loading="lazy" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" style="max-height: 420px;" />
                            <figcaption class="figure-caption text-muted text-center mt-2 small">Expert coding for IMRT, stereotactic radiosurgery (SRS/SBRT), and complex dosimetry planning.</figcaption>
                        </figure>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">The 2026 IMRT and Delivery Overhaul</h2>
                        <p>For 2026, the AMA executed a total overhaul of radiation treatment delivery codes to align with the bundled payments favored by CMS.</p>
                        <ul>
                            <li><strong>Legacy IMRT Codes DELETED</strong>: The long-standing IMRT delivery codes (77385 and 77386) have been officially deleted.</li>
                            <li><strong>New Complexity Tiers</strong>: Delivery is now billed using new complexity-based tier codes: <strong>77402 (Level 1), 77407 (Level 2), and 77412 (Level 3)</strong>. Selecting the correct tier requires a deep understanding of the exact number of treatment sites, fractions, and the sophistication of the beam modulation utilized.</li>
                            <li><strong>IGRT Bundling</strong>: Daily Image-Guided Radiation Therapy (IGRT) is no longer a separate line item for technical delivery. The technical component of imaging is now <strong>bundled directly into the new tier codes</strong> (77402-77412). Billing separate technical imaging charges will trigger instant NCCI edits.</li>
                        </ul>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Professional Image Guidance (77387)</h2>
                        <p>While the technical component of IGRT was bundled, the physician's professional work remains billable. The legacy CT guidance code (77014) has been deleted for radiation oncology purposes.</p>
                        <p>All professional image guidance for radiation treatment delivery has been <strong>consolidated to CPT 77387 (with modifier 26)</strong>. However, the documentation requirements are immense. The radiation oncologist must personally review the images, document the patient setup adjustments, and sign the daily report. A simple "images reviewed" note will not survive an OIG audit; we ensure your physicians meet the strict narrative requirements for 77387-26.</p>

                        <h2 class="h3 fw-bold text-primary mt-5 mb-3">Physics and Dosimetry Authorization</h2>
                        <p>Denials for Medical Radiation Physics (77336) and complex dosimetry (77307) are skyrocketing due to aggressive Medicare Advantage prior authorization requirements. We manage the entire pre-authorization lifecycle, ensuring that the initial treatment plan, simulation codes, and weekly physics reviews are all structurally aligned with the authorized clinical pathway. If an unexpected boost field is required, we secure the retro-authorization before the claims are filed.</p>
                    </article>

                    <!-- FAQ Section -->
                    <section class="mt-5 pt-4 border-top" id="faq">
                        <h2 class="h3 fw-bold mb-4">Frequently Asked Questions</h2>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        What happened to the IMRT delivery codes (77385/77386) in 2026?
                                    </button>
                                </h3>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        These codes were completely deleted for 2026. They have been replaced by a new system of complexity-based tier codes: 77402 (Level 1), 77407 (Level 2), and 77412 (Level 3). Your coders must map the specific parameters of the IMRT delivery to the correct new tier.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Can we still bill separately for daily IGRT?
                                    </button>
                                </h3>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        For the Technical Component (TC), no. IGRT is now bundled into the new delivery tier codes (77402-77412). However, you can still bill the Professional Component (26) of the image guidance using the consolidated code 77387-26, provided the physician documents their review of the images.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        What replaced the CT guidance code 77014?
                                    </button>
                                </h3>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Code 77014 was deleted for radiation oncology. All professional image guidance, regardless of modality (CT, MRI, ultrasound), is now billed using the single consolidated code 77387 (with modifier 26 for the professional component).
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        How often can we bill Medical Radiation Physics (77336)?
                                    </button>
                                </h3>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        CPT 77336 is billed once per five fractions (treatments) delivered, representing the continuing medical physics consultation. It requires documented review of the patient's chart by the qualified medical physicist. Billing this prematurely or without the required physics note triggers automatic denials.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        What is the difference between a freestanding and hospital-based center for billing?
                                    </button>
                                </h3>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Freestanding centers bill globally (both professional and technical components) on a CMS-1500 form. Hospital-based centers require split billing: the hospital bills the technical component (TC) on a UB-04, and the physician group bills the professional component (Modifier 26) on a CMS-1500. Misapplying these modifiers leads to massive revenue loss.
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
                                <p class="small mb-4">Speak with a radiation oncology billing expert today. Available 24/7 for support.</p>
                                <a href="tel:8627992199" class="btn btn-light text-dark w-100 mb-2 py-2 fw-bold"><i class="ph ph-phone-call"></i> Call 862-799-2199</a>
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
                    <h3 class="h5 fw-bold mb-1">ARIA/MOSAIQ</h3>
                    <p class="text-muted small mb-0">EHR Experts</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-shield-check text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">100% HIPAA</h3>
                    <p class="text-muted small mb-0">Fully Compliant</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="ph ph-certificate text-primary display-4 mb-3"></i>
                    <h3 class="h5 fw-bold mb-1">ROCC Certified</h3>
                    <p class="text-muted small mb-0">Expert Coders</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-5 border-bottom">
        <div class="container text-center py-4">
            <i class="ph ph-quotes text-primary display-3 opacity-25 mb-3"></i>
            <blockquote class="blockquote fs-4 fw-medium mb-4 mx-auto" style="max-width: 800px;">
                "The 2026 deletion of the legacy IMRT codes would have crippled our practice. MEDINEXT SOLUTIONS proactively updated our entire ARIA charge capture matrix to the new tier system months in advance. We didn't suffer a single disruption in our cash flow."
            </blockquote>
            <cite class="d-block text-muted fw-bold">- Dr. Sarah Jenkins, MD, Radiation Oncologist</cite>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-5 text-center text-white" style="background: var(--bs-dark);">
        <div class="container py-4">
            <h2 class="display-5 fw-bold mb-3">Stop Leaving Money on the Table</h2>
            <p class="lead mb-4 mx-auto" style="max-width: 700px;">Partner with MEDINEXT SOLUTIONS and experience a 30% average revenue increase. Get expert RCM support tailored to your cancer center.</p>
            <a href="free-practice-audit/" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg">Get Your Free Center Audit</a>
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
      "@id": "https://medinextsolutions.com/radiation-oncology-billing/#breadcrumb",
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
          "name": "Radiation Oncology Billing",
          "item": "https://medinextsolutions.com/radiation-oncology-billing/"
        }
      ]
    },
    {
      "@type": "MedicalWebPage",
      "@id": "https://medinextsolutions.com/radiation-oncology-billing/#webpage",
      "url": "https://medinextsolutions.com/radiation-oncology-billing/",
      "name": "Radiation Oncology Billing Services | MEDINEXT SOLUTIONS",
      "specialty": "https://schema.org/MedicalBusiness",
      "about": [
        {"@type": "MedicalSpecialty", "name": "Radiation Oncology"},
        {"@type": "MedicalSpecialty", "name": "Medical Billing and Coding"}
      ]
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/radiation-oncology-billing/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What happened to the IMRT delivery codes (77385/77386) in 2026?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "These codes were completely deleted for 2026. They have been replaced by a new system of complexity-based tier codes: 77402 (Level 1), 77407 (Level 2), and 77412 (Level 3)."
          }
        },
        {
          "@type": "Question",
          "name": "Can we still bill separately for daily IGRT?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "For the Technical Component (TC), no. IGRT is now bundled into the new delivery tier codes (77402-77412). However, you can still bill the Professional Component (26) of the image guidance using the consolidated code 77387-26."
          }
        },
        {
          "@type": "Question",
          "name": "What replaced the CT guidance code 77014?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Code 77014 was deleted for radiation oncology. All professional image guidance, regardless of modality (CT, MRI, ultrasound), is now billed using the single consolidated code 77387 (with modifier 26)."
          }
        },
        {
          "@type": "Question",
          "name": "How often can we bill Medical Radiation Physics (77336)?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "CPT 77336 is billed once per five fractions (treatments) delivered, representing the continuing medical physics consultation. It requires documented review of the patient's chart by the qualified medical physicist."
          }
        },
        {
          "@type": "Question",
          "name": "What is the difference between a freestanding and hospital-based center for billing?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Freestanding centers bill globally on a CMS-1500 form. Hospital-based centers require split billing: the hospital bills the technical component (TC) on a UB-04, and the physician group bills the professional component (Modifier 26) on a CMS-1500."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
