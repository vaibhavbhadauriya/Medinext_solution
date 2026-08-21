/**
 * MEDINEXT SOLUTIONS - Main Application Controller
 * Vanilla JavaScript ES6+  No jQuery
 * Handles all interactive functionality
 */

'use strict';

/* ==========================================================
   1. PAGE LOADER
   ========================================================== */
const PageLoader = {
    element: document.getElementById('page-loader'),

    hide() {
        if (!this.element) return;
        this.element.classList.add('loaded');
        document.body.style.overflow = '';
        setTimeout(() => {
            this.element.remove();
        }, 400);
    },

    init() {
        document.body.style.overflow = 'hidden';
        window.addEventListener('load', () => this.hide());
        // Fallback: force hide after 1.5s
        setTimeout(() => this.hide(), 1500);
    }
};


/* ==========================================================
   2. SCROLL PROGRESS BAR
   ========================================================== */
const ScrollProgress = {
    bar: document.getElementById('scroll-progress'),

    update() {
        if (!this.bar) return;
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        this.bar.style.width = `${progress}%`;
    },

    init() {
        // Scroll handled by unified ScrollManager below
    }
};


/* ==========================================================
   3. NAVBAR CONTROLLER
   ========================================================== */
const Navbar = {
    nav: document.getElementById('mainNav'),
    toggler: document.getElementById('navToggler'),
    drawer: document.getElementById('mobileDrawer'),
    overlay: document.getElementById('mobileOverlay'),
    closeBtn: document.getElementById('drawerClose'),
    lastScroll: 0,

    handleScroll() {
        if (!this.nav) return;
        const currentScroll = window.scrollY;

        // Hide on scroll down, show on scroll up
        if (currentScroll > this.lastScroll && currentScroll > 80) {
            this.nav.style.transform = 'translateY(-100%)';
        } else {
            this.nav.style.transform = 'translateY(0)';
        }

        if (currentScroll > 80) {
            this.nav.classList.add('scrolled');
        } else {
            this.nav.classList.remove('scrolled');
        }

        this.lastScroll = currentScroll <= 0 ? 0 : currentScroll;
    },

    openDrawer() {
        if (!this.drawer || !this.overlay) return;
        this.drawer.classList.add('active');
        this.overlay.classList.add('active');
        if (this.toggler) this.toggler.classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    closeDrawer() {
        if (!this.drawer || !this.overlay) return;
        this.drawer.classList.remove('active');
        this.overlay.classList.remove('active');
        if (this.toggler) this.toggler.classList.remove('active');
        document.body.style.overflow = '';
    },

    init() {
        // Scroll handled by unified ScrollManager below

        if (this.toggler) {
            this.toggler.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.drawer && this.drawer.classList.contains('active')) {
                    this.closeDrawer();
                } else {
                    this.openDrawer();
                }
            });
        }

        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.closeDrawer());
        }

        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.closeDrawer());
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeDrawer();
        });

        // Close drawer on link click
        const drawerLinks = document.querySelectorAll('.drawer-nav a');
        drawerLinks.forEach(link => {
            link.addEventListener('click', () => this.closeDrawer());
        });
    }
};


/* ==========================================================
   4. BACK TO TOP BUTTON
   ========================================================== */
const BackToTop = {
    button: document.getElementById('backToTop'),

    handleScroll() {
        if (!this.button) return;
        if (window.scrollY > 400) {
            this.button.classList.add('visible');
        } else {
            this.button.classList.remove('visible');
        }
    },

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    },

    init() {
        // Scroll handled by unified ScrollManager below
        if (this.button) {
            this.button.addEventListener('click', () => this.scrollToTop());
        }
    }
};


/* ==========================================================
   4b. UNIFIED SCROLL MANAGER (single throttled listener)
   ========================================================== */
const ScrollManager = {
    ticking: false,

    onScroll() {
        if (this.ticking) return;
        this.ticking = true;
        requestAnimationFrame(() => {
            ScrollProgress.update();
            Navbar.handleScroll();
            BackToTop.handleScroll();
            this.ticking = false;
        });
    },

    init() {
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    }
};


/* ==========================================================
   5. PARTICLES.JS INITIALIZATION
   ========================================================== */
const ParticlesInit = {
    init() {
        const container = document.getElementById('hero-particles');
        if (!container || typeof particlesJS === 'undefined') return;

        particlesJS('hero-particles', {
            particles: {
                number: {
                    value: 50,
                    density: { enable: true, value_area: 1000 }
                },
                color: { value: '#0056D2' },
                shape: { type: 'circle' },
                opacity: {
                    value: 0.15,
                    random: true,
                    anim: { enable: true, speed: 0.5, opacity_min: 0.05, sync: false }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: { enable: true, speed: 1, size_min: 0.5, sync: false }
                },
                line_linked: {
                    enable: true,
                    distance: 180,
                    color: '#0056D2',
                    opacity: 0.08,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 0.8,
                    direction: 'none',
                    random: true,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'grab' },
                    onclick: { enable: false },
                    resize: true
                },
                modes: {
                    grab: { distance: 140, line_linked: { opacity: 0.15 } }
                }
            },
            retina_detect: true
        });
    }
};


/* ==========================================================
   6. TYPED.JS INITIALIZATION
   ========================================================== */
