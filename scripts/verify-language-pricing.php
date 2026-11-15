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
echo 'PASS: 12 combinations, stable references, drafts, deletion, duplicates, availability, decimal prices and native ACF POST keys.' . PHP_EOL;
