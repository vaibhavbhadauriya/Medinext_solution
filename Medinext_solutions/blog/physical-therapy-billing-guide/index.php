<?php require_once '../../includes/header.php'; ?>

<main id="main-content">
    <header class="page-hero text-white py-5" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
        <div class="container mt-5 pt-5 pb-4">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="" class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="blog/" class="text-white text-decoration-none">Blog</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Physical Therapy Billing</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Physical Therapy Billing: CPT Codes, Rules & Compliance Guide</h1>
            <p class="lead mb-4 text-light">Master the Medicare 8-Minute Rule, precise PT evaluation codes (97161-97163), and Modifier GP usage to completely eliminate physical therapy claim denials and maximize your rehab clinic?s cash flow.</p>
            <div class="d-flex align-items-center mb-3">
                <div class="badge bg-primary text-white p-2 me-3">Reading Time: 14 min</div>
                <div class="text-white small"><i class="ph ph-calendar-blank me-1"></i> Last Updated: January 2025</div>
            </div>
        </div>
    </header>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row">

                <!-- Main Article Content -->
                <div class="col-lg-8">
                    <article class="blog-post">

                        <!-- Author Box -->
                        <div class="card bg-light border-0 rounded-3 p-3 mb-5 d-flex flex-row align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                <i class="ph ph-wheelchair text-white fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold text-dark mb-1">MEDINEXT SOLUTIONS Editorial Team</p>
                                <p class="mb-0 small text-muted">AAPC-Certified Therapy Billing Experts | 10+ Years Experience</p>
                            </div>
                        </div>

                        <!-- Medical Billing Disclaimer -->
                        <div class="alert alert-secondary small text-muted border-0 mb-5 border-start border-4 border-secondary">
                            <strong>Disclaimer:</strong> Physical therapy billing regulations?specifically the Medicare 8-Minute Rule vs. AMA Rule?vary significantly between federal and commercial payers. The guidelines detailed below are for educational purposes based on prevailing 2025 CMS standards. Always verify specific Medicare Administrative Contractor (MAC) regulations in your jurisdiction.
                        </div>

                        <!-- Table of Contents -->
                        <div class="card shadow-sm border-0 mb-5 bg-white">
                            <div class="card-body p-4 bg-light rounded-3">
                                <h2 class="h5 fw-bold mb-3"><i class="ph ph-list-bullets text-primary me-2"></i> Table of Contents</h2>
                                <ul class="list-unstyled mb-0 d-flex flex-wrap">
                                    <li class="mb-2 w-50"><a href="#pt-evaluations" class="text-decoration-none text-dark hover-primary">1. PT Evaluations (97161-97163)</a></li>
                                    <li class="mb-2 w-50"><a href="#cpt-codes" class="text-decoration-none text-dark hover-primary">2. The Master PT Therapy CPT Code List</a></li>
                                    <li class="mb-2 w-50"><a href="#timed-untimed" class="text-decoration-none text-dark hover-primary">3. Untimed vs. Timed Codes</a></li>
                                    <li class="mb-2 w-50"><a href="#eight-minute-rule" class="text-decoration-none text-dark hover-primary">4. Medicare?s 8-Minute Rule Explained</a></li>
                                    <li class="mb-2 w-50"><a href="#modifiers" class="text-decoration-none text-dark hover-primary">5. Mandatory Modifiers (GP, 59, KX)</a></li>
                                    <li class="mb-2 w-50"><a href="#therapy-cap" class="text-decoration-none text-dark hover-primary">6. The Medicare Therapy Thresholds (Caps)</a></li>
                                    <li class="mb-2 w-50"><a href="#documentation" class="text-decoration-none text-dark hover-primary">7. Defensible Clinical Documentation</a></li>
                                    <li class="mb-2 w-50"><a href="#outsourcing" class="text-decoration-none text-dark hover-primary">8. Optimizing PT Revenue Cycles</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- AI Citable Block 1 -->
                        <div class="p-4 bg-light border-start border-4 border-primary shadow-sm mb-5 rounded-end">
                            <p class="mb-0 fw-medium">
                                <strong>What is the Medicare 8-Minute Rule for Physical Therapy?</strong> The Medicare 8-Minute Rule dictates how physical therapists bill for direct, one-on-one time-based CPT codes. To bill for a single 15-minute unit of a timed service (like 97110 Therapeutic Exercise), the therapist must perform that specific continuous service for a minimum of 8 cumulative minutes. If the service lasts 7 minutes or less, it cannot be billed sequentially.
                            </p>
                        </div>

                        <h2 id="pt-evaluations" class="fw-bold mb-3 mt-4">1. Billing PT Evaluations (97161-97163)</h2>
                        <p class="lh-lg mb-4">
                            In <a href="physical-therapy-billing/" class="text-primary fw-bold text-decoration-none">physical therapy billing</a>, the initial evaluation sets the entire diagnostic foundation for the patient?s Plan of Care (POC). Previously, therapists utilized one generic code for all evaluations. Today, therapists must explicitly code based on the clinical complexity of the patient's presentation across three strict tiers.
                        </p>
                        <p class="lh-lg mb-4">
                            These evaluation codes are <strong>untimed</strong>. Regardless of whether the evaluation takes 25 minutes or 70 minutes, you bill exactly one unit.
                        </p>
                        <ul class="lh-lg mb-4">
                            <li><strong>97161 (Low Complexity):</strong> Patient has no personal factors/comorbidities impacting the POC. Evaluation involves examining 1-2 elements from body structures, activity limitations, or participation restrictions. Clinical presentation is inherently stable.</li>
                            <li><strong>97162 (Moderate Complexity):</strong> Patient has 1-2 personal factors/comorbidities. Evaluation involves 3 elements from the above categories. Clinical presentation is evolving or changing.</li>
                            <li><strong>97163 (High Complexity):</strong> Patient has 3 or more personal factors/comorbidities. Evaluation examines 4 or more elements. Clinical presentation is explicitly unstable and unpredictable.</li>
                            <li><strong>97164 (Re-Evaluation):</strong> Used when there is a significant, documented change in the patient's condition that requires radically altering the original POC. Do not use this simply to write a progress report.</li>
                        </ul>

                        <h2 id="cpt-codes" class="fw-bold mb-3 mt-5">2. The Master PT Therapy CPT Code List</h2>
                        <p class="lh-lg mb-4">
                            Selecting the procedurally correct CPT code is the absolute core of <a href="therapy-billing-services/" class="text-primary fw-bold text-decoration-none">rehab billing</a>. A therapist cannot simply bill "exercise" for an hour; they must categorize the exact biomechanical intent of the exercise into 15-minute increments.
                        </p>

                        <div class="table-responsive mb-5 mt-4">
                            <table class="table table-bordered table-striped border-dark align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th class="text-white" style="width: 15%;">CPT Code</th>
                                        <th class="text-white" style="width: 35%;">Category / Name</th>
                                        <th class="text-white" style="width: 50%;">Clinical Definition & Intent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97110</td>
                                        <td><strong>Therapeutic Exercise</strong></td>
                                        <td>Exercises performed to develop strength, endurance, range of motion (ROM), or flexibility. Intent is physiological conditioning.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97112</td>
                                        <td><strong>Neuromuscular Re-education</strong></td>
                                        <td>Movement, balance, coordination, kinesthetic sense, posture, and proprioception activities (e.g., BAPS board, stabilization).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97116</td>
                                        <td><strong>Gait Training</strong></td>
                                        <td>Biomechanical training to improve the patient's walking ability, including stair climbing and usage of assistive devices.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97140</td>
                                        <td><strong>Manual Therapy Techniques</strong></td>
                                        <td>Mobilization, manipulation, manual lymphatic drainage, and manual traction to soft tissues/joints to increase pain-free ROM.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97530</td>
                                        <td><strong>Therapeutic Activities</strong></td>
                                        <td>Use of dynamic activities to improve <em>functional</em> performance (e.g., lifting, bending, transferring, throwing).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97010</td>
                                        <td><strong>Hot/Cold Packs</strong></td>
                                        <td>Application of a modality to one or more areas. (Note: Medicare explicitly bundles this code; it is virtually never reimbursed).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-5 text-center">97014 / G0283</td>
                                        <td><strong>Electrical Stimulation (Unattended)</strong></td>
                                        <td>Application of e-stim where the provider does not need to be actively present. G0283 is the specific code utilized for CMS Medicare.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mid-Article CTA -->
                        <div class="card border-0 bg-primary text-white text-center p-5 my-5 rounded-4 shadow-lg position-relative overflow-hidden">
                            <i class="ph-calculator position-absolute display-1 opacity-10" style="bottom: -10px; right: 10px;"></i>
                            <h3 class="h2 fw-bold mb-3 position-relative">Struggling with the 8-Minute Rule?</h3>
                            <p class="lead mb-4 position-relative">Miscalculating timed units is the leading cause of massive PT claim denials and RAC audits. Let MEDINEXT SOLUTIONS's AAPC experts scrub your coding automatically.</p>
                            <a href="free-practice-audit/" class="btn btn-light btn-lg fw-bold text-dark px-5 position-relative">Get a Free Coding Audit</a>
                        </div>

                        <h2 id="timed-untimed" class="fw-bold mb-3 mt-5">3. Untimed vs. Timed Codes</h2>
                        <p class="lh-lg mb-4">
                            The fundamental distinction in physical therapy coding lies between <strong>Untimed codes</strong> and <strong>Timed codes</strong>.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>Untimed Codes (Service-Based):</strong> These codes are billed as exactly ONE unit per date of service, regardless of whether the treatment took 12 minutes or 45 minutes to execute. Examples include PT Evaluations (97161-97163), Unattended E-Stim (G0283), and Hot/Cold packs (97010). If a therapist applies e-stim to a patient's shoulder and knee simultaneously, they still only bill <em>one</em> unit of G0283.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>Timed Codes (Time-Based):</strong> These codes require continuous, direct, one-on-one patient contact by the provider. They are billed in 15-minute increments (units). Examples include Therapeutic Exercise (97110) and Manual Therapy (97140). The amount of units billed is directly mathematically correlated to the length of face-to-face time spent.
                        </p>

                        <h2 id="eight-minute-rule" class="fw-bold mb-3 mt-5">4. Medicare?s 8-Minute Rule Explained</h2>
                        <p class="lh-lg mb-4">
                            Because a therapist rarely spends exactly 15.00 minutes on an exercise, <a href="https://www.cms.gov/" rel="noopener noreferrer" target="_blank" class="text-primary">Medicare (CMS)</a> enforces the notorious "8-Minute Rule" to calculate billable units across all timed codes provided in a single day.
                        </p>
                        
                        <div class="table-responsive mb-4 mt-3">
                            <table class="table table-bordered border-dark text-center align-middle" style="max-width: 500px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Total Timed Minutes</th>
                                        <th>Billable Units Allowed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>00 - 07 minutes</td><td class="text-danger fw-bold">0 Units</td></tr>
                                    <tr><td>08 - 22 minutes</td><td class="text-success fw-bold">1 Unit</td></tr>
                                    <tr><td>23 - 37 minutes</td><td class="text-success fw-bold">2 Units</td></tr>
                                    <tr><td>38 - 52 minutes</td><td class="text-success fw-bold">3 Units</td></tr>
                                    <tr><td>53 - 67 minutes</td><td class="text-success fw-bold">4 Units</td></tr>
                                    <tr><td>68 - 82 minutes</td><td class="text-success fw-bold">5 Units</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Key Takeaway Box -->
                        <div class="alert alert-info border-0 border-start border-4 border-info shadow-sm p-4 mb-5">
                            <h4 class="fw-bold text-dark"><i class="ph ph-math-operations  fs-3 me-2" style="color: #b45309;"></i> How to Calculate It</h4>
                            <p class="mb-0 text-dark">
                                Submitting excessive volumes of units requires adding together the minutes of ALL timed services. <br><br>
                                <strong>Scenario:</strong> 25 mins of TherEx (97110) + 10 mins of Manual Therapy (97140) = 35 Total Timed Minutes. <br>
                                According to the chart, 35 minutes equals <strong>2 Total Units</strong>. Because TherEx took the majority of time, you bill: 1 unit 97110, 1 unit 97140. 
                            </p>
                        </div>


                        <!-- AI Citable Block 2 -->
                        <div class="p-4 bg-light border-start border-4 border-success shadow-sm mb-5 rounded-end">
                            <p class="mb-0 fw-medium">
                                <strong>What is the GP Modifier in Medical Billing?</strong> The GP modifier is a strict healthcare billing indicator appended to CPT codes to signify that the service was provided under a physical therapy Plan of Care (POC). It is mandatory for Medicare and most commercial claims. Failure to append the GP modifier to a physical therapy claim results in immediate automated denial.
                            </p>
                        </div>


                        <h2 id="modifiers" class="fw-bold mb-3 mt-5">5. Mandatory Modifiers (GP, 59, KX)</h2>
                        <p class="lh-lg mb-4">
                            Without precise modifiers, clean PT claims get shredded by clearinghouse scrubbing engines.
                        </p>
                        <ul class="lh-lg mb-4">
                            <li><strong>GP Modifier:</strong> Indicates the service was performed personally by a Physical Therapist. (Note: OTs use GO, and STs use GN. See our <a href="occupational-therapy-billing/" class="text-primary fw-bold text-decoration-none">Occupational Therapy Billing Guide</a> for OT specifics).</li>
                            <li><strong>Modifier 59 (or XE/XS/XP/XU):</strong> Used as an "unbundling" tool. If you perform Manual Therapy (97140) and a PT Evaluation (97161) on the exact same day, National Correct Coding Initiative (NCCI) edits immediately bundle them, assuming they were overlapping. You must append Modifier 59 to the 97140 to declare it was a completely distinct, separate block of time.</li>
                            <li><strong>CQ / CO Modifiers:</strong> Appended to signify services provided by a Physical Therapist Assistant (PTA) or Occupational Therapy Assistant (OTA). Since 2022, Medicare radically reduces reimbursement by 15% for services provided by assistants instead of primary therapists.</li>
                        </ul>

                        <h2 id="therapy-cap" class="fw-bold mb-3 mt-5">6. The Medicare Therapy Thresholds (Caps)</h2>
                        <p class="lh-lg mb-4">
                            Historically, Medicare imposed a hard financial cap on how much therapy a beneficiary could receive annually. The Bipartisan Budget Act of 2018 technically repealed the "hard cap," but replaced it with a complex "Thresholds" system.
                        </p>
                        <p class="lh-lg mb-4">
                            For 2024/2025, there is a combined financial threshold (approx $2,330) for Physical Therapy and Speech-Language Pathology combined. Once a patient's claims cross this dollar threshold, the provider must append the <strong>KX Modifier</strong> to every single subsequent claim to mathematically attest that the continued therapy is medically necessary. If claims cross a secondary threshold ($3,000), they are immediately subject to targeted medical review and severe RAC audits.
                        </p>

                        <h2 id="documentation" class="fw-bold mb-3 mt-5">7. Defensible Clinical Documentation</h2>
                        <p class="lh-lg mb-4">
                            Functional Limitation Reporting (G-Codes) was permanently retired by CMS over five years ago. However, commercial payers still demand aggressive functional outcome data. A clinically defensible PT note must include:
                        </p>
                        <ul class="lh-lg mb-4">
                            <li>Exact total timed treatment minutes vs. total Session Minutes (in time in, out time out).</li>
                            <li>The explicit rationale for any Modifier 59 utilization.</li>
                            <li>Objective, measurable functional improvements tied to a validated tool (e.g., FOTO, Oswestry Disability Index) demonstrating that the patient is actively progressing toward independence, rather than plateauing.</li>
                        </ul>

                        <h2 id="outsourcing" class="fw-bold mb-3 mt-5">8. Optimizing PT Revenue Cycles</h2>
                        <p class="lh-lg mb-4">
                            Physical therapy margins are under unprecedented attack from declining CMS reimbursements and PTA pay cuts. Relying on an inexperienced front desk clerk to calculate complex 8-minute rule mathematics is a recipe for fiscal disaster.
                        </p>
                        <p class="lh-lg mb-5">
                            By leveraging an <a href="medical-billing-services/" class="text-primary fw-bold text-decoration-none">expert medical billing company</a> like MEDINEXT SOLUTIONS, PT clinic owners can completely outsource the friction of denial management, modifier configurations, and complex authorization tracking. Our specialized teams manage a 98% clean claim rate specifically for rehab facilities, allowing you to maximize unit throughput and focus purely on clinical outcomes.
                        </p>

                    </article>

                    <!-- Article Footer / CTA -->
                    <div class="card bg-dark text-white text-center p-5 mt-5 rounded-4 shadow">
                        <h3 class="h2 fw-bold mb-3">Eliminate PT Denials Today.</h3>
                        <p class="lead mb-4 mx-auto" style="max-width: 600px;">Stop losing 15% of your revenue to NCCI edit errors and modifier mismatches. Secure your pipeline with MEDINEXT SOLUTIONS.</p>
                        <a href="free-practice-audit/" class="btn btn-primary btn-lg fw-bold px-5">Partner with PT Coding Experts</a>
                    </div>
                    
                    
                    <!-- FAQ SECTION -->
                    <h2 id="faqs" class="fw-bold mb-4 mt-5 pt-4 border-top">Frequently Asked Questions</h2>
                    <div class="accordion mb-5" id="articleFaq">
                        
                        <!-- FAQ 1 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false">
                                    Can I bill an evaluation code (97161) and a treatment code (97110) on the same day?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    Yes. However, under NCCI edits, the treatments are often bundled into the evaluation. To receive reimbursement for both, you must ensure the therapy was a completely separate and distinct block of time from the evaluation itself, and you must append Modifier 59 (or XE/59/XU) to the treatment code.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false">
                                    What happens if I forget to append the KX modifier?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    If a Medicare patient has exceeded the annual financial therapy threshold (approx $2,330) and you fail to append the KX modifier on subsequent claims, Medicare will automatically deny the exact claim in its entirety. You will have to correct the claim and resubmit.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false">
                                    Do commercial insurances use the 8-Minute Rule?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    Not all of them. Medicare uses the strict 8-Minute algorithm where cumulative time determines total units. Many commercial payers, however, use the "AMA Rule" (Substantial Portion Methodology), where you evaluate each CPT code individually and can bill 1 unit if you cross the 8-minute mark for that specific code independently. Knowing which rule applies to which payer is critical.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Sidebar Content -->
                <div class="col-lg-4 mt-5 mt-lg-0">
                    <aside class="sticky-top" style="top: 100px;">
                        
                        <div class="card shadow border-0 rounded-4 mb-4">
                            <div class="card-body p-4 text-center text-white bg-primary rounded-4">
                                <h3 class="h5 fw-bold mb-3">Rehab Billing Audits</h3>
                                <p class="small mb-4">Losing cash flow to NCCI bundled denials? Request a free practice audit and we will fix your modifier matrices.</p>
                                <a href="free-practice-audit/" class="btn btn-light text-dark fw-bold w-100 mb-2">Request Assistance</a>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-4 bg-light mb-4">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-4">Related RCM Services</h3>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="therapy-billing-services/" class="text-decoration-none text-dark hover-primary">Multidisciplinary Therapy RCM</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="occupational-therapy-billing/" class="text-decoration-none text-dark hover-primary">Occupational Therapy (OT)</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="speech-therapy-billing/" class="text-decoration-none text-dark hover-primary">Speech Therapy (ST)</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="denial-management-services/" class="text-decoration-none text-dark hover-primary">Denial Management</a></li>
                                    <li class="mb-0"><i class="ph ph-caret-right text-primary me-2"></i><a href="prior-authorization-services/" class="text-decoration-none text-dark hover-primary">Prior Authorizations</a></li>
                                </ul>
                            </div>
                        </div>
                        
                    </aside>
                </div>

            </div>
        </div>
    </section>

