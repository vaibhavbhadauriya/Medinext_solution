<?php
/**
 * MEDINEXT SOLUTIONS - Footer Include
 * Shared footer for all pages
 */
?>
    </main><!-- End Main Content -->

    <!-- ============================================ -->
    <!-- Footer -->
    <!-- ============================================ -->
    <footer class="prisma-footer" id="footer">

    <!-- Aurora WebGL Background -->
    <canvas id="footer-aurora-canvas" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:0;pointer-events:none;-webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 15%); mask-image: linear-gradient(to bottom, transparent 0%, black 15%);"></canvas>

        <div class="footer-top">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <!-- Column 1: Logo & Tagline -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand" data-aos="fade-up">
                            <a href="<?php echo $baseUrl; ?>/" class="footer-logo">
                                <div class="brand-logo-wrapper">
                                    <img src="<?php echo $baseUrl; ?>/assets/images/logo.png?v=8" alt="MEDINEXT SOLUTIONS Logo">
                                </div>
                            </a>
                            <p class="footer-tagline">
                                Your Trusted Partner in Revenue Cycle Management.
                            </p>
                            <div class="footer-socials new-socials gap-3 mt-4">
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/company/medinextsolutions" class="social-icon linkedin" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                                  <div class="icon-container">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="social-svg linkedin-svg" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                    </svg>
                                  </div>
                                </a>

                                <!-- Twitter / X -->
                                <a href="javascript:void(0)" class="social-icon twitter" aria-label="Twitter" title="Coming Soon">
                                  <div class="icon-container">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="social-svg twitter-svg" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
                                    </svg>
                                  </div>
                                </a>
                                
                                <!-- Facebook -->
                                <a href="javascript:void(0)" class="social-icon facebook" aria-label="Facebook" title="Coming Soon">
                                  <div class="icon-container">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="social-svg facebook-svg" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.325V1.325C24 .597 23.403 0 22.675 0z"></path>
                                    </svg>
                                  </div>
                                </a>
                                
                                <!-- Instagram -->
                                <a href="javascript:void(0)" class="social-icon instagram" aria-label="Instagram" title="Coming Soon">
                                  <div class="icon-container">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="social-svg instagram-svg" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                    </svg>
                                  </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget" data-aos="fade-up" data-aos-delay="100">
                            <h5 class="footer-heading">Quick Links</h5>
                            <ul class="footer-links">
                                <li><a href="<?php echo $baseUrl; ?>/">Home</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/">Services</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/locations/">Locations Directory</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/about/">About Us</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/contact/">Contact</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/#why-us">Why Choose Us</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 3: Services -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget" data-aos="fade-up" data-aos-delay="200">
                            <h5 class="footer-heading">Our Services</h5>
                            <ul class="footer-links">
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#therapy">Therapy Billing</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#pain">Pain Management</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#cardio">Cardiovascular</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#oncology">Oncology &amp; Hematology</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#dental">Dental Billing</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#behavioral">Behavioral Health</a></li>
                                <li><a href="<?php echo $baseUrl; ?>/medical-billing-services/#dme">DME Billing</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 4: Contact & Newsletter -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget" data-aos="fade-up" data-aos-delay="300">
                            <h5 class="footer-heading">Contact Info</h5>
                            <ul class="footer-contact">
                                <li>
                                    <i class="ph ph-map-pin"></i>
                                    <span>1317 Edgewater Dr #3520<br>Orlando, FL 32804 USA</span>
                                </li>
                                <li>
                                    <i class="ph ph-phone"></i>
                                    <a href="tel:+18627992199">862-799-2199</a>
                                </li>
                                <li>
                                    <i class="ph ph-envelope-simple"></i>
                                    <a href="mailto:info@medinextsolutions.com">info@medinextsolutions.com</a>
                                </li>
                                <li>
                                    <i class="ph ph-clock"></i>
                                    <span>24/7 Support Available</span>
                                </li>
                            </ul>

                            <!-- Newsletter -->
                            <div class="footer-newsletter">
                                <h6>Stay Updated</h6>
                                <form id="newsletterForm" class="newsletter-form" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken ?? generateCSRFToken(); ?>">
                                    <div class="input-group">
                                        <input type="email" class="form-control" name="newsletter_email" placeholder="Enter your email" aria-label="Email for newsletter" required>
                                        <button type="submit" class="btn btn-accent" aria-label="Subscribe">
                                            <i class="ph ph-paper-plane-tilt"></i>
                                        </button>
                                    </div>
                                    <div class="newsletter-feedback"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">
                            &copy; <?php echo date('Y'); ?> MEDINEXT SOLUTIONS. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-badges">
                            <span class="compliance-badge">
                                <i class="ph ph-shield-check"></i> HIPAA Compliant
                            </span>
                            <span class="compliance-badge">
                                <i class="ph ph-certificate"></i> ICD-10 Certified
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================ -->
    <!-- Back to Top Button (original) -->
    <!-- ============================================ -->
    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- ============================================ -->
    <!-- Scripts -->
    <!-- ============================================ -->

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- GSAP + ScrollTrigger -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    <!-- AOS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>

    <!-- Swiper.js -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

    <!-- Typed.js -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.16/dist/typed.umd.js" defer></script>

    <!-- Custom Scripts (cache-busted, deferred) -->
    <script src="assets/js/animations.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/animations.js'); ?>" defer></script>
    <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/main.js'); ?>" defer></script>
    <script src="assets/js/mega-menu.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/mega-menu.js'); ?>" defer></script>
    <script src="assets/js/color-panels-shader.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/color-panels-shader.js'); ?>" defer></script>

    <!-- SEO Enhancements -->
    <?php include __DIR__ . '/seo-foot-common.php'; ?>
</body>
</html>

