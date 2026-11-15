<?php
/** Per-page price combinations for the shared language template. */
function educado_pricing_value($row, $name) {
    return $row[$name] ?? $row['field_edp_' . $name] ?? null;
}

function educado_pricing_model($value) {
    $model = ['options' => [], 'prices' => [], 'ready' => false, 'errors' => []];
    $value = is_array($value) ? $value : [];
    foreach (['course', 'duration', 'level'] as $dimension) {
        $options = [];
        foreach ((array) educado_pricing_value($value, $dimension . '_options') as $option) {
            $id = (string) educado_pricing_value($option, $dimension . '_id');
            $label = trim((string) educado_pricing_value($option, $dimension . '_label'));
            if (!preg_match('/^opt_[a-f0-9]{16}$/D', $id) || isset($options[$id]) || $label === '') {
                $model['errors'][] = 'Варіанти повинні мати назви та унікальні ідентифікатори. Скопійований варіант отримує новий ID автоматично.';
                continue;
            }
            $options[$id] = $label;
        }
        $model['options'][$dimension] = $options;
    }
    $seen = [];
    $resolved = 0;
    foreach ((array) educado_pricing_value($value, 'price_rows') as $row) {
        $ids = [];
        $current = true;
        foreach ($model['options'] as $dimension => $options) {
            $id = (string) educado_pricing_value($row, $dimension . '_ref');
            $ids[] = $id;
            $current = $current && isset($options[$id]);
        }
        // Deleted options keep their stored prices for recovery, outside the live matrix.
        if (!$current) {
            continue;
        }
        $key = implode('|', $ids);
        if (isset($seen[$key])) {
            $model['errors'][] = 'Комбінація типу курсу, тривалості та рівня повторюється. Залиште один рядок для кожної комбінації.';
            continue;
        }
        $seen[$key] = true;
        $status = educado_pricing_value($row, 'availability');
        if (!in_array($status, ['draft', 'available', 'unavailable'], true)) {
            $model['errors'][] = 'Оберіть стан кожної комбінації.';
            continue;
        }
        $prices = [];
        foreach ([8, 24, 48] as $size) {
            $price = educado_pricing_value($row, 'price_' . $size);
            $filled = $price !== null && $price !== '';
            $valid = $filled && is_scalar($price) && preg_match('/^\d{1,7}(\.\d{1,2})?$/D', (string) $price) && (float) $price > 0;
            if (($filled && !$valid) || ($status === 'available' && !$valid)) {
                $model['errors'][] = 'Для доступної комбінації заповніть усі три ціни за урок. Ціна має бути більшою за нуль, максимум два знаки після крапки.';
            }
            $prices[$size] = $valid ? (string) $price : null;
        }
        if ($status !== 'draft') {
            $resolved++;
        }
        if ($status === 'available' && !in_array(null, $prices, true)) {
            $model['prices'][$key] = $prices;
        }
    }
    $expected = count($model['options']['course']) * count($model['options']['duration']) * count($model['options']['level']);
    $model['errors'] = array_values(array_unique($model['errors']));
    $model['ready'] = $expected > 0 && $resolved === $expected && $model['prices'] && !$model['errors'];
    return $model;
}

