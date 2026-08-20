/* MEDINEXT SOLUTIONS — Service Worker v1.0
 * File: sw.js (place in website root)
 * Purpose: Offline support and performance caching
 */
'use strict';

var CACHE_NAME = 'ph-cache-v1';
var OFFLINE_URL = '/offline.html';
var STATIC_ASSETS = [
    '/',
    '/offline.html',
    '/about/',
    '/contact/',
    '/medical-billing-services/',
    '/assets/css/style.css',
    '/assets/css/animations.css',
    '/assets/css/seo-enhancements.css',
    '/assets/js/seo-enhancements.js',
    '/assets/images/logo.png'
];

/* ── Install: Pre-cache core assets ──────────────────────────── */
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(STATIC_ASSETS.map(function (url) {
                return new Request(url, { credentials: 'same-origin' });
            }));
        }).then(function () {
            return self.skipWaiting();
        }).catch(function (err) {
            console.warn('[SW] Pre-cache failed for some assets:', err);
        })
    );
});

/* ── Activate: Delete old caches ─────────────────────────────── */
self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (names) {
            return Promise.all(
                names.filter(function (name) { return name !== CACHE_NAME; })
                    .map(function (name) { return caches.delete(name); })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

/* ── Fetch: Tiered caching strategy ──────────────────────────── */
self.addEventListener('fetch', function (event) {
    var request = event.request;
    var url = new URL(request.url);

    /* Only handle same-origin GET requests */
    if (request.method !== 'GET' || url.origin !== self.location.origin) return;

    var isHTML = request.headers.get('Accept') && request.headers.get('Accept').includes('text/html');
    var isFont = url.pathname.match(/\.(woff2?|ttf|otf|eot)(\?.*)?$/i);
    var isImage = url.pathname.match(/\.(jpe?g|png|gif|svg|webp|ico)(\?.*)?$/i);
    var isAsset = url.pathname.match(/\.(css|js)(\?.*)?$/i);

    if (isHTML) {
        /* HTML: Network-first, fallback to cache, then offline page */
        event.respondWith(
            fetch(request).then(function (res) {
                var clone = res.clone();
                caches.open(CACHE_NAME).then(function (c) { c.put(request, clone); });
                return res;
            }).catch(function () {
                return caches.match(request).then(function (cached) {
                    return cached || caches.match(OFFLINE_URL);
                });
            })
        );
    } else if (isAsset || isFont) {
        /* CSS/JS/Fonts: Cache-first, fallback to network */
        event.respondWith(
            caches.match(request).then(function (cached) {
                if (cached) return cached;
                return fetch(request).then(function (res) {
                    var clone = res.clone();
                    caches.open(CACHE_NAME).then(function (c) { c.put(request, clone); });
                    return res;
                });
            })
        );
    } else if (isImage) {
        /* Images: Cache-first, fallback to network, then transparent placeholder */
        event.respondWith(
            caches.match(request).then(function (cached) {
                if (cached) return cached;
                return fetch(request).then(function (res) {
                    var clone = res.clone();
                    caches.open(CACHE_NAME).then(function (c) { c.put(request, clone); });
                    return res;
                }).catch(function () {
                    return new Response(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"></svg>',
                        { headers: { 'Content-Type': 'image/svg+xml' } }
                    );
                });
            })
        );
    }
});
