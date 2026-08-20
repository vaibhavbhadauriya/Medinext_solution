<?php
/**
 * MEDINEXT SOLUTIONS - Location Engine & SEO Helper
 * Handles querying US states, cities, nearby locations, and generating dynamic local SEO metadata.
 */

declare(strict_types=1);

function getLocationPDO(): ?PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dbPath = __DIR__ . '/../database/uscities.sqlite';
        if (!file_exists($dbPath)) {
            return null;
        }
        try {
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('SQLite Location DB Error: ' . $e->getMessage());
            return null;
        }
    }
    return $pdo;
}

/**
 * Get all US states sorted alphabetically
 */
function getAllStates(): array {
    static $states = null;
    if ($states !== null) {
        return $states;
    }

    $cacheFile = __DIR__ . '/../config/states_cache.json';
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (!empty($data['states'])) {
            $states = $data['states'];
            return $states;
        }
    }

    $pdo = getLocationPDO();
    if (!$pdo) return [];

    $stmt = $pdo->query("SELECT * FROM states ORDER BY name ASC");
    $states = $stmt->fetchAll();
    return $states;
}

/**
 * Get state details by slug (e.g., 'california', 'texas')
 */
function getStateBySlug(string $stateSlug): ?array {
    $states = getAllStates();
    foreach ($states as $st) {
        if ($st['slug'] === strtolower($stateSlug)) {
            return $st;
        }
    }

    $pdo = getLocationPDO();
    if (!$pdo) return null;

    $stmt = $pdo->prepare("SELECT * FROM states WHERE slug = :slug LIMIT 1");
    $stmt->execute([':slug' => strtolower($stateSlug)]);
    $res = $stmt->fetch();
    return $res ?: null;
}

/**
 * Get city details by state slug and city slug
 */
