<?php
/**
 * Registration post_type teachers
 */
add_action('init', 'teachers_init');
function teachers_init(){
    register_post_type('teachers', array(
        'labels'             => array(
            'name'               => __( 'Викладачі', 'ed' ),
            'singular_name'      => __( 'Викладач', 'ed' ),
            'add_new'            => _x( 'Додати нового', 'ed' ),
            'add_new_item'       => _x( 'Додати нового викладача', 'ed' ),
            'edit_item'          => _x( 'Редагувати викладача', 'ed' ),
            'new_item'           => _x( 'Новий викладач', 'ed' ),
            'view_item'          => _x( 'Переглянути викладача', 'ed' ),
            'search_items'       => _x( 'Пошук викладачів', 'ed' ),
            'not_found'          => _x( 'Не знайдено', 'ed' ),
            'not_found_in_trash' => _x( 'В кошику не знайдено', 'ed' ),
            'parent_item_colon'  => ''

        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'thumbnail', 'page-attributes')
    ) );
    flush_rewrite_rules();
}


/**
 * Registration post_type courses
 */
add_action('init', 'courses_init');
function courses_init(){
    register_post_type('courses', array(
        'labels'             => array(
            'name'               => __( 'Курси', 'ed' ),
            'singular_name'      => __( 'Курс', 'ed' ),
            'add_new'            => _x( 'Додати новий', 'ed' ),
            'add_new_item'       => _x( 'Додати новий курс', 'ed' ),
            'edit_item'          => _x( 'Редагувати курс', 'ed' ),
            'new_item'           => _x( 'Новий курс', 'ed' ),
            'view_item'          => _x( 'Переглянути курс', 'ed' ),
            'search_items'       => _x( 'Пошук курсів', 'ed' ),
            'not_found'          => _x( 'Не знайдено', 'ed' ),
            'not_found_in_trash' => _x( 'В кошику не знайдено', 'ed' ),
            'parent_item_colon'  => ''

        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array('title', 'page-attributes')
    ) );
    flush_rewrite_rules();
}


/**
 * Registration post_type reviews
 */
add_action('init', 'reviews_init');
function reviews_init(){
    register_post_type('reviews', array(
        'labels'             => array(
            'name'               => __( 'Відгуки', 'ed' ),
            'singular_name'      => __( 'Відгук', 'ed' ),
            'add_new'            => _x( 'Додати новий', 'ed' ),
            'add_new_item'       => _x( 'Додати новий відгук', 'ed' ),
            'edit_item'          => _x( 'Редагувати відгук', 'ed' ),
            'new_item'           => _x( 'Новий відгук', 'ed' ),
            'view_item'          => _x( 'Переглянути відгук', 'ed' ),
            'search_items'       => _x( 'Пошук відгуків', 'ed' ),
            'not_found'          => _x( 'Не знайдено', 'ed' ),
            'not_found_in_trash' => _x( 'В кошику не знайдено', 'ed' ),
            'parent_item_colon'  => ''

        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_icon'          => 'dashicons-testimonial',
        'supports'           => array('title', 'page-attributes')
    ) );
    flush_rewrite_rules();
}
