<?php
add_action('wp_enqueue_scripts', 'ed_scripts');
function ed_scripts()
{
    $ver = '1.0.40';
    /* Style */
    wp_enqueue_style('ed-main', get_template_directory_uri() . '/assets/css/style.min.css', array(), $ver);

    /* Scripts */
    wp_enqueue_script('ed-main', get_template_directory_uri() . '/assets/js/main.min.js', array(), $ver, true);
}
