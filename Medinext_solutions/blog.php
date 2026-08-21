<?php
/**
 * MEDINEXT SOLUTIONS ? Blog Listing Page
 * Redesigned with animated card layout
 */

$pageTitle = 'Healthcare Billing Blog & RCM Insights | MEDINEXT SOLUTIONS';
$pageDescription = 'Expert insights on medical coding updates, denial management, revenue cycle optimization, and healthcare billing best practices from AAPC-certified specialists.';
$pageKeywords = 'medical billing blog, revenue cycle management tips, denial management, healthcare coding, RCM insights';

require_once 'includes/header.php';

// Blog posts data ? maps folder names to display info
$posts = [
  [
    'slug'     => 'revenue-cycle-management-guide',
    'title'    => 'Revenue Cycle Management Explained: A Complete RCM Guide',
    'excerpt'  => 'Master every stage of the revenue cycle ? from patient registration to final payment ? with expert KPIs, automation strategies, and 2025 trend forecasts.',
    'category' => 'RCM GUIDE',
    'date'     => 'Feb 28, 2025',
    'read'     => '12 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-rcm-guide.jpg',
  ],
  [
    'slug'     => 'medical-billing-outsourcing-guide',
    'title'    => 'The Complete Guide to Medical Billing Outsourcing in 2025',
    'excerpt'  => 'Everything you need to know before outsourcing your billing ? cost analysis, vetting checklist, red flags, and transition timelines from in-house to fully outsourced.',
    'category' => 'OUTSOURCING',
    'date'     => 'Feb 14, 2025',
    'read'     => '10 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-outsourcing.jpg',
  ],
  [
    'slug'     => 'behavioral-health-billing-guide',
    'title'    => 'Behavioral Health Billing: Complete Guide to Mental Health Claims',
    'excerpt'  => 'CPT codes, parity laws, telehealth billing rules, and denial prevention strategies for behavioral health and mental health practices.',
    'category' => 'BILLING GUIDE',
    'date'     => 'Jan 30, 2025',
    'read'     => '9 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-behavioral.jpg',
  ],
  [
    'slug'     => 'claim-denial-reasons-and-fixes',
    'title'    => 'Top 10 Claim Denial Reasons & How to Fix Them Fast',
    'excerpt'  => 'Stop losing revenue to preventable denials. Our billing experts break down the most common CO and PR denial codes with proven appeal strategies.',
    'category' => 'DENIALS',
    'date'     => 'Jan 18, 2025',
    'read'     => '8 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-denials.jpg',
  ],
  [
    'slug'     => 'physical-therapy-billing-guide',
    'title'    => 'Physical Therapy Billing: CPT Codes, KX Modifier & Compliance',
    'excerpt'  => 'Your complete reference for PT billing ? therapy cap exceptions, functional limitation reporting, and the most common physical therapy CPT codes for 2025.',
    'category' => 'PT BILLING',
    'date'     => 'Jan 05, 2025',
    'read'     => '10 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-pt-billing.jpg',
  ],
  [
    'slug'     => 'inhouse-vs-outsourced-billing',
    'title'    => 'In-House vs Outsourced Medical Billing: True Cost Comparison',
    'excerpt'  => 'Side-by-side financial analysis of keeping billing internal vs. outsourcing ? salary, software, training, denial rates, and net revenue impact for 2025.',
    'category' => 'COST ANALYSIS',
    'date'     => 'Dec 22, 2024',
    'read'     => '7 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-inhouse-vs-outsourced.jpg',
  ],
  [
    'slug'     => 'cost-of-billing-errors',
    'title'    => 'The Real Cost of Medical Billing Errors for Your Practice',
    'excerpt'  => 'Undercoding, overcoding, missing modifiers ? billing errors cost U.S. healthcare providers $125B annually. Discover how to identify and eliminate errors at your practice.',
    'category' => 'COMPLIANCE',
    'date'     => 'Dec 10, 2024',
    'read'     => '8 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-errors.jpg',
  ],
  [
    'slug'     => 'medical-billing-kpis',
    'title'    => 'Medical Billing KPIs: The 12 Metrics Every Practice Must Track',
    'excerpt'  => 'Clean claim rate, AR days, denial rate, collection rate ? understand exactly which billing KPIs matter, industry benchmarks, and how to improve each one.',
    'category' => 'KPIs & METRICS',
    'date'     => 'Nov 28, 2024',
    'read'     => '9 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-kpis.jpg',
  ],
  [
    'slug'     => 'how-to-choose-medical-billing-company',
    'title'    => 'How to Choose a Medical Billing Company: 15-Point Checklist',
    'excerpt'  => 'Not all billing companies are equal. Use this proven vetting framework ? certifications, technology, transparency, pricing models ? to find the right partner.',
    'category' => 'BUYER\'S GUIDE',
    'date'     => 'Nov 15, 2024',
    'read'     => '8 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-choose-company.jpg',
  ],
  [
    'slug'     => 'provider-credentialing-guide',
    'title'    => 'Provider Credentialing: The Complete 2025 Process Guide',
    'excerpt'  => 'Step-by-step credentialing walkthrough ? CAQH enrollment, payer-specific timelines, common delays, and how to avoid the revenue gap during credentialing.',
    'category' => 'CREDENTIALING',
    'date'     => 'Nov 02, 2024',
    'read'     => '11 min read',
    'image'    => $baseUrl . '/assets/images/content/blog-credentialing.jpg',
  ],
];

