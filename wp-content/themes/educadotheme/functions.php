<?php
/**
 * Functions and definitions
 */


/**
 * Setup theme
 */
require_once get_template_directory() . '/inc/setup.php';


/**
 * Include additional file with custom post-type
 */
require_once get_template_directory() . '/inc/post-type.php';


/**
 * Enqueue scripts and styles.
 */
require_once get_template_directory() . '/inc/enqueue.php';


/**
 * Additional WP functions
 */
require_once get_template_directory() . '/inc/wp-func.php';