function getCityBySlug(string $stateSlug, string $citySlug): ?array {
    $pdo = getLocationPDO();
    if (!$pdo) return null;

    $stmt = $pdo->prepare("
        SELECT * FROM cities 
        WHERE state_slug = :state_slug AND city_slug = :city_slug 
        LIMIT 1
    ");
    $stmt->execute([
        ':state_slug' => strtolower($stateSlug),
        ':city_slug' => strtolower($citySlug)
    ]);
    $res = $stmt->fetch();
    return $res ?: null;
}

/**
 * Get cities within a state, ordered by population or name
 */
function getCitiesByState(string $stateSlug, int $limit = 60, int $offset = 0, string $orderBy = 'population DESC'): array {
    $pdo = getLocationPDO();
    if (!$pdo) return [];

    $allowedOrder = ['population DESC', 'city ASC', 'ranking ASC'];
    if (!in_array($orderBy, $allowedOrder, true)) {
        $orderBy = 'population DESC';
    }

    $stmt = $pdo->prepare("
        SELECT * FROM cities 
        WHERE state_slug = :state_slug 
        ORDER BY $orderBy 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':state_slug', strtolower($stateSlug), PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get total city count in a state
 */
function getCityCountInState(string $stateSlug): int {
    $pdo = getLocationPDO();
    if (!$pdo) return 0;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cities WHERE state_slug = :state_slug");
    $stmt->execute([':state_slug' => strtolower($stateSlug)]);
    return (int)$stmt->fetchColumn();
}

/**
 * Get top metropolitan cities across the USA
 */
function getTopMetroCities(int $limit = 24): array {
    $pdo = getLocationPDO();
    if (!$pdo) return [];

    $stmt = $pdo->prepare("
        SELECT * FROM cities 
        WHERE ranking = 1 OR population >= 500000 
        ORDER BY population DESC 
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get nearby / sister cities in the same county or state for cross-linking
 */
function getNearbyCities(array $currentCity, int $limit = 12): array {
    $pdo = getLocationPDO();
    if (!$pdo) return [];

    // Try same county first
    $stmt = $pdo->prepare("
        SELECT * FROM cities 
        WHERE state_slug = :state_slug 
          AND county_name = :county_name 
          AND id != :current_id 
        ORDER BY population DESC 
        LIMIT :limit
    ");
    $stmt->bindValue(':state_slug', $currentCity['state_slug'], PDO::PARAM_STR);
    $stmt->bindValue(':county_name', $currentCity['county_name'], PDO::PARAM_STR);
    $stmt->bindValue(':current_id', $currentCity['id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();

    if (count($results) < $limit) {
        $needed = $limit - count($results);
        $existingIds = array_merge([$currentCity['id']], array_column($results, 'id'));
        $placeholders = implode(',', array_fill(0, count($existingIds), '?'));

        $sql = "
            SELECT * FROM cities 
            WHERE state_slug = ? 
              AND id NOT IN ($placeholders) 
            ORDER BY population DESC 
            LIMIT ?
        ";
        $stmt2 = $pdo->prepare($sql);
        $params = array_merge([$currentCity['state_slug']], $existingIds, [$needed]);
        $stmt2->execute($params);
        $more = $stmt2->fetchAll();
        $results = array_merge($results, $more);
    }

    return $results;
}

/**
 * Get list of medical & dental billing specialties for rich content & schema
 */
function getLocationSpecialties(): array {
    return [
        ['name' => 'Physical & Occupational Therapy', 'slug' => 'therapy-billing-services', 'icon' => 'bi-activity', 'desc' => 'ST, PT, and OT billing with strict modifier compliance (GN/GO/GP) and timed unit tracking.'],
        ['name' => 'Cardiology & Cardiovascular', 'slug' => 'cardiovascular-billing-services', 'icon' => 'bi-heart-pulse', 'desc' => 'High-complexity cardiac catheterization, EP studies, echocardiograms, and device monitoring.'],
        ['name' => 'Behavioral Health & Psychiatry', 'slug' => 'behavioral-health-billing', 'icon' => 'bi-person-bounding-box', 'desc' => 'Psychiatry, psychotherapy, IOP, PHP, and substance abuse billing with parity law enforcement.'],
        ['name' => 'Pain Management', 'slug' => 'pain-management-billing', 'icon' => 'bi-bandaid', 'desc' => 'Interventional pain injections, nerve blocks, radiofrequency ablations, and spinal cord stimulation.'],
        ['name' => 'Durable Medical Equipment (DME)', 'slug' => 'dme-billing-services', 'icon' => 'bi-wheelchair', 'desc' => 'HCPCS Level II coding, prior authorizations, CMNs, and Brightree/Kareo integration.'],
        ['name' => 'Oncology & Hematology', 'slug' => 'oncology-hematology-billing', 'icon' => 'bi-capsule', 'desc' => 'Complex chemotherapy infusions, JW drug wastage tracking, and multi-tier payer appeals.'],
        ['name' => 'Dental & Maxillofacial', 'slug' => 'dental-billing-services', 'icon' => 'bi-file-earmark-medical', 'desc' => 'Medical-dental cross coding, CDT claim processing, and secondary insurance recovery.'],
        ['name' => 'Neurology & Neurosurgery', 'slug' => 'neurology-billing-services', 'icon' => 'bi-graph-up-arrow', 'desc' => 'EEG, EMG/NCV studies, botox for migraines, and neurosurgical procedure coding.'],
        ['name' => 'Dermatology & Mohs Surgery', 'slug' => 'dermatology-billing', 'icon' => 'bi-person-lines-fill', 'desc' => 'Biopsies, lesion excisions, Mohs micrographic surgery, and cosmetic vs medical coding.'],
        ['name' => 'Internal & Family Medicine', 'slug' => 'family-medicine-billing', 'icon' => 'bi-house-heart', 'desc' => 'Preventive visits, chronic care management (CCM), remote patient monitoring (RPM), and E/M coding.'],
        ['name' => 'Orthopedic Surgery', 'slug' => 'orthopedic-billing', 'icon' => 'bi-person-arms-up', 'desc' => 'Joint replacements, arthroscopy, fracture care, and global surgical package tracking.'],
        ['name' => 'Radiology & Imaging', 'slug' => 'radiology-billing-services', 'icon' => 'bi-camera', 'desc' => 'Professional vs technical component splits (-26 / -TC) and high-volume image claims.']
    ];
}

/**
 * Generate localized FAQs for city landing pages
 */
function generateLocationFAQs(array $city): array {
    $cityName = $city['city'];
    $stateName = $city['state_name'];
    $stateId = $city['state_id'];
    $county = $city['county_name'];

    return [
        [
            'q' => "Why should healthcare practices in {$cityName}, {$stateId} outsource medical billing to MEDINEXT SOLUTIONS?",
            'a' => "Practices in {$cityName} face rising overhead costs, changing payer guidelines, and high claim denial rates. MEDINEXT SOLUTIONS delivers a dedicated team of AAPC-certified medical coders and billing specialists that achieve an industry-leading 98% first-pass clean claim rate, accelerate accounts receivable to under 30 days, and boost practice revenue by an average of 15% to 30% without hiring local in-house staff."
        ],
        [
            'q' => "Do you support all major commercial and government payers in {$stateName}?",
            'a' => "Yes. We have deep expertise with all commercial insurers operating across {$stateName} (including Blue Cross Blue Shield, Aetna, UnitedHealthcare, Cigna, Humana) as well as state Medicaid programs, Medicare Administrative Contractors (MACs), Medicare Advantage, and workers' compensation carriers serving {$county} County."
        ],
        [
            'q' => "Which EHR and Practice Management systems do you work with for {$cityName} clinics?",
            'a' => "We seamlessly integrate with over 40+ leading healthcare software platforms, including Epic, eClinicalWorks, AdvancedMD, Kareo/Tebra, NextGen, Athenahealth, ModMed, WebPT, Dentrix, Eaglesoft, and Brightree. Your {$cityName} staff can keep your existing software while we handle all back-office RCM tasks."
        ],
        [
            'q' => "How quickly can MEDINEXT SOLUTIONS onboard a medical practice in {$cityName}, {$stateId}?",
            'a' => "Our streamlined onboarding protocol gets your practice live in as little as 7 to 10 business days. We conduct an initial practice audit, connect with your clearinghouse and EHR, map provider credentials, and establish workflow rules with zero disruption to your daily patient appointments."
        ],
        [
            'q' => "What is your claim denial management and appeals strategy for {$cityName} providers?",
            'a' => "We operate on a zero-tolerance policy for unpaid claims. Every denied claim is audited within 24–48 hours, matched with correct clinical documentation or modifiers, and aggressively appealed through multi-level payer channels until paid in full."
        ]
    ];
}
