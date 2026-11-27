<?php
/** Isolated group-offer regression: php scripts/verify-group-pricing.php */
function add_action(...$args) {}
function add_filter(...$args) {}
function wp_get_environment_type() { return 'production'; }
function get_the_ID() { return 504; }
function current_user_can(...$args) { return false; }
function get_field($name) { return $GLOBALS['group_test_data'][$name] ?? null; }
require dirname(__DIR__) . '/wp-content/themes/educadotheme/inc/language-pricing.php';
function group_check($condition, $message) { if (!$condition) throw new RuntimeException($message); }
$individual = 'opt_0000000000000001';
$group_id = 'opt_0000000000000002';
$pricing = ['format_options' => [['format_id' => $individual, 'format_label' => 'Individual'], ['format_id' => $group_id, 'format_label' => 'Group']], 'price_rows' => [
    ['format_ref' => $individual, 'availability' => 'available', 'price_8' => '559', 'price_24' => '529', 'price_48' => '499'],
]];
$snapshot = serialize($pricing);
$offer = ['enabled' => 1, 'format_ref' => $group_id, 'module_price' => '5600', 'course_price' => '15120', 'previous_price' => '16800',
    'condition_lessons' => '16 lessons', 'condition_students' => '3–8 students', 'condition_schedule' => 'Twice a week', 'module_title' => 'Module', 'course_title' => 'Course'];
group_check(educado_group_pricing_model($offer, $pricing)['ready'], 'Valid group offer not ready');
group_check(count(educado_group_pricing_model($offer, $pricing)['offers']) === 1, 'Legacy offer missing');
$level_offer = $offer;
$level_offer['levels'] = [
    ['label' => 'Початковий', 'module_price' => '5600', 'course_price' => '15120', 'previous_price' => '16800'],
    ['label' => 'Просунутий', 'module_price' => '6400', 'course_price' => '18000', 'previous_price' => '', 'condition_schedule' => 'Three times a week', 'trial_note' => 'Level-specific trial'],
];
$level_snapshot = serialize($level_offer);
$level_model = educado_group_pricing_model($level_offer, $pricing);
group_check($level_model['ready'] && $level_model['has_levels'] && count($level_model['offers']) === 2, 'Level offers not ready');
group_check($level_model['offers'][0]['course_price'] === '15120' && $level_model['offers'][1]['course_price'] === '18000', 'Level prices mixed');
group_check($level_model['offers'][0]['condition_schedule'] === $offer['condition_schedule'], 'Shared conditions not inherited');
group_check($level_model['offers'][1]['condition_schedule'] === 'Three times a week' && $level_model['offers'][1]['trial_note'] === 'Level-specific trial', 'Level conditions lost');
group_check($level_model['offers'][1]['previous_price'] === '', 'Previous price inherited into level');
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return esc_html($value); }
function checked($value, $expected) { if ($value === $expected) echo 'checked="checked"'; }
function number_format_i18n($value, $decimals = 0) { return number_format($value, $decimals, '.', ' '); }
$html = (function ($group_model) {
    $group_initial = true; $block_id = 'test-pricing';
    ob_start();
    include dirname(__DIR__) . '/wp-content/themes/educadotheme/parts/pricing-group.php';
    return ob_get_clean();
})($level_model);
$doc = new DOMDocument(); @$doc->loadHTML('<?xml encoding="UTF-8">' . $html);
$xpath = new DOMXPath($doc);
group_check($xpath->query('//input[@data-group-level]')->length === 2, 'Level selectors not rendered');
group_check($xpath->query('//*[@data-group-level-panel="0" and not(@hidden)]')->length === 1, 'Initial offer not visible');
group_check($xpath->query('//*[@data-group-level-panel="1" and @hidden]')->length === 1, 'Second offer not initially hidden');
group_check(strpos($html, '18 000') !== false && strpos($html, '15 120') !== false, 'Independent prices missing from rendered panels');
group_check(serialize($level_offer) === $level_snapshot && serialize($pricing) === $snapshot, 'Existing data mutated');
$level_offer['module_price'] = ''; $level_offer['course_price'] = '';
group_check(educado_group_pricing_model($level_offer, $pricing)['ready'], 'Unused shared prices required for levels');
$level_offer['levels'][0]['label'] = 'Перейменований';
group_check(educado_group_pricing_model($level_offer, $pricing)['offers'][0]['module_price'] === '5600', 'Rename changed level prices');
$invalid_level = $level_offer; unset($invalid_level['levels'][0]['module_price']);
group_check(!educado_group_pricing_model($invalid_level, $pricing)['ready'], 'Missing level price inherited');
$invalid_level = $level_offer; $invalid_level['levels'][1]['label'] = $invalid_level['levels'][0]['label'];
group_check(!educado_group_pricing_model($invalid_level, $pricing)['ready'], 'Duplicate level accepted');
$posted = ['field_edg_enabled' => 1, 'field_edg_levels' => ['row-0' => ['field_edg_level_label' => 'A1', 'field_edg_level_module_price' => '5600']]];
$normalized = educado_group_normalize($posted);
group_check($normalized['levels']['row-0']['label'] === 'A1' && $normalized['levels']['row-0']['module_price'] === '5600', 'Native ACF repeater submission not normalized');
$no_levels = $offer; $no_levels['levels'] = false;
group_check(educado_group_pricing_model($no_levels, $pricing)['ready'], 'Empty ACF repeater broke legacy offer');
group_check(serialize($pricing) === $snapshot, 'Existing prices changed');
$renamed = $pricing; $renamed['format_options'][1]['format_label'] = 'Renamed';
group_check(educado_group_pricing_model($offer, $renamed)['ready'], 'Rename lost format binding');
foreach (['module_price' => '', 'course_price' => '0', 'previous_price' => '12000', 'format_ref' => $individual . 'x', 'condition_lessons' => ''] as $name => $bad) {
    $invalid = $offer; $invalid[$name] = $bad;
    group_check(!educado_group_pricing_model($invalid, $pricing)['ready'], 'Invalid field accepted: ' . $name);
}
$disabled = $pricing; $disabled['format_enabled'] = 0;
group_check(!educado_group_pricing_model($offer, $disabled)['ready'], 'Disabled format exposed');
$off = $offer; $off['enabled'] = 0;
group_check(!educado_group_pricing_model($off, $pricing)['ready'], 'Disabled group offer exposed');
$offer['previous_price'] = '';
group_check(educado_group_pricing_model($offer, $pricing)['ready'], 'Optional previous price required');
group_check(educado_pricing_model($pricing)['prices'][$individual][8] === '559', 'Package price changed');
$group_only = $pricing; $group_only['price_rows'] = [];
$GLOBALS['group_test_data'] = ['pricing_visual' => 'v2', 'pricing_v2' => $group_only, 'pricing_group' => $offer];
group_check(educado_pricing_v2_visible(), 'Ready group-only page hidden');
$GLOBALS['group_test_data']['pricing_group']['enabled'] = 0;
group_check(!educado_pricing_v2_visible(), 'Empty disabled group published');
echo "PASS: legacy and per-level group offers, ACF submission, independent prices/conditions, invalid input protection and unchanged package prices.\n";
