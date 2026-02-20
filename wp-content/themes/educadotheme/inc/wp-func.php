<?php

if (!defined('ABSPATH')) exit;

/**
 * Change FROM name for wp_mail
 */
add_filter('wp_mail_from_name', function($name){
    return trim(get_option('blogname'));
});

add_filter('wp_mail_from', function($from){
    $site_url = trim(get_option('siteurl'));
    $host = preg_replace('~^https?://~', '', $site_url);
    $host = preg_replace('~/.*$~', '', $host);
    $host = trim($host);

    if ($host) {
        return "no-reply@{$host}";
    }

    return $from;
});

/**
 * Фильтр архива programs по taxonomy=language из GET ?language=slug
 */
add_action('pre_get_posts', function($q){
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

/**
 * Добавляет картинку в заголовок пункта меню (Menu Image)
 */
add_filter('nav_menu_item_title', function($title, $item, $args, $depth){

    $img_id = get_post_meta($item->ID, 'menu-image-id', true);
    if (!$img_id) {
        $img_id = get_post_meta($item->ID, 'menu-image', true);
    }

    if ($img_id) {
        $src = wp_get_attachment_image_url((int)$img_id, 'thumbnail');
        if ($src) {
            $title = '<img class="menu-image" src="'.esc_url($src).'" alt=""> ' . $title;
        }
    }

    return $title;
}, 10, 4);

/**
 * Form processing AJAX
 */
function ed_callback()
{
    $to = trim((string) get_field('get_letter_from_site', 'options'));
    if (!$to) $to = trim((string) get_option('admin_email'));

    $type = '';
    if (isset($_REQUEST['title']) && $_REQUEST['title'] !== '') {
        $type = wp_strip_all_tags((string) $_REQUEST['title']);
    }

    $site_name = trim((string) get_option('blogname'));
    $subject = $site_name . ($type ? ': ' . $type : '');

    $skip = ['title','action','url','_wpnonce','_wp_http_referer'];
    $mes = '';

    foreach ((array) $_REQUEST as $key => $value) {
        if (in_array($key, $skip, true)) continue;
        if ($value === null || $value === '' ) continue;

        if (is_array($value)) {
            $value = implode(', ', array_map('sanitize_text_field', $value));
        } else {
            $value = sanitize_text_field((string)$value);
        }

        $k = sanitize_text_field((string)$key);
        $mes .= '<p><strong>' . esc_html($k) . ':</strong> ' . esc_html($value) . '</p>';
    }

    $ip = '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', (string)$_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim((string)($parts[0] ?? ''));
    }
    if (!$ip && !empty($_SERVER['REMOTE_ADDR'])) {
        $ip = trim((string)$_SERVER['REMOTE_ADDR']);
    }
    $ip = sanitize_text_field($ip);

    $ip_html = $ip ? '<div><strong>IP:</strong> ' . esc_html($ip) . '</div><br>' : '';

    $country_html = '';
    if ($ip) {
        $url = 'http://ip-api.com/json/' . rawurlencode($ip) . '?lang=ru';
        $resp = wp_remote_get($url, ['timeout' => 2]);

        if (!is_wp_error($resp)) {
            $body = wp_remote_retrieve_body($resp);
            $data = json_decode($body, true);
            if (is_array($data) && !empty($data['country'])) {
                $country_html = '<div><strong>Країна:</strong> ' . esc_html((string)$data['country']) . '</div><br>';
            }
        }
    }

    $utm_keys = ['utm_source','utm_medium','utm_campaign','utm_content','utm_term','utm_id','utm_source_platform','utm_creative_format','utm_marketing_tactic'];
    $utm_html = '';
    foreach ($utm_keys as $k) {
        if (isset($_POST[$k]) && $_POST[$k] !== '') {
            $utm_html .= '<div><strong>' . esc_html($k) . ':</strong> ' . esc_html(sanitize_text_field((string)$_POST[$k])) . '</div><br>';
        }
    }
    if ($utm_html) {
        $utm_html = '<hr><div><strong>UTM:</strong></div><br>' . $utm_html;
    }

    $message = '
        <html>
            <head>
                <meta charset="utf-8">
                <title>' . esc_html($subject) . '</title>
            </head>
            <body>
                ' . $ip_html . '
                ' . $country_html . '
                ' . $utm_html . '
                <hr>
                ' . $mes . '
            </body>
        </html>
    ';

    $headers = [
            'Content-Type: text/html; charset=UTF-8',
    ];

    $result = wp_mail($to, $subject, $message, $headers);
    echo $result ? 1 : 0;

    wp_die();
}

add_action('wp_ajax_ed_callback', 'ed_callback');
add_action('wp_ajax_nopriv_ed_callback', 'ed_callback');


/**
 * Speed: preconnect for critical hosts ONLY
 * (трекеры грузятся позже — preconnect к ним в head НЕ нужен)
 */
add_action('wp_head', function () {
    if (is_admin()) return;
    ?>
    <link rel="preconnect" href="https://code.jquery.com" crossorigin>
    <?php
}, 1);


/**
 * jQuery CDN — оставляем в HEAD, чтобы не ломать зависимости темы
 */
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    wp_deregister_script('jquery');
    wp_register_script(
            'jquery',
            'https://code.jquery.com/jquery-3.7.1.min.js',
            [],
            '3.7.1',
            false // head
    );
    wp_enqueue_script('jquery');
}, 20);




