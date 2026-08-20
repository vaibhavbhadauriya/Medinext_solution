<?php
/**
 * MEDINEXT SOLUTIONS ? SEO Head Common Include v1.0
 * File: includes/seo-head-common.php
 * Usage: Place inside <head> of every page, AFTER your existing meta tags
 */

// Detect base URL for localhost vs production
$_ph_host   = $_SERVER['HTTP_HOST'] ?? 'medinextsolutions.com';
$_ph_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_ph_is_local = in_array($_ph_host, ['localhost', '127.0.0.1']);
$_ph_base   = $_ph_scheme . '://' . $_ph_host . ($_ph_is_local ? '/Medinext_solution/Medinext_solutions' : '');
?>
    <!-- ============================================================ -->
    <!-- MEDINEXT SOLUTIONS ? SEO Head Common (seo-head-common.php)  -->
    <!-- ============================================================ -->



    <!-- SEO Enhancement CSS (loads after main stylesheet) -->
    <link rel="stylesheet" href="<?php echo $_ph_base; ?>/assets/css/seo-enhancements.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/seo-enhancements.css'); ?>">

    <!-- dataLayer Init + GTM Head Snippet -->
    <script>
    // Initialize dataLayer BEFORE GTM loads
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
      site_name:     'MEDINEXT SOLUTIONS',
      page_language: 'en',
      page_url:      window.location.href
    });
    </script>

