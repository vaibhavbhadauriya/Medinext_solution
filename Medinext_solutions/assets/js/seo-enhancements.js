/* MEDINEXT SOLUTIONS  SEO Enhancement Scripts v1.0
 * File: assets/js/seo-enhancements.js
 * Purpose: Additive JS only  loaded with defer, after existing scripts
 * NO dependencies. Vanilla JS. IIFE scoped.
 */
(function () {
    'use strict';

    /*  Shared Utilities  */
    var win = window;
    var doc = document;
    var dL = win.dataLayer = win.dataLayer || [];

    function qs(sel, ctx) { return (ctx || doc).querySelector(sel); }
    function qsa(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

    function debounce(fn, ms) {
        var t;
        return function () {
            clearTimeout(t);
            t = setTimeout(fn, ms);
        };
    }

    function setCookie(name, value, days) {
        var d = new Date();
        d.setTime(d.getTime() + days * 864e5);
        doc.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
    }

    function getCookie(name) {
        return doc.cookie.split('; ').find(function (r) { return r.startsWith(name + '='); });
    }

    function push(obj) {
        try { dL.push(obj); } catch (e) { }
    }

    /*  Feature 1: Mobile CTA Bar Controller  */
    try {
        var ctaBar = qs('.ph-mobile-cta');
        var lastScroll = 0;
        var ticking = false;

        if (ctaBar) {
            /* Hide when footer is visible */
            var footer = qs('footer');
            if (footer && win.IntersectionObserver) {
                new IntersectionObserver(function (entries) {
                    ctaBar.classList.toggle('ph-cta-hidden', entries[0].isIntersecting);
                }, { threshold: 0.05 }).observe(footer);
            }

            /* Hide on scroll-down, show on scroll-up */
            win.addEventListener('scroll', function () {
                if (!ticking) {
                    win.requestAnimationFrame(function () {
                        var cur = win.pageYOffset;
                        if (cur > lastScroll + 10) {
                            ctaBar.classList.add('ph-cta-hidden');
                        } else if (cur < lastScroll - 10) {
                            ctaBar.classList.remove('ph-cta-hidden');
                        }
                        lastScroll = cur;
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        }
    } catch (e) { }

    /*  Feature 2: Back to Top Button  */
    try {
        var btt = qs('.ph-back-to-top');
        if (btt) {
            var bttScroll = debounce(function () {
                btt.classList.toggle('ph-btt-visible', win.pageYOffset > 500);
            }, 100);

            win.addEventListener('scroll', bttScroll, { passive: true });

            btt.addEventListener('click', function () {
                var prefersReduced = win.matchMedia('(prefers-reduced-motion: reduce)').matches;
                win.scrollTo({ top: 0, behavior: prefersReduced ? 'auto' : 'smooth' });
            });
        }
    } catch (e) { }

    /*  Feature 3: Cookie Consent Banner  */
    try {
        var cookieBanner = qs('.ph-cookie-banner');
        var COOKIE_KEY   = 'ph_cookie_consent';
        var COOKIE_OLD   = 'ph_consent'; /* legacy key from old inline injection */

        /* Show banner only if NEITHER cookie exists */
        var alreadyConsented = getCookie(COOKIE_KEY) || getCookie(COOKIE_OLD);
        if (cookieBanner && !alreadyConsented) {
            cookieBanner.classList.add('ph-cookie-visible');
        }

        function grantConsent(type) {
            setCookie(COOKIE_KEY, type, 365);
            if (cookieBanner) cookieBanner.classList.remove('ph-cookie-visible');
            push({ event: 'cookie_consent', consent_type: type });

            /* Consent Mode v2 */
            if (typeof win.gtag === 'function') {
                var analytics = type === 'all' ? 'granted' : 'denied';
                var ads = type === 'all' ? 'granted' : 'denied';
                win.gtag('consent', 'update', {
                    analytics_storage: analytics,
                    ad_storage: ads,
                    functionality_storage: 'granted',
                    security_storage: 'granted'
                });
            }
        }

        var btnAccept = qs('.ph-cookie-btn-accept');
        var btnEssential = qs('.ph-cookie-btn-essential');
        if (btnAccept) btnAccept.addEventListener('click', function () { grantConsent('all'); });
        if (btnEssential) btnEssential.addEventListener('click', function () { grantConsent('essential'); });
    } catch (e) { }

    /*  Feature 4: FAQ Accordion Enhancement  */
    try {
        var faqContainers = qsa('.seo-faq, .ph-faq-section');
        faqContainers.forEach(function (container) {
            var items = qsa('details', container);
            items.forEach(function (item) {
                var sum = qs('summary', item);
                if (!sum) return;

                item.addEventListener('toggle', function () {
                    if (item.open) {
                        /* Accordion: close others */
                        items.forEach(function (other) {
                            if (other !== item && other.open) other.removeAttribute('open');
                        });
                        /* Track FAQ open */
                        push({ event: 'faq_open', question: sum.textContent.trim().substring(0, 100) });
                    }
                });
            });
        });
    } catch (e) { }

    /*  Feature 5: Announcement Bar Close  */
    try {
        var announceBar = qs('.ph-announce-bar');
        var announceClose = qs('.ph-announce-close');
        var ANN_KEY = 'ph_ann_closed';

        if (announceBar) {
            if (sessionStorage.getItem(ANN_KEY)) {
                announceBar.style.display = 'none';
            }
            if (announceClose) {
                announceClose.addEventListener('click', function () {
                    announceBar.classList.add('ph-announce-closed');
                    setTimeout(function () { announceBar.style.display = 'none'; }, 420);
                    sessionStorage.setItem(ANN_KEY, '1');
                });
            }
        }
    } catch (e) { }

    /*  Feature 6: Reading Progress Bar  */
    try {
        var progressBar = qs('.ph-reading-progress');
        if (progressBar) {
            var article = qs('article, main, .post-content') || doc.body;
            var rafProgress = false;

            win.addEventListener('scroll', function () {
                if (!rafProgress) {
                    win.requestAnimationFrame(function () {
                        var rect = article.getBoundingClientRect();
                        var total = article.offsetHeight - win.innerHeight;
                        var scrolled = Math.max(0, -rect.top);
                        var pct = Math.min(100, Math.round((scrolled / total) * 100));
                        progressBar.style.width = pct + '%';
                        rafProgress = false;
                    });
                    rafProgress = true;
                }
            }, { passive: true });
        }
    } catch (e) { }

    /*  Feature 7: Tracking Events (Event Delegation)  */
    try {
        var milestones = { 25: false, 50: false, 75: false, 100: false };

        /* Scroll depth */
        win.addEventListener('scroll', function () {
            var scrolled = (win.pageYOffset + win.innerHeight) / doc.documentElement.scrollHeight * 100;
            Object.keys(milestones).forEach(function (pct) {
                if (!milestones[pct] && scrolled >= parseInt(pct)) {
                    milestones[pct] = true;
                    push({ event: 'scroll_depth', scroll_percentage: parseInt(pct), page_url: win.location.href });
                }
            });
        }, { passive: true });

        /* Click delegation */
        doc.body.addEventListener('click', function (e) {
            var el = e.target.closest('a, button, [data-cta]');
            if (!el) return;

            var href = el.getAttribute('href') || '';

            /* tel: */
            if (href.indexOf('tel:') === 0) {
                push({ event: 'phone_click', phone_number: href.replace('tel:', ''), page_url: win.location.href });
                return;
            }
            /* mailto: */
            if (href.indexOf('mailto:') === 0) {
                push({ event: 'email_click', email: href.replace('mailto:', ''), page_url: win.location.href });
                return;
            }
            /* CTA [data-cta] */
            if (el.hasAttribute('data-cta')) {
                push({ event: 'cta_click', cta_text: el.textContent.trim().substring(0, 60), cta_url: href, page_url: win.location.href });
                return;
            }
            /* External links */
            if (href && el.hostname && el.hostname !== win.location.hostname) {
                push({ event: 'outbound_click', destination_url: href, page_url: win.location.href });
            }
        });
    } catch (e) { }

    /*  Feature 8: Dynamic Copyright Year  */
    try {
        var year = new Date().getFullYear();
        qsa('#copyright-year, .copyright-year').forEach(function (el) {
            el.textContent = year;
        });
    } catch (e) { }

    /*  Feature 9: Lazy Load (data-src images)  */
    try {
        var lazyImgs = qsa('img[data-src]');
        if (lazyImgs.length && win.IntersectionObserver) {
            var lazyObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        img.src = img.getAttribute('data-src');
                        var srcset = img.getAttribute('data-srcset');
                        if (srcset) img.srcset = srcset;
                        img.removeAttribute('data-src');
                        img.classList.add('ph-img-loaded');
                        lazyObserver.unobserve(img);
                    }
                });
            }, { rootMargin: '200px' });

            lazyImgs.forEach(function (img) { lazyObserver.observe(img); });
        }
    } catch (e) { }

    /*  Feature 10: Smooth Scroll for Anchor Links  */
    try {
        var prefersReduced = win.matchMedia('(prefers-reduced-motion: reduce)').matches;

        doc.body.addEventListener('click', function (e) {
            var link = e.target.closest('a[href^="#"]');
            if (!link) return;

            var hash = link.getAttribute('href');
            if (hash === '#') return;
            var target = doc.querySelector(hash);
            if (!target) return;

            e.preventDefault();

            /* Calculate offset for sticky header (~80px) */
            var header = qs('.navbar, header, nav');
            var navHeight = header ? header.offsetHeight : 80;
            var top = target.getBoundingClientRect().top + win.pageYOffset - navHeight;

            win.scrollTo({ top: top, behavior: prefersReduced ? 'auto' : 'smooth' });

            /* Update URL without jump */
            if (win.history && win.history.pushState) {
                win.history.pushState(null, '', hash);
            }
        });
    } catch (e) { }

})();
