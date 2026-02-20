<?php

if (!defined('ABSPATH')) exit;


/* ============================================================
 * MAIL SETTINGS
 * ============================================================ */

add_filter('wp_mail_from_name', function ($name) {
    return trim(get_option('blogname'));
});

add_filter('wp_mail_from', function ($from) {

    $site_url = trim(get_option('siteurl'));
    $host = preg_replace('~^https?://~', '', $site_url);
    $host = preg_replace('~/.*$~', '', $host);
    $host = trim($host);

    if ($host) {
        return "no-reply@{$host}";
    }

    return $from;
});


/* ============================================================
 * DISABLE AUTOPTIMIZE CSS (убираем autoptimize_single_*.css)
 * ============================================================ */

add_filter('autoptimize_filter_css', '__return_false');


/* ============================================================
 * PROGRAMS ARCHIVE FILTER
 * ============================================================ */

add_action('pre_get_posts', function ($q) {

    if (is_admin() || !$q->is_main_query()) return;
    if (!is_post_type_archive('programs')) return;

    $language = isset($_GET['language']) ? sanitize_title($_GET['language']) : '';
    if (!$language) return;

    $tax_query = (array) $q->get('tax_query');
    if (empty($tax_query)) $tax_query = ['relation' => 'AND'];

    $tax_query[] = [
            'taxonomy' => 'language',
            'field'    => 'slug',
            'terms'    => $language,
    ];

    $q->set('tax_query', $tax_query);

});


/* ============================================================
 * MENU IMAGE
 * ============================================================ */

add_filter('nav_menu_item_title', function ($title, $item) {

    $img_id = get_post_meta($item->ID, 'menu-image-id', true);
    if (!$img_id) {
        $img_id = get_post_meta($item->ID, 'menu-image', true);
    }

    if ($img_id) {
        $src = wp_get_attachment_image_url((int) $img_id, 'thumbnail');
        if ($src) {
            $title = '<img class="menu-image" src="' . esc_url($src) . '" alt=""> ' . $title;
        }
    }

    return $title;

}, 10, 2);


/* ============================================================
 * ENQUEUE SCRIPTS & STYLES (СТАБИЛЬНО, БЕЗ АРТЕФАКТОВ)
 * ============================================================ */

add_action('wp_enqueue_scripts', function () {

    $css_rel = '/assets/css/style.min.css';
    $js_rel  = '/assets/js/main.min.js';

    $css_path = get_template_directory() . $css_rel;
    $js_path  = get_template_directory() . $js_rel;

    $css_ver = file_exists($css_path) ? filemtime($css_path) : null;
    $js_ver  = file_exists($js_path) ? filemtime($js_path) : null;

    wp_enqueue_style(
            'ed-main',
            get_template_directory_uri() . $css_rel,
            [],
            $css_ver
    );

    wp_enqueue_script(
            'ed-main',
            get_template_directory_uri() . $js_rel,
            [],
            $js_ver,
            true
    );

}, 20);


/* ============================================================
 * LCP IMAGE PRELOAD (главная)
 * ============================================================ */

add_action('wp_head', function () {

    if (is_admin()) return;
    if (!is_front_page()) return;

    $href = get_template_directory_uri() . '/assets/img/world.svg';

    echo '<link rel="preload" as="image" href="' . esc_url($href) . '" fetchpriority="high">' . "\n";

}, 1);


/* ============================================================
 * DELAYED ANALYTICS (5 сек ИЛИ взаимодействие)
 * ============================================================ */

add_action('wp_footer', function () {

    if (is_admin()) return;

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false ||
            strpos($host, '.loc') !== false ||
            strpos($host, '.test') !== false) {
        return;
    }

    ?>
    <script>
        (function () {

            if (window.__trackersLoadedOnce) return;
            window.__trackersLoadedOnce = true;

            function loadTrackers() {

                if (window.__trackersLoaded) return;
                window.__trackersLoaded = true;

                // ===== Meta Pixel =====
                !function(f,b,e,v,n,t,s){
                    if(f.fbq)return;n=f.fbq=function(){
                        n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)
                    };
                    if(!f._fbq)f._fbq=n;
                    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];
                    t=b.createElement(e);t.async=!0;t.src=v;
                    s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)
                }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');

                fbq('init', '794161271856671');
                fbq('track', 'PageView');

                // ===== GTM =====
                window.dataLayer = window.dataLayer || [];
                (function(w,d,s,l,i){
                    w[l]=w[l]||[];
                    w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
                    var f=d.getElementsByTagName(s)[0],
                        j=d.createElement(s),
                        dl=l!='dataLayer' ? '&l='+l : '';
                    j.async=true;
                    j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
                    f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','GTM-P3BNBJ4');

                // ===== TikTok =====
                !function (w, d, t) {
                    w.TiktokAnalyticsObject=t;
                    var ttq=w[t]=w[t]||[];
                    ttq.methods=["page","track","identify","instances"];
                    ttq.setAndDefer=function(t,e){
                        t[e]=function(){
                            t.push([e].concat(Array.prototype.slice.call(arguments,0)))
                        }
                    };
                    for (var i=0;i<ttq.methods.length;i++) ttq.setAndDefer(ttq,ttq.methods[i]);
                    ttq.load=function(e){
                        var n=document.createElement("script");
                        n.async=!0;
                        n.src="https://analytics.tiktok.com/i18n/pixel/events.js?sdkid="+e+"&lib=ttq";
                        var s=document.getElementsByTagName("script")[0];
                        s.parentNode.insertBefore(n,s)
                    };
                    ttq.load('COGOMNBC77U9B3UA6340');
                    ttq.page();
                }(window, document, 'ttq');
            }

            // 5 секунд после load
            window.addEventListener('load', function () {
                setTimeout(loadTrackers, 5000);
            });

            // или первое взаимодействие
            var fired = false;
            function onFirstInteraction() {
                if (fired) return;
                fired = true;
                loadTrackers();
            }

            window.addEventListener('scroll', onFirstInteraction, {passive:true});
            window.addEventListener('click', onFirstInteraction, true);
            window.addEventListener('touchstart', onFirstInteraction, {passive:true});

        })();
    </script>
    <?php
}, 999);

add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    // Попробуем снять стиль по самому частому handle
    wp_dequeue_style('wmi-front-style');
    wp_deregister_style('wmi-front-style');

    // На некоторых версиях/сборках handle может отличаться — тогда снимем по URL (см. способ 2)
}, 999);