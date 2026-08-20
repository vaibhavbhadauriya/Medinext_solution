<?php
/**
 * MEDINEXT SOLUTIONS - Dynamic Location XML Sitemap Generator
 * Generates XML sitemap for state hubs and top US cities / metros
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/location-helper.php';

$pdo = getLocationPDO();
$states = getAllStates();

// Query all states and top cities (ranking <= 3 or pop >= 5000 for indexing priority)
$cities = [];
if ($pdo) {
    $stmt = $pdo->query("
        SELECT state_slug, city_slug, population, ranking 
        FROM cities 
        WHERE ranking <= 2 OR population >= 5000 
        ORDER BY population DESC
    ");
    $cities = $stmt->fetchAll();
}

$baseUrl = 'https://medinextsolutions.com';
$today = date('Y-m-d');

ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- ============================================== -->
    <!-- NATIONAL LOCATIONS DIRECTORY                   -->
    <!-- ============================================== -->
    <url>
        <loc><?php echo $baseUrl; ?>/locations/</loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- ============================================== -->
    <!-- ALL 50 US STATES & TERRITORIES (Priority 0.8)  -->
    <!-- ============================================== -->
<?php foreach ($states as $st): ?>
    <url>
        <loc><?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($st['slug']); ?>/</loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php endforeach; ?>

    <!-- ============================================== -->
    <!-- TOP US METRO AREAS & CITIES (Priority 0.7)     -->
    <!-- ============================================== -->
<?php foreach ($cities as $ct): 
    $priority = ((int)$ct['population'] >= 100000 || (int)$ct['ranking'] === 1) ? '0.8' : '0.7';
?>
    <url>
        <loc><?php echo $baseUrl; ?>/locations/<?php echo htmlspecialchars($ct['state_slug']); ?>/<?php echo htmlspecialchars($ct['city_slug']); ?>/</loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority><?php echo $priority; ?></priority>
    </url>
<?php endforeach; ?>

</urlset>
<?php
$xmlOutput = ob_get_clean();

// If called directly via browser / crawler, output XML header
if (php_sapi_name() !== 'cli' && !isset($argv)) {
    header('Content-Type: application/xml; charset=utf-8');
    echo $xmlOutput;
}

// Also write to static sitemap-locations.xml for high performance
file_put_contents(__DIR__ . '/sitemap-locations.xml', $xmlOutput);

if (php_sapi_name() === 'cli') {
    echo "Generated sitemap-locations.xml with " . (1 + count($states) + count($cities)) . " URLs.\n";
}