</main>

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "@id": "https://medinextsolutions.com/blog/physical-therapy-billing-guide/#article",
      "headline": "Physical Therapy Billing: CPT Codes, Rules & Compliance Guide",
      "description": "Complete guide to physical therapy billing. Master the 8-minute rule, exact PT CPT codes, modifier GP, and strict Medicare therapy caps.",
      "datePublished": "2025-01-25T08:00:00+00:00",
      "dateModified": "2025-01-25T08:00:00+00:00",
      "author": {
        "@type": "Organization",
        "name": "MEDINEXT SOLUTIONS Editorial Team",
        "url": "https://medinextsolutions.com/"
      },
      "publisher": {
        "@type": "MedicalBusiness",
        "name": "MEDINEXT SOLUTIONS",
        "logo": {
          "@type": "ImageObject",
          "url": "https://medinextsolutions.com/assets/images/logo.png"
        }
      },
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://medinextsolutions.com/blog/physical-therapy-billing-guide/"
      }
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/blog/physical-therapy-billing-guide/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Can I bill an evaluation code (97161) and a treatment code (97110) on the same day?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. However, under NCCI edits, treatments are typically bundled. You must ensure the therapy was a completely separate block of time and append Modifier 59 to the treatment code to receive reimbursement."
          }
        },
        {
          "@type": "Question",
          "name": "What happens if I forget to append the KX modifier?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "If a Medicare patient has exceeded the annual financial therapy threshold and you fail to append the KX modifier on subsequent claims, Medicare will automatically deny the claim. You must correct and resubmit."
          }
        },
        {
          "@type": "Question",
          "name": "Do commercial insurances use the 8-Minute Rule?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Not all of them. Medicare uses the strict 8-Minute algorithm. Many commercial payers use the Substantial Portion Methodology, where you evaluate each CPT code individually."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once '../../includes/footer.php'; ?>
