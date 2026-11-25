<?php
/** Shared store presentation; existing product repeaters remain authoritative. */
function educado_market_groups($value) {
    $groups = [];
    foreach (is_array($value) ? $value : [] as $index => $group) {
        $cards = [];
        foreach (($group['propozic_po_rvnyam'] ?? []) ?: [] as $level) {
            $items = ($level['perelk_samih_propozicj'] ?? []) ?: [];
            foreach ($items as $item) {
                $label = trim((string) ($level['zagolovok_z_zaznachennyam_rvnya'] ?? ''));
                if (count($items) > 1 && !empty($item['tematika'])) $label .= ' · ' . wp_strip_all_tags($item['tematika']);
                $cards[] = ['label' => $label, 'item' => $item];
            }
        }
        if ($cards) $groups[] = ['id' => 'market-group-' . ($index + 1), 'title' => $group['zagolovok_propozic'] ?? '', 'cards' => $cards];
    }
    return $groups;
}

function educado_market_heading($value) {
    $plain = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags(str_replace(['<br>', '<br/>', '<br />'], ' ', $value))));
    if (preg_match('/^(.*?)\s+з\s+(.+?)\s+онлайн$/u', $plain, $parts)) {
        return esc_html($parts[1]) . '<br> з <mark>' . esc_html($parts[2]) . '</mark> онлайн';
    }
    return wp_kses_post($value);
}

add_action('wp_enqueue_scripts', function () {
    if (!is_page_template('template/template-market.php')) return;
    foreach (['css' => '/assets/css/market.min.css', 'js' => '/assets/js/libraries/market.js'] as $type => $path) {
        $version = educado_asset_version(get_template_directory() . $path);
        if ($type === 'css') wp_enqueue_style('educado-market', get_template_directory_uri() . $path, ['ed-main'], $version);
        else wp_enqueue_script('educado-market', get_template_directory_uri() . $path, ['ed-main'], $version, true);
    }
}, 25);

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;
    $text = function ($name, $label, $default = '') {
        return ['key' => 'field_edm_' . $name, 'name' => $name, 'label' => $label, 'type' => 'text', 'default_value' => $default];
    };
    $form_fields = [];
    foreach ([
        'heading' => ['Заголовок форми', 'Хочеш навчатися з вчителем?'],
        'subtitle' => ['Текст під заголовком', 'Залиш заявку — ми зв’яжемося з тобою та підберемо найкращого вчителя саме під твою мету.'],
        'name_placeholder' => ['Підказка в полі імені', 'Ваше ім’я'],
        'phone_placeholder' => ['Підказка в полі телефону', 'Введіть номер телефону'],
        'button' => ['Текст кнопки', 'Залишити заявку'],
        'agreement_text' => ['Текст перед посиланням на політику (позначка {кнопка} підставляє текст кнопки)', 'Натискаючи кнопку «{кнопка}», я приймаю'],
        'agreement_label' => ['Текст посилання на політику (порожньо — спільний текст сайту)', ''],
        'lead_title' => ['Назва заявки для менеджера', 'Запит із магазину курсів'],
    ] as $name => $copy) $form_fields[] = $text('form_' . $name, $copy[0], $copy[1]);
    // Keep short names inside the separate form group.
    foreach ($form_fields as &$field) $field['name'] = substr($field['name'], 5);
    unset($field);
    acf_add_local_field_group([
        'key' => 'group_educado_market', 'title' => 'Магазин — навігація, FAQ і форма',
        'fields' => [
            $text('nav_label', 'Підпис перед категоріями', 'Обирай курс за потребою:'),
            $text('details_label', 'Текст кнопки курсу', 'Детальніше'),
            $text('faq_heading', 'Заголовок FAQ', 'Часті питання'),
            ['key' => 'field_edm_faq', 'name' => 'market_faq', 'label' => 'Питання та відповіді', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Додати питання', 'sub_fields' => [
                ['key' => 'field_edm_faq_question', 'name' => 'question', 'label' => 'Питання', 'type' => 'text', 'required' => 1],
                ['key' => 'field_edm_faq_answer', 'name' => 'answer', 'label' => 'Відповідь', 'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0, 'required' => 1],
            ]],
            ['key' => 'field_edm_form', 'name' => 'market_form', 'label' => 'Форма як на головній — тексти для цієї сторінки', 'type' => 'group', 'layout' => 'block', 'sub_fields' => $form_fields,
                'instructions' => 'Той самий обробник, ім’я, телефон і політика, що на головній. Ці тексти змінюються лише в поточному магазині. Текст і посилання політики залишаються у спільних налаштуваннях сайту.'],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'template/template-market.php']]],
    ]);
});
