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

/**
 * =========================================================================
 * MEDICARE ADMINISTRATIVE CONTRACTOR (MAC) MATRIX & REGIONAL COMPLIANCE
 * Authoritative 12-Jurisdiction & 7-Operator Regional Data Engine
 * Maps 50 US States + DC + 5 US Territories to CMS A/B MAC Jurisdictions
 * =========================================================================
 */

/**
 * Returns definitions for all 12 CMS A/B MAC Jurisdictions and their 7 contracted operators.
 *
 * @return array<string, array>
 */
function getAllMacJurisdictions(): array {
    static $jurisdictions = null;
    if ($jurisdictions !== null) {
        return $jurisdictions;
    }

    $jurisdictions = [
        'J-E' => [
            'code' => 'J-E',
            'jurisdiction_name' => 'Jurisdiction E (J-E)',
            'contractor' => 'Noridian Healthcare Solutions, LLC',
            'contractor_short' => 'Noridian',
            'headquarters' => 'Fargo, ND',
            'portal_name' => 'Noridian Medicare Portal (NMP)',
            'portal_url' => 'https://www.noridianmedicare.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L38397', 'name' => 'MolDX: Molecular Diagnostic Services'],
                ['id' => 'L34993', 'name' => 'Nerve Blockade & Epidural Injections'],
                ['id' => 'L34194', 'name' => 'Blepharoplasty & Brow Lift'],
                ['id' => 'L38904', 'name' => 'Wound Care & Cellular/Tissue-Based Products (CTPs)']
            ],
            'billing_nuances' => [
                'California Multi-Locality GPCI Pricing: Split across 9 geographic pricing localities; Box 32 physical service location zip code controls exact GPCI payment.',
                'Medi-Cal Automated 835 Crossover: Retroactive claims older than 1 year require Medi-Cal Delay Reason Code 11 with Medicare Remittance Advice attachment.',
                'Hospital Outpatient Department (OPD) Prior Authorization enforced for blepharoplasty, rhinoplasty, vein ablation, and cervical spinal fusion.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction E',
                'Noridian Healthcare Solutions',
                'Noridian Medicare Portal (NMP)',
                'California Medi-Cal Crossover & Locality GPCI Billing',
                'MolDX Molecular Diagnostic DEX Z-Codes',
                'Part A/B LCD Medical Necessity Guidelines',
                'Targeted Probe and Educate (TPE) Audit Defense',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-F' => [
            'code' => 'J-F',
            'jurisdiction_name' => 'Jurisdiction F (J-F)',
            'contractor' => 'Noridian Healthcare Solutions, LLC',
            'contractor_short' => 'Noridian',
            'headquarters' => 'Fargo, ND',
            'portal_name' => 'Noridian Medicare Portal (NMP)',
            'portal_url' => 'https://www.noridianmedicare.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L38397', 'name' => 'MolDX: Molecular Testing for Oncology and Infectious Disease'],
                ['id' => 'L38765', 'name' => 'Facet Joint Injections & Denervation'],
                ['id' => 'L33767', 'name' => 'Hyaluronan Injections for Osteoarthritis of the Knee'],
                ['id' => 'L34177', 'name' => 'Non-Coronary Vascular Stents & Angioplasty']
            ],
            'billing_nuances' => [
                'Alaska GPCI Frontier Floor: Protected under statutory GPCI floor adjustments (ACA § 10324), resulting in higher Medicare Part B work units.',
                'Critical Access Hospital (CAH) Method II Billing utilized throughout MT, WY, ND, SD, and ID (UB-04 Revenue Code 096X/097X with Attending NPI).',
                'State Medicaid coordination (AHCCCS in AZ, Apple Health in WA, OHP in OR) requires exact provider ID cross-walks on COB files.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction F',
                'Noridian Healthcare Solutions',
                'Noridian Medicare Portal (NMP)',
                'Alaska Statutory GPCI Frontier Floor Rates',
                'Rural Health Clinic (RHC) & CAH Method II Billing',
                'Arizona AHCCCS & Washington Apple Health Coordination',
                'MolDX Biomarker LCD Compliance',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-5' => [
            'code' => 'J-5',
            'jurisdiction_name' => 'Jurisdiction 5 (J-5)',
            'contractor' => 'Wisconsin Physicians Service (WPS GHA)',
            'contractor_short' => 'WPS GHA',
            'headquarters' => 'Madison, WI',
            'portal_name' => 'WPS GHA Secure Provider Portal',
            'portal_url' => 'https://www.wpsgha.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L39042', 'name' => 'Epidural Steroid Injections for Pain Management'],
                ['id' => 'L34741', 'name' => 'Cataract Surgery'],
                ['id' => 'L34563', 'name' => 'Outpatient Physical Therapy / OT / SLP'],
                ['id' => 'L39641', 'name' => 'Micro-Invasive Glaucoma Surgery (MIGS)']
            ],
            'billing_nuances' => [
                'Missouri MO HealthNet & Kansas KanCare Crossovers: Requires NPI-1 (Individual Rendering) and NPI-2 (Billing Group) taxonomy harmonization.',
                'WPS Electronic Redeterminations: Form CMS-20027 appeals must be initiated within 120 calendar days through WPS GHA Portal with highlighted clinical notes.',
                'Therapy threshold KX modifier compliance and strict 30-day signed Plan of Care adherence.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction 5',
                'Wisconsin Physicians Service (WPS GHA)',
                'WPS GHA Secure Provider Portal',
                'Missouri MO HealthNet & Kansas KanCare Coordination',
                'WPS Epidural & Cataract Surgery LCD Compliance',
                'Therapy Cap KX Modifier Auditing',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-6' => [
            'code' => 'J-6',
            'jurisdiction_name' => 'Jurisdiction 6 (J-6)',
            'contractor' => 'National Government Services, Inc. (NGS)',
            'contractor_short' => 'NGS',
            'headquarters' => 'Indianapolis, IN',
            'portal_name' => 'NGSConnex Provider Portal',
            'portal_url' => 'https://www.ngsmedicare.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L33580', 'name' => 'Non-Invasive Vascular Testing'],
                ['id' => 'L33632', 'name' => 'Psychiatric Partial Hospitalization Programs (PHP) & Psychotherapy'],
                ['id' => 'L33587', 'name' => 'Colorectal Cancer Screening & Colonoscopy'],
                ['id' => 'L33618', 'name' => 'Cardiac Rehabilitation Programs']
            ],
            'billing_nuances' => [
                'Illinois HFS / IMPACT Portal: Illinois Medicaid requires provider re-validation in IMPACT; automated cross-over 835 files matched via Provider Category of Service (COS).',
                'Minnesota MHCP & Wisconsin ForwardHealth: Real-time cross-over 837P reconciliation; electronic Additional Documentation Request (ADR) fulfillment via NGSConnex within 45 days.',
                'Non-Invasive Vascular: Separate billing of -26 (professional) and -TC (technical) components.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction 6',
                'National Government Services (NGS)',
                'NGSConnex Provider Portal',
                'Illinois HFS & Minnesota MHCP Crossover Processing',
                'NGS Psychiatric & Vascular LCD Policies',
                'Real-Time Electronic ADR Response & 835 Remittance Matching',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-8' => [
            'code' => 'J-8',
            'jurisdiction_name' => 'Jurisdiction 8 (J-8)',
            'contractor' => 'Wisconsin Physicians Service (WPS GHA)',
            'contractor_short' => 'WPS GHA',
            'headquarters' => 'Madison, WI',
            'portal_name' => 'WPS GHA Provider Portal',
            'portal_url' => 'https://www.wpsgha.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L34739', 'name' => 'Peripheral Vascular Interventions'],
                ['id' => 'L34697', 'name' => 'Cosmetic and Reconstructive Services'],
                ['id' => 'L34711', 'name' => 'Implantable Cardiac Pacemakers & Defibrillators'],
                ['id' => 'L34723', 'name' => 'Spinal Cord Stimulation for Chronic Intractable Pain']
            ],
            'billing_nuances' => [
                'Michigan Auto No-Fault PIP Fee Schedule Medicare Parity: Michigan Public Acts 21 & 22 tie auto insurance personal injury protection (PIP) medical reimbursements to 190%-250% of the WPS Medicare Part B fee schedule.',
                'Michigan CHAMPS & Indiana IHCP: 837P cross-over claims require registered CHAMPS Provider ID validation to prevent auto-denials.',
                'Spinal cord stimulation requires mandatory formal psychological evaluation prior to temporary trial lead placement.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction 8',
                'Wisconsin Physicians Service (WPS GHA)',
                'WPS GHA Provider Portal',
                'Michigan Auto No-Fault PIP Fee Schedule Medicare Parity',
                'Indiana IHCP & Michigan CHAMPS Medicaid Crossover',
                'WPS Spinal Cord Stimulation & Cardiac Pacemaker LCD Rules',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-H' => [
            'code' => 'J-H',
            'jurisdiction_name' => 'Jurisdiction H (J-H)',
            'contractor' => 'Novitas Solutions, Inc.',
            'contractor_short' => 'Novitas',
            'headquarters' => 'Mechanicsburg, PA',
            'portal_name' => 'Novitasphere Portal',
            'portal_url' => 'https://www.novitas-solutions.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L35041', 'name' => 'Wound Care & Cellular/Tissue-Based Products (CTPs)'],
                ['id' => 'L35049', 'name' => 'Monitored Anesthesia Care (MAC) Sedation'],
                ['id' => 'L36130', 'name' => 'Major Joint Replacement (Hip & Knee) Clinical Necessity'],
                ['id' => 'L35172', 'name' => 'Botulinum Toxins for Migraines and Spasticity']
            ],
            'billing_nuances' => [
                'Texas TMHP Crossover Compliance: Secondary claims crossing to TMHP require active Master Provider Index (MPI) enrollment and strict 95-day filing deadline from Medicare EOB date.',
                'Novitasphere EDI & MBI Discovery: Real-time Medicare Beneficiary Identifier (MBI) lookup, automated Redetermination filing via CMS-20027 e-forms.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.',
                'Targeted Probe and Educate (TPE) audit defense protocols for high-frequency E/M coding.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction H',
                'Novitas Solutions, Inc.',
                'Novitasphere Provider Portal',
                'Texas TMHP & Louisiana Healthy Louisiana Crossover Billing',
                'Novitas Wound Care & CTP Skin Substitute LCD (L35041)',
                'Monitored Anesthesia Care (MAC) Compliance',
                'JW/JZ Drug Wastage Audit Verification',
                'AAPC-Certified Medical Coding & Billing',
                'Targeted Probe and Educate (TPE) Audit Defense'
            ]
        ],
        'J-J' => [
            'code' => 'J-J',
            'jurisdiction_name' => 'Jurisdiction J (J-J)',
            'contractor' => 'Palmetto GBA, LLC',
            'contractor_short' => 'Palmetto GBA',
            'headquarters' => 'Columbia, SC',
            'portal_name' => 'Palmetto GBA eServices',
            'portal_url' => 'https://www.palmettogba.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L38397', 'name' => 'MolDX: Molecular Diagnostic Services'],
                ['id' => 'L36593', 'name' => 'Polysomnography and Sleep Testing'],
                ['id' => 'L34547', 'name' => 'Non-Vascular Ultrasound'],
                ['id' => 'L37643', 'name' => 'Debridement Services']
            ],
            'billing_nuances' => [
                'MolDX Regulatory Authority: Palmetto GBA establishes national MolDX policies mirrored across MACs. All precision medicine labs must submit assays through the DEX registry.',
                'Georgia GAMMIS & Tennessee TennCare: Secondary balance coordination across TennCare MCOs (BlueCare, Wellpoint, UHC Community Plan).',
                'Polysomnography requires documented Epworth Sleepiness Scale score >= 10 and AHI/RDI >= 15.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction J',
                'Palmetto GBA',
                'Palmetto GBA eServices Portal',
                'MolDX Program Authority & DEX Z-Code Registration',
                'Georgia GAMMIS & Tennessee TennCare Medicaid Coordination',
                'Palmetto Sleep Study & Wound Debridement LCD Guidelines',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-M' => [
            'code' => 'J-M',
            'jurisdiction_name' => 'Jurisdiction M (J-M)',
            'contractor' => 'Palmetto GBA, LLC',
            'contractor_short' => 'Palmetto GBA',
            'headquarters' => 'Columbia, SC',
            'portal_name' => 'Palmetto GBA eServices',
            'portal_url' => 'https://www.palmettogba.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L38853', 'name' => 'MolDX: Molecular Biomarkers for Solid Tumors and Liquid Biopsy'],
                ['id' => 'L38896', 'name' => 'Lumbar Spinal Fusion & Decompression'],
                ['id' => 'L34611', 'name' => 'Trigger Point & Tender Point Injections'],
                ['id' => 'L36141', 'name' => 'Chronic Care Management (CCM) & Principal Care Management']
            ],
            'billing_nuances' => [
                'North Carolina NC Medicaid Direct & Managed Care PHPs: Secondary claim balance crossover tracking with Prepaid Health Plans (Healthy Blue, WellCare, UnitedHealthcare, AmeriHealth Caritas).',
                'Virginia Cardinal Care (DMAS): Third-party liability (TPL) coordination and automated 835 cross-over processing.',
                'Lumbar spinal fusion requires 3-6 months conservative therapy and imaging confirmation of neural compression.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction M',
                'Palmetto GBA',
                'Palmetto GBA eServices Portal',
                'North Carolina NC Medicaid & Virginia Cardinal Care Crossover',
                'MolDX Biomarker Research Triangle Laboratory Compliance',
                'Chronic Care Management (CCM) & Spinal Fusion LCD Protocols',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-N' => [
            'code' => 'J-N',
            'jurisdiction_name' => 'Jurisdiction N (J-N)',
            'contractor' => 'First Coast Service Options, Inc. (FCSO)',
            'contractor_short' => 'FCSO',
            'headquarters' => 'Jacksonville, FL',
            'portal_name' => 'SPOT (Secure Provider Online Tool)',
            'portal_url' => 'https://medicare.fcso.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L33751', 'name' => 'Scanning Computerized Ophthalmic Diagnostic Imaging (SCODI / OCT)'],
                ['id' => 'L39641', 'name' => 'Micro-Invasive Glaucoma Surgery (MIGS)'],
                ['id' => 'L33748', 'name' => 'Joint Injections & Local Infiltration'],
                ['id' => 'L33822', 'name' => 'Continuous Glucose Monitors (CGM)']
            ],
            'billing_nuances' => [
                'High-Volume Senior Audit Scrutiny: FCSO enforces rigorous Targeted Probe & Educate (TPE) audits for high-volume Florida geriatric specialties (Ophthalmology, Pain, Dermatology, Cardiology).',
                'Puerto Rico (PR) & Virgin Islands (VI) Operations: Dual-language Spanish/English documentation handling; specific PR locality payment schedules.',
                'Florida Medicaid (AHCA / SMMC): Automatic crossover coordination with Florida Statewide Medicaid Managed Care plans.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction N',
                'First Coast Service Options (FCSO)',
                'SPOT (Secure Provider Online Tool)',
                'Florida AHCA Medicaid & Puerto Rico Crossover Billing',
                'FCSO Ophthalmology, SCODI OCT & MIGS LCD Policies',
                'Targeted Probe and Educate (TPE) Audit Defense',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-L' => [
            'code' => 'J-L',
            'jurisdiction_name' => 'Jurisdiction L (J-L)',
            'contractor' => 'Novitas Solutions, Inc.',
            'contractor_short' => 'Novitas',
            'headquarters' => 'Mechanicsburg, PA',
            'portal_name' => 'Novitasphere Portal',
            'portal_url' => 'https://www.novitas-solutions.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L35396', 'name' => 'Biomarkers for Oncology'],
                ['id' => 'L35056', 'name' => 'Transesophageal Echocardiography (TEE)'],
                ['id' => 'L35091', 'name' => 'Non-Invasive Vascular Studies'],
                ['id' => 'L35076', 'name' => 'Stereotactic Radiosurgery (SRS) & SBRT']
            ],
            'billing_nuances' => [
                'Maryland Total Cost of Care (TCOC) All-Payer Model: While Maryland hospitals operate under global budgets, Medicare Part B physician claims are billed directly to Novitas J-L using national Part B rules.',
                'Pennsylvania PROMISe & New Jersey FamilyCare: PA PROMISe requires 13-digit Provider ID mapping on secondary crossover claims within 180 days of Medicare RA.',
                'DC Department of Health Care Finance (DHCF) electronic crossover claim adjudication.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction L',
                'Novitas Solutions, Inc.',
                'Novitasphere Provider Portal',
                'Maryland All-Payer Model Part B Physician Billing',
                'Pennsylvania PROMISe & New Jersey FamilyCare Medicaid Crossover',
                'Novitas Radiation Oncology (SRS/SBRT) & Echocardiography LCD Guidelines',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-K' => [
            'code' => 'J-K',
            'jurisdiction_name' => 'Jurisdiction K (J-K)',
            'contractor' => 'National Government Services, Inc. (NGS)',
            'contractor_short' => 'NGS',
            'headquarters' => 'Indianapolis, IN',
            'portal_name' => 'NGSConnex Provider Portal',
            'portal_url' => 'https://www.ngsmedicare.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L35000', 'name' => 'MoPath: Molecular Pathology Procedures'],
                ['id' => 'L33615', 'name' => 'Lower Extremity Major Joint Replacements'],
                ['id' => 'L33631', 'name' => 'Outpatient Physical Therapy, OT, and SLP'],
                ['id' => 'L33583', 'name' => 'Non-Invasive Cardiac Imaging (SPECT, PET, Stress Echo)']
            ],
            'billing_nuances' => [
                'New York eMedNY Crossover & Downstate GPCI Differentials: NYC/Long Island (Locality 01/02) vs Upstate (Locality 99) GPCI variations. Secondary claims route automatically to eMedNY with Category of Service (COS) validation.',
                'MassHealth (MA) & CT HUSKY Health Coordination: Electronic attachments transmitted via Provider Online Service Center (POSC).',
                'Outpatient therapy follows Jimmo v. Sebelius maintenance therapy compliance and KX modifier threshold tracking.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction K',
                'National Government Services (NGS)',
                'NGSConnex Provider Portal',
                'New York eMedNY & Massachusetts MassHealth Crossover Billing',
                'Downstate NYC vs Upstate GPCI Locality Billing Differentials',
                'NGS Molecular Pathology (MoPath) & Cardiac Imaging LCDs',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ],
        'J-15' => [
            'code' => 'J-15',
            'jurisdiction_name' => 'Jurisdiction 15 (J-15)',
            'contractor' => 'CGS Administrators, LLC',
            'contractor_short' => 'CGS',
            'headquarters' => 'Nashville, TN',
            'portal_name' => 'myCGS Provider Web Portal',
            'portal_url' => 'https://www.cgsmedicare.com',
            'medicare_timely_filing' => '12 months (365 days) from Date of Service',
            'appeals_deadline' => '120 calendar days from Remittance Advice (Redetermination)',
            'key_lcds' => [
                ['id' => 'L38397', 'name' => 'MolDX: Molecular Diagnostic Services'],
                ['id' => 'L34170', 'name' => 'Outpatient Physical and Occupational Therapy Services'],
                ['id' => 'L36504', 'name' => 'Hyperbaric Oxygen Therapy (HBOT)'],
                ['id' => 'L34241', 'name' => 'Nerve Blockade: Somatic & Autonomic']
            ],
            'billing_nuances' => [
                'Ohio Department of Medicaid (ODM) NextGen PNM: Single Pharmacy Benefit Manager (SPBM) and NextGen EDI clearinghouse integration; automated 835 cross-over feeds.',
                'Kentucky Medicaid (KY DMS) Partner Portal: Managed care crossovers with Passport by Molina, Humana Healthy Horizons, WellCare, Anthem.',
                'National DME MAC Jurisdiction C Synergy: CGS also administers national DME MAC Jurisdiction C, providing specialized cross-specialty billing integration for in-office dispensed medical equipment.',
                'Mandatory single-use drug vial wastage recording with JW/JZ modifiers.'
            ],
            'knows_about' => [
                'Medicare Administrative Contractor (MAC) Jurisdiction 15',
                'CGS Administrators',
                'myCGS Provider Portal',
                'Ohio ODM NextGen & Kentucky DMS Medicaid Crossover',
                'CGS Hyperbaric Oxygen Therapy (HBOT) & Therapy LCD Policies',
                'DME MAC Jurisdiction C Cross-Billing Coordination',
                'AAPC-Certified Medical Coding & Billing',
                'Revenue Cycle Management (RCM)'
            ]
        ]
    ];

    return $jurisdictions;
}

