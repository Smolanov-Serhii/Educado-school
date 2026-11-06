<?php
if (!educado_has_language_headings()) {
    return;
}
$heading = get_field('seo_beginner_heading');
$content = get_field('seo_beginner_content');
if (!$heading || !trim(wp_strip_all_tags($content ?: ''))) {
    return;
}
?>
<section class="ed-home-language-beginners">
    <div class="container">
        <h2 class="h1"><?= esc_html($heading); ?></h2>
        <div class="language-beginners-content"><?= wp_kses_post($content); ?></div>
    </div>
</section>
