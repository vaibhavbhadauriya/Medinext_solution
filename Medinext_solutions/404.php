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
<section class="section" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 4rem 1rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Error Code -->
                <h1 style="font-size: clamp(5rem, 15vw, 10rem); font-weight: 900; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0; line-height: 1;">
                    404
                </h1>

                <!-- Error Message -->
                <h2 style="font-size: var(--fs-2xl); color: var(--light); margin-bottom: 1rem;">
                    Page Not Found
                </h2>

                <p style="font-size: var(--fs-md); color: var(--text-muted); max-width: 600px; margin: 0 auto 2.5rem;">
                    The page you're looking for doesn't exist or has been moved. Don't worry ? you can find what you need from the links below, or contact our team directly.
                </p>

                <!-- Search Suggestion -->
                <div style="margin-bottom: 2.5rem;">
                    <p style="color: var(--gray-400); margin-bottom: 1.5rem;">Try one of these popular pages:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center;">
                        <a href="" class="btn btn-primary" style="min-width: 160px;">
                            <i class="ph ph-house"></i> Homepage
                        </a>
                        <a href="/services.php" class="btn btn-primary" style="min-width: 160px;">
                            <i class="ph ph-stethoscope"></i> Our Services
                        </a>
                        <a href="/about.php" class="btn btn-primary" style="min-width: 160px;">
                            <i class="ph ph-users-three"></i> About Us
                        </a>
                        <a href="/contact.php" class="btn btn-accent" style="min-width: 160px;">
                            <i class="ph ph-phone"></i> Contact Us
                        </a>
                    </div>
                </div>

                <!-- Additional Help -->
                <div style="background: var(--dark-card); border: 1px solid var(--border-color); border-radius: 1rem; padding: 2rem; max-width: 500px; margin: 0 auto;">
                    <h3 style="font-size: var(--fs-lg); color: var(--light); margin-bottom: 0.75rem;">
                        Need Help Right Now?
                    </h3>
                    <p style="color: var(--text-muted); margin-bottom: 1rem; font-size: var(--fs-sm);">
                        Our expert billing team is available 24/7 to assist you.
                    </p>
                    <a href="tel:+18627992199" style="color: var(--secondary); font-weight: 600; font-size: var(--fs-md); text-decoration: none;">
                        <i class="ph ph-phone-call"></i> (862) 799-2199
                    </a>
                    <br>
                    <a href="mailto:info@medinextsolutions.com" style="color: var(--secondary-light); font-size: var(--fs-sm); text-decoration: none; margin-top: 0.5rem; display: inline-block;">
                        info@medinextsolutions.com
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
