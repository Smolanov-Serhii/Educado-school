<?php
add_action('after_setup_theme', 'ed_theme_setup');
function ed_theme_setup()
{
    /*
    * Remove unnecessary entries from header
    */
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'wp_resource_hints', 2);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);


    add_theme_support('post-thumbnails');

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        'title-tag',
        array(
            'style',
            'script',
        )
    );

}


/**
 * Create page for common settings
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Параметри теми',
        'menu_title' => 'Параметри теми',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}


/**
 * Remove Gutenberg Block Library CSS from loading on the frontend
 */
add_action('wp_enqueue_scripts', 'ed_remove_wp_default_css', 100);
function ed_remove_wp_default_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}


/**
 * Remove unnecessary link from admin menu
 */
add_action('admin_init', 'ed_remove_menu_pages');
function ed_remove_menu_pages()
{
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('separator2');
}
