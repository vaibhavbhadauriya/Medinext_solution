<?php
/**
 * MEDINEXT SOLUTIONS - 404 Error Page
 * SEO-friendly custom "Page Not Found" page
 */

$pageTitle = 'Page Not Found | MEDINEXT SOLUTIONS';
$pageDescription = 'The page you are looking for could not be found. Browse our medical billing services or contact MEDINEXT SOLUTIONS for assistance.';
$pageKeywords = 'page not found, 404, MEDINEXT SOLUTIONS';

require_once 'includes/header.php';
?>

<!-- Meta: noindex this page so search engines don't index it -->
<script>
    // Dynamically add noindex for 404 pages (belt and suspenders with header-level too)
    var meta = document.createElement('meta');
    meta.name = 'robots';
    meta.content = 'noindex, nofollow';
    document.head.appendChild(meta);
</script>

<!-- ============================================ -->
<!-- 404 ERROR SECTION -->
<!-- ============================================ -->
<section class="section py-5" style="min-height: 75vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <!-- Left Column: Helpful Healthcare Support Specialist Image -->
            <div class="col-lg-5 text-center text-lg-start">
                <div class="position-relative d-inline-block w-100" style="max-width: 480px;">
                    <img src="<?php echo $baseUrl ?? ''; ?>/assets/images/decorative%20images/pexels-oluwakoreimage-20020595.jpg"
                         alt="MEDINEXT SOLUTIONS healthcare support specialist ready to assist you"
                         loading="lazy"
                         class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover border"
                         style="max-height: 480px; object-position: center top;" />
                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 px-3 py-2 rounded-3 text-white shadow" style="background: rgba(8, 47, 73, 0.92); width: 88%;">
                        <div class="d-flex align-items-center justify-content-center gap-2 small fw-semibold">
                            <i class="ph ph-headset fs-5 text-info"></i>
                            <span>24/7 Clinical &amp; RCM Client Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Error Code, Recovery Links & Direct Support -->
            <div class="col-lg-7 text-center text-lg-start">
                <!-- Error Code -->
                <h1 style="font-size: clamp(4.5rem, 12vw, 8rem); font-weight: 900; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.25rem; line-height: 1;">
                    404
                </h1>

                <!-- Error Message -->
                <h2 style="font-size: var(--fs-2xl); color: #0f172a; margin-bottom: 1rem; font-weight: 700;">
                    Page Not Found
                </h2>

                <p style="font-size: var(--fs-md); color: #334155; max-width: 600px; margin: 0 0 2rem; line-height: 1.6;">
                    The page or medical billing resource you are looking for does not exist or has been moved. Explore our core services below or connect directly with our support team.
                </p>

                <!-- High-Contrast Navigation Buttons -->
                <div style="margin-bottom: 2.5rem;">
                    <p style="color: #334155; margin-bottom: 1rem; font-weight: 600;">Popular destinations:</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                        <a href="<?php echo $baseUrl ?? ''; ?>/" class="btn btn-primary px-4 py-2" style="min-width: 160px;">
                            <i class="ph ph-house me-1"></i> Homepage
                        </a>
                        <a href="<?php echo $baseUrl ?? ''; ?>/free-practice-audit/" class="btn btn-primary px-4 py-2" style="min-width: 160px;">
                            <i class="ph ph-chart-polar me-1"></i> Free Practice Audit
                        </a>
                        <a href="<?php echo $baseUrl ?? ''; ?>/medical-billing-services/" class="btn btn-primary px-4 py-2" style="min-width: 160px;">
                            <i class="ph ph-stethoscope me-1"></i> Our Services
                        </a>
                        <a href="<?php echo $baseUrl ?? ''; ?>/about/" class="btn btn-primary px-4 py-2" style="min-width: 160px;">
                            <i class="ph ph-users-three me-1"></i> About Us
                        </a>
                        <a href="<?php echo $baseUrl ?? ''; ?>/contact/" class="btn btn-accent px-4 py-2" style="min-width: 160px;">
                            <i class="ph ph-phone me-1"></i> Contact Us
                        </a>
                    </div>
                </div>

                <!-- Additional Direct Support Box -->
                <div style="background: #082f49; border: 1px solid rgba(255,255,255,0.15); border-radius: 1rem; padding: 1.75rem; max-width: 540px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <h3 style="font-size: var(--fs-lg); color: #ffffff; margin-bottom: 0.5rem; font-weight: 700;">
                        Need Help Right Now?
                    </h3>
                    <p style="color: #e2e8f0; margin-bottom: 1rem; font-size: var(--fs-sm);">
                        Our AAPC-certified billing consultants are available to assist your practice.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3 justify-content-center justify-content-lg-start">
                        <a href="tel:+18627992199" style="color: #38bdf8; font-weight: 700; font-size: var(--fs-md); text-decoration: none;">
                            <i class="ph ph-phone-call me-1"></i> (862) 799-2199
                        </a>
                        <span style="color: rgba(255,255,255,0.3);">|</span>
                        <a href="mailto:info@medinextsolutions.com" style="color: #ffffff; font-size: var(--fs-sm); text-decoration: none;">
                            <i class="ph ph-envelope me-1"></i> info@medinextsolutions.com
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
