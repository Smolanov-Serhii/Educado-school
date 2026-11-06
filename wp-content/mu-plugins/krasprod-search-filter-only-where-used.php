<?php
/**
 * Plugin Name: Krasprod: Search & Filter     
 * Description:    Search & Filter Pro   ,     ( programs-one-lang.php  template-corporative2.php)     (?sfid=).      .  27.09.2026, Krasprod,  . :   .
 */
add_action("wp_enqueue_scripts", function () {
if (is_admin()) { return; }
if (is_page_template(array("programs-one-lang.php", "template/template-corporative2.php")) || isset($_GET["sfid"])) { return; }
wp_dequeue_style("search-filter-plugin-styles");
wp_dequeue_script("search-filter-plugin-build");
wp_dequeue_script("search-filter-plugin-chosen");
}, 100);
