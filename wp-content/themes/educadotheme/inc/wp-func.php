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

/* ============================================================
 * TECHNICAL SEO
 * ============================================================ */

function educado_seo_hidden_taxonomies()
{
    return ['vchitel_', 'r_ven_', 'napryam', 'language', 'category'];
}

function educado_seo_hidden_post_types()
{
    return ['teachers', 'courses', 'reviews', 'price', 'programs'];
}

function educado_seo_clean_text($value)
{
    if (is_array($value) || is_object($value)) {
        return '';
    }

    $text = wp_strip_all_tags(strip_shortcodes((string) $value));
    $text = html_entity_decode($text, ENT_QUOTES, get_bloginfo('charset'));
    $text = preg_replace('/\s+/u', ' ', $text);

    return trim((string) $text);
}

function educado_seo_lowercase_path($path)
{
    return preg_replace_callback('/%[0-9A-Fa-f]{2}|[A-Z]+/', function ($match) {
        return strpos($match[0], '%') === 0 ? $match[0] : strtolower($match[0]);
    }, (string) $path);
}

function educado_seo_lowercase_url_path($url)
{
    $parts = wp_parse_url($url);

    if (empty($parts['path'])) {
        return $url;
    }

    $path = educado_seo_lowercase_path($parts['path']);
    if ($path === $parts['path']) {
        return $url;
    }

    $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
    $host = $parts['host'] ?? '';
    $port = isset($parts['port']) ? ':' . $parts['port'] : '';
    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

    return $scheme . $host . $port . $path . $query . $fragment;
}

function educado_seo_request_query_params()
{
    static $params = null;

    if ($params !== null) {
        return $params;
    }

    parse_str((string) ($_SERVER['QUERY_STRING'] ?? ''), $params);

    return is_array($params) ? $params : [];
}

function educado_seo_has_filter_params()
{
    foreach (array_keys(educado_seo_request_query_params()) as $key) {
        $key = (string) $key;

        if ($key === 'language' && (is_page_template('programs-one-lang.php') || is_post_type_archive('programs'))) {
            return true;
        }

        if (strpos($key, '_sft_') === 0 || strpos($key, 'sf_') === 0) {
            return true;
        }

        if (in_array($key, ['sfid', 'sf_paged', 'sf_action'], true)) {
            return true;
        }
    }

    return false;
}

function educado_seo_should_noindex_request()
{
    if (is_admin() || wp_doing_ajax()) {
        return false;
    }

    if (is_search() || is_author() || is_date() || is_attachment() || is_category()) {
        return true;
    }

    if (is_tax(educado_seo_hidden_taxonomies())) {
        return true;
    }

    if (is_post_type_archive(educado_seo_hidden_post_types()) || is_singular(educado_seo_hidden_post_types())) {
        return true;
    }

    return educado_seo_has_filter_params();
}

add_action('template_redirect', function () {
    if (educado_seo_should_noindex_request() && !headers_sent()) {
        header('X-Robots-Tag: noindex, nofollow', true);
    }
}, 1);

add_filter('wp_robots', function ($robots) {
    if (!educado_seo_should_noindex_request()) {
        return $robots;
    }

    unset($robots['index'], $robots['follow']);
    $robots['noindex'] = true;
    $robots['nofollow'] = true;

    return $robots;
}, 20);

add_filter('wpseo_robots', function ($robots) {
    if (!educado_seo_should_noindex_request()) {
        return $robots;
    }

    return 'noindex, nofollow, max-image-preview:large';
}, 20);

add_filter('wpseo_canonical', function ($canonical) {
    if (educado_seo_should_noindex_request()) {
        return false;
    }

    if (!$canonical) {
        return $canonical;
    }

    return educado_seo_lowercase_url_path($canonical);
}, 20);

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (!in_array($method, ['GET', 'HEAD'], true)) {
        return;
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $parts = wp_parse_url($uri);
    $path = $parts['path'] ?? '';
    $lower_path = educado_seo_lowercase_path($path);

    if ($path === '' || $lower_path === $path) {
        return;
    }

    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    wp_safe_redirect(home_url($lower_path . $query), 301, 'Educado SEO');
    exit;
}, 0);