// Filter categories for tab UI
$allCategories = array_unique(array_column($posts, 'category'));
?>

<main id="main-content">

  <!-- ?? Hero ?? -->
  <header class="ph-blog-hero">
    <div class="container">
      <nav aria-label="Breadcrumb" class="ph-blog-breadcrumb">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li aria-hidden="true">?</li>
          <li aria-current="page">Blog</li>
        </ol>
      </nav>
      <p class="ph-blog-eyebrow">HEALTHCARE INSIGHTS</p>
      <h1 class="ph-blog-hero-title">Medical Billing<br><span>Blog & Resources</span></h1>
      <p class="ph-blog-hero-sub">Expert knowledge from our AAPC-certified billing specialists ? delivered straight to you.</p>

      <!-- Search -->
      <form class="ph-blog-search" role="search" aria-label="Search blog posts">
        <input type="search" id="ph-blog-search-input" placeholder="Search articles?" aria-label="Search blog articles">
        <button type="submit" aria-label="Search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>
      </form>
    </div>
  </header>

  <!-- ?? Filter Tabs ? -->
  <section class="ph-blog-filter-bar">
    <div class="container">
      <div class="ph-blog-tabs" role="tablist" aria-label="Filter blog posts by category">
        <button class="ph-blog-tab active" data-filter="all" role="tab" aria-selected="true">All Articles</button>
        <?php foreach ($allCategories as $cat): ?>
        <button class="ph-blog-tab" data-filter="<?php echo htmlspecialchars(strtolower(str_replace([' ', "'"], ['-', ''], $cat))); ?>" role="tab" aria-selected="false">
          <?php echo htmlspecialchars($cat); ?>
        </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ?? Blog Grid  -->
  <section class="ph-blog-section" aria-label="Blog articles">
    <div class="container">
      <div class="row g-4" id="ph-blog-grid">

        <?php foreach ($posts as $i => $post):
          $filterSlug = strtolower(str_replace([' ', "'"], ['-', ''], $post['category']));
        ?>
                <div class="col-12 col-md-6 col-lg-4">
