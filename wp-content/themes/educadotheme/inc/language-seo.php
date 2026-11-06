<?php
/** Editable SEO content for the six language landing pages. */
function educado_has_language_headings() {
    return is_page(['anhliyska', 'ispanska', 'frantsuzka', 'nimetska', 'polska', 'italiyska']);
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    $locations = [];
    foreach (['anhliyska', 'ispanska', 'frantsuzka', 'nimetska', 'polska', 'italiyska'] as $slug) {
        $page = get_page_by_path($slug);
        if ($page) {
            $locations[] = [['param' => 'page', 'operator' => '==', 'value' => (string) $page->ID]];
        }
    }
    if (!$locations) {
        return;
    }
    acf_add_local_field_group([
        'key' => 'group_educado_language_seo',
        'title' => 'SEO: заголовки мовної сторінки',
        'fields' => [
            ['key' => 'field_educado_reviews_heading', 'label' => 'Заголовок відгуків', 'name' => 'seo_reviews_heading', 'type' => 'text'],
            ['key' => 'field_educado_beginner_heading', 'label' => 'Заголовок блоку для початківців', 'name' => 'seo_beginner_heading', 'type' => 'text'],
            ['key' => 'field_educado_beginner_content', 'label' => 'Текст блоку для початківців', 'name' => 'seo_beginner_content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0, 'instructions' => 'Блок показується лише коли заповнені заголовок і текст. Для французької не дублювати окрему сторінку для початківців; польський блок потребує погодження.'],
        ],
        'location' => $locations,
    ]);
});