add_filter('wpseo_sitemap_exclude_taxonomy', function ($excluded, $taxonomy) {
    return in_array($taxonomy, educado_seo_hidden_taxonomies(), true) ? true : $excluded;
}, 10, 2);

add_filter('wpseo_sitemap_exclude_post_type', function ($excluded, $post_type) {
    return in_array($post_type, educado_seo_hidden_post_types(), true) ? true : $excluded;
}, 10, 2);

add_filter('wpseo_sitemap_exclude_author', function () {
    return [];
});

add_filter('wp_sitemaps_taxonomies', function ($taxonomies) {
    foreach (educado_seo_hidden_taxonomies() as $taxonomy) {
        unset($taxonomies[$taxonomy]);
    }

    return $taxonomies;
});

add_filter('wp_sitemaps_post_types', function ($post_types) {
    foreach (educado_seo_hidden_post_types() as $post_type) {
        unset($post_types[$post_type]);
    }

    return $post_types;
});

add_filter('big_image_size_threshold', function () {
    return 1920;
});

add_filter('wp_editor_set_quality', function ($quality, $mime_type) {
    return $mime_type === 'image/jpeg' ? 82 : $quality;
}, 10, 2);

add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (empty($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }

    return $attr;
}, 10);

function educado_internal_redirect_link_map()
{
    return [
        [home_url('/anhliyska/fnhliyska-mova-z-nosiyem'), home_url('/anhliyska/anhliyska-mova-z-nosiyem/'), true],
        [home_url('/anhliyska/anglijska-dlya-roboty'), home_url('/anhliyska/anglijska-dlya-roboty/'), false],
        [home_url('/anhliyska/anglijska-pidhotovka-do-ispytiv'), home_url('/anhliyska/anglijska-pidhotovka-do-ispytiv/'), false],
        [home_url('/anhliyska/anhliyska-dlya-ditey-ta-pidlitkiv'), home_url('/anhliyska/anhliyska-dlya-ditey-ta-pidlitkiv/'), false],
        [home_url('/ispanska/ispanska-dlya-doroslyh'), home_url('/ispanska/ispanska-dlya-doroslyh/'), false],
        [home_url('/ispanska/ispanska-dlya-ditey-ta-pidlitkiv'), home_url('/ispanska/ispanska-dlya-ditey-ta-pidlitkiv/'), false],
        [home_url('/magazyn'), home_url('/mahazyn-kursiv-z-ispanskoyi/'), true],
        [home_url('/language/ispanska/page/1'), home_url('/language/ispanska/'), true],
    ];
}

function educado_rewrite_internal_redirect_links($html)
{
    if (!is_string($html) || $html === '') {
        return $html;
    }

    foreach (educado_internal_redirect_link_map() as $rule) {
        $from = rtrim((string) ($rule[0] ?? ''), '/');
        $to = (string) ($rule[1] ?? '');
        $optional_slash = !empty($rule[2]);

        if ($from === '' || $to === '') {
            continue;
        }

        $pattern = '~' . preg_quote($from, '~') . ($optional_slash ? '/?' : '') . '(?=(["\'\s<>#?]|$))~u';
        $replaced = preg_replace($pattern, $to, $html);

        if (is_string($replaced)) {
            $html = $replaced;
        }
    }

    return $html;
}

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || is_feed() || is_robots() || is_trackback()) {
        return;
    }

    ob_start('educado_rewrite_internal_redirect_links');
}, 2);

function educado_reviews_cache_key()
{
    return 'educado_reviews_items_v1';
}

function educado_reviews_flush_cache()
{
    delete_transient(educado_reviews_cache_key());
}

add_action('save_post_reviews', 'educado_reviews_flush_cache');
add_action('deleted_post', 'educado_reviews_flush_cache');
add_action('trashed_post', 'educado_reviews_flush_cache');

