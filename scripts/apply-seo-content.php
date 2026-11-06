<?php
/** Run with wp eval-file; SEO_CONTENT_FILE points to the reviewed payload. */
$path = getenv('SEO_CONTENT_FILE');
if (!$path || !is_readable($path)) {
    WP_CLI::error('Missing readable SEO_CONTENT_FILE.');
}
$changes = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
// Validate every existing value before the first database write.
foreach ($changes as $change) {
    $page = get_post($change['id']);
    if (!$page || $page->post_name !== $change['slug'] || get_page_template_slug($page) !== $change['template']) {
        WP_CLI::error('Page/template mismatch: ' . $change['slug']);
    }
    foreach ($change['updates'] as $key => $value) {
        $current = get_field($key, $page->ID);
        if ($current !== $value['before'] && $current !== $value['after']) {
            WP_CLI::error('Content changed since inspection: ' . $change['slug'] . '/' . $key);
        }
    }
    foreach ($change['new'] as $key => $value) {
        if (!acf_get_field($key)) {
            WP_CLI::error('ACF field not registered: ' . $key);
        }
        $current = get_field($key, $page->ID, false);
        if ($current && $current !== $value) {
            WP_CLI::error('New field already contains other content: ' . $key);
        }
    }
}
if (getenv('SEO_APPLY') !== '1') {
    WP_CLI::success('Preflight passed; set SEO_APPLY=1 to apply.');
    return;
}
foreach ($changes as $change) {
    foreach ($change['updates'] as $key => $value) {
        if (is_array($value['after'])) {
            // Repeater values are formatted for display; links and other
            // structured fields must retain their raw storage representation.
            foreach ($value['after'] as $index => $row) {
                foreach ($row as $name => $content) {
                    if ($content !== $value['before'][$index][$name]) {
                        update_sub_field([$key, $index + 1, $name], $content, $change['id']);
                    }
                }
            }
        } else {
            update_field($key, $value['after'], $change['id']);
        }
        $field = acf_get_field($key);
        acf_flush_value_cache($change['id'], $field['name']);
        if (get_field($key, $change['id']) !== $value['after']) {
            WP_CLI::error('Readback mismatch: ' . $key);
        }
    }
    foreach ($change['new'] as $key => $value) {
        update_field($key, $value, $change['id']);
        // WYSIWYG formatting adds paragraphs/newlines; compare stored raw value.
        if (get_field($key, $change['id'], false) !== $value) {
            WP_CLI::error('New field readback mismatch: ' . $key);
        }
    }
    clean_post_cache($change['id']);
    WP_CLI::log('Updated and verified: ' . $change['slug']);
}
WP_CLI::success('Six language pages updated through ACF.');
