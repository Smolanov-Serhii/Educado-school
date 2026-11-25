<?php
/** Restore only course repeaters from a scoped export; other store fields stay untouched. */
$export = json_decode(file_get_contents($args[0] ?? ''), true);
if (!is_array($export)) throw new RuntimeException('Invalid scoped catalog export.');
$prepared = [];
foreach ([895, 943] as $id) {
    if (get_page_template_slug($id) !== 'template/template-market.php') throw new RuntimeException('Unexpected page template.');
    $groups = $export[$id]['data']['perelk_propozicj'] ?? null;
    if (!is_array($groups)) throw new RuntimeException('Missing original courses.');
    foreach ($groups as &$group) foreach ($group['propozic_po_rvnyam'] as &$level) foreach ($level['perelk_samih_propozicj'] as &$item) {
        $url = str_replace(['https://educado.school', 'http://educado.school'], untrailingslashit(home_url()), $item['zobrazhennya']);
        $attachment = attachment_url_to_postid($url);
        if (!$attachment || !wp_attachment_is_image($attachment)) throw new RuntimeException('Original image not found: ' . $url);
        $item['zobrazhennya'] = $attachment;
    }
    unset($group, $level, $item);
    $prepared[$id] = $groups;
}
// Resolve every image before making the first write.
foreach ($prepared as $id => $groups) update_field('perelk_propozicj', $groups, $id);
echo "Restored both original store catalogs, including names, facts, prices, covers and destinations.\n";
