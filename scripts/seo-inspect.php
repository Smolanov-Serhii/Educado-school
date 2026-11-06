<?php
$result = [];
foreach (['anhliyska','ispanska','frantsuzka','nimetska','polska','italiyska'] as $slug) {
    $page = get_page_by_path($slug);
    $fields = get_fields($page->ID);
    $selected = [];
    foreach (['section_1_zagolovok','how_stady_zagolovok','zagolovok_nad_perelkom','zagolovok_bloku_rozvdnih_stornok','elementi_bloku_rozvdnih_stornok','how_start_zagolovok','how_start_kartka_1','how_start_kartka_2','how_start_kartka_3','teachers_zagolovok','zaglovok_bloku_faq','pitannya_vdpovd_bloku','questions_zagolovok','taba','zagolovok_bloku_z_spiskom'] as $name) {
        $field = get_field_object($name, $page->ID);
        $selected[$name] = ['key' => $field['key'] ?? null, 'value' => $fields[$name] ?? null];
    }
    $result[$slug] = ['id' => $page->ID, 'template' => get_page_template_slug($page->ID), 'fields' => $selected];
}
echo wp_json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
