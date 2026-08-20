<?php
/**
 * MEDINEXT SOLUTIONS - Dynamic Schema Include v1.0
 * File: includes/schema-dynamic.php
 * Usage: Include inside <head> on EVERY page, after schema-organization.php
 */

$isHome = ($requestUri === '/' || $requestUri === '/index.php');

$schemas = [];

// 1. BreadcrumbList Schema (for all pages except home)
if (!$isHome) {
    // Basic breadcrumb logic
    $pathTokens = array_filter(explode('/', trim($requestUri, '/')));
    $itemListElement = [];
    
    // Home is always position 1
    $itemListElement[] = [
        "@type" => "ListItem",
        "position" => 1,
        "name" => "Home",
        "item" => "https://medinextsolutions.com/"
    ];
    
    $position = 2;
    $currentPath = "https://medinextsolutions.com";
    
    foreach ($pathTokens as $token) {
        $currentPath .= '/' . $token . '/';
        $name = ucwords(str_replace('-', ' ', $token));
        
        $item = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => $name
        ];
        
        $item["item"] = $currentPath;
        
        $itemListElement[] = $item;
        $position++;
    }
    
    $schemas[] = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $itemListElement
    ];
}

// 2. Service Schema (for service pages)
// Check if the page is a service page
$isServicePage = strpos($requestUri, '-billing') !== false || 
                 strpos($requestUri, 'revenue-cycle-management') !== false || 
                 strpos($requestUri, 'denial-management') !== false ||
                 strpos($requestUri, 'prior-authorization') !== false ||
                 strpos($requestUri, 'medical-coding') !== false ||
                 strpos($requestUri, 'provider-credentialing') !== false;

if ($isServicePage) {
    $schemas[] = [
        "@context" => "https://schema.org",
        "@type" => "Service",
        "name" => isset($pageTitle) ? str_replace(' | MEDINEXT SOLUTIONS', '', $pageTitle) : 'Medical Billing Service',
        "description" => isset($pageDescription) ? $pageDescription : '',
        "provider" => [
            "@id" => "https://medinextsolutions.com/#organization"
        ],
        "areaServed" => [
            "@type" => "Country",
            "name" => "United States"
        ],
        "serviceType" => "Medical Billing and Revenue Cycle Management",
        "url" => isset($canonicalUrl) ? $canonicalUrl : "https://medinextsolutions.com" . $requestUri
    ];
}

if (!empty($schemas)) {
    echo "<!-- Dynamic SEO Schemas -->\n";
    echo '<script type="application/ld+json">' . "\n";
    if (count($schemas) === 1) {
        echo json_encode($schemas[0], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    } else {
        foreach ($schemas as $schema) {
            echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
            if ($schema !== end($schemas)) echo "</script>\n<script type=\"application/ld+json\">\n";
        }
    }
    echo "\n</script>\n";
}
?>
