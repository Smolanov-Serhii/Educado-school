<?php
/** Run with wp eval-file scripts/verify-language-pricing.php. Does not write data. */
function pricing_check($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
$value = [];
foreach (['course' => 2, 'duration' => 3, 'level' => 2] as $dimension => $count) {
    for ($i = 1; $i <= $count; $i++) {
        $value[$dimension . '_options'][] = [$dimension . '_id' => 'opt_' . str_pad(dechex($i), 16, '0', STR_PAD_LEFT), $dimension . '_label' => $dimension . ' ' . $i];
    }
}
foreach ($value['course_options'] as $course) {
    foreach ($value['level_options'] as $level) {
        foreach ($value['duration_options'] as $duration) {
            $value['price_rows'][] = ['course_ref' => $course['course_id'], 'duration_ref' => $duration['duration_id'], 'level_ref' => $level['level_id'], 'availability' => 'available', 'price_8' => '559', 'price_24' => '529.50', 'price_48' => '499'];
        }
    }
}
$model = educado_pricing_model($value);
pricing_check($model['ready'] && count($model['prices']) === 12, 'Expected 12 complete combinations');
$renamed = $value;
$renamed['course_options'][0]['course_label'] = 'Перейменований курс';
$renamed['duration_options'] = array_reverse($renamed['duration_options']);
pricing_check(educado_pricing_model($renamed)['prices'] === $model['prices'], 'Rename/reorder changed prices');
$duplicate = $value;
$duplicate['price_rows'][] = $duplicate['price_rows'][0];
pricing_check(educado_pricing_model($duplicate)['errors'], 'Duplicate combination accepted');
$added = $value;
$added['level_options'][] = ['level_id' => 'opt_0000000000000003', 'level_label' => 'Новий рівень'];
pricing_check(!educado_pricing_model($added)['ready'], 'New unfilled option marked ready');
$deleted = $value;
array_pop($deleted['level_options']);
pricing_check(count(educado_pricing_model($deleted)['prices']) === 6, 'Removed option leaked prices');
$draft = $value;
$draft['price_rows'][0]['availability'] = 'draft';
$draft['price_rows'][0]['price_8'] = '';
pricing_check(!educado_pricing_model($draft)['errors'] && count(educado_pricing_model($draft)['prices']) === 11, 'Draft price leaked or could not be saved');
$unavailable = $draft;
$unavailable['price_rows'][0]['availability'] = 'unavailable';
pricing_check(educado_pricing_model($unavailable)['ready'] && count(educado_pricing_model($unavailable)['prices']) === 11, 'Unavailable row blocked readiness or exposed price');
foreach (['', '0', '-5', 'NaN', '1e3', '2.001', '1,50'] as $invalid) {
    $bad = $value;
    $bad['price_rows'][0]['price_8'] = $invalid;
    pricing_check(educado_pricing_model($bad)['errors'] && !educado_pricing_model($bad)['ready'], 'Invalid available price accepted');
}
$post = [];
foreach ($value as $name => $rows) {
    foreach ($rows as $i => $row) {
        foreach ($row as $key => $entry) {
            $post['field_edp_' . $name]['row-' . $i]['field_edp_' . $key] = $entry;
        }
    }
}
pricing_check(educado_pricing_model($post)['prices'] === $model['prices'], 'Native ACF POST keys not supported');
pricing_check(!educado_pricing_model([])['ready'], 'Empty group marked ready');

$acf_empty = $value;
$acf_empty['format_options'] = false;
pricing_check(educado_pricing_model($acf_empty)['prices'] === $model['prices'] && !educado_pricing_model($acf_empty)['errors'], 'Native ACF false repeater broke legacy prices');
$acf_empty = ['course_options' => false, 'duration_options' => false, 'level_options' => false, 'format_options' => false, 'price_rows' => false];
pricing_check(!educado_pricing_model($acf_empty)['errors'] && !educado_pricing_model($acf_empty)['prices'], 'Native ACF empty group rejected');
$with_format = $value;
$first_format = 'opt_1000000000000001';
$second_format = 'opt_1000000000000002';
$with_format['format_options'] = [
    ['format_id' => $first_format, 'format_label' => 'Індивідуально'],
    ['format_id' => $second_format, 'format_label' => 'Парні'],
];
$format_model = educado_pricing_model($with_format);
pricing_check(count($format_model['prices']) === 12 && !$format_model['errors'], 'Adding format lost legacy prices');
foreach ($model['prices'] as $key => $prices) {
    pricing_check($format_model['prices'][$key . '|' . $first_format] === $prices, 'Legacy price was not assigned to first format');
    pricing_check(!isset($format_model['prices'][$key . '|' . $second_format]), 'Individual price leaked into paired format');
}
$paired = $with_format['price_rows'][0];
$paired['format_ref'] = $second_format;
$paired['price_8'] = '333';
$with_format['price_rows'][] = $paired;
$paired_model = educado_pricing_model($with_format);
pricing_check(count($paired_model['prices']) === 13 && !$paired_model['errors'], 'Independent paired price collided');
$key = implode('|', [$paired['course_ref'], $paired['duration_ref'], $paired['level_ref'], $second_format]);
pricing_check($paired_model['prices'][$key][8] === '333', 'Wrong paired price');
$disabled = $value;
$disabled['duration_enabled'] = '0';
pricing_check(educado_pricing_model($disabled)['errors'], 'Disabling an axis silently chose conflicting prices');
$disabled['price_rows'] = array_values(array_filter($disabled['price_rows'], function ($row) use ($value) {
    return $row['duration_ref'] === $value['duration_options'][0]['duration_id'];
}));
$disabled_model = educado_pricing_model($disabled);
pricing_check(count($disabled_model['prices']) === 4 && !isset($disabled_model['options']['duration']) && !$disabled_model['errors'], 'Disabled axis still affects combinations');
$disabled['duration_enabled'] = 1;
pricing_check(isset(educado_pricing_model($disabled)['options']['duration']), 'Re-enabling axis lost its options');
$no_duration = $disabled;
$no_duration['duration_options'] = [];
pricing_check(count(educado_pricing_model($no_duration)['prices']) === 4, 'Empty axis still affects combinations');
$no_axes = ['price_rows' => [$value['price_rows'][0]]];
$no_axes_model = educado_pricing_model($no_axes);
pricing_check($no_axes_model['ready'] && isset($no_axes_model['prices']['']), 'Pricing without any switches failed');
echo 'PASS: legacy combinations, optional format, independent paired prices, disabled/empty axes, collision protection and pricing without switches.' . PHP_EOL;
