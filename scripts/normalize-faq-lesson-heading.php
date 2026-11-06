<?php
/** Correct only the four FAQ question titles found by the production spec audit. */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['educado.school', 'educado-school.loc'], true)) {
    WP_CLI::error('Unexpected site: refusing FAQ edits.');
}
$languages = [499 => 'французької', 487 => 'німецької', 495 => 'польської', 492 => 'італійської'];
$plan = [];
foreach ($languages as $id => $language) {
    $field = get_field_object('pitannya_vdpovd_bloku', $id);
    $rows = get_field('pitannya_vdpovd_bloku', $id);
    $before = 'Скільки триває онлайн урок з ' . $language . '?';
    $after = 'Скільки триває онлайн-урок з ' . $language . '?';
    $matches = [];
    foreach ($rows ?: [] as $index => $row) {
        if (in_array(trim($row['pitannya']), [$before, $after], true)) {
            $matches[] = $index;
        }
    }
    if (!$field || count($matches) !== 1) {
        WP_CLI::error('Unexpected FAQ content on page ' . $id);
    }
    $plan[] = compact('id', 'field', 'rows', 'after', 'matches');
}
foreach ($plan as $change) {
    $id = $change['id'];
    $index = $change['matches'][0];
    $expected = $change['rows'];
    $expected[$index]['pitannya'] = $change['after'];
    update_sub_field([$change['field']['key'], $index + 1, 'pitannya'], $change['after'], $id);
    acf_flush_value_cache($id, 'pitannya_vdpovd_bloku');
    if (get_field('pitannya_vdpovd_bloku', $id) !== $expected) {
        WP_CLI::error('FAQ readback mismatch on page ' . $id);
    }
    WP_CLI::log('Verified FAQ title and unchanged answers: ' . $id);
}
WP_CLI::success('Four FAQ titles match the supplied specification.');
