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
echo "PASS: group readiness, stable format binding, optional previous price, invalid/draft protection and unchanged package prices.\n";