function educado_pricing_v2_visible() {
    if (get_field('pricing_visual') !== 'v2') {
        return false;
    }
    $model = educado_pricing_model(get_field('pricing_v2'));
    if (wp_get_environment_type() === 'local') {
        return true;
    }
    if (isset($_GET['ed_pricing_preview'], $_GET['_wpnonce']) && current_user_can('edit_post', get_the_ID()) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'ed-pricing-' . get_the_ID())) {
        return true;
    }
    return defined('EDUCADO_PRICING_V2_PUBLIC') && EDUCADO_PRICING_V2_PUBLIC && $model['ready'];
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    $fields = [];
    $dimensions = ['course' => 'Типи курсу', 'duration' => 'Тривалість уроку', 'level' => 'Рівні'];
    foreach ($dimensions as $dimension => $label) {
        $fields[] = [
            'key' => 'field_edp_' . $dimension . '_options', 'name' => $dimension . '_options',
            'label' => $label, 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Додати варіант',
            'instructions' => 'Додайте потрібні варіанти для цієї мови. Порядок рядків визначає порядок перемикачів. Після зміни списків створіть відсутні комбінації нижче.',
            'sub_fields' => [
                ['key' => 'field_edp_' . $dimension . '_id', 'name' => $dimension . '_id', 'label' => 'Службовий ID', 'type' => 'text', 'readonly' => 1, 'wrapper' => ['class' => 'edp-option-id']],
                ['key' => 'field_edp_' . $dimension . '_label', 'name' => $dimension . '_label', 'label' => 'Назва для відвідувача', 'type' => 'text', 'required' => 1],
            ],
        ];
    }
    $row_fields = [];
    foreach ($dimensions as $dimension => $label) {
        $row_fields[] = ['key' => 'field_edp_' . $dimension . '_ref', 'name' => $dimension . '_ref', 'label' => $label, 'type' => 'select', 'allow_null' => 1, 'choices' => [], 'placeholder' => 'Оберіть варіант'];
    }
    $row_fields[] = ['key' => 'field_edp_availability', 'name' => 'availability', 'label' => 'Стан', 'type' => 'select', 'choices' => ['draft' => 'Чернетка', 'available' => 'Ціни заповнено', 'unavailable' => 'Не пропонуємо'], 'default_value' => 'draft'];
    foreach ([8, 24, 48] as $size) {
        $row_fields[] = ['key' => 'field_edp_price_' . $size, 'name' => 'price_' . $size, 'label' => ($size === 24 ? '24 уроки' : $size . ' уроків') . ' — грн за урок', 'type' => 'number', 'min' => 0.01, 'max' => 9999999.99, 'step' => '0.01'];
    }
    $fields[] = ['key' => 'field_edp_price_rows', 'name' => 'price_rows', 'label' => 'Комбінації та ціни', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Додати комбінацію вручну', 'sub_fields' => $row_fields,
        'instructions' => 'У кожному рядку — один тип курсу, одна тривалість і один рівень. Введіть ціну ОДНОГО уроку в кожному пакеті. Переведіть готові рядки у стан «Ціни заповнено». Чернетки можна зберігати частково.'];
    foreach ([
        'heading' => ['Заголовок блоку', 'Ціна'],
        'note_8' => ['Підпис пакета 8 уроків', 'Старт без зобов’язань'],
        'note_24' => ['Підпис пакета 24 уроки', 'Можна розтермінувати'],
        'note_48' => ['Підпис пакета 48 уроків', 'Можна розтермінувати'],
        'badge' => ['Позначка пакета 48 уроків', 'Найвигідніше'],
        'installments' => ['Текст про розтермінування', 'Розтермінування від банків та школи для пакетів від 24 уроків'],
        'bonus' => ['Додаткове пояснення', 'Про додатковий бонус дізнайся у менеджера.'],
        'button' => ['Текст кнопки', 'Хочу на безкоштовний урок'],
    ] as $name => $copy) {
        $fields[] = ['key' => 'field_edp_' . $name, 'name' => $name, 'label' => $copy[0], 'type' => 'text', 'default_value' => $copy[1],
            'instructions' => $name === 'heading' ? 'Для польської, німецької, французької та італійської сторінок використовується погоджений SEO-заголовок зі старого поля «Заголовок блоку зі списком». На інших сторінках використовується цей заголовок.' : ''];
    }
    $fields[] = ['key' => 'field_edp_banks', 'name' => 'banks', 'label' => 'Зображення банків-партнерів', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => 'Необов’язково. Завантажте погоджене зображення логотипів.'];
    acf_add_local_field_group([
        'key' => 'group_educado_pricing', 'title' => 'Блок цін — поточний / Ціни v2', 'menu_order' => -10, 'position' => 'acf_after_title',
        'fields' => [
            ['key' => 'field_edp_visual', 'name' => 'pricing_visual', 'label' => 'Який вигляд блоку використовувати', 'type' => 'select', 'choices' => ['legacy' => 'Поточний блок', 'v2' => 'Ціни v2'], 'default_value' => 'legacy',
                'instructions' => 'На проді новий блок закритий для відвідувачів до окремого випуску. Виберіть «Ціни v2», щоб відкрити поля для заповнення. Поточні ціни зберігаються у старих полях.'],
            ['key' => 'field_edp_pricing_v2', 'name' => 'pricing_v2', 'label' => 'Налаштування Ціни v2', 'type' => 'group', 'layout' => 'block', 'sub_fields' => $fields,
                'conditional_logic' => [[['field' => 'field_edp_visual', 'operator' => '==', 'value' => 'v2']]]],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'template/template-language.php']]],
    ]);
});

