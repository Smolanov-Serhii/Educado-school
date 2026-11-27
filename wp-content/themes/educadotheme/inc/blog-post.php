<?php

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_educado_blog_faq',
        'title' => 'FAQ статті',
        'fields' => [[
            'key' => 'field_educado_blog_faq',
            'name' => 'educado_blog_faq',
            'label' => 'Часті питання',
            'type' => 'repeater',
            'layout' => 'block',
            'collapsed' => 'field_educado_blog_faq_question',
            'button_label' => 'Додати питання',
            'sub_fields' => [
                ['key' => 'field_educado_blog_faq_question', 'name' => 'question', 'label' => 'Питання', 'type' => 'text', 'required' => 1],
                ['key' => 'field_educado_blog_faq_answer', 'name' => 'answer', 'label' => 'Відповідь', 'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0, 'required' => 1],
            ],
        ]],
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'post']]],
    ]);

    // Register the new group independently, then attach it to the existing repeater at runtime.
    acf_add_local_fields([[
        'key' => 'field_educado_blog_table',
        'name' => 'shablon_table_4',
        'label' => 'Таблиця (шаблон 4)',
        'type' => 'group',
        'layout' => 'block',
        'conditional_logic' => [[['field' => 'field_695f4dde5f895', 'operator' => '==', 'value' => 'template4']]],
        'sub_fields' => [
            ['key' => 'field_educado_blog_table_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
            [
                'key' => 'field_educado_blog_table_content',
                'name' => 'content',
                'label' => 'Таблиця',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'required' => 1,
                'instructions' => 'Вставте таблицю у вкладці «Візуально» або додайте її HTML у вкладці «Код».',
            ],
        ],
    ]]);
});

add_filter('acf/load_field/key=field_695f4dde5f895', function ($field) {
    $field['choices']['template4'] = 'Таблиця (шаблон 4)';
    return $field;
});

add_filter('acf/load_field/key=field_695f4d10597de', function ($field) {
    $table = acf_get_field('field_educado_blog_table');
    if ($table && !in_array($table['key'], array_column($field['sub_fields'], 'key'), true)) {
        $table['parent_repeater'] = $field['key'];
        $table['parent'] = $field['ID'];
        $field['sub_fields'][] = $table;
    }
    return $field;
});

add_action('wp_enqueue_scripts', function () {
    if (!is_singular('post')) {
        return;
    }

    $css = '/assets/css/blog-post.css';
    $js = '/assets/js/libraries/blog-post.js';
    wp_enqueue_style('ed-blog-post', get_theme_file_uri($css), ['ed-main'], educado_asset_version(get_theme_file_path($css)));
    wp_enqueue_script('ed-blog-post', get_theme_file_uri($js), [], educado_asset_version(get_theme_file_path($js)), true);
}, 25);

function educado_blog_faq_rows($rows) {
    $faq = [];
    foreach ((array) $rows as $row) {
        $question = trim(wp_strip_all_tags($row['question'] ?? ''));
        $answer = wp_kses_post($row['answer'] ?? '');
        $answer_text = html_entity_decode(wp_strip_all_tags($answer), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($question !== '' && preg_match('/[^\s\p{Z}]/u', $answer_text)) {
            $faq[] = ['question' => $question, 'answer' => $answer];
        }
    }

    return $faq;
}

function educado_blog_remove_duplicate_title($html, $title) {
    if (!preg_match('~^\s*<h1\b[^>]*>(.*?)</h1\s*>~is', $html, $heading)) {
        return $html;
    }

    $normalize = static function ($value) {
        $value = html_entity_decode(wp_strip_all_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = str_replace(['’', 'ʼ'], "'", $value);
        return trim(preg_replace('/[\s\p{Z}]+/u', ' ', $value));
    };
    if ($normalize($heading[1]) !== $normalize($title)) {
        return $html;
    }

    $tag = new WP_HTML_Tag_Processor($heading[0]);
    $tag->next_tag('H1');
    $id = $tag->get_attribute('id');
    $anchor = is_string($id) && $id !== '' ? '<span id="' . esc_attr($id) . '"></span>' : '';
    return $anchor . substr($html, strlen($heading[0]));
}