/**
 * ✅ Analytics: delayed loading (5 sec OR first user interaction OR idle)
 * - меньше влияет на PageSpeed
 * - меньше потерь событий, чем "только 5 секунд"
 */
add_action('wp_footer', function () {
    if (is_admin()) return;

    // DEV: не грузим трекеры
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $is_dev = false;
    if (function_exists('str_contains')) {
        $is_dev = str_contains($host, 'localhost') || str_contains($host, '.loc') || str_contains($host, '.test');
    } else {
        $is_dev = (strpos($host, 'localhost') !== false) || (strpos($host, '.loc') !== false) || (strpos($host, '.test') !== false);
    }
    if ($is_dev) return;
    ?>
    <script>
        (function () {
            if (window.__trackersLoadedOnce) return;
            window.__trackersLoadedOnce = true;

            function preconnectOnce(href) {
                try {
                    var key = 'data-preconnect-' + href;
                    if (document.querySelector('link[' + key + '="1"]')) return;
                    var l = document.createElement('link');
                    l.rel = 'preconnect';
                    l.href = href;
                    l.crossOrigin = 'anonymous';
                    l.setAttribute(key, '1');
                    document.head.appendChild(l);
                } catch (e) {}
            }

            function loadTrackers() {
                if (window.__trackersLoaded) return;
                window.__trackersLoaded = true;

                // preconnect — ТОЛЬКО перед самой загрузкой
                preconnectOnce('https://connect.facebook.net');
                preconnectOnce('https://www.googletagmanager.com');
                preconnectOnce('https://analytics.tiktok.com');

                // ===== Meta Pixel =====
                if (!window.fbq) {
                    !function(f,b,e,v,n,t,s){
                        if(f.fbq) return;
                        n=f.fbq=function(){ n.callMethod ? n.callMethod.apply(n,arguments) : n.queue.push(arguments) };
                        if(!f._fbq) f._fbq=n;
                        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];
                        t=b.createElement(e);t.async=!0;t.src=v;
                        s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)
                    }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');

                    fbq('init', '794161271856671');
                    fbq('track', 'PageView');
                    fbq('track', 'Contact');
                }

                // ===== GTM =====
                window.dataLayer = window.dataLayer || [];
                if (!window.__gtmLoaded) {
                    window.__gtmLoaded = true;
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
                }

                // ===== TikTok Pixel =====
                if (!window.ttq) {
                    !function (w, d, t) {
                        w.TiktokAnalyticsObject=t;
                        var ttq=w[t]=w[t]||[];
                        ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"];
                        ttq.setAndDefer=function(t,e){ t[e]=function(){ t.push([e].concat(Array.prototype.slice.call(arguments,0))) } };
                        for (var i=0;i<ttq.methods.length;i++) ttq.setAndDefer(ttq,ttq.methods[i]);
                        ttq.load=function(e){
                            var n=document.createElement("script");
                            n.type="text/javascript"; n.async=!0;
                            n.src="https://analytics.tiktok.com/i18n/pixel/events.js?sdkid="+e+"&lib="+t;
                            var s=document.getElementsByTagName("script")[0];
                            s.parentNode.insertBefore(n,s)
                        };
                        ttq.load('COGOMNBC77U9B3UA6340');
                        ttq.page();
                    }(window, document, 'ttq');
                }
            }

            // 1) после полной загрузки + 5 секунд
            window.addEventListener('load', function () {
                setTimeout(loadTrackers, 5000);

                // 2) или когда браузер простаивает (если поддерживает)
                if ('requestIdleCallback' in window) {
                    requestIdleCallback(loadTrackers, {timeout: 5000});
                }
            });

            // 3) или при первом взаимодействии пользователя (чтобы не терять события)
            var fired = false;
            function onFirstInteraction() {
                if (fired) return;
                fired = true;
                loadTrackers();
                window.removeEventListener('scroll', onFirstInteraction, {passive:true});
                window.removeEventListener('click', onFirstInteraction, true);
                window.removeEventListener('touchstart', onFirstInteraction, {passive:true});
                window.removeEventListener('mousemove', onFirstInteraction, {passive:true});
            }

            window.addEventListener('scroll', onFirstInteraction, {passive:true});
            window.addEventListener('click', onFirstInteraction, true);
            window.addEventListener('touchstart', onFirstInteraction, {passive:true});
            window.addEventListener('mousemove', onFirstInteraction, {passive:true});
        })();
    </script>
    <?php
}, 999);

add_action('wp_head', function () {
    if (is_admin()) return;
    if (!is_front_page()) return;

    $href = get_template_directory_uri() . '/assets/img/world.svg';
    echo '<link rel="preload" as="image" href="' . esc_url($href) . '" fetchpriority="high">' . "\n";
}, 1);