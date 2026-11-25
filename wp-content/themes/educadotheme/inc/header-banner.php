<?php

function educado_banner_text_color(string $color): string
{
    $hex = substr($color, 1);
    if (strlen($hex) === 3) {
        $hex = preg_replace('/(.)/', '$1$1', $hex);
    }
    $linear = array_map(static function ($channel) {
        $value = hexdec($channel) / 255;
        return $value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
    }, str_split($hex, 2));
    $luminance = 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];

    return $luminance < 0.179 ? '#fff' : '#000';
}

add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_educado_header_banner',
        'title' => 'Вигляд банера',
        'location' => [[['param' => 'page', 'operator' => '==', 'value' => '5']]],
        'fields' => [
            [
                'key' => 'field_educado_banner_layout',
                'name' => 'educado_banner_layout',
                'label' => 'Варіант банера',
                'type' => 'select',
                'choices' => [
                    'classic' => 'Текст на кнопці',
                    'split' => 'Текст і окрема кнопка',
                ],
                'default_value' => 'classic',
            ],
            [
                'key' => 'field_educado_banner_text',
                'name' => 'educado_banner_text',
                'label' => 'Текст банера',
                'type' => 'textarea',
                'rows' => 2,
                'maxlength' => 180,
                'required' => 1,
                'conditional_logic' => [[['field' => 'field_educado_banner_layout', 'operator' => '==', 'value' => 'split']]],
            ],
            [
                'key' => 'field_educado_banner_button',
                'name' => 'educado_banner_button',
                'label' => 'Підпис окремої кнопки',
                'type' => 'text',
                'maxlength' => 36,
                'required' => 1,
                'default_value' => 'Дізнатися більше',
                'conditional_logic' => [[['field' => 'field_educado_banner_layout', 'operator' => '==', 'value' => 'split']]],
            ],
            [
                'key' => 'field_educado_banner_background',
                'name' => 'educado_banner_background',
                'label' => 'Фонове зображення',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'conditional_logic' => [[['field' => 'field_educado_banner_layout', 'operator' => '==', 'value' => 'split']]],
            ],
            [
                'key' => 'field_educado_banner_width',
                'name' => 'educado_banner_width',
                'label' => 'Ширина фону',
                'type' => 'select',
                'choices' => [
                    'full' => 'На всю ширину екрана',
                    'contained' => 'У межах контейнера',
                ],
                'default_value' => 'full',
                'conditional_logic' => [[['field' => 'field_educado_banner_layout', 'operator' => '==', 'value' => 'split']]],
            ],
        ],
    ]);
});

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('get_field') || get_field('educado_banner_layout', 5) !== 'split') {
        return;
    }

    $asset = '/assets/css/header-banner.css';
    wp_enqueue_style('educado-header-banner', get_template_directory_uri() . $asset, ['ed-main'],
        educado_asset_version(get_template_directory() . $asset));
}, 21);