function educado_reviews_items()
{
    $cache_key = educado_reviews_cache_key();
    $cached = get_transient($cache_key);

    if (is_array($cached)) {
        return $cached;
    }

    $reviews = get_posts([
        'post_type' => 'reviews',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'data',
        'orderby' => 'meta_value',
        'meta_type' => 'DATE',
        'order' => 'DESC',
        'no_found_rows' => true,
        'suppress_filters' => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false,
    ]);

    $items = [];

    foreach ($reviews as $review) {
        $id = (int) $review->ID;
        $items[] = [
            'id' => $id,
            'title' => get_the_title($id),
            'date' => function_exists('get_field') ? get_field('data', $id) : '',
            'subtitle' => function_exists('get_field') ? get_field('pdzagolovok', $id) : '',
            'rating' => function_exists('get_field') ? get_field('rejting', $id) : '',
            'text' => function_exists('get_field') ? get_field('tekst', $id) : '',
        ];
    }

    set_transient($cache_key, $items, 12 * HOUR_IN_SECONDS);

    return $items;
}

function educado_seo_schema_page_template_slugs()
{
    return [
        'template/template-language.php',
        'template-language.php',
        'template/template-rozvod.php',
        'template-rozvod.php',
        'template/template-corporative.php',
        'template-corporative.php',
        'template/template-corporative2.php',
        'template-corporative2.php',
        'template/template-market.php',
        'template-market.php',
    ];
}

function educado_seo_market_template_slugs()
{
    return [
        'template/template-market.php',
        'template-market.php',
    ];
}

function educado_seo_is_schema_page()
{
    if (!is_singular('page')) {
        return false;
    }

    $post_id = get_queried_object_id();
    $template = get_page_template_slug($post_id);

    if (in_array($template, educado_seo_schema_page_template_slugs(), true)) {
        return true;
    }

    if (!function_exists('get_fields')) {
        return false;
    }

    $fields = get_fields($post_id);
    if (!is_array($fields)) {
        return false;
    }

    return !empty($fields['section_1_zagolovok'])
        || !empty($fields['zagolovok_v_gapku'])
        || !empty($fields['perelk_propozicj']);
}

function educado_seo_is_market_schema_page($post_id, $fields)
{
    $template = get_page_template_slug($post_id);

    return in_array($template, educado_seo_market_template_slugs(), true)
        || !empty($fields['perelk_propozicj']);
}

function educado_seo_extract_price($value)
{
    $text = educado_seo_clean_text($value);

    if ($text === '') {
        return null;
    }

    $text = preg_replace('/[^\d,.\s]/u', ' ', $text);
    if (!preg_match('/\d[\d\s]*(?:[,.]\d+)?/u', $text, $match)) {
        return null;
    }

    $number = str_replace(["\xc2\xa0", ' '], '', $match[0]);
    $number = str_replace(',', '.', $number);
    $price = (float) $number;

    return $price > 0 ? $price : null;
}

function educado_seo_collect_prices($fields)
{
    $prices = [];

    if (!empty($fields['cni']) && is_array($fields['cni'])) {
        foreach ($fields['cni'] as $item) {
            $price = educado_seo_extract_price($item['cna'] ?? '');
            if ($price !== null) {
                $prices[] = $price;
            }
        }
    }

    if (!empty($fields['taba']) && is_array($fields['taba'])) {
        foreach ($fields['taba'] as $tab) {
            if (empty($tab['kontent_tabi']) || !is_array($tab['kontent_tabi'])) {
                continue;
            }

            foreach ($tab['kontent_tabi'] as $item) {
                $price = educado_seo_extract_price($item['cna'] ?? '');
                if ($price !== null) {
                    $prices[] = $price;
                }
            }
        }
    }

    $prices = array_values(array_unique($prices));
    sort($prices);

    return $prices;
}

function educado_seo_normalize_date($value)
{
    $value = educado_seo_clean_text($value);

    if ($value === '') {
        return '';
    }

    foreach (['d/m/Y', 'd.m.Y', 'Y-m-d'] as $format) {
        $date = DateTime::createFromFormat($format, $value);
        if ($date instanceof DateTime) {
            return $date->format('Y-m-d');
        }
    }

    return '';
}

