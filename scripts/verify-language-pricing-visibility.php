<?php
/** Isolated pricing visibility checks. Run with PHP CLI, without booting WordPress. */
function add_action(...$args) {}
function add_filter(...$args) {}
function wp_get_environment_type() { return 'production'; }
function get_the_ID() { return $GLOBALS['page_id']; }
function current_user_can(...$args) { return false; }
function get_field($name) { return $GLOBALS['data'][$name] ?? null; }
require dirname(__DIR__) . '/wp-content/themes/educadotheme/inc/language-pricing.php';
function release_check($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}
$v = [];
foreach (['course', 'duration', 'level'] as $dimension) {
    $v[$dimension . '_options'] = [[$dimension . '_id' => 'opt_0000000000000001', $dimension . '_label' => 'Test']];
}
$v['course_options'][] = ['course_id' => 'opt_0000000000000002', 'course_label' => 'Unfilled'];
$v['price_rows'] = [['course_ref' => 'opt_0000000000000001', 'duration_ref' => 'opt_0000000000000001', 'level_ref' => 'opt_0000000000000001', 'availability' => 'available', 'price_8' => '559', 'price_24' => '529', 'price_48' => '499']];
$GLOBALS['data'] = ['pricing_visual' => 'v2', 'pricing_v2' => $v];
$GLOBALS['page_id'] = 504;
release_check(educado_pricing_v2_visible(), 'Filled rows hidden by an incomplete matrix');
$GLOBALS['page_id'] = 487;
release_check(educado_pricing_v2_visible(), 'Automatic visibility requires page-specific authorization');
$GLOBALS['page_id'] = 495;
$GLOBALS['data']['pricing_v2']['price_rows'] = [];
release_check(!educado_pricing_v2_visible(), 'Empty page was published');
$GLOBALS['data']['pricing_v2'] = $v;
$GLOBALS['data']['pricing_v2']['price_rows'][0]['price_8'] = '0';
release_check(!educado_pricing_v2_visible(), 'Invalid price was published');
$GLOBALS['data']['pricing_v2'] = $v;
$GLOBALS['data']['pricing_visual'] = 'legacy';
release_check(!educado_pricing_v2_visible(), 'Legacy visual selection ignored');
$GLOBALS['data']['pricing_visual'] = 'v2';
$GLOBALS['data']['pricing_v2']['price_rows'][0]['availability'] = 'draft';
release_check(!educado_pricing_v2_visible(), 'Draft-only page was published');
$GLOBALS['data']['pricing_v2'] = $v;
$GLOBALS['data']['pricing_v2']['price_rows'][] = $v['price_rows'][0];
release_check(!educado_pricing_v2_visible(), 'Duplicate matrix was published');
echo 'PASS: automatic partial pricing, empty/invalid/draft protection, legacy selection and duplicate detection.' . PHP_EOL;
