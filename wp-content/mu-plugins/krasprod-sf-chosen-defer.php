<?php
/**
 * Plugin Name: Krasprod: defer для chosen.jquery (Search & Filter)
 * Description: jQuery і скрипт фільтра підключені з defer, а chosen.jquery.min.js без нього, тому він
 *              запускався раніше за jQuery і давав у консолі «jQuery is not defined». Додаємо defer,
 *              порядок виконання тоді правильний. Krasprod, 07.10.2026.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_filter('script_loader_tag', function ($tag, $handle) {
    if ($handle === 'search-filter-plugin-chosen' && strpos($tag, ' defer') === false) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }
    return $tag;
}, 20, 2);