<article class="ph-blog-card"
                 data-category="<?php echo htmlspecialchars($filterSlug); ?>"
                 data-aos="fade-up"
                 data-aos-delay="<?php echo ($i % 3) * 80; ?>">

          <!-- Image with category tag overlay -->
          <a href="blog/<?php echo htmlspecialchars($post['slug']); ?>/index.php"
             class="ph-blog-card-img-wrap"
             tabindex="-1"
             aria-hidden="true">
            <img src="<?php echo htmlspecialchars($post['image']); ?>"
                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                 width="800" height="450"
                 loading="<?php echo $i < 3 ? 'eager' : 'lazy'; ?>"
                 decoding="async">
            <span class="ph-blog-cat-tag">#<?php echo htmlspecialchars($post['category']); ?></span>
          </a>

          <!-- Content -->
          <div class="ph-blog-card-body">
            <h2 class="ph-blog-card-title">
              <a href="blog/<?php echo htmlspecialchars($post['slug']); ?>/index.php">
                <?php echo htmlspecialchars($post['title']); ?>
              </a>
            </h2>
            <p class="ph-blog-card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>

            <!-- Footer: animated read more + date -->
            <div class="ph-blog-card-footer">
              <a href="blog/<?php echo htmlspecialchars($post['slug']); ?>/index.php"
                 class="ph-blog-read-more"
                 aria-label="Read more about <?php echo htmlspecialchars($post['title']); ?>">
                <span class="ph-blog-arrow-wrap" aria-hidden="true">
                  <!-- Arrow icon (inline SVG ? no external deps) -->
                  <svg class="ph-arrow ph-arrow-out" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                  <svg class="ph-arrow ph-arrow-in" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </span>
                Read more
              </a>
              <span class="ph-blog-meta">
                <?php echo htmlspecialchars($post['date']); ?>
                <span class="ph-blog-meta-line" aria-hidden="true"></span>
                <small><?php echo htmlspecialchars($post['read']); ?></small>
              </span>
            </div>
          </div>
        </article>
        </div>
        <?php endforeach; ?>

      </div><!-- /grid -->

      <!-- No Results State -->
      <div class="ph-blog-no-results" id="ph-no-results" hidden>
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <h3>No articles found</h3>
        <p>Try a different category or clear the search.</p>
      </div>
    </div>
  </section>

  <!-- ?? CTA Banner ?? -->
  <section class="ph-blog-cta-banner" aria-label="Call to action">
    <div class="container">
      <div class="ph-blog-cta-inner">
        <div class="ph-blog-cta-content">
          <h2>Ready to Stop Leaving Money on the Table?</h2>
          <p>Get a FREE practice revenue audit from our AAPC-certified billing experts ? no commitment, no cost.</p>
        </div>
        <a href="<?php echo $baseUrl; ?>/free-practice-audit/" class="ph-blog-cta-btn" data-cta="blog-cta">
          Get Free Audit
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </section>

</main>

<!-- ?? Blog Page CSS  -->
<style>
/* ?? CRITICAL: Force dark text in all white-background blog sections ?? */
/* Site global CSS defaults to white text for dark theme.
   Blog cards/filter/CTA use white/light backgrounds so we must override. */
.ph-blog-filter-bar,
.ph-blog-section,
.ph-blog-filter-bar *:not(.ph-blog-tab.active),
.ph-blog-section * {
  color: #0c4a6e;
}
/* Specific overrides to get intention right */
.ph-blog-section h1,
.ph-blog-section h2,
.ph-blog-section h3,
.ph-blog-section h4,
.ph-blog-card-title,
.ph-blog-card-title a {
  color: #0c4a6e !important;
}
.ph-blog-section p,
.ph-blog-card-excerpt {
  color: #6B7280 !important;
}
.ph-blog-tab {
  color: #6B7280 !important;
}
.ph-blog-tab.active {
  color: #0ea5e9 !important;
}
.ph-blog-tab:hover {
  color: #0ea5e9 !important;
}
/* Meta text (date/read time) */
.ph-blog-card-meta,
.ph-blog-card-meta * {
  color: #9CA3AF !important;
}
/* Read more link stays dark */
.ph-blog-read-more,
.ph-blog-read-more * {
  color: #0c4a6e !important;
}

/* ?? Variables ——————————— */
.ph-blog-hero,
.ph-blog-section,
.ph-blog-filter-bar,
.ph-blog-cta-banner {
  --ph-primary: #0ea5e9;
  --ph-primary-dark: #0284c7;
  --ph-accent: #38bdf8;
  --ph-dark: #0c4a6e;
  --ph-gray: #6B7280;
  --ph-light: #f8f9fa;
  --ph-border: #e5e7eb;
  --ph-white: #ffffff;
  --ph-radius: 0px; /* flat, like the React design */
  --ph-transition: 0.3s ease;
}