const TypedInit = {
    init() {
        // Hero Section Auto-Typing Headline
        const heroElement = document.getElementById('hero-typewriter-target');
        if (heroElement) {
            const fullHTML = 'We <span class="text-blue-highlight">bill</span>, you <span class="text-blue-highlight">heal</span><br><span class="hero-title-subline">that’s the deal</span>';
            
            if (typeof Typed !== 'undefined') {
                new Typed('#hero-typewriter-target', {
                    strings: [fullHTML],
                    typeSpeed: 35,
                    startDelay: 200,
                    showCursor: true,
                    cursorChar: '|',
                    autoInsertCss: false,
                    contentType: 'html',
                    onComplete: function(self) {
                        setTimeout(function() {
                            const cursor = document.querySelector('.hero-v2-title .typed-cursor');
                            if (cursor) cursor.style.display = 'none';
                        }, 2500);
                        document.querySelectorAll('.hero-fade-in-element').forEach(function(el) {
                            el.classList.add('is-visible');
                        });
                    }
                });
            } else {
                // Fallback if Typed.js isn't yet ready
                heroElement.innerHTML = fullHTML;
                document.querySelectorAll('.hero-fade-in-element').forEach(function(el) {
                    el.classList.add('is-visible');
                });
            }
        }

        const element = document.getElementById('typed-output');
        if (element && typeof Typed !== 'undefined') {
            new Typed('#typed-output', {
                strings: [
                    'Medical Billing Experts',
                    'Revenue Cycle Management',
                    'Claim Accuracy Specialists'
                ],
                typeSpeed: 50,
                backSpeed: 30,
                backDelay: 2000,
                startDelay: 2500,
                loop: true,
                showCursor: true,
                cursorChar: '|',
                autoInsertCss: true
            });
        }
    }
};


/* ==========================================================
   7. COUNTUP ANIMATION (Increasing Numbers)
   ========================================================== */
const CounterInit = {
    animateSingle(el) {
        const target = parseFloat(el.dataset.countup);
        if (isNaN(target)) return;
        const suffix = el.dataset.suffix || '';
        const prefix = el.dataset.prefix || '';
        const decimals = el.dataset.decimals ? parseInt(el.dataset.decimals) : 0;
        const duration = 1800; // ms

        const startTime = performance.now();
        const update = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Smooth ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = target * eased;
            el.textContent = prefix + (decimals > 0 ? current.toFixed(decimals) : Math.round(current)) + suffix;
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = prefix + (decimals > 0 ? target.toFixed(decimals) : target) + suffix;
            }
        };
        requestAnimationFrame(update);
    },

    animateContainer(containerOrId) {
        const container = typeof containerOrId === 'string' ? document.getElementById(containerOrId) : containerOrId;
        if (!container) return;
        const counters = container.querySelectorAll('[data-countup]');
        counters.forEach((el, index) => {
            setTimeout(() => {
                this.animateSingle(el);
            }, index * 80);
        });
    },

    start() {
        document.querySelectorAll('[data-countup]').forEach(el => this.animateSingle(el));
    },

    init() {
        document.addEventListener('startCounters', () => this.start());

        // Global IntersectionObserver for all countup elements
        const countupElements = document.querySelectorAll('[data-countup]');
        if ('IntersectionObserver' in window && countupElements.length) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateSingle(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });

            countupElements.forEach(el => observer.observe(el));
        } else {
            this.start();
        }
    }
};

window.CounterInit = CounterInit;
window.animateEhrCounters = function(containerId) {
    if (window.CounterInit) {
        window.CounterInit.animateContainer(containerId);
    }
};


/* ==========================================================
   8. SWIPER.JS INITIALIZATION
   ========================================================== */
const SwiperInit = {
    init() {
        const swiperContainer = document.querySelector('.swiper-testimonials');
        if (!swiperContainer || typeof Swiper === 'undefined') return;

        new Swiper('.swiper-testimonials', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            speed: 600,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                576: {
                    slidesPerView: 1,
                    spaceBetween: 24
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 32
                }
            }
        });
    }
};


/* ==========================================================
   9. VANILLA TILT INITIALIZATION
   ========================================================== */
const TiltInit = {
    init() {
        if (typeof VanillaTilt === 'undefined') return;
        if (window.innerWidth < 992) return; // Disable on mobile

        const cards = document.querySelectorAll('[data-tilt]');
        cards.forEach(card => {
            VanillaTilt.init(card, {
                max: 5,
                speed: 400,
                glare: true,
                'max-glare': 0.08,
                scale: 1.02,
                perspective: 1200
            });
        });
    }
};


/* ==========================================================
   10. SMOOTH SCROLL FOR ANCHOR LINKS
   ========================================================== */
const SmoothScroll = {
    init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href');
                if (targetId === '#' || targetId === '') return;

                const target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();
                const offsetTop = target.getBoundingClientRect().top + window.scrollY - 80;

                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            });
        });
    }
};


/* ==========================================================
   11. CONTACT FORM HANDLER
   ========================================================== */
