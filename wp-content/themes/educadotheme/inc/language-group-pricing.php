<?php
/** Separate group offer: never rewrites the existing 8/24/48 price matrix. */
function educado_group_offer_errors($value) {
    $errors = [];
    foreach (['module_price', 'course_price'] as $name) {
        $price = $value[$name] ?? '';
        if (!is_scalar($price) || !preg_match('/^\d{1,7}(\.\d{1,2})?$/D', (string) $price) || (float) $price <= 0) {
            $errors[] = 'Заповніть додатні ціни модуля та всього курсу.';
        }
    }
    $previous = $value['previous_price'] ?? '';
    if ($previous !== '' && $previous !== null && (!is_scalar($previous) || !preg_match('/^\d{1,7}(\.\d{1,2})?$/D', (string) $previous) || (float) $previous <= (float) ($value['course_price'] ?? 0))) {
        $errors[] = 'Ціна до знижки має бути більшою за ціну всього курсу або залиште поле порожнім.';
    }
    foreach (['condition_lessons', 'condition_students', 'condition_schedule', 'module_title', 'course_title'] as $name) {
        if (trim((string) ($value[$name] ?? '')) === '') $errors[] = 'Заповніть умови занять і назви двох пакетів.';
    }
    return array_values(array_unique($errors));
}

/** ACF submits field keys, while get_field returns field names (including repeater rows). */
function educado_group_normalize($value) {
    $normalized = [];
    foreach ((array) $value as $key => $item) {
        $name = preg_replace('/^field_edg_(?:level_)?/', '', (string) $key);
        $normalized[$name] = is_array($item) ? educado_group_normalize($item) : $item;
    }
    return $normalized;
}

