<?php
/** Explicitly approved local reference content. wp eval-file scripts/seed-market-reference.php <attachment-id> */
if (wp_get_environment_type() !== 'local') throw new RuntimeException('Reference content is for local review only.');
$attachment_id = (int) ($args[0] ?? 0);
if (!$attachment_id || !wp_attachment_is_image($attachment_id)) throw new RuntimeException('Pass the imported reference cover attachment ID.');
$groups = json_decode(file_get_contents(__DIR__ . '/fixtures/market-reference.json'), true);
if (!is_array($groups)) throw new RuntimeException('Invalid reference fixture.');
foreach ($groups as &$group) foreach ($group['propozic_po_rvnyam'] as &$level) foreach ($level['perelk_samih_propozicj'] as &$item) $item['zobrazhennya'] = $attachment_id;
unset($group, $level, $item);
foreach ([895, 943] as $id) {
    if (get_page_template_slug($id) !== 'template/template-market.php') throw new RuntimeException('Wrong store page.');
    update_field('perelk_propozicj', $groups, $id);
    update_field('field_edm_details_label', 'Детальніше', $id);
}
echo "Filled both local stores from the approved reference. Business B1 prices remain empty.\n";