const ContactForm = {
    form: null,
    submitBtn: null,
    successOverlay: null,

    parseServerJson(responseText) {
        try {
            return JSON.parse(responseText);
        } catch (parseError) {
            const jsonStart = responseText.lastIndexOf('{"success"');
            if (jsonStart !== -1) {
                return JSON.parse(responseText.slice(jsonStart));
            }
            throw parseError;
        }
    },

    submitWithXHR(formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'contact.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(xhr.responseText || '');
                    return;
                }
                reject(new Error(`XHR Error: ${xhr.status} ${xhr.statusText}`));
            };

            xhr.onerror = () => reject(new Error('XHR network error'));
            xhr.ontimeout = () => reject(new Error('XHR timeout'));
            xhr.timeout = 20000;
            xhr.send(formData);
        });
    },

    validate(formData) {
        const errors = {};

        if (!formData.get('full_name') || formData.get('full_name').trim().length < 2) {
            errors.full_name = 'Please enter your full name (at least 2 characters).';
        }

        const email = formData.get('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            errors.email = 'Please enter a valid email address.';
        }

        const phone = formData.get('phone');
        if (phone && phone.trim().length > 0) {
            const phoneClean = phone.replace(/[^0-9]/g, '');
            if (phoneClean.length < 10) {
                errors.phone = 'Please enter a valid phone number.';
            }
        }

        if (!formData.get('message') || formData.get('message').trim().length < 10) {
            errors.message = 'Please enter a message (at least 10 characters).';
        }

        return errors;
    },

    showErrors(errors) {
        // Clear previous errors
        this.form.querySelectorAll('.form-control, .form-select').forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
        });
        this.form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });

        // Show new errors
        Object.entries(errors).forEach(([field, message]) => {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback') ||
                                 input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = message;
                    feedback.style.display = 'block';
                }
            }
        });
    },

    markValid(fieldName) {
        const input = this.form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    },

    setLoading(loading) {
        if (!this.submitBtn) return;
        if (loading) {
            this.submitBtn.disabled = true;
            this.submitBtn.dataset.originalText = this.submitBtn.innerHTML;
            this.submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Sending...
            `;
        } else {
            this.submitBtn.disabled = false;
            this.submitBtn.innerHTML = this.submitBtn.dataset.originalText || 'Send Message';
        }
    },

    async submit(e) {
        e.preventDefault();

        const formData = new FormData(this.form);
        const errors = this.validate(formData);

        if (Object.keys(errors).length > 0) {
            this.showErrors(errors);
            // Focus first error field
            const firstError = Object.keys(errors)[0];
            const firstField = this.form.querySelector(`[name="${firstError}"]`);
            if (firstField) firstField.focus();
            return;
        }

        this.setLoading(true);

        try {
            const response = await fetch('contact.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Check if response is OK before parsing JSON
            if (!response.ok) {
                alert(`Error: ${response.status} ${response.statusText}. Please try again.`);
                this.setLoading(false);
                return;
            }

            // Get response text first to debug issues
            const responseText = await response.text();

            let result;
            try {
                result = this.parseServerJson(responseText);
            } catch (parseError) {
                alert('Invalid response from server. Please check browser console.');
                this.setLoading(false);
                return;
            }

            if (result.success) {
                // Show success overlay
                if (this.successOverlay) {
                    this.form.style.display = 'none';
                    this.successOverlay.classList.add('active');
                }
                this.form.reset();
            } else {
                alert(result.message || 'An error occurred. Please try again.');
            }
        } catch (error) {

            try {
                const fallbackText = await this.submitWithXHR(formData);
                const fallbackResult = this.parseServerJson(fallbackText);

                if (fallbackResult.success) {
                    if (this.successOverlay) {
                        this.form.style.display = 'none';
                        this.successOverlay.classList.add('active');
                    }
                    this.form.reset();
                    return;
                }

                alert(fallbackResult.message || 'An error occurred. Please try again.');
            } catch (fallbackError) {
                alert('A network error occurred. Please check your connection and try again.');
            }
        } finally {
            this.setLoading(false);
        }
    },

    initRealTimeValidation() {
        if (!this.form) return;

        const fields = this.form.querySelectorAll('.form-control, .form-select');
        fields.forEach(field => {
            field.addEventListener('blur', () => {
                const name = field.getAttribute('name');
                const value = field.value;

                field.classList.remove('is-invalid', 'is-valid');
                const feedback = field.parentElement.querySelector('.invalid-feedback') ||
                                 field.nextElementSibling;

                if (name === 'full_name' && value.trim().length >= 2) {
                    field.classList.add('is-valid');
                } else if (name === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(value)) {
                        field.classList.add('is-valid');
                    } else if (value.length > 0) {
                        field.classList.add('is-invalid');
                        if (feedback) {
                            feedback.textContent = 'Please enter a valid email address.';
                            feedback.style.display = 'block';
                        }
                    }
                } else if (name === 'phone' && value.trim().length > 0) {
                    const phoneClean = value.replace(/[^0-9]/g, '');
                    if (phoneClean.length >= 10) {
                        field.classList.add('is-valid');
                    } else {
                        field.classList.add('is-invalid');
                        if (feedback) {
                            feedback.textContent = 'Please enter a valid phone number.';
                            feedback.style.display = 'block';
                        }
                    }
                } else if (name === 'message' && value.trim().length >= 10) {
                    field.classList.add('is-valid');
                }
            });
        });
    },

    init() {
        this.form = document.getElementById('contactForm');
        if (!this.form) return;

        this.submitBtn = this.form.querySelector('button[type="submit"]');
        this.successOverlay = document.getElementById('formSuccessOverlay');

        this.form.addEventListener('submit', (e) => this.submit(e));
        this.initRealTimeValidation();
    }
};


/* ==========================================================
   12. NEWSLETTER FORM HANDLER
   ========================================================== */
const NewsletterForm = {
    form: null,

    async submit(e) {
        e.preventDefault();

        const emailInput = this.form.querySelector('input[name="newsletter_email"]');
        const feedback = this.form.querySelector('.newsletter-feedback');
        const email = emailInput ? emailInput.value.trim() : '';

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (feedback) {
                feedback.textContent = 'Please enter a valid email address.';
                feedback.className = 'newsletter-feedback error';
            }
            return;
        }

        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        }

        try {
            const formData = new FormData(this.form);
            formData.append('action', 'newsletter');

            const response = await fetch('contact.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                if (feedback) {
                    feedback.textContent = `Error: ${response.status}. Please try again.`;
                    feedback.className = 'newsletter-feedback error';
                }
                return;
            }

            const responseText = await response.text();

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                const jsonStart = responseText.lastIndexOf('{"success"');
                if (jsonStart !== -1) {
                    try {
                        result = JSON.parse(responseText.slice(jsonStart));
                    } catch (fallbackError) {
                        if (feedback) {
                            feedback.textContent = 'Server error. Please try again.';
                            feedback.className = 'newsletter-feedback error';
                        }
                        return;
                    }
                } else {
                    if (feedback) {
                        feedback.textContent = 'Server error. Please try again.';
                        feedback.className = 'newsletter-feedback error';
                    }
                    return;
                }
            }

            if (feedback) {
                feedback.textContent = result.message;
                feedback.className = `newsletter-feedback ${result.success ? 'success' : 'error'}`;
            }

            if (result.success && emailInput) {
                emailInput.value = '';
            }
        } catch (error) {
            if (feedback) {
                feedback.textContent = 'An error occurred. Please try again.';
                feedback.className = 'newsletter-feedback error';
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ph-paper-plane-tilt"></i>';
            }
        }
    },

    init() {
        this.form = document.getElementById('newsletterForm');
        if (!this.form) return;
        this.form.addEventListener('submit', (e) => this.submit(e));
    }
};


/* ==========================================================
   13. BUTTON RIPPLE EFFECT
   ========================================================== */
const RippleEffect = {
    init() {
        document.querySelectorAll('.btn').forEach(button => {
            button.classList.add('btn-ripple');
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
                ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    }
};


/* ==========================================================
   14. DASHBOARD MOCKUP ANIMATION
   ========================================================== */
const MockupAnimation = {
    init() {
        const progressBars = document.querySelectorAll('.mockup-progress-fill');
        if (progressBars.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        progressBars.forEach(bar => {
                            const width = bar.dataset.width || '75%';
                            bar.style.width = width;
                        });
                    }, 1000);
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        const mockup = document.querySelector('.dashboard-mockup');
        if (mockup) observer.observe(mockup);
    }
};


/* ==========================================================
   15. FAQ ACCORDION (Services page)
   ========================================================== */
const FaqAccordion = {
    init() {
        const accordionButtons = document.querySelectorAll('.accordion-button');
        if (accordionButtons.length === 0) return;

        // Bootstrap handles accordion  just add smooth transition
        accordionButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Smooth scroll to accordion item if needed
                setTimeout(() => {
                    const item = btn.closest('.accordion-item');
                    if (item) {
                        const top = item.getBoundingClientRect().top + window.scrollY - 100;
                        if (item.getBoundingClientRect().top < 0) {
                            window.scrollTo({ top, behavior: 'smooth' });
                        }
                    }
                }, 400);
            });
        });
    }
};


/* ==========================================================
   16. PHONE INPUT FORMATTING
   ========================================================== */
const PhoneFormatter = {
    init() {
        const phoneInputs = document.querySelectorAll('input[name="phone"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) value = value.substring(0, 10);

                if (value.length >= 7) {
                    value = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
                } else if (value.length >= 4) {
                    value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
                } else if (value.length >= 1) {
                    value = `(${value}`;
                }

                e.target.value = value;
            });
        });
    }
};




/* ==========================================================
   17. INTERACTIVE US COVERAGE MAP (Centered + Mega-Menu Hover Tooltip)
   ========================================================== */
const InteractiveUSMap = {
    init() {
        const mapSvg = document.getElementById('us-interactive-map');
        const container = document.querySelector('.us-svg-responsive-container');
        const popup = document.getElementById('us-state-hover-popup');
        const popupName = document.getElementById('popup-state-name');
        const popupProvidersText = document.getElementById('popup-providers-text');

        if (!mapSvg || !container || !popup) return;

        const paths = mapSvg.querySelectorAll('.us-state-path');
        const labels = mapSvg.querySelectorAll('.us-state-label');

        function showStateTooltip(pathEl) {
            if (!pathEl) return;
            const code = pathEl.getAttribute('data-code');
            const name = pathEl.getAttribute('data-name');
            const providers = pathEl.getAttribute('data-providers') || '15+';

            // Remove active from all
            paths.forEach(p => p.classList.remove('active-state'));
            labels.forEach(l => l.classList.remove('active-label'));

            // Highlight current
            pathEl.classList.add('active-state');
            const labelEl = mapSvg.querySelector(`.state-label-${code.toLowerCase()}`);
            if (labelEl) labelEl.classList.add('active-label');

            // Update popup content
            if (popupName) popupName.textContent = name;
            if (popupProvidersText) popupProvidersText.textContent = providers + ' Active Practices';

            // Calculate position: center of the state relative to container
            try {
                const stateBBox = pathEl.getBBox();
                const svgRect = mapSvg.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();

                // SVG viewBox is 0 0 959 593
                const scaleX = svgRect.width / 959;
                const scaleY = svgRect.height / 593;

                // Center of state in SVG pixels
                const stateCenterX = (stateBBox.x + stateBBox.width / 2) * scaleX + (svgRect.left - containerRect.left);
                const stateCenterY = stateBBox.y * scaleY + (svgRect.top - containerRect.top);

                popup.style.left = `${stateCenterX}px`;
                popup.style.top = `${stateCenterY}px`;
                popup.classList.add('active');

                // Trigger mega-menu style entrance re-animation
                const popupContent = popup.querySelector('.us-popup-content');
                if (popupContent) {
                    popupContent.style.animation = 'none';
                    popupContent.offsetHeight; // Trigger reflow
                    popupContent.style.animation = 'mnPopupEntrance 0.24s cubic-bezier(0.16, 1, 0.3, 1)';
                }
            } catch (e) {
                // Fallback if getBBox fails
                popup.classList.add('active');
            }
        }

        function hideStateTooltip() {
            popup.classList.remove('active');
            paths.forEach(p => p.classList.remove('active-state'));
            labels.forEach(l => l.classList.remove('active-label'));
        }

        // Attach hover listeners to each state path
        paths.forEach(path => {
            path.addEventListener('mouseenter', () => showStateTooltip(path));
            path.addEventListener('touchstart', (e) => {
                showStateTooltip(path);
            }, { passive: true });
        });

        // Hide when mouse leaves map container
        mapSvg.addEventListener('mouseleave', hideStateTooltip);
    }
};

/* ==========================================================
   18. BLOG INSIGHTS SWIPER CAROUSEL
   ========================================================== */
const BlogInsightsSwiper = {
    init() {
        const container = document.querySelector('.swiper-blog-insights');
        if (!container || typeof Swiper === 'undefined') return;

        new Swiper('.swiper-blog-insights', {
            slidesPerView: 1.15,
            spaceBetween: 20,
            grabCursor: true,
            scrollbar: {
                el: '.blog-swiper-scrollbar',
                draggable: true,
                dragSize: 'auto'
            },
            navigation: {
                nextEl: '.blog-swiper-next',
                prevEl: '.blog-swiper-prev'
            },
            breakpoints: {
                576: {
                    slidesPerView: 2.15,
                    spaceBetween: 22
                },
                992: {
                    slidesPerView: 3.15,
                    spaceBetween: 24
                },
                1200: {
                    slidesPerView: 3.4,
                    spaceBetween: 28
                }
            }
        });
    }
};


/* ==========================================================
   19. TESTIMONIALS SHOWCASE SWIPER
   ========================================================== */
const TestimonialsShowcaseSwiper = {
    init() {
        const swiperEl = document.querySelector('.swiper-testimonials-showcase');
        if (!swiperEl || typeof Swiper === 'undefined') return;

        new Swiper('.swiper-testimonials-showcase', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            speed: 600,
            pagination: {
                el: '.tm-swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.tm-swiper-next',
                prevEl: '.tm-swiper-prev',
            },
        });
    }
};

/* ==========================================================
   20. PRACTICE AUDIT FORM CONTROLLER (Milestone 1)
   ========================================================== */
const AuditForm = {
    form: null,
    submitBtn: null,
    alertBanner: null,
    alertMessage: null,
    successOverlay: null,
    isSubmitting: false,

    /**
     * Resilient server JSON response parser.
     * Extracts JSON safely even if server output buffer leaked PHP warnings.
     */
    parseServerJson(responseText) {
        if (!responseText || typeof responseText !== 'string') {
            throw new Error('Empty server response');
        }
        try {
            return JSON.parse(responseText);
        } catch (parseError) {
            const jsonStart = responseText.lastIndexOf('{"success"');
            if (jsonStart !== -1) {
                return JSON.parse(responseText.slice(jsonStart));
            }
            throw parseError;
        }
    },

    /**
     * Fallback XHR runner if Fetch API encounters unexpected network rejection.
     */
    submitWithXHR(url, formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 500) {
                    resolve(xhr.responseText || '');
                } else {
                    reject(new Error(`Server returned HTTP ${xhr.status}: ${xhr.statusText}`));
                }
            };

            xhr.onerror = () => reject(new Error('Network connection failure.'));
            xhr.ontimeout = () => reject(new Error('Request timed out. Please retry.'));
            xhr.timeout = 20000;
            xhr.send(formData);
        });
    },

    /**
     * Format Phone Input as (XXX) XXX-XXXX
     */
    formatPhoneNumber(value) {
        const cleaned = ('' + value).replace(/\D/g, '');
        const match = cleaned.substring(0, 10);
        if (match.length >= 7) {
            return `(${match.substring(0, 3)}) ${match.substring(3, 6)}-${match.substring(6)}`;
        } else if (match.length >= 4) {
            return `(${match.substring(0, 3)}) ${match.substring(3)}`;
        } else if (match.length >= 1) {
            return `(${match}`;
        }
        return '';
    },

    /**
     * Real-time Input Masking for Phone and ZIP code
     */
    initMasking() {
        if (!this.form) return;

        // Phone inputs
        const phoneInputs = this.form.querySelectorAll('input[type="tel"], input[name="phone"], .phone-mask');
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const start = e.target.selectionStart;
                const prevLen = e.target.value.length;
                e.target.value = this.formatPhoneNumber(e.target.value);
                const newLen = e.target.value.length;
                if (start !== null && start < prevLen) {
                    e.target.setSelectionRange(start, start);
                }
            });
        });

        // ZIP Code inputs (5 numeric digits)
        const zipInputs = this.form.querySelectorAll('input[name="zip_code"], .zip-mask');
        zipInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                let val = e.target.value.replace(/\D/g, '');
                if (val.length > 5) {
                    val = val.substring(0, 5);
                }
                e.target.value = val;
            });
        });
    },

    /**
     * Interactive Multi-Select Pills (Toggle Active state & Checkbox sync)
     */
    initPills() {
        if (!this.form) return;

        const pillLabels = this.form.querySelectorAll('.pain-point-pill, .audit-pill-label, .audit-pill');
        pillLabels.forEach(label => {
            const checkbox = label.querySelector('input[type="checkbox"]');
            if (!checkbox) return;

            // Set initial state
            if (checkbox.checked) {
                label.classList.add('selected', 'active');
                label.setAttribute('aria-checked', 'true');
            } else {
                label.classList.remove('selected', 'active');
                label.setAttribute('aria-checked', 'false');
            }

            // Click listener
            label.addEventListener('click', (e) => {
                if (e.target !== checkbox) {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                }
                if (checkbox.checked) {
                    label.classList.add('selected', 'active');
                    label.setAttribute('aria-checked', 'true');
                } else {
                    label.classList.remove('selected', 'active');
                    label.setAttribute('aria-checked', 'false');
                }
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            });

            // Keyboard accessibility
            label.setAttribute('tabindex', '0');
            label.setAttribute('role', 'checkbox');
            label.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    if (checkbox.checked) {
                        label.classList.add('selected', 'active');
                        label.setAttribute('aria-checked', 'true');
                    } else {
                        label.classList.remove('selected', 'active');
                        label.setAttribute('aria-checked', 'false');
                    }
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    },

    /**
     * Live character counter for additional notes / service requirements
     */
    initCharCounter() {
        if (!this.form) return;
        const notesTextarea = this.form.querySelector('textarea[name="additional_notes"], textarea[name="service_requirements"]');
        const charCountEl = document.getElementById('charCount');
        if (notesTextarea && charCountEl) {
            const updateCount = () => {
                charCountEl.textContent = notesTextarea.value.length;
            };
            notesTextarea.addEventListener('input', updateCount);
            updateCount();
        }
    },

    /**
     * Validate single field value against schema
     */
    validateField(name, value, element) {
        const val = typeof value === 'string' ? value.trim() : '';

        switch (name) {
            case 'practice_name':
            case 'practiceName':
                if (!val || val.length < 2) return 'Please enter your practice or facility name.';
                if (val.length > 150) return 'Practice name must be under 150 characters.';
                return null;

            case 'contact_name':
            case 'contactName':
                if (!val || val.length < 2) return 'Please enter primary contact full name.';
                if (val.length > 100) return 'Name must be under 100 characters.';
                return null;

            case 'job_title':
            case 'jobTitle':
                if (!val || val === '') return 'Please specify your job title or role.';
                return null;

            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!val || !emailRegex.test(val)) return 'Please enter a valid work email address.';
                return null;

            case 'phone':
                const phoneClean = val.replace(/\D/g, '');
                if (!phoneClean || phoneClean.length < 10) return 'Please enter a valid 10-digit phone number.';
                return null;

            case 'street_address':
            case 'address':
                if (!val || val.length < 3) return 'Please enter your practice street address.';
                return null;

            case 'city':
                if (!val || val.length < 2) return 'Please enter your city.';
                return null;

            case 'state':
                if (!val || val === '') return 'Please select your state.';
                return null;

            case 'zip_code':
            case 'zipCode':
            case 'zip':
                const zipClean = val.replace(/\D/g, '');
                if (!zipClean || (zipClean.length !== 5 && zipClean.length !== 9)) {
                    return 'Please enter a valid 5-digit ZIP code.';
                }
                return null;

            case 'specialty':
                if (!val || val === '') return 'Please select your primary specialty.';
                return null;

            case 'patient_volume':
            case 'patientVolume':
            case 'volume':
                if (!val || val === '') return 'Please select monthly patient volume.';
                return null;

            case 'monthly_revenue':
            case 'monthlyRevenue':
            case 'revenue':
                if (!val || val === '') return 'Please select estimated monthly revenue.';
                return null;

            case 'current_ehr':
            case 'currentEhr':
            case 'ehr_software':
                if (!val || val === '' || val.length < 2) return 'Please specify your current EHR / PMS software.';
                return null;

            case 'service_requirements':
            case 'additional_notes':
                if (val.length > 2000) return 'Notes cannot exceed 2000 characters.';
                return null;

            default:
                return null;
        }
    },

    /**
     * Validate entire form and return error map
     */
    validateForm(formData) {
        const errors = {};
        const requiredFields = [
            'practice_name',
            'contact_name',
            'job_title',
            'email',
            'phone',
            'street_address',
            'city',
            'state',
            'zip_code',
            'specialty',
            'patient_volume',
            'monthly_revenue',
            'current_ehr'
        ];

        requiredFields.forEach(field => {
            const altField = field === 'practice_name' ? 'practiceName' :
                             field === 'contact_name' ? 'contactName' :
                             field === 'job_title' ? 'jobTitle' : field;
            const value = formData.get(field) ?? formData.get(altField) ?? '';
            const error = this.validateField(field, value);
            if (error) {
                errors[field] = error;
            }
        });

        // Optional check for notes
        const notes = formData.get('additional_notes') ?? formData.get('service_requirements') ?? '';
        const notesError = this.validateField('service_requirements', notes);
        if (notesError) {
            errors.additional_notes = notesError;
        }

        return errors;
    },

    /**
     * Display validation errors on form controls
     */
    showErrors(errors) {
        this.clearErrors();

        let firstInvalidField = null;

        Object.entries(errors).forEach(([field, message]) => {
            const input = this.form.querySelector(`[name="${field}"], [name="${field}[]"], [id="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                input.setAttribute('aria-invalid', 'true');

                const feedback = input.parentElement.querySelector('.invalid-feedback') ||
                                 input.closest('.form-group')?.querySelector('.invalid-feedback') ||
                                 input.nextElementSibling;

                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = message;
                    feedback.style.display = 'block';
                }

                if (!firstInvalidField) {
                    firstInvalidField = input;
                }
            }
        });

        // Top alert banner
        if (this.alertBanner) {
            const errorCount = Object.keys(errors).length;
            const msg = errorCount === 1 
                ? 'Please correct the highlighted field below.'
                : `Please correct the ${errorCount} highlighted fields below before submitting.`;
            if (this.alertMessage) {
                this.alertMessage.textContent = msg;
            }
            this.alertBanner.classList.remove('d-none');
        }

        // Focus and scroll smoothly to first invalid field
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
    },

    /**
     * Clear all error states
     */
    clearErrors() {
        if (!this.form) return;
        this.form.querySelectorAll('.form-control, .form-select').forEach(field => {
            field.classList.remove('is-invalid');
            field.removeAttribute('aria-invalid');
        });
        this.form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
        if (this.alertBanner) {
            this.alertBanner.classList.add('d-none');
        }
    },

    /**
     * Mark single field valid
     */
    markFieldValid(input) {
        if (!input) return;
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        input.removeAttribute('aria-invalid');
        const feedback = input.parentElement.querySelector('.invalid-feedback') ||
                         input.closest('.form-group')?.querySelector('.invalid-feedback') ||
                         input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    },

    /**
     * Mark single field invalid
     */
    markFieldInvalid(input, message) {
        if (!input) return;
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
        const feedback = input.parentElement.querySelector('.invalid-feedback') ||
                         input.closest('.form-group')?.querySelector('.invalid-feedback') ||
                         input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    },

    /**
     * Real-time inline field validation listeners
     */
    initRealTimeValidation() {
        if (!this.form) return;

        const inputs = this.form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            const fieldName = input.getAttribute('name') || input.id;

            // On Blur: validate field
            input.addEventListener('blur', () => {
                if (input.value.trim().length === 0 && !input.required) {
                    input.classList.remove('is-invalid', 'is-valid');
                    return;
                }
                const error = this.validateField(fieldName, input.value, input);
                if (error) {
                    this.markFieldInvalid(input, error);
                } else if (input.value.trim().length > 0) {
                    this.markFieldValid(input);
                }
            });

            // On Input / Change: clear error if now valid
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid')) {
                    const error = this.validateField(fieldName, input.value, input);
                    if (!error) {
                        this.markFieldValid(input);
                    }
                }
            });

            input.addEventListener('change', () => {
                const error = this.validateField(fieldName, input.value, input);
                if (error) {
                    this.markFieldInvalid(input, error);
                } else if (input.value.trim().length > 0) {
                    this.markFieldValid(input);
                }
            });
        });
    },

    /**
     * Set loading spinner on submit button and toggle input disables
     */
    setLoading(loading) {
        this.isSubmitting = loading;
        if (!this.submitBtn) return;

        const inputs = this.form.querySelectorAll('input, select, textarea, button');

        if (loading) {
            this.submitBtn.disabled = true;
            this.submitBtn.dataset.originalText = this.submitBtn.innerHTML;
            this.submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span>Analyzing Practice Profile...</span>
            `;
            inputs.forEach(el => {
                if (el !== this.submitBtn) el.setAttribute('data-was-disabled', el.disabled);
                el.disabled = true;
            });
        } else {
            this.submitBtn.disabled = false;
            this.submitBtn.innerHTML = this.submitBtn.dataset.originalText || '<i class="ph ph-chart-line-up fs-5"></i><span>Generate My Free Practice Audit</span>';
            inputs.forEach(el => {
                if (el.getAttribute('data-was-disabled') === 'false' || !el.hasAttribute('data-was-disabled')) {
                    el.disabled = false;
                }
                el.removeAttribute('data-was-disabled');
            });
        }
    },

    /**
     * Render Animated Success Card & Fill Submission Summary Details
     */
    showSuccessState(payload, formData) {
        const practiceName = formData.get('practice_name') || formData.get('practiceName') || 'Your Practice';
        const contactName = formData.get('contact_name') || formData.get('contactName') || 'Doctor';
        const email = formData.get('email') || '';
        const phone = formData.get('phone') || '';
        const specialty = formData.get('specialty') || 'Healthcare Specialty';
        const leadId = payload.data?.lead_id || payload.data?.submission_id || '#AUD-' + Math.floor(100000 + Math.random() * 900000);

        // Populate success elements
        const nameEl = document.getElementById('successContactName');
        const leadNameEl = document.getElementById('successLeadName');
        const practiceEl = document.getElementById('successPracticeName');
        const leadIdEl = document.getElementById('successLeadId');
        const specialtyEl = document.getElementById('successSpecialty');
        const emailEl = document.getElementById('successContactEmail');
        const phoneEl = document.getElementById('successContactPhone');

        if (nameEl) nameEl.textContent = contactName;
        if (leadNameEl) leadNameEl.textContent = contactName;
        if (practiceEl) practiceEl.textContent = practiceName;
        if (leadIdEl) leadIdEl.textContent = typeof leadId === 'number' ? `#AUD-${leadId}` : leadId;
        if (specialtyEl) specialtyEl.textContent = specialty;
        if (emailEl) emailEl.textContent = email;
        if (phoneEl) phoneEl.textContent = phone;

        // Hide form
        this.form.style.display = 'none';

        // Display success overlay
        if (this.successOverlay) {
            this.successOverlay.style.display = 'block';
            this.successOverlay.classList.add('active');
            this.successOverlay.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Trigger custom event for analytics
        document.dispatchEvent(new CustomEvent('auditFormSubmitted', {
            detail: { leadId, practiceName, specialty, email }
        }));
    },

    /**
     * Handle Form Submission via AJAX Pipeline
     */
    async handleSubmit(e) {
        e.preventDefault();
        if (this.isSubmitting) return;

        const formData = new FormData(this.form);

        // Honeypot anti-bot check
        const honeypot = formData.get('website_hp') || formData.get('audit_form_hp') || formData.get('hp_audit_field') || '';
        if (honeypot.trim() !== '') {
            // Simulated fake success for spam bot
            this.setLoading(true);
            setTimeout(() => {
                this.setLoading(false);
                this.showSuccessState({ success: true, data: { lead_id: 'SPAM-FILTERED' } }, formData);
            }, 800);
            return;
        }

        // Validate client-side
        const errors = this.validateForm(formData);
        if (Object.keys(errors).length > 0) {
            this.showErrors(errors);
            return;
        }

        this.clearErrors();
        this.setLoading(true);

        const targetUrl = this.form.getAttribute('action') || 'api/submit-audit-request.php';

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 20000);

            const response = await fetch(targetUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            const responseText = await response.text();
            let result;

            try {
                result = this.parseServerJson(responseText);
            } catch (parseErr) {
                if (response.ok && responseText.includes('success=1')) {
                    result = { success: true, message: 'Audit request received successfully.' };
                } else {
                    throw new Error('Malformed server response.');
                }
            }

            if (result.success) {
                this.showSuccessState(result, formData);
                this.form.reset();
            } else {
                if (result.errors || (result.data && result.data.errors)) {
                    const serverErrors = result.errors || result.data.errors;
                    this.showErrors(serverErrors);
                } else {
                    if (this.alertBanner && this.alertMessage) {
                        this.alertMessage.textContent = result.message || 'An error occurred during submission. Please try again.';
                        this.alertBanner.classList.remove('d-none');
                        this.alertBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        alert(result.message || 'Submission failed. Please check your entries.');
                    }
                }
            }
        } catch (error) {
            // Attempt XHR Fallback
            try {
                const xhrResponseText = await this.submitWithXHR(targetUrl, formData);
                const fallbackResult = this.parseServerJson(xhrResponseText);

                if (fallbackResult.success) {
                    this.showSuccessState(fallbackResult, formData);
                    this.form.reset();
                    return;
                }

                if (fallbackResult.errors) {
                    this.showErrors(fallbackResult.errors);
                    return;
                }

                throw new Error(fallbackResult.message || 'XHR submission failed.');
            } catch (xhrError) {
                if (this.alertBanner && this.alertMessage) {
                    this.alertMessage.textContent = 'Connection timeout or network issue. Please verify your connection or call us directly at 862-799-2199.';
                    this.alertBanner.classList.remove('d-none');
                    this.alertBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alert('Network error. Please call us at 862-799-2199 or try again.');
                }
            }
        } finally {
            this.setLoading(false);
        }
    },

    /**
     * Initialize Module
     */
    init() {
        this.form = document.getElementById('practice-audit-form') || document.getElementById('auditForm') || document.getElementById('auditRequestForm');
        if (!this.form) return;

        this.submitBtn = this.form.querySelector('button[type="submit"]') || document.getElementById('auditSubmitBtn');
        this.alertBanner = document.getElementById('auditFormAlert');
        if (this.alertBanner) {
            this.alertMessage = this.alertBanner.querySelector('.alert-message') || this.alertBanner;
        }
        this.successOverlay = document.getElementById('auditSuccessOverlay') || document.getElementById('formSuccessOverlay');

        this.initMasking();
        this.initPills();
        this.initCharCounter();
        this.initRealTimeValidation();

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }
};

/* ==========================================================
   MASTER INITIALIZATION
   ========================================================== */
document.addEventListener('DOMContentLoaded', () => {
    PageLoader.init();
    ScrollProgress.init();
    Navbar.init();
    BackToTop.init();
    ScrollManager.init();
    SmoothScroll.init();
    RippleEffect.init();
    PhoneFormatter.init();
    FaqAccordion.init();
    InteractiveUSMap.init();

    // Initialize after slight delay for library loading
    setTimeout(() => {
        TypedInit.init();
        CounterInit.init();
        SwiperInit.init();
        BlogInsightsSwiper.init();
        TestimonialsShowcaseSwiper.init();
        MockupAnimation.init();
        ContactForm.init();
        NewsletterForm.init();
        AuditForm.init();
    }, 100);
});