function educado_group_pricing_model($value, $pricing) {
    $value = is_array($value) ? $value : [];
    $model = ['ready' => false, 'format_ref' => (string) ($value['format_ref'] ?? ''), 'errors' => [], 'offers' => [], 'has_levels' => false];
    if (empty($value['enabled'])) return $model;
    $formats = educado_pricing_model($pricing)['options']['format'] ?? [];
    if (!isset($formats[$model['format_ref']])) $model['errors'][] = 'Оберіть активний формат занять для групового макета.';
    $levels = is_array($value['levels'] ?? null) ? array_values($value['levels']) : [];
    $model['has_levels'] = (bool) $levels;
    $labels = [];
    foreach ($levels as $index => $level) {
        $level = is_array($level) ? $level : [];
        $offer = $value;
        // Texts may inherit shared conditions; prices always belong to this level.
        foreach ($level as $name => $item) {
            if (in_array($name, ['module_price', 'course_price', 'previous_price', 'label'], true) || trim((string) $item) !== '') $offer[$name] = $item;
        }
        foreach (['module_price', 'course_price', 'previous_price'] as $name) $offer[$name] = $level[$name] ?? '';
        $label = trim((string) ($level['label'] ?? ''));
        $errors = educado_group_offer_errors($offer);
        if ($label === '' || in_array($label, $labels, true)) $errors[] = 'Вкажіть непорожню унікальну назву рівня.';
        $labels[] = $label;
        foreach ($errors as $error) $model['errors'][] = 'Рівень ' . ($index + 1) . ': ' . $error;
        if (!$errors) $model['offers'][] = $offer;
    }
    if (!$levels) {
        $model['errors'] = array_merge($model['errors'], educado_group_offer_errors($value));
        if (!$model['errors']) $model['offers'][] = $value;
    }
    $model['errors'] = array_values(array_unique($model['errors']));
    $model['ready'] = !$model['errors'] && (bool) $model['offers'];
    return $model;
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;
    $enabled = [[['field' => 'field_edg_enabled', 'operator' => '==', 'value' => '1']]];
    $fields = [
        ['key' => 'field_edg_enabled', 'name' => 'enabled', 'label' => 'Окремий макет для групових занять', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'instructions' => 'Вмикайте після заповнення умов і двох цін. Наявні ціни 8/24/48 уроків зберігаються окремо та не змінюються.'],
        ['key' => 'field_edg_format_ref', 'name' => 'format_ref', 'label' => 'Який формат відкриває груповий макет', 'type' => 'select', 'choices' => [], 'allow_null' => 1, 'conditional_logic' => $enabled, 'instructions' => 'Додайте «Групові» до списку форматів у Ціни v2 та оберіть його тут. Прив’язка зберігається при перейменуванні. Для цього формату не потрібно створювати комбінації 8/24/48.'],
    ];
    foreach ([
        'condition_lessons' => ['Тривалість і кількість уроків у модулі', '16 уроків по 60 хв у модулі'],
        'condition_students' => ['Розмір групи', 'Міні-група 3–8 студентів*'],
        'condition_schedule' => ['Розклад занять', '2 уроки на тиждень'],
        'module_title' => ['Назва пакета «Модуль»', '1 модуль · 16 уроків'],
        'module_note' => ['Пояснення під ціною модуля', 'Оплата за кожен модуль окремо'],
        'course_title' => ['Назва пакета «Весь курс»', 'Весь курс · 3 модулі, 48 уроків'],
        'course_note' => ['Пояснення під ціною всього курсу', ''],
        'trial_note' => ['Текст про пробний урок', 'Перший пробний урок безкоштовний.'],
        'footnote' => ['Примітка про старт групи', '* Група стартує, коли набирається щонайменше 3 студенти.'],
    ] as $name => $copy) {
        $fields[] = ['key' => 'field_edg_' . $name, 'name' => $name, 'label' => $copy[0], 'type' => 'text', 'default_value' => $copy[1], 'conditional_logic' => $enabled];
    }
    foreach (['module_price' => 'Ціна одного модуля — грн', 'course_price' => 'Ціна всього курсу — грн', 'previous_price' => 'Ціна всього курсу до знижки — грн (необов’язково)'] as $name => $label) {
        $fields[] = ['key' => 'field_edg_' . $name, 'name' => $name, 'label' => $label, 'type' => 'number', 'min' => 0.01, 'max' => 9999999.99, 'step' => '0.01', 'conditional_logic' => $enabled];
    }
    $level_fields = [['key' => 'field_edg_level_label', 'name' => 'label', 'label' => 'Назва рівня', 'type' => 'text', 'required' => 1, 'instructions' => 'Наприклад: Початковий, Просунутий, A1 або HSK 2.']];
    foreach ($fields as $field) {
        if (in_array($field['name'], ['enabled', 'format_ref'], true)) continue;
        $field['key'] = 'field_edg_level_' . $field['name'];
        unset($field['conditional_logic'], $field['default_value']);
        $field['required'] = in_array($field['name'], ['module_price', 'course_price'], true) ? 1 : 0;
        $field['instructions'] = $field['type'] === 'text' ? 'Необов’язково. Порожнє поле використовує спільний текст вище.' : '';
        $level_fields[] = $field;
    }
    $fields[] = ['key' => 'field_edg_levels', 'name' => 'levels', 'label' => 'Рівні групових занять', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Додати рівень', 'collapsed' => 'field_edg_level_label', 'sub_fields' => $level_fields, 'conditional_logic' => $enabled,
        'instructions' => 'Необов’язково. Без рядків показуються спільні ціни вище. Додайте всі потрібні рівні та окремі ціни модуля/курсу для кожного. Тексти можна успадкувати зі спільних полів. Звичайні ціни 8/24/48 не змінюються.'];
    acf_add_local_field_group(['key' => 'group_educado_group_pricing', 'title' => 'Ціни v2 — умови групових занять', 'menu_order' => -9, 'position' => 'acf_after_title',
        'fields' => [['key' => 'field_edg_settings', 'name' => 'pricing_group', 'label' => 'Групові заняття', 'type' => 'group', 'layout' => 'block', 'sub_fields' => $fields]],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'template/template-language.php']]]]);
});

add_filter('acf/load_field/key=field_edg_format_ref', function ($field) {
    $post_id = isset($_GET['post']) ? absint($_GET['post']) : get_the_ID();
    $count = (int) get_post_meta($post_id, 'pricing_v2_format_options', true);
    for ($i = 0; $i < $count; $i++) {
        $base = 'pricing_v2_format_options_' . $i . '_format';
        $id = get_post_meta($post_id, $base . '_id', true);
        if ($id) $field['choices'][$id] = get_post_meta($post_id, $base . '_label', true);
    }
    return $field;
});

add_filter('acf/validate_value/key=field_edg_settings', function ($valid, $value) {
    if ($valid !== true || !is_array($value) || empty($value['field_edg_enabled'])) return $valid;
    $normalized = educado_group_normalize($value);
    $pricing = $_POST['acf']['field_edp_pricing_v2'] ?? [];
    $model = educado_group_pricing_model($normalized, $pricing);
    return $model['errors'] ? implode(' ', $model['errors']) : true;
}, 10, 2);