/* ?? Fix blog page width - prevent overflow causing half-screen display */
.ph-blog-hero,
.ph-blog-filter-bar,
.ph-blog-section,
.ph-blog-cta-banner {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
  box-sizing: border-box;
}
main,
#main-content {
  overflow-x: hidden;
  width: 100%;
}


/* ?? Hero ————————————?? */
.ph-blog-hero {
  background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0c4a6e 100%);
  padding: 7rem 0 5rem;
  position: relative;
  overflow: hidden;
}
.ph-blog-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url('assets/images/content/blog-hero-bg.jpg') center/cover no-repeat;
  opacity: 0.07;
}
.ph-blog-hero .container { position: relative; text-align: center; }

.ph-blog-breadcrumb ol {
  display: flex; align-items: center; justify-content: center;
  gap: 0.5rem; list-style: none; margin: 0 0 1.5rem; padding: 0;
  font-size: 0.82rem; opacity: 0.7;
}
.ph-blog-breadcrumb a { color: #fff; text-decoration: none; }
.ph-blog-breadcrumb a:hover { text-decoration: underline; }
.ph-blog-breadcrumb li:nth-child(2) { opacity: 0.5; }
.ph-blog-breadcrumb li:last-child { color: #fff; }

.ph-blog-eyebrow {
  font-size: 0.75rem; font-weight: 600; letter-spacing: 0.18em;
  color: rgba(255,255,255,0.6); text-transform: uppercase; margin-bottom: 1rem;
}
.ph-blog-hero-title {
  font-size: clamp(2.2rem, 5vw, 3.5rem);
  font-weight: 300; color: #fff; line-height: 1.15;
  letter-spacing: -0.02em; margin-bottom: 1.2rem;
}
.ph-blog-hero-title span { font-weight: 700; color: #FFD700; }
.ph-blog-hero-sub {
  font-size: 1.05rem; color: rgba(255,255,255,0.75);
  max-width: 540px; margin: 0 auto 2.2rem; line-height: 1.7;
}

/* Search */
.ph-blog-search {
  display: flex; align-items: center;
  max-width: 460px; margin: 0 auto;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 8px; overflow: hidden;
  backdrop-filter: blur(12px);
}
.ph-blog-search input {
  flex: 1; background: none; border: none; outline: none;
  padding: 0.85rem 1.2rem; color: #fff; font-size: 0.93rem;
}
.ph-blog-search input::placeholder { color: rgba(255,255,255,0.55); }
.ph-blog-search button {
  background: var(--ph-primary); border: none; color: #fff;
  padding: 0 1.1rem; height: 48px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background var(--ph-transition);
}
.ph-blog-search button:hover { background: var(--ph-primary-dark); }

/* ?? Filter Tabs ——————————? */
.ph-blog-filter-bar {
  background: var(--ph-white);
  border-bottom: 1px solid var(--ph-border);
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 2px 16px rgba(0,0,0,0.06);
}
.ph-blog-tabs {
  display: flex; gap: 0; overflow-x: auto;
  scrollbar-width: none; -ms-overflow-style: none;
  padding: 0;
}
.ph-blog-tabs::-webkit-scrollbar { display: none; }
.ph-blog-tab {
  flex-shrink: 0; background: none; border: none; border-bottom: 3px solid transparent;
  padding: 1rem 1.4rem; font-size: 0.82rem; font-weight: 600;
  color: var(--ph-gray); cursor: pointer; letter-spacing: 0.06em;
  text-transform: uppercase; transition: all var(--ph-transition);
  white-space: nowrap;
}
.ph-blog-tab:hover { color: var(--ph-primary); }
.ph-blog-tab.active {
  color: var(--ph-primary);
  border-bottom-color: var(--ph-primary);
}

/* ?? Blog Grid ———————————? */
.ph-blog-section {
  background: var(--ph-white);
  padding: 4rem 0 5rem;
}
/* Grid handled by Bootstrap row/col */

/* ?? Blog Card ———————————? */
.ph-blog-card {
  width: 100%;
  display: block;
  border: 1px solid rgba(209,213,219,0.5);
  background: #fff;
  transition: box-shadow var(--ph-transition), transform var(--ph-transition);
  cursor: pointer;
  overflow: hidden;
  /* FLAT, no border-radius ? matches React design */
}
.ph-blog-card:hover {
  box-shadow: 0 8px 40px rgba(0,82,204,0.12);
  transform: translateY(-4px);
}

/* Image area */
.ph-blog-card-img-wrap {
  display: block; position: relative; overflow: hidden; aspect-ratio: 4/3;
}
.ph-blog-card-img-wrap img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.5s ease;
  display: block;
}
.ph-blog-card:hover .ph-blog-card-img-wrap img { transform: scale(1.04); }

/* Category overlay tag */
.ph-blog-cat-tag {
  position: absolute; top: 0; left: 0;
  background: #fff; color: #000;
  font-size: 0.65rem; font-weight: 600; letter-spacing: 0.1em;
  text-transform: uppercase; padding: 0.3rem 0.7rem;
  line-height: 1.6; z-index: 2;
}

/* Card body */
.ph-blog-card-body { padding: 1.1rem 1.2rem 1.25rem; }

.ph-blog-card-title {
  font-size: 1.05rem; font-weight: 400;
  color: var(--ph-dark); line-height: 1.45;
  margin: 0 0 0.65rem; letter-spacing: -0.01em;
}
.ph-blog-card-title a {
  color: inherit; text-decoration: none;
  transition: color var(--ph-transition);
}
.ph-blog-card-title a:hover { color: var(--ph-primary); }

.ph-blog-card-excerpt {
  font-size: 0.84rem; color: var(--ph-gray);
  line-height: 1.7; margin: 0 0 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Card footer */
.ph-blog-card-footer {
  display: flex; align-items: center;
  justify-content: space-between; gap: 0.75rem;
  flex-wrap: wrap;
}

/* ?? Animated Read More (exact React port) —?? */
.ph-blog-read-more {
  display: inline-flex; align-items: center; gap: 0.5rem;
  font-size: 0.82rem; font-weight: 600;
  color: var(--ph-dark); text-decoration: none;
  transition: color var(--ph-transition);
}
.ph-blog-read-more:hover { color: var(--ph-gray); }

.ph-blog-arrow-wrap {
  position: relative; display: inline-flex;
  align-items: center; justify-content: center;
  width: 34px; height: 34px;
  border: 1px solid var(--ph-border);
  overflow: hidden;
  transition: background var(--ph-transition), border-color var(--ph-transition);
  flex-shrink: 0;
}
.ph-blog-read-more:hover .ph-blog-arrow-wrap {
  background: #000;
  border-color: #000;
}
.ph-blog-read-more:hover .ph-blog-arrow-wrap svg { stroke: #fff; }

/* Two arrows ? one exits right, one enters from left */
.ph-arrow          { transition: transform 0.45s cubic-bezier(0.4,0,0.2,1), opacity 0.45s; }
.ph-arrow-out      { transform: translateX(0);  opacity: 1; }
.ph-arrow-in       { position: absolute; transform: translateX(-130%); opacity: 0; }
.ph-blog-read-more:hover .ph-arrow-out { transform: translateX(130%); opacity: 0; }
.ph-blog-read-more:hover .ph-arrow-in  { transform: translateX(0);    opacity: 1; }

/* Meta date+line */
.ph-blog-meta {
  display: flex; align-items: center; gap: 0.5rem;
  font-size: 0.72rem; color: var(--ph-gray); flex-shrink: 0;
}
.ph-blog-meta-line {
  display: inline-block; width: 2.5rem;
  border-top: 1px solid var(--ph-border);
}
.ph-blog-meta small { font-size: 0.68rem; }

/* Hidden card during filter */
.ph-blog-card.ph-hidden {
  display: none;
}

/* No results */
.ph-blog-no-results {
  text-align: center; padding: 4rem 1rem; color: var(--ph-gray);
}
.ph-blog-no-results svg { margin-bottom: 1.2rem; opacity: 0.4; }
.ph-blog-no-results h3 { font-size: 1.3rem; color: var(--ph-dark); margin-bottom: 0.5rem; }

/* ?? CTA Banner ——————————— */
.ph-blog-cta-banner {
  background: linear-gradient(135deg, var(--ph-primary) 0%, #0c4a6e 100%);
  padding: 4.5rem 0;
}
.ph-blog-cta-inner {
  display: flex; align-items: center; justify-content: space-between;
  gap: 2rem; flex-wrap: wrap;
}
.ph-blog-cta-content h2 {
  color: #fff; font-size: clamp(1.4rem, 3vw, 2rem);
  font-weight: 700; margin: 0 0 0.5rem;
}
.ph-blog-cta-content p  { color: rgba(255,255,255,0.75); margin: 0; font-size: 0.98rem; }
.ph-blog-cta-btn {
  display: inline-flex; align-items: center; gap: 0.65rem;
  background: #38bdf8; color: #fff;
  padding: 0.9rem 2rem; font-weight: 700; font-size: 0.95rem;
  text-decoration: none; border-radius: 6px; flex-shrink: 0;
  transition: background var(--ph-transition), transform var(--ph-transition);
}
.ph-blog-cta-btn:hover { background: #E55A2B; transform: translateY(-2px); color: #fff; }
@media (max-width: 640px) {
  .ph-blog-cta-inner { flex-direction: column; text-align: center; }
}
</style>

<!-- ?? Blog Page JS: filter + search ?? -->
<script>
(function () {
  'use strict';
  var noRes    = document.getElementById('ph-no-results');
  var tabs     = document.querySelectorAll('.ph-blog-tab');
  var searchIn = document.getElementById('ph-blog-search-input');
  // Select the Bootstrap col wrappers (parent of each article)
  var cards    = document.querySelectorAll('#ph-blog-grid > [class*="col"]');
  var activeFilter = 'all';

  function applyFilter() {
    var q   = searchIn ? searchIn.value.trim().toLowerCase() : '';
    var vis = 0;
    cards.forEach(function (col) {
      var card    = col.querySelector('[data-category]');
      if (!card) return;
      var cat     = card.getAttribute('data-category') || '';
      var text    = card.textContent.toLowerCase();
      var catOk   = activeFilter === 'all' || cat === activeFilter;
      var searchOk = !q || text.includes(q);
      if (catOk && searchOk) {
        col.style.display = '';
        vis++;
      } else {
        col.style.display = 'none';
      }
    });
    if (noRes) noRes.hidden = vis > 0;
  }

  /* Tab clicks */
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      tabs.forEach(function (t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      activeFilter = tab.getAttribute('data-filter');
      applyFilter();
    });
  });

  /* Search */
  if (searchIn) {
    var st;
    searchIn.addEventListener('input', function () {
      clearTimeout(st);
      st = setTimeout(applyFilter, 200);
    });
  }

  /* Blog search form submit */
  var form = document.querySelector('.ph-blog-search');
  if (form) form.addEventListener('submit', function (e) { e.preventDefault(); applyFilter(); });
})();
</script>

<!-- ?? Structured Data  -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "https://medinextsolutions.com/blog/#breadcrumb",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://medinextsolutions.com/"},
        {"@type": "ListItem", "position": 2, "name": "Blog", "item": "https://medinextsolutions.com/blog/"}
      ]
    },
    {
      "@type": "CollectionPage",
      "@id": "https://medinextsolutions.com/blog/#webpage",
      "url": "https://medinextsolutions.com/blog/",
      "name": "Medical Billing Blog & Healthcare RCM Insights | MEDINEXT SOLUTIONS",
      "description": "Expert insights on medical coding updates, denial management strategies, and revenue cycle optimization for healthcare providers."
    }
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
