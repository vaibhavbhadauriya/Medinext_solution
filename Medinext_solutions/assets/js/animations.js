/**
 * MEDINEXT SOLUTIONS - Animation Controller
 * GSAP + ScrollTrigger + AOS initialization
 * Professional, subtle animations only
 */

'use strict';

/* ==========================================================
   1. AOS INITIALIZATION
   ========================================================== */
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 80,
        delay: 0,
        disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
    });
});


/* ==========================================================
   2. GSAP & SCROLLTRIGGER SETUP
   ========================================================== */
const PrismaAnimations = {

    /**
     * Initialize all GSAP animations
     */
    init() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // Set GSAP defaults for professional feel
        gsap.defaults({
            ease: 'power3.out',
            duration: 0.8
        });

        this.initHeroAnimation();
        this.initSectionReveals();
        this.initStatsAnimation();
        this.initParallaxEffects();
        this.initNavbarScroll();
    },

    /**
     * Hero section entrance timeline
     */
    initHeroAnimation() {
        const heroElements = {
            badge: '.hero-animate-badge',
            title: '.hero-animate-title',
            subtitle: '.hero-animate-subtitle',
            buttons: '.hero-animate-buttons',
            stats: '.hero-animate-stats',
            visual: '.hero-animate-visual'
        };

        // Check if hero elements exist
        if (!document.querySelector(heroElements.badge)) return;

        const tl = gsap.timeline({
            delay: 1.8 // After page loader
        });

        tl.to(heroElements.badge, {
            opacity: 1,
            y: 0,
            duration: 0.6
        })
            .to(heroElements.title, {
                opacity: 1,
                y: 0,
                duration: 0.8
            }, '-=0.3')
            .to(heroElements.subtitle, {
                opacity: 1,
                y: 0,
                duration: 0.6
            }, '-=0.4')
            .to(heroElements.buttons, {
                opacity: 1,
                y: 0,
                duration: 0.6
            }, '-=0.3')
            .to(heroElements.stats, {
                opacity: 1,
                y: 0,
                duration: 0.8
            }, '-=0.2')
            .to(heroElements.visual, {
                opacity: 1,
                x: 0,
                duration: 1
            }, '-=0.6');
    },

    /**
     * General section reveal animations on scroll
     */
    initSectionReveals() {
        // Fade up reveals
        gsap.utils.toArray('.gsap-reveal').forEach(element => {
            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 1,
                y: 0,
                duration: 0.8
            });
        });

        // Fade from left
        gsap.utils.toArray('.gsap-reveal-left').forEach(element => {
            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 1,
                x: 0,
                duration: 0.8
            });
        });

        // Fade from right
        gsap.utils.toArray('.gsap-reveal-right').forEach(element => {
            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 1,
                x: 0,
                duration: 0.8
            });
        });

        // Scale in
        gsap.utils.toArray('.gsap-scale-in').forEach(element => {
            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 1,
                scale: 1,
                duration: 0.8
            });
        });

        // Service cards stagger
        const serviceCards = gsap.utils.toArray('.service-card');
        if (serviceCards.length > 0) {
            ScrollTrigger.batch(serviceCards, {
                start: 'top 85%',
                onEnter: (batch) => {
                    gsap.to(batch, {
                        opacity: 1,
                        y: 0,
                        duration: 0.6,
                        stagger: 0.1,
                        ease: 'power3.out'
                    });
                },
                once: true
            });

            // Set initial state
            gsap.set(serviceCards, { opacity: 0, y: 30 });
        }
    },

    /**
     * Stats counter animation trigger
     */
    initStatsAnimation() {
        const statsSection = document.querySelector('.stats-section');
        if (!statsSection) return;

        ScrollTrigger.create({
            trigger: statsSection,
            start: 'top 75%',
            once: true,
            onEnter: () => {
                // Trigger counter animation (handled in main.js)
                document.dispatchEvent(new CustomEvent('startCounters'));

                // Animate counter items
                gsap.utils.toArray('.counter-item').forEach((item, index) => {
                    gsap.to(item, {
                        opacity: 1,
                        y: 0,
                        duration: 0.6,
                        delay: index * 0.15,
                        ease: 'power3.out'
                    });
                });
            }
        });

        // Set initial state
        gsap.set('.counter-item', { opacity: 0, y: 20 });
    },

    /**
     * Subtle parallax on scroll
     */
    initParallaxEffects() {
        // Only on desktop
        if (window.innerWidth < 992) return;

        gsap.utils.toArray('.parallax-element').forEach(element => {
            const speed = element.dataset.speed || 0.1;

            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 1
                },
                y: () => (parseFloat(speed) * -100),
                ease: 'none'
            });
        });

        // Mesh gradient orbs parallax
        gsap.utils.toArray('.mesh-orb').forEach((orb, index) => {
            gsap.to(orb, {
                scrollTrigger: {
                    trigger: '.hero-section',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 1
                },
                y: (index + 1) * -60,
                ease: 'none'
            });
        });
    },

    /**
     * Navbar scroll behavior
     */
    initNavbarScroll() {
        const nav = document.getElementById('mainNav');
        if (!nav) return;

        ScrollTrigger.create({
            start: 'top -80',
            onUpdate: (self) => {
                if (self.scroll() > 80) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            }
        });
    },

    /**
     * Refresh ScrollTrigger (call after dynamic content)
     */
    refresh() {
        ScrollTrigger.refresh();
    }
};


/* ==========================================================
   3. INITIALIZE ON DOM READY
   ========================================================== */
document.addEventListener('DOMContentLoaded', () => {
    // Wait for page loader to finish
    setTimeout(() => {
        PrismaAnimations.init();
    }, 200);
});


/* ==========================================================
   4. REFRESH ON WINDOW RESIZE
   ========================================================== */
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
        }
    }, 250);
});


window.PrismaAnimations = PrismaAnimations;

/* ==========================================================
   6. MEGA MENU INTERACTIONS
   ========================================================== */
document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.mega-dropdown');

    dropdowns.forEach(dropdown => {
        let timeoutId;
        const menu = dropdown.querySelector('.mega-menu');
        const toggle = dropdown.querySelector('.dropdown-toggle');

        // Show menu immediately on mouseenter
        dropdown.addEventListener('mouseenter', () => {
            clearTimeout(timeoutId);
            menu.style.opacity = '1';
            menu.style.visibility = 'visible';
            menu.style.transform = 'translateX(-50%) translateY(0)';
            menu.style.pointerEvents = 'auto';
            if (toggle) toggle.classList.add('show');
        });

        // Delay hiding menu on mouseleave to allow diagonal movement
        dropdown.addEventListener('mouseleave', () => {
            timeoutId = setTimeout(() => {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateX(-50%) translateY(10px)';
                menu.style.pointerEvents = 'none';
                if (toggle) toggle.classList.remove('show');
            }, 250); // Increased from 150ms to 250ms for more forgiving diagonal movement
        });

        // Handle keyboard focus accessibility
        if (toggle) {
            toggle.addEventListener('focus', () => {
                dropdown.dispatchEvent(new Event('mouseenter'));
            });
            toggle.addEventListener('blur', () => {
                dropdown.dispatchEvent(new Event('mouseleave'));
            });
        }
    });
});