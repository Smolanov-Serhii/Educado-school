<?php

$data = get_fields();

get_header();
?>

    <main class="ed-blog-single">
        <?php if ($templates_list = $data['shablon']) : ?>
                <?php
                $courses_count = count($templates_list);
                foreach ($templates_list as $item_id) {
                    $templatename = $item_id['obert_tip_shablonu'];
                    get_template_part('parts/pt-cards/content', $templatename , array(
                        'data' => $item_id,
                    ));
                }
                ?>
        <?php endif; ?>
        <?php get_template_part('parts/content-blog-callback'); ?>
    </main>

<?php get_footer(); ?>