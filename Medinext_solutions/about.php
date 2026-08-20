<?php
/**
 * MEDINEXT SOLUTIONS - About Page
 */

$pageTitle = 'About Us | MEDINEXT SOLUTIONS - RCM & Medical Billing Experts';
$pageDescription = 'Discover MEDINEXT SOLUTIONS, your dedicated partner in Revenue Cycle Management. Learn how our expert team helps healthcare providers streamline billing and maximize revenue.';
$pageKeywords = 'about MEDINEXT SOLUTIONS, revenue cycle management company, RCM experts, healthcare billing team, medical billing professionals, outsourced medical billing';

require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- PAGE HERO -->
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
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi-house-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active">About Us</li>
                </ol>
            </nav>
            <h1 class="page-hero-title" data-aos="fade-up">
                About <span class="gradient-text">MEDINEXT SOLUTIONS</span>
            </h1>
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                Dedicated to empowering healthcare providers with expert billing solutions that maximize revenue and ensure compliance.
            </p>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- COMPANY STORY -->
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
                    <i class="bi-book"></i>
                    Our Story
                </span>
                <h2 class="section-title" style="text-align:left;">
                    A Decade of <span class="gradient-text">Excellence in Medical Billing</span>
                </h2>
                <p>
                    Founded over a decade ago, MEDINEXT SOLUTIONS was born from a simple yet powerful vision: to provide healthcare providers with billing services that are as specialized as the care they deliver.
                </p>
                <p>
                    We recognized that generic billing solutions often fail to capture the nuances of specialized medical practices, leading to lost revenue and compliance risks. That's why we built a team of experts who understand the unique coding requirements, payer policies, and regulatory frameworks for each specialty we serve.
                </p>
                <p class="text-muted">
                    Today, we proudly serve over 500 healthcare providers across all 50 states, maintaining a 98% claim accuracy rate and recovering millions of dollars in revenue for our clients annually.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- MISSION & VISION -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">
                <i class="bi-compass"></i>
                Our Purpose
            </span>
            <h2 class="section-title">
                Mission & <span class="gradient-text">Vision</span>
            </h2>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="mission-card">
                    <div class="mission-card-icon primary">
                        <i class="bi-bullseye"></i>
                    </div>
                    <h4>Our Mission</h4>
                    <p class="mt-3" style="color: var(--gray-500);">
                        To empower healthcare providers with accurate, compliant, and efficient billing services that maximize revenue, reduce administrative burden, and allow practitioners to focus entirely on delivering exceptional patient care.
                    </p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="mission-card">
                    <div class="mission-card-icon secondary">
                        <i class="bi-eye"></i>
                    </div>
                    <h4>Our Vision</h4>
                    <p class="mt-3" style="color: var(--gray-500);">
                        To be the most trusted and innovative medical billing partner in the nation, setting the industry standard for accuracy, technology integration, and client satisfaction across every healthcare specialty.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- CORE VALUES -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">
                <i class="bi-heart-fill"></i>
                What Drives Us
            </span>
            <h2 class="section-title">
                Our Core <span class="gradient-text">Values</span>
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi-bullseye"></i>
                    </div>
                    <h5>Accuracy</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        Every claim is meticulously reviewed and coded to ensure maximum accuracy and minimize denials.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi-shield-check"></i>
                    </div>
                    <h5>Integrity</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        We operate with complete transparency and honesty, building trust with every interaction.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi-lightning-fill"></i>
                    </div>
                    <h5>Innovation</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        We continuously adopt the latest technologies and coding practices to stay ahead of the curve.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5>Support</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        Our 24/7 dedicated team ensures you always have the guidance and assistance you need.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- TEAM SECTION -->
