<?php require_once '../../includes/header.php'; ?>

<main id="main-content">
    <header class="page-hero text-white py-5" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
        <div class="container mt-5 pt-5 pb-4">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="" class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="blog/" class="text-white text-decoration-none">Blog</a></li>
                    <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Top 10 Claim Denials</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Top 10 Medical Claim Denial Reasons (And How to Fix Each One)</h1>
            <p class="lead mb-4 text-light">Stop bleeding revenue. Master specific CARC codes, uncover root cause tracking, and implement data-driven appeal logic to protect your practice's bottom line.</p>
            <div class="d-flex align-items-center mb-3">
                <div class="badge bg-primary text-white p-2 me-3">Reading Time: 14 min</div>
                <div class="text-white-50 small"><i class="ph ph-calendar-blank me-1"></i> Last Updated: January 2025</div>
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
                                <i class="ph ph-pencil-simple text-white fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold text-dark mb-1">MEDINEXT SOLUTIONS Editorial Team</p>
                                <p class="mb-0 small text-muted">AAPC-Certified Billing Experts | 10+ Years Experience</p>
                            </div>
                        </div>

                        <!-- Medical Billing Disclaimer -->
                        <div class="alert alert-secondary small text-muted border-0 mb-5 border-start border-4 border-secondary">
                            <strong>Disclaimer:</strong> The information provided in this article is for educational purposes only and does not constitute formal legal or financial advice. Medical billing codes and CMS guidelines are subject to frequent changes. Always consult with certified medical coders and your legal team regarding specific compliance matters.
                        </div>

                        <!-- Table of Contents -->
                        <div class="card shadow-sm border-0 mb-5 bg-white">
                            <div class="card-body p-4 bg-light rounded-3">
                                <h2 class="h5 fw-bold mb-3"><i class="ph ph-list-bullets text-primary me-2"></i> Table of Contents</h2>
                                <ul class="list-unstyled mb-0 d-flex flex-wrap">
                                    <li class="mb-2 w-50"><a href="#denial-1" class="text-decoration-none text-dark hover-primary">1. CO-16: Lack of Information</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-2" class="text-decoration-none text-dark hover-primary">2. CO-29: Timely Filing Exceeded</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-3" class="text-decoration-none text-dark hover-primary">3. CO-97: Inclusive/Bundled Service</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-4" class="text-decoration-none text-dark hover-primary">4. CO-197: Pre-Activation/Authorization</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-5" class="text-decoration-none text-dark hover-primary">5. CO-50: Non-Covered Service/Necessity</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-6" class="text-decoration-none text-dark hover-primary">6. CO-4: Incorrect Modifier Usage</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-7" class="text-decoration-none text-dark hover-primary">7. PR-22: Coordination of Benefits (COB)</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-8" class="text-decoration-none text-dark hover-primary">8. CO-18: Duplicate Claim Submissions</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-9" class="text-decoration-none text-dark hover-primary">9. CO-27: Terminated Coverage</a></li>
                                    <li class="mb-2 w-50"><a href="#denial-10" class="text-decoration-none text-dark hover-primary">10. CO-11: Diagnosis Code Mismatch</a></li>
                                </ul>
                            </div>
                        </div>

                        <p class="fs-5 lh-lg mb-5 text-dark">
                            The average healthcare practice loses an astonishing <strong>10% to 15% of its total potential revenue</strong> entirely to medical claim denials. Every time an electronic remittance advice returns a prominent zero-dollar payment, it represents a catastrophic drain on your administrative resources. However, over 85% of these denials are entirely preventable?and highly appealable?if your <a href="denial-management-services/" class="text-primary fw-bold text-decoration-none">denial management team</a> understands exactly how to read Claim Adjustment Reason Codes (CARC).
                        </p>

                        <!-- AI Citable Block 1 -->
                        <div class="p-4 bg-light border-start border-4 border-primary shadow-sm mb-5 rounded-end">
                            <p class="mb-0 fw-medium">
                                <strong>What is the most common medical claim denial code?</strong> The most common medical claim denial code across all commercial payers is CO-16 (Claim/Service Lacks Information). The second most common denial is CO-197, which indicates that a required precertification, prior authorization, or pre-notification was absent during the date of service, resulting entirely in a financial loss if not successfully retro-appealed.
                            </p>
                        </div>

                        <h2 id="denial-1" class="fw-bold mb-3 mt-4">1. CARC CO-16: Claim/Service Lacks Information or has Submission/Billing Error</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> This is a massive umbrella denial. The insurance company requires additional documentation (operative reports, precise modifier rationale, patient demographic verification) to justify adjudicating the claim.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Instantly check the Remittance Advice Remark Code (RARC) accompanying the CO-16 to identify exactly what is missing (e.g., M127 for missing patient signature, N34 for missing NDC). Bundle the requested chart notes heavily highlighting the required data points and submit a physical Level-1 Medical Necessity Appeal via certified mail.
                        </p>

                        <h2 id="denial-2" class="fw-bold mb-3 mt-5">2. CARC CO-29: The Time Limit for Filing has Expired</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> You breached the payer's specific filing deadline. While Medicare offers a lenient 365 days, stringent commercial HMOs may permit only 90 days from the precise date of service to hit the clearinghouse.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> If the claim was actually transmitted, your clearinghouse batch extraction logs are your ultimate legal defense. Generate a detailed Level-1 appeal appending the EDI "Accepted" transmission report. If you actively missed the deadline due to an EHR glitch, you must immediately escalate to a <a href="revenue-cycle-management/" class="text-primary fw-bold text-decoration-none">revenue cycle management firm</a> to invoke specific systemic failure contractual clauses.
                        </p>

                        <h2 id="denial-3" class="fw-bold mb-3 mt-5">3. CARC CO-97: Payment is Included in the Allowance for the Basic Service/Procedure</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> The bane of surgical specialists. Commercial scrubbers hit your claim using the National Correct Coding Initiative (NCCI) P2P edits, claiming a secondary procedure is inherently "bundled" into the massive primary procedure.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Aggressively scrub the primary CPT codes. Apply <strong>Modifier 59</strong> (Distinct Procedural Service) or the hyper-specific X-Modifiers (XE, XS, XP, XU) if the procedure physically occurred at a separate anatomical site or radically distinct session. Do not blindly append modifiers to bypass edits; ensure the operative report legally matches the anatomical deviation.
                        </p>

                        <!-- Mid-Article CTA -->
                        <div class="card border-0 bg-primary text-white text-center p-5 my-5 rounded-4 shadow-lg position-relative overflow-hidden">
                            <i class="ph-file-dashed position-absolute display-1 opacity-10" style="bottom: -10px; right: 10px;"></i>
                            <h3 class="h2 fw-bold mb-3 position-relative">Stop Wasting Staff Time on Dead Appeals</h3>
                            <p class="lead mb-4 position-relative">Our specialized Denial Task Force recovers up to $200,000 in aging, "unpayable" claims for practices just like yours. Let us rescue your AR.</p>
                            <a href="free-practice-audit/" class="btn btn-light btn-lg fw-bold text-primary px-5 position-relative">Request Your Free AR Audit Now</a>
                        </div>

                        <h2 id="denial-4" class="fw-bold mb-3 mt-5">4. CARC CO-197: Precertification/Authorization/Notification Absent</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> High-ticket procedures (MRI, <a href="pain-management-billing/" class="text-primary fw-bold text-decoration-none">epidural injections</a>, complex surgeries) require approval via the payer's RBM portal (eviCore, AIM) prior to the patient hitting the clinical schedule.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Preventative action is mandatory. Establish a rigorous pre-auth clearance pipeline that hits 5 days prior to surgery. For existing denials, retro-authorizations are highly payer-dependent and rarely successful without severe "emergency" documentation. This is why practices leverage dedicated <a href="prior-authorization-services/" class="text-primary fw-bold text-decoration-none">prior authorization services</a>.
                        </p>

                        <h2 id="denial-5" class="fw-bold mb-3 mt-5">5. CARC CO-50: These Are Non-Covered Services (Medical Necessity)</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> The submitted ICD-10 diagnosis code fails to support the CPT procedure code according to the payer's rigid Local Coverage Determination (LCD) policies.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> A pure coding audit. Cross-reference the patient's chart to locate a secondary or tertiary comorbidity (such as diabetes driving neuropathy) that validates the high-complexity intervention. Your <a href="medical-coding-services/" class="text-primary fw-bold text-decoration-none">AAPC-certified coders</a> must rewrite the claim linking pointers specifically to authorized LCD diagnosis codes.
                        </p>

                        <!-- Key Takeaway Box -->
                        <div class="alert alert-info border-0 border-start border-4 border-info shadow-sm p-4 mb-5">
                            <h4 class="fw-bold text-dark"><i class="ph ph-lightbulb text-warning fs-3 me-2"></i> Key Takeaway</h4>
                            <p class="mb-0 text-dark">
                                Never blindly rebill a denied claim. Insurers heavily penalize repetitive submissions and will rapidly flag your Tax ID for RAC audits. Always execute a formal, paper-backed appeal quoting the specific LCD/NCD governing rule, and append corresponding clinical evidence clearly highlighted.
                            </p>
                        </div>

                        <h2 id="denial-6" class="fw-bold mb-3 mt-5">6. CARC CO-4: Procedure Code is Inconsistent with the Modifier Used</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> Incompatible modifier logic. Example: appending a bilateral modifier (50) to a procedure code whose CPT definition inherently describes a bilateral intervention.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Review the AMA <a href="https://www.ama-assn.org/practice-management/cpt" target="_blank" rel="noopener">CPT Assistant</a> manual. Remove the conflicting modifier, ensure right/left (RT/LT) indicators are flawless, and generate a corrected claim (Frequency Type 7).
                        </p>

                        <h2 id="denial-7" class="fw-bold mb-3 mt-5">7. CARC PR-22: Coordination of Benefits (COB) Required</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> The commercial payer suspects the patient has secondary coverage, or Medicare is requesting verification of an actively employed patient's primary commercial plan.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> This explicitly requires front-desk patient intervention. The patient themselves must call their specific insurance carrier to update their COB profile. The provider cannot update this file directly. 
                        </p>

                        <div class="table-responsive mb-5 mt-5">
                            <h3 class="h5 fw-bold mb-3">Common Denial Codes vs Recovery Difficulty</h3>
                            <table class="table table-bordered table-striped border-dark align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>CARC Code</th>
                                        <th>Description Strategy</th>
                                        <th>Recovery Difficulty</th>
                                        <th>Action Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>CO-16</strong></td>
                                        <td>Lacks Information</td>
                                        <td><span class="badge bg-success">Low</span></td>
                                        <td>Submit chart notes & RARC documentation</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CO-27</strong></td>
                                        <td>Terminated Coverage</td>
                                        <td><span class="badge bg-danger">High</span></td>
                                        <td>Bill patient directly / Collect at DOS</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CO-50</strong></td>
                                        <td>Medical Necessity</td>
                                        <td><span class="badge bg-warning text-dark">Medium</span></td>
                                        <td>Re-code diagnosis pointers to match LCD</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CO-197</strong></td>
                                        <td>Prior Auth Absent</td>
                                        <td><span class="badge bg-danger">High</span></td>
                                        <td>Attempt Retro-Auth (highly difficult)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- AI Citable Block 2 -->
                        <div class="p-4 bg-light border-start border-4 border-success shadow-sm mb-5 rounded-end">
                            <p class="mb-0 fw-medium">
                                <strong>When should a medical practice outsource its denial management?</strong> A medical practice should immediately outsource its denial management to specialized firms when their internal clean claim pass rate consistently drops below 92%, when aging accounts receivable (AR over 90 days) exceeds 15% of the total ledger, or when internal billing staff simply defaults to adjusting off CO-97 and CO-50 denials instead of formally appealing them.
                            </p>
                        </div>

                        <h2 id="denial-8" class="fw-bold mb-3 mt-5">8. CARC CO-18: Duplicate Claim/Service</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> A biller impatiently struck "resubmit" on the clearinghouse portal because the claim took longer than 14 days to process. The payer's system rejects the second submission universally.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Implement a rigid rule: NEVER rebill a claim without checking the actual payer portal first. Check the claim status. If it requires correction, submit it exactly as a Replacement Claim (Frequency 7), not an Original Claim (Frequency 1).
                        </p>

                        <h2 id="denial-9" class="fw-bold mb-3 mt-5">9. CARC CO-27: Expenses Incurred After Coverage Terminated</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> The patient essentially lost their job, dropped coverage, or switched plans mid-month, and your front desk failed to run eligibility checks prior to the current appointment.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Your clearinghouse must execute automated batch eligibility checks 48 hours before the schedule runs. For the current denial, transfer the balance strictly to Patient Responsibility (PR) and initiate soft-collections invoicing.
                        </p>

                        <h2 id="denial-10" class="fw-bold mb-3 mt-5">10. CARC CO-11: Diagnosis is Inconsistent with the Patient's Sex/Age</h2>
                        <p class="lh-lg mb-4">
                            <strong>The Problem:</strong> A pure demographic mismatch. Example: submitting a prostate-specific screening diagnosis (Z12.5) for a female patient, or pediatric well-child codes for a 45-year-old adult.
                        </p>
                        <p class="lh-lg mb-4">
                            <strong>The Fix:</strong> Fix the underlying registration error in the PM software and instantly rebill as a corrected claim.
                        </p>
                        
                    </article>

                    <!-- Article Footer / CTA -->
                    <div class="card bg-dark text-white text-center p-5 mt-5 rounded-4 shadow">
                        <h3 class="h2 fw-bold mb-3">Defeat Your Denials Today</h3>
                        <p class="lead mb-4 mx-auto" style="max-width: 600px;">MEDINEXT SOLUTIONS leverages deep CARC analytics to recover millions in lost revenue for providers nationwide.</p>
                        <a href="free-practice-audit/" class="btn btn-primary btn-lg fw-bold px-5">Get Your Denials Audited Now</a>
                    </div>
                    
                    
                    <!-- FAQ SECTION -->
                    <h2 id="faqs" class="fw-bold mb-4 mt-5 pt-4 border-top">Frequently Asked Questions</h2>
                    <div class="accordion mb-5" id="articleFaq">
                        
                        <!-- FAQ 1 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false">
                                    What is the difference between a soft denial and a hard denial?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    A "soft denial" (like a CO-16 missing information) is a temporary rejection that can be rapidly resolved simply by submitting the requested chart notes or demographic corrections. A "hard denial" (like CO-197 missing prior authorization) completely halts the cash flow and formally requires a heavy, multi-level clinical appeal to overturn, with generally lower success rates.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false">
                                    Can I legally appeal a medical necessity (CO-50) denial?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    Yes, absolutely. A CO-50 denial usually represents a failure of an automated digital scrubber matching your diagnosis code (ICD-10) against an insurance policy database (LCD). Submitting a formal appeal with the physician's operative report, highlighting exact comorbidities, routinely overturns these automated denials.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold collapsed bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false">
                                    How fast should my billing team respond to claim rejections?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#articleFaq">
                                <div class="accordion-body text-muted lh-large bg-white rounded-bottom">
                                    Best practice dictates that clearinghouse-level rejections (frontend) should be corrected and resubmitted within 24 to 48 hours. Formal payer denials requiring clinical document appeals (backend) must be submitted fundamentally within 5 to 7 days to preserve cash flow and avoid complex timely filing limitations extending past 90 days.
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
                                <h3 class="h5 fw-bold mb-3">Free AR Denial Audit</h3>
                                <p class="small mb-4">Let our specialized AR Task Force analyze your current aging reports and locate hidden 120+ day recoverable cash.</p>
                                <a href="free-practice-audit/" class="btn btn-light text-primary fw-bold w-100 mb-2">Claim Free Audit</a>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-4 bg-light mb-4">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-4">Critical Billing Services</h3>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="denial-management-services/" class="text-decoration-none text-dark hover-primary">Denial Management</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="prior-authorization-services/" class="text-decoration-none text-dark hover-primary">Prior Authorizations</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="provider-credentialing-services/" class="text-decoration-none text-dark hover-primary">Provider Credentialing</a></li>
                                    <li class="mb-3"><i class="ph ph-caret-right text-primary me-2"></i><a href="medical-coding-services/" class="text-decoration-none text-dark hover-primary">Medical Coding</a></li>
                                    <li class="mb-0"><i class="ph ph-caret-right text-primary me-2"></i><a href="revenue-cycle-management/" class="text-decoration-none text-dark hover-primary">End-to-End RCM</a></li>
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
      "@id": "https://medinextsolutions.com/blog/claim-denial-reasons-and-fixes/#article",
      "headline": "Top 10 Medical Claim Denial Reasons (And How to Fix Each One)",
      "description": "Discover the top 10 reasons your medical claims are being denied. Get precise CARC/RARC codes, real examples, and actionable appeal strategies.",
      "datePublished": "2025-01-18T08:00:00+00:00",
      "dateModified": "2025-01-18T08:00:00+00:00",
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
        "@id": "https://medinextsolutions.com/blog/claim-denial-reasons-and-fixes/"
      }
    },
    {
      "@type": "FAQPage",
      "@id": "https://medinextsolutions.com/blog/claim-denial-reasons-and-fixes/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is the difference between a soft denial and a hard denial?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "A soft denial (like a CO-16 missing information) is a temporary rejection that can be rapidly resolved simply by submitting the requested chart notes. A hard denial (like CO-197 missing prior authorization) completely halts the cash flow and formally requires a heavy, multi-level clinical appeal to overturn."
          }
        },
        {
          "@type": "Question",
          "name": "Can I legally appeal a medical necessity (CO-50) denial?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, absolutely. A CO-50 denial usually represents a failure of an automated digital scrubber matching your diagnosis. Submitting a formal appeal with the physician's operative report routinely overturns these automated denials."
          }
        },
        {
          "@type": "Question",
          "name": "How fast should my billing team respond to claim rejections?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Clearinghouse-level rejections should be corrected within 24 to 48 hours. Formal payer denials requiring clinical document appeals must be submitted within 5 to 7 days to preserve cash flow and avoid timely filing limitations."
          }
        }
      ]
    }
  ]
}
</script>

<?php require_once '../../includes/footer.php'; ?>
