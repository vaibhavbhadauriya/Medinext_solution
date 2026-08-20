<?php
/**
 * MEDINEXT SOLUTIONS ? Organization Schema Include v1.0
 * File: includes/schema-organization.php
 * Usage: Include inside <head> on EVERY page (homepage, services, contact, etc.)
 * Note: AggregateRating only included on pages where reviews are visible
 */
$_show_reviews = isset($showAggregateRating) && $showAggregateRating === true;
?>
    <!-- Organization + MedicalBusiness Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": ["Organization", "MedicalBusiness"],
          "@id": "https://medinextsolutions.com/#organization",
          "name": "MEDINEXT SOLUTIONS",
          "alternateName": "MEDINEXT SOLUTIONS Medical Billing",
          "description": "AAPC-certified medical billing and revenue cycle management serving 500+ providers nationwide with a 98% clean claim rate.",
          "url": "https://medinextsolutions.com",
          "logo": {
            "@type": "ImageObject",
            "url": "https://medinextsolutions.com/assets/images/logo.png",
            "width": 300, "height": 80
          },
          "image": "https://medinextsolutions.com/assets/images/og-image.jpg",
          "telephone": "+1-908-829-0133",
          "email": "Info@medinextsolutions.com",
          "foundingDate": "2015",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "234 Old Stage Rd",
            "addressLocality": "East Brunswick",
            "addressRegion": "NJ",
            "postalCode": "08816",
            "addressCountry": "US"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": 40.4390,
            "longitude": -74.4143
          },
          "openingHoursSpecification": [{
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
            "opens": "00:00", "closes": "23:59"
          }],
          "priceRange": "$$",
          "areaServed": {"@type": "Country", "name": "United States"},
          "sameAs": [
            "https://www.linkedin.com/company/medinextsolutions",
            "https://www.facebook.com/medinextsolutions",
            "https://twitter.com/medinextsolutions"
          ]<?php if ($_show_reviews): ?>,
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "bestRating": "5",
            "worstRating": "1",
            "reviewCount": "5"
          },
          "review": [
            {"@type":"Review","author":{"@type":"Person","name":"Dr. Michael Torres"},"reviewRating":{"@type":"Rating","ratingValue":"5","bestRating":"5"},"datePublished":"2024-11-15","reviewBody":"MEDINEXT SOLUTIONS transformed our practice revenue. Our denial rate dropped from 18% to under 2% in just 90 days."},
            {"@type":"Review","author":{"@type":"Person","name":"Sarah Jenkins"},"reviewRating":{"@type":"Rating","ratingValue":"5","bestRating":"5"},"datePublished":"2024-10-22","reviewBody":"Claim denials dropped near zero and revenue increased by over 30%."},
            {"@type":"Review","author":{"@type":"Person","name":"Dr. Priya Nair"},"reviewRating":{"@type":"Rating","ratingValue":"5","bestRating":"5"},"datePublished":"2024-09-10","reviewBody":"Our AR days went from 52 to 15. I cannot recommend them enough."},
            {"@type":"Review","author":{"@type":"Person","name":"Mark Davidson"},"reviewRating":{"@type":"Rating","ratingValue":"5","bestRating":"5"},"datePublished":"2024-08-19","reviewBody":"We recovered over $240,000 in previously denied claims in the first six months."},
            {"@type":"Review","author":{"@type":"Person","name":"Dr. Aisha Okafor"},"reviewRating":{"@type":"Rating","ratingValue":"5","bestRating":"5"},"datePublished":"2024-07-05","reviewBody":"Switching to MEDINEXT SOLUTIONS was the single best financial decision for our behavioral health practice."}
          ]<?php endif; ?>
        },
        {
          "@type": "WebSite",
          "@id": "https://medinextsolutions.com/#website",
          "url": "https://medinextsolutions.com",
          "name": "MEDINEXT SOLUTIONS",
          "publisher": {"@id": "https://medinextsolutions.com/#organization"},
          "potentialAction": {
            "@type": "SearchAction",
            "target": {"@type": "EntryPoint", "urlTemplate": "https://medinextsolutions.com/?s={search_term_string}"},
            "query-input": "required name=search_term_string"
          }
        }
      ]
    }
    </script>
