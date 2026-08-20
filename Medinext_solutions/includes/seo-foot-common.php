<?php
/**
 * MEDINEXT SOLUTIONS ? SEO Foot Common Include v1.0
 * File: includes/seo-foot-common.php
 * Usage: Place just BEFORE </body> on every page
 */
$_ph_host_f  = $_SERVER['HTTP_HOST'] ?? 'medinextsolutions.com';
$_ph_is_local_f = in_array($_ph_host_f, ['localhost', '127.0.0.1']);
$_ph_base_f  = ($_ph_is_local_f ? 'http://' . $_ph_host_f . '/Medinext_solution/Medinext_solutions' : 'https://' . $_ph_host_f);
?>
    <!-- ============================================================ -->
    <!-- MEDINEXT SOLUTIONS ? SEO Foot Common (seo-foot-common.php) -->
    <!-- ============================================================ -->

    <!-- GTM Body Snippet ? replace GTM-XXXXXXX with your container ID -->
    <noscript>
      <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
              height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>


    <!-- Cookie Consent Banner (Component 6) -->
    <div class="ph-cookie-banner" id="ph-cookie-banner" role="dialog" aria-live="polite" aria-label="Cookie preferences">
      <div class="ph-cookie-inner">
        <p>&#127850; We use cookies to enhance your experience and analyze site traffic.
           <a href="<?php echo $_ph_base_f; ?>/privacy-policy/" aria-label="Read our privacy policy">Privacy Policy</a>
        </p>
        <div class="ph-cookie-actions">
          <button class="ph-cookie-btn ph-cookie-btn-accept"    id="ph-cookie-accept"    aria-label="Accept all cookies">Accept All</button>
          <button class="ph-cookie-btn ph-cookie-btn-essential" id="ph-cookie-essential" aria-label="Accept essential cookies only">Essential Only</button>
        </div>
      </div>
    </div>

    <!-- Sticky Mobile CTA Bar (Component 5) -->
    <div class="ph-mobile-cta" id="ph-mobile-cta" role="complementary" aria-label="Quick contact options">
      <a href="tel:+19088290133"
         class="ph-mobile-cta-btn ph-mobile-cta-call"
         aria-label="Call MEDINEXT SOLUTIONS now at 908-829-0133"
         data-cta="mobile-call">
        &#128222; Call Now
      </a>
      <a href="<?php echo $_ph_base_f; ?>/free-practice-audit/"
         class="ph-mobile-cta-btn ph-mobile-cta-audit"
         aria-label="Get your free practice revenue audit"
         data-cta="mobile-audit">
        &#128203; Free Audit
      </a>
    </div>

    <!-- Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
<?php if (!$_ph_is_local_f): ?>
        navigator.serviceWorker.register('<?php echo $_ph_base_f; ?>/sw.js')
          .then(function(reg) { console.log('[SW] Registered:', reg.scope); })
          .catch(function(err){ console.warn('[SW] Registration failed:', err); });
<?php else: ?>
        // Unregister service worker on localhost to prevent aggressive caching during development
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
          for (let registration of registrations) {
            registration.unregister().then(function(success) {
              if (success) console.log('[SW] Unregistered on localhost.');
            });
          }
        });
<?php endif; ?>
      });
    }
    </script>

    <!-- SEO Enhancement JS (loads deferred, last) -->
    <script src="<?php echo $_ph_base_f; ?>/assets/js/seo-enhancements.js" defer></script>
