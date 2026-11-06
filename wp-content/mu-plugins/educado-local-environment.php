<?php
/**
 * Plugin Name: Educado local environment
 * Description: Prevent outbound mail, external HTTP and indexing in the local clone.
 */
if (!defined('ABSPATH') || !defined('WP_ENVIRONMENT_TYPE') || WP_ENVIRONMENT_TYPE !== 'local') {
    return;
}
add_filter('pre_wp_mail', '__return_false', PHP_INT_MAX);
add_filter('pre_http_request', function ($preempt, $args, $url) {
    $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
    if (in_array($host, ['localhost', '127.0.0.1', 'educado-school.loc', 'www.educado-school.loc'], true)) {
        return $preempt;
    }
    return new WP_Error('educado_local_http_blocked', 'External HTTP requests are disabled in the local clone.');
}, PHP_INT_MAX, 3);
add_filter('pre_option_blog_public', function () { return '0'; });
add_filter('wp_robots', function ($robots) {
    $robots['noindex'] = true;
    $robots['nofollow'] = true;
    return $robots;
});
