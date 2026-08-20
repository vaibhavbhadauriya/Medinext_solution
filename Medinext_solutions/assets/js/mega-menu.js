/* Mega Menu v5 — Medusind-Style 2-Column with Hover Panels */
document.addEventListener('DOMContentLoaded', function() {

    // --- Tab switching (Medical / Dental) ---
    var medTab = document.getElementById('mega-tab-medical');
    var denTab = document.getElementById('mega-tab-dental');
    var medContent = document.getElementById('mega-content-medical');
    var denContent = document.getElementById('mega-content-dental');

    if (medTab && denTab && medContent && denContent) {
        medTab.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            medTab.classList.add('active');
            denTab.classList.remove('active');
            medContent.style.display = 'flex';
            denContent.style.display = 'none';
        });
        denTab.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            denTab.classList.add('active');
            medTab.classList.remove('active');
            denContent.style.display = 'flex';
            medContent.style.display = 'none';
        });
    }

    // --- Left Nav ↔ Right Panel Hover Interaction ---
    function initNavPanels(wrapperEl) {
        if (!wrapperEl) return;
        var navItems = wrapperEl.querySelectorAll('.mn-nav-item');
        var panels = wrapperEl.querySelectorAll('.mn-panel');

        navItems.forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                var targetId = this.getAttribute('data-panel');
                // Deactivate all nav items
                navItems.forEach(function(n) { n.classList.remove('active'); });
                // Activate this one
                this.classList.add('active');
                // Switch panels
                panels.forEach(function(p) { p.classList.remove('active'); });
                var targetPanel = document.getElementById(targetId);
                if (targetPanel) targetPanel.classList.add('active');
            });

            // Also handle click for mobile / touch
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var targetId = this.getAttribute('data-panel');
                navItems.forEach(function(n) { n.classList.remove('active'); });
                this.classList.add('active');
                panels.forEach(function(p) { p.classList.remove('active'); });
                var targetPanel = document.getElementById(targetId);
                if (targetPanel) targetPanel.classList.add('active');
            });
        });
    }

    // Initialize for both Medical and Dental tabs
    initNavPanels(medContent);
    initNavPanels(denContent);

    // --- Pause heavy canvas animations when dropdown is open ---
    var dropdowns = document.querySelectorAll('.mega-dropdown');
    dropdowns.forEach(function(dd) {
        var shaderCanvas = document.getElementById('shader-canvas');
        var auroraCanvas = document.getElementById('footer-aurora-canvas');

        dd.addEventListener('show.bs.dropdown', function() {
            if (shaderCanvas) shaderCanvas.style.display = 'none';
            if (auroraCanvas) auroraCanvas.style.display = 'none';
        });
        dd.addEventListener('hidden.bs.dropdown', function() {
            if (shaderCanvas) shaderCanvas.style.display = '';
            if (auroraCanvas) auroraCanvas.style.display = '';
        });
    });

    // --- Double-click Services to navigate directly ---
    var sBtn = document.getElementById('servicesDropdown');
    if (sBtn) {
        sBtn.addEventListener('dblclick', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    }
});