/**
 * Returns state-level metadata mapping for all 50 US States, DC, and 5 Territories.
 * Keyed by uppercase 2-letter postal code.
 *
 * @return array<string, array>
 */
function getStatesMacMapping(): array {
    static $stateMap = null;
    if ($stateMap !== null) {
        return $stateMap;
    }

    $stateMap = [
        'AL' => [
            'name' => 'Alabama',
            'slug' => 'alabama',
            'mac' => 'J-J',
            'medicaid_program' => 'Alabama Medicaid Agency (AMA)',
            'medicaid_agency' => 'Alabama Medicaid Agency',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'AK' => [
            'name' => 'Alaska',
            'slug' => 'alaska',
            'mac' => 'J-F',
            'medicaid_program' => 'Alaska Department of Health (Alaska Medicaid)',
            'medicaid_agency' => 'Alaska Department of Health',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'AS' => [
            'name' => 'American Samoa',
            'slug' => 'american-samoa',
            'mac' => 'J-E',
            'medicaid_program' => 'American Samoa Medicaid Agency',
            'medicaid_agency' => 'American Samoa Medicaid State Agency',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'AZ' => [
            'name' => 'Arizona',
            'slug' => 'arizona',
            'mac' => 'J-F',
            'medicaid_program' => 'Arizona Health Care Cost Containment System (AHCCCS)',
            'medicaid_agency' => 'Arizona Health Care Cost Containment System Administration',
            'medicaid_timely_filing' => '12 months from Date of Service / 60 days from Medicare RA'
        ],
        'AR' => [
            'name' => 'Arkansas',
            'slug' => 'arkansas',
            'mac' => 'J-H',
            'medicaid_program' => 'Arkansas Department of Human Services (DHS / DMS)',
            'medicaid_agency' => 'Arkansas Department of Human Services (DHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'CA' => [
            'name' => 'California',
            'slug' => 'california',
            'mac' => 'J-E',
            'medicaid_program' => 'California Department of Health Care Services (Medi-Cal)',
            'medicaid_agency' => 'California Department of Health Care Services (DHCS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 60 days from Medicare RA'
        ],
        'CO' => [
            'name' => 'Colorado',
            'slug' => 'colorado',
            'mac' => 'J-H',
            'medicaid_program' => 'Health First Colorado (Colorado Medicaid / HCPF)',
            'medicaid_agency' => 'Colorado Department of Health Care Policy & Financing (HCPF)',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'CT' => [
            'name' => 'Connecticut',
            'slug' => 'connecticut',
            'mac' => 'J-K',
            'medicaid_program' => 'Connecticut Department of Social Services (HUSKY Health)',
            'medicaid_agency' => 'Connecticut Department of Social Services (DSS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'DE' => [
            'name' => 'Delaware',
            'slug' => 'delaware',
            'mac' => 'J-L',
            'medicaid_program' => 'Delaware Division of Medicaid & Medical Assistance (DMMA)',
            'medicaid_agency' => 'Delaware Department of Health and Social Services (DHSS / DMMA)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'DC' => [
            'name' => 'District of Columbia',
            'slug' => 'district-of-columbia',
            'mac' => 'J-L',
            'medicaid_program' => 'DC Department of Health Care Finance (DHCF)',
            'medicaid_agency' => 'District of Columbia Department of Health Care Finance',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'FL' => [
            'name' => 'Florida',
            'slug' => 'florida',
            'mac' => 'J-N',
            'medicaid_program' => 'Florida Agency for Health Care Administration (AHCA / SMMC)',
            'medicaid_agency' => 'Florida Agency for Health Care Administration (AHCA)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'GA' => [
            'name' => 'Georgia',
            'slug' => 'georgia',
            'mac' => 'J-J',
            'medicaid_program' => 'Georgia Department of Community Health (GAMMIS)',
            'medicaid_agency' => 'Georgia Department of Community Health (DCH)',
            'medicaid_timely_filing' => '180 days from Date of Service / Medicare RA'
        ],
        'GU' => [
            'name' => 'Guam',
            'slug' => 'guam',
            'mac' => 'J-E',
            'medicaid_program' => 'Guam Department of Public Health & Social Services',
            'medicaid_agency' => 'Guam Division of Public Welfare / Bureau of Health Care Financing',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'HI' => [
            'name' => 'Hawaii',
            'slug' => 'hawaii',
            'mac' => 'J-E',
            'medicaid_program' => 'Hawaii Department of Human Services (Med-QUEST)',
            'medicaid_agency' => 'Hawaii Department of Human Services (DHS)',
            'medicaid_timely_filing' => '12 months from Date of Service'
        ],
        'ID' => [
            'name' => 'Idaho',
            'slug' => 'idaho',
            'mac' => 'J-F',
            'medicaid_program' => 'Idaho Department of Health and Welfare (Idaho Medicaid)',
            'medicaid_agency' => 'Idaho Department of Health and Welfare (DHW)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'IL' => [
            'name' => 'Illinois',
            'slug' => 'illinois',
            'mac' => 'J-6',
            'medicaid_program' => 'Illinois Healthcare and Family Services (HFS / IMPACT)',
            'medicaid_agency' => 'Illinois Department of Healthcare and Family Services (HFS)',
            'medicaid_timely_filing' => '180 days from Date of Service / Medicare RA'
        ],
        'IN' => [
            'name' => 'Indiana',
            'slug' => 'indiana',
            'mac' => 'J-8',
            'medicaid_program' => 'Indiana Health Coverage Programs (IHCP / CoreMMIS)',
            'medicaid_agency' => 'Indiana Family and Social Services Administration (FSSA / OMPP)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'IA' => [
            'name' => 'Iowa',
            'slug' => 'iowa',
            'mac' => 'J-5',
            'medicaid_program' => 'Iowa Department of Health and Human Services (Iowa Total Care)',
            'medicaid_agency' => 'Iowa Department of Health and Human Services (HHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'KS' => [
            'name' => 'Kansas',
            'slug' => 'kansas',
            'mac' => 'J-5',
            'medicaid_program' => 'Kansas Department of Health and Environment (KanCare)',
            'medicaid_agency' => 'Kansas Department of Health and Environment (KDHE)',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'KY' => [
            'name' => 'Kentucky',
            'slug' => 'kentucky',
            'mac' => 'J-15',
            'medicaid_program' => 'Kentucky Department for Medicaid Services (KY DMS)',
            'medicaid_agency' => 'Kentucky Cabinet for Health and Family Services (CHFS / DMS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'LA' => [
            'name' => 'Louisiana',
            'slug' => 'louisiana',
            'mac' => 'J-H',
            'medicaid_program' => 'Louisiana Department of Health (Healthy Louisiana)',
            'medicaid_agency' => 'Louisiana Department of Health (LDH)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'ME' => [
            'name' => 'Maine',
            'slug' => 'maine',
            'mac' => 'J-K',
            'medicaid_program' => 'Maine Department of Health and Human Services (MaineCare)',
            'medicaid_agency' => 'Maine Department of Health and Human Services (DHHS / Office of MaineCare Services)',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'MD' => [
            'name' => 'Maryland',
            'slug' => 'maryland',
            'mac' => 'J-L',
            'medicaid_program' => 'Maryland Department of Health (Maryland Medicaid)',
            'medicaid_agency' => 'Maryland Department of Health (MDH)',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'MA' => [
            'name' => 'Massachusetts',
            'slug' => 'massachusetts',
            'mac' => 'J-K',
            'medicaid_program' => 'MassHealth (Executive Office of Health & Human Services)',
            'medicaid_agency' => 'Massachusetts Executive Office of Health and Human Services (EOHHS / MassHealth)',
            'medicaid_timely_filing' => '90 days from Date of Service / Medicare RA'
        ],
        'MI' => [
            'name' => 'Michigan',
            'slug' => 'michigan',
            'mac' => 'J-8',
            'medicaid_program' => 'Michigan Department of Health & Human Services (CHAMPS)',
            'medicaid_agency' => 'Michigan Department of Health and Human Services (MDHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'MN' => [
            'name' => 'Minnesota',
            'slug' => 'minnesota',
            'mac' => 'J-6',
            'medicaid_program' => 'Minnesota Department of Human Services (MHCP)',
            'medicaid_agency' => 'Minnesota Department of Human Services (DHS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'MS' => [
            'name' => 'Mississippi',
            'slug' => 'mississippi',
            'mac' => 'J-H',
            'medicaid_program' => 'Mississippi Division of Medicaid (DOM / Envision)',
            'medicaid_agency' => 'Mississippi Division of Medicaid (DOM)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'MO' => [
            'name' => 'Missouri',
            'slug' => 'missouri',
            'mac' => 'J-5',
            'medicaid_program' => 'Missouri Department of Social Services (MO HealthNet)',
            'medicaid_agency' => 'Missouri Department of Social Services (DSS / MHD)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'MT' => [
            'name' => 'Montana',
            'slug' => 'montana',
            'mac' => 'J-F',
            'medicaid_program' => 'Montana Department of Public Health & Human Services',
            'medicaid_agency' => 'Montana Department of Public Health and Human Services (DPHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'NE' => [
            'name' => 'Nebraska',
            'slug' => 'nebraska',
            'mac' => 'J-5',
            'medicaid_program' => 'Nebraska Department of Health and Human Services (Heritage Health)',
            'medicaid_agency' => 'Nebraska Department of Health and Human Services (DHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'NV' => [
            'name' => 'Nevada',
            'slug' => 'nevada',
            'mac' => 'J-E',
            'medicaid_program' => 'Nevada Division of Health Care Financing and Policy (NV Medicaid)',
            'medicaid_agency' => 'Nevada Department of Health and Human Services (DHCFP)',
            'medicaid_timely_filing' => '180 days from Date of Service / Medicare RA'
        ],
        'NH' => [
            'name' => 'New Hampshire',
            'slug' => 'new-hampshire',
            'mac' => 'J-K',
            'medicaid_program' => 'New Hampshire Department of Health and Human Services',
            'medicaid_agency' => 'New Hampshire Department of Health and Human Services (DHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'NJ' => [
            'name' => 'New Jersey',
            'slug' => 'new-jersey',
            'mac' => 'J-L',
            'medicaid_program' => 'New Jersey Division of Medical Assistance & Health Services (NJ FamilyCare)',
            'medicaid_agency' => 'New Jersey Department of Human Services (DMAHS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'NM' => [
            'name' => 'New Mexico',
            'slug' => 'new-mexico',
            'mac' => 'J-H',
            'medicaid_program' => 'New Mexico Human Services Department (Centennial Care / Turquoise Care)',
            'medicaid_agency' => 'New Mexico Health Care Authority Department (HCA)',
            'medicaid_timely_filing' => '90 days from Date of Service / Medicare RA'
        ],
        'NY' => [
            'name' => 'New York',
            'slug' => 'new-york',
            'mac' => 'J-K',
            'medicaid_program' => 'New York State Department of Health (eMedNY)',
            'medicaid_agency' => 'New York State Department of Health (NYSDOH / eMedNY)',
            'medicaid_timely_filing' => '90 days from Date of Service / 30 days from Medicare RA'
        ],
        'NC' => [
            'name' => 'North Carolina',
            'slug' => 'north-carolina',
            'mac' => 'J-M',
            'medicaid_program' => 'North Carolina Department of Health & Human Services (NC Medicaid Direct)',
            'medicaid_agency' => 'North Carolina Department of Health and Human Services (NCDHHS / NC Medicaid)',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'ND' => [
            'name' => 'North Dakota',
            'slug' => 'north-dakota',
            'mac' => 'J-F',
            'medicaid_program' => 'North Dakota Department of Health and Human Services',
            'medicaid_agency' => 'North Dakota Department of Health and Human Services (ND DHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'MP' => [
            'name' => 'Northern Mariana Islands',
            'slug' => 'northern-mariana-islands',
            'mac' => 'J-E',
            'medicaid_program' => 'Northern Mariana Islands Medicaid Program',
            'medicaid_agency' => 'Commonwealth Healthcare Corporation / State Medicaid Agency',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'OH' => [
            'name' => 'Ohio',
            'slug' => 'ohio',
            'mac' => 'J-15',
            'medicaid_program' => 'Ohio Department of Medicaid (ODM / NextGen PNM)',
            'medicaid_agency' => 'Ohio Department of Medicaid (ODM)',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'OK' => [
            'name' => 'Oklahoma',
            'slug' => 'oklahoma',
            'mac' => 'J-H',
            'medicaid_program' => 'Oklahoma Health Care Authority (SoonerCare)',
            'medicaid_agency' => 'Oklahoma Health Care Authority (OHCA)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'OR' => [
            'name' => 'Oregon',
            'slug' => 'oregon',
            'mac' => 'J-F',
            'medicaid_program' => 'Oregon Health Authority (Oregon Health Plan / OHP)',
            'medicaid_agency' => 'Oregon Health Authority (OHA)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'PA' => [
            'name' => 'Pennsylvania',
            'slug' => 'pennsylvania',
            'mac' => 'J-L',
            'medicaid_program' => 'Pennsylvania Department of Human Services (PROMISe)',
            'medicaid_agency' => 'Pennsylvania Department of Human Services (DHS)',
            'medicaid_timely_filing' => '180 days from Medicare Remittance (RA) date'
        ],
        'PR' => [
            'name' => 'Puerto Rico',
            'slug' => 'puerto-rico',
            'mac' => 'J-N',
            'medicaid_program' => 'Puerto Rico Medicaid Program (Plan Vital / ASES)',
            'medicaid_agency' => 'Puerto Rico Department of Health / ASES (Medicaid)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'RI' => [
            'name' => 'Rhode Island',
            'slug' => 'rhode-island',
            'mac' => 'J-K',
            'medicaid_program' => 'Rhode Island Executive Office of Health & Human Services (RI Medicaid)',
            'medicaid_agency' => 'Rhode Island Executive Office of Health and Human Services (EOHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 90 days from Medicare RA'
        ],
        'SC' => [
            'name' => 'South Carolina',
            'slug' => 'south-carolina',
            'mac' => 'J-M',
            'medicaid_program' => 'South Carolina Department of Health and Human Services (Healthy Connections)',
            'medicaid_agency' => 'South Carolina Department of Health and Human Services (SCDHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'SD' => [
            'name' => 'South Dakota',
            'slug' => 'south-dakota',
            'mac' => 'J-F',
            'medicaid_program' => 'South Dakota Department of Social Services (SD Medicaid)',
            'medicaid_agency' => 'South Dakota Department of Social Services (DSS)',
            'medicaid_timely_filing' => '180 days from Date of Service / Medicare RA'
        ],
        'TN' => [
            'name' => 'Tennessee',
            'slug' => 'tennessee',
            'mac' => 'J-J',
            'medicaid_program' => 'Tennessee Division of TennCare (TennCare)',
            'medicaid_agency' => 'Tennessee Division of TennCare',
            'medicaid_timely_filing' => '365 days from Date of Service / 120 days from Medicare RA'
        ],
        'TX' => [
            'name' => 'Texas',
            'slug' => 'texas',
            'mac' => 'J-H',
            'medicaid_program' => 'Texas Medicaid & Healthcare Partnership (TMHP)',
            'medicaid_agency' => 'Texas Health and Human Services Commission (HHSC)',
            'medicaid_timely_filing' => '95 days from Medicare Remittance (RA) date'
        ],
        'UT' => [
            'name' => 'Utah',
            'slug' => 'utah',
            'mac' => 'J-F',
            'medicaid_program' => 'Utah Department of Health & Human Services (Utah Medicaid)',
            'medicaid_agency' => 'Utah Department of Health and Human Services (DHHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'VT' => [
            'name' => 'Vermont',
            'slug' => 'vermont',
            'mac' => 'J-K',
            'medicaid_program' => 'Vermont Agency of Human Services (Green Mountain Care / Vermont Medicaid)',
            'medicaid_agency' => 'Vermont Agency of Human Services (Department of Vermont Health Access / DVHA)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'VI' => [
            'name' => 'U.S. Virgin Islands',
            'slug' => 'virgin-islands',
            'mac' => 'J-N',
            'medicaid_program' => 'Virgin Islands Department of Human Services (VI Medicaid)',
            'medicaid_agency' => 'Virgin Islands Department of Human Services',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'VA' => [
            'name' => 'Virginia',
            'slug' => 'virginia',
            'mac' => 'J-M',
            'medicaid_program' => 'Virginia Department of Medical Assistance Services (DMAS / Cardinal Care)',
            'medicaid_agency' => 'Virginia Department of Medical Assistance Services (DMAS)',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'WA' => [
            'name' => 'Washington',
            'slug' => 'washington',
            'mac' => 'J-F',
            'medicaid_program' => 'Washington State Health Care Authority (Apple Health)',
            'medicaid_agency' => 'Washington State Health Care Authority (HCA)',
            'medicaid_timely_filing' => '365 days from Date of Service / 180 days from Medicare RA'
        ],
        'WV' => [
            'name' => 'West Virginia',
            'slug' => 'west-virginia',
            'mac' => 'J-M',
            'medicaid_program' => 'West Virginia Department of Health and Human Resources (BMS / WV Medicaid)',
            'medicaid_agency' => 'West Virginia Bureau for Medical Services (BMS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'WI' => [
            'name' => 'Wisconsin',
            'slug' => 'wisconsin',
            'mac' => 'J-6',
            'medicaid_program' => 'Wisconsin Department of Health Services (ForwardHealth)',
            'medicaid_agency' => 'Wisconsin Department of Health Services (DHS)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ],
        'WY' => [
            'name' => 'Wyoming',
            'slug' => 'wyoming',
            'mac' => 'J-F',
            'medicaid_program' => 'Wyoming Department of Health (Wyoming Medicaid)',
            'medicaid_agency' => 'Wyoming Department of Health (WDH)',
            'medicaid_timely_filing' => '365 days from Date of Service'
        ]
    ];

    return $stateMap;
}

/**
 * Normalizes a state code, slug, or full state name into its canonical uppercase 2-letter postal code.
 *
 * @param string $stateIdOrSlug State 2-letter code (e.g. 'TX', 'tx'), slug (e.g. 'texas', 'puerto-rico'), or name
 * @return string|null Uppercase 2-letter state code or null if unrecognized
 */
function normalizeStateCode(string $stateIdOrSlug): ?string {
    $clean = trim($stateIdOrSlug);
    if ($clean === '') {
        return null;
    }

    $stateMap = getStatesMacMapping();
    $upper = strtoupper($clean);

    // Direct 2-letter uppercase match
    if (isset($stateMap[$upper])) {
        return $upper;
    }

    // Slug-based matching
    $normalizedSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $clean));
    $normalizedSlug = trim($normalizedSlug, '-');

    // Slug aliases map for edge cases (e.g. US Virgin Islands, Washington DC)
    $slugAliases = [
        'district-of-columbia' => 'DC',
        'washington-dc' => 'DC',
        'dc' => 'DC',
        'puerto-rico' => 'PR',
        'pr' => 'PR',
        'american-samoa' => 'AS',
        'american_samoa' => 'AS',
        'americansamoa' => 'AS',
        'as' => 'AS',
        'guam' => 'GU',
        'gu' => 'GU',
        'northern-mariana-islands' => 'MP',
        'northern-mariana' => 'MP',
        'mp' => 'MP',
        'virgin-islands' => 'VI',
        'u-s-virgin-islands' => 'VI',
        'us-virgin-islands' => 'VI',
        'vi' => 'VI'
    ];

    if (isset($slugAliases[$normalizedSlug])) {
        return $slugAliases[$normalizedSlug];
    }

    foreach ($stateMap as $code => $info) {
        if ($info['slug'] === $normalizedSlug || strtolower($info['name']) === strtolower($clean)) {
            return $code;
        }
    }

    return null;
}

/**
 * Resolves the full Medicare Administrative Contractor (MAC) and regional compliance profile for a state.
 *
 * @param string $stateIdOrSlug State 2-letter uppercase/lowercase code (e.g. 'TX', 'tx') or slug (e.g. 'texas', 'california')
 * @return array|null Associative array matching PROJECT.md § Interface Contracts or null if invalid
 */
function getMacJurisdiction(string $stateIdOrSlug): ?array {
    $stateCode = normalizeStateCode($stateIdOrSlug);
    if ($stateCode === null) {
        return null;
    }

    $stateMap = getStatesMacMapping();
    if (!isset($stateMap[$stateCode])) {
        return null;
    }

    $stateData = $stateMap[$stateCode];
    $jurisdictions = getAllMacJurisdictions();
    $macCode = $stateData['mac'];

    if (!isset($jurisdictions[$macCode])) {
        return null;
    }

    $macBase = $jurisdictions[$macCode];

    return [
        'code' => $macBase['code'],
        'jurisdiction_name' => $macBase['jurisdiction_name'],
        'contractor' => $macBase['contractor'],
        'contractor_short' => $macBase['contractor_short'],
        'headquarters' => $macBase['headquarters'],
        'portal_name' => $macBase['portal_name'],
        'portal_url' => $macBase['portal_url'],
        'medicaid_program' => $stateData['medicaid_program'],
        'medicaid_agency' => $stateData['medicaid_agency'],
        'medicare_timely_filing' => $macBase['medicare_timely_filing'],
        'medicaid_timely_filing' => $stateData['medicaid_timely_filing'],
        'appeals_deadline' => $macBase['appeals_deadline'],
        'key_lcds' => $macBase['key_lcds'],
        'billing_nuances' => $macBase['billing_nuances'],
        'knows_about' => $macBase['knows_about']
    ];
}

