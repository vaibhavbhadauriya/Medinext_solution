<?php
/**
 * MEDINEXT SOLUTIONS - US Cities SQLite & Cache Builder
 * Parses assets/US every city/uscities.csv and builds an indexed SQLite database + JSON cache
 */

declare(strict_types=1);

$csvFile = __DIR__ . '/../assets/US every city/uscities.csv';
$dbFile = __DIR__ . '/uscities.sqlite';
$cacheFile = __DIR__ . '/../config/states_cache.json';

if (!file_exists($csvFile)) {
    die("Error: CSV file not found at $csvFile\n");
}

function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text ?: 'n-a');
}

echo "Removing old database if exists...\n";
if (file_exists($dbFile)) {
    unlink($dbFile);
}

echo "Creating SQLite database at $dbFile...\n";
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$pdo->exec("
    CREATE TABLE IF NOT EXISTS states (
        id VARCHAR(2) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        total_population INTEGER DEFAULT 0,
        city_count INTEGER DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS cities (
        id INTEGER PRIMARY KEY,
        city VARCHAR(100) NOT NULL,
        city_ascii VARCHAR(100) NOT NULL,
        city_slug VARCHAR(120) NOT NULL,
        state_id VARCHAR(2) NOT NULL,
        state_name VARCHAR(100) NOT NULL,
        state_slug VARCHAR(100) NOT NULL,
        county_fips VARCHAR(10),
        county_name VARCHAR(100),
        lat REAL,
        lng REAL,
        population INTEGER,
        density REAL,
        timezone VARCHAR(50),
        ranking INTEGER,
        zips TEXT,
        external_id VARCHAR(20)
    );

    CREATE INDEX IF NOT EXISTS idx_cities_state_slug ON cities (state_slug);
    CREATE INDEX IF NOT EXISTS idx_cities_city_slug ON cities (city_slug);
    CREATE INDEX IF NOT EXISTS idx_cities_state_city ON cities (state_slug, city_slug);
    CREATE INDEX IF NOT EXISTS idx_cities_population ON cities (population DESC);
    CREATE INDEX IF NOT EXISTS idx_cities_ranking ON cities (ranking);
");

echo "Parsing CSV and inserting records...\n";
$f = fopen($csvFile, 'r');
$header = fgetcsv($f); // city,city_ascii,state_id,state_name,county_fips,county_name,lat,lng,population,density,source,military,incorporated,timezone,ranking,zips,id

$states = [];
$citiesInsertStmt = $pdo->prepare("
    INSERT INTO cities (
        city, city_ascii, city_slug, state_id, state_name, state_slug,
        county_fips, county_name, lat, lng, population, density,
        timezone, ranking, zips, external_id
    ) VALUES (
        :city, :city_ascii, :city_slug, :state_id, :state_name, :state_slug,
        :county_fips, :county_name, :lat, :lng, :population, :density,
        :timezone, :ranking, :zips, :external_id
    )
");

$pdo->beginTransaction();

$rowCount = 0;
$seenStateCity = [];

while (($row = fgetcsv($f)) !== false) {
    $rowCount++;
    $cityName = $row[0];
    $cityAscii = $row[1];
    $stateId = $row[2];
    $stateName = $row[3];
    $countyFips = $row[4];
    $countyName = $row[5];
    $lat = (float)$row[6];
    $lng = (float)$row[7];
    $population = (int)$row[8];
    $density = (float)$row[9];
    $timezone = $row[13];
    $ranking = (int)$row[14];
    $zips = $row[15];
    $externalId = $row[16];

    $stateSlug = slugify($stateName);
    $baseCitySlug = slugify($cityAscii);
    $citySlug = $baseCitySlug;

    // Disambiguate duplicate city slugs within the same state using county if needed
    $stateCityKey = $stateSlug . '/' . $citySlug;
    if (isset($seenStateCity[$stateCityKey])) {
        $countySlug = slugify($countyName);
        $citySlug = $baseCitySlug . '-' . $countySlug;
        $stateCityKey = $stateSlug . '/' . $citySlug;
        // If still duplicate, add rowCount
        if (isset($seenStateCity[$stateCityKey])) {
            $citySlug = $baseCitySlug . '-' . $rowCount;
            $stateCityKey = $stateSlug . '/' . $citySlug;
        }
    }
    $seenStateCity[$stateCityKey] = true;

    // Track state aggregate
    if (!isset($states[$stateId])) {
        $states[$stateId] = [
            'id' => $stateId,
            'name' => $stateName,
            'slug' => $stateSlug,
            'total_population' => 0,
            'city_count' => 0
        ];
    }
    $states[$stateId]['total_population'] += $population;
    $states[$stateId]['city_count']++;

    // Insert city
    $citiesInsertStmt->execute([
        ':city' => $cityName,
        ':city_ascii' => $cityAscii,
        ':city_slug' => $citySlug,
        ':state_id' => $stateId,
        ':state_name' => $stateName,
        ':state_slug' => $stateSlug,
        ':county_fips' => $countyFips,
        ':county_name' => $countyName,
        ':lat' => $lat,
        ':lng' => $lng,
        ':population' => $population,
        ':density' => $density,
        ':timezone' => $timezone,
        ':ranking' => $ranking,
        ':zips' => $zips,
        ':external_id' => $externalId
    ]);

    if ($rowCount % 5000 === 0) {
        echo "Processed $rowCount rows...\n";
    }
}

fclose($f);

// Insert states
$statesInsertStmt = $pdo->prepare("
    INSERT INTO states (id, name, slug, total_population, city_count)
    VALUES (:id, :name, :slug, :total_population, :city_count)
");

foreach ($states as $st) {
    $statesInsertStmt->execute([
        ':id' => $st['id'],
        ':name' => $st['name'],
        ':slug' => $st['slug'],
        ':total_population' => $st['total_population'],
        ':city_count' => $st['city_count']
    ]);
}

$pdo->commit();

echo "Database built successfully with $rowCount cities and " . count($states) . " states/territories!\n";

// Save lightweight state cache
usort($states, fn($a, $b) => strcmp($a['name'], $b['name']));
$cacheData = [
    'generated_at' => date('Y-m-d H:i:s'),
    'total_cities' => $rowCount,
    'total_states' => count($states),
    'states' => $states
];

file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT));
echo "Saved state cache to $cacheFile\n";