add_filter('acf/validate_value/key=field_edp_pricing_v2', function ($valid, $value) {
    if ($valid !== true) {
        return $valid;
    }
    $model = educado_pricing_model($value);
    return $model['errors'] ? implode(' ', $model['errors']) : true;
}, 10, 2);

foreach (['course', 'duration', 'level'] as $dimension) {
    add_filter('acf/load_field/key=field_edp_' . $dimension . '_ref', function ($field) use ($dimension) {
        $post_id = isset($_GET['post']) ? absint($_GET['post']) : get_the_ID();
        $value = $post_id ? get_post_meta($post_id, 'pricing_v2_' . $dimension . '_options', true) : 0;
        for ($i = 0; $i < (int) $value; $i++) {
            $base = 'pricing_v2_' . $dimension . '_options_' . $i . '_' . $dimension;
            $id = get_post_meta($post_id, $base . '_id', true);
            $label = get_post_meta($post_id, $base . '_label', true);
            if ($id && $label) {
                $field['choices'][$id] = $label;
            }
        }
        $rows = (int) get_post_meta($post_id, 'pricing_v2_price_rows', true);
        for ($i = 0; $i < $rows; $i++) {
            $id = get_post_meta($post_id, 'pricing_v2_price_rows_' . $i . '_' . $dimension . '_ref', true);
            if ($id && !isset($field['choices'][$id])) {
                $field['choices'][$id] = 'Видалений варіант — ціни збережено';
            }
        }
        return $field;
    });
}

add_action('acf/input/admin_enqueue_scripts', function () {
    $base = '/assets/js/libraries/language-pricing-admin.js';
    wp_enqueue_script('educado-pricing-admin', get_template_directory_uri() . $base, ['acf-input'], educado_asset_version(get_template_directory() . $base), true);
    wp_localize_script('educado-pricing-admin', 'educadoPricingAdmin', ['inputLimit' => (int) ini_get('max_input_vars')]);
    wp_add_inline_style('acf-input', '.edp-option-id,[data-key^="field_edp_"][data-key$="_id"]{display:none!important}.edp-tools{padding:12px 0}.edp-tools p{margin:8px 0}.edp-price-table{overflow-x:auto}.edp-price-table>.acf-input>.acf-repeater{min-width:900px}');
});

add_action('wp_enqueue_scripts', function () {
    if (is_page_template('template/template-language.php') && educado_pricing_v2_visible()) {
        $style = '/assets/css/pricing.min.css';
        wp_enqueue_style('educado-pricing', get_template_directory_uri() . $style, ['ed-main'], educado_asset_version(get_template_directory() . $style));
        $base = '/assets/js/libraries/language-pricing.js';
        wp_enqueue_script('educado-pricing', get_template_directory_uri() . $base, ['ed-main'], educado_asset_version(get_template_directory() . $base), true);
    }
}, 25);

add_action('post_submitbox_misc_actions', function ($post) {
    if (get_page_template_slug($post->ID) !== 'template/template-language.php' || !current_user_can('edit_post', $post->ID)) {
        return;
    }
    $url = wp_nonce_url(add_query_arg('ed_pricing_preview', '1', get_permalink($post)), 'ed-pricing-' . $post->ID);
    echo '<div class="misc-pub-section"><a target="_blank" rel="noopener" href="' . esc_url($url) . '">Переглянути збережені Ціни v2</a><p>Оберіть «Ціни v2» і збережіть сторінку. На проді цей перегляд доступний лише редактору.</p></div>';
});

add_action('template_redirect', function () {
    if (isset($_GET['ed_pricing_preview']) && current_user_can('edit_post', get_queried_object_id())) {
        nocache_headers();
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        do_action('litespeed_control_set_nocache', 'Pricing editor preview');
    }
});