<!-- ============================================ -->
<section class="section section-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">
                <i class="bi-people-fill"></i>
                Our Team
            </span>
            <h2 class="section-title">
                Meet Our <span class="gradient-text">Leadership</span>
            </h2>
            <p class="section-subtitle">
                Experienced professionals dedicated to your practice's financial success.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="team-card p-4 d-flex flex-column flex-md-row align-items-center gap-4 text-start">
                    <div class="team-card-image" style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; flex-shrink: 0; box-shadow: var(--shadow-md);">
                        <img src="<?php echo $baseUrl; ?>/assets/images/hardik-siddhpura.webp" alt="Hardik Siddhpura" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="team-card-body p-0">
                        <h4 class="team-card-name text-light mb-1">Hardik Siddhpura</h4>
                        <p class="team-card-role text-accent mb-3">Founder</p>
                        <p class="text-muted" style="line-height:1.7;">
                            Hardik Siddhpura is the Founder of MEDINEXT SOLUTIONS, a medical billing and revenue cycle management company focused on supporting healthcare providers across the United States. With hands-on experience in U.S. medical billing, he specializes in helping practices improve claim accuracy, reduce denials, and optimize revenue cycle performance. Hardik founded MEDINEXT SOLUTIONS to provide reliable, transparent, and personalized billing support so providers can focus on delivering quality patient care.
                        </p>
                        <div class="team-card-socials mt-3 justify-content-start">
                            <div class="footer-socials new-socials">
                                <a href="https://www.linkedin.com/in/hardik-siddhpura-138518a9/" target="_blank" class="social-icon linkedin" aria-label="LinkedIn">
                                    <div class="icon-container">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="social-svg linkedin-svg" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                        </svg>
                                    </div>
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
<!-- COMPLIANCE BADGES -->
<!-- ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">
                <i class="bi-award"></i>
                Compliance
            </span>
            <h2 class="section-title">
                Certified & <span class="gradient-text">Compliant</span>
            </h2>
        </div>

        <div class="row g-4 justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-4">
                <div class="value-card text-center">
                    <div class="value-icon" style="background: rgba(var(--secondary-rgb), 0.1); color: var(--secondary);">
                        <i class="bi-shield-check"></i>
                    </div>
                    <h5>HIPAA Compliant</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        All operations fully comply with HIPAA regulations to ensure the security and privacy of patient health information.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card text-center">
                    <div class="value-icon" style="background: rgba(var(--primary-rgb), 0.1); color: var(--primary-light);">
                        <i class="bi-file-text"></i>
                    </div>
                    <h5>ICD-10 Certified</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        Our coders are certified in ICD-10-CM/PCS coding systems, ensuring accurate diagnosis and procedure coding.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card text-center">
                    <div class="value-icon" style="background: rgba(var(--accent-rgb), 0.1); color: var(--accent);">
                        <i class="bi-patch-check-fill"></i>
                    </div>
                    <h5>AAPC Certified Coders</h5>
                    <p class="text-muted mt-3" style="font-size: var(--fs-sm);">
                        Our team includes AAPC-certified professionals ensuring the highest level of coding accuracy and compliance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- CTA -->
<section class="section cta-section">
    <canvas class="shader-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none; opacity: 1;"></canvas>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="cta-wrapper" data-aos="fade-up">
            <div class="glass-bend-layer"></div>
            <div class="glass-face-layer"></div>
            <div class="glass-edge-layer"></div>
            <div class="glass-content-layer cta-content">
                <h2 class="cta-title">
                    Ready to <span class="gradient-text">Partner With Us?</span>
                </h2>
                <p class="cta-text">
                    Let our expert billing team handle the paperwork while you focus on patient care.
                </p>
                <a href="contact.php" class="btn btn-accent btn-lg">
                    <i class="bi-calendar-check"></i>
                    Schedule a Free Consultation
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- STRUCTURED DATA (JSON-LD) -->
<!-- ============================================ -->

<!-- SCHEMA 5b ï¿½?? BreadcrumbList (About Us) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "@id": "https://medinextsolutions.com/about-us/#breadcrumb",
  "name": "About Us Breadcrumbs",
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
      "item": "https://medinextsolutions.com/about-us/"
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>