function educado_seo_build_teacher_schema($teacher_ids, $organization_id)
{
    $teachers = [];

    foreach ((array) $teacher_ids as $teacher_id) {
        if ($teacher_id instanceof WP_Post) {
            $teacher_id = $teacher_id->ID;
        }

        $teacher_id = (int) $teacher_id;
        if (!$teacher_id) {
            continue;
        }

        $name = educado_seo_clean_text(get_the_title($teacher_id));
        if ($name === '') {
            continue;
        }

        $slug = sanitize_title($name);
        if ($slug === '') {
            $slug = 'teacher-' . $teacher_id;
        }

        $job_title = educado_seo_clean_text(function_exists('get_field') ? get_field('mova', $teacher_id) : '');
        $person = [
            '@type' => 'Person',
            '@id' => home_url('/#' . $slug),
            'name' => $name,
            'worksFor' => [
                '@id' => $organization_id,
            ],
        ];

        if ($job_title !== '') {
            $person['jobTitle'] = $job_title;
        }

        $photo_id = get_post_thumbnail_id($teacher_id);
        if ($photo_id) {
            $photo = wp_get_attachment_image_url($photo_id, 'large');
            if ($photo) {
                $person['image'] = $photo;
            }
        }

        $teachers[] = $person;
    }

    return $teachers;
}

function educado_seo_build_reviews_schema($course_id)
{
    $schema_reviews = [];
    $ratings = [];

    foreach (educado_reviews_items() as $review) {
        $rating = (float) str_replace(',', '.', (string) ($review['rating'] ?? ''));
        if ($rating <= 0) {
            continue;
        }

        $rating = min(5, max(1, $rating));
        $ratings[] = $rating;
        $review_id = (int) ($review['id'] ?? 0);

        $review_node = [
            '@type' => 'Review',
            '@id' => $course_id . '-review-' . $review_id,
            'itemReviewed' => [
                '@id' => $course_id,
            ],
            'author' => [
                '@type' => 'Person',
                'name' => educado_seo_clean_text($review['title'] ?? ''),
            ],
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => $rating,
                'bestRating' => 5,
                'worstRating' => 1,
            ],
        ];

        $body = educado_seo_clean_text($review['text'] ?? '');
        if ($body !== '') {
            $review_node['reviewBody'] = $body;
        }

        $date = educado_seo_normalize_date($review['date'] ?? '');
        if ($date !== '') {
            $review_node['datePublished'] = $date;
        }

        $schema_reviews[] = $review_node;
    }

    if (!$ratings) {
        return [$schema_reviews, null];
    }

    $aggregate = [
        '@type' => 'AggregateRating',
        'ratingValue' => round(array_sum($ratings) / count($ratings), 1),
        'reviewCount' => count($ratings),
        'bestRating' => 5,
        'worstRating' => 1,
    ];

    return [$schema_reviews, $aggregate];
}

function educado_seo_build_faq_schema($faq_items, $page_url)
{
    $entities = [];

    foreach ((array) $faq_items as $item) {
        $question = educado_seo_clean_text($item['pitannya'] ?? '');
        $answer = educado_seo_clean_text($item['vdpovd'] ?? '');

        if ($question === '' || $answer === '') {
            continue;
        }

        $entities[] = [
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $answer,
            ],
        ];
    }

    if (!$entities) {
        return null;
    }

    return [
        '@type' => 'FAQPage',
        '@id' => trailingslashit($page_url) . '#faq',
        'mainEntity' => $entities,
    ];
}

function educado_seo_image_url($image)
{
    if (is_array($image)) {
        if (!empty($image['url'])) {
            return esc_url_raw($image['url']);
        }

        if (!empty($image['ID'])) {
            $url = wp_get_attachment_image_url((int) $image['ID'], 'large');
            return $url ? esc_url_raw($url) : '';
        }

        if (!empty($image['id'])) {
            $url = wp_get_attachment_image_url((int) $image['id'], 'large');
            return $url ? esc_url_raw($url) : '';
        }

        return '';
    }

    if (is_numeric($image)) {
        $url = wp_get_attachment_image_url((int) $image, 'large');
        return $url ? esc_url_raw($url) : '';
    }

    $image = trim((string) $image);
    if ($image === '' || !preg_match('#^https?://#i', $image)) {
        return '';
    }

    return esc_url_raw($image);
}

function educado_seo_offer_schema($price_value, $url)
{
    $price = educado_seo_extract_price($price_value);
    if ($price === null) {
        return null;
    }

    return [
        '@type' => 'Offer',
        'url' => $url,
        'priceCurrency' => 'UAH',
        'price' => $price,
        'availability' => 'https://schema.org/InStock',
    ];
}

function educado_seo_build_market_products_schema($fields, $page_url, $organization_id)
{
    $products = [];
    $list_items = [];
    $position = 1;
    $page_description = educado_seo_clean_text($fields['pdzagolovok_v_shapku'] ?? '');

    foreach ((array) ($fields['perelk_propozicj'] ?? []) as $proposal_group) {
        $category = educado_seo_clean_text($proposal_group['zagolovok_propozic'] ?? '');

        foreach ((array) ($proposal_group['propozic_po_rvnyam'] ?? []) as $level_group) {
            $level = educado_seo_clean_text($level_group['zagolovok_z_zaznachennyam_rvnya'] ?? '');

            foreach ((array) ($level_group['perelk_samih_propozicj'] ?? []) as $offer_item) {
                $theme = educado_seo_clean_text($offer_item['tematika'] ?? '');
                $name_parts = array_values(array_unique(array_filter([$theme, $level])));
                $name = $name_parts ? implode(' - ', $name_parts) : $category;

                if ($name === '') {
                    $name = educado_seo_clean_text($fields['zagolovok_v_gapku'] ?? '');
                }

                if ($name === '') {
                    continue;
                }

                $product_id = trailingslashit($page_url) . '#product-' . $position;
                $product_url = esc_url_raw($offer_item['posilannya_na_magazin'] ?? '');
                if ($product_url === '') {
                    $product_url = $page_url;
                }

                $description = educado_seo_clean_text($offer_item['opis_propozic'] ?? '');
                if ($description === '') {
                    $description = $page_description;
                }

                $product = [
                    '@type' => 'Product',
                    '@id' => $product_id,
                    'name' => $name,
                    'url' => $product_url,
                    'brand' => [
                        '@type' => 'Organization',
                        '@id' => $organization_id,
                        'name' => 'EDUCADO',
                        'url' => home_url('/'),
                    ],
                ];

                if ($description !== '') {
                    $product['description'] = $description;
                }

                $image = educado_seo_image_url($offer_item['zobrazhennya'] ?? '');
                if ($image !== '') {
                    $product['image'] = $image;
                }

                $product_category = implode(' / ', array_values(array_filter([$category, $level])));
                if ($product_category !== '') {
                    $product['category'] = $product_category;
                }

                $offer = educado_seo_offer_schema($offer_item['cna'] ?? '', $product_url);
                if ($offer) {
                    $product['offers'] = $offer;
                }

                $products[] = $product;
                $list_items[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'url' => $product_url,
                    'item' => [
                        '@id' => $product_id,
                    ],
                ];

                $position++;
            }
        }
    }

    return [$products, $list_items];
}

function educado_seo_build_market_page_schema($post_id, $fields)
{
    $page_url = get_permalink($post_id);
    $organization_id = home_url('/#organization');
    $name = educado_seo_clean_text($fields['zagolovok_v_gapku'] ?? '');

    if ($name === '') {
        $name = educado_seo_clean_text(get_the_title($post_id));
    }

    $description = educado_seo_clean_text($fields['pdzagolovok_v_shapku'] ?? '');
    if ($description === '') {
        $description = educado_seo_clean_text(get_post_meta($post_id, '_yoast_wpseo_metadesc', true));
    }

    [$products, $list_items] = educado_seo_build_market_products_schema($fields, $page_url, $organization_id);

    $collection_page = [
        '@type' => 'CollectionPage',
        '@id' => trailingslashit($page_url) . '#webpage',
        'url' => $page_url,
        'name' => $name,
        'inLanguage' => get_bloginfo('language'),
        'publisher' => [
            '@type' => 'Organization',
            '@id' => $organization_id,
            'name' => 'EDUCADO',
            'url' => home_url('/'),
        ],
    ];

    if ($description !== '') {
        $collection_page['description'] = $description;
    }

    $graph = [$collection_page];

    if ($list_items) {
        $graph[] = [
            '@type' => 'ItemList',
            '@id' => trailingslashit($page_url) . '#products',
            'name' => $name,
            'itemListElement' => $list_items,
        ];
    }

    foreach ($products as $product) {
        $graph[] = $product;
    }

    return [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];
}

function educado_seo_build_language_page_schema($post_id)
{
    if (!function_exists('get_fields')) {
        return null;
    }

    $fields = get_fields($post_id);
    if (!is_array($fields)) {
        return null;
    }

    $page_url = get_permalink($post_id);
    $organization_id = home_url('/#organization');
    $course_id = trailingslashit($page_url) . '#course';
    $name = educado_seo_clean_text($fields['section_1_zagolovok'] ?? '');

    if ($name === '') {
        $name = educado_seo_clean_text(get_the_title($post_id));
    }

    $description = educado_seo_clean_text($fields['section_1_pdzagolovok'] ?? '');
    if ($description === '') {
        $description = educado_seo_clean_text(get_post_meta($post_id, '_yoast_wpseo_metadesc', true));
    }

    if ($description === '') {
        $description = $name;
    }

    $course = [
        '@type' => 'Course',
        '@id' => $course_id,
        'name' => $name,
        'description' => $description,
        'url' => $page_url,
        'inLanguage' => get_bloginfo('language'),
        'provider' => [
            '@type' => 'Organization',
            '@id' => $organization_id,
            'name' => 'EDUCADO',
            'url' => home_url('/'),
        ],
    ];

    $prices = educado_seo_collect_prices($fields);
    if (count($prices) === 1) {
        $course['offers'] = [
            '@type' => 'Offer',
            'url' => $page_url,
            'priceCurrency' => 'UAH',
            'price' => $prices[0],
            'availability' => 'https://schema.org/InStock',
        ];
    } elseif (count($prices) > 1) {
        $course['offers'] = [
            '@type' => 'AggregateOffer',
            'url' => $page_url,
            'priceCurrency' => 'UAH',
            'lowPrice' => min($prices),
            'highPrice' => max($prices),
            'offerCount' => count($prices),
            'availability' => 'https://schema.org/InStock',
        ];
    }

    $teachers = educado_seo_build_teacher_schema($fields['teachers_vikladach'] ?? [], $organization_id);
    if ($teachers) {
        $course['hasCourseInstance'] = [
            '@type' => 'CourseInstance',
            '@id' => $course_id . '-instance',
            'courseMode' => 'online',
            'instructor' => array_map(function ($teacher) {
                return ['@id' => $teacher['@id']];
            }, $teachers),
        ];
    }

    [$reviews, $aggregate_rating] = educado_seo_build_reviews_schema($course_id);
    if ($aggregate_rating) {
        $course['aggregateRating'] = $aggregate_rating;
    }

    if ($reviews) {
        $course['review'] = array_map(function ($review) {
            return ['@id' => $review['@id']];
        }, $reviews);
    }

    $graph = [$course];
    foreach ($teachers as $teacher) {
        $graph[] = $teacher;
    }
    foreach ($reviews as $review) {
        $graph[] = $review;
    }

    $faq = educado_seo_build_faq_schema($fields['pitannya_vdpovd_bloku'] ?? [], $page_url);
    if ($faq) {
        $graph[] = $faq;
    }

    return [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];
}

function educado_seo_build_page_schema($post_id)
{
    if (!function_exists('get_fields')) {
        return null;
    }

    $fields = get_fields($post_id);
    if (!is_array($fields)) {
        return null;
    }

    if (educado_seo_is_market_schema_page($post_id, $fields)) {
        return educado_seo_build_market_page_schema($post_id, $fields);
    }

    return educado_seo_build_language_page_schema($post_id);
}

add_action('wp_head', function () {
    if (!educado_seo_is_schema_page()) {
        return;
    }

    $schema = educado_seo_build_page_schema(get_queried_object_id());
    if (!$schema) {
        return;
    }

    echo "\n" . '<script type="application/ld+json" class="educado-schema">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}, 30);
